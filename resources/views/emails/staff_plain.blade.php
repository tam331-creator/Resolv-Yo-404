✅ TICKET CLOSED #[{{ $ticket->id }}]

Hello {{ $ticket->user->name ?? ($ticket->user_name ?? 'User') }},

Your IT support ticket has been successfully resolved and marked as CLOSED.

Ticket ID: #{{ $ticket->id }}
Title: {{ $ticket->title }}
Technician: {{ $ticket->technician->name ?? 'IT Team' }}
Status: CLOSED

Thank you for using our IT Helpdesk service.

Helpdesk System • Automated Notification