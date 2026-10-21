@extends('layouts.app')

@section('content')
<style>
    @media print {
        * {
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }
    }
</style>

<div class="max-w-6xl mx-auto space-y-6 px-3 py-4">

    <!-- Header & Action Buttons -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-slate-900/90 backdrop-blur-xl border border-slate-800/80 rounded-2xl p-5 shadow-2xl">
        <div>
            <h1 class="text-xl sm:text-2xl font-black text-slate-100 flex items-center gap-2.5">
                <i class="fa-solid fa-chart-line text-cyan-400"></i> Analytics & Performance Report
            </h1>
            <p class="text-xs text-slate-400 mt-1">Visual representation of IT support ticket statistics and breakdown.</p>
        </div>
        <div class="flex flex-wrap items-center gap-2.5 w-full sm:w-auto shrink-0">
            <button onclick="window.print()" class="flex-1 sm:flex-none bg-cyan-600 hover:bg-cyan-500 text-white text-xs font-bold px-4 py-2.5 rounded-xl shadow-lg transition flex items-center justify-center gap-2">
                <i class="fa-solid fa-print"></i> Print / Save PDF
            </button>
            <a href="{{ route('tickets.index') }}" class="flex-1 sm:flex-none bg-slate-800 hover:bg-slate-700 text-slate-200 text-xs font-bold px-4 py-2.5 rounded-xl shadow transition flex items-center justify-center gap-2 border border-slate-700">
                <i class="fa-solid fa-arrow-left"></i> Back to Dashboard
            </a>
        </div>
    </div>

    @php
        $totalTickets   = \App\Models\Ticket::count();
        $closedTickets  = \App\Models\Ticket::where('status', 'Closed')->count();
        $pendingTickets = \App\Models\Ticket::whereIn('status', ['Open', 'In Progress'])->count();
        $hardwareCount  = \App\Models\Ticket::where('category', 'Hardware')->count();
        $softwareCount  = \App\Models\Ticket::where('category', 'Software')->count();
        $networkCount   = \App\Models\Ticket::where('category', 'Network')->count();
        $otherCount     = \App\Models\Ticket::where('category', 'Other')->count();
        $openCount      = \App\Models\Ticket::where('status', 'Open')->count();
        $inProgressCount= \App\Models\Ticket::whereIn('status', ['In Progress'])->count();

        // Pengiraan Dinamik untuk Satisfaction Rating
        $avgRating     = round(\App\Models\Ticket::whereNotNull('rating')->avg('rating') ?? 0, 1);
        $totalFeedback = \App\Models\Ticket::whereNotNull('rating')->count();

        // Mengira bilangan tiket bagi setiap bintang (1 hingga 5 bintang)
        $star1 = \App\Models\Ticket::where('rating', 1)->count();
        $star2 = \App\Models\Ticket::where('rating', 2)->count();
        $star3 = \App\Models\Ticket::where('rating', 3)->count();
        $star4 = \App\Models\Ticket::where('rating', 4)->count();
        $star5 = \App\Models\Ticket::where('rating', 5)->count();
    @endphp

    <!-- 4 Original Gradient Top Cards (Gaya Analytics System Lama) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3.5">
        
        <!-- Average Staff Satisfaction (Purple Gradient) -->
        <div class="rounded-xl p-4 shadow-xl flex items-center justify-between transform hover:-translate-y-0.5 transition-all duration-200" style="background: linear-gradient(135deg, #6f42c1, #4e73df);">
            <div>
                <p class="text-[10px] font-black uppercase tracking-wider text-slate-950" style="color: #000000;">AVG STAFF SATISFACTION</p>
                <div class="flex items-baseline gap-1 mt-1">
                    <span class="text-2xl font-black text-slate-950" style="color: #000000;">{{ number_format($avgRating, 1) }}</span>
                    <span class="text-xs font-bold text-slate-900/80" style="color: #000000;">/ 5.0</span>
                </div>
                <p class="text-[9px] font-bold text-slate-900/80 mt-1" style="color: #000000;">Based on {{ $totalFeedback }} feedback</p>
            </div>
            <div class="text-amber-300 text-3xl opacity-90 drop-shadow">
                <i class="fa-solid fa-star"></i>
            </div>
        </div>

        <!-- Resolved Tickets (Green Gradient) -->
        <div class="rounded-xl p-4 shadow-xl flex items-center justify-between transform hover:-translate-y-0.5 transition-all duration-200" style="background: linear-gradient(135deg, #198754, #20c997);">
            <div>
                <p class="text-[10px] font-black uppercase tracking-wider text-slate-950" style="color: #000000;">RESOLVED TICKETS</p>
                <span class="text-2xl font-black text-slate-950 mt-1 block" style="color: #000000;">{{ $closedTickets }}</span>
                <p class="text-[9px] font-bold text-slate-900/80 mt-1" style="color: #000000;">Status: Closed</p>
            </div>
            <div class="w-9 h-9 rounded-full bg-black/15 text-slate-950 flex items-center justify-center text-sm font-black shadow-inner">
                <i class="fa-solid fa-check"></i>
            </div>
        </div>

        <!-- Unresolved Tickets (Orange/Amber Gradient) -->
        <div class="rounded-xl p-4 shadow-xl flex items-center justify-between transform hover:-translate-y-0.5 transition-all duration-200" style="background: linear-gradient(135deg, #fd7e14, #ffc107);">
            <div>
                <p class="text-[10px] font-black uppercase tracking-wider text-slate-950" style="color: #000000;">UNRESOLVED TICKETS</p>
                <span class="text-2xl font-black text-slate-950 mt-1 block" style="color: #000000;">{{ $pendingTickets }}</span>
                <p class="text-[9px] font-bold text-slate-900/80 mt-1" style="color: #000000;">Status: Open / In Progress</p>
            </div>
            <div class="w-9 h-9 rounded-full bg-black/15 text-slate-950 flex items-center justify-center text-sm font-black shadow-inner">
                <i class="fa-solid fa-hourglass-half"></i>
            </div>
        </div>

        <!-- Total Overall (Cyan-Blue Gradient) -->
        <div class="rounded-xl p-4 shadow-xl flex items-center justify-between transform hover:-translate-y-0.5 transition-all duration-200" style="background: linear-gradient(135deg, #0dcaf0, #0d6efd);">
            <div>
                <p class="text-[10px] font-black uppercase tracking-wider text-slate-950" style="color: #000000;">TOTAL OVERALL</p>
                <span class="text-2xl font-black text-slate-950 mt-1 block" style="color: #000000;">{{ $totalTickets }}</span>
                <p class="text-[9px] font-bold text-slate-900/80 mt-1" style="color: #000000;">Recorded in database</p>
            </div>
            <div class="w-9 h-9 rounded-full bg-black/15 text-slate-950 flex items-center justify-center text-sm font-black shadow-inner">
                <i class="fa-solid fa-ticket"></i>
            </div>
        </div>

    </div>

    <!-- Charts Row 1: Pie & Donut Charts (DARK SLATE CARDS) -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">
        
        <!-- Category Breakdown -->
        <div class="bg-slate-900/90 backdrop-blur-xl text-slate-100 rounded-2xl p-5 shadow-2xl border border-slate-800/80">
            <h3 class="text-xs font-bold uppercase tracking-wider text-slate-200 mb-4 flex items-center gap-2">
                <i class="fa-solid fa-chart-pie text-cyan-400"></i> Breakdown by Issue Category
            </h3>
            <div class="h-64 relative flex items-center justify-center">
                <canvas id="categoryChart"></canvas>
            </div>
        </div>

        <!-- Ticket Status Breakdown -->
        <div class="bg-slate-900/90 backdrop-blur-xl text-slate-100 rounded-2xl p-5 shadow-2xl border border-slate-800/80">
            <h3 class="text-xs font-bold uppercase tracking-wider text-slate-200 mb-4 flex items-center gap-2">
                <i class="fa-solid fa-chart-donut text-emerald-400"></i> Ticket Status Breakdown
            </h3>
            <div class="h-64 relative flex items-center justify-center">
                <canvas id="statusChart"></canvas>
            </div>
        </div>

    </div>

    <!-- Charts Row 2: Ratings Bar Chart (DARK SLATE CARD) -->
    <div class="bg-slate-900/90 backdrop-blur-xl text-slate-100 rounded-2xl p-5 shadow-2xl border border-slate-800/80">
        <h3 class="text-xs font-bold uppercase tracking-wider text-slate-200 mb-4 flex items-center gap-2">
            <i class="fa-solid fa-chart-column text-amber-400"></i> Staff Satisfaction Rating Distribution (Stars)
        </h3>
        <div class="h-60 relative">
            <canvas id="ratingChart"></canvas>
        </div>
    </div>

