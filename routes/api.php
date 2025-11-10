<?php

declare(strict_types=1);

use App\Http\Controllers\IndexController;
use Hypervel\Support\Facades\Route;

Route::any('/', ['as', 'index', 'uses' => IndexController::class . '@index']);
