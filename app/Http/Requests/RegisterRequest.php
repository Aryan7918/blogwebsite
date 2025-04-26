<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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
            'email' => 'required|email|max:255|unique:users,email',
            'mobile' => 'required',
            'birthdate' => 'required|date',
            'password' => 'required|min:7|max:255|confirmed',
            'password_confirmation' => 'required|min:7|max:255',
        ];
    }
}
