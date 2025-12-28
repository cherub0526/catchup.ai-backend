<?php

declare(strict_types=1);

namespace App\Services;

use Aws\Sqs\SqsClient;
use Aws\Exception\AwsException;
use Hypervel\Support\Facades\Log;

class SQSService
{
    protected SqsClient $client;

    public function __construct()
    {
        $this->client = new SqsClient([
            'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
            'version' => 'latest',
            'credentials' => [
                'key' => env('AWS_ACCESS_KEY_ID'),
                'secret' => env('AWS_SECRET_ACCESS_KEY'),
            ],
        ]);
    }

    /**
     * Send a message to the specified SQS queue.
     *
     * @param string $queueName
     * @param array $data
     * @return string|null
     */
    public function push(string $queueName, array $data): ?string
    {
        try {
            if (filter_var($queueName, FILTER_VALIDATE_URL)) {
                $queueUrl = $queueName;
            } else {
                $result = $this->client->getQueueUrl(['QueueName' => $queueName]);
                $queueUrl = $result->get('QueueUrl');
            }

            $result = $this->client->sendMessage([
                'QueueUrl' => $queueUrl,
                'MessageBody' => json_encode($data),
            ]);

            return $result->get('MessageId');
        } catch (AwsException $e) {
            Log::error("SQS Service Error: " . $e->getMessage());
            return null;
        }
    }
}
