<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreMovieRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'title' => ['required','string','max:180'],
            'duration_minutes' => ['required','integer','min:1','max:600'],
            'description' => ['nullable','string'],
            'age_rating' => ['nullable','string','max:10'],
            'poster' => ['nullable','image','mimes:jpg,jpeg,png,webp','max:4096'], // 4MB
        ];
    }
}
