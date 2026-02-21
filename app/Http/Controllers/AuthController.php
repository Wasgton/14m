<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Handle Login Attempt via SPA stateful cookie (Sanctum)
     */
    public function login(LoginRequest $request)
    {
        $credentials = $request->validated();

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            $user = Auth::user();
            
            // Eager load roles and permissions so the frontend has them
            $user->load('roles', 'permissions');

            // Optionally map all permissions into a flat array for the frontend
            $permissions = $user->getAllPermissions()->pluck('name');

            return response()->json([
                'user' => $user,
                'permissions' => $permissions
            ], 200);
        }

        return response()->json([
            'message' => 'The provided credentials do not match our records.'
        ], 401);
    }

    /**
     * Handle Logout Attempt
     */
    public function logout(Request $request)
    {
        Auth::guard('web')->logout();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json(['message' => 'Logged out intentionally.'], 200);
    }

    /**
     * Fetch Current Authenticated User Data
     */
    public function me(Request $request)
    {
        $user = $request->user();
        $user->load('roles', 'permissions');
        $permissions = $user->getAllPermissions()->pluck('name');
        
        return response()->json([
            'user' => $user,
            'permissions' => $permissions
        ]);
    }
}
