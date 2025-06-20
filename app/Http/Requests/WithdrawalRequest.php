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
}
