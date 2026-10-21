<?php

namespace App\Mail;

use App\Models\Ticket;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TechnicianAssignedMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $ticket;

    public function __construct(Ticket $ticket)
    {
        $this->ticket = $ticket;
    }

    public function build()
    {
        return $this->subject('[NEW ASSIGNMENT] Ticket #' . $this->ticket->id . ' has been assigned to you')
                    ->view('emails.technician')
                    ->text('emails.technician_plain'); // Wajib untuk melepasi Outlook
    }
}