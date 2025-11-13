<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thank You for Contacting Us</title>
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
            background-color: #27AE60;
            color: white;
            padding: 30px 20px;
            text-align: center;
            border-radius: 5px 5px 0 0;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        .content {
            background-color: #f9f9f9;
            padding: 30px;
            border: 1px solid #ddd;
            border-top: none;
        }
        .greeting {
            font-size: 18px;
            margin-bottom: 20px;
        }
        .message-summary {
            background-color: white;
            padding: 20px;
            border-left: 4px solid #27AE60;
            margin: 20px 0;
        }
        .footer {
            background-color: #f0f0f0;
            padding: 20px;
            text-align: center;
            font-size: 12px;
            color: #666;
            border-radius: 0 0 5px 5px;
        }
        .checkmark {
            font-size: 48px;
            color: #27AE60;
            text-align: center;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="checkmark">✓</div>
        <h1>Thank You for Contacting Us!</h1>
    </div>

    <div class="content">
        <p class="greeting">
            Hello <strong>{{ $submission->name }}</strong>,
        </p>

        <p>
            Thank you for reaching out to us. We have received your message and wanted to confirm that it has been successfully delivered to our team.
        </p>

        <div class="message-summary">
            <h3 style="margin-top: 0; color: #27AE60;">Your Message Summary:</h3>
            <p style="margin: 5px 0;">
                <strong>Subject:</strong> {{ $submission->subject }}
            </p>
            <p style="margin: 5px 0;">
                <strong>Sent on:</strong> {{ $submission->created_at->format('F d, Y \a\t H:i') }}
            </p>
            <p style="margin: 15px 0 5px 0;">
                <strong>Your Message:</strong>
            </p>
            <p style="white-space: pre-wrap; color: #666; font-style: italic;">
                {{ Str::limit($submission->message, 200) }}
            </p>
        </div>

        <p>
            <strong>What happens next?</strong>
        </p>
        <ul style="line-height: 1.8;">
            <li>Our team will review your message carefully</li>
            <li>We typically respond within 24-48 hours during business days</li>
            <li>You will receive a personal response to this email address: <strong>{{ $submission->email }}</strong></li>
        </ul>

        <p style="margin-top: 25px;">
            If your inquiry is urgent, please feel free to contact us directly at:
        </p>
        <p style="text-align: center; font-size: 16px; margin: 15px 0;">
            <strong>{{ \App\Models\Setting::get('contact_email', 'info@example.com') }}</strong>
        </p>
    </div>

    <div class="footer">
        <p style="margin: 0 0 10px 0;">
            <strong>{{ \App\Models\Setting::get('site_name', config('app.name')) }}</strong>
        </p>
        <p style="margin: 0;">
            This is an automated confirmation email. Please do not reply to this message.<br>
            If you did not submit this contact form, please disregard this email.
        </p>
    </div>
</body>
</html>
