<?php

namespace App\Http\Requests\Tenants\Options;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class SourcesRequest extends FormRequest
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
            'title' => 'string|required',
            'source' => 'string|required',
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
