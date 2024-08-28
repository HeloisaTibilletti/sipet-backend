<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Agendamento extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_cliente',
        'id_pet',
        'id_raca',
        'id_user',
        'data_reserva',
        'horario_reserva',
        'status',
        'valor_total',
        'observacoes',
        'transporte',
    ];

    // Relacionamento com a model Cliente
    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'id_cliente');
    }

    // Relacionamento com a model Pet
    public function pet()
    {
        return $this->belongsTo(Pets::class, 'id_pet');
    }

    // Relacionamento com a model Raca
    public function raca()
    {
        return $this->belongsTo(Raca::class, 'id_raca');
    }

    // Relacionamento com a model Funcionario
    public function user()
    {
        return $this->belongsTo(User::class, 'id_funcionario');
    }

    public function produto()
    {
        return $this->belongsTo(Produto::class, 'id_produto');
    }
}
