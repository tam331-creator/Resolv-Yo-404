<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\Asset;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Mail;
use App\Events\MessageSent;
use App\Events\UserTyping;
use App\Models\TicketRead;
use App\Mail\TicketClosedMail;
use App\Mail\TechnicianAssignedMail;

class TicketController extends Controller
{
    // 1. Papar Senarai Tiket (RBAC Filter)
    public function index()
    {
        $user = auth()->user();
        if ($user) {
            $user->update([
                'last_seen' => now()
            ]);
        }

        $query = Ticket::with([
            'asset',
            'user',
            'technician',
            'reads'
        ])->latest();

        if ($user) {
            if ($user->role === 'staff') {
                $query->where('user_id', $user->id);
            } elseif ($user->role === 'technician') {
                $query->where('assigned_to', $user->id);
            }
        }

        $tickets = $query->get();
        return view('tickets.index', compact('tickets'));
    }

    // 2. Papar Borang Bina Tiket
    public function create()
    {
        $assets = Asset::all();
        return view('tickets.create', compact('assets'));
    }

    // 3. Simpan Tiket Baharu (DIKEMASKINI DENGAN LOGIK SLA)
    public function store(Request $request)
    {
        $request->validate([
            'title'       => 'required|string|max:255',
            'category'    => 'required|string',
            'priority'    => 'required|string',
            'description' => 'nullable|string',
            'asset_id'    => 'nullable|exists:assets,id',
            'attachment'  => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ]);

        $attachmentPath = null;
        if ($request->hasFile('attachment')) {
            $attachmentPath = $request->file('attachment')->store('attachments', 'public');
        }

        // Auto-assign technician (round-robin)
        $autoAssignedTechId = null;

        if (Schema::hasColumn('users', 'role')) {
            $technicians = User::where('role', 'technician')->orderBy('id', 'asc')->get();
        } else {
            $technicians = User::orderBy('id', 'asc')->get();
        }

        if ($technicians->isNotEmpty()) {
            $lastTicket = Ticket::whereNotNull('assigned_to')->latest('id')->first();

            if (!$lastTicket) {
                $autoAssignedTechId = $technicians->first()->id;
            } else {
                $lastTechIndex = $technicians->search(function ($tech) use ($lastTicket) {
                    return $tech->id == $lastTicket->assigned_to;
                });

                if ($lastTechIndex === false || $lastTechIndex >= $technicians->count() - 1) {
                    $autoAssignedTechId = $technicians->first()->id;
                } else {
                    $autoAssignedTechId = $technicians[$lastTechIndex + 1]->id;
                }
            }
        }

        // Kira tarikh luput SLA mengikut Priority (Low = 24h, Medium = 8h, High = 3h)
        $slaDueAt = Ticket::calculateSlaDueAt($request->priority);

        $ticket = Ticket::create([
            'title'       => $request->title,
            'category'    => $request->category,
            'priority'    => $request->priority,
            'description' => $request->description,
            'asset_id'    => $request->asset_id,
            'user_id'     => auth()->id() ?? null,
            'assigned_to' => $autoAssignedTechId,
            'attachment'  => $attachmentPath,
            'status'      => 'Open',
            'sla_due_at'  => $slaDueAt,
        ]);

        // Hantar emel notifikasi tiket baharu
        try {
            $ticket->load(['technician', 'user']);

            $recipients = array_filter([
                $ticket->technician->email ?? null,
                'tamtks331@gmail.com'
            ]);
            $recipients = array_unique($recipients);

            $ticketUrl = route('tickets.show', $ticket->id);
            $descriptionText = e($ticket->description ?? 'No description provided.');
            $assignedTechName = e($ticket->technician->name ?? 'Unassigned');
            $reporterName = e($ticket->user->name ?? 'Staff');

            $htmlContent = "
            <table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\" style=\"background-color: #0d1117; padding: 30px 10px; font-family: Arial, sans-serif;\">
                <tr>
                    <td align=\"center\">
                        <table width=\"550\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\" style=\"background-color: #161b22; border: 1px solid #30363d; border-radius: 8px; width: 550px; max-width: 100%;\">
                            <tr>
                                <td align=\"center\" style=\"background-color: #0088cc; padding: 18px 20px; border-top-left-radius: 8px; border-top-right-radius: 8px;\">
                                    <h2 style=\"color: #ffffff; margin: 0; font-size: 18px; font-weight: bold; text-transform: uppercase;\">
                                        🎫 NEW TICKET CREATED #[{$ticket->id}]
                                    </h2>
                                </td>
                            </tr>
                            <tr>
                                <td style=\"padding: 25px; color: #c9d1d9;\">
                                    <table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\" style=\"margin-bottom: 15px;\">
                                        <tr>
                                            <td>
                                                <span style=\"color: #00f2fe; font-size: 11px; font-weight: bold; text-transform: uppercase; display: block; margin-bottom: 4px;\">TICKET TITLE</span>
                                                <div style=\"font-size: 16px; font-weight: bold; color: #ffffff;\">{$ticket->title}</div>
                                            </td>
                                        </tr>
                                    </table>

                                    <table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\" style=\"margin-bottom: 15px;\">
                                        <tr>
                                            <td width=\"50%\" valign=\"top\" style=\"padding-right: 10px;\">
                                                <span style=\"color: #00f2fe; font-size: 11px; font-weight: bold; text-transform: uppercase; display: block; margin-bottom: 4px;\">CATEGORY</span>
                                                <table cellpadding=\"4\" cellspacing=\"0\" border=\"0\" style=\"background: #21262d; border: 1px solid #30363d; border-radius: 4px;\">
                                                    <tr>
                                                        <td style=\"color: #58a6ff; font-size: 12px; font-weight: bold;\">{$ticket->category}</td>
                                                    </tr>
                                                </table>
                                            </td>
                                            <td width=\"50%\" valign=\"top\" style=\"padding-left: 10px;\">
                                                <span style=\"color: #00f2fe; font-size: 11px; font-weight: bold; text-transform: uppercase; display: block; margin-bottom: 4px;\">PRIORITY</span>
                                                <table cellpadding=\"4\" cellspacing=\"0\" border=\"0\" style=\"background: #21262d; border: 1px solid #30363d; border-radius: 4px;\">
                                                    <tr>
                                                        <td style=\"color: #ff7b72; font-size: 12px; font-weight: bold;\">{$ticket->priority}</td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                    </table>

                                    <table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\" style=\"margin-bottom: 15px;\">
                                        <tr>
                                            <td width=\"50%\" valign=\"top\" style=\"padding-right: 10px;\">
                                                <span style=\"color: #00f2fe; font-size: 11px; font-weight: bold; text-transform: uppercase; display: block; margin-bottom: 4px;\">REPORTER</span>
                                                <span style=\"color: #ffffff; font-size: 13px; font-weight: bold;\">{$reporterName}</span>
                                            </td>
                                            <td width=\"50%\" valign=\"top\" style=\"padding-left: 10px;\">
                                                <span style=\"color: #00f2fe; font-size: 11px; font-weight: bold; text-transform: uppercase; display: block; margin-bottom: 4px;\">ASSIGNED TECHNICIAN</span>
                                                <span style=\"color: #ffffff; font-size: 13px; font-weight: bold;\">{$assignedTechName}</span>
                                            </td>
                                        </tr>
                                    </table>

                                    <table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\" style=\"margin-bottom: 25px;\">
                                        <tr>
                                            <td>
                                                <span style=\"color: #00f2fe; font-size: 11px; font-weight: bold; text-transform: uppercase; display: block; margin-bottom: 6px;\">ISSUE DESCRIPTION</span>
                                                <table width=\"100%\" cellpadding=\"10\" cellspacing=\"0\" border=\"0\" style=\"background-color: #0d1117; border-left: 3px solid #00f2fe; border-radius: 4px;\">
                                                    <tr>
                                                        <td style=\"color: #c9d1d9; font-size: 13px; line-height: 1.5;\">{$descriptionText}</td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                    </table>

                                    <table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\">
                                        <tr>
                                            <td align=\"center\">
                                                <table cellpadding=\"0\" cellspacing=\"0\" border=\"0\">
                                                    <tr>
                                                        <td align=\"center\" style=\"background-color: #00f2fe; border-radius: 6px; padding: 12px 25px;\">
                                                            <a href=\"{$ticketUrl}\" style=\"color: #000000; text-decoration: none; font-weight: bold; font-size: 14px; display: inline-block;\">View Ticket Now &rarr;</a>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <td align=\"center\" style=\"background-color: #0d1117; padding: 12px; border-top: 1px solid #21262d; font-size: 11px; color: #484f58; border-bottom-left-radius: 8px; border-bottom-right-radius: 8px;\">
                                    Helpdesk System &bull; Automated Notification
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
            ";

            Mail::send([], [], function ($message) use ($recipients, $ticket, $htmlContent) {
                $message->to($recipients)
                        ->subject("New Ticket Created #[{$ticket->id}]: {$ticket->title}")
                        ->html($htmlContent);
            });

        } catch (\Exception $e) {
            \Log::error('Failed to send ticket email: ' . $e->getMessage());
        }

        return redirect()->route('tickets.index')->with('success', 'Ticket submitted successfully and is now Open!');
    }

