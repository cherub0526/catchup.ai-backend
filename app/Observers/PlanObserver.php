<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\Plan;
use Paddle\SDK\Client;
use Paddle\SDK\Entities\Shared\TaxCategory;
use Paddle\SDK\Exceptions\ApiError;
use Paddle\SDK\Exceptions\ApiError\ProductApiError;
use Paddle\SDK\Exceptions\SdkExceptions\MalformedResponse;
use Paddle\SDK\Resources\Products\Operations\CreateProduct;
use Paddle\SDK\Resources\Products\Operations\UpdateProduct;

class PlanObserver
{
    /**
     * Handle the Plan "created" event.
     */
    public function created(Plan $plan): void
    {
        $paddle = new Client(
            apiKey: env('PADDLE_API_KEY'),
            options: new \Paddle\SDK\Options(
                env('PADDLE_SANDBOX') === 'true' || env('PADDLE_SANDBOX') === true
                    ? \Paddle\SDK\Environment::SANDBOX
                    : \Paddle\SDK\Environment::PRODUCTION
            )
        );

        try {
            $product = $paddle->products->create(
                new CreateProduct(
                    name: $plan->title,
                    taxCategory: TaxCategory::Standard(),
                    description: $plan->description ?? ''
                )
            );

            $plan->fill(['paddle_plan_id' => $product->id])->save();
        } catch (ProductApiError $e) {
        } catch (ApiError $e) {
        } catch (MalformedResponse $e) {
        }
    }

    /**
     * Handle the Plan "updated" event.
     */
    public function updated(Plan $plan): void
    {
        $paddle = new Client(
            apiKey: env('PADDLE_API_KEY'),
            options: new \Paddle\SDK\Options(
                env('PADDLE_SANDBOX') === 'true' || env('PADDLE_SANDBOX') === true
                    ? \Paddle\SDK\Environment::SANDBOX
                    : \Paddle\SDK\Environment::PRODUCTION
            )
        );

        try {
            $paddle->products->update(
                $plan->paddle_plan_id,
                new UpdateProduct(
                    name: $plan->title,
                    description: $plan->description ?? ''
                )
            );
        } catch (ProductApiError $e) {
        } catch (ApiError $e) {
        } catch (MalformedResponse $e) {
        }
    }
}
