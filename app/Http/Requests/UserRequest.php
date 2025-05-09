<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
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
            'fname' => 'required|min:2',
            'lname' => 'required|min:2',
            'email' => 'required|email|unique:users',
            'mobile' => 'required|min:10|max:10',
            'birthdate' => 'required|date',
            'password' => 'required|min:6',
        ];
    }
}
