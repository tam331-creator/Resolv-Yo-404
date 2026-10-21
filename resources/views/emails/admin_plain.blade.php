🚨 NEW TICKET CREATED #[{{ $ticket->id }}]

Hello Admin,

A new support ticket has been submitted to the IT Helpdesk System and requires your attention:

Ticket ID: #{{ $ticket->id }}
Title: {{ $ticket->title }}
Reporter: {{ $ticket->user->name ?? ($ticket->user_name ?? 'Staff User') }}
Category: {{ $ticket->category }}
Priority: {{ $ticket->priority }}

Please log in to the admin dashboard for further review and action.

Helpdesk System • Automated Notification