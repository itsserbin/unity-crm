<?php

namespace App\Http\Requests\Tenants\Options;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class UserUpdateRequest extends FormRequest
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
        $rules = [
            'name' => 'string|required',
            'phone' => 'string|required',
            'email' => 'string|required',
        ];

        // Перевірка паролю, якщо він був вказаний
        if ($this->filled('password')) {
            $rules['password'] = 'string|confirmed';
        }

        return $rules;
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
