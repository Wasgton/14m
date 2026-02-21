<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBannerRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'title' => 'sometimes|required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'image' => 'nullable|image|max:5120',
            'button_text' => 'nullable|string|max:255',
            'button_link' => 'nullable|string|max:255',
            'is_active' => 'boolean',
            'order' => 'integer',
        ];
    }

    public function messages()
    {
        return [
            'title.required' => 'O título do banner é obrigatório.',
            'title.string' => 'O título deve ser um texto válido.',
            'title.max' => 'O título não pode ter mais de 255 caracteres.',
            
            'subtitle.string' => 'O subtítulo deve ser um texto válido.',
            'subtitle.max' => 'O subtítulo não pode ter mais de 255 caracteres.',
            
            'image.image' => 'O arquivo enviado deve ser uma imagem válida.',
            'image.max' => 'A imagem não pode ser maior que 5 MB.',
            
            'button_text.string' => 'O texto do botão deve ser um texto válido.',
            'button_text.max' => 'O texto do botão não pode ter mais de 255 caracteres.',
            
            'button_link.string' => 'O link do botão deve ser um texto válido.',
            'button_link.max' => 'O link do botão não pode ter mais de 255 caracteres.',
            
            'is_active.boolean' => 'O status deve ser verdadeiro ou falso.',
            'order.integer' => 'A ordem de exibição deve ser um número inteiro.',
        ];
    }
}
