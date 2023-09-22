<?php

namespace App\Http\Requests\Tenants\CRM;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class ClientCreateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    final public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    final public function rules(): array
    {
        return [
            'full_name' => 'string',
            'phones' => 'array',
            'phones.*' => 'string', // Всі значення в масиві phones мають бути строками
            'emails' => 'array',
            'emails.*' => 'string|nullable', // Всі значення в масиві emails мають бути строками
        ];
    }

    /**
     * Format errors
     *
     * @param Validator $validator
     */
    final public function failedValidation(Validator $validator)
    {
        return throw new HttpResponseException(response()->json($validator->errors(), 422));
    }
}
