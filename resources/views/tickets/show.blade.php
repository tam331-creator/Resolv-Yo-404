@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto my-4 sm:my-6 space-y-6 text-slate-100 text-sm font-sans px-2 sm:px-4">

    <!-- NOTIFIKASI SUCCESS / ERROR -->
    @if(session('success'))
        <div class="bg-emerald-500/10 border border-emerald-500/40 text-emerald-400 px-4 py-3 rounded-xl text-xs font-semibold flex items-center gap-2 shadow-md">
            <i class="fa-solid fa-circle-check text-sm"></i> {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="bg-rose-500/10 border border-rose-500/40 text-rose-300 px-4 py-3 rounded-xl text-xs shadow-md">
            <strong class="font-bold flex items-center gap-1.5"><i class="fa-solid fa-triangle-exclamation"></i> Submission Failed:</strong>
            <ul class="mt-1 list-disc list-inside space-y-0.5">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- LOGIK SLA BERDASARKAN KEUTAMAAN (PRIORITY) -->
    @php
        $priorityLower = strtolower(trim($ticket->priority ?? ''));
        
        switch($priorityLower) {
            case 'high':
                $slaHours = 3;
                $priorityText = 'High (3 Hours)';$priorityBgClass = 'bg-rose-600 text-white border border-rose-500';
                break;
            case 'medium':
                $slaHours = 8;
                $priorityText = 'Medium (8 Hours)';$priorityBgClass = 'bg-amber-500 text-slate-950 border border-amber-400 font-extrabold';
                break;
            case 'low':
            default:
                $slaHours = 12;
                $priorityText = 'Low (12 Hours)';$priorityBgClass = 'bg-cyan-600 text-white border border-cyan-400';
                break;
        }

        $targetSlaDate =$ticket->created_at 
            ? $ticket->created_at->timezone('Asia/Kuala_Lumpur')->addHours($slaHours) 
            : null;
    @endphp

    <!-- KAD 1: MAKLUMAT UTAMA TIKET -->
    <div class="bg-slate-800/95 backdrop-blur-xl rounded-2xl shadow-2xl overflow-hidden border border-slate-700/80">
        <div class="bg-gradient-to-r from-blue-700 via-blue-600 to-indigo-700 text-white px-4 sm:px-6 py-3.5 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-2 font-semibold text-sm sm:text-base shadow-md">
            <div class="flex items-center gap-2.5">
                <i class="fa-solid fa-ticket text-cyan-300"></i>
                <span>Ticket #{{ $ticket->id }}: <span id="ticket_title" class="font-extrabold text-white">{{ $ticket->title }}</span></span>
            </div>
            <a href="{{ route('tickets.index') }}" class="bg-slate-900/40 hover:bg-slate-950 text-slate-100 text-xs px-3.5 py-1.5 rounded-lg flex items-center gap-1.5 transition font-semibold border border-white/20">
                <i class="fa-solid fa-arrow-left"></i> Back
            </a>
        </div>

        <div class="p-4 sm:p-6 space-y-5">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 bg-slate-900/60 p-4 rounded-xl border border-slate-700/60">
                <div>
                    <span class="block font-bold text-slate-400 text-xs mb-1">Reporter:</span>
                    <span class="text-slate-100 text-xs font-semibold break-words">{{ $ticket->user->name ?? 'N/A' }}</span>
                </div>
                <div>
                    <span class="block font-bold text-slate-400 text-xs mb-1">Category:</span>
                    <span id="ticket_category" class="bg-slate-950 text-cyan-300 px-2.5 py-0.5 rounded-md text-xs font-semibold border border-slate-700 inline-block">
                        {{ $ticket->category }}
                    </span>
                </div>
                <div>
                    <span class="block font-bold text-slate-400 text-xs mb-1">Priority:</span>
                    <span class="{{ $priorityBgClass }} font-bold px-2.5 py-0.5 rounded-full text-[11px] shadow-sm inline-block">
                        {{ $priorityText }}
                    </span>
                </div>
                <div>
                    <span class="block font-bold text-slate-400 text-xs mb-1">Status:</span>
                    <span class="{{ strtolower($ticket->status) === 'closed' ? 'bg-emerald-500/20 text-emerald-300 border border-emerald-500/50' : 'bg-amber-500/20 text-amber-300 border border-amber-500/50' }} font-bold px-2.5 py-0.5 rounded-md text-xs inline-block uppercase tracking-wider">
                        {{ $ticket->status }}
                    </span>
                </div>
            </div>

           <div class="flex items-center gap-2 flex-wrap pt-1">
                <span class="font-bold text-xs text-slate-300">Technician:</span>
                @if($ticket->assignedTechnician)
                    <span class="bg-cyan-600/30 border border-cyan-500/50 text-cyan-300 text-xs px-3 py-0.5 rounded-full flex items-center gap-1.5 font-bold">
                        <i class="fa-solid fa-user text-[10px]"></i> {{ $ticket->assignedTechnician->name }}
                    </span>
                    <span
                        id="technician-status"
                        data-tech-id="{{ $ticket->assignedTechnician->id ?? '' }}"
                        class="text-slate-400 text-xs font-semibold whitespace-nowrap flex items-center gap-1.5"
                    >
                        <span class="w-2 h-2 rounded-full bg-slate-500"></span> Offline
                    </span>
              @endif
            </div>

            <div class="bg-slate-900/90 border border-slate-700/80 rounded-xl p-4 flex items-center gap-3.5">
                <div class="w-10 h-10 rounded-lg bg-cyan-500/20 text-cyan-400 flex items-center justify-center text-lg border border-cyan-500/30 shrink-0">
                    <i class="fa-solid fa-laptop"></i>
                </div>
                <div>
                    <h4 class="font-bold text-xs text-slate-200">Asset / Device Details (ITAM):</h4>
                    @if($ticket->asset)
                        <p class="text-xs text-slate-300 mt-0.5">
                            <span class="bg-cyan-500/20 text-cyan-300 border border-cyan-500/40 font-mono font-bold text-[10px] px-2 py-0.5 rounded mr-1">
                                {{ $ticket->asset->asset_tag ?? 'N/A' }}
                            </span>
                            <span class="font-bold text-slate-100">{{ $ticket->asset->name ?? $ticket->asset->asset_name }}</span> 
                            <span class="text-slate-400">(Category: {{ $ticket->asset->category ?? 'N/A' }})</span>
                        </p>
                    @else
                        <p class="text-xs text-slate-400 italic mt-0.5">No specific asset associated.</p>
                    @endif
                </div>
            </div>

            <!-- BAHAGIAN TARGET SLA & REAL-TIME DUE MASA -->
            @if(strtolower($ticket->status) === 'closed')
                <div class="bg-emerald-950/40 border border-emerald-800/60 text-emerald-300 rounded-xl p-3.5 text-xs flex items-center justify-center gap-2 font-semibold">
                    <i class="fa-regular fa-circle-check text-emerald-400 text-base"></i> This ticket has been resolved.
                </div>
            @else
                <div class="bg-slate-900/90 border border-slate-700/80 rounded-xl p-3.5 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-2 text-xs shadow-inner">
                    <div>
                        <i class="fa-regular fa-clock mr-1 text-cyan-400"></i>
                        <span class="font-bold text-slate-300">Target SLA:</span> 
                        <span class="text-slate-100 font-mono font-semibold">{{ $targetSlaDate ? $targetSlaDate->format('d M Y, h:i A') : 'N/A' }}</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="font-bold text-slate-300">Remaining Time:</span>
                        <span id="sla-countdown" class="bg-rose-500/20 border border-rose-500/40 text-rose-300 font-mono font-extrabold px-3 py-1 rounded-lg shadow-sm text-xs">
                            Calculating...
                        </span>
                    </div>
                </div>
            @endif

            <hr class="border-slate-700/80">

            <div>
                <label class="block font-bold text-xs text-slate-300 mb-1.5">Problem Description:</label>
                <div id="ticket_desc" class="w-full bg-slate-900/90 border border-slate-700 rounded-xl p-3.5 text-xs text-slate-200 min-h-[70px] leading-relaxed">
                    {{ $ticket->description }}
                </div>
            </div>
        </div>
    </div>

    <!-- KAD PENILAIAN KEPUASAN PERKHIDMATAN -->
    @if(strtolower($ticket->status) === 'closed')
        <div class="bg-slate-800/95 backdrop-blur-xl rounded-2xl shadow-2xl overflow-hidden border-l-4 border-amber-500 border-r border-t border-b border-slate-700/80 p-4 sm:p-5 space-y-4 text-slate-100 font-sans">
            
            <div class="font-bold text-sm text-slate-100 flex items-center gap-2">
                <i class="fa-solid fa-star text-amber-400"></i>
                <span>Service Satisfaction Rating</span>
            </div>

            @if($ticket->rating)
                <div class="bg-slate-900 border border-slate-700 rounded-xl p-4 space-y-2">
                    <p class="text-xs font-bold text-slate-400">Submitted Rating:</p>
                    <div class="flex items-center text-amber-400 text-sm gap-1 font-bold">
                        <span>{{ $ticket->rating }}</span>
                        <i class="fa-solid fa-star"></i>
                    </div>
                    @if($ticket->rating_feedback)
                        <p class="text-xs text-slate-300 italic">"{{ $ticket->rating_feedback }}"</p>
                    @endif
                </div>
            @elseif(auth()->id() === $ticket->user_id)
                <form action="{{ route('tickets.rating', $ticket->id) }}" method="POST" class="space-y-4">
                    @csrf

                    <p class="text-xs font-bold text-slate-200">
                        Please rate our IT service:
                    </p>

                    <div class="flex flex-wrap items-center gap-3 sm:gap-4 text-xs font-bold text-slate-100">
                        @for($i = 1; $i <= 5; $i++)
                            <label class="inline-flex items-center gap-1 cursor-pointer hover:opacity-80">
                                <input type="radio" name="rating" value="{{ $i }}" class="w-4 h-4 text-amber-500 border-slate-700 bg-slate-900 focus:ring-amber-400" required>
                                <span>{{ $i }}</span>
                                <i class="fa-solid fa-star text-amber-400 text-xs"></i>
                            </label>
                        @endfor
                    </div>

                    <div class="space-y-1">
                        <label class="block font-bold text-xs text-slate-300">
                            Additional Comments / Feedback <span class="text-slate-400 font-normal">(Optional)</span>
                        </label>
                        <textarea name="rating_feedback" rows="3" placeholder="How was our technician's service?"
                                  class="w-full bg-slate-900 border border-slate-700 rounded-xl p-3 text-xs text-slate-100 placeholder-slate-500 focus:outline-none focus:border-amber-500"></textarea>
                    </div>

                    <button type="submit" class="w-full bg-amber-600 hover:bg-amber-500 text-slate-950 font-black text-xs py-2.5 rounded-xl flex items-center justify-center gap-2 transition shadow cursor-pointer">
                        <i class="fa-solid fa-paper-plane"></i>
                        <span>Submit Rating</span>
                    </button>
                </form>
            @else
                <p class="text-xs text-slate-400 italic">Awaiting rating from the reporter.</p>
            @endif

        </div>
    @endif

    <!-- KAD 2: TUGASKAN JURUTEKNIK / ADMIN -->
    @if(auth()->user()->role === 'admin')
        <div class="bg-slate-800/95 backdrop-blur-xl rounded-2xl shadow-2xl overflow-hidden border border-slate-700/80 p-4">
            <form action="{{ route('tickets.update', $ticket->id) }}" method="POST" class="space-y-2">
                @csrf
                @method('PUT')
                
                <label class="font-bold text-xs text-slate-200 flex items-center gap-2">
                    <i class="fa-solid fa-user-gear text-cyan-400"></i> Assign Technician / Admin
                </label>
                
                <div class="flex flex-col sm:flex-row gap-2 sm:gap-3">
                    <select name="assigned_to" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3.5 py-2 text-xs text-slate-100 focus:outline-none focus:border-cyan-400">
                        <option value="">-- Select Technician --</option>

                        @if(isset($technicians) && count($technicians) > 0)
                            @foreach($technicians as $tech)
                                <option value="{{ $tech->id }}" {{ $ticket->assigned_to == $tech->id ? 'selected' : '' }}>
                                    {{ $tech->name }}
                                </option>
                            @endforeach
                        @elseif($ticket->assignedTechnician)
                            <option value="{{ $ticket->assignedTechnician->id }}" selected>
                                {{ $ticket->assignedTechnician->name }} 
                            </option>
                        @endif

                    </select>

                    <button type="submit" class="w-full sm:w-auto bg-blue-600 hover:bg-blue-500 text-white font-bold text-xs px-5 py-2 rounded-xl flex items-center justify-center gap-1.5 transition whitespace-nowrap shadow">
                        <i class="fa-solid fa-check"></i> Save Assignment
                    </button>
                </div>
            </form>
        </div>
    @endif

    <!-- KAD 3: PERBUALAN / LOG KEMASKINI & PENYELESAIAN AI -->
    <div class="bg-slate-800/95 backdrop-blur-xl rounded-2xl shadow-2xl overflow-hidden border border-slate-700/80 p-4 sm:p-5 space-y-4">
        <div class="font-bold text-xs text-slate-200 flex items-center gap-2 border-b border-slate-700/80 pb-2.5">
            <i class="fa-solid fa-comments text-cyan-400"></i> Conversation / Activity Log
        </div>

        <!-- KONTAINER CHAT -->
        <div id="chat-messages-container" class="space-y-3 max-h-[400px] overflow-y-auto p-1">
            @php
                $messages = json_decode($ticket->resolution_notes, true);
                
                if (!is_array($messages) && !empty($ticket->resolution_notes)) {$messages = [[
                        'user' => $ticket->user->name ?? 'User',
                        'role' => $ticket->user->role ?? 'USER',
                        'message' => $ticket->resolution_notes,
                        'created_at' => $ticket->updated_at ? $ticket->updated_at->timezone('Asia/Kuala_Lumpur')->format('d M Y, h:i A') : ''
                    ]];
                } elseif (!is_array($messages)) {$messages = [];
                }
            @endphp

            @forelse($messages as $msg)
                @php
                    $isSelf = isset($msg['user']) && auth()->check() && $msg['user'] == auth()->user()->name;
                    $roleUpper = strtoupper($msg['role'] ?? 'USER');
                    
                    $roleColor = match($roleUpper) {
                        'ADMIN' => 'bg-rose-600 text-white',
                        'TECHNICIAN' => 'bg-slate-700 text-slate-200 border border-slate-600',
                        default => 'bg-cyan-600 text-white',
                    };
                @endphp

                <div class="flex flex-col {{ $isSelf ? 'items-end' : 'items-start' }}">
                    <div class="max-w-[90%] sm:max-w-[85%] bg-slate-900 border border-slate-700/80 rounded-2xl p-3.5 shadow-md space-y-1">
                        <div class="flex items-center justify-between gap-2 sm:gap-4 text-[11px] text-slate-400 border-b border-slate-800 pb-1.5 flex-wrap">
                            <span class="font-bold text-slate-100 flex items-center gap-1.5">
                                {{ $msg['user'] ?? 'User' }}
                                <span class="text-[9px] px-1.5 py-0.5 rounded font-extrabold uppercase {{ $roleColor }}">
                                    {{ $roleUpper }}
                                </span>
                            </span>
                            <span class="text-[10px] font-mono text-slate-400">{{ $msg['created_at'] ?? '' }}</span>
                        </div>
                        <div class="text-xs text-slate-200 whitespace-pre-line pt-1 leading-relaxed">
                            {{ $msg['message'] ?? '' }}
                        </div>
                    </div>
                </div>
            @empty
                <div class="bg-slate-900/80 border border-slate-700/80 rounded-xl p-8 flex flex-col items-center justify-center text-center space-y-2 min-h-[120px]">
                    <i class="fa-regular fa-comment-dots text-3xl text-slate-500"></i>
                    <p class="text-xs text-slate-400 font-medium">No conversation for this ticket yet.</p>
                </div>
            @endforelse
        </div>

        <div id="typing-indicator" class="hidden mt-2"></div>

        <!-- BORANG CHAT -->
        <form id="chatForm" action="{{ route('tickets.update', $ticket->id) }}" method="POST" class="space-y-3 border-t border-slate-700/80 pt-3">
            @csrf
            @method('PUT')

            <!-- INPUT HIDDEN UNTUK MELEWATI VALIDASI CONTROLLER -->
            <input type="hidden" name="title" value="{{ $ticket->title }}">
            <input type="hidden" name="status" value="{{ $ticket->status }}">
            <input type="hidden" name="priority" value="{{ $ticket->priority }}">
            <input type="hidden" name="category" value="{{ $ticket->category }}">
            <input type="hidden" name="description" value="{{ $ticket->description }}">
            <input type="hidden" name="assigned_to" value="{{ $ticket->assigned_to }}">

            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-2">
                <label class="font-bold text-xs text-slate-300">Add New Message / Comment:</label>
                
                <button type="button" onclick="generateAiSolution()" class="w-full sm:w-auto border border-cyan-500/50 hover:bg-cyan-500/20 text-cyan-300 font-semibold text-xs px-3.5 py-1.5 rounded-full flex items-center justify-center gap-2 transition bg-slate-900 shadow-sm cursor-pointer">
                    <i class="fa-solid fa-wand-magic-sparkles text-cyan-400"></i>
                    <span>Suggest AI Solution</span>
                </button>
            </div>

            <div id="ai-loading" class="hidden text-xs text-cyan-400 font-semibold flex items-center gap-2 py-1">
                <i class="fa-solid fa-spinner fa-spin"></i> Generating AI solution suggestion...
            </div>

            <textarea id="comment_box" name="resolution_notes" rows="3" placeholder="Type response or update here..."
                      class="w-full bg-slate-900 border border-slate-700 rounded-xl p-3 text-xs text-slate-100 placeholder-slate-500 focus:outline-none focus:border-cyan-400" required></textarea>

            <button type="submit" id="btnSubmitChat" class="w-full bg-gradient-to-r from-cyan-600 to-blue-600 hover:from-cyan-500 hover:to-blue-500 text-white font-black text-xs py-2.5 rounded-xl flex items-center justify-center gap-2 transition shadow cursor-pointer">
                <i class="fa-solid fa-paper-plane"></i> Send Message
            </button>
        </form>
    </div>

</div>

<script>
// Skrip Real-Time Countdown SLA
document.addEventListener("DOMContentLoaded", function() {
    @if(strtolower($ticket->status) !== 'closed' && isset($targetSlaDate) &&$targetSlaDate)
        const targetIso = "{{ $targetSlaDate->toISOString() }}";
        const targetTime = new Date(targetIso).getTime();
        const countdownElement = document.getElementById("sla-countdown");

        function updateSlaCountdown() {
            if (!countdownElement) return;

            const now = new Date().getTime();
            const diff = targetTime - now;

            if (diff <= 0) {
                countdownElement.className = "bg-rose-600 text-white font-bold px-3 py-1 rounded shadow text-xs";
                countdownElement.innerText = "SLA Breached!";
            } else {
                const hours = Math.floor(diff / (1000 * 60 * 60));
                const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((diff % (1000 * 60)) / 1000);

                countdownElement.className = "bg-rose-500/20 border border-rose-500/40 text-rose-300 font-extrabold px-3 py-1 rounded-lg shadow-sm text-xs font-mono";
                countdownElement.innerText = `${hours}h ${minutes}m ${seconds}s`;
            }
        }

        updateSlaCountdown();
        setInterval(updateSlaCountdown, 1000);
    @endif
});

const notifiedMessageIds = new Set();

document.addEventListener("DOMContentLoaded", function() {
    if ("Notification" in window && Notification.permission !== "granted") {
        Notification.requestPermission();
    }
    scrollToBottom();
});

function scrollToBottom() {
    const container = document.getElementById('chat-messages-container');
    if (container) {
        container.scrollTop = container.scrollHeight;
    }
}

function showDesktopNotification(title, body, uniqueKey) {
    if (uniqueKey && notifiedMessageIds.has(uniqueKey)) {
        return;
    }

    if ("Notification" in window && Notification.permission === "granted") {
        new Notification(title, {
            body: body,
            icon: '/favicon.ico',
            tag: uniqueKey
        });

        if (uniqueKey) {
            notifiedMessageIds.add(uniqueKey);
        }
    }
}

function fetchMessages() {
    fetch(window.location.href, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(res => {
        if (!res.ok) throw new Error("Network error");
        return res.text();
    })
    .then(html => {
        const parser = new DOMParser();
        const doc = parser.parseFromString(html, 'text/html');
        const newChatContent = doc.getElementById('chat-messages-container');
        if (newChatContent) {
            const container = document.getElementById('chat-messages-container');
            const isScrolledToBottom = container.scrollHeight - container.clientHeight <= container.scrollTop + 50;
            
            if (container.innerHTML.trim() !== newChatContent.innerHTML.trim()) {
                container.innerHTML = newChatContent.innerHTML;
            }
            
            if (isScrolledToBottom) {
                scrollToBottom();
            }
        }
    })
    .catch(err => console.error("Error refreshing messages:", err));
}

document.getElementById('chatForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const form = this;
    const commentBox = document.getElementById('comment_box');
    const submitBtn = document.getElementById('btnSubmitChat');
    const messageText = commentBox.value.trim();
    
    if (!messageText) return;

    const formData = new FormData(form);
    const token = document.querySelector('input[name="_token"]').value;

    submitBtn.disabled = true;
    submitBtn.classList.add('opacity-50');

    fetch(form.action, {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': token,
            'Accept': 'application/json, text/html'
        }
    })
    .then(async res => {
        if (!res.ok) {
            const errData = await res.json().catch(() => null);
            throw new Error(errData?.message || 'Failed to send message.');
        }
        return res;
    })
    .then(() => {
        const currentUser = "{{ auth()->user()->name }}";
        const currentRole = "{{ strtoupper(auth()->user()->role) }}";

        let roleColor = "bg-cyan-600 text-white";
        if (currentRole === "ADMIN") {
            roleColor = "bg-rose-600 text-white";
        } else if (currentRole === "TECHNICIAN") {
            roleColor = "bg-slate-700 text-slate-200 border border-slate-600";
        }

        const now = new Date();
        const formattedTime = now.toLocaleString('en-GB', {
            day: '2-digit',
            month: 'short',
            year: 'numeric',
            hour: '2-digit',
            minute: '2-digit',
            hour12: true
        }).toUpperCase();

        const container = document.getElementById('chat-messages-container');

        container.insertAdjacentHTML('beforeend', `
            <div class="flex flex-col items-end">
                <div class="max-w-[90%] sm:max-w-[85%] bg-slate-900 border border-slate-700/80 rounded-2xl p-3.5 shadow-md space-y-1">
                    <div class="flex items-center justify-between gap-2 sm:gap-4 text-[11px] text-slate-400 border-b border-slate-800 pb-1.5 flex-wrap">
                        <span class="font-bold text-slate-100 flex items-center gap-1.5">
                            ${currentUser}
                            <span class="text-[9px] px-1.5 py-0.5 rounded font-extrabold uppercase ${roleColor}">
                                ${currentRole}
                            </span>
                        </span>
                        <span class="text-[10px] font-mono text-slate-400">
                            ${formattedTime}
                        </span>
                    </div>
                    <div class="text-xs text-slate-200 whitespace-pre-line pt-1 leading-relaxed">
                        ${messageText}
                    </div>
                </div>
            </div>
        `);

        commentBox.value = '';
        submitBtn.disabled = false;
        submitBtn.classList.remove('opacity-50');
        scrollToBottom();
    })
    .catch(err => {
        alert(err.message || "Failed to send message. Please try again.");
        submitBtn.disabled = false;
        submitBtn.classList.remove('opacity-50');
    });
});

