<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\FormRequest;

class UpdateAdminRequest extends FormRequest
{

    public function rules()
    {
        $adminId = auth('admin')->id();
        return [
            'phone' => 'phone:CN,mobile|unique:admins',
            'email' => 'email|unique:admins,email,' . $adminId,
            'sex'   => 'in:1,2'
        ];
    }
}
