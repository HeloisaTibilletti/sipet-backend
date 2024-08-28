<?php

namespace App\Http\Controllers;

use App\Models\Agendamento;
use App\Models\Produto;
use Illuminate\Http\Request;
use Validator;

class AgendamentoController extends Controller
{
    public function getAll() {
        $array = ['error' => ''];

        try {
            // Obtém todos os registros da tabela raca
            $agendamento = Agendamento::all();

            // Armazena os registros no array de resposta
            $array['agendamentos'] = $agendamento;
        } catch (\Exception $e) {
            // Captura e exibe o erro se algo der errado
            $array['error'] = $e->getMessage();
        }

        return $array;
    }


    public function insert(Request $request) {
        $array = ['error' => ''];

    // Validação dos dados
    $validator = Validator::make($request->all(), [
        'id_cliente' => 'required|exists:clientes,id',
        'id_pet' => 'required|exists:pets,id',
        'id_raca' => 'required|exists:racas,id',
        'id_user' => 'required|exists:users,id',
        'id_status' => 'required|exists:status,id',
        'id_produto' => 'required|array',
        'id_produto.*' => 'exists:produtos,id',
        'data_reserva' => 'required|date',
        'horario_reserva' => 'required|date_format:H:i',
        'observacoes' => 'nullable|string|max:255',
        'transporte' => 'required|boolean',
    ]);

    if ($validator->fails()) {
        $array['error'] = $validator->errors()->first();
        return $array;
    }

    try {

        // Verificar o número total de agendamentos do dia atual
        $hoje = now()->startOfDay(); // Início do dia
        $agendamentosHoje = Agendamento::whereBetween('created_at', [$hoje, now()])->count();

        if ($agendamentosHoje >= 10) {
            $array['error'] = 'Não é possível criar mais de 10 agendamentos por dia.';
            return $array;
        }

        // Obter produtos e preços
        $produtos = Produto::whereIn('id', $request->input('id_produto'))->get();
        
        // Calcular o valor total dos produtos
        $valorTotal = $produtos->sum('preco');
        
        // Adicionar custo de transporte se necessário
        if ($request->input('transporte')) {
            $valorTotal += 15; // Adiciona R$15 se o transporte estiver ativado
        }

        // Criar um novo pedido
        $newAgendamento = new Agendamento();
        $newAgendamento->id_cliente = $request->input('id_cliente');
        $newAgendamento->id_pet = $request->input('id_pet');
        $newAgendamento->id_raca = $request->input('id_raca');
        $newAgendamento->id_user = $request->input('id_user');
        $newAgendamento->id_status = $request->input('id_status');
        $newAgendamento->data_reserva = $request->input('data_reserva');
        $newAgendamento->horario_reserva = $request->input('horario_reserva');
        $newAgendamento->observacoes = $request->input('observacoes');
        $newAgendamento->transporte = $request->input('transporte');
        $newAgendamento->valor_total = $valorTotal;
        $newAgendamento->save();

        // Adiciona uma mensagem de sucesso
        $array['success'] = 'Pedido criado com sucesso!';
    } catch (\Exception $e) {
        // Captura e exibe o erro se algo der errado
        $array['error'] = 'Ocorreu um erro ao criar o pedido: ' . $e->getMessage();
    }

        return $array;
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

}
