<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Funcao;
use Illuminate\Support\Facades\Validator;

class FuncaoController extends Controller
{
    public function getAll() {
        $array = ['error' => ''];

        try {
            // Obtém todos os registros da tabela funcoes
            $funcao = Funcao::all();

            // Armazena os registros no array de resposta
            $array['funcao'] = $funcao;
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
            'nome' => 'required|string|nome|max:255|unique:funcao,nome',
            'observacao' => 'required|string'
        ]);

        if ($validator->fails()) {
            $array['error'] = $validator->errors()->first();
            return $array;
        }

        try {
            // Criação de um novo registro
            $nome = $request->input('nome');
            $observacao = $request->input('observacao');

            $newFuncao  = new Funcao();
            $newFuncao->nome = $nome;
            $newFuncao->observacao = $observacao;
            $newFuncao ->save();

            // Adiciona uma mensagem de sucesso
            $array['success'] = 'Função  inserida com sucesso!';
        } catch (\Exception $e) {
            // Captura e exibe o erro se algo der errado
            $array['error'] = 'Ocorreu um erro ao inserir a função: ' . $e->getMessage();
        }

        return $array;
    }

    public function update($id, Request $request) {
        $array = ['error' => '', 'success' => ''];

        // Validação dos dados de entrada
        $validator = Validator::make($request->all(), [
            'nome' => 'required|string|nome|max:255|unique:funcao,nome',
            'observacao' => 'required|string'
        ]);

        if ($validator->fails()) {
            $array['error'] = $validator->errors()->first();
            return $array;
        }

        try {
            // Encontra a funcao pelo ID
            $funcao = Funcao::find($id);

            if (!$funcao) {
                $array['error'] = 'Função não encontrada.';
                return $array;
            }

            // Atualiza a função com os novos dados
            $funcao->nome = $request->input('nome');
            $funcao->observacao = $request->input('observacao');
            $funcao->save();

            $array['success'] = 'Função atualizado com sucesso!';
        } catch (\Exception $e) {
            // Captura e exibe o erro se algo der errado
            $array['error'] = 'Ocorreu um erro ao atualizar a função: ' . $e->getMessage();
        }
    }

    public function delete($id) {
        $array = ['error' => '', 'success' => ''];

        try {
            // Encontra a função pelo ID
            $funcao = Funcao::find($id);

            if (!$funcao) {
                $array['error'] = 'Função não encontrada.';
                return $array;
            }

            // Deleta a funcao
            $funcao->delete();

            $array['success'] = 'Função deletada com sucesso!';
        } catch (\Exception $e) {
            // Captura e exibe o erro se algo der errado
            $array['error'] = 'Ocorreu um erro ao deletar a função: ' . $e->getMessage();
        }

        return $array;
    }
}

