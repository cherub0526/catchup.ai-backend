<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\V1\Media;

use Hypervel\Http\Request;
use App\Utils\Const\ISO6391;
use App\Utils\OpenAI\Completion;
use App\Services\Prompts\TemplateFactory;
use App\Validators\CustomPromptValidator;
use App\Exceptions\InvalidRequestException;
use App\Services\Prompts\TemplateCompletionManager;

class CustomPromptController
{
    /**
     * @throws InvalidRequestException
     */
    public function store(Request $request, string $mediaId)
    {
        $params = $request->only(['prompt']);

        $v = new CustomPromptValidator($params);
        $v->setStoreRules();

        if (!$v->passes()) {
            throw new InvalidRequestException($v->errors()->toArray());
        }

        if (!$media = $request->user()->media()->find($mediaId)) {
            throw new InvalidRequestException(['media' => [__('validators.controllers.media.not_found')]]);
        }

        $completion = new Completion(env('OPENAI_API_KEY'));

        $template = TemplateFactory::create('customPrompt', [
            'system_prompt'    => $params['prompt'],
            'user_prompt'      => $media->captions()->orderByDesc('primary')->first()->text ?? '',
            'respond_language' => ISO6391::getNameByCode($request->user()->setting()->first()->data['ai']['language']),
        ]);

        dd($template->getSystemPrompt());

        $openai = new TemplateCompletionManager($completion, $template);
        $response = $openai->complete('', 'gpt-4.1-mini');

        return response()->json([
            'text' => json_decode($response['choices'][0]['message']['content'], true)[''],
        ]);
    }
}
