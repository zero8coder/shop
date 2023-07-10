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
            case 'PUT':
            case 'PATCH':
            return [
                'name' => 'required|unique:roles,name,' . $this->role->id,
                'permissions' => 'array'
            ];
        }
        return [];
    }
}
