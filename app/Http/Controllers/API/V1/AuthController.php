<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\V1;

use Hypervel\Http\Request;

class AuthController
{
    public function store(Request $request)
    {
        return response()->json([]);
    }
}
