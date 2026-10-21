<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Ticket extends Model
{
    use HasFactory;

    protected $guarded = [];

    // Ditambah untuk kebenaran kemaskini rating & SLA
    protected $fillable = [
        'title',
        'category',
        'priority',
        'description',
        'asset_id',
        'user_id',
        'assigned_to',
        'attachment',
        'status',
        'resolution_notes',
        'rating',
        'rating_feedback',
        'sla_due_at',
    ];

    protected $casts = [
        'sla_due_at' => 'datetime',
    ];

    /**
     * Kira tarikh luput SLA berdasarkan Priority:
     * High   = +3 Jam
     * Medium = +8 Jam
     * Low    = +24 Jam
     */
    public static function calculateSlaDueAt(string $priority, $fromTime = null): Carbon
    {
        $baseTime = $fromTime ? Carbon::parse($fromTime) : now();

        return match (strtolower($priority)) {
            'high'   => $baseTime->copy()->addHours(3),
            'medium' => $baseTime->copy()->addHours(8),
            default  => $baseTime->copy()->addHours(24), // Low atau keutamaan lain
        };
    }

    // Hubungan ke User (Pelapor)
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Hubungan ke Asset
    public function asset()
    {
        return $this->belongsTo(Asset::class, 'asset_id');
    }

    // Hubungan ke Juruteknik
    public function technician()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function assignedTechnician()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function reads()
    {
        return $this->hasMany(TicketRead::class);
    }

    public function isUnreadFor($userId)
    {
        $read = $this->reads()
            ->where('user_id', $userId)
            ->first();

        if (!$read) {
            return true;
        }

        if (!$this->last_message_at) {
            return false;
        }

        return $this->last_message_at > $read->last_read_at;
    }
}