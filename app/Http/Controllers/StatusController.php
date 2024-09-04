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
    
        // Verifique a função do usuário
        if (auth()->user()->id_funcao != 1) {
            $array['error'] = 'Acesso negado. Voce nao tem permissao para adicionar registros.';
            return response()->json($array, 403); // Retorna 403 Forbidden
        }
    
        // Validação dos dados de entrada
        $validator = Validator::make($request->all(), [
            'nome' => 'required|string|max:255'
        ]);
    
        if ($validator->fails()) {
            $array['error'] = $validator->errors()->first();
            return response()->json($array);
        }
    
        try {
            // Criação de um novo registro
            $nome = $request->input('nome');
    
            $newStatus = new Status();
            $newStatus->nome = $nome;
            $newStatus->save();
    
            // Adiciona uma mensagem de sucesso
            $array['success'] = 'Registro inserido com sucesso!';
            $array['data'] = [
                'id' => $newStatus->id,
                'nome' => $newStatus->nome
            ];
        } catch (\Exception $e) {
            // Captura e exibe o erro se algo der errado
            $array['error'] = 'Ocorreu um erro ao inserir o registro: ' . $e->getMessage();
        }
    
        return response()->json($array);
    }
    
    public function update(Request $request, $id) {
        $array = ['error' => ''];

        if (auth()->user()->id_funcao != 1) {
            $array['error'] = 'Acesso negado. Voce nao tem permissao para editar registros.';
            return response()->json($array, 403); // Retorna 403 Forbidden
        }
    
        // Validação dos dados de entrada
        $validator = Validator::make($request->all(), [
            'nome' => 'required|string|max:255',
        ]);
    
        if ($validator->fails()) {
            $array['error'] = $validator->errors()->first();
            return response()->json($array);
        }
    
        try {
            // Atualização do registro
            $status = Status::find($id);
    
            if (!$status) {
                $array['error'] = 'Status não encontrado.';
                return response()->json($array);
            }
    
            $status->nome = $request->input('nome');
            $status->save();
    
            $array['success'] = 'Registro atualizado com sucesso!';
            $array['data'] = [
                'id' => $status->id,
                'nome' => $status->nome
            ];
        } catch (\Exception $e) {
            $array['error'] = 'Ocorreu um erro ao atualizar o registro: ' . $e->getMessage();
        }
    
        return response()->json($array);
    }
    

    public function delete($id) {
        $array = ['error' => '', 'success' => ''];

        if (auth()->user()->id_funcao != 1) {
            $array['error'] = 'Acesso Negado. Voce nao tem permissao para excluir registros.';
            return response()->json($array, 403); // Retorna 403 Forbidden
        }

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

        return response()->json($array);
    }
}
