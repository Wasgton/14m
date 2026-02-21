<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateEventRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'sometimes|required|string|max:255',
            'date' => 'sometimes|required|date',
            'location' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|required|string',
            'price' => 'nullable|integer|min:0',
            'status' => 'sometimes|required|in:upcoming,sold-out,past',
            'ticket_link' => 'nullable|url|max:255',
            'lineup' => 'nullable|array',
            'lineup.*.display_order' => 'required|integer|min:1',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'O nome do evento é obrigatório.',
            'name.string' => 'O nome do evento deve ser um texto.',
            'name.max' => 'O nome não pode ter mais de 255 caracteres.',
            
            'date.required' => 'A data do evento é obrigatória.',
            'date.date' => 'Forneça uma data e hora válidas.',
            
            'location.required' => 'O local do evento é obrigatório.',
            'location.string' => 'O local deve ser um texto válido.',
            'location.max' => 'O local não pode ter mais de 255 caracteres.',
            
            'description.required' => 'A descrição do evento é obrigatória.',
            'description.string' => 'A descrição deve ser um texto.',
            
            'price.integer' => 'O preço deve ser um valor inteiro representando centavos.',
            'price.min' => 'O preço não pode ser negativo.',
            
            'status.required' => 'O status do evento é obrigatório.',
            'status.in' => 'O status selecionado é inválido.',
            
            'ticket_link.url' => 'O link dos ingressos deve ser uma URL válida (Ex: https://...).',
            'ticket_link.max' => 'O link dos ingressos não pode ter mais de 255 caracteres.',
        ];
    }
}