    // 4. Papar Maklumat Terperinci Tiket
    public function show(Request $request, Ticket $ticket)
    {
        if (auth()->check()) {
            TicketRead::updateOrCreate(
                [
                    'ticket_id' => $ticket->id,
                    'user_id'   => auth()->id(),
                ],
                [
                    'last_read_at' => now(),
                ]
            );

            auth()->user()->update([
                'last_seen' => now()
            ]);
        }

        if ($request->ajax()) {
            return response()->json([
                'status' => 'success'
            ]);
        }

        $technicians = User::where('role', 'technician')->get();
        return view('tickets.show', compact('ticket', 'technicians'));
    }

    // 5. Papar Borang Edit Tiket
    public function edit($id)
    {
        $ticket = Ticket::findOrFail($id);
        $assets = Asset::all();

        if (Schema::hasColumn('users', 'role')) {
            $technicians = User::where('role', 'technician')->get();
        } else {
            $technicians = User::all();
        }

        return view('tickets.edit', compact('ticket', 'assets', 'technicians'));
    }

    // 6. Kemaskini Tiket (DIKEMASKINI UNTUK PERUBAHAN SLA JIKA PRIORITY DIUBAH)
    public function update(Request $request, $id)
    {
        $ticket = Ticket::findOrFail($id);
        $user = auth()->user();

        $oldDescription = $ticket->description;
        $oldStatus = $ticket->status;
        $oldAssignedTo = $ticket->assigned_to;
        $oldPriority = $ticket->priority;

        $request->validate([
            'attachment' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ]);

        if ($request->hasFile('attachment')) {
            $ticket->attachment = $request->file('attachment')->store('attachments', 'public');
        }

        if ($user && $user->role === 'staff') {
            $request->validate([
                'title'       => 'required|string|max:255',
                'description' => 'nullable|string',
                'asset_id'    => 'nullable|exists:assets,id',
            ]);

            $ticket->title       = $request->title;
            $ticket->description = $request->description;
            $ticket->asset_id    = $request->asset_id ?: null;

        } elseif ($user && $user->role === 'technician') {
            $request->validate([
                'title'       => 'required|string|max:255',
                'description' => 'nullable|string',
                'status'      => 'required|string|in:Open,In Progress',
                'priority'    => 'required|string',
                'category'    => 'required|string',
                'asset_id'    => 'nullable|exists:assets,id',
            ]);

            $ticket->title       = $request->title;
            $ticket->description = $request->description;
            $ticket->status      = $request->status;
            $ticket->priority    = $request->priority;
            $ticket->category    = $request->category;
            $ticket->asset_id    = $request->asset_id ?: null;

        } else { // Admin
            $request->validate([
                'title'       => 'required|string|max:255',
                'status'      => 'required|string|in:Open,In Progress,Closed',
                'priority'    => 'required|string',
                'category'    => 'required|string',
                'description' => 'nullable|string',
                'asset_id'    => 'nullable|exists:assets,id',
                'assigned_to' => 'nullable|exists:users,id',
            ]);

            $ticket->title       = $request->title;
            $ticket->status      = $request->status;
            $ticket->priority    = $request->priority;
            $ticket->category    = $request->category;
            $ticket->description = $request->description;
            $ticket->asset_id    = $request->asset_id ?: null;
            $ticket->assigned_to = $request->assigned_to ?: null;
        }

        // Kemaskini semula SLA jika keutamaan (priority) bertukar
        if ($ticket->priority !== $oldPriority) {
            $ticket->sla_due_at = Ticket::calculateSlaDueAt($ticket->priority, $ticket->created_at);
        }

        $newMessage = null;

        if ($request->filled('resolution_notes')) {
            $existingMessages = json_decode($ticket->resolution_notes, true);

            if (!is_array($existingMessages) && !empty($ticket->resolution_notes)) {
                $existingMessages = [[
                    'user'       => $ticket->user->name ?? 'User',
                    'role'       => $ticket->user->role ?? 'USER',
                    'message'    => $ticket->resolution_notes,
                    'created_at' => $ticket->updated_at ? $ticket->updated_at->timezone('Asia/Kuala_Lumpur')->format('d M Y, h:i A') : now()->timezone('Asia/Kuala_Lumpur')->format('d M Y, h:i A'),
                ]];
            } elseif (!is_array($existingMessages)) {
                $existingMessages = [];
            }

            $newMessage = [
                'user'       => $user->name ?? 'System',
                'role'       => strtoupper($user->role ?? 'USER'),
                'message'    => $request->resolution_notes,
                'created_at' => now()->timezone('Asia/Kuala_Lumpur')->format('d M Y, h:i A'),
            ];

            $existingMessages[] = $newMessage;
            $ticket->resolution_notes = json_encode($existingMessages);
            $ticket->last_message_at = now();
        }

        $ticket->save();

        // 1. NOTIFIKASI APABILA JURUTEKNIK DI-ASSIGN / BERTUKAR
        if ($ticket->assigned_to && $ticket->assigned_to != $oldAssignedTo) {
            try {
                $ticket->load(['technician', 'user', 'asset']);
                if ($ticket->technician && $ticket->technician->email) {
                    Mail::to($ticket->technician->email)->send(new TechnicianAssignedMail($ticket));
                }
            } catch (\Exception $e) {
                \Log::error('Failed to send TechnicianAssignedMail on update: ' . $e->getMessage());
            }
        }

        // 2. NOTIFIKASI APABILA TIKET DI-CLOSED
        if (strtolower($ticket->status) === 'closed' && strtolower($oldStatus) !== 'closed') {
            try {
                $ticket->load(['user', 'technician']);
                if ($ticket->user && $ticket->user->email) {
                    Mail::to($ticket->user->email)->send(new TicketClosedMail($ticket));
                }
            } catch (\Exception $e) {
                \Log::error('Failed to send TicketClosedMail: ' . $e->getMessage());
            }
        }

        if ($newMessage) {
            broadcast(
                new MessageSent(
                    $ticket->id,
                    $newMessage
                )
            )->toOthers();
        }

        if ($request->ajax()) {
            return response()->json([
                'status' => 'success'
            ]);
        }

        return redirect()->route('tickets.index')
            ->with('success', 'Ticket updated successfully!');
    }

