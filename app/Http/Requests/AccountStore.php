<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AccountStore extends FormRequest
{
    public function authorize()
    {
        // Set to true if all authenticated users can create accounts
        return true;
    }

    public function rules()
    {
        return [
            'account_type' => 'required|string|max:255',
            'account_name' => 'required|string|max:255',
            'account_number' => 'required|string|max:255',
            'branch_name' => 'nullable|string|max:255',
        ];
    }

    public function messages()
    {
        return [
            'account_name.required' => 'Account name is required.',
            'account_number.required' => 'Account number is required.',
        ];
    }
}
