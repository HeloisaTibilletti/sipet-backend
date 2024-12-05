<?php

namespace App\Http\Controllers;

use App\Models\Agendamento;
use App\Models\Produto;
use Illuminate\Http\Request;
use Validator;

class AgendamentoController extends Controller
{
    public function getAll()
    {
        $array = ['error' => ''];

        try {
            // Inclui os dados completos de cliente e pet
            $agendamentos = Agendamento::with(['cliente', 'pet'])->get();

            $array['agendamentos'] = $agendamentos;  // Retorna todos os dados, incluindo cliente e pet
        } catch (\Exception $e) {
            $array['error'] = $e->getMessage();
        }

        return response()->json($array);
    }


    public function insert(Request $request)
{
    $array = ['error' => ''];

    // Validação dos dados
    $validator = Validator::make($request->all(), [
        'id_cliente' => 'required|exists:clientes,id',
        'id_pet' => 'required|exists:pets,id',
        'id_user' => 'required|exists:users,id',
        'id_status' => 'required|exists:status,id',
        'id_produto' => 'required|array', // Deve ser um array
        'id_produto.*.id' => 'required|exists:produtos,id', // O ID do produto deve existir
        'id_produto.*.valor' => 'required|numeric|min:0', // O valor do produto deve ser numérico
        'data_reserva' => 'required|date',
        'horario_reserva' => 'required|date_format:H:i',
        'observacoes' => 'nullable|string|max:255',
        'transporte' => 'required|boolean',
    ]);

    if ($validator->fails()) {
        $array['error'] = $validator->errors()->first();
        return response()->json($array);
    }

    try {
        // Criar um novo agendamento
        $newAgendamento = new Agendamento();
        $newAgendamento->id_cliente = $request->input('id_cliente');
        $newAgendamento->id_pet = $request->input('id_pet');
        $newAgendamento->id_user = $request->input('id_user');
        $newAgendamento->id_status = $request->input('id_status');
        $newAgendamento->data_reserva = $request->input('data_reserva');
        $newAgendamento->horario_reserva = $request->input('horario_reserva');
        $newAgendamento->observacoes = $request->input('observacoes');
        $newAgendamento->transporte = $request->boolean('transporte');

        // Calcular valor total dos produtos
        $produtos = $request->input('id_produto');
        $valorTotal = array_sum(array_column($produtos, 'valor')); // Soma os valores enviados

        // Adicionar taxa de transporte, se necessário
        if ($newAgendamento->transporte) {
            $valorTotal += 15;
        }

        $newAgendamento->valor_total = $valorTotal;

        // Salvar o agendamento
        $newAgendamento->save();

        // Associar os produtos ao agendamento com os valores individuais
        $produtosAttach = [];
        foreach ($produtos as $produto) {
            $produtosAttach[$produto['id']] = ['valor' => $produto['valor']];
        }

        $newAgendamento->produtos()->attach($produtosAttach);

        $array['success'] = 'Agendamento criado com sucesso!';
    } catch (\Exception $e) {
        \Log::error('Erro ao criar agendamento:', ['message' => $e->getMessage()]);
        $array['error'] = 'Erro ao criar agendamento: ' . $e->getMessage();
    }

    return response()->json($array);
}


        public function cancel($id)
    {
        $array = ['error' => '', 'success' => ''];

        try {
            // Encontrar o agendamento pelo ID
            $agendamento = Agendamento::find($id);

            if (!$agendamento) {
                $array['error'] = 'Agendamento não encontrado.';
                return $array;
            }

            // Verificar se o agendamento já foi cancelado
            if ($agendamento->status === 'Cancelado') {
                $array['error'] = 'O agendamento já está cancelado.';
                return $array;
            }

            // Alterar o status para 'cancelado'
            $agendamento->status = 'Cancelado';
            $agendamento->save();

            // Adiciona uma mensagem de sucesso
            $array['success'] = 'Agendamento cancelado com sucesso!';
        } catch (\Exception $e) {
            // Captura e exibe o erro se algo der errado
            $array['error'] = 'Ocorreu um erro ao cancelar o agendamento: ' . $e->getMessage();
        }

        return $array;
    }

    public function update(Request $request, $id)
    {
        $agendamento = Agendamento::find($id);
        if (!$agendamento) {
            return response()->json(['error' => 'Agendamento não encontrado'], 404);
        }

        $agendamento->id_status = $request->id_status; // Atualiza o status
        $agendamento->save();

        return response()->json(['success' => true, 'agendamento' => $agendamento]);
    }

    public function getProdutos($agendamento_id)
    {
        // Recuperar o agendamento com seus produtos relacionados
        $agendamento = Agendamento::with('produtos')->find($agendamento_id);

        // Retornar os produtos em formato JSON
        return response()->json($agendamento->produtos);
    }



}
