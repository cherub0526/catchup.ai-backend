<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('mails.reset_password.subject') }}</title>
    <style>
        /* Base Reset */
        body {
            margin: 0;
            padding: 0;
            background-color: #f5f7fa; /* var(--bg-color) */
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            -webkit-font-smoothing: antialiased;
            color: #333333; /* var(--text-color) */
            line-height: 1.6;
        }

        /* Layout */
        .wrapper {
            width: 100%;
            background-color: #f5f7fa;
            padding: 40px 0;
        }

        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff; /* var(--bg-secondary) */
            border-radius: 8px; /* var(--border-radius) */
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05); /* Soft shadow like var(--shadow-md) */
            overflow: hidden;
        }

        /* Branding */
        .header {
            text-align: center;
            padding: 30px 20px;
            border-bottom: 1px solid #f0f2f5;
        }

        .logo {
            max-width: 150px;
            height: auto;
        }

        /* Content */
        .content {
            padding: 40px 40px;
            text-align: left;
        }

        h1 {
            margin: 0 0 20px 0;
            font-size: 24px;
            font-weight: 600;
            color: #333333;
        }

        p {
            margin: 0 0 20px 0;
            font-size: 16px;
            color: #666666; /* var(--text-secondary) */
        }

        /* Action Button with Gradient */
        .button-container {
            text-align: center;
            margin: 30px 0;
        }

        .btn-primary {
            display: inline-block;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); /* Your Primary -> Secondary gradient */
            color: #ffffff !important;
            font-weight: 600;
            text-decoration: none;
            padding: 12px 32px;
            border-radius: 8px;
            font-size: 16px;
            transition: opacity 0.3s;
        }

        .btn-primary:hover {
            opacity: 0.9;
        }

        /* Footer */
        .footer {
            padding: 20px;
            text-align: center;
            font-size: 12px;
            color: #999999; /* var(--text-light) */
            background-color: #fafafa;
            border-top: 1px solid #f0f2f5;
        }

        /* Responsive */
        @media only screen and (max-width: 600px) {
            .content {
                padding: 20px;
            }

            .wrapper {
                padding: 20px 10px;
            }
        }
    </style>
</head>
<body>
<div class="wrapper">
    <div class="email-container">

        <!-- Header with Logo -->
        <div class="header">
            <!-- IMPORTANT: Replace src with your actual hosted URL for logo1.png -->
            <!-- Example: https://your-catchup-domain.com/assets/logo1.png -->
            <img src="{{ asset('/logo2.png') }}" alt="{{ __('mails.reset_password.alt_logo') }}" class="logo">
        </div>

        <!-- Main Content -->
        <div class="content">
            <p style="font-size: 20px; font-weight: 600; color: #333;">{{ __('mails.reset_password.greeting') }}</p>

            <p>{{ __('mails.reset_password.line_1') }}</p>

            <div class="button-container">
                <!-- The link provided in your content -->
                <a href="{{ $url }}" class="btn-primary" target="_blank">{{ __('mails.reset_password.action') }}</a>
            </div>

            <p style="font-size: 14px; color: #999;">
            <p>{{ __('mails.reset_password.line_2') }}</p></p>

            <p>{{ __('mails.reset_password.salutation') }},<br>{{ __('mails.reset_password.team_name') }}</p>

            <!-- Fallback Link Section -->
            <p style="margin-top: 30px; border-top: 1px solid #eee; padding-top: 20px; font-size: 12px; color: #999; word-break: break-all;">
                {{ __('mails.reset_password.fallback_text') }}<br>
                <a href="{{ $url }}" style="color: #667eea; text-decoration: none;">{{ $url }}</a>
            </p>
        </div>

        <!-- Footer -->
        <div class="footer">
            {!! __('mails.reset_password.footer_text', ['year' => '2025']) !!}
        </div>
    </div>
</div>
</body>
</html>
