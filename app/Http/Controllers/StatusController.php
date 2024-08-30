<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Status;
use Illuminate\Support\Facades\Validator;

class StatusController extends Controller
{
    public function getAll() {
        $array = ['error' => ''];

        try {
            // Obtém todos os registros da tabela raca
            $status = Status::all();

            // Armazena os registros no array de resposta
            $array['status'] = $status;
        } catch (\Exception $e) {
            // Captura e exibe o erro se algo der errado
            $array['error'] = $e->getMessage();
        }

        return response()->json($array);
    }


    public function insert(Request $request) {
        $array = ['error' => ''];

        // Validação dos dados de entrada
        $validator = Validator::make($request->all(), [
            'nome' => 'required|string|max:255'
        ]);

        if ($validator->fails()) {
            $array['error'] = $validator->errors()->first();
            return $array;
        }

        try {
            // Criação de um novo registro
            $nome = $request->input('nome');

            $newStatus = new Status();
            $newStatus->nome = $nome;
            $newStatus->save();

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
            'nome' => 'required|string|max:255'
        ]);

        if ($validator->fails()) {
            $array['error'] = $validator->errors()->first();
            return $array;
        }

        try {
            // Encontra o registro pelo ID
            $status = Status::find($id);

            if (!$status) {
                $array['error'] = 'Registro não encontrado.';
                return $array;
            }

            // Atualiza o registro com os novos dados
            $status->nome = $request->input('nome');
            $status->save();

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
            $status = Status::find($id);

            if (!$status) {
                $array['error'] = 'Registro não encontrado.';
                return $array;
            }

            // Deleta o registro
            $status->delete();

            $array['success'] = 'Registro deletado com sucesso!';
        } catch (\Exception $e) {
            // Captura e exibe o erro se algo der errado
            $array['error'] = 'Ocorreu um erro ao deletar o registro: ' . $e->getMessage();
        }

        return $array;
    }
}
