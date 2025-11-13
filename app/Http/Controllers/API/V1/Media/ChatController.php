<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\V1\Media;

use App\Exceptions\InvalidRequestException;
use App\Services\Prompts\TemplateCompletionManager;
use App\Services\Prompts\TemplateFactory;
use App\Utils\OpenAI\Completion;
use App\Validators\ChatValidator;
use Hypervel\Http\Request;

class ChatController
{
    /**
     * @throws InvalidRequestException
     */
    public function store(Request $request, int $mediaId): \Psr\Http\Message\ResponseInterface
    {
        $params = $request->only(['messages']);

        $v = new ChatValidator($params);
        $v->setStoreRules();

        if (! $v->passes()) {
            throw new InvalidRequestException($v->errors()->toArray());
        }

        if (! $media = $request->user()->media()->find($mediaId)) {
            throw new InvalidRequestException(['media' => trans('messages.media.not_found')]);
        }

        $completion = new Completion(env('OPENAI_API_KEY'));

        $userMessage = collect($params['messages'])->last()['content'] ?? '';

        $template = TemplateFactory::create('assistant', [
            'user_prompt' => $media->captions()->first()->text ?? '',
            'messages' => array_pop($params['messages']),
        ]);
        $openai = new TemplateCompletionManager($completion, $template);
        $response = $openai->complete($userMessage, 'gpt-4.1-mini');

        return response()->json([
            'role' => 'assistant',
            'content' => $response['choices'][0]['message']['content'],
        ]);
    }
}
