@extends('layouts.app')

@section('content')

@if(request()->has('manage'))
    <!-- ==========================================
         MODE 1: MANAGE TICKETS (Dengan Search, Filter, SLA, Grid/Table & Action Hierarchy)
         ========================================== -->
    <div class="w-full space-y-6 container mx-auto px-2 sm:px-4 pt-0 pb-6">
        
        <!-- Header Page & View Switch Toggle -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h1 class="text-xl sm:text-2xl font-bold text-slate-100 flex items-center gap-2">
                    <i class="fa-solid fa-laptop-code text-cyan-400"></i> Ticket Management
                </h1>
                <p class="text-xs text-slate-300 mt-1">List of IT service complaint tickets</p>
            </div>
            
            <div class="flex items-center gap-3 w-full sm:w-auto justify-between sm:justify-end">
                <!-- 2. Grid View vs Table View Switch -->
                <div class="bg-slate-900 border border-slate-700/80 p-1 rounded-xl flex items-center gap-1 shadow-inner">
                    <button id="btn-view-grid" onclick="switchView('grid')" class="px-3 py-1.5 rounded-lg text-xs font-bold transition flex items-center gap-1.5 bg-indigo-600/30 text-indigo-300 border border-indigo-500/50">
                        <i class="fa-solid fa-border-all"></i> <span class="hidden sm:inline">Grid</span>
                    </button>
                    <button id="btn-view-table" onclick="switchView('table')" class="px-3 py-1.5 rounded-lg text-xs font-bold transition flex items-center gap-1.5 text-slate-400 hover:text-slate-200">
                        <i class="fa-solid fa-list-ul"></i> <span class="hidden sm:inline">Table</span>
                    </button>
                </div>

                <a href="{{ route('tickets.create') }}" class="bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-semibold px-4 py-2.5 rounded-lg shadow transition flex items-center justify-center gap-2">
                    <i class="fa-solid fa-plus"></i> Create New Ticket
                </a>
            </div>
        </div>

        <!-- 1. SEARCH & FILTER CONTROLS BAR -->
        <div class="bg-slate-900/90 border border-slate-800 rounded-2xl p-4 space-y-4 shadow-xl">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                <!-- Search Input -->
                <div class="relative md:col-span-1">
                    <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-3.5 text-slate-500 text-xs"></i>
                    <input type="text" id="ticketSearchInput" onkeyup="filterTickets()" placeholder="Search ID, title, or reporter..." 
                           class="w-full bg-slate-950 text-slate-200 text-xs rounded-xl pl-9 pr-3 py-2.5 border border-slate-800 outline-none focus:border-indigo-500 transition">
                </div>

                <!-- Filter Category -->
                <div>
                    <select id="filterCategory" onchange="filterTickets()" class="w-full bg-slate-950 text-slate-300 text-xs rounded-xl px-3 py-2.5 border border-slate-800 outline-none focus:border-indigo-500 transition cursor-pointer">
                        <option value="all">All Categories</option>
                        <option value="hardware">💻 Hardware</option>
                        <option value="software">⚙️ Software</option>
                        <option value="network">🌐 Network</option>
                        <option value="other">📦 Other</option>
                    </select>
                </div>

                <!-- Filter Priority -->
                <div>
                    <select id="filterPriority" onchange="filterTickets()" class="w-full bg-slate-950 text-slate-300 text-xs rounded-xl px-3 py-2.5 border border-slate-800 outline-none focus:border-indigo-500 transition cursor-pointer">
                        <option value="all">All Priorities</option>
                        <option value="low">🔵 Low</option>
                        <option value="medium">🟡 Medium</option>
                        <option value="high">🔴 High / Critical</option>
                    </select>
                </div>
            </div>

            <!-- Status Filter Pill Badges -->
            <div class="flex items-center gap-2 pt-1 border-t border-slate-800/80 overflow-x-auto">
                <span class="text-[11px] font-bold text-slate-400 mr-2 shrink-0">Status:</span>
                <button onclick="setStatusFilter('all')" class="status-pill px-3 py-1 rounded-full text-[11px] font-bold transition bg-indigo-600/30 text-indigo-300 border border-indigo-500/50" data-status="all">All</button>
                <button onclick="setStatusFilter('open')" class="status-pill px-3 py-1 rounded-full text-[11px] font-bold transition bg-slate-800 text-slate-400 border border-slate-700 hover:text-white" data-status="open">Open</button>
                <button onclick="setStatusFilter('in progress')" class="status-pill px-3 py-1 rounded-full text-[11px] font-bold transition bg-slate-800 text-slate-400 border border-slate-700 hover:text-white" data-status="in progress">In Progress</button>
                <button onclick="setStatusFilter('closed')" class="status-pill px-3 py-1 rounded-full text-[11px] font-bold transition bg-slate-800 text-slate-400 border border-slate-700 hover:text-white" data-status="closed">Closed</button>
            </div>
        </div>

        <!-- VIEW 1: GRID / CARD LAYOUT (DEFAULT) -->
        <div id="grid-container" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 items-start">
            @forelse($tickets as $ticket)
                @php
                    $statusLower = strtolower($ticket->status ?? 'open');
                    $priorityLower = strtolower($ticket->priority ?? 'medium');

                    $borderClass = 'border-rose-500/70 shadow-rose-950/30';
                    if($statusLower == 'in progress') $borderClass = 'border-amber-500/70 shadow-amber-950/30';
                    if($statusLower == 'closed') $borderClass = 'border-emerald-500/70 shadow-emerald-950/30';
                @endphp
                <div class="ticket-card bg-slate-900/90 backdrop-blur-md p-4 sm:p-5 rounded-xl border-2 {{ $borderClass }} shadow-2xl flex flex-col justify-between space-y-4 w-full transition"
                     data-id="#{{ $ticket->id }}"
                     data-title="{{ strtolower($ticket->title) }}"
                     data-reporter="{{ strtolower($ticket->user->name ?? ($ticket->user_name ?? 'staff')) }}"
                     data-category="{{ strtolower($ticket->category) }}"
                     data-priority="{{ $priorityLower }}"
                     data-status="{{ $statusLower }}">
                    
                    <div class="space-y-3">
                        <!-- ID, SLA Badge & Status Header -->
                        <div class="flex justify-between items-center text-xs">
                            <span class="text-cyan-400 font-mono font-extrabold text-sm">#{{ $ticket->id }}</span>
                            
                            <div class="flex items-center gap-1.5">
                                <!-- 3. Visual SLA Timer Badge -->
                                @if($statusLower != 'closed')
                                    <span class="text-[10px] font-mono px-2 py-0.5 rounded bg-slate-950 text-slate-300 border border-slate-800 flex items-center gap-1">
                                        <i class="fa-regular fa-clock text-cyan-400"></i>
                                        @if($priorityLower == 'high') <span class="text-rose-400 font-bold">&lt; 3h</span>
                                        @elseif($priorityLower == 'medium') <span class="text-amber-400">&lt; 12h</span>
                                        @else <span class="text-sky-400">&lt; 24h</span>
                                        @endif
                                    </span>
                                @endif

                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold tracking-wider uppercase shadow-sm
                                    {{ $statusLower == 'open' ? 'bg-rose-500/20 text-rose-300 border border-rose-500/50' : '' }}
                                    {{ $statusLower == 'in progress' ? 'bg-amber-500/20 text-amber-300 border border-amber-500/50' : '' }} 
                                    {{ $statusLower == 'closed' ? 'bg-emerald-500/20 text-emerald-300 border border-emerald-500/50' : '' }}">
                                    {{ $ticket->status }}
                                </span>
                            </div>
                        </div>

                        <!-- Ticket Title -->
                        <div class="flex items-center gap-2">
                            <h3 class="font-extrabold text-white text-sm line-clamp-2 leading-snug tracking-wide">
                                {{ $ticket->title }}
                            </h3>

                            @if($ticket->isUnreadFor(auth()->id()))
                                <span class="bg-red-600 text-white text-[10px] px-2 py-0.5 rounded-full font-bold shrink-0">
                                    🔴 NEW
                                </span>
                            @endif
                        </div>

                        <!-- Ticket Info List -->
                        <div class="text-xs space-y-2 text-slate-200 pt-2 border-t border-slate-700/80 font-medium">
                            <div class="flex justify-between items-center">
                                <span class="text-slate-300 font-semibold flex items-center gap-1.5">
                                    <i class="fa-solid fa-user text-cyan-400 text-xs w-4"></i>Reporter:
                                </span> 
                                <span class="text-slate-100 font-bold truncate max-w-[140px] text-right">{{ $ticket->user->name ?? ($ticket->user_name ?? 'Staff User') }}</span>
                            </div>

                            <div class="flex justify-between items-center">
                                <span class="text-slate-300 font-semibold flex items-center gap-1.5">
                                    <i class="fa-solid fa-laptop text-cyan-400 text-xs w-4"></i>Device:
                                </span> 
                                <span class="text-cyan-300 font-mono font-bold">{{ $ticket->asset->asset_tag ?? ($ticket->asset_tag ?? '-- None --') }}</span>
                            </div>

                            <div class="flex justify-between items-center">
                                <span class="text-slate-300 font-semibold flex items-center gap-1.5">
                                    <i class="fa-solid fa-layer-group text-cyan-400 text-xs w-4"></i>Category:
                                </span> 
                                <span class="text-slate-100 font-bold">{{ $ticket->category }}</span>
                            </div>

                            <div class="flex justify-between items-center">
                                <span class="text-slate-300 font-semibold flex items-center gap-1.5">
                                    <i class="fa-solid fa-signal text-cyan-400 text-xs w-4"></i>Priority:
                                </span> 
                                @php
                                    $pColor = match($priorityLower) {
                                        'high' => 'bg-rose-600 text-white border-rose-400',
                                        'medium' => 'bg-amber-400 text-slate-950 border-amber-300',
                                        'low' => 'bg-sky-500 text-white border-sky-300',
                                        default => 'bg-slate-600 text-white border-slate-400',
                                    };
                                @endphp
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold border {{ $pColor }} shadow-sm">
                                    {{ $ticket->priority }}
                                </span>
                            </div>

                            <div class="flex justify-between items-center">
                                <span class="text-slate-300 font-semibold flex items-center gap-1.5">
                                    <i class="fa-solid fa-user-gear text-cyan-400 text-xs w-4"></i>Technician:
                                </span> 
                                <span class="text-teal-300 font-bold truncate max-w-[140px] text-right">{{ $ticket->technician->name ?? 'Auto-Assigned' }}</span>
                            </div>

                            <div class="flex justify-between items-center text-[10px] text-slate-400 pt-1">
                                <span class="text-slate-300 font-medium flex items-center gap-1.5">
                                    <i class="fa-solid fa-clock text-cyan-400 text-xs w-4"></i>Created Date:
                                </span> 
                                <span class="text-slate-300 font-semibold">{{ $ticket->created_at ? $ticket->created_at->format('d M Y, h:i A') : '-' }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- 4. Action Buttons Hierarchy -->
                    <div class="flex items-center justify-between gap-1.5 text-[11px] pt-2 border-t border-slate-700/80 font-bold">
                        <!-- View (Primary Action) -->
                        <a href="{{ route('tickets.show', $ticket->id) }}" class="flex-1 bg-indigo-600/30 hover:bg-indigo-600 text-indigo-200 border border-indigo-500/50 text-center py-1.5 rounded-lg transition flex items-center justify-center gap-1">
                            <i class="fa-solid fa-eye"></i> View
                        </a>
                        <!-- Edit (Secondary Action) -->
                        <a href="{{ route('tickets.edit', $ticket->id) }}" class="flex-1 bg-slate-800 hover:bg-slate-700 text-slate-300 border border-slate-700 text-center py-1.5 rounded-lg transition flex items-center justify-center gap-1">
                            <i class="fa-solid fa-pen-to-square"></i> Edit
                        </a>
                        <!-- Delete (Destructive Icon Only to avoid accidental deletion) -->
                        <form action="{{ route('tickets.destroy', $ticket->id) }}" method="POST" onsubmit="return confirm('Delete this ticket?')" class="shrink-0">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="px-2.5 py-1.5 bg-slate-800 hover:bg-rose-600 text-slate-400 hover:text-white border border-slate-700 rounded-lg transition" title="Delete Ticket">
                                <i class="fa-solid fa-trash-can"></i>
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-12 text-slate-400 font-medium">
                    No tickets registered yet.
                </div>
            @endforelse
        </div>

        <!-- VIEW 2: TABLE LAYOUT (HIDDEN BY DEFAULT) -->
        <div id="table-container" class="hidden bg-slate-900/90 border border-slate-800 rounded-2xl overflow-hidden shadow-xl">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-slate-300">
                    <thead class="bg-slate-950 text-slate-400 uppercase font-mono text-[10px] border-b border-slate-800">
                        <tr>
                            <th class="p-3.5">ID</th>
                            <th class="p-3.5">Title</th>
                            <th class="p-3.5">Reporter</th>
                            <th class="p-3.5">Category</th>
                            <th class="p-3.5">Priority</th>
                            <th class="p-3.5">Status</th>
                            <th class="p-3.5">Technician</th>
                            <th class="p-3.5 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-800/80">
                        @forelse($tickets as $ticket)
                        @php
                            $statusLower = strtolower($ticket->status ?? 'open');
                            $priorityLower = strtolower($ticket->priority ?? 'medium');

                            $statusClass = 'bg-rose-500/20 text-rose-300 border-rose-500/50';
                            if ($statusLower == 'in progress') $statusClass = 'bg-amber-500/20 text-amber-300 border-amber-500/50';
                            if ($statusLower == 'closed') $statusClass = 'bg-emerald-500/20 text-emerald-300 border-emerald-500/50';
                        @endphp
                        <tr class="ticket-row hover:bg-slate-800/40 transition"
                            data-id="#{{ $ticket->id }}"
                            data-title="{{ strtolower($ticket->title) }}"
                            data-reporter="{{ strtolower($ticket->user->name ?? ($ticket->user_name ?? 'staff')) }}"
                            data-category="{{ strtolower($ticket->category) }}"
                            data-priority="{{ $priorityLower }}"
                            data-status="{{ $statusLower }}">
                            <td class="p-3.5 font-mono font-bold text-cyan-400">#{{ $ticket->id }}</td>
                            <td class="p-3.5 font-bold text-slate-100 max-w-xs truncate">{{ $ticket->title }}</td>
                            <td class="p-3.5">{{ $ticket->user->name ?? ($ticket->user_name ?? 'Staff') }}</td>
                            <td class="p-3.5">{{ $ticket->category }}</td>
                            <td class="p-3.5"><span class="px-2 py-0.5 rounded text-[10px] font-bold border border-slate-700 capitalize">{{ $ticket->priority }}</span></td>
                            <td class="p-3.5"><span class="px-2 py-0.5 rounded text-[10px] font-bold border {{ $statusClass }} uppercase">{{ $ticket->status }}</span></td>
                            <td class="p-3.5">{{ $ticket->technician->name ?? 'Auto-Assigned' }}</td>
                            <td class="p-3.5 text-right space-x-1">
                                <a href="{{ route('tickets.show', $ticket->id) }}" class="px-2 py-1 bg-indigo-600/30 text-indigo-300 rounded font-bold hover:bg-indigo-600 hover:text-white transition">View</a>
                                <a href="{{ route('tickets.edit', $ticket->id) }}" class="px-2 py-1 bg-slate-800 text-slate-300 rounded font-bold hover:bg-slate-700 transition">Edit</a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="p-8 text-center text-slate-400">No tickets found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    <!-- JavaScript Controls for Search, Filtering & View Switching -->
    <script>
        let activeStatus = 'all';

        function switchView(type) {
            const grid = document.getElementById('grid-container');
            const table = document.getElementById('table-container');
            const btnGrid = document.getElementById('btn-view-grid');
            const btnTable = document.getElementById('btn-view-table');

            if (type === 'grid') {
                grid.classList.remove('hidden');
                table.classList.add('hidden');
                btnGrid.className = 'px-3 py-1.5 rounded-lg text-xs font-bold transition flex items-center gap-1.5 bg-indigo-600/30 text-indigo-300 border border-indigo-500/50';
                btnTable.className = 'px-3 py-1.5 rounded-lg text-xs font-bold transition flex items-center gap-1.5 text-slate-400 hover:text-slate-200';
            } else {
                grid.classList.add('hidden');
                table.classList.remove('hidden');
                btnTable.className = 'px-3 py-1.5 rounded-lg text-xs font-bold transition flex items-center gap-1.5 bg-indigo-600/30 text-indigo-300 border border-indigo-500/50';
                btnGrid.className = 'px-3 py-1.5 rounded-lg text-xs font-bold transition flex items-center gap-1.5 text-slate-400 hover:text-slate-200';
            }
        }

        function setStatusFilter(status) {
            activeStatus = status.toLowerCase();
            document.querySelectorAll('.status-pill').forEach(btn => {
                if (btn.getAttribute('data-status').toLowerCase() === activeStatus) {
                    btn.className = 'status-pill px-3 py-1 rounded-full text-[11px] font-bold transition bg-indigo-600/30 text-indigo-300 border border-indigo-500/50';
                } else {
                    btn.className = 'status-pill px-3 py-1 rounded-full text-[11px] font-bold transition bg-slate-800 text-slate-400 border border-slate-700 hover:text-white';
                }
            });
            filterTickets();
        }

        function filterTickets() {
            const searchInput = document.getElementById('ticketSearchInput').value.toLowerCase().trim();
            const categoryVal = document.getElementById('filterCategory').value.toLowerCase();
            const priorityVal = document.getElementById('filterPriority').value.toLowerCase();

            const cards = document.querySelectorAll('.ticket-card');
            const rows = document.querySelectorAll('.ticket-row');

            const processElement = (el) => {
                const id = (el.getAttribute('data-id') || '').toLowerCase();
                const title = (el.getAttribute('data-title') || '').toLowerCase();
                const reporter = (el.getAttribute('data-reporter') || '').toLowerCase();
                const category = (el.getAttribute('data-category') || '').toLowerCase();
                const priority = (el.getAttribute('data-priority') || '').toLowerCase();
                const status = (el.getAttribute('data-status') || '').toLowerCase();

                const matchesSearch = !searchInput || id.includes(searchInput) || title.includes(searchInput) || reporter.includes(searchInput);
                const matchesCategory = (categoryVal === 'all') || (category === categoryVal);
                const matchesPriority = (priorityVal === 'all') || (priority === priorityVal);
                const matchesStatus = (activeStatus === 'all') || (status === activeStatus);

                if (matchesSearch && matchesCategory && matchesPriority && matchesStatus) {
                    el.style.display = '';
                } else {
                    el.style.display = 'none';
                }
            };

            cards.forEach(processElement);
            rows.forEach(processElement);
        }

        document.addEventListener("DOMContentLoaded", function() {
            filterTickets();
        });
    </script>

@else
    <!-- ==========================================
         MODE 2: MAIN DASHBOARD (TIDAK DIUSIK LANSUNG)
         ========================================== -->
    <div class="max-w-[1180px] mx-auto space-y-5 px-3 py-4">

        <!-- 1) User Greeting Card -->
        <div class="bg-slate-900/90 text-slate-100 p-5 rounded-2xl shadow-2xl flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 border border-slate-800/80 backdrop-blur-xl relative overflow-hidden">
            <div class="absolute -right-10 -bottom-10 w-40 h-40 bg-cyan-500/10 rounded-full blur-3xl pointer-events-none"></div>
            <div>
                <h1 class="text-xl sm:text-2xl font-black text-slate-100 flex items-center gap-2">
                    Welcome, {{ Auth::user()->name ?? 'User' }}! 👋
                </h1>
                <p class="text-xs text-slate-400 mt-1 font-medium">Here is a summary of your IT support ticket status and system activities today.</p>
            </div>
            <a href="{{ route('tickets.create') }}" class="w-full sm:w-auto bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-bold px-4 py-2.5 rounded-xl shadow-lg transition-all flex items-center justify-center gap-2 whitespace-nowrap">
                <i class="fa-solid fa-plus"></i> Create New Ticket
            </a>
        </div>

        <!-- 2) 4 REDESIGNED SR DEVOPS TICKET KPI CARDS -->
        @if(Auth::user()->role === 'admin' || Auth::user()->role === 'technician')
            <!-- Admin & Technician KPI Cards (Dim / Darker) -->
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                
                <!-- TOTAL TICKETS CARD -->
                <div class="relative bg-gradient-to-b from-slate-900/90 to-slate-950/95 backdrop-blur-xl text-slate-100 p-4 rounded-2xl border border-sky-500/30 shadow-xl shadow-sky-950/20 group hover:-translate-y-1 hover:border-sky-400/70 hover:shadow-sky-500/10 transition-all duration-300 overflow-hidden">
                    <div class="absolute top-0 right-0 w-20 h-20 bg-sky-500/10 rounded-full blur-xl group-hover:bg-sky-500/20 transition-all"></div>
                    <div class="flex justify-between items-start relative z-10">
                        <div>
                            <div class="flex items-center gap-1.5">
                                <span class="w-1.5 h-1.5 rounded-full bg-sky-400 animate-pulse"></span>
                                <p class="text-[10px] font-black text-sky-400 uppercase tracking-widest">TOTAL TICKETS</p>
                            </div>
                            <h3 class="text-3xl sm:text-4xl font-black text-white tracking-tight mt-1 group-hover:text-sky-300 transition-colors">{{ count($tickets) }}</h3>
                        </div>
                        <div class="w-11 h-11 rounded-xl bg-sky-500/10 text-sky-400 border border-sky-500/30 flex items-center justify-center text-lg shadow-inner group-hover:scale-110 group-hover:bg-sky-500 group-hover:text-slate-950 transition-all duration-300">
                            <i class="fa-solid fa-ticket-simple"></i>
                        </div>
                    </div>
                    <div class="mt-3 pt-2 border-t border-slate-800/80 flex items-center justify-between text-[10px] text-slate-400">
                        <span>All Records</span>
                        <span class="font-mono text-sky-400 font-bold">Since Launch</span>
                    </div>
                </div>

                <!-- OPEN TICKETS CARD -->
                <div class="relative bg-gradient-to-b from-slate-900/90 to-slate-950/95 backdrop-blur-xl text-slate-100 p-4 rounded-2xl border border-rose-500/30 shadow-xl shadow-rose-950/20 group hover:-translate-y-1 hover:border-rose-400/70 hover:shadow-rose-500/10 transition-all duration-300 overflow-hidden">
                    <div class="absolute top-0 right-0 w-20 h-20 bg-rose-500/10 rounded-full blur-xl group-hover:bg-rose-500/20 transition-all"></div>
                    <div class="flex justify-between items-start relative z-10">
                        <div>
                            <div class="flex items-center gap-1.5">
                                <span class="w-1.5 h-1.5 rounded-full bg-rose-500 animate-ping"></span>
                                <p class="text-[10px] font-black text-rose-400 uppercase tracking-widest">OPEN TICKETS</p>
                            </div>
                            <h3 class="text-3xl sm:text-4xl font-black text-rose-400 tracking-tight mt-1 group-hover:text-rose-300 transition-colors">{{ $tickets->where('status', 'Open')->count() }}</h3>
                        </div>
                        <div class="w-11 h-11 rounded-xl bg-rose-500/10 text-rose-400 border border-rose-500/30 flex items-center justify-center text-lg shadow-inner group-hover:scale-110 group-hover:bg-rose-500 group-hover:text-slate-950 transition-all duration-300">
                            <i class="fa-solid fa-triangle-exclamation"></i>
                        </div>
                    </div>
                    <div class="mt-3 pt-2 border-t border-slate-800/80 flex items-center justify-between text-[10px] text-slate-400">
                        <span>Pending Review</span>
                        <span class="font-mono text-rose-400 font-bold">Awaiting Response</span>
                    </div>
                </div>

                <!-- IN PROGRESS CARD -->
                <div class="relative bg-gradient-to-b from-slate-900/90 to-slate-950/95 backdrop-blur-xl text-slate-100 p-4 rounded-2xl border border-amber-500/30 shadow-xl shadow-amber-950/20 group hover:-translate-y-1 hover:border-amber-400/70 hover:shadow-amber-500/10 transition-all duration-300 overflow-hidden">
                    <div class="absolute top-0 right-0 w-20 h-20 bg-amber-500/10 rounded-full blur-xl group-hover:bg-amber-500/20 transition-all"></div>
                    <div class="flex justify-between items-start relative z-10">
                        <div>
                            <div class="flex items-center gap-1.5">
                                <span class="w-1.5 h-1.5 rounded-full bg-amber-400 animate-pulse"></span>
                                <p class="text-[10px] font-black text-amber-400 uppercase tracking-widest">IN PROGRESS</p>
                            </div>
                            <h3 class="text-3xl sm:text-4xl font-black text-amber-400 tracking-tight mt-1 group-hover:text-amber-300 transition-colors">{{ $tickets->where('status', 'In Progress')->count() }}</h3>
                        </div>
                        <div class="w-11 h-11 rounded-xl bg-amber-500/10 text-amber-400 border border-amber-500/30 flex items-center justify-center text-lg shadow-inner group-hover:scale-110 group-hover:bg-amber-500 group-hover:text-slate-950 transition-all duration-300">
                            <i class="fa-solid fa-hourglass-half"></i>
                        </div>
                    </div>
                    <div class="mt-3 pt-2 border-t border-slate-800/80 flex items-center justify-between text-[10px] text-slate-400">
                        <span>Pending Resolution</span>
                        <span class="font-mono text-amber-400 font-bold">SLA Active</span>
                    </div>
                </div>

                <!-- CLOSED TICKETS CARD -->
                <div class="relative bg-gradient-to-b from-slate-900/90 to-slate-950/95 backdrop-blur-xl text-slate-100 p-4 rounded-2xl border border-emerald-500/30 shadow-xl shadow-emerald-950/20 group hover:-translate-y-1 hover:border-emerald-400/70 hover:shadow-emerald-500/10 transition-all duration-300 overflow-hidden">
                    <div class="absolute top-0 right-0 w-20 h-20 bg-emerald-500/10 rounded-full blur-xl group-hover:bg-emerald-500/20 transition-all"></div>
                    <div class="flex justify-between items-start relative z-10">
                        <div>
                            <div class="flex items-center gap-1.5">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
                                <p class="text-[10px] font-black text-emerald-400 uppercase tracking-widest">CLOSED TICKETS</p>
                            </div>
                            <h3 class="text-3xl sm:text-4xl font-black text-emerald-400 tracking-tight mt-1 group-hover:text-emerald-300 transition-colors">{{ $tickets->where('status', 'Closed')->count() }}</h3>
                        </div>
                        <div class="w-11 h-11 rounded-xl bg-emerald-500/10 text-emerald-400 border border-emerald-500/30 flex items-center justify-center text-lg shadow-inner group-hover:scale-110 group-hover:bg-emerald-500 group-hover:text-slate-950 transition-all duration-300">
                            <i class="fa-solid fa-circle-check"></i>
                        </div>
                    </div>
                    <div class="mt-3 pt-2 border-t border-slate-800/80 flex items-center justify-between text-[10px] text-slate-400">
                        <span>Completed Jobs</span>
                        <span class="font-mono text-emerald-400 font-bold">Resolved</span>
                    </div>
                </div>

            </div>
        @else
            <!-- Staff KPI Cards (Bright / Vibrant) -->
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                
                <!-- TOTAL TICKETS CARD (Cyan-Blue Gradient) -->
                <div class="relative rounded-2xl p-4 shadow-xl transform hover:-translate-y-1 transition-all duration-300 overflow-hidden" style="background: linear-gradient(135deg, #0dcaf0, #0d6efd);">
                    <div class="flex justify-between items-start relative z-10">
                        <div>
                            <div class="flex items-center gap-1.5">
                                <span class="w-1.5 h-1.5 rounded-full bg-slate-950 animate-pulse"></span>
                                <p class="text-[10px] font-black text-slate-950 uppercase tracking-widest">TOTAL TICKETS</p>
                            </div>
                            <h3 class="text-3xl sm:text-4xl font-black text-slate-950 tracking-tight mt-1">{{ count($tickets) }}</h3>
                        </div>
                        <div class="w-11 h-11 rounded-xl bg-black/15 text-slate-950 flex items-center justify-center text-lg shadow-inner transition-all duration-300">
                            <i class="fa-solid fa-ticket-simple"></i>
                        </div>
                    </div>
                    <div class="mt-3 pt-2 border-t border-black/10 flex items-center justify-between text-[10px] text-slate-900/80 font-bold">
                        <span>All Records</span>
                        <span class="font-mono">Since Launch</span>
                    </div>
                </div>

                <!-- OPEN TICKETS CARD (Purple Gradient) -->
                <div class="relative rounded-2xl p-4 shadow-xl transform hover:-translate-y-1 transition-all duration-300 overflow-hidden" style="background: linear-gradient(135deg, #6f42c1, #4e73df);">
                    <div class="flex justify-between items-start relative z-10">
                        <div>
                            <div class="flex items-center gap-1.5">
                                <span class="w-1.5 h-1.5 rounded-full bg-slate-950 animate-ping"></span>
                                <p class="text-[10px] font-black text-slate-950 uppercase tracking-widest">OPEN TICKETS</p>
                            </div>
                            <h3 class="text-3xl sm:text-4xl font-black text-slate-950 tracking-tight mt-1">{{ $tickets->where('status', 'Open')->count() }}</h3>
                        </div>
                        <div class="w-11 h-11 rounded-xl bg-black/15 text-slate-950 flex items-center justify-center text-lg shadow-inner transition-all duration-300">
                            <i class="fa-solid fa-triangle-exclamation"></i>
                        </div>
                    </div>
                    <div class="mt-3 pt-2 border-t border-black/10 flex items-center justify-between text-[10px] text-slate-900/80 font-bold">
                        <span>Pending Review</span>
                        <span class="font-mono">Awaiting Response</span>
                    </div>
                </div>

                <!-- IN PROGRESS CARD (Orange Gradient) -->
                <div class="relative rounded-2xl p-4 shadow-xl transform hover:-translate-y-1 transition-all duration-300 overflow-hidden" style="background: linear-gradient(135deg, #fd7e14, #ffc107);">
                    <div class="flex justify-between items-start relative z-10">
                        <div>
                            <div class="flex items-center gap-1.5">
                                <span class="w-1.5 h-1.5 rounded-full bg-slate-950 animate-pulse"></span>
                                <p class="text-[10px] font-black text-slate-950 uppercase tracking-widest">IN PROGRESS</p>
                            </div>
                            <h3 class="text-3xl sm:text-4xl font-black text-slate-950 tracking-tight mt-1">{{ $tickets->where('status', 'In Progress')->count() }}</h3>
                        </div>
                        <div class="w-11 h-11 rounded-xl bg-black/15 text-slate-950 flex items-center justify-center text-lg shadow-inner transition-all duration-300">
                            <i class="fa-solid fa-hourglass-half"></i>
                        </div>
                    </div>
                    <div class="mt-3 pt-2 border-t border-black/10 flex items-center justify-between text-[10px] text-slate-900/80 font-bold">
                        <span>Pending Resolution</span>
                        <span class="font-mono">SLA Active</span>
                    </div>
                </div>

                <!-- CLOSED TICKETS CARD (Green Gradient) -->
                <div class="relative rounded-2xl p-4 shadow-xl transform hover:-translate-y-1 transition-all duration-300 overflow-hidden" style="background: linear-gradient(135deg, #198754, #20c997);">
                    <div class="flex justify-between items-start relative z-10">
                        <div>
                            <div class="flex items-center gap-1.5">
                                <span class="w-1.5 h-1.5 rounded-full bg-slate-950"></span>
                                <p class="text-[10px] font-black text-slate-950 uppercase tracking-widest">CLOSED TICKETS</p>
                            </div>
                            <h3 class="text-3xl sm:text-4xl font-black text-slate-950 tracking-tight mt-1">{{ $tickets->where('status', 'Closed')->count() }}</h3>
                        </div>
                        <div class="w-11 h-11 rounded-xl bg-black/15 text-slate-950 flex items-center justify-center text-lg shadow-inner transition-all duration-300">
                            <i class="fa-solid fa-circle-check"></i>
                        </div>
                    </div>
                    <div class="mt-3 pt-2 border-t border-black/10 flex items-center justify-between text-[10px] text-slate-900/80 font-bold">
                        <span>Completed Jobs</span>
                        <span class="font-mono">Resolved</span>
                    </div>
                </div>

            </div>
        @endif

        <!-- Bottom Grid Section -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-5">
            
            <!-- 3) Recent Tickets Card (7 Columns) -->
            <div class="lg:col-span-7 bg-slate-900/90 backdrop-blur-xl text-slate-100 p-5 rounded-2xl shadow-2xl border border-slate-800/80 space-y-4">
                <div class="flex justify-between items-center border-b border-slate-800/80 pb-3">
                    <h3 class="font-bold text-slate-100 text-sm flex items-center gap-2">
                        <i class="fa-regular fa-clock text-cyan-400"></i> Recent Tickets
                    </h3>
                    <a href="{{ route('tickets.index', ['manage' => 1]) }}" class="text-[11px] text-indigo-400 hover:text-indigo-300 border border-indigo-800/80 px-3 py-1 rounded-full bg-indigo-950/60 font-semibold transition whitespace-nowrap">
                        View All Tickets &rarr;
                    </a>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-xs text-left">
                        <thead class="text-slate-400 uppercase border-b border-slate-800 text-[10px] font-bold">
                            <tr>
                                <th class="py-2.5 px-2">ID</th>
                                <th class="py-2.5 px-2">TITLE</th>
                                <th class="py-2.5 px-2">REPORTER</th>
                                <th class="py-2.5 px-2">PRIORITY</th>
                                <th class="py-2.5 px-2">STATUS</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-800/80 font-medium text-slate-300">
                            @forelse($tickets->take(5) as $ticket)
                                <tr class="hover:bg-slate-800/40 transition">
                                    <td class="py-3 px-2 font-mono font-bold text-cyan-400">#{{ $ticket->id }}</td>
                                    <td class="py-3 px-2 text-slate-100 font-semibold max-w-[160px] truncate">{{ $ticket->title }}</td>
                                    <td class="py-3 px-2 text-slate-400">{{ $ticket->user->name ?? ($ticket->user_name ?? 'Staff') }}</td>
                                    <td class="py-3 px-2">
                                        @php
                                            $badgePriorityColor = match(strtolower($ticket->priority)) {
                                                'high' => 'bg-rose-500/20 text-rose-300 border border-rose-500/40',
                                                'medium' => 'bg-amber-500/20 text-amber-300 border border-amber-500/40',
                                                'low' => 'bg-sky-500/20 text-sky-300 border border-sky-500/40',
                                                default => 'bg-slate-800 text-slate-400 border border-slate-700',
                                            };
                                        @endphp
                                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold {{ $badgePriorityColor }}">
                                            {{ $ticket->priority }}
                                        </span>
                                    </td>
                                    <td class="py-3 px-2">
                                        @php
                                            $badgeStatusColor = match($ticket->status) {
                                                'Open' => 'bg-rose-500/20 text-rose-300 border border-rose-500/40',
                                                'In Progress' => 'bg-amber-500/20 text-amber-300 border border-amber-500/40',
                                                'Closed' => 'bg-emerald-500/20 text-emerald-300 border border-emerald-500/40',
                                                default => 'bg-slate-800 text-slate-400 border border-slate-700',
                                            };
                                        @endphp
                                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold {{ $badgeStatusColor }}">
                                            {{ $ticket->status }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-8 text-center text-slate-500 italic">No recent ticket records in database.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Right Column (5 Columns) -->
            <div class="lg:col-span-5 space-y-5">
                
                <!-- 4) Category Allocation Card -->
                <div class="bg-slate-900/90 backdrop-blur-xl text-slate-100 p-5 rounded-2xl shadow-2xl border border-slate-800/80">
                    <h3 class="font-bold text-slate-100 text-sm mb-3 flex items-center gap-2">
                        <i class="fa-solid fa-chart-pie text-indigo-400"></i> Category Allocation
                    </h3>
                    <div class="h-44 relative flex items-center justify-center">
                        <canvas id="dashCatChart"></canvas>
                    </div>
                </div>

                <!-- 5) Status & IT Announcement Card -->
                <div class="bg-slate-900/90 backdrop-blur-xl text-slate-100 p-5 rounded-2xl shadow-2xl border border-slate-800/80 space-y-3">
                    <h3 class="font-bold text-slate-100 text-sm flex items-center gap-2">
                        <i class="fa-solid fa-bullhorn text-cyan-400"></i> Status & IT Announcement
                    </h3>

                    <!-- Dynamic Network Card Container -->
                    <div id="networkStatusCard" class="bg-emerald-950/40 border border-emerald-800/60 p-3 rounded-xl text-xs transition-all duration-300">
                        <p class="font-bold text-emerald-300 flex items-center gap-2 text-xs" id="networkStatusTitle">
                            <i class="fa-solid fa-circle-check text-emerald-400"></i> All Systems Operational
                        </p>
                        <p class="text-[11px] text-emerald-400/80 mt-1" id="networkStatusDesc">
                            Primary servers and Wi-Fi/Data connection are stable.
                        </p>
                    </div>

                    <!-- IT Support Hours -->
                    <div class="bg-amber-950/40 border border-amber-800/60 p-3 rounded-xl text-xs">
                        <p class="font-bold text-amber-300 flex items-center gap-2 text-xs">
                            <i class="fa-solid fa-triangle-exclamation text-amber-400"></i> IT Support Hours
                        </p>
                        <p class="text-[11px] text-amber-300/80 mt-1">Technical support operates Monday–Friday (8:30 AM – 5:30 PM).</p>
                    </div>
                </div>

            </div>

        </div>
    </div>

    <!-- Chart.js Script -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const ctxDash = document.getElementById('dashCatChart');
            if (ctxDash) {
                new Chart(ctxDash.getContext('2d'), {
                    type: 'doughnut',
                    data: {
                        labels: ['Hardware', 'Software', 'Network', 'Other'],
                        datasets: [{
                            data: [
                                {{ $tickets->where('category', 'Hardware')->count() }},
                                {{ $tickets->where('category', 'Software')->count() }},
                                {{ $tickets->where('category', 'Network')->count() }},
                                {{ $tickets->where('category', 'Other')->count() }}
                            ],
                            backgroundColor: ['#38bdf8', '#c084fc', '#34d399', '#94a3b8'],
                            borderWidth: 2,
                            borderColor: '#0f172a'
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { 
                                position: 'bottom', 
                                labels: { 
                                    boxWidth: 10, 
                                    font: { size: 10, weight: '600' },
                                    color: '#cbd5e1'
                                } 
                            }
                        }
                    }
                });
            }

            const card = document.getElementById('networkStatusCard');
            const titleEl = document.getElementById('networkStatusTitle');
            const descEl = document.getElementById('networkStatusDesc');

            async function checkNetworkStatus() {
                if (!card || !titleEl || !descEl) return;

                if (!navigator.onLine) {
                    showOfflineStatus();
                    return;
                }

                const startTime = performance.now();
                try {
                    const response = await fetch('/favicon.ico?v=' + Date.now(), { 
                        method: 'HEAD', 
                        cache: 'no-store',
                        signal: AbortSignal.timeout(3000)
                    });

                    if (!response.ok && response.status !== 0) {
                        showOfflineStatus();
                        return;
                    }

                    const duration = performance.now() - startTime;

                    if (duration > 800) {
                        setCardStatus(
                            'bg-amber-950/40 border-amber-800/60',
                            '<i class="fa-solid fa-triangle-exclamation text-amber-400"></i> <span class="text-amber-300">Slow Connection</span>',
                            `Slow server response (${Math.round(duration)}ms).`,
                            'text-amber-400/80'
                        );
                    } else {
                        setCardStatus(
                            'bg-emerald-950/40 border-emerald-800/60',
                            '<i class="fa-solid fa-circle-check text-emerald-400"></i> <span class="text-emerald-300">All Systems Operational</span>',
                            'Primary servers and Wi-Fi/Data connection are stable.',
                            'text-emerald-400/80'
                        );
                    }

                } catch (error) {
                    showOfflineStatus();
                }
            }

            function showOfflineStatus() {
                setCardStatus(
                    'bg-rose-950/40 border-rose-800/60',
                    '<i class="fa-solid fa-wifi text-rose-400"></i> <span class="text-rose-300">No Network Connection</span>',
                    'Connection lost. Please check your internet connection.',
                    'text-rose-400/80'
                );
            }

            function setCardStatus(cardClasses, titleHtml, descText, descTextColor) {
                card.className = `p-3 rounded-xl border text-xs transition-all duration-300 ${cardClasses}`;
                titleEl.className = `font-bold flex items-center gap-2 text-xs`;
                titleEl.innerHTML = titleHtml;
                descEl.className = `text-[11px] mt-1 ${descTextColor}`;
                descEl.innerText = descText;
            }

            window.addEventListener('online', checkNetworkStatus);
            window.addEventListener('offline', checkNetworkStatus);
            setInterval(checkNetworkStatus, 5000);
            checkNetworkStatus();
        });
    </script>
@endif

@endsection