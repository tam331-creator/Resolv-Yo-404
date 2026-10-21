<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body style="margin: 0; padding: 0; background-color: #0d1117; font-family: 'Segoe UI', Arial, sans-serif;">
    <table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color: #0d1117; padding: 30px 10px;">
        <tr>
            <td align="center">
                <table width="550" cellpadding="0" cellspacing="0" border="0" style="background-color: #161b22; border: 1px solid #30363d; border-radius: 8px; width: 550px; max-width: 100%;">
                    <tr>
                        <td align="center" style="background-color: #2563eb; padding: 18px 20px; border-top-left-radius: 8px; border-top-right-radius: 8px;">
                            <h2 style="color: #ffffff; margin: 0; font-size: 18px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px;">
                                📋 NEW TASK ASSIGNED #[{{ $ticket->id }}]
                            </h2>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 25px; color: #c9d1d9; font-size: 14px; line-height: 1.6;">
                            <p style="margin: 0 0 15px 0; color: #ffffff; font-size: 15px;">
                                Hello <strong>{{ $ticket->technician->name ?? 'Technician' }}</strong>,
                            </p>
                            <p style="margin: 0 0 20px 0; color: #8b949e;">
                                The following ticket has been assigned to you for resolution:
                            </p>

                            <table width="100%" cellpadding="8" cellspacing="0" border="0" style="background-color: #0d1117; border: 1px solid #30363d; border-radius: 6px; margin-bottom: 20px;">
                                <tr>
                                    <td width="35%" style="color: #58a6ff; font-weight: bold; font-size: 12px; text-transform: uppercase;">Ticket ID:</td>
                                    <td width="65%" style="color: #ffffff; font-weight: bold; font-size: 13px;">#{{ $ticket->id }}</td>
                                </tr>
                                <tr>
                                    <td style="color: #58a6ff; font-weight: bold; font-size: 12px; text-transform: uppercase;">Title:</td>
                                    <td style="color: #ffffff; font-size: 13px;">{{ $ticket->title }}</td>
                                </tr>
                                <tr>
                                    <td style="color: #58a6ff; font-weight: bold; font-size: 12px; text-transform: uppercase;">Asset/Device:</td>
                                    <td style="color: #ffffff; font-size: 13px;">{{ $ticket->asset->asset_tag ?? '-- None --' }}</td>
                                </tr>
                                <tr>
                                    <td style="color: #58a6ff; font-weight: bold; font-size: 12px; text-transform: uppercase;">Priority:</td>
                                    <td style="color: #ff7b72; font-weight: bold; font-size: 13px;">{{ $ticket->priority }}</td>
                                </tr>
                                <tr>
                                    <td style="color: #58a6ff; font-weight: bold; font-size: 12px; text-transform: uppercase;">Reporter:</td>
                                    <td style="color: #ffffff; font-size: 13px;">{{ $ticket->user->name ?? 'Staff User' }}</td>
                                </tr>
                            </table>

                            <p style="margin: 0; color: #8b949e; font-size: 13px;">
                                Please review and resolve this issue as soon as possible.
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <td align="center" style="background-color: #0d1117; padding: 12px; border-top: 1px solid #21262d; font-size: 11px; color: #484f58; border-bottom-left-radius: 8px; border-bottom-right-radius: 8px;">
                            Helpdesk System &bull; Automated Notification
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>