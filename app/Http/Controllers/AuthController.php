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
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:8',
            'password_confirm' => 'required|same:password',
            'nome' => 'required|string|max:255',
            'sobrenome' => 'required|string|max:255',
            'telefone' => 'required|digits_between:8,15',
            'funcao' => 'required|exists:funcao,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()->first()
            ], 400);
        }

        $nome = $request->input('nome');
        $sobrenome = $request->input('sobrenome');
        $telefone = $request->input('telefone');
        $email = $request->input('email');
        $password = $request->input('password');
        $funcao = $request->input('funcao');
        
        $hash = bcrypt($password);
        
        $newUser = new User();
        $newUser->nome = $nome;
        $newUser->sobrenome = $sobrenome;
        $newUser->telefone = $telefone;
        $newUser->email = $email;
        $newUser->password = $hash;
        $newUser->id_funcao = $funcao;
        $newUser->save();

        $token = auth()->attempt([
            'email' => $email,
            'password' => $password
        ]);

        if (!$token) {
            return response()->json([
                'error' => 'Ocorreu um erro interno.'
            ], 500);
        }

        return response()->json([
            'token' => $token,
            'user' => auth()->user()
        ], 201);
    }

   public function login(Request $request) {
    // Valida os dados de entrada
    $validator = Validator::make($request->all(), [
        'email' => 'required|string',
        'password' => 'required'
    ]);

    if ($validator->fails()) {
        return response()->json(['error' => $validator->errors()->first()], 400);
    }

    // Tenta autenticar o usuário
    $credentials = $request->only('email', 'password');
    $token = auth()->attempt($credentials);

    if (!$token) {
        return response()->json(['error' => 'Email e/ou senha estão incorretos.'], 401);
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