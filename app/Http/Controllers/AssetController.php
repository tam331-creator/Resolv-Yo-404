<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AssetController extends Controller
{
    public function index(Request $request)
    {
        $query = Asset::latest();

        // Search Bar Utama (General Search)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('asset_tag', 'like', "%{$search}%")
                  ->orWhere('name', 'like', "%{$search}%")
                  ->orWhere('assigned_user', 'like', "%{$search}%")
                  ->orWhere('serial_number', 'like', "%{$search}%");
            });
        }

        // Filter mengikut Category
        if ($request->filled('category') && $request->category !== 'All Categories') {
            $query->where('category', $request->category);
        }

        // Filter mengikut Status
        if ($request->filled('status') && $request->status !== 'All Status') {
            $query->where('status', $request->status);
        }

        // Filter mengikut Department
        if ($request->filled('department') && $request->department !== 'All Departments') {
            $query->where('location', $request->department);
        }

        // Filter mengikut Assigned User
        if ($request->filled('assigned_user') && $request->assigned_user !== 'All Users') {
            $query->where('assigned_user', $request->assigned_user);
        }

        // Ambil semua tiket daripada pangkalan data phpMyAdmin untuk disepadukan dengan sejarah aset
        $allTickets = DB::table('tickets')->get();

        $assets = $query->get()->map(function ($asset) use ($allTickets) {
            
            // Pengiraan Warranty Remaining
            if ($asset->warranty_expiry) {
                $expiryDate = Carbon::parse($asset->warranty_expiry);
                $now = Carbon::now();

                if ($now->greaterThanOrEqualTo($expiryDate)) {
                    $asset->warranty_remaining = 'Expired';
                    $asset->warranty_color_class = 'text-rose-500 font-extrabold';
                    $asset->is_warranty_active = false;
                } else {
                    $asset->is_warranty_active = true;
                    $diffDays = (int) $now->diffInDays($expiryDate);

                    if ($diffDays >= 365) {
                        $years = (int) floor($diffDays / 365);
                        $asset->warranty_remaining = $years . ' ' . ($years > 1 ? 'Years' : 'Year');
                        $asset->warranty_color_class = 'text-lime-400 font-extrabold drop-shadow-[0_0_8px_rgba(163,230,53,0.8)]';
                    } elseif ($diffDays >= 30) {
                        $months = (int) floor($diffDays / 30);
                        $asset->warranty_remaining = $months . ' ' . ($months > 1 ? 'Months' : 'Month');
                        $asset->warranty_color_class = 'text-yellow-300 font-extrabold drop-shadow-[0_0_8px_rgba(253,224,71,0.8)]';
                    } else {
                        $days = $diffDays == 0 ? 1 : $diffDays;
                        $asset->warranty_remaining = $days . ' ' . ($days > 1 ? 'Days' : 'Day');
                        $asset->warranty_color_class = 'text-rose-500 font-extrabold drop-shadow-[0_0_8px_rgba(244,63,94,0.9)]';
                    }
                }
            } else {
                $asset->warranty_remaining = 'N/A';
                $asset->warranty_color_class = 'text-slate-400 font-bold';
                $asset->is_warranty_active = false;
            }

            // Bina Senarai Sejarah Aset & Tiket Secara Terus
            $combinedHistory = [];

            // 1. Rekod Tiket yang Berkaitan dengan Aset Ini
            $relatedTickets = $allTickets->filter(function($t) use ($asset) {
                return (isset($t->asset_tag) && $t->asset_tag === $asset->asset_tag) || 
                       (isset($t->asset_id) && $t->asset_id == $asset->id);
            });

            foreach ($relatedTickets as $ticket) {
                $tId = $ticket->id;
                $tNo = $ticket->ticket_number ?? $ticket->id ?? 'Ticket';
                $tSub = $ticket->subject ?? $ticket->title ?? 'Issue Reported';
                $tDate = Carbon::parse($ticket->created_at ?? now())->format('d M Y');

                $combinedHistory[] = [
                    'date'        => $tDate,
                    'timestamp'   => strtotime($ticket->created_at ?? now()),
                    'title'       => "Linked to Ticket #{$tNo}",
                    'description' => $tSub,
                    'type'        => 'ticket',
                    'ticket_id'   => $tId,
                    'icon'        => '🎫',
                    'badge_text'  => 'Ticket Link',
                    'badge_class' => 'bg-amber-500/20 text-amber-300 border-amber-500/40'
                ];
            }

            // 2. Rekod Penyelenggaraan / Status Aset
            if ($asset->status === 'Maintenance') {
                $combinedHistory[] = [
                    'date'        => Carbon::parse($asset->updated_at ?? now())->format('d M Y'),
                    'timestamp'   => strtotime($asset->updated_at ?? now()),
                    'title'       => 'Maintenance Started',
                    'description' => 'Device sent for repair / maintenance servicing',
                    'type'        => 'maintenance',
                    'icon'        => '🔧',
                    'badge_text'  => 'Maintenance',
                    'badge_class' => 'bg-amber-500/20 text-amber-300 border-amber-500/40'
                ];
            } elseif ($asset->status === 'Active') {
                $combinedHistory[] = [
                    'date'        => Carbon::parse($asset->updated_at ?? now())->format('d M Y'),
                    'timestamp'   => strtotime($asset->updated_at ?? now()),
                    'title'       => 'Returned to Active Status',
                    'description' => 'Device operational and currently active',
                    'type'        => 'status_active',
                    'icon'        => '✅',
                    'badge_text'  => 'Active Status',
                    'badge_class' => 'bg-emerald-500/20 text-emerald-300 border-emerald-500/40'
                ];
            } elseif ($asset->status === 'Retired') {
                $combinedHistory[] = [
                    'date'        => Carbon::parse($asset->updated_at ?? now())->format('d M Y'),
                    'timestamp'   => strtotime($asset->updated_at ?? now()),
                    'title'       => 'Asset Disposed',
                    'description' => 'Device retired and decommissioned from service',
                    'type'        => 'disposed',
                    'icon'        => '♻️',
                    'badge_text'  => 'Disposed',
                    'badge_class' => 'bg-rose-500/20 text-rose-300 border-rose-500/40'
                ];
            }

            // 3. Rekod Penugasan (Assigned User)
            if (!empty($asset->assigned_user) && $asset->assigned_user !== 'Unassigned') {
                $combinedHistory[] = [
                    'date'        => Carbon::parse($asset->updated_at ?? $asset->created_at ?? now())->format('d M Y'),
                    'timestamp'   => strtotime($asset->updated_at ?? $asset->created_at ?? now()),
                    'title'       => "Assigned to {$asset->assigned_user}",
                    'description' => "Device currently assigned and in use",
                    'type'        => 'assignment',
                    'icon'        => '👤',
                    'badge_text'  => 'User Assigned',
                    'badge_class' => 'bg-sky-500/20 text-sky-300 border-sky-500/40'
                ];
            }

            // 4. Rekod Kemaskini Waranti (Warranty Updated)
            if (!empty($asset->warranty_expiry)) {
                $combinedHistory[] = [
                    'date'        => Carbon::parse($asset->updated_at ?? $asset->created_at ?? now())->format('d M Y'),
                    'timestamp'   => strtotime($asset->updated_at ?? $asset->created_at ?? now()),
                    'title'       => "Warranty Updated",
                    'description' => "Warranty coverage set until " . Carbon::parse($asset->warranty_expiry)->format('d M Y'),
                    'type'        => 'warranty',
                    'icon'        => '📅',
                    'badge_text'  => 'Warranty',
                    'badge_class' => 'bg-emerald-500/20 text-emerald-300 border-emerald-500/40'
                ];
            }

            // 5. Rekod Kemaskini Spesifikasi Aset (Asset Updated)
            if ($asset->updated_at && $asset->updated_at != $asset->created_at) {
                $combinedHistory[] = [
                    'date'        => Carbon::parse($asset->updated_at)->format('d M Y'),
                    'timestamp'   => strtotime($asset->updated_at),
                    'title'       => "Asset Updated",
                    'description' => "Asset specifications or configuration details updated",
                    'type'        => 'updated',
                    'icon'        => '✏️',
                    'badge_text'  => 'Asset Edit',
                    'badge_class' => 'bg-blue-500/20 text-blue-300 border-blue-500/40'
                ];
            }

            // 6. Rekod Pendaftaran Aset asal (Asset Registered)
            if (!empty($asset->created_at)) {
                $combinedHistory[] = [
                    'date'        => Carbon::parse($asset->created_at)->format('d M Y'),
                    'timestamp'   => strtotime($asset->created_at),
                    'title'       => "Asset Registered",
                    'description' => "Initial ITAM System Registration",
                    'type'        => 'system',
                    'icon'        => '📦',
                    'badge_text'  => 'Registration',
                    'badge_class' => 'bg-cyan-500/20 text-cyan-300 border-cyan-500/40'
                ];
            }

            // Susun sejarah mengikut urutan tarikh terkini di atas
            usort($combinedHistory, function($a, $b) {
                return $b['timestamp'] - $a['timestamp'];
            });

            $asset->history_logs = $combinedHistory;

            return $asset;
        });

        $totalAssets = Asset::count();
        $activeAssets = Asset::where('status', 'Active')->count();
        $maintenanceAssets = Asset::where('status', 'Maintenance')->count();
        $retiredAssets = Asset::where('status', 'Retired')->count();

        // Data departments & users daripada phpMyAdmin
        $departments = DB::table('departments')->pluck('name');
        $users = DB::table('users')->pluck('name');

        return view('assets.index', compact('assets', 'totalAssets', 'activeAssets', 'maintenanceAssets', 'retiredAssets', 'departments', 'users'));
    }

    public function edit(Asset $asset)
    {
        return view('assets.edit', compact('asset'));
    }

    public function update(Request $request, Asset $asset)
    {
        $validated = $request->validate([
            'asset_tag'       => 'required|string|unique:assets,asset_tag,' . $asset->id,
            'name'            => 'required|string|max:255',
            'category'        => 'required|string',
            'brand'           => 'nullable|string',
            'model'           => 'nullable|string',
            'serial_number'   => 'nullable|string',
            'assigned_user'   => 'nullable|string',
            'location'        => 'nullable|string',
            'status'          => 'required|string',
            'purchase_date'   => 'nullable|date',
            'warranty_expiry' => 'nullable|date',
            'remarks'         => 'nullable|string',
        ]);

        $asset->update([
            'asset_tag'       => $validated['asset_tag'],
            'name'            => $validated['name'],
            'type'            => $validated['category'],
            'category'        => $validated['category'],
            'brand'           => $validated['brand'] ?? null,
            'model'           => $validated['model'] ?? null,
            'serial_number'   => $validated['serial_number'] ?? null,
            'assigned_user'   => $validated['assigned_user'] ?? null,
            'location'        => $validated['location'] ?? null,
            'status'          => $validated['status'],
            'purchase_date'   => $validated['purchase_date'] ?? null,
            'warranty_expiry' => $validated['warranty_expiry'] ?? null,
            'remarks'         => $validated['remarks'] ?? null,
        ]);

        return redirect()->route('assets.index')->with('success', 'Asset specifications updated successfully!');
    }

    public function destroy(Asset $asset)
    {
        $asset->delete();
        return redirect()->route('assets.index')->with('success', 'Asset deleted successfully!');
    }
}