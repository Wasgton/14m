<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'email' => 'required|email',
            'password' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'email.required' => 'Por favor, informe o seu e-mail.',
            'email.email' => 'Por favor, digite um e-mail válido.',
            'password.required' => 'Por favor, informe a sua senha.',
        ];
    }
}
