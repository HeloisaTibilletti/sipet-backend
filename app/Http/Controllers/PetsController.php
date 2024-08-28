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

        return $array;
    }


    public function insert(Request $request) {
        $array = ['error' => ''];

        // Validação dos dados de entrada
        $validator = Validator::make($request->all(), [
            'nome' => 'required|string|max:255',
            'data_nasc' => 'required|date',
            'raca_id' => 'required|exists:racas,id',
            'sexo' => 'required|string|in:M,F', // M para macho, F para fêmea (ajuste conforme necessário)
            'especie' => 'required|string|max:255',
            'porte' => 'required|string|max:255', // Pode ser 'Pequeno', 'Médio', 'Grande', etc.
            'condicoes_fisicas' => 'required|string|max:255',
            'tratamentos_especiais' => 'nullable|string|max:255',
            'cliente_id' => 'required|exists:clientes,id' // Verifica se o cliente_id existe na tabela clientes
                ]);

            if ($validator->fails()) {
                    return response()->json(['error' => $validator->errors()->first()], 400);
                }

                try {
                    // Criação de um novo registro
                    $nome = $request->input('nome');
                    $dataNasc = $request->input('data_nasc');
                    $raca_id = $request->input('raca_id'); // Assumindo que `raca_id` é a chave estrangeira
                    $sexo = $request->input('sexo');
                    $especie = $request->input('especie');
                    $porte = $request->input('porte');
                    $condicoesFisicas = $request->input('condicoes_fisicas');
                    $tratamentosEspeciais = $request->input('tratamentos_especiais');
                    $clienteId = $request->input('cliente_id'); // Chave estrangeira para o cliente
                
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
            'raca' => 'required|exists:racas,id',
            'sexo' => 'required|string|in:M,F', // M para macho, F para fêmea (ajuste conforme necessário)
            'especie' => 'required|string|max:255',
            'porte' => 'required|string|max:255', // Pode ser 'Pequeno', 'Médio', 'Grande', etc.
            'condicoes_fisicas' => 'required|string|max:255',
            'tratamentos_especiais' => 'nullable|string|max:255',
            'cliente_id' => 'required|exists:clientes,id' // Verifica se o cliente_id existe na tabela clientes
        ]);

        if ($validator->fails()) {
            $array['error'] = $validator->errors()->first();
            return $array;
        }

        try {
            // Encontra o registro pelo ID
            $pets = Pets::find($id);

            if (!$pets) {
                $array['error'] = 'Registro não encontrado.';
                return $array;
            }

           // Atualiza os campos do pet com os dados recebidos na requisição
            $pets->nome = $request->input('nome');
            $pets->data_nasc = $request->input('data_nasc');
            $pets->raca_id = $request->input('raca_id'); // Atualiza a chave estrangeira da raça
            $pets->sexo = $request->input('sexo');
            $pets->especie = $request->input('especie');
            $pets->porte = $request->input('porte');
            $pets->condicoes_fisicas = $request->input('condicoes_fisicas');
            $pets->tratamentos_especiais = $request->input('tratamentos_especiais');
            $pets->cliente_id = $request->input('cliente_id'); // Atualiza a chave estrangeira do cliente

            // Salva as alterações no banco de dados
            $pets->save();

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
            $pets = Pets::find($id);

            if (!$pets) {
                $array['error'] = 'Registro não encontrado.';
                return $array;
            }

            // Deleta o registro
            $pets->delete();

            $array['success'] = 'Registro deletado com sucesso!';
        } catch (\Exception $e) {
            // Captura e exibe o erro se algo der errado
            $array['error'] = 'Ocorreu um erro ao deletar o registro: ' . $e->getMessage();
        }

        return $array;
    }
}
