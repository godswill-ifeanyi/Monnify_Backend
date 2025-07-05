<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class WithdrawalRequest extends FormRequest
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
            'accountRef' => 'required|string',
            'amount' => 'required|numeric|regex:/^\d+(\.\d{1,2})?$/',
            'narration' => 'nullable',
            'destinationBankCode' => 'required|numeric',
            'destinationAccountNumber' => 'required|numeric'
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
            'accountRef' => [
                'type' => 'string',
                'required' => true,
                'example' => 'cliApp68400ed1b4b25'
            ],
            'amount' => [
                'type' => 'float',
                'required' => true,
                'example' => 2000.00
            ],
            'narration' => [
                'required' => false,
                'example' => 'This is a gift money withdrawal'
            ],
            'destinationBankCode' => [
                'type' => 'string',
                'required' => true,
                'example' => '044'
            ],
            'destinationAccountNumber' => [
                'type' => 'string',
                'required' => true,
                'example' => '069157103'
            ],
        ];
    }
}