document.addEventListener("DOMContentLoaded", function () {
    const ticketId = {{ $ticket->id }};
    const onlineUsers = new Set();

    const techStatus = document.getElementById('technician-status');
    const technicianId = techStatus?.dataset.techId;

    if (window.Echo) {
        window.Echo.join('online-users')
            .here((users) => {
                users.forEach(user => onlineUsers.add(String(user.id)));
                updateTechnicianStatus();
            })
            .joining((user) => {
                onlineUsers.add(String(user.id));
                updateTechnicianStatus();
            })
            .leaving((user) => {
                onlineUsers.delete(String(user.id));
                updateTechnicianStatus();
            });

        function updateTechnicianStatus() {
            if (!technicianId || !techStatus) return;

            if (onlineUsers.has(String(technicianId))) {
                techStatus.className = 'text-emerald-400 text-xs font-semibold whitespace-nowrap flex items-center gap-1.5';
                techStatus.innerHTML = '<span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span> Online';
            } else {
                techStatus.className = 'text-slate-400 text-xs font-semibold whitespace-nowrap flex items-center gap-1.5';
                techStatus.innerHTML = '<span class="w-2 h-2 rounded-full bg-slate-500"></span> Offline';
            }
        }

        let typingTimer;
        const commentBox = document.getElementById('comment_box');
        if (commentBox) {
            commentBox.addEventListener('input', function () {
                clearTimeout(typingTimer);
                fetch(`/tickets/${ticketId}/typing`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    }
                });

                typingTimer = setTimeout(() => {}, 1000);
            });
        }

        window.Echo.channel(`ticket.${ticketId}`)
            .listen('.message.sent', (e) => {
                const msg = e.message;
                const currentUser = "{{ auth()->user()->name }}";
                
                if (msg.user === currentUser) return;

                const uniqueKey = e.id || `${msg.user}_${msg.message}_${msg.created_at || ''}`;

                let roleColor = "bg-cyan-600 text-white";
                if ((msg.role || '').toUpperCase() === "ADMIN") {
                    roleColor = "bg-rose-600 text-white";
                } else if ((msg.role || '').toUpperCase() === "TECHNICIAN") {
                    roleColor = "bg-slate-700 text-slate-200 border border-slate-600";
                }

                document.getElementById('chat-messages-container')
                    .insertAdjacentHTML('beforeend', `
                    <div class="flex flex-col items-start">
                        <div class="max-w-[90%] sm:max-w-[85%] bg-slate-900 border border-slate-700/80 rounded-2xl p-3.5 shadow-md space-y-1">
                            <div class="flex items-center justify-between gap-2 sm:gap-4 text-[11px] text-slate-400 border-b border-slate-800 pb-1.5 flex-wrap">
                                <span class="font-bold text-slate-100 flex items-center gap-1.5">
                                    ${msg.user}
                                    <span class="text-[9px] px-1.5 py-0.5 rounded font-extrabold uppercase ${roleColor}">
                                        ${msg.role}
                                    </span>
                                </span>
                                <span class="text-[10px] font-mono text-slate-400">
                                    ${msg.created_at ?? ''}
                                </span>
                            </div>
                            <div class="text-xs text-slate-200 whitespace-pre-line pt-1 leading-relaxed">
                                ${msg.message}
                            </div>
                        </div>
                    </div>
                `);

                scrollToBottom();
            })
            .listen('.user.typing', (e) => {
                const currentUser = "{{ auth()->user()->name }}";
                if (e.userName === currentUser) return;

                const indicator = document.getElementById('typing-indicator');
                if (!indicator) return;

                indicator.classList.remove('hidden');

                let role = (e.role || '').toUpperCase();
                let dotColor = 'bg-cyan-400';
                let textColor = 'text-cyan-300';
                let wrapperColor = 'bg-cyan-950/60 border-cyan-800/80';
                let roleText = 'Technician';

                if (role === 'ADMIN') {
                    dotColor = 'bg-rose-500';
                    textColor = 'text-rose-300';
                    wrapperColor = 'bg-rose-950/60 border-rose-800/80';
                    roleText = 'Admin';
                } else if (role === 'STAFF') {
                    dotColor = 'bg-purple-400';
                    textColor = 'text-purple-300';
                    wrapperColor = 'bg-purple-950/60 border-purple-800/80';
                    roleText = 'Staff';
                } else if (role === 'TECHNICIAN') {
                    roleText = 'Technician';
                }

                indicator.innerHTML = `
                <div class="flex items-center gap-2 ${wrapperColor} border rounded-full px-4 py-2 w-fit shadow-sm">
                    <div class="flex gap-1">
                        <span class="w-2 h-2 ${dotColor} rounded-full animate-bounce"></span>
                        <span class="w-2 h-2 ${dotColor} rounded-full animate-bounce" style="animation-delay:0.15s"></span>
                        <span class="w-2 h-2 ${dotColor} rounded-full animate-bounce" style="animation-delay:0.30s"></span>
                    </div>
                    <span class="text-xs font-medium ${textColor}">
                        ${e.userName} is typing...
                    </span>
                </div>
                `;

                clearTimeout(window.typingIndicatorTimeout);
                window.typingIndicatorTimeout = setTimeout(() => {
                    indicator.innerHTML = '';
                    indicator.classList.add('hidden');
                }, 2000);
            });
    }
});

