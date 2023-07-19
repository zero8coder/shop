<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\FormRequest;

class MenuRequest extends FormRequest
{
    public function rules(): array
    {
        switch ($this->method()) {
            case 'POST':
                return [
                    'pid'  => 'required|int',
                    'name' => 'required',
                    'sort' => 'required|int'
                ];
        }
        return [];
    }
}
