<?php

namespace App\Http\Requests\Client;

use Illuminate\Foundation\Http\FormRequest;

class StoreBookingRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'showing_id' => ['required','integer','exists:showings,id'],
            'seat_ids' => ['required','array','min:1','max:10'],
            'seat_ids.*' => ['required','integer','distinct','exists:seats,id'],
            'customer_email' => ['nullable','email','max:180'],
            'customer_phone' => ['nullable','string','max:40'],
        ];
    }
}
