<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use Auth;
use Illuminate\Support\Facades\Log;

use App\Models\User;


class AuthController extends Controller
{
    public function unauthorized()
    {
        return response()->json([
            'error' => "Não Autorizado"
        ], 401);
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:8',
            'password_confirm' => 'required|same:password',
            'nome' => 'required|string|max:255',
            'sobrenome' => 'required|string|max:255',
            'telefone' => 'required|digits_between:8,15',
            'id_funcao' => 'required|exists:funcao,id',
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
        $id_funcao = $request->input('id_funcao');

        $hash = bcrypt($password);

        $newUser = new User();
        $newUser->nome = $nome;
        $newUser->sobrenome = $sobrenome;
        $newUser->telefone = $telefone;
        $newUser->email = $email;
        $newUser->password = $hash;
        $newUser->id_funcao = $id_funcao;
        $newUser->save();

        // Exemplo em Laravel
        Log::info('Dados retornados:', ['dados' => $newUser]);


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

    public function login(Request $request)
    {
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

    public function validateToken()
    {
        $array = ['error' => ''];

        $user = auth()->user();
        $array['user'] = $user;

        return $array;
    }

    public function logout()
    {
        $array = ['error' => ''];
        auth()->logout();

        return $array;
    }

    public function index()
    {
        // Busca todos os usuários
        $users = User::all();

        // Retorna os usuários em formato JSON
        return response()->json([
            'users' => $users
        ], 200);
    }

    public function delete($id)
    {
        $array = ['error' => '', 'success' => ''];

        if (auth()->user()->id_funcao != 1) {
            $array['error'] = 'Acesso negado. Voce nao tem permissao para excluir registros.';
            return response()->json($array, 403); // Retorna 403 Forbidden
        }

        try {
            // Encontra o registro pelo ID
            $user = User::find($id);

            if (!$user) {
                $array['error'] = 'Registro não encontrado.';
                return response()->json($array, 404); // Retorna 404 se o usuário não for encontrado
            }

            // Deleta o registro
            $user->delete();

            $array['success'] = 'Registro deletado com sucesso!';
        } catch (\Exception $e) {
            // Captura e exibe o erro se algo der errado
            $array['error'] = 'Ocorreu um erro ao deletar o registro: ' . $e->getMessage();
        }

        return response()->json($array);
    }
}