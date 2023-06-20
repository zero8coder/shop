<?php

namespace App\Http\Requests\Api;

use App\Http\Requests\FormRequest;

class SocialAuthorizationRequest extends FormRequest
{
    public function rules()
    {
        $rules = [
            'code' => 'required_without:access_token|string',
            'access_token' => 'required_without:code|string',
        ];

        if ($this->social_type == 'wechat' && !$this->code) {
            $rules['openid'] = 'required|string';
        }

        return $rules;
    }
}
