<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role', // Ditambah sekiranya anda menggunakan lajur role dalam pangkalan data
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
{
    return [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'last_seen' => 'datetime',
    ];
}

    /**
     * Tiket yang ditugaskan kepada juruteknik/admin ini.
     * Ini menyelesaikan isu BadMethodCallException pada TicketController line 47.
     */
    public function tickets()
    {
        return $this->hasMany(Ticket::class, 'assigned_to');
    }

    /**
     * Tiket yang dicipta oleh pengguna ini.
     */
    public function createdTickets()
    {
        return $this->hasMany(Ticket::class, 'user_id');
    }
    
    public function isOnline()
{
    if (!$this->last_seen) {
        return false;
    }

    return $this->last_seen->gt(now()->subMinutes(2));
}
    public function ticketReads()
    {
        return $this->hasMany(TicketRead::class);
    }
}