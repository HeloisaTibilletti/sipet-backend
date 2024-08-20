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
        ]);
    }

    public function register(Request $request) {
        $array = ['error' => ''];

        $validator = Validator::make($request->all(), [
            'usuario' => 'required|usuario|unique:users,usuario',
            'password' => 'required',
            'password_confirm' => 'required|same:password'

        ]);

        if($validator->fails()) {
            $usuario = $request->input('usuario');
            $password = $request->input('password');

            $hash = password_hash($password, PASSWORD_DEFAULT);

            $newUser = new User();
            $newUser->usuario = $usuario;
            $newUser->password = $password = $hash;
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
            $array['error'] = $validator->error()->first();
            return $array;
        }

        return $array;
    }
}