function generateAiSolution() {
    const commentBox = document.getElementById('comment_box');
    const loadingState = document.getElementById('ai-loading');
    
    const title = document.getElementById('ticket_title') ? document.getElementById('ticket_title').innerText : '';
    const desc = document.getElementById('ticket_desc') ? document.getElementById('ticket_desc').innerText : '';
    const category = document.getElementById('ticket_category') ? document.getElementById('ticket_category').innerText : '';

    const fullPrompt = `Title: ${title}\nCategory: ${category}\nProblem Description: ${desc}`;

    loadingState.classList.remove('hidden');
    commentBox.disabled = true;

    const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}';

    fetch("{{ Route::has('ai.suggest') ? route('ai.suggest') : '/ai/suggest-solution' }}", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": token,
            "Accept": "application/json"
        },
        body: JSON.stringify({ prompt: fullPrompt })
    })
    .then(res => res.json())
    .then(data => {
        loadingState.classList.add('hidden');
        commentBox.disabled = false;
        
        if (data.reply) {
            commentBox.value = data.reply;
        } else if (data.error) {
            alert('Failed to get AI suggestion: ' + data.error);
        } else {
            alert('No response received from AI server.');
        }
    })
    .catch(err => {
        loadingState.classList.add('hidden');
        commentBox.disabled = false;
        alert('Network connection error to AI server.');
        console.error(err);
    });
}
</script>
@endsection