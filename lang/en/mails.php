<?php

declare(strict_types=1);

return [
    'reset_password' => [
        'subject'       => 'Reset Your Password',
        'greeting'      => 'Hello!',
        'line_1'        => 'You are receiving this email because we received a password reset request for your account.',
        'action'        => 'Reset Password',
        'line_2'        => 'This password reset link will expire in :count minutes.',
        'salutation'    => 'Regards,',
        'team_name'     => 'The CatchUp Team',
        'alt_logo'      => 'CatchUp Logo',
        'fallback_text' => 'If you\'re having trouble clicking the "Reset Password" button, copy and paste the URL below into your web browser:',
        'footer_text'   => '&copy; :year CatchUp. All rights reserved.',
    ],
];
