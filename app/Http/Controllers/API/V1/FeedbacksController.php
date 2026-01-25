<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\V1;

use App\Models\Image;
use App\Models\Feedback;
use Hypervel\Http\Request;
use App\Validators\ImageValidator;
use App\Validators\FeedbackValidator;
use Hypervel\Support\Facades\Storage;
use App\Exceptions\InvalidRequestException;
use App\Http\Controllers\AbstractController;

class FeedbacksController extends AbstractController
{
    /**
     * @throws InvalidRequestException
     */
    public function store(Request $request)
    {
        $params = $request->only(['content']);

        $v = new FeedbackValidator($params);
        $v->setStoreRules();

        if (!$v->passes()) {
            throw new InvalidRequestException($v->errors()->toArray());
        }

        $v = new ImageValidator($request->only(['images']));
        $v->setStoreRules();

        if (!$v->passes()) {
            throw new InvalidRequestException($v->errors()->toArray());
        }

        $feedback = Feedback::create(['content' => $params['content']]);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $key => $file) {
                $image = Image::create([
                    'filename'     => $file->getClientFilename(),
                    'foreign_type' => Feedback::class,
                    'foreign_id'   => $feedback->id,
                ]);
                $destination = sprintf('feedbacks/%s', $image->id);
                $response = Storage::disk('s3')->put($destination, file_get_contents($file->getRealPath()));

                // 如果上傳成功，就將路徑更新，失敗就刪除。
                $response
                    ? $image->update(['path' => $destination])
                    : $image->forceDelete();
            }
        }

        return response()->make(self::RESPONSE_OK);
    }
}
