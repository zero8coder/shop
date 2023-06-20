<?php

namespace App\Http\Requests\Api;

use App\Http\Requests\FormRequest;

class CaptchaRequest extends FormRequest
{
    public function rules(): array
    {
        return [
           'phone' => 'required|phone:CN,mobile|unique:users'
       ];
    }
}
