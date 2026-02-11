<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreHallRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'title' => ['required','string','max:120'],
            'rows'  => ['required','integer','min:1','max:30'],
            'cols'  => ['required','integer','min:1','max:30'],
            'is_active' => ['sometimes','boolean'],
        ];
    }
}
