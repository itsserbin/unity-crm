<?php

namespace App\Http\Requests\Tenants\Catalog;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class CategoryRequest extends FormRequest
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
     * @return array<string, ValidationRule|array|string>
     */
    final public function rules(): array
    {
        return [
            'title' => 'required|string'
        ];
    }

    /**
     * @comment Format errors
     *
     * @param Validator $validator
     */
    final public function failedValidation(Validator $validator)
    {
        return throw new HttpResponseException(response()->json($validator->errors(), 422));
    }
}
