<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\V1;

use Carbon\Carbon;
use App\Models\Plan;
use App\Models\Price;
use Hypervel\Http\Request;
use App\Models\Subscription;
use App\Services\PaddleClient;
use Paddle\SDK\Exceptions\ApiError;
use App\Http\Resources\PlanResource;
use App\Validators\SubscriptionValidator;
use App\Exceptions\InvalidRequestException;
use App\Http\Controllers\AbstractController;
use Paddle\SDK\Entities\Shared\TransactionStatus;
use Paddle\SDK\Exceptions\SdkExceptions\MalformedResponse;
use Paddle\SDK\Notifications\Entities\Payout\PayoutStatus;

class SubscriptionsController extends AbstractController
{
    public function index(Request $request): PlanResource
    {
        if ($subscription = $request->user()->subscriptions()->active()->orderBy('start_date', 'desc')->first()) {
            $plan = $subscription->plan()->first();
        }

        // 如果沒有找訂閱的方案，預設就是免費的月訂閱方案
        if (! isset($plan)) {
            $plan = Plan::query()->whereHas('prices', function ($builder) {
                $builder->where('unit', Price::UNIT_MONTHLY)->where('price', 0);
            })->first();
        }

        $plan->load([
            'prices' => function ($builder) use ($subscription) {
                $subscription
                    ? $builder->where('id', $subscription->price_id)
                    : $builder->where('unit', Price::UNIT_MONTHLY)->where('price', 0);
            },
        ]);

        return new PlanResource($plan);
    }

    /**
     * @throws InvalidRequestException
     */
    public function store(Request $request): \Psr\Http\Message\ResponseInterface
    {
        $params = $request->only(['planId', 'priceId']);

        $v = new SubscriptionValidator($params);
        $v->setStoreRules();

        if (! $v->passes()) {
            throw new InvalidRequestException($v->errors()->toArray());
        }

        if (! $plan = Plan::query()->find($params['planId'])) {
            throw new InvalidRequestException(['planId' => ['plan not found']]);
        }

        if (! $price = Price::query()->find($params['priceId'])) {
            throw new InvalidRequestException(['priceId' => ['price not found']]);
        }

        if (! $plan->prices()->find($price->id)) {
            throw new InvalidRequestException(['priceId' => ['price not found in plan']]);
        }

        $subscription = $request->user()->subscriptions()->create([
            'plan_id' => $plan->id,
            'price_id' => $price->id,
            'payment_method' => Subscription::PAYMENT_METHOD_PADDLE,
            'status' => Subscription::STATUS_PAYING,
        ]);

        $data = [
            'paddle' => [
                'client_token' => env('PADDLE_CLIENT_TOKEN'),
                'environment' => env('PADDLE_SANDBOX') ? 'sandbox' : 'production',
            ],
            'items' => [$price->paddle->paddle_id],
            'customer' => [
                'name' => $request->user()->name,
                'email' => $request->user()->email,
            ],
            'customData' => [
                'subscriptionId' => $subscription->id,
            ],
        ];

        if ($request->user()->paddle) {
            $data['customer']['id'] = $request->user()->paddle->paddle_customer_id;
        }

        return response()->json($data);
    }

    /**
     * @throws InvalidRequestException
     */
    public function update(Request $request, string $subscriptionId)
    {
        if (! $subscription = $request->user()->subscriptions()->find($subscriptionId)) {
            throw new InvalidRequestException(['subscriptionId' => ['subscription not found']]);
        }

        $params = $request->all();

        $paddle = new PaddleClient();
        try {
            $paddleTransaction = $paddle->transactions()->get($params['transaction_id']);

            if (
                $paddleTransaction->status->getValue() === PayoutStatus::Paid()->getValue()
                || $paddleTransaction->status->getValue() === TransactionStatus::Completed()->getValue()
            ) {
                $billedAt = Carbon::parse($paddleTransaction->billedAt);

                $items = $paddleTransaction->items;

                $subscription->fill([
                    'status' => Subscription::STATUS_ACTIVE,
                    'start_date' => $billedAt->clone()->toDateTime(),
                    'next_date' => $billedAt->clone()->add(
                        sprintf(
                            '%d %s',
                            $items[0]->price->billingCycle->frequency,
                            $items[0]->price->billingCycle->interval
                        )
                    ),
                ])->save();

                return response()->make(self::RESPONSE_OK);
            }
        } catch (ApiError $e) {
        } catch (MalformedResponse $e) {
        }
    }

    /**
     * 取消訂閱.
     */
    public function destroy(Request $request, int $subscriptionId)
    {
    }

    public function usage(Request $request): \Psr\Http\Message\ResponseInterface
    {
        $between = [
            'start' => now()->startOfMonth(),
            'end' => now()->endOfMonth(),
        ];
        return response()->json([
            'data' => [
                'plan' => [
                    'channels' => 1,
                    'media' => 5,
                ],
                'usage' => [
                    'channels' => $request->user()->rss()->count(),
                    'media' => $request->user()->media()->whereBetween('userables.created_at', $between)->count(),
                ],
            ],
        ]);
    }
}
