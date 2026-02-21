<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'roles' => 'array',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'O nome do usuário é obrigatório.',
            'name.string' => 'O nome deve ser um texto válido.',
            'name.max' => 'O nome não pode ter mais de 255 caracteres.',
            
            'email.required' => 'O e-mail do usuário é obrigatório.',
            'email.string' => 'O e-mail deve ser um texto válido.',
            'email.email' => 'O e-mail fornecido não é válido.',
            'email.max' => 'O e-mail não pode ter mais de 255 caracteres.',
            'email.unique' => 'Este e-mail já está em uso por outro usuário.',
            
            'password.required' => 'A senha é obrigatória para novos usuários.',
            'password.string' => 'A senha deve ser um texto válido.',
            'password.min' => 'A senha deve ter pelo menos 8 caracteres.',
            
            'roles.array' => 'Os cargos devem ser enviados em formato de lista.',
        ];
    }
}
