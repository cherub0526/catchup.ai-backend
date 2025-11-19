<?php

declare(strict_types=1);

namespace App\Services;

use Paddle\SDK\Client;
use Paddle\SDK\Environment;
use Paddle\SDK\Options;

class PaddleClient
{
    protected Client $client;

    public function __construct()
    {
        $this->client = new Client(
            apiKey: env('PADDLE_API_KEY'),
            options: new Options(
                env('PADDLE_SANDBOX') ? Environment::SANDBOX : Environment::PRODUCTION
            )
        );
    }

    public function customers(): \Paddle\SDK\Resources\Customers\CustomersClient
    {
        return $this->client->customers;
    }

    public function products(): \Paddle\SDK\Resources\Products\ProductsClient
    {
        return $this->client->products;
    }

    public function prices(): \Paddle\SDK\Resources\Prices\PricesClient
    {
        return $this->client->prices;
    }

    public function subscriptions(): \Paddle\SDK\Resources\Subscriptions\SubscriptionsClient
    {
        return $this->client->subscriptions;
    }

    public function transactions(): \Paddle\SDK\Resources\Transactions\TransactionsClient
    {
        return $this->client->transactions;
    }
}
