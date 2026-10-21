📋 NEW TASK ASSIGNED #[{{ $ticket->id }}]

Hello {{ $ticket->technician->name ?? 'Technician' }},

The following ticket has been assigned to you for resolution:

Ticket ID: #{{ $ticket->id }}
Title: {{ $ticket->title }}
Asset/Device: {{ $ticket->asset->asset_tag ?? '-- None --' }}
Priority: {{ $ticket->priority }}
Reporter: {{ $ticket->user->name ?? 'Staff User' }}

Please review and resolve this issue as soon as possible.

Helpdesk System • Automated Notification