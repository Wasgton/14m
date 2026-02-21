<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePermissionRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required|string|unique:permissions,name'
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'A chave da permissão é obrigatória.',
            'name.string' => 'A chave deve ser um texto válido.',
            'name.unique' => 'Já existe uma permissão com esta chave cadastrada.',
        ];
    }
}
