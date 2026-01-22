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
    public function update(Request $request)
    {
        $params = $request->only(['ai']);

        $v = new SettingValidator($params);
        $v->setUpdateRules();

        if (!$v->passes()) {
            throw new InvalidRequestException($v->errors()->toArray());
        }

        $setting = $request->user()->setting()->first();

        $setting->update([
            'data' => array_merge($setting->data, $params),
        ]);

        return response()->make(self::RESPONSE_OK);
    }
}
