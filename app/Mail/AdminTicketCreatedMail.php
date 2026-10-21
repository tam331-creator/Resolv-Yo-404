<?php

namespace App\Mail;

use App\Models\Ticket;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AdminTicketCreatedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $ticket;

    public function __construct(Ticket $ticket)
    {
        $this->ticket = $ticket;
    }

    public function build()
    {
        return $this->subject('[URGENT] New Ticket Created: #' . $this->ticket->id)
                    ->view('emails.admin')
                    ->text('emails.admin_plain'); // Wajib untuk melepasi Outlook
    }
}