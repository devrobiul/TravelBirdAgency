<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ManpowerRequest extends FormRequest
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
            'delivery_date' => 'required',
            'visit_country' => 'required',
            'tracking_id' => 'required',
            'g_pax_name.*' => 'required|string',
            'g_pax_mobile_no.*' => 'required|numeric',
            'sale_price' => 'required|numeric',
            'purchase_vendor_id' => 'required',
            'sale_customer_id' => 'required',
            'sale_date' => 'required|date',
            'purchase_price' => 'required|numeric',
        ];
    }
}
