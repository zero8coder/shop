<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\FormRequest;

class UpdateAdminRequest extends FormRequest
{

    public function rules()
    {
        $adminId = auth('admin')->id();
        return [
            'email' => 'email|unique:admins,email,' . $adminId,
            'sex'   => 'in:1,2'
        ];
    }
}
