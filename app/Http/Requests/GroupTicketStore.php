<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GroupTicketStore extends FormRequest
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
            'airline_id' => 'required',
            'group_qty' => 'required|numeric',
            'purchase_price' => 'required',
            'depart_date' => 'required',
            'group_single_price' => 'required',
            'ticket_pnr' => 'required|unique:products,ticket_pnr',
        ];
    }
}
