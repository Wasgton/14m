<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePartnerRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'sometimes|required|string|max:255',
            'logo' => 'nullable|image|max:5120',
            'is_active' => 'boolean',
            'order' => 'integer',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'O nome do parceiro é obrigatório.',
            'name.string' => 'O nome deve ser um texto válido.',
            'name.max' => 'O nome não pode ter mais de 255 caracteres.',
            
            'logo.image' => 'O arquivo enviado deve ser uma imagem válida.',
            'logo.max' => 'A logo não pode ser maior que 5 MB.',
            
            'is_active.boolean' => 'O status deve ser verdadeiro ou falso.',
            'order.integer' => 'A ordem de exibição deve ser um número inteiro.',
        ];
    }
}
