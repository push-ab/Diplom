<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreShowingRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'hall_id' => ['required','integer','exists:halls,id'],
            'movie_id' => ['required','integer','exists:movies,id'],
            'start_time' => ['required','date'],
        ];
    }
}
