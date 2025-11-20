<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\V1\Subscriptions;

use App\Exceptions\InvalidRequestException;
use App\Models\Paddle;
use App\Validators\PaddleTransactionValidator;
use Hypervel\Http\Request;

class WebhookController
{
    /**
     * @throws InvalidRequestException
     */
    public function store(Request $request)
    {
        $params = $request->all();

        $v = new PaddleTransactionValidator($params);
        $v->setStoreRules();

        if (! $v->passes()) {
            throw new InvalidRequestException($v->errors()->toArray());
        }

        $transactionData = $params['data'];
        $paddlePrice = Paddle::where('paddle_id', $transactionData['items'][0]['price']['id'])->first();
        if (! $paddlePrice || ! $paddlePrice->price) {
            throw new InvalidRequestException(['transaction' => ['']]);
        }

        $paddlePlan = Paddle::where('paddle_id', $transactionData['items'][0]['price']['product_id'])->first();
        if (! $paddlePlan || ! $paddlePlan->plan) {
            throw new InvalidRequestException(['transaction' => ['']]);
        }

        if (! $paddlePlan->plan->prices()->find($paddlePrice->price->id)) {
            throw new InvalidRequestException(['transaction' => ['']]);
        }
    }
}
