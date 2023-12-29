<?php

namespace App\Http\Requests;

use Exception;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;

class BaseRequest extends FormRequest
{
    public function expectsJson(): bool
    {
        return true;
    }

    public function wantsJson(): bool
    {
        return true;
    }

    /**
     * @throws Exception
     */
    protected function failedValidation(Validator $validator)
    {
        $msg = $validator->errors()->first();

        throw new Exception($msg);
    }
}