    // 7. Simpan Tugasan Juruteknik
    public function assignTechnician(Request $request, $id)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }

        $request->validate([
            'technician_id' => 'required|exists:users,id',
        ]);

        $ticket = Ticket::findOrFail($id);
        $ticket->update([
            'assigned_to' => $request->technician_id,
            'status'      => 'In Progress',
        ]);

        try {
            $ticket->load(['technician', 'user', 'asset']);
            if ($ticket->technician && $ticket->technician->email) {
                Mail::to($ticket->technician->email)->send(new TechnicianAssignedMail($ticket));
            }
        } catch (\Exception $e) {
            \Log::error('Failed to send TechnicianAssignedMail: ' . $e->getMessage());
        }

        return back()->with('success', 'Technician assigned successfully!');
    }

    // 8. Padam Tiket
    public function destroy($id)
    {
        $ticket = Ticket::findOrFail($id);
        $ticket->delete();

        return redirect()->route('tickets.index')->with('success', 'Ticket deleted successfully!');
    }

    public function typing(Ticket $ticket)
    {
        broadcast(
            new UserTyping(
                $ticket->id,
                auth()->user()->name,
                auth()->user()->role
            )
        )->toOthers();

        return response()->json([
            'status' => 'success'
        ]);
    }

    // 9. Simpan Penilaian Bintang
    public function submitRating(Request $request, $id)
    {
        $request->validate([
            'rating'          => 'required|integer|min:1|max:5',
            'rating_feedback' => 'nullable|string|max:1000',
        ]);

        $ticket = Ticket::findOrFail($id);

        if (auth()->id() !== $ticket->user_id) {
            return back()->with('error', 'Only the ticket submitter can provide feedback.');
        }

        if (strtolower($ticket->status) !== 'closed') {
            return back()->with('error', 'Feedback can only be provided after the ticket is closed.');
        }

        $ticket->update([
            'rating'          => $request->rating,
            'rating_feedback' => $request->rating_feedback,
        ]);

        return back()->with('success', 'Thank you for your feedback!');
    }

    // 10. Semak Notifikasi Tiket & Mesej Baharu
    public function checkNotifications(Request $request)
    {
        $user = auth()->user();
        if (!$user) return response()->json(['notifications' => []]);

        $sinceParam = $request->query('since');
        try {
            $since = $sinceParam ? \Carbon\Carbon::parse($sinceParam) : now()->subSeconds(15);
        } catch (\Exception $e) {
            $since = now()->subSeconds(15);
        }

        $notifications = [];

        // A) NOTIFIKASI TIKET BAHARU
        $ticketQuery = Ticket::with('user')->where('created_at', '>', $since);
        if ($user->role === 'technician') {
            $ticketQuery->where('assigned_to', $user->id);
        } elseif ($user->role === 'staff') {
            $ticketQuery->where('user_id', $user->id);
        }

        $newTickets = $ticketQuery->get();
        foreach ($newTickets as $ticket) {
            if ($ticket->user_id !== $user->id) {
                $notifications[] = [
                    'id'      => 'ticket_' . $ticket->id . '_' . strtotime($ticket->created_at),
                    'title'   => "🎫 Tiket Baharu #[{$ticket->id}]",
                    'message' => ($ticket->user->name ?? 'Staff') . ": " . $ticket->title,
                    'url'     => route('tickets.show', $ticket->id)
                ];
            }
        }

        // B) NOTIFIKASI MESEJ / CHAT BAHARU
        $msgQuery = Ticket::where('last_message_at', '>', $since);
        if ($user->role === 'staff') {
            $msgQuery->where('user_id', $user->id);
        } elseif ($user->role === 'technician') {
            $msgQuery->where('assigned_to', $user->id);
        }

        $updatedTickets = $msgQuery->get();
        foreach ($updatedTickets as $ticket) {
            $messages = json_decode($ticket->resolution_notes, true);
            if (is_array($messages) && count($messages) > 0) {
                $lastMsg = end($messages);

                if (
                    isset($lastMsg['user']) &&
                    $lastMsg['user'] !== $user->name &&
                    !empty($lastMsg['message'])
                ) {
                    $notifications[] = [
                        'id'      => 'chat_' . md5($ticket->id . $lastMsg['user'] . ($lastMsg['created_at'] ?? '')),
                        'title'   => "💬 Mesej Baharu (Tiket #{$ticket->id})",
                        'message' => $lastMsg['user'] . ': ' . $lastMsg['message'],
                        'url'     => route('tickets.show', $ticket->id)
                    ];
                }
            }
        }

        return response()->json([
            'notifications' => $notifications,
            'timestamp'     => now()->toISOString()
        ]);
    }
}