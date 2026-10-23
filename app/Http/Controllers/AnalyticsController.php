<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AnalyticsController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // Asas Kueri Berdasarkan RBAC
        $ticketQuery = Ticket::query();

        if ($user && $user->role === 'staff') {
            $ticketQuery->where('user_id', $user->id);
        } elseif ($user && $user->role === 'technician') {
            $ticketQuery->where('assigned_to', $user->id);
        }

        // 1. Ringkasan Keseluruhan Tiket
        $totalTickets   = (clone $ticketQuery)->count();
        $closedTickets  = (clone $ticketQuery)->whereIn(DB::raw('LOWER(status)'), ['closed', 'resolved'])->count();
        $pendingTickets = (clone $ticketQuery)->whereIn(DB::raw('LOWER(status)'), ['open', 'in progress'])->count();

        // 2. Breakdown Mengikut Kategori (Case-Insensitive)
        $hardwareCount  = (clone $ticketQuery)->where(DB::raw('LOWER(category)'), 'hardware')->count();
        $softwareCount  = (clone $ticketQuery)->where(DB::raw('LOWER(category)'), 'software')->count();
        $networkCount   = (clone $ticketQuery)->where(DB::raw('LOWER(category)'), 'network')->count();
        $otherCount     = (clone $ticketQuery)->whereNotIn(DB::raw('LOWER(category)'), ['hardware', 'software', 'network'])->count();

        // 3. Breakdown Status Tiket (Case-Insensitive)
        $openCount       = (clone $ticketQuery)->where(DB::raw('LOWER(status)'), 'open')->count();
        $inProgressCount = (clone $ticketQuery)->where(DB::raw('LOWER(status)'), 'in progress')->count();

        // 4. Pengiraan Penilaian CSAT / Feedback Rating
        $ratingQuery   = (clone $ticketQuery)->whereNotNull('rating')->where('rating', '>', 0);
        $avgRating     = round((float)$ratingQuery->avg('rating') ?? 0, 1);
        $totalFeedback = $ratingQuery->count();

        // Breakdown Taburan Bintang (1 - 5 Stars)
        $star1 = (clone $ticketQuery)->where('rating', 1)->count();
        $star2 = (clone $ticketQuery)->where('rating', 2)->count();
        $star3 = (clone $ticketQuery)->where('rating', 3)->count();
        $star4 = (clone $ticketQuery)->where('rating', 4)->count();
        $star5 = (clone $ticketQuery)->where('rating', 5)->count();

        // Menyokong pemanggilan view 'tickets.analytics' atau 'analytics.index'
        $viewName = view()->exists('tickets.analytics') ? 'tickets.analytics' : 'analytics.index';

        return view($viewName, compact(
            'totalTickets',
            'closedTickets',
            'pendingTickets',
            'hardwareCount',
            'softwareCount',
            'networkCount',
            'otherCount',
            'openCount',
            'inProgressCount',
            'avgRating',
            'totalFeedback',
            'star1',
            'star2',
            'star3',
            'star4',
            'star5'
        ));
    }
}