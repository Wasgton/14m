<?php

namespace App\Http\Controllers;

use App\Mail\ContactMessage;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function send(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'message' => 'required|string|max:5000',
        ]);

        // Attempt to find a registered email in settings, fallback to a default if not fully configured.
        $contactEmail = Setting::where('key', 'email')->value('value') 
                        ?? Setting::where('key', 'contact_email')->value('value') 
                        ?? 'contato@14mproducoes.com.br';

        try {
            Mail::to($contactEmail)->send(new ContactMessage(
                $validated['name'],
                $validated['email'],
                $validated['message']
            ));

            return response()->json(['message' => 'Mensagem enviada com sucesso!'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Erro ao enviar mensagem.', 'error' => $e->getMessage()], 500);
        }
    }
}
