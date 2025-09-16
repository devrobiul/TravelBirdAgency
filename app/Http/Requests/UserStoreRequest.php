<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // অনুমোদন
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name'      => ['required', 'string', 'max:255'],
            'email'     => ['nullable', 'email', 'max:255', 'unique:users,email'],
            'phone'     => ['required', 'string', 'max:20', 'unique:users,phone'],
            'password'  => ['required', 'string', 'min:6'],
            'roles'     => ['required', 'array', 'min:1'],
            'roles.*'   => ['string', 'in:admin,staff,editor'],
            'status'    => ['nullable', 'boolean'],
        ];
    }

    /**
     * Custom messages (optional)
     */
    public function messages(): array
    {
        return [
            'roles.required' => 'Please assign at least one role to the user.',
            'roles.*.in'     => 'Invalid role selected.',
        ];
    }
}
