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
                <i class="fa-solid fa-chart-line text-indigo-400"></i> Analytics & Performance Report
            </h1>
            <p class="text-xs text-slate-400 mt-1">Visual representation of IT support ticket statistics and breakdown.</p>
        </div>
        <div class="flex flex-wrap items-center gap-2.5 w-full sm:w-auto shrink-0">
            <button onclick="window.print()" class="flex-1 sm:flex-none bg-indigo-600/20 hover:bg-indigo-600/30 text-indigo-300 border border-indigo-500/40 text-xs font-bold px-4 py-2.5 rounded-xl shadow-lg transition flex items-center justify-center gap-2">
                <i class="fa-solid fa-print"></i> Print / Save PDF
            </button>
            <a href="{{ route('tickets.index') }}" class="flex-1 sm:flex-none bg-slate-800/90 hover:bg-slate-700/90 text-slate-300 text-xs font-bold px-4 py-2.5 rounded-xl shadow transition flex items-center justify-center gap-2 border border-slate-700/80">
                <i class="fa-solid fa-arrow-left"></i> Back to Dashboard
            </a>
        </div>
    </div>

    <!-- 4 Modern Low-Glare KPI Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        
        <!-- Average Staff Satisfaction -->
        <div class="relative bg-slate-900/90 border-b-2 border-b-indigo-500 border border-slate-800/80 rounded-2xl p-4 shadow-xl flex items-center justify-between backdrop-blur-xl transition-all duration-200 hover:-translate-y-0.5">
            <div>
                <p class="text-[10px] font-extrabold uppercase tracking-widest text-indigo-400">AVG SATISFACTION</p>
                <div class="flex items-baseline gap-1 mt-1.5">
                    <span class="text-3xl font-black text-slate-100">{{ number_format($avgRating, 1) }}</span>
                    <span class="text-xs font-bold text-slate-400">/ 5.0</span>
                </div>
                <p class="text-[10px] font-medium text-slate-400 mt-1">Based on {{ $totalFeedback }} feedback</p>
            </div>
            <div class="w-10 h-10 rounded-xl bg-indigo-500/10 text-indigo-400 border border-indigo-500/20 flex items-center justify-center text-lg">
                <i class="fa-solid fa-star"></i>
            </div>
        </div>

        <!-- Resolved Tickets -->
        <div class="relative bg-slate-900/90 border-b-2 border-b-emerald-500 border border-slate-800/80 rounded-2xl p-4 shadow-xl flex items-center justify-between backdrop-blur-xl transition-all duration-200 hover:-translate-y-0.5">
            <div>
                <p class="text-[10px] font-extrabold uppercase tracking-widest text-emerald-400">RESOLVED TICKETS</p>
                <span class="text-3xl font-black text-slate-100 mt-1.5 block">{{ $closedTickets }}</span>
                <p class="text-[10px] font-medium text-slate-400 mt-1">Status: Closed / Resolved</p>
            </div>
            <div class="w-10 h-10 rounded-xl bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 flex items-center justify-center text-lg">
                <i class="fa-solid fa-check"></i>
            </div>
        </div>

        <!-- Unresolved Tickets -->
        <div class="relative bg-slate-900/90 border-b-2 border-b-amber-500 border border-slate-800/80 rounded-2xl p-4 shadow-xl flex items-center justify-between backdrop-blur-xl transition-all duration-200 hover:-translate-y-0.5">
            <div>
                <p class="text-[10px] font-extrabold uppercase tracking-widest text-amber-400">UNRESOLVED TICKETS</p>
                <span class="text-3xl font-black text-slate-100 mt-1.5 block">{{ $pendingTickets }}</span>
                <p class="text-[10px] font-medium text-slate-400 mt-1">Status: Open / In Progress</p>
            </div>
            <div class="w-10 h-10 rounded-xl bg-amber-500/10 text-amber-400 border border-amber-500/20 flex items-center justify-center text-lg">
                <i class="fa-solid fa-hourglass-half"></i>
            </div>
        </div>

        <!-- Total Overall -->
        <div class="relative bg-slate-900/90 border-b-2 border-b-sky-500 border border-slate-800/80 rounded-2xl p-4 shadow-xl flex items-center justify-between backdrop-blur-xl transition-all duration-200 hover:-translate-y-0.5">
            <div>
                <p class="text-[10px] font-extrabold uppercase tracking-widest text-sky-400">TOTAL OVERALL</p>
                <span class="text-3xl font-black text-slate-100 mt-1.5 block">{{ $totalTickets }}</span>
                <p class="text-[10px] font-medium text-slate-400 mt-1">Recorded in database</p>
            </div>
            <div class="w-10 h-10 rounded-xl bg-sky-500/10 text-sky-400 border border-sky-500/20 flex items-center justify-center text-lg">
                <i class="fa-solid fa-ticket"></i>
            </div>
        </div>

    </div>

    <!-- Charts Row 1: Pie & Donut Charts -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">
        
        <!-- Category Breakdown -->
        <div class="bg-slate-900/90 backdrop-blur-xl text-slate-100 rounded-2xl p-5 shadow-2xl border border-slate-800/80">
            <h3 class="text-xs font-bold uppercase tracking-wider text-slate-300 mb-4 flex items-center gap-2">
                <i class="fa-solid fa-chart-pie text-indigo-400"></i> Breakdown by Issue Category
            </h3>
            <div class="h-64 relative flex items-center justify-center">
                <canvas id="categoryChart"></canvas>
            </div>
        </div>

        <!-- Ticket Status Breakdown -->
        <div class="bg-slate-900/90 backdrop-blur-xl text-slate-100 rounded-2xl p-5 shadow-2xl border border-slate-800/80">
            <h3 class="text-xs font-bold uppercase tracking-wider text-slate-300 mb-4 flex items-center gap-2">
                <i class="fa-solid fa-chart-donut text-emerald-400"></i> Ticket Status Breakdown
            </h3>
            <div class="h-64 relative flex items-center justify-center">
                <canvas id="statusChart"></canvas>
            </div>
        </div>

    </div>

    <!-- Charts Row 2: Ratings Bar Chart -->
    <div class="bg-slate-900/90 backdrop-blur-xl text-slate-100 rounded-2xl p-5 shadow-2xl border border-slate-800/80">
        <h3 class="text-xs font-bold uppercase tracking-wider text-slate-300 mb-4 flex items-center gap-2">
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
    Chart.defaults.color = '#94a3b8';

    // 1. Category Chart (Aesthetic Glass Modern Palette)
    const ctxCat = document.getElementById('categoryChart').getContext('2d');
    new Chart(ctxCat, {
        type: 'pie',
        data: {
            labels: ['Hardware', 'Software', 'Network', 'Other'],
            datasets: [{
                data: [{{ $hardwareCount }}, {{ $softwareCount }}, {{ $networkCount }}, {{ $otherCount }}],
                backgroundColor: ['#0284c7', '#8b5cf6', '#f59e0b', '#64748b'],
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

    // 2. Status Chart (Pro-Grade Muted Tones)
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

    // 3. Rating Chart (Modern Indigo Gradient Bars)
    const ctxRate = document.getElementById('ratingChart').getContext('2d');
    new Chart(ctxRate, {
        type: 'bar',
        data: {
            labels: ['1 Star', '2 Stars', '3 Stars', '4 Stars', '5 Stars'],
            datasets: [{
                label: 'Number of Feedback',
                data: [{{ $star1 }}, {{ $star2 }}, {{ $star3 }}, {{ $star4 }}, {{ $star5 }}],
                backgroundColor: '#6366f1',
                borderRadius: 8
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: { 
                    beginAtZero: true, 
                    ticks: { stepSize: 1, color: '#64748b' },
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