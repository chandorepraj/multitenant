<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule; // Add this line


class InvitationStoreRequest extends FormRequest
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
        'email' => [
            'required',
            'email',
            Rule::unique('invitations')
                ->where(fn ($query) =>
                    $query->where(
                        'tenant_id',
                        auth()->user()->tenant_id
                    )
                ),
        ],

        'role' => [
            'required',
            'in:Admin,Manager,Sales,Support',
        ],
    ];

    }
}
