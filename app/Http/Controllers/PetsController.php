<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pets;
use Illuminate\Support\Facades\Validator;

class PetsController extends Controller
{
    public function getAll() {
        $array = ['error' => ''];

        try {
            // Obtém todos os registros da tabela 
            $pets = Pets::all();

            // Armazena os registros no array de resposta
            $array['pets'] = $pets;
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
            'data_nasc' => 'required|date',
            'raca_id' => 'required|exists:racas,id',
            'sexo' => 'required|string|in:M,F', 
            'especie' => 'required|string|max:255',
            'porte' => 'required|string|max:255', 
            'condicoes_fisicas' => 'required|string|max:255',
            'tratamentos_especiais' => 'nullable|string|max:255',
            'cliente_id' => 'required|exists:clientes,id' 
                ]);

            if ($validator->fails()) {
                    return response()->json(['error' => $validator->errors()->first()], 400);
                }

                try {
                    // Criação de um novo registro
                    $nome = $request->input('nome');
                    $dataNasc = $request->input('data_nasc');
                    $raca_id = $request->input('raca_id'); 
                    $sexo = $request->input('sexo');
                    $especie = $request->input('especie');
                    $porte = $request->input('porte');
                    $condicoesFisicas = $request->input('condicoes_fisicas');
                    $tratamentosEspeciais = $request->input('tratamentos_especiais');
                    $clienteId = $request->input('cliente_id'); 
                
                    $newPet = new Pets();
                    $newPet->nome = $nome;
                    $newPet->data_nasc = $dataNasc;
                    $newPet->raca_id = $raca_id;
                    $newPet->sexo = $sexo;
                    $newPet->especie = $especie;
                    $newPet->porte = $porte;
                    $newPet->condicoes_fisicas = $condicoesFisicas;
                    $newPet->tratamentos_especiais = $tratamentosEspeciais;
                    $newPet->cliente_id = $clienteId;
                    $newPet->save();
                
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
            'data_nasc' => 'required|date',
            'raca_id' => 'required|exists:racas,id',  // Certifique-se de que o campo correto esteja sendo enviado
            'sexo' => 'required|string|in:M,F',
            'especie' => 'required|string|max:255',
            'porte' => 'required|string|max:255',
            'condicoes_fisicas' => 'required|string|max:255',
            'tratamentos_especiais' => 'nullable|string|max:255',
            'cliente_id' => 'required|exists:clientes,id',
        ]);
    
        if ($validator->fails()) {
            $array['error'] = $validator->errors()->first();
            return $array;
        }
    
        try {
            $pet = Pets::find($id);
            if (!$pet) {
                $array['error'] = 'Pet não encontrado.';
                return response()->json($array, 404);  // Retorna erro 404 se o pet não for encontrado
            }
    
            // Atualizando os dados do pet
            $pet->update([
                'nome' => $request->input('nome'),
                'data_nasc' => $request->input('data_nasc'),
                'raca_id' => $request->input('raca_id'),
                'sexo' => $request->input('sexo'),
                'especie' => $request->input('especie'),
                'porte' => $request->input('porte'),
                'condicoes_fisicas' => $request->input('condicoes_fisicas'),
                'tratamentos_especiais' => $request->input('tratamentos_especiais'),
                'cliente_id' => $request->input('cliente_id'),
            ]);
    
            $array['success'] = 'Pet atualizado com sucesso!';
            return response()->json($array, 200);  // Resposta de sucesso
    
        } catch (\Exception $e) {
            $array['error'] = 'Erro ao atualizar: ' . $e->getMessage();
            return response()->json($array, 500);  // Erro 500 em caso de falha
        }
    }
    
    

    public function delete($id) {
        try {
            $pet = Pets::find($id);
    
            if (!$pet) {
                return response()->json(['error' => 'Registro não encontrado.'], 404);
            }
    
            $pet->delete();

            return response()->json(['success' => 'Registro deletado com sucesso!'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Ocorreu um erro ao deletar o registro: ' . $e->getMessage()], 500);
        }
    }
    
}
