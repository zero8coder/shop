<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\FormRequest;

class RoleRequest extends FormRequest
{
    public function rules()
    {
        switch ($this->method()) {
            case 'POST':
                return [
                    'name' => 'required|unique:roles,name',
                    'permissions' => 'array'
                ];
        }
        return [];
    }
}
