<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateArtistRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'sometimes|required|string|max:255',
            'genre' => 'nullable|string|max:255',
            'image' => 'nullable|image|max:5120',
            'instagram_url' => 'nullable|string|max:255',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'O campo nome do artista é obrigatório.',
            'name.string' => 'O nome deve ser um texto válido.',
            'name.max' => 'O nome não pode ter mais de 255 caracteres.',
            
            'genre.string' => 'O gênero musical deve ser um texto válido.',
            'genre.max' => 'O gênero musical não pode ter mais de 255 caracteres.',
            
            'image.image' => 'O arquivo enviado deve ser uma imagem válida.',
            'image.max' => 'A imagem não pode ser maior que 5 MB.',
            
            'instagram_url.string' => 'O Instagram deve ser um texto válido (Ex: @usuario ou https://...).',
        ];
    }
}
