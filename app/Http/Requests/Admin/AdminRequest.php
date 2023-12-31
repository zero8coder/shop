<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\FormRequest;

class AdminRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name'     => 'required|between:3,25|regex:/^[A-Za-z0-9\-\_]+$/|unique:admins,name',
            'password' => 'required|alpha_dash|min:6',
            'phone'    => 'required|phone:CN,mobile|unique:admins',
            'sex'      => 'required|in:1,2',
            'email'    => 'required|email|unique:admins,email'
        ];
    }
}
