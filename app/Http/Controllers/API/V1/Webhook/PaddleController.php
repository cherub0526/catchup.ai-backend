<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\V1\Webhook;

use Carbon\Carbon;
use Hypervel\Http\Request;
use App\Models\Transaction;
use App\Models\Subscription;
use App\Services\PaddleClient;
use Paddle\SDK\Exceptions\ApiError;
use App\Exceptions\InvalidRequestException;
use App\Http\Controllers\AbstractController;
use App\Validators\PaddleTransactionValidator;
use Paddle\SDK\Entities\Shared\TransactionStatus;
use Paddle\SDK\Exceptions\SdkExceptions\MalformedResponse;

class PaddleController extends AbstractController
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

        $paddleClient = new PaddleClient();

        try {
            $paddleTransaction = $paddleClient->transactions()->get($params['data']['id']);

            if ($paddleTransaction->status->getValue() !== TransactionStatus::Completed()->getValue()) {
                throw new InvalidRequestException(['transaction' => ['Transaction status is not completed.']]);
            }

            if (! $subscription = Subscription::query()->find($paddleTransaction->customData->data['subscriptionId'])) {
                throw new InvalidRequestException(['subscription' => ['Subscription not found.']]);
            }

            $paddleSubscription = $paddleClient->subscriptions()->get($paddleTransaction->subscriptionId);

            $subscription->fill([
                'start_date' => Carbon::parse($paddleSubscription->createdAt)->toDateTime(),
                'next_date' => Carbon::parse($paddleSubscription->nextBilledAt)->toDateTime(),
                'status' => Subscription::STATUS_ACTIVE,
            ])->save();

            if (! $subscription->paddle()->where(['paddle_id' => $paddleTransaction->subscriptionId])->first()) {
                $subscription->paddle()->create([
                    'paddle_id' => $paddleSubscription->id,
                    'paddle_detail' => $paddleSubscription,
                    'foreign_type' => Subscription::class,
                ]);
            }

            $transactionPaddle = $subscription->transactions()->whereHas(
                'paddle',
                function ($builder) use ($paddleTransaction) {
                    $builder->where('paddle_id', $paddleTransaction->id);
                }
            )->first();

            if (! $transactionPaddle) {
                $transactionPaddle = $subscription->transactions()->create([
                    'billing_date' => Carbon::parse($paddleTransaction->billedAt),
                    'amount' => floatval($paddleTransaction->details->totals->total) / 100,
                    'status' => TransactionStatus::Completed()->getValue(),
                ]);

                $transactionPaddle->paddle()->create([
                    'paddle_id' => $paddleTransaction->id,
                    'paddle_detail' => $paddleTransaction,
                    'foreign_type' => Transaction::class,
                ]);
            }

            return response()->make(self::RESPONSE_OK);
        } catch (ApiError $e) {
        } catch (MalformedResponse $e) {
        }
    }
}
