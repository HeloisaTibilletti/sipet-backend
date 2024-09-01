<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Produto;
use Illuminate\Support\Facades\Validator;

class ProdutoController extends Controller
{
    public function getAll() {
        $array = ['error' => ''];

        try {
            // Obtém todos os registros da tabela 
            $produto = Produto::all();

            // Armazena os registros no array de resposta
            $array['produtos'] = $produto;
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
            'nome' => 'required|string|max:255',
            'valor' => 'required|numeric'
        ]);

        if ($validator->fails()) {
            $array['error'] = $validator->errors()->first();
            return $array;
        }

        try {
            // Criação de um novo registro
            $nome = $request->input('nome');
            $valor = (float) $request->input('valor');


            $newProduto = new Produto();
            $newProduto->nome = $nome;
            $newProduto->valor = $valor;
            $newProduto->save();


             $array['success'] = 'Registro inserido com sucesso!';
             $array['data'] = [
                 'id' => $newProduto->id,
                 'nome' => $newProduto->nome,
                 'valor' => $newProduto->valor
             ];
        } catch (\Exception $e) {
            // Captura e exibe o erro se algo der errado
            $array['error'] = 'Ocorreu um erro ao inserir o registro: ' . $e->getMessage();
        }

        return response()->json($array);
    }

    public function update(Request $request, $id) {
        $array = ['error' => ''];
    
        // Validação dos dados de entrada
        $validator = Validator::make($request->all(), [
            'nome' => 'required|string|max:255',
            'valor' => 'required|numeric'
        ]);
    
        if ($validator->fails()) {
            $array['error'] = $validator->errors()->first();
            return response()->json($array);
        }
    
        try {
            // Atualização do registro
            $produto = Produto::find($id);
    
            if (!$produto) {
                $array['error'] = 'Produto não encontrado.';
                return response()->json($array);
            }
    
            $produto->nome = $request->input('nome');
            $produto->valor = $request->input('valor');
            $produto->save();
    
            $array['success'] = 'Registro atualizado com sucesso!';
            $array['data'] = [
                'id' => $produto->id,
                'nome' => $produto->nome,
                'valor' => $produto->valor
            ];
        } catch (\Exception $e) {
            $array['error'] = 'Ocorreu um erro ao atualizar o registro: ' . $e->getMessage();
        }
    
        return response()->json($array);
    }
    
    public function delete($id) {
        $array = ['error' => '', 'success' => ''];

        try {
            // Encontra o registro pelo ID
            $produto = Produto::find($id);

            if (!$produto) {
                $array['error'] = 'Registro não encontrado.';
                return $array;
            }

            // Deleta o registro
            $produto->delete();

            $array['success'] = 'Registro deletado com sucesso!';
        } catch (\Exception $e) {
            // Captura e exibe o erro se algo der errado
            $array['error'] = 'Ocorreu um erro ao deletar o registro: ' . $e->getMessage();
        }

        return $array;
    }
}
