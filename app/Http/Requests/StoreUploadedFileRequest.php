<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUploadedFileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'file' => [
                'required',
                'file',
                'max:51200', // 50MB max
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'file.max' => 'The file size must not exceed 50MB.',
        ];
    }
}
