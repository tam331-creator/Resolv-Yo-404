@extends('layouts.app')

@section('content')
<div class="max-w-[1400px] mx-auto space-y-6 px-3 py-4">

    <!-- Header Page Profile & Settings -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-slate-900/60 backdrop-blur-xl border border-slate-800/80 rounded-2xl p-5 shadow-xl">
        <div class="flex items-center gap-3.5">
            <div class="w-12 h-12 rounded-xl bg-cyan-500/10 border border-cyan-500/30 flex items-center justify-center text-cyan-400 text-2xl shadow-inner">
                <i class="fa-solid fa-gear"></i>
            </div>
            <div>
                <h1 class="text-2xl font-black text-slate-100 tracking-tight flex items-center gap-2">
                    Profile & Settings
                </h1>
                <p class="text-xs text-slate-400 font-medium mt-0.5">Manage your account preferences, view system activity, and monitor security.</p>
            </div>
        </div>
    </div>

    <!-- Alert Notifications -->
    @if(session('success'))
        <div class="bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 text-xs px-4 py-3.5 rounded-xl flex items-center gap-3 shadow-lg backdrop-blur-md">
            <i class="fa-solid fa-circle-check text-lg text-emerald-400"></i>
            <span class="font-semibold">{{ session('success') }}</span>
        </div>
    @endif

    @if($errors->any())
        <div class="bg-rose-500/10 border border-rose-500/30 text-rose-400 text-xs px-4 py-3.5 rounded-xl space-y-1.5 shadow-lg backdrop-blur-md">
            @foreach($errors->all() as $error)
                <div class="flex items-center gap-2.5 font-semibold"><i class="fa-solid fa-triangle-exclamation text-base"></i> {{ $error }}</div>
            @endforeach
        </div>
    @endif

    <!-- GRID UTAMA 2-KOLOM -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">

        <!-- KELOMPOK KIRI (LG: COL 7) -->
        <div class="lg:col-span-7 space-y-6">

            <!-- 👤 1. PROFILE INFORMATION CARD -->
            <div class="bg-slate-900/80 backdrop-blur-xl border border-slate-800/80 rounded-2xl p-6 shadow-2xl hover:border-slate-700/60 transition-all duration-300 relative overflow-hidden group">
                <div class="absolute top-0 right-0 w-32 h-32 bg-indigo-500/5 rounded-full blur-2xl pointer-events-none"></div>

                <div class="flex justify-between items-center border-b border-slate-800/80 pb-4 mb-5">
                    <h3 class="font-bold text-slate-100 text-sm flex items-center gap-2.5 tracking-wide">
                        <div class="w-7 h-7 rounded-lg bg-indigo-500/10 border border-indigo-500/20 text-indigo-400 flex items-center justify-center text-xs">
                            <i class="fa-solid fa-user"></i>
                        </div>
                        Profile Information
                    </h3>
                    <button onclick="toggleEditProfileModal()" class="bg-slate-800/90 hover:bg-slate-700 text-slate-200 border border-slate-700/80 text-xs font-semibold px-3.5 py-1.5 rounded-xl transition-all shadow-sm flex items-center gap-1.5 cursor-pointer hover:shadow-indigo-500/10">
                        <i class="fa-solid fa-pen-to-square text-indigo-400"></i> Edit Profile
                    </button>
                </div>

                <div class="flex flex-col md:flex-row items-center md:items-start gap-6">
                    <!-- Avatar & Info Utama -->
                    <div class="flex flex-col items-center text-center shrink-0 space-y-3">
                        <div class="relative">
                            <div class="w-22 h-22 rounded-2xl bg-gradient-to-tr from-indigo-600 via-indigo-500 to-cyan-500 text-white font-black text-2xl flex items-center justify-center shadow-xl border-2 border-slate-800 ring-4 ring-indigo-500/20">
                                {{ strtoupper(substr($user->name, 0, 2)) }}
                            </div>
                            <div class="w-4 h-4 bg-emerald-500 border-2 border-slate-900 rounded-full absolute bottom-0 right-1 shadow-md" title="Online"></div>
                        </div>
                        <div>
                            <h2 class="text-lg font-black text-white tracking-tight">{{ $user->name }}</h2>
                            <p class="text-xs text-slate-400 font-medium capitalize mt-0.5">{{ $user->role }}</p>
                        </div>
                        <span class="bg-indigo-950/80 text-indigo-300 border border-indigo-800/80 px-3.5 py-1 rounded-full text-[10px] font-bold tracking-wide shadow-inner">
                            {{ $user->department ?? 'IT Department' }}
                        </span>
                    </div>

                    <!-- List Rincian Informasi -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs w-full pt-1">
                        <div class="bg-slate-950/70 border border-slate-800/80 p-3 rounded-xl hover:border-indigo-500/30 transition-all flex items-start gap-3">
                            <div class="w-7 h-7 rounded-lg bg-slate-900 text-indigo-400 flex items-center justify-center text-xs shrink-0 mt-0.5">
                                <i class="fa-solid fa-user"></i>
                            </div>
                            <div class="min-w-0">
                                <p class="text-[10px] text-slate-400 font-medium">Full Name</p>
                                <p class="font-bold text-slate-100 mt-0.5 truncate">{{ $user->name }}</p>
                            </div>
                        </div>

                        <div class="bg-slate-950/70 border border-slate-800/80 p-3 rounded-xl hover:border-sky-500/30 transition-all flex items-start gap-3">
                            <div class="w-7 h-7 rounded-lg bg-slate-900 text-sky-400 flex items-center justify-center text-xs shrink-0 mt-0.5">
                                <i class="fa-solid fa-user-shield"></i>
                            </div>
                            <div class="min-w-0">
                                <p class="text-[10px] text-slate-400 font-medium">Role</p>
                                <span class="inline-block uppercase font-bold text-[10px] bg-sky-950/80 text-sky-400 border border-sky-800/80 px-2 py-0.5 rounded mt-0.5">
                                    {{ Auth::user()->role }}
                                </span>
                            </div>
                        </div>

                        <div class="bg-slate-950/70 border border-slate-800/80 p-3 rounded-xl hover:border-indigo-500/30 transition-all flex items-start gap-3">
                            <div class="w-7 h-7 rounded-lg bg-slate-900 text-indigo-400 flex items-center justify-center text-xs shrink-0 mt-0.5">
                                <i class="fa-solid fa-envelope"></i>
                            </div>
                            <div class="min-w-0">
                                <p class="text-[10px] text-slate-400 font-medium">Email Address</p>
                                <p class="font-bold text-slate-100 mt-0.5 font-mono truncate">{{ $user->email }}</p>
                            </div>
                        </div>

                        <div class="bg-slate-950/70 border border-slate-800/80 p-3 rounded-xl hover:border-indigo-500/30 transition-all flex items-start gap-3">
                            <div class="w-7 h-7 rounded-lg bg-slate-900 text-indigo-400 flex items-center justify-center text-xs shrink-0 mt-0.5">
                                <i class="fa-solid fa-location-dot"></i>
                            </div>
                            <div class="min-w-0">
                                <p class="text-[10px] text-slate-400 font-medium">Office Location</p>
                                <p class="font-bold text-slate-100 mt-0.5 truncate">{{ $user->office_location ?? 'Main Office' }}</p>
                            </div>
                        </div>

                        <div class="bg-slate-950/70 border border-slate-800/80 p-3 rounded-xl hover:border-indigo-500/30 transition-all flex items-start gap-3">
                            <div class="w-7 h-7 rounded-lg bg-slate-900 text-indigo-400 flex items-center justify-center text-xs shrink-0 mt-0.5">
                                <i class="fa-solid fa-building"></i>
                            </div>
                            <div class="min-w-0">
                                <p class="text-[10px] text-slate-400 font-medium">Department</p>
                                <p class="font-bold text-slate-100 mt-0.5 truncate">{{ $user->department ?? 'IT Department' }}</p>
                            </div>
                        </div>

                        <div class="bg-slate-950/70 border border-slate-800/80 p-3 rounded-xl hover:border-indigo-500/30 transition-all flex items-start gap-3">
                            <div class="w-7 h-7 rounded-lg bg-slate-900 text-indigo-400 flex items-center justify-center text-xs shrink-0 mt-0.5">
                                <i class="fa-solid fa-calendar-days"></i>
                            </div>
                            <div class="min-w-0">
                                <p class="text-[10px] text-slate-400 font-medium">Account Created</p>
                                <p class="font-bold text-slate-100 mt-0.5 truncate">{{ $user->created_at ? $user->created_at->format('d M Y') : '-' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 📊 2. MY ACTIVITY CARD (ROLE-ACCURATE KPIS) -->
            <div class="bg-slate-900/80 backdrop-blur-xl border border-slate-800/80 rounded-2xl p-6 shadow-2xl hover:border-slate-700/60 transition-all duration-300 space-y-5">
                <div class="flex justify-between items-center border-b border-slate-800/80 pb-4">
                    <h3 class="font-bold text-slate-100 text-sm flex items-center gap-2.5 tracking-wide">
                        <div class="w-7 h-7 rounded-lg bg-cyan-500/10 border border-cyan-500/20 text-cyan-400 flex items-center justify-center text-xs">
                            <i class="fa-solid fa-chart-simple"></i>
                        </div>
                        My Activity
                    </h3>
                    <select onchange="window.location.href='{{ route('settings.index') }}?filter=' + this.value" class="bg-slate-950 text-slate-300 border border-slate-800 text-xs rounded-xl px-3 py-1.5 outline-none focus:border-cyan-500 cursor-pointer font-medium shadow-inner transition">
                        <option value="this_year" {{ $myActivity['filter'] == 'this_year' ? 'selected' : '' }}>This Year</option>
                        <option value="this_month" {{ $myActivity['filter'] == 'this_month' ? 'selected' : '' }}>This Month</option>
                    </select>
                </div>

                <!-- 4 KARD KPI DINAMIK MENGIKUT ROLE -->
                <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-3.5">

                    @if(Auth::user()->role === 'staff')
                        <!-- ==================== ROLE: STAFF ==================== -->
                        <!-- 🟦 1. My Tickets Submitted -->
                        <div class="rounded-xl p-4 shadow-lg flex flex-col justify-between space-y-3 transform hover:-translate-y-0.5 transition-all duration-200" style="background: linear-gradient(135deg, #0dcaf0, #0d6efd);">
                            <div class="flex justify-between items-start">
                                <span class="text-[10px] font-black uppercase tracking-wider text-slate-950">My Tickets Submitted</span>
                                <div class="w-8 h-8 rounded-lg bg-black/15 text-slate-950 flex items-center justify-center text-sm font-bold shadow-inner">
                                    <i class="fa-solid fa-file-lines"></i>
                                </div>
                            </div>
                            <div>
                                <h4 class="text-2xl font-black text-slate-950 tracking-tight" style="color: #000000;">{{ $myActivity['created_tickets'] }}</h4>
                                <p class="text-[10px] font-bold text-slate-900/80 mt-0.5" style="color: #000000;">Total submitted</p>
                            </div>
                        </div>

                        <!-- 🟨 2. Open Tickets -->
                        <div class="rounded-xl p-4 shadow-lg flex flex-col justify-between space-y-3 transform hover:-translate-y-0.5 transition-all duration-200" style="background: linear-gradient(135deg, #ffc107, #fd7e14);">
                            <div class="flex justify-between items-start">
                                <span class="text-[10px] font-black uppercase tracking-wider text-slate-950">Open Tickets</span>
                                <div class="w-8 h-8 rounded-lg bg-black/15 text-slate-950 flex items-center justify-center text-sm font-bold shadow-inner">
                                    <i class="fa-solid fa-spinner"></i>
                                </div>
                            </div>
                            <div>
                                <h4 class="text-2xl font-black text-slate-950 tracking-tight" style="color: #000000;">{{ $myActivity['open_tickets'] }}</h4>
                                <p class="text-[10px] font-bold text-slate-900/80 mt-0.5" style="color: #000000;">Pending resolution</p>
                            </div>
                        </div>

                        <!-- 🟩 3. Resolved Tickets -->
                        <div class="rounded-xl p-4 shadow-lg flex flex-col justify-between space-y-3 transform hover:-translate-y-0.5 transition-all duration-200" style="background: linear-gradient(135deg, #198754, #20c997);">
                            <div class="flex justify-between items-start">
                                <span class="text-[10px] font-black uppercase tracking-wider text-slate-950">Resolved Tickets</span>
                                <div class="w-8 h-8 rounded-lg bg-black/15 text-slate-950 flex items-center justify-center text-sm font-bold shadow-inner">
                                    <i class="fa-solid fa-circle-check"></i>
                                </div>
                            </div>
                            <div>
                                <h4 class="text-2xl font-black text-slate-950 tracking-tight" style="color: #000000;">{{ $myActivity['resolved_tickets'] }}</h4>
                                <p class="text-[10px] font-bold text-slate-900/80 mt-0.5" style="color: #000000;">Status "Closed"</p>
                            </div>
                        </div>

                        <!-- 🟪 4. Average Satisfaction Given -->
                        <div class="rounded-xl p-4 shadow-lg flex flex-col justify-between space-y-3 transform hover:-translate-y-0.5 transition-all duration-200" style="background: linear-gradient(135deg, #6f42c1, #4e73df);">
                            <div class="flex justify-between items-start">
                                <span class="text-[10px] font-black uppercase tracking-wider text-slate-950">Avg. Satisfaction Given</span>
                                <div class="w-8 h-8 rounded-lg bg-black/15 text-slate-950 flex items-center justify-center text-sm font-bold shadow-inner">
                                    <i class="fa-solid fa-star"></i>
                                </div>
                            </div>
                            <div>
                                <h4 class="text-2xl font-black text-slate-950 tracking-tight" style="color: #000000;">{{ number_format($myActivity['average_rating'], 1) }} <span class="text-xs font-bold text-slate-900/80">/ 5.0</span></h4>
                                <p class="text-[10px] font-bold text-slate-900/80 mt-0.5" style="color: #000000;">User feedback score</p>
                            </div>
                        </div>

                    @elseif(Auth::user()->role === 'technician')
                        <!-- ==================== ROLE: TECHNICIAN ==================== -->
                        <!-- 🟦 1. Assigned Tickets -->
                        <div class="rounded-xl p-4 shadow-lg flex flex-col justify-between space-y-3 transform hover:-translate-y-0.5 transition-all duration-200" style="background: linear-gradient(135deg, #0dcaf0, #0d6efd);">
                            <div class="flex justify-between items-start">
                                <span class="text-[10px] font-black uppercase tracking-wider text-slate-950">Assigned Tickets</span>
                                <div class="w-8 h-8 rounded-lg bg-black/15 text-slate-950 flex items-center justify-center text-sm font-bold shadow-inner">
                                    <i class="fa-solid fa-clipboard-list"></i>
                                </div>
                            </div>
                            <div>
                                <h4 class="text-2xl font-black text-slate-950 tracking-tight" style="color: #000000;">{{ $myActivity['created_tickets'] }}</h4>
                                <p class="text-[10px] font-bold text-slate-900/80 mt-0.5" style="color: #000000;">Workload assigned</p>
                            </div>
                        </div>

                        <!-- 🟩 2. Resolved Tickets -->
                        <div class="rounded-xl p-4 shadow-lg flex flex-col justify-between space-y-3 transform hover:-translate-y-0.5 transition-all duration-200" style="background: linear-gradient(135deg, #198754, #20c997);">
                            <div class="flex justify-between items-start">
                                <span class="text-[10px] font-black uppercase tracking-wider text-slate-950">Resolved Tickets</span>
                                <div class="w-8 h-8 rounded-lg bg-black/15 text-slate-950 flex items-center justify-center text-sm font-bold shadow-inner">
                                    <i class="fa-solid fa-circle-check"></i>
                                </div>
                            </div>
                            <div>
                                <h4 class="text-2xl font-black text-slate-950 tracking-tight" style="color: #000000;">{{ $myActivity['resolved_tickets'] }}</h4>
                                <p class="text-[10px] font-bold text-slate-900/80 mt-0.5" style="color: #000000;">Successfully closed</p>
                            </div>
                        </div>

                        <!-- 🟪 3. Average Resolution Time -->
                        <div class="rounded-xl p-4 shadow-lg flex flex-col justify-between space-y-3 transform hover:-translate-y-0.5 transition-all duration-200" style="background: linear-gradient(135deg, #6f42c1, #4e73df);">
                            <div class="flex justify-between items-start">
                                <span class="text-[10px] font-black uppercase tracking-wider text-slate-950">Avg. Resolution Time</span>
                                <div class="w-8 h-8 rounded-lg bg-black/15 text-slate-950 flex items-center justify-center text-sm font-bold shadow-inner">
                                    <i class="fa-solid fa-stopwatch"></i>
                                </div>
                            </div>
                            <div>
                                <h4 class="text-2xl font-black text-slate-950 tracking-tight" style="color: #000000;">{{ $myActivity['avg_response'] }}</h4>
                                <p class="text-[10px] font-bold text-slate-900/80 mt-0.5" style="color: #000000;">Personal SLA time</p>
                            </div>
                        </div>

                        <!-- 🟧 4. Average Rating Received -->
                        <div class="rounded-xl p-4 shadow-lg flex flex-col justify-between space-y-3 transform hover:-translate-y-0.5 transition-all duration-200" style="background: linear-gradient(135deg, #fd7e14, #ffc107);">
                            <div class="flex justify-between items-start">
                                <span class="text-[10px] font-black uppercase tracking-wider text-slate-950">Avg. Rating Received</span>
                                <div class="w-8 h-8 rounded-lg bg-black/15 text-slate-950 flex items-center justify-center text-sm font-bold shadow-inner">
                                    <i class="fa-solid fa-award"></i>
                                </div>
                            </div>
                            <div>
                                <h4 class="text-2xl font-black text-slate-950 tracking-tight" style="color: #000000;">{{ number_format($myActivity['average_rating'], 1) }} <span class="text-xs font-bold text-slate-900/80">/ 5.0</span></h4>
                                <p class="text-[10px] font-bold text-slate-900/80 mt-0.5" style="color: #000000;">Service CSAT score</p>
                            </div>
                        </div>

                    @else
                        <!-- ==================== ROLE: ADMIN ==================== -->
                        <!-- 🟦 1. Total Tickets -->
                        <div class="rounded-xl p-4 shadow-lg flex flex-col justify-between space-y-3 transform hover:-translate-y-0.5 transition-all duration-200" style="background: linear-gradient(135deg, #0dcaf0, #0d6efd);">
                            <div class="flex justify-between items-start">
                                <span class="text-[10px] font-black uppercase tracking-wider text-slate-950">Total Tickets</span>
                                <div class="w-8 h-8 rounded-lg bg-black/15 text-slate-950 flex items-center justify-center text-sm font-bold shadow-inner">
                                    <i class="fa-solid fa-database"></i>
                                </div>
                            </div>
                            <div>
                                <h4 class="text-2xl font-black text-slate-950 tracking-tight" style="color: #000000;">{{ $myActivity['created_tickets'] }}</h4>
                                <p class="text-[10px] font-bold text-slate-900/80 mt-0.5" style="color: #000000;">System-wide records</p>
                            </div>
                        </div>

                        <!-- 🟩 2. Resolved Tickets -->
                        <div class="rounded-xl p-4 shadow-lg flex flex-col justify-between space-y-3 transform hover:-translate-y-0.5 transition-all duration-200" style="background: linear-gradient(135deg, #198754, #20c997);">
                            <div class="flex justify-between items-start">
                                <span class="text-[10px] font-black uppercase tracking-wider text-slate-950">Resolved Tickets</span>
                                <div class="w-8 h-8 rounded-lg bg-black/15 text-slate-950 flex items-center justify-center text-sm font-bold shadow-inner">
                                    <i class="fa-solid fa-circle-check"></i>
                                </div>
                            </div>
                            <div>
                                <h4 class="text-2xl font-black text-slate-950 tracking-tight" style="color: #000000;">{{ $myActivity['resolved_tickets'] }}</h4>
                                <p class="text-[10px] font-bold text-slate-900/80 mt-0.5" style="color: #000000;">Total closed jobs</p>
                            </div>
                        </div>

                        <!-- 🟧 3. SLA Compliance (%) -->
                        <div class="rounded-xl p-4 shadow-lg flex flex-col justify-between space-y-3 transform hover:-translate-y-0.5 transition-all duration-200" style="background: linear-gradient(135deg, #fd7e14, #ffc107);">
                            <div class="flex justify-between items-start">
                                <span class="text-[10px] font-black uppercase tracking-wider text-slate-950">SLA Compliance</span>
                                <div class="w-8 h-8 rounded-lg bg-black/15 text-slate-950 flex items-center justify-center text-sm font-bold shadow-inner">
                                    <i class="fa-solid fa-shield-halved"></i>
                                </div>
                            </div>
                            <div>
                                <h4 class="text-2xl font-black text-slate-950 tracking-tight" style="color: #000000;">{{ $myActivity['sla_compliance'] }}</h4>
                                <p class="text-[10px] font-bold text-slate-900/80 mt-0.5" style="color: #000000;">On-time resolution rate</p>
                            </div>
                        </div>

                        <!-- 🟪 4. Average Team Rating Satisfaction -->
                        <div class="rounded-xl p-4 shadow-lg flex flex-col justify-between space-y-3 transform hover:-translate-y-0.5 transition-all duration-200" style="background: linear-gradient(135deg, #6f42c1, #4e73df);">
                            <div class="flex justify-between items-start">
                                <span class="text-[10px] font-black uppercase tracking-wider text-slate-950">Avg. Team CSAT</span>
                                <div class="w-8 h-8 rounded-lg bg-black/15 text-slate-950 flex items-center justify-center text-sm font-bold shadow-inner">
                                    <i class="fa-solid fa-star"></i>
                                </div>
                            </div>
                            <div>
                                <h4 class="text-2xl font-black text-slate-950 tracking-tight" style="color: #000000;">{{ number_format($myActivity['average_rating'], 1) }} <span class="text-xs font-bold text-slate-900/80">/ 5.0</span></h4>
                                <p class="text-[10px] font-bold text-slate-900/80 mt-0.5" style="color: #000000;">Overall IT satisfaction</p>
                            </div>
                        </div>
                    @endif

                </div>

                <!-- Recent Activity Feed -->
                <div class="pt-3 space-y-3">
                    <p class="text-xs font-bold text-slate-200 tracking-wide flex items-center gap-2">
                        <i class="fa-solid fa-history text-slate-400"></i> Recent Activity Feed
                    </p>
                    <div class="space-y-2.5 text-xs">
                        @forelse($recentActivities as $act)
                            <div class="flex justify-between items-center bg-slate-950/70 p-3 rounded-xl border border-slate-800/80 hover:border-slate-700 transition shadow-sm">
                                <div class="flex items-center gap-3">
                                    <span class="text-base bg-slate-900 w-8 h-8 rounded-lg flex items-center justify-center border border-slate-800/80 shadow-inner shrink-0">{{ $act['emoji'] }}</span>
                                    <div>
                                        <p class="font-bold text-slate-200">{{ $act['title'] }}</p>
                                        <p class="text-[10px] text-slate-400 mt-0.5 truncate max-w-[220px] sm:max-w-xs">{{ $act['subject'] }}</p>
                                    </div>
                                </div>
                                <span class="text-[10px] font-mono text-slate-400 bg-slate-900 px-2 py-1 rounded-md border border-slate-800/80 shrink-0">{{ $act['time'] }}</span>
                            </div>
                        @empty
                            <div class="text-center py-5 text-slate-500 text-xs italic bg-slate-950/40 rounded-xl border border-slate-800/50">No recent activity found.</div>
                        @endforelse
                    </div>

                    <button type="button" onclick="toggleAllActivityModal()" class="w-full bg-slate-950 hover:bg-slate-800 text-cyan-400 hover:text-cyan-300 border border-slate-800 text-xs py-2.5 rounded-xl transition-all font-bold mt-3 cursor-pointer flex items-center justify-center gap-2 shadow-md">
                        <span>View All Activity History</span>
                        <i class="fa-solid fa-arrow-right text-xs"></i>
                    </button>
                </div>
            </div>

        </div>

        <!-- KELOMPOK KANAN (LG: COL 5) -->
        <div class="lg:col-span-5 space-y-6">

            <!-- 🔐 3. SECURITY CARD -->
            <div class="bg-slate-900/80 backdrop-blur-xl border border-slate-800/80 rounded-2xl p-6 shadow-2xl hover:border-slate-700/60 transition-all duration-300 space-y-5">
                <div class="flex justify-between items-center border-b border-slate-800/80 pb-4">
                    <h3 class="font-bold text-slate-100 text-sm flex items-center gap-2.5 tracking-wide">
                        <div class="w-7 h-7 rounded-lg bg-amber-500/10 border border-amber-500/20 text-amber-400 flex items-center justify-center text-xs">
                            <i class="fa-solid fa-shield-halved"></i>
                        </div>
                        Security & Authentication
                    </h3>
                    <span class="bg-emerald-950/80 text-emerald-400 border border-emerald-800/80 px-3 py-1 rounded-full text-[10px] font-bold flex items-center gap-1.5 shadow-inner">
                        <i class="fa-solid fa-circle text-[6px]"></i> Protected
                    </span>
                </div>

                <!-- Info Status Kata Laluan -->
                <div class="grid grid-cols-2 gap-3 text-xs bg-slate-950/80 p-3.5 rounded-xl border border-slate-800/80">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg bg-slate-900 text-amber-400 flex items-center justify-center text-xs shrink-0">
                            <i class="fa-solid fa-calendar-check"></i>
                        </div>
                        <div>
                            <p class="text-[10px] text-slate-400 font-medium">Last Changed</p>
                            <p class="font-bold text-slate-200 mt-0.5 text-[11px] truncate">{{ $passwordLastChanged }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg bg-slate-900 text-emerald-400 flex items-center justify-center text-xs shrink-0">
                            <i class="fa-solid fa-lock"></i>
                        </div>
                        <div>
                            <p class="text-[10px] text-slate-400 font-medium">Account Status</p>
                            <p class="font-bold text-emerald-400 mt-0.5 text-[11px] flex items-center gap-1">
                                <i class="fa-solid fa-circle text-[6px]"></i> Active
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Form Penukaran Kata Laluan -->
                <form action="{{ route('settings.password.update') }}" method="POST" class="space-y-3.5 pt-1 text-xs">
                    @csrf
                    <p class="font-bold text-slate-200 flex items-center gap-2">
                        <i class="fa-solid fa-key text-amber-400 text-xs"></i> Change Password
                    </p>

                    <div class="relative">
                        <i class="fa-solid fa-lock absolute left-3.5 top-3.5 text-slate-500 text-xs"></i>
                        <input type="password" id="current_password" name="current_password" required placeholder="Enter current password" class="w-full bg-slate-950/90 text-slate-100 rounded-xl pl-9 pr-9 py-2.5 border border-slate-800 outline-none focus:border-amber-500 focus:ring-1 focus:ring-amber-500/50 text-xs transition">
                        <button type="button" onclick="togglePasswordVisibility('current_password', this)" class="absolute right-3.5 top-3.5 text-slate-500 hover:text-amber-400 text-xs">
                            <i class="fa-solid fa-eye"></i>
                        </button>
                    </div>

                    <div class="relative">
                        <i class="fa-solid fa-key absolute left-3.5 top-3.5 text-slate-500 text-xs"></i>
                        <input type="password" id="new_password" name="new_password" required placeholder="Enter new password (min. 6 characters)" class="w-full bg-slate-950/90 text-slate-100 rounded-xl pl-9 pr-9 py-2.5 border border-slate-800 outline-none focus:border-amber-500 focus:ring-1 focus:ring-amber-500/50 text-xs transition">
                        <button type="button" onclick="togglePasswordVisibility('new_password', this)" class="absolute right-3.5 top-3.5 text-slate-500 hover:text-amber-400 text-xs">
                            <i class="fa-solid fa-eye"></i>
                        </button>
                    </div>

                    <div class="relative">
                        <i class="fa-solid fa-check-double absolute left-3.5 top-3.5 text-slate-500 text-xs"></i>
                        <input type="password" id="new_password_confirmation" name="new_password_confirmation" required placeholder="Confirm new password" class="w-full bg-slate-950/90 text-slate-100 rounded-xl pl-9 pr-9 py-2.5 border border-slate-800 outline-none focus:border-amber-500 focus:ring-1 focus:ring-amber-500/50 text-xs transition">
                        <button type="button" onclick="togglePasswordVisibility('new_password_confirmation', this)" class="absolute right-3.5 top-3.5 text-slate-500 hover:text-amber-400 text-xs">
                            <i class="fa-solid fa-eye"></i>
                        </button>
                    </div>

                    <button type="submit" class="w-full bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-400 hover:to-amber-500 text-slate-950 font-black py-2.5 px-4 rounded-xl shadow-lg transition-all flex items-center justify-center gap-2 text-xs mt-3 cursor-pointer active:scale-[0.99]">
                        <i class="fa-solid fa-wrench"></i> Update Password
                    </button>
                </form>
            </div>

            <!-- ⚙️ 4. SYSTEM INFORMATION CARD -->
            <div class="bg-slate-900/80 backdrop-blur-xl border border-slate-800/80 rounded-2xl p-6 shadow-2xl hover:border-slate-700/60 transition-all duration-300 space-y-5">
                <div class="flex justify-between items-center border-b border-slate-800/80 pb-4">
                    <h3 class="font-bold text-slate-100 text-sm flex items-center gap-2.5 tracking-wide">
                        <div class="w-7 h-7 rounded-lg bg-indigo-500/10 border border-indigo-500/20 text-indigo-400 flex items-center justify-center text-xs">
                            <i class="fa-solid fa-server"></i>
                        </div>
                        System Information
                    </h3>
                    @if(Auth::user()->role === 'admin')
                        <span class="bg-purple-950/80 text-purple-300 border border-purple-800/80 px-2.5 py-0.5 rounded-lg text-[10px] font-black uppercase tracking-wider shadow-inner">DEVOPS ADMIN</span>
                    @endif
                </div>

                @if(Auth::user()->role === 'admin' && $systemInfo)
                    <div class="grid grid-cols-2 gap-3 text-xs">
                        <div class="bg-slate-950/80 p-3 rounded-xl border border-slate-800/80 flex items-center gap-3 hover:border-slate-700 transition">
                            <div class="w-8 h-8 rounded-lg bg-slate-900 text-cyan-400 flex items-center justify-center shrink-0">
                                <i class="fa-solid fa-tag"></i>
                            </div>
                            <div class="min-w-0">
                                <p class="text-[10px] text-slate-400 font-medium">App Version</p>
                                <p class="font-bold text-slate-200 mt-0.5 font-mono truncate">{{ $systemInfo['system_version'] }}</p>
                            </div>
                        </div>

                        <div class="bg-slate-950/80 p-3 rounded-xl border border-slate-800/80 flex items-center gap-3 hover:border-slate-700 transition">
                            <div class="w-8 h-8 rounded-lg bg-slate-900 text-emerald-400 flex items-center justify-center shrink-0">
                                <i class="fa-solid fa-database"></i>
                            </div>
                            <div class="min-w-0">
                                <p class="text-[10px] text-slate-400 font-medium">Database Status</p>
                                <p class="font-bold text-emerald-400 mt-0.5 flex items-center gap-1 truncate">
                                    <i class="fa-solid fa-circle text-[6px]"></i> {{ $systemInfo['db_status'] }}
                                </p>
                            </div>
                        </div>

                        <div class="bg-slate-950/80 p-3 rounded-xl border border-slate-800/80 flex items-center gap-3 hover:border-slate-700 transition">
                            <div class="w-8 h-8 rounded-lg bg-slate-900 text-rose-400 flex items-center justify-center shrink-0">
                                <i class="fa-brands fa-laravel"></i>
                            </div>
                            <div class="min-w-0">
                                <p class="text-[10px] text-slate-400 font-medium">Laravel Version</p>
                                <p class="font-bold text-slate-200 mt-0.5 font-mono truncate">{{ $systemInfo['laravel_version'] }}</p>
                            </div>
                        </div>

                        <!-- Realtime Reverb Status -->
                        <div class="bg-slate-950/80 p-3 rounded-xl border border-slate-800/80 flex items-center gap-3 hover:border-slate-700 transition">
                            <div class="w-8 h-8 rounded-lg bg-slate-900 {{ $systemInfo['reverb_status'] === 'Online' ? 'text-emerald-400' : 'text-rose-400' }} flex items-center justify-center shrink-0">
                                <i class="fa-solid fa-tower-broadcast"></i>
                            </div>
                            <div class="min-w-0">
                                <p class="text-[10px] text-slate-400 font-medium">Realtime (Reverb)</p>
                                <p class="font-bold {{ $systemInfo['reverb_status'] === 'Online' ? 'text-emerald-400' : 'text-rose-400' }} mt-0.5 flex items-center gap-1 truncate">
                                    <i class="fa-solid fa-circle text-[6px]"></i> {{ $systemInfo['reverb_status'] }}
                                </p>
                            </div>
                        </div>

                        <div class="bg-slate-950/80 p-3 rounded-xl border border-slate-800/80 flex items-center gap-3 hover:border-slate-700 transition">
                            <div class="w-8 h-8 rounded-lg bg-slate-900 text-indigo-400 flex items-center justify-center shrink-0">
                                <i class="fa-brands fa-php"></i>
                            </div>
                            <div class="min-w-0">
                                <p class="text-[10px] text-slate-400 font-medium">PHP Version</p>
                                <p class="font-bold text-slate-200 mt-0.5 font-mono truncate">{{ $systemInfo['php_version'] }}</p>
                            </div>
                        </div>

                        <div class="bg-slate-950/80 p-3 rounded-xl border border-slate-800/80 flex items-center gap-3 hover:border-slate-700 transition">
                            <div class="w-8 h-8 rounded-lg bg-slate-900 text-sky-400 flex items-center justify-center shrink-0">
                                <i class="fa-solid fa-layer-group"></i>
                            </div>
                            <div class="min-w-0">
                                <p class="text-[10px] text-slate-400 font-medium">Environment</p>
                                <p class="font-bold text-sky-400 mt-0.5 flex items-center gap-1 truncate">
                                    <i class="fa-solid fa-circle text-[6px]"></i> {{ $systemInfo['environment'] }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Server Information Section -->
                    <div class="pt-3 border-t border-slate-800/80 space-y-2.5">
                        <div class="flex justify-between items-center">
                            <span class="text-xs font-bold text-slate-200 flex items-center gap-2">
                                <i class="fa-solid fa-desktop text-slate-400"></i> Server Information
                            </span>
                        </div>
                        <div class="bg-slate-950/80 p-3 rounded-xl border border-slate-800/80 space-y-2 text-xs">
                            <div class="flex justify-between text-slate-400">
                                <span>Server Time:</span>
                                <span class="text-slate-200 font-mono font-bold">{{ $systemInfo['server_time'] }}</span>
                            </div>
                            <div class="flex justify-between text-slate-400">
                                <span>Timezone:</span>
                                <span class="text-slate-200 font-mono font-bold">{{ $systemInfo['timezone'] }}</span>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="text-xs text-slate-400 italic py-5 text-center bg-slate-950/40 rounded-xl border border-slate-800/50">
                        <i class="fa-solid fa-lock mr-1.5 text-slate-500"></i> Technical system metrics are restricted to Admin accounts only.
                    </div>
                @endif
            </div>

        </div>

    </div>
</div>

<!-- MODAL EDIT PROFILE -->
<div id="editProfileModal" class="fixed inset-0 bg-slate-950/80 backdrop-blur-md z-50 hidden flex items-center justify-center p-4">
    <div class="bg-slate-900 border border-slate-800 rounded-2xl max-w-md w-full p-6 space-y-4 shadow-2xl relative">
        <div class="flex justify-between items-center border-b border-slate-800 pb-3">
            <h3 class="font-bold text-slate-100 text-sm flex items-center gap-2">
                <i class="fa-solid fa-pen-to-square text-cyan-400"></i> Edit Profile Information
            </h3>
            <button onclick="toggleEditProfileModal()" class="text-slate-400 hover:text-white text-lg">&times;</button>
        </div>

        <form action="{{ route('settings.profile.update') }}" method="POST" class="space-y-3.5 text-xs">
            @csrf
            <div>
                <label class="block mb-1 text-slate-300 font-medium">Full Name</label>
                <input type="text" name="name" value="{{ $user->name }}" required class="w-full bg-slate-950 text-slate-100 rounded-xl p-2.5 border border-slate-800 outline-none focus:border-cyan-500">
            </div>

            <div>
                <label class="block mb-1 text-slate-300 font-medium">Email Address</label>
                <input type="email" name="email" value="{{ $user->email }}" required class="w-full bg-slate-950 text-slate-100 rounded-xl p-2.5 border border-slate-800 outline-none focus:border-cyan-500">
            </div>

            <div>
                <label class="block mb-1 text-slate-300 font-medium">Department</label>
                <input type="text" name="department" value="{{ $user->department ?? 'IT Department' }}" class="w-full bg-slate-950 text-slate-100 rounded-xl p-2.5 border border-slate-800 outline-none focus:border-cyan-500">
            </div>

            <div>
                <label class="block mb-1 text-slate-300 font-medium">Office Location</label>
                <input type="text" name="office_location" value="{{ $user->office_location ?? 'Main Office' }}" class="w-full bg-slate-950 text-slate-100 rounded-xl p-2.5 border border-slate-800 outline-none focus:border-cyan-500">
            </div>

            <div class="pt-2 flex justify-end gap-2">
                <button type="button" onclick="toggleEditProfileModal()" class="bg-slate-800 hover:bg-slate-700 text-slate-300 px-4 py-2 rounded-xl font-bold transition">Cancel</button>
                <button type="submit" class="bg-cyan-600 hover:bg-cyan-500 text-white px-4 py-2 rounded-xl font-bold transition">Save Changes</button>
            </div>
        </form>
    </div>
</div>

<!-- MODAL POP-UP VIEW ALL ACTIVITY -->
<div id="allActivityModal" class="fixed inset-0 bg-slate-950/80 backdrop-blur-md z-50 hidden flex items-center justify-center p-4">
    <div class="bg-slate-900 border border-slate-800 rounded-2xl max-w-3xl w-full p-6 space-y-4 shadow-2xl relative max-h-[88vh] flex flex-col">
        
        <!-- Modal Header -->
        <div class="flex justify-between items-center border-b border-slate-800 pb-3 shrink-0">
            <h3 class="font-bold text-slate-100 text-sm flex items-center gap-2">
                <i class="fa-solid fa-list-check text-cyan-400"></i> 
                {{ Auth::user()->role === 'admin' ? 'All System Accounts Activity History' : 'My Activity History' }}
            </h3>
            <button onclick="toggleAllActivityModal()" class="text-slate-400 hover:text-white text-xl font-bold cursor-pointer transition">&times;</button>
        </div>

        <!-- BARIS PENAPIS -->
        <div class="bg-slate-950 p-3 rounded-xl border border-slate-800 space-y-2.5 shrink-0">
            
            <!-- 1. Category Filter Options -->
            <div class="flex items-center gap-2 overflow-x-auto pb-1 text-[11px] scrollbar-none">
                <span class="text-slate-400 font-semibold shrink-0 text-[10px]">Filter:</span>

                @if(Auth::user()->role === 'admin')
                    <!-- ADMIN FILTER CATEGORIES -->
                    <button onclick="setCategoryFilter('all')" class="cat-btn bg-cyan-600 text-white font-bold px-2.5 py-1 rounded-lg transition shrink-0" data-cat="all">[ All ]</button>
                    <button onclick="setCategoryFilter('tickets')" class="cat-btn bg-slate-900 text-slate-300 hover:text-white font-medium px-2.5 py-1 rounded-lg border border-slate-800 transition shrink-0" data-cat="tickets">🎫 Ticket</button>
                    <button onclick="setCategoryFilter('assets')" class="cat-btn bg-slate-900 text-slate-300 hover:text-white font-medium px-2.5 py-1 rounded-lg border border-slate-800 transition shrink-0" data-cat="assets">💻 Asset</button>
                    <button onclick="setCategoryFilter('users')" class="cat-btn bg-slate-900 text-slate-300 hover:text-white font-medium px-2.5 py-1 rounded-lg border border-slate-800 transition shrink-0" data-cat="users">👤 User</button>
                    <button onclick="setCategoryFilter('security')" class="cat-btn bg-slate-900 text-slate-300 hover:text-white font-medium px-2.5 py-1 rounded-lg border border-slate-800 transition shrink-0" data-cat="security">🔐 Security</button>
                    <button onclick="setCategoryFilter('knowledge')" class="cat-btn bg-slate-900 text-slate-300 hover:text-white font-medium px-2.5 py-1 rounded-lg border border-slate-800 transition shrink-0" data-cat="knowledge">📚 Knowledge</button>
                    <button onclick="setCategoryFilter('system')" class="cat-btn bg-slate-900 text-slate-300 hover:text-white font-medium px-2.5 py-1 rounded-lg border border-slate-800 transition shrink-0" data-cat="system">⚙️ System</button>
                    <button onclick="setCategoryFilter('closed')" class="cat-btn bg-slate-900 text-slate-300 hover:text-white font-medium px-2.5 py-1 rounded-lg border border-slate-800 transition shrink-0" data-cat="closed">✅ Closed</button>

                @elseif(Auth::user()->role === 'technician')
                    <!-- TECHNICIAN FILTER CATEGORIES -->
                    <button onclick="setCategoryFilter('all')" class="cat-btn bg-cyan-600 text-white font-bold px-2.5 py-1 rounded-lg transition shrink-0" data-cat="all">[ All ]</button>
                    <button onclick="setCategoryFilter('tickets')" class="cat-btn bg-slate-900 text-slate-300 hover:text-white font-medium px-2.5 py-1 rounded-lg border border-slate-800 transition shrink-0" data-cat="tickets">🎫 Ticket</button>
                    <button onclick="setCategoryFilter('replies')" class="cat-btn bg-slate-900 text-slate-300 hover:text-white font-medium px-2.5 py-1 rounded-lg border border-slate-800 transition shrink-0" data-cat="replies">💬 Replies</button>
                    <button onclick="setCategoryFilter('ai')" class="cat-btn bg-slate-900 text-slate-300 hover:text-white font-medium px-2.5 py-1 rounded-lg border border-slate-800 transition shrink-0" data-cat="ai">🤖 AI</button>
                    <button onclick="setCategoryFilter('assets')" class="cat-btn bg-slate-900 text-slate-300 hover:text-white font-medium px-2.5 py-1 rounded-lg border border-slate-800 transition shrink-0" data-cat="assets">💻 Assets</button>

                @else
                    <!-- STAFF FILTER CATEGORIES -->
                    <button onclick="setCategoryFilter('all')" class="cat-btn bg-cyan-600 text-white font-bold px-2.5 py-1 rounded-lg transition shrink-0" data-cat="all">[ All ]</button>
                    <button onclick="setCategoryFilter('tickets')" class="cat-btn bg-slate-900 text-slate-300 hover:text-white font-medium px-2.5 py-1 rounded-lg border border-slate-800 transition shrink-0" data-cat="tickets">🎫 My Tickets</button>
                    <button onclick="setCategoryFilter('replies')" class="cat-btn bg-slate-900 text-slate-300 hover:text-white font-medium px-2.5 py-1 rounded-lg border border-slate-800 transition shrink-0" data-cat="replies">💬 Replies</button>
                    <button onclick="setCategoryFilter('ratings')" class="cat-btn bg-slate-900 text-slate-300 hover:text-white font-medium px-2.5 py-1 rounded-lg border border-slate-800 transition shrink-0" data-cat="ratings">⭐ Ratings</button>
                @endif
            </div>

            <!-- 2. Additional Controls: User, Role, Department & Period Filters -->
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-2 text-[10px]">
                @if(Auth::user()->role === 'admin')
                    <!-- Filter By User Name -->
                    <select id="filterUser" onchange="applyAllFilters()" class="bg-slate-900 text-slate-300 border border-slate-800 rounded-lg px-2 py-1 outline-none focus:border-cyan-500 cursor-pointer">
                        <option value="all">👤 All Users</option>
                        @foreach($usersList as $u)
                            <option value="{{ strtolower($u->name) }}">{{ $u->name }}</option>
                        @endforeach
                    </select>

                    <!-- Filter By Role -->
                    <select id="filterRole" onchange="applyAllFilters()" class="bg-slate-900 text-slate-300 border border-slate-800 rounded-lg px-2 py-1 outline-none focus:border-cyan-500 cursor-pointer">
                        <option value="all">🎭 All Roles</option>
                        <option value="admin">Admin</option>
                        <option value="technician">Technician</option>
                        <option value="staff">Staff</option>
                    </select>

                    <!-- Filter By Department -->
                    <select id="filterDept" onchange="applyAllFilters()" class="bg-slate-900 text-slate-300 border border-slate-800 rounded-lg px-2 py-1 outline-none focus:border-cyan-500 cursor-pointer">
                        <option value="all">🏢 All Departments</option>
                        @foreach($departmentsList as $d)
                            <option value="{{ strtolower($d) }}">{{ $d }}</option>
                        @endforeach
                    </select>
                @endif

                <!-- Filter By Date Range / Period -->
                <select id="filterPeriod" onchange="applyAllFilters()" class="bg-slate-900 text-slate-300 border border-slate-800 rounded-lg px-2 py-1 outline-none focus:border-cyan-500 cursor-pointer {{ Auth::user()->role !== 'admin' ? 'col-span-2 sm:col-span-4' : '' }}">
                    <option value="all">📅 All Time Range</option>
                    <option value="today">📅 Today</option>
                    <option value="this_week">📅 This Week</option>
                    <option value="this_month">📅 This Month</option>
                </select>
            </div>

        </div>

        <!-- Senarai Log Aktiviti Dalam Kad Berasingan -->
        <div class="overflow-y-auto space-y-3 text-xs pr-1 flex-1 min-h-[300px]" id="activityListContainer">
            @forelse($allActivities as $activity)
                <div class="activity-card bg-slate-950/90 border border-slate-800/90 rounded-xl p-3.5 space-y-2 shadow-md hover:border-slate-700 transition" 
                     data-category="{{ $activity['category'] }}"
                     data-user="{{ strtolower($activity['user_name']) }}"
                     data-role="{{ strtolower($activity['user_role']) }}"
                     data-department="{{ strtolower($activity['department']) }}"
                     data-period="{{ $activity['period'] }}"
                     data-closed="{{ $activity['is_closed'] ? 'true' : 'false' }}">
                    
                    <!-- Baris 1: Pengguna (User) -->
                    <div class="flex justify-between items-center text-slate-200 font-bold">
                        <div class="flex items-center gap-2">
                            <span class="text-base">👤</span>
                            <span class="text-xs text-indigo-300 font-semibold">{{ $activity['user_name'] }}</span>
                        </div>
                        <span class="text-[9px] uppercase px-2 py-0.5 rounded bg-slate-900 text-slate-400 border border-slate-800">
                            {{ $activity['user_role'] }}
                        </span>
                    </div>

                    <!-- Baris 2: Masa Lengkap -->
                    <div class="flex items-center gap-2 text-slate-400 font-mono text-[11px]">
                        <span>🕒</span>
                        <span>{{ $activity['datetime'] }}</span>
                    </div>

                    <!-- Baris 3: Emoji + Tajuk Aktiviti -->
                    <div class="flex items-center gap-2 text-white font-bold pt-0.5">
                        <span class="text-sm">{{ $activity['emoji'] }}</span>
                        <span>{{ $activity['title'] }}</span>
                    </div>

                    <!-- Baris 4: Kutipan Keterangan / Subjek -->
                    @if(!empty($activity['subject']))
                        <p class="text-[11px] text-slate-400 italic pl-6 pt-0.5 border-l-2 border-slate-800 ml-1">
                            "{{ $activity['subject'] }}"
                        </p>
                    @endif
                </div>
            @empty
                <div class="text-center py-12 text-slate-500 text-xs italic">No activity record found in the database.</div>
            @endforelse

            <div id="noMatchMessage" class="hidden text-center py-12 text-slate-500 text-xs italic">
                No activities match the selected filter criteria.
            </div>
        </div>

        <div class="pt-3 border-t border-slate-800 flex justify-end shrink-0">
            <button type="button" onclick="toggleAllActivityModal()" class="bg-slate-800 hover:bg-slate-700 text-slate-300 px-5 py-2 rounded-xl font-bold text-xs transition cursor-pointer">
                Close
            </button>
        </div>
    </div>
</div>

<script>
    let activeCategory = 'all';

    function togglePasswordVisibility(inputId, btn) {
        const input = document.getElementById(inputId);
        const icon = btn.querySelector('i');
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    }

    function toggleEditProfileModal() {
        const modal = document.getElementById('editProfileModal');
        modal.classList.toggle('hidden');
    }

    function toggleAllActivityModal() {
        const modal = document.getElementById('allActivityModal');
        modal.classList.toggle('hidden');
    }

    // Tukar Kategori Aktif
    function setCategoryFilter(category) {
        activeCategory = category;

        // Kemaskini Gaya Butang
        document.querySelectorAll('.cat-btn').forEach(btn => {
            if (btn.getAttribute('data-cat') === category) {
                btn.className = "cat-btn bg-cyan-600 text-white font-bold px-2.5 py-1 rounded-lg transition shrink-0";
            } else {
                btn.className = "cat-btn bg-slate-900 text-slate-300 hover:text-white font-medium px-2.5 py-1 rounded-lg border border-slate-800 transition shrink-0";
            }
        });

        applyAllFilters();
    }

    // Fungsi Utama Penapis Dinamik (Client-side Fast Filtering)
    function applyAllFilters() {
        const userFilterElem = document.getElementById('filterUser');
        const roleFilterElem = document.getElementById('filterRole');
        const deptFilterElem = document.getElementById('filterDept');
        const periodFilterElem = document.getElementById('filterPeriod');

        const userFilter = userFilterElem ? userFilterElem.value.trim().toLowerCase() : 'all';
        const roleFilter = roleFilterElem ? roleFilterElem.value.trim().toLowerCase() : 'all';
        const deptFilter = deptFilterElem ? deptFilterElem.value.trim().toLowerCase() : 'all';
        const periodFilter = periodFilterElem ? periodFilterElem.value : 'all';

        const cards = document.querySelectorAll('.activity-card');
        let visibleCount = 0;

        cards.forEach(card => {
            const cat = card.getAttribute('data-category');
            const user = (card.getAttribute('data-user') || '').trim().toLowerCase();
            const role = (card.getAttribute('data-role') || '').trim().toLowerCase();
            const dept = (card.getAttribute('data-department') || '').trim().toLowerCase();
            const period = card.getAttribute('data-period');
            const isClosed = card.getAttribute('data-closed') === 'true';

            let matchCategory = false;
            if (activeCategory === 'all') {
                matchCategory = true;
            } else if (activeCategory === 'closed') {
                matchCategory = isClosed;
            } else {
                matchCategory = (cat === activeCategory || (cat === 'assets' && activeCategory === 'asset') || (cat === 'asset' && activeCategory === 'assets'));
            }

            const matchUser = (userFilter === 'all' || user === userFilter || user.includes(userFilter) || userFilter.includes(user));
            const matchRole = (roleFilter === 'all' || role === roleFilter);
            const matchDept = (deptFilter === 'all' || dept === deptFilter);
            const matchPeriod = (periodFilter === 'all' || period === periodFilter);

            if (matchCategory && matchUser && matchRole && matchDept && matchPeriod) {
                card.style.display = 'block';
                visibleCount++;
            } else {
                card.style.display = 'none';
            }
        });

        const noMatchMsg = document.getElementById('noMatchMessage');
        if (noMatchMsg) {
            if (visibleCount === 0) {
                noMatchMsg.classList.remove('hidden');
            } else {
                noMatchMsg.classList.add('hidden');
            }
        }
    }
</script>
@endsection