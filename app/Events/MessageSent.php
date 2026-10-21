<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\InteractsWithSockets;

class MessageSent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $ticketId;
    public $message;

    public function __construct($ticketId, $message)
    {
        $this->ticketId = $ticketId;
        $this->message = $message;
    }

    public function broadcastOn(): array
    {
        return [
            new Channel('tickets'),
            new Channel('ticket.'.$this->ticketId)
        ];
    }

    public function broadcastAs(): string
    {
        return 'message.sent';
    }

    public function broadcastWith(): array
    {
        return [
            'ticket_id' => $this->ticketId,
            'message'   => $this->message,
        ];
    }
}