<?php

declare(strict_types=1);

namespace App\Mail;

use Hypervel\Mail\Mailable;
use Hypervel\Queue\Queueable;
use Hypervel\Queue\SerializesModels;

class ResetPasswordMail extends Mailable
{
    use Queueable;
    use SerializesModels;

    public string $token;

    public int $minutes;

    public function __construct(string $token, int $minutes)
    {
        $this->token = $token;

        $this->minutes = $minutes;
    }

    public function build(): self
    {
        $url = parse_url($this->token);

        $resetUrl = env('CLIENT_URL') . '/reset-password?' . $url['query'];

        return $this->subject(__('mails.reset_password.subject'))
            ->view('emails.reset-password', [
                'url'     => $resetUrl,
                'minutes' => $this->minutes,
            ]);
    }
}
