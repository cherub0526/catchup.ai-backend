<?php

declare(strict_types=1);

namespace App\Exceptions;

use Exception;

class InvalidRequestException extends Exception
{
    public static int $statusCode = 422;

    public static string $statusMessage = 'Bad Request';

    public array $messages = [];

    public function __construct(array $messages = [], int $code = 422)
    {
        $this->messages = $messages;

        parent::__construct(self::$statusMessage, $code);
    }

    public function render(): \Psr\Http\Message\ResponseInterface
    {
        return response()->json([
            'messages' => $this->messages,
        ], self::$statusCode);
    }
}
