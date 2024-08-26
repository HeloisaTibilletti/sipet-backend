<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cliente;
use Illuminate\Support\Facades\Validator;

class ClienteController extends Controller
{
    public function getAll() {
        $array = ['error' => ''];

        try {
            // Obtém todos os registros da tabela raca
            $cliente = Cliente::all();

            // Armazena os registros no array de resposta
            $array['clientes'] = $cliente;
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
            // Criação de um novo registro
            $nome = $request->input('nome');
            $sobrenome = $request->input('sobrenome');
            $email = $request->input('email');
            $endereco = $request->input('endereco');
            $telefone = $request->input('telefone');

            $newCliente = new Cliente();
            $newCliente->nome = $nome;
            $newCliente->sobrenome = $sobrenome;
            $newCliente->email = $email;
            $newCliente->endereco = $endereco;
            $newCliente->telefone = $telefone;
            $newCliente->save();

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
            $cliente = Cliente::find($id);

            if (!$cliente) {
                $array['error'] = 'Registro não encontrado.';
                return $array;
            }

            // Atualiza o registro com os novos dados
            $cliente->nome = $request->input('nome');
            $cliente = $request->input('sobrenome');
            $cliente = $request->input('email');
            $cliente = $request->input('endereco');
            $cliente = $request->input('telefone');
            $cliente->save();

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
            $cliente = Cliente::find($id);

            if (!$cliente) {
                $array['error'] = 'Registro não encontrado.';
                return $array;
            }

            // Deleta o registro
            $cliente->delete();

            $array['success'] = 'Registro deletado com sucesso!';
        } catch (\Exception $e) {
            // Captura e exibe o erro se algo der errado
            $array['error'] = 'Ocorreu um erro ao deletar o registro: ' . $e->getMessage();
        }

        return $array;
    }
}
