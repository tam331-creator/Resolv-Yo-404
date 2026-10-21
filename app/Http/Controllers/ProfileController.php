<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use App\Models\Ticket;
use App\Models\User;
use App\Helpers\ActivityHelper;
use Carbon\Carbon;

class ProfileController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $filter = $request->get('filter', 'this_year');

        // 1. Penapis Tarikh untuk Statistik
        $ticketQuery = Ticket::query();
        if ($filter === 'this_month') {
            $ticketQuery->whereYear('created_at', Carbon::now()->year)
                        ->whereMonth('created_at', Carbon::now()->month);
        } else {
            $ticketQuery->whereYear('created_at', Carbon::now()->year);
        }

        // Pengiraan Aktiviti Ringkasan (My Activity)
        $createdCount = (clone $ticketQuery)->where('user_id', $user->id)->count();
        $resolvedCount = (clone $ticketQuery)->where(function($q) use ($user) {
                            $q->where('user_id', $user->id)
                              ->orWhere('assigned_to', $user->id);
                        })->whereIn('status', ['Closed', 'Resolved'])->count();

        $avgRating = (clone $ticketQuery)->where(function($q) use ($user) {
                        $q->where('user_id', $user->id)
                          ->orWhere('assigned_to', $user->id);
                    })
                    ->whereNotNull('rating')
                    ->where('rating', '>', 0)
                    ->avg('rating') ?? 0;

        $myActivity = [
            'created_tickets'  => $createdCount,
            'resolved_tickets' => $resolvedCount,
            'average_rating'   => round((float)$avgRating, 1),
            'filter'           => $filter,
        ];

        // 2. Recent Activity (3 Terkini di Ringkasan Kad Utama)
        $recentActivities = Ticket::where('user_id', $user->id)
            ->orWhere('assigned_to', $user->id)
            ->latest('updated_at')
            ->take(3)
            ->get()
            ->map(function($ticket) {
                if ($ticket->status === 'Closed' || $ticket->status === 'Resolved') {
                    $title = "Ticket #{$ticket->id} marked as " . $ticket->status;
                    $emoji = ActivityHelper::getEmoji('ticket_resolved');
                } elseif ($ticket->created_at->equalTo($ticket->updated_at)) {
                    $title = "Created Ticket #{$ticket->id}";
                    $emoji = ActivityHelper::getEmoji('ticket_created');
                } else {
                    $title = "Updated Ticket #{$ticket->id}";
                    $emoji = ActivityHelper::getEmoji('ticket_updated');
                }

                return [
                    'title'   => $title,
                    'subject' => $ticket->subject ?? $ticket->title ?? 'No Subject',
                    'time'    => $ticket->updated_at->diffForHumans(),
                    'emoji'   => $emoji
                ];
            });

        // 3. SENARAI PENGGUNA & JABATAN UNTUK FILTER ADMIN
        $usersList = collect();
        $departmentsList = collect();

        if ($user->role === 'admin') {
            $hasUserDept = Schema::hasColumn('users', 'department');
            $userSelectCols = ['id', 'name', 'role'];
            if ($hasUserDept) {
                $userSelectCols[] = 'department';
            }

            $usersList = User::select($userSelectCols)->orderBy('name')->get();

            // Ambil Jabatan daripada jadual 'departments' DAN jadual 'users'
            $deptFromUsers = $hasUserDept ? User::whereNotNull('department')->where('department', '!=', '')->pluck('department') : collect();
            $deptFromTable = Schema::hasTable('departments') ? DB::table('departments')->pluck('name') : collect();

            $departmentsList = $deptFromUsers->concat($deptFromTable)
                ->map(fn($d) => trim($d))
                ->filter()
                ->unique()
                ->values();
        }

        // 4. OVERALL SYSTEM ACTIVITIES (STRICT RBAC & SPECIFIC FILTER CATEGORIES)
        $rawActivities = collect();
        $now = Carbon::now();

        // A. Data Aktiviti Tiket & Replies
        $ticketQuery = Ticket::with(['user', 'assignedTechnician']);
        if ($user->role !== 'admin') {
            $ticketQuery->where(function($q) use ($user) {
                $q->where('user_id', $user->id)
                  ->orWhere('assigned_to', $user->id);
            });
        }
        $tickets = $ticketQuery->get();

        foreach ($tickets as $ticket) {
            $tUser = $ticket->user;
            $userName = $tUser->name ?? 'System User';
            $userRole = strtolower($tUser->role ?? 'staff');
            $userDept = $tUser->department ?? 'General';
            $cDate = $ticket->created_at;
            $uDate = $ticket->updated_at;

            // Tempoh Masa (Period Tag)
            $period = 'older';
            if ($cDate->isToday()) {
                $period = 'today';
            } elseif ($cDate->isSameWeek($now)) {
                $period = 'this_week';
            } elseif ($cDate->isSameMonth($now)) {
                $period = 'this_month';
            }

            // Ticket Created Event
            $rawActivities->push([
                'category'   => 'tickets',
                'user_name'  => $userName,
                'user_role'  => $userRole,
                'department' => $userDept,
                'period'     => $period,
                'timestamp'  => $cDate,
                'datetime'   => $cDate->format('d M Y, h:i A'),
                'title'      => "Created Ticket #{$ticket->id}",
                'subject'    => $ticket->subject ?? $ticket->title ?? '',
                'emoji'      => ActivityHelper::getEmoji('ticket_created'),
                'is_closed'  => false,
            ]);

            // Jika tiket ditugaskan kepada Juruteknik (Contoh: Bee)
            if ($ticket->assignedTechnician && $ticket->assigned_to != $ticket->user_id) {
                $tech = $ticket->assignedTechnician;
                $rawActivities->push([
                    'category'   => 'tickets',
                    'user_name'  => $tech->name,
                    'user_role'  => strtolower($tech->role ?? 'technician'),
                    'department' => $tech->department ?? 'IT Department',
                    'period'     => $period,
                    'timestamp'  => $cDate,
                    'datetime'   => $cDate->format('d M Y, h:i A'),
                    'title'      => "Assigned Ticket #{$ticket->id}",
                    'subject'    => $ticket->subject ?? $ticket->title ?? '',
                    'emoji'      => '🎫',
                    'is_closed'  => false,
                ]);
            }

            // Rating Event (Jika ada rating)
            if ($ticket->rating && $ticket->rating > 0) {
                $rawActivities->push([
                    'category'   => 'ratings',
                    'user_name'  => $userName,
                    'user_role'  => $userRole,
                    'department' => $userDept,
                    'period'     => $period,
                    'timestamp'  => $uDate,
                    'datetime'   => $uDate->format('d M Y, h:i A'),
                    'title'      => "Rated Ticket #{$ticket->id} ({$ticket->rating}/5 ⭐)",
                    'subject'    => $ticket->feedback ?? 'User feedback submitted',
                    'emoji'      => '⭐',
                    'is_closed'  => false,
                ]);
            }

            // Ticket Reply / Update / Closed Event
            if (!$cDate->equalTo($uDate)) {
                $uPeriod = 'older';
                if ($uDate->isToday()) {
                    $uPeriod = 'today';
                } elseif ($uDate->isSameWeek($now)) {
                    $uPeriod = 'this_week';
                } elseif ($uDate->isSameMonth($now)) {
                    $uPeriod = 'this_month';
                }

                $isClosed = in_array($ticket->status, ['Closed', 'Resolved']);
                $actCategory = $isClosed ? 'tickets' : 'replies';
                $actTitle = $isClosed ? "Ticket #{$ticket->id} Closed" : "Replied to Ticket #{$ticket->id}";
                $actEmoji = $isClosed ? ActivityHelper::getEmoji('ticket_resolved') : ActivityHelper::getEmoji('ticket_replied');

                $actorObj = ($ticket->assignedTechnician) ? $ticket->assignedTechnician : $tUser;
                $actorName = $actorObj ? $actorObj->name : $userName;
                $actorRole = $actorObj ? strtolower($actorObj->role ?? 'staff') : $userRole;
                $actorDept = $actorObj ? ($actorObj->department ?? 'General') : $userDept;

                $rawActivities->push([
                    'category'   => $actCategory,
                    'user_name'  => $actorName,
                    'user_role'  => $actorRole,
                    'department' => $actorDept,
                    'period'     => $uPeriod,
                    'timestamp'  => $uDate,
                    'datetime'   => $uDate->format('d M Y, h:i A'),
                    'title'      => $actTitle,
                    'subject'    => $ticket->subject ?? $ticket->title ?? '',
                    'emoji'      => $actEmoji,
                    'is_closed'  => $isClosed,
                ]);
            }
        }

        // B. Data Aktiviti Assets (MENYOKONG SEMUA VARIASI KOLOM JADUAL ASSETS ITAM)
        if (Schema::hasTable('assets')) {
            $assets = DB::table('assets')->get();

            foreach ($assets as $asset) {
                $assetArr = (array) $asset;

                // 1. Semak Semua Kemungkinan Kolom Pemilik Asset
                $assignedVal = null;
                $possibleUserCols = [
                    'assigned_to', 'assigned_user', 'assigned_name', 'assigned_to_name',
                    'user_id', 'user_name', 'user', 'owner', 'owner_id', 'employee',
                    'employee_name', 'recipient', 'holder', 'assigned_to_user', 'technician'
                ];

                foreach ($possibleUserCols as $col) {
                    if (array_key_exists($col, $assetArr) && !empty($assetArr[$col])) {
                        $assignedVal = $assetArr[$col];
                        break;
                    }
                }

                // 2. Dapatkan Tag & Nama Asset
                $assetTag = $assetArr['asset_tag'] ?? $assetArr['tag'] ?? $assetArr['device_name'] ?? $assetArr['name'] ?? ('Asset #' . ($assetArr['id'] ?? '1'));
                $assetName = $assetArr['name'] ?? $assetArr['device_name'] ?? $assetArr['model'] ?? 'IT Asset';

                // 3. Resolusi Model Pengguna (User Matching)
                $assetUser = null;
                if (!empty($assignedVal)) {
                    if (is_numeric($assignedVal)) {
                        $assetUser = User::find($assignedVal);
                    }
                    if (!$assetUser) {
                        $assetUser = User::where('name', 'LIKE', trim($assignedVal))
                            ->orWhere('email', 'LIKE', trim($assignedVal))
                            ->first();
                    }
                }

                // 4. Tetapkan Nama, Role, dan Department
                if ($assetUser) {
                    $aUserName = $assetUser->name;
                    $aUserRole = strtolower($assetUser->role ?? 'staff');
                    $aUserDept = $assetUser->department ?? 'General';
                } elseif (!empty($assignedVal) && is_string($assignedVal)) {
                    $aUserName = trim($assignedVal);
                    $matchedInList = $usersList->first(function($u) use ($aUserName) {
                        return strtolower($u->name) === strtolower($aUserName);
                    });
                    $aUserRole = $matchedInList ? strtolower($matchedInList->role) : 'technician';
                    $aUserDept = $matchedInList ? ($matchedInList->department ?? 'General') : 'General';
                } else {
                    $aUserName = $user->role === 'admin' ? 'Admin' : $user->name;
                    $aUserRole = strtolower($user->role);
                    $aUserDept = $user->department ?? 'General';
                }

                // 5. Pengiraan Period Masa
                $aDateRaw = $assetArr['created_at'] ?? $assetArr['updated_at'] ?? now();
                $aDate = Carbon::parse($aDateRaw);
                $aPeriod = 'older';
                if ($aDate->isToday()) {
                    $aPeriod = 'today';
                } elseif ($aDate->isSameWeek($now)) {
                    $aPeriod = 'this_week';
                } elseif ($aDate->isSameMonth($now)) {
                    $aPeriod = 'this_month';
                }

                $rawActivities->push([
                    'category'   => 'assets',
                    'user_name'  => $aUserName,
                    'user_role'  => $aUserRole,
                    'department' => $aUserDept,
                    'period'     => $aPeriod,
                    'timestamp'  => $aDate,
                    'datetime'   => $aDate->format('d M Y, h:i A'),
                    'title'      => "Assigned Asset {$assetTag}",
                    'subject'    => "{$assetName} (Assigned to: {$aUserName})",
                    'emoji'      => ActivityHelper::getEmoji('asset_assigned'),
                    'is_closed'  => false,
                ]);
            }
        }

        // C. Data Registration Users (DIHUBUNGKAN KEPADA NAMA PENGGUNA SEBENAR)
        if ($user->role === 'admin') {
            $allUsers = User::all();
            foreach ($allUsers as $usr) {
                $uDate = $usr->created_at ?? now();
                $uPeriod = 'older';
                if ($uDate->isToday()) {
                    $uPeriod = 'today';
                } elseif ($uDate->isSameWeek($now)) {
                    $uPeriod = 'this_week';
                } elseif ($uDate->isSameMonth($now)) {
                    $uPeriod = 'this_month';
                }

                $rawActivities->push([
                    'category'   => 'users',
                    'user_name'  => $usr->name,
                    'user_role'  => strtolower($usr->role ?? 'staff'),
                    'department' => $usr->department ?? 'General',
                    'period'     => $uPeriod,
                    'timestamp'  => $uDate,
                    'datetime'   => $uDate->format('d M Y, h:i A'),
                    'title'      => "Registered New User: {$usr->name}",
                    'subject'    => "Role: " . ucfirst($usr->role) . " | Email: {$usr->email}",
                    'emoji'      => ActivityHelper::getEmoji('user_login'),
                    'is_closed'  => false,
                ]);
            }
        }

        // Susun mengikut urutan terbaharu ke terlama
        $allActivities = $rawActivities->sortByDesc('timestamp')->values();

        // 5. Status Keselamatan
        $passwordLastChanged = $user->updated_at ? $user->updated_at->format('d M Y') : Carbon::now()->format('d M Y');

        // 6. Maklumat Sistem (Admin Only)
        $systemInfo = null;
        if ($user->role === 'admin') {
            $dbConnected = false;
            try {
                DB::connection()->getPdo();
                $dbConnected = true;
            } catch (\Exception $e) {
                $dbConnected = false;
            }

            $reverbOnline = false;
            $connection = @fsockopen('127.0.0.1', 8080, $errno, $errstr, 1);
            if (is_resource($connection)) {
                $reverbOnline = true;
                fclose($connection);
            }

            $systemInfo = [
                'system_version'  => 'v1.0.0',
                'laravel_version' => app()->version(),
                'php_version'     => PHP_VERSION,
                'db_status'       => $dbConnected ? 'Connected' : 'Disconnected',
                'reverb_status'   => $reverbOnline ? 'Online' : 'Offline',
                'environment'     => config('app.env') ?: 'local',
                'server_time'     => Carbon::now('Asia/Kuala_Lumpur')->format('d M Y, h:i A'),
                'timezone'        => 'Asia/Kuala_Lumpur',
            ];
        }

        return view('settings.index', compact(
            'user', 
            'myActivity', 
            'recentActivities', 
            'allActivities', 
            'passwordLastChanged', 
            'systemInfo',
            'usersList',
            'departmentsList'
        ));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $rules = [
            'name'  => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
        ];

        if (Schema::hasColumn('users', 'department')) {
            $rules['department'] = 'nullable|string|max:255';
        }
        if (Schema::hasColumn('users', 'office_location')) {
            $rules['office_location'] = 'nullable|string|max:255';
        }

        $request->validate($rules);

        $updateData = [
            'name'  => $request->name,
            'email' => $request->email,
        ];

        if (Schema::hasColumn('users', 'department')) {
            $updateData['department'] = $request->department;
        }
        if (Schema::hasColumn('users', 'office_location')) {
            $updateData['office_location'] = $request->office_location;
        }

        $user->update($updateData);

        return back()->with('success', 'Profile updated successfully!');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password'          => 'required',
            'new_password'              => 'required|min:6',
            'new_password_confirmation' => 'required|same:new_password',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Current password does not match!']);
        }

        $user->password = Hash::make($request->new_password);
        $user->save();

        Auth::setUser($user);

        return back()->with('success', 'Password updated successfully!');
    }
}