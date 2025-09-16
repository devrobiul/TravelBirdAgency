<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PassportStore extends FormRequest
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
            'tracking_id' => 'required|unique:products,tracking_id',
            'sale_customer_id' => 'required',
            'sale_date' => 'required',
            'purchase_price' => 'required|numeric',
            'passport_type' => 'required',
            'delivery_date' => 'required',
            'dath_of_birth' => 'required',
            'g_pax_name.*' => 'required|string',
            'g_pax_type*' => 'required|string',
            'sale_price' => 'required|numeric',
            'purchase_account_id' => 'required',
            'purchase_tnxid' => 'required',
        ];
    }
}
