<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\FormRequest;

class PermissionRequest extends FormRequest
{
    public function rules(): array
    {
        switch ($this->method()) {
            case 'PUT':
            case 'PATCH':
                return [
                    'name' => 'required|unique:permissions,name,' . $this->permission->id,
                ];
        }
        return [];
    }
}
