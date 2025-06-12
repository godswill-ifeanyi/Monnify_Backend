<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateUserRequest extends FormRequest
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
            'name' => 'required|string',
            'chamberName' => 'required|string',
            'email' => 'required|email|unique:users',
            'nin' => 'required|numeric'
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
            'name' => [
                'type' => 'string',
                'required' => true,
                'example' => 'John Doe'
            ],
            'chamberName' => [
                'type' => 'string',
                'required' => true,
                'example' => 'John Doe & Sons Chambers'
            ],
            'email' => [
                'type' => 'string',
                'required' => true,
                'format' => 'email',
                'example' => 'john.doe@example.com'
            ],
            'nin' => [
                'type' => 'numeric',
                'required' => true,
                'example' => '5767676767'
            ],
        ];
    }
}
