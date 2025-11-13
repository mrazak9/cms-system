<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Contact Form Submission</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: #4A90E2;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 5px 5px 0 0;
        }
        .content {
            background-color: #f9f9f9;
            padding: 30px;
            border: 1px solid #ddd;
            border-top: none;
        }
        .info-row {
            margin-bottom: 15px;
            padding-bottom: 15px;
            border-bottom: 1px solid #e0e0e0;
        }
        .info-label {
            font-weight: bold;
            color: #555;
            display: inline-block;
            width: 120px;
        }
        .info-value {
            color: #333;
        }
        .message-box {
            background-color: white;
            padding: 20px;
            border-left: 4px solid #4A90E2;
            margin: 20px 0;
        }
        .footer {
            background-color: #f0f0f0;
            padding: 15px;
            text-align: center;
            font-size: 12px;
            color: #666;
            border-radius: 0 0 5px 5px;
        }
        .btn {
            display: inline-block;
            padding: 12px 24px;
            background-color: #4A90E2;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 20px;
        }
        .metadata {
            font-size: 11px;
            color: #999;
            margin-top: 15px;
            padding-top: 15px;
            border-top: 1px solid #e0e0e0;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1 style="margin: 0;">New Contact Form Submission</h1>
    </div>

    <div class="content">
        <p style="font-size: 16px; margin-bottom: 20px;">
            You have received a new message from your website contact form.
        </p>

        <div class="info-row">
            <span class="info-label">From:</span>
            <span class="info-value"><strong>{{ $submission->name }}</strong></span>
        </div>

        <div class="info-row">
            <span class="info-label">Email:</span>
            <span class="info-value">
                <a href="mailto:{{ $submission->email }}">{{ $submission->email }}</a>
            </span>
        </div>

        <div class="info-row">
            <span class="info-label">Subject:</span>
            <span class="info-value"><strong>{{ $submission->subject }}</strong></span>
        </div>

        <div class="info-row">
            <span class="info-label">Date:</span>
            <span class="info-value">{{ $submission->created_at->format('F d, Y \a\t H:i') }}</span>
        </div>

        <div class="message-box">
            <h3 style="margin-top: 0; color: #4A90E2;">Message:</h3>
            <p style="white-space: pre-wrap;">{{ $submission->message }}</p>
        </div>

        <div style="text-align: center;">
            <a href="{{ route('admin.contact-submissions.show', $submission->id) }}" class="btn">
                View in Admin Panel
            </a>
        </div>

        <div class="metadata">
            <strong>Submission Details:</strong><br>
            IP Address: {{ $submission->ip_address ?? 'N/A' }}<br>
            User Agent: {{ Str::limit($submission->user_agent ?? 'N/A', 80) }}
        </div>
    </div>

    <div class="footer">
        <p style="margin: 0;">
            This is an automated notification from {{ config('app.name') }}<br>
            Please do not reply to this email directly. Use the "Reply" button in the admin panel or reply to {{ $submission->email }}.
        </p>
    </div>
</body>
</html>