</div>

<!-- Include Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    // Global Chart.js Text Color for Dark Theme
    Chart.defaults.color = '#cbd5e1';

    // 1. Category Chart
    const ctxCat = document.getElementById('categoryChart').getContext('2d');
    new Chart(ctxCat, {
        type: 'pie',
        data: {
            labels: ['Hardware', 'Software', 'Network', 'Other'],
            datasets: [{
                data: [{{ $hardwareCount }}, {{ $softwareCount }}, {{ $networkCount }}, {{ $otherCount }}],
                backgroundColor: ['#38bdf8', '#34d399', '#f59e0b', '#94a3b8'],
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
                    labels: { color: '#cbd5e1', font: { weight: '600', size: 11 } }
                }
            }
        }
    });

    // 2. Status Chart
    const ctxStat = document.getElementById('statusChart').getContext('2d');
    new Chart(ctxStat, {
        type: 'doughnut',
        data: {
            labels: ['Open', 'In Progress', 'Closed'],
            datasets: [{
                data: [{{ $openCount }}, {{ $inProgressCount }}, {{ $closedTickets }}],
                backgroundColor: ['#f43f5e', '#f59e0b', '#10b981'],
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
                    labels: { color: '#cbd5e1', font: { weight: '600', size: 11 } }
                }
            }
        }
    });

    // 3. Rating Chart
    const ctxRate = document.getElementById('ratingChart').getContext('2d');
    new Chart(ctxRate, {
        type: 'bar',
        data: {
            labels: ['1 Star', '2 Stars', '3 Stars', '4 Stars', '5 Stars'],
            datasets: [{
                label: 'Number of Feedback',
                data: [{{ $star1 }}, {{ $star2 }}, {{ $star3 }}, {{ $star4 }}, {{ $star5 }}],
                backgroundColor: '#f59e0b',
                borderRadius: 6
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: { 
                    beginAtZero: true, 
                    ticks: { stepSize: 1, color: '#94a3b8' },
                    grid: { color: 'rgba(255, 255, 255, 0.05)' }
                },
                x: {
                    ticks: { color: '#94a3b8' },
                    grid: { display: false }
                }
            },
            plugins: {
                legend: { display: false }
            }
        }
    });
</script>
@endsection