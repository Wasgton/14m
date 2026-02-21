<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePermissionRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $permissionId = $this->route('permission') ? $this->route('permission')->id : null;

        return [
            'name' => 'required|string|unique:permissions,name,' . $permissionId
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
