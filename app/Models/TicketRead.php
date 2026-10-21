<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TicketRead extends Model
{
    protected $fillable = [
        'ticket_id',
        'user_id',
        'last_read_at',
    ];

    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}