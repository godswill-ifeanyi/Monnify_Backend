<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VerifyBankAccountRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'accountNumber' => 'required|string',
            'bankCode' => 'required|string',
        ];
    }

    /**
     * Get the body parameters for the request.
     *
     * @return array<string, array<string, mixed>>
     */
    public function bodyParameters(): array
    {
        return [
            'accountNumber' => [
                'type' => 'string',
                'required' => true,
                'example' => '3434343434'
            ],
            'bankCode' => [
                'type' => 'string',
                'required' => true,
                'example' => '035'
            ]
        ];
    }
}
