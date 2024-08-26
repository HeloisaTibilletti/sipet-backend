<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Funcionario;
use Illuminate\Support\Facades\Validator;

class FuncionarioController extends Controller
{
    public function getAll() {
        $array = ['error' => ''];

        try {
            // Obtém todos os registros da tabela
            $funcionario = Funcionario::all();

            // Armazena os registros no array de resposta
            $array['clientes'] = $funcionario;
        } catch (\Exception $e) {
            // Captura e exibe o erro se algo der errado
            $array['error'] = $e->getMessage();
        }

        return $array;
    }


    public function insert(Request $request) {
        $array = ['error' => ''];

        // Validação dos dados de entrada
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:8',
            'nome' => 'required|string|max:255',
            'sobrenome' => 'required|string|max:255',
            'telefone' => 'required|digits_between:8,15',
            'funcao' => 'required|string|max:255',
            'data_nasc' => 'required|date'
        ]);

        if ($validator->fails()) {
            $array['error'] = $validator->errors()->first();
            return $array;
        }

        try {
            // Criação de um novo registro
            $nome = $request->input('nome');
            $sobrenome = $request->input('sobrenome');
            $email = $request->input('email');
            $endereco = $request->input('endereco');
            $telefone = $request->input('telefone');
            $funcao = $request->input('funcao');
            $data_nasc = $request->input('data_nasc');
            

            $newFuncionario = new Funcionario();
            $newFuncionario->nome = $nome;
            $newFuncionario->sobrenome = $sobrenome;
            $newFuncionario->email = $email;
            $newFuncionario->endereco = $endereco;
            $newFuncionario->telefone = $telefone;
            $newFuncionario->funcao = $funcao;
            $newFuncionario->data_nasc = $data_nasc;
            $newFuncionario->save();

            // Adiciona uma mensagem de sucesso
            $array['success'] = 'Registro inserido com sucesso!';
        } catch (\Exception $e) {
            // Captura e exibe o erro se algo der errado
            $array['error'] = 'Ocorreu um erro ao inserir o registro: ' . $e->getMessage();
        }

        return $array;
    }

    public function update($id, Request $request) {
        $array = ['error' => '', 'success' => ''];

        // Validação dos dados de entrada
        $validator = Validator::make($request->all(), [
            'nome' => 'required|string|max:255',
            'sobrenome' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:clientes,email',
            'endereco' => 'required|string|max:255',
            'telefone' => 'required|digits_between:8,15'
        ]);

        if ($validator->fails()) {
            $array['error'] = $validator->errors()->first();
            return $array;
        }

        try {
            // Encontra o registro pelo ID
            $funcionario = Funcionario::find($id);

            if (!$funcionario) {
                $array['error'] = 'Registro não encontrado.';
                return $array;
            }

            // Atualiza o registro com os novos dados
            $funcionario = $request->input('nome');
            $funcionario = $request->input('sobrenome');
            $funcionario = $request->input('email');
            $funcionario = $request->input('endereco');
            $funcionario = $request->input('telefone');
            $funcionario = $request->input('funcao');
            $funcionario = $request->input('data_nasc');
            $funcionario->save();

            $array['success'] = 'Registro atualizado com sucesso!';
        } catch (\Exception $e) {
            // Captura e exibe o erro se algo der errado
            $array['error'] = 'Ocorreu um erro ao atualizar o registro: ' . $e->getMessage();
        }
    }

    public function delete($id) {
        $array = ['error' => '', 'success' => ''];

        try {
            // Encontra o registro pelo ID
            $funcionario = Funcionario::find($id);

            if (!$funcionario) {
                $array['error'] = 'Registro não encontrado.';
                return $array;
            }

            // Deleta o registro
            $funcionario->delete();

            $array['success'] = 'Registro deletado com sucesso!';
        } catch (\Exception $e) {
            // Captura e exibe o erro se algo der errado
            $array['error'] = 'Ocorreu um erro ao deletar o registro: ' . $e->getMessage();
        }

        return $array;
    }
}
