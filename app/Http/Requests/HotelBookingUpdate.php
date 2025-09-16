<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class HotelBookingUpdate extends FormRequest
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
            'visit_country' => 'required',
            'hotel_name' => 'required',
            'hotel_number_of_day' => 'required',
            'hotel_location' => 'required',
            'hotel_purchase_email' => 'required',
            'hotel_refer' => 'required',
            'g_pax_name.*' => 'required|string',
            'g_pax_mobile_no.*' => 'required|numeric',
            'sale_price' => 'required|numeric',
            'sale_customer_id' => 'required',
            'sale_date' => 'required|date',
            'purchase_price' => 'required|numeric',
        ];
    }
}
