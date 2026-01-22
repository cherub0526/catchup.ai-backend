<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\V1;

use Hypervel\Http\Request;
use App\Validators\SettingValidator;
use App\Exceptions\InvalidRequestException;
use App\Http\Controllers\AbstractController;

class SettingsController extends AbstractController
{
    /**
     * @throws InvalidRequestException
     */
    public function store(Request $request)
    {
        $params = $request->only(['ai']);

        $v = new SettingValidator($params);
        $v->setUpdateRules();

        if (!$v->passes()) {
            throw new InvalidRequestException($v->errors()->toArray());
        }

        $request->user()->setting()->update([
            'data' => array_merge($request->setting()->first()->data, $params),
        ]);

        return response()->make(self::RESPONSE_OK);
    }
}
