<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\InteractsWithSockets;

class UserTyping implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $ticketId;
    public $userName;
    public $role;

    public function __construct($ticketId, $userName, $role)
{
    $this->ticketId = $ticketId;
    $this->userName = $userName;
    $this->role = $role;
}

    public function broadcastOn(): array
    {
        return [
            new Channel('ticket.' . $this->ticketId),
        ];
    }

    public function broadcastAs(): string
    {
        return 'user.typing';
    }
    
public function broadcastWith(): array
{
    return [
        'userName' => $this->userName,
        'role' => $this->role,
    ];
}
}