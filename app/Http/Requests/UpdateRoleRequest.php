<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRoleRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $roleId = $this->route('role') ? $this->route('role')->id : null;

        return [
            'name' => ['required', 'string', Rule::unique('roles', 'name')->ignore($roleId)],
            'permissions' => 'array'
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'O nome do cargo é obrigatório.',
            'name.string' => 'O nome deve ser um texto válido.',
            'name.unique' => 'Já existe um cargo cadastrado com este nome.',
            
            'permissions.array' => 'As permissões devem ser enviadas em formato de lista.',
        ];
    }
}
