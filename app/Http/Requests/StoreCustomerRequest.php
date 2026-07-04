<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule; // Add this line


class StoreCustomerRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'email',
                Rule::unique('customers')
                    ->where(fn ($query) =>
                        $query->where(
                            'tenant_id',
                            session('tenant_id')
                        )
                    ),
            ],
            'phone' => ['required', 'string', 'max:20'],
            'company' => ['nullable', 'string', 'max:255']
        ];
    }
}
