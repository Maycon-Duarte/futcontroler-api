<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'usuario' => 'required|string',
            'password' => 'required|string|confirmed',
        ]);

        if (User::where('usuario', $request->usuario)->exists()) {
            $credentials = request(['usuario', 'password']);
            if (Auth::attempt($credentials)) {
                // Credenciais válidas
                $user = Auth::user();
                $token = $user->createToken('Futebol-API-Token', $user->tokens[count($user->tokens) - 1]->abilities);

                $response = [
                    'token' => $token,
                ];

                return response($response, 201);
            } else {
                return response([
                    'message' => 'credenciais invalidas'
                ]);
            }
        }

        $user = User::create([
            'name' => $request->name,
            'usuario' => $request->usuario,
            'password' => bcrypt($request->password),
        ]);

        if ($user->id == 1) {
            $token = $user->createToken('Futebol-API-Token', ['api:read', 'api:write']);
        } else {
            $token = $user->createToken('Futebol-API-Token', ['api:read']);
        }

        $response = [
            'token' => $token,
        ];

        return response($response, 201);
    }
}
