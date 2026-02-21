<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    /**
     * Retorna a lista de chaves e valores.
     */
    public function index()
    {
        return response()->json(Setting::pluck('value', 'key'));
    }

    /**
     * Salva ou atualiza as configurações em lote.
     */
    public function store(Request $request)
    {
        // Verifica as permissões
        if (!auth()->user() || !auth()->user()->can('settings.edit')) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        // Pega todos os campos que não sejam o arquivo de logo
        $data = $request->except(['_method', 'logo']);

        foreach ($data as $key => $value) {
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value ?? '']
            );
        }

        if ($request->hasFile('logo')) {
            $request->validate(['logo' => 'image|max:5120']);
            $path = '/storage/' . $request->file('logo')->store('settings', 'public');
            Setting::updateOrCreate(
                ['key' => 'logo'],
                ['value' => $path]
            );
        }

        return response()->json(Setting::pluck('value', 'key'), 200);
    }
}
