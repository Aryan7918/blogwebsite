<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PostRequest extends FormRequest
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
        switch ($this->method()) {
            case 'POST':
                return [
                    'title' => 'required|min:5|max:255',
                    'category' => 'required|array',
                    'body' => 'required|min:10|'
                ];
            case 'PUT':
                return [
                    'title' => 'required|min:5|max:255',
                    'body' => 'required|min:10',
                    'category' => 'required|array',
                    'status' => 'required'
                ];
        }
    }

    public function messages()
    {
        return [
            'category.required' => 'The Category field is required',
        ];
    }
}
