<!DOCTYPE html>
<html>
<head>
    <title>{{ __('mails.reset_password.subject') }}</title>
</head>
<body>
    <p>{{ __('mails.reset_password.greeting') }}</p>
    <p>{{ __('mails.reset_password.line_1') }}</p>
    <a href="{{ $url }}">{{ __('mails.reset_password.action') }}</a>
    <p>{{ __('mails.reset_password.line_2') }}</p>
    <p>{{ __('mails.reset_password.salutation') }}</p>
</body>
</html>
