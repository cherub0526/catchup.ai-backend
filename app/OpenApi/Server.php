<?php

declare(strict_types=1);

namespace App\OpenApi;

use OpenApi\Attributes as OAT;

#[OAT\Server(
    url: 'http://127.0.0.1:9501',
    description: 'Local server'
)]
#[OAT\Server(
    url: 'https://catchupai.on-forge.com',
    description: 'Production server'
)]
class Server
{
}
