<?php

namespace App\Http\Requests;

use App\BusinessCode;
use App\Http\Controllers\ApiResponse;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest as BaseFormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Symfony\Component\HttpFoundation\Response;

class FormRequest extends BaseFormRequest
{
    use ApiResponse;
    public function authorize(): bool
    {
        return true;
    }

    public function failedValidation(Validator $validator)
    {
        $error= $validator->errors()->all();
        throw new HttpResponseException($this->error($error[0], Response::HTTP_UNAUTHORIZED, BusinessCode::PARAMETER_ERROR));
    }

}
