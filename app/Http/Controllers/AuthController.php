<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use Auth;

use App\Models\User;


class AuthController extends Controller
{
    public function unauthorized() {
        return response()->json([
            'error' => "Não Autorizado"
        ], 401);
    }

    public function register(Request $request) {
        $array = ['error' => ''];

        $validator = Validator::make($request->all(), [
            'usuario' => 'required|string|unique:users,usuario',
            'password' => 'required',
            'password_confirm' => 'required|same:password'

        ]);

        if(!$validator->fails()) {
            $usuario = $request->input('usuario');
            $password = $request->input('password');

            $hash = password_hash($password, PASSWORD_DEFAULT);

            $newUser = new User();
            $newUser->usuario = $usuario;
            $newUser->password = $hash;
            $newUser->save();

            $token = auth()->attempt([
                'usuario' => $usuario,
                'password' => $password
            ]);

            if(!$token) {
                $array['error'] = 'Ocorreu um erro interno.';
                return $array;
            }

            $array['token'] = $token;

            $user = auth()->user();
            $array['user'] = $user;


        } else {
            $array['error'] = $validator->errors()->first();
            return $array;
        }

        return $array;
    }

   public function login(Request $request) {
    // Valida os dados de entrada
    $validator = Validator::make($request->all(), [
        'usuario' => 'required|string',
        'password' => 'required'
    ]);

    if ($validator->fails()) {
        return response()->json(['error' => $validator->errors()->first()], 400);
    }

    // Tenta autenticar o usuário
    $credentials = $request->only('usuario', 'password');
    $token = auth()->attempt($credentials);

    if (!$token) {
        return response()->json(['error' => 'Usuário e/ou senha estão incorretos.'], 401);
    }

    // Retorna o token e as informações do usuário
    return response()->json([
        'token' => $token,
        'user' => auth()->user()
    ]);
    }

    public function validateToken() {
        $array = ['error' => ''];

        $user = auth()->user();
        $array['user'] = $user;

        return $array;
    }

    public function logout() {
        $array = ['error' => ''];
        auth()->logout();

        return $array;
    }

}
