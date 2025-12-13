<?php

declare(strict_types=1);

namespace App\Exceptions;

use Exception;
use Psr\Http\Message\ResponseInterface;

class NotFoundHttpException extends Exception
{
    public static int $statusCode = 404;

    public static string $statusMessage = 'Not Found';

    public function __construct(int $code = 404)
    {
        parent::__construct(self::$statusMessage, $code);
    }

    public function render(): ResponseInterface
    {
        return response()->json([
            'messages' => 'Resource not found.',
        ], self::$statusCode);
    }
}
