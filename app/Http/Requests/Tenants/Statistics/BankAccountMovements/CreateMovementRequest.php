<?php

namespace App\Http\Requests\Tenants\Statistics\BankAccountMovements;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class CreateMovementRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    final public function rules(): array
    {
        return [
            'date' => 'required',
            'account_id' => 'required|integer',
            'sum' => 'required|integer'
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
