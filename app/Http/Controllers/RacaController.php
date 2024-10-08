<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Raca;

class RacaController extends Controller
{

    public function getAll() {
        $array = ['error' => ''];

        try {
            // Obtém todos os registros da tabela raca
            $racas = Raca::all();

            // Armazena os registros no array de resposta
            $array['racas'] = $racas;
        } catch (\Exception $e) {
            // Captura e exibe o erro se algo der errado
            $array['error'] = $e->getMessage();
        }

        return response()->json($array);
    }


    public function insert(Request $request) {
        $array = ['error' => ''];

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
            return $array;
        }

        try {
            // Criação de um novo registro
            $nome = $request->input('nome');

            $newRaca = new Raca();
            $newRaca->nome = $nome;
            $newRaca->save();

            // Adiciona uma mensagem de sucesso
            $array['success'] = 'Registro inserido com sucesso!';
            $array['data'] = [
                'id' => $newRaca->id,
                'nome' => $newRaca->nome
            ];
        } catch (\Exception $e) {
            // Captura e exibe o erro se algo der errado
            $array['error'] = 'Ocorreu um erro ao inserir o registro: ' . $e->getMessage();
        }

        return response()->json($array);
    }

    public function update($id, Request $request) {
        $array = ['error' => '', 'success' => ''];

        if (auth()->user()->id_funcao != 1) {
            $array['error'] = 'Acesso negado. Voce nao tem permissao para editar registros.';
            return response()->json($array, 403); // Retorna 403 Forbidden
        }

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
            $raca = Raca::find($id);

            if (!$raca) {
                $array['error'] = 'Registro não encontrado.';
                return $array;
            }

            // Atualiza o registro com os novos dados
            $raca->nome = $request->input('nome');
            $raca->save();

            $array['success'] = 'Registro atualizado com sucesso!';
        } catch (\Exception $e) {
            // Captura e exibe o erro se algo der errado
            $array['error'] = 'Ocorreu um erro ao atualizar o registro: ' . $e->getMessage();
        }

        return response()->json($array);
    }

    public function delete($id) {
        $array = ['error' => '', 'success' => ''];

        if (auth()->user()->id_funcao != 1) {
            $array['error'] = 'Acesso negado. Voce nao tem permissao para adicionar registros.';
            return response()->json($array, 403); // Retorna 403 Forbidden
        }
    
        try {
            // Encontra o registro pelo ID
            $raca = Raca::find($id);
    
            if (!$raca) {
                $array['error'] = 'Registro não encontrado.';
                return response()->json($array);
            }
    
            // Deleta o registro
            $raca->delete();
    
            $array['success'] = 'Registro deletado com sucesso!';
        } catch (\Exception $e) {
            // Captura e exibe o erro se algo der errado
            $array['error'] = 'Ocorreu um erro ao deletar o registro: ' . $e->getMessage();
        }
    
        return response()->json($array);
    }
    
    
}
