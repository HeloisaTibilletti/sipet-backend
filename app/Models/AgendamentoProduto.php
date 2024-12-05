<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AgendamentoProduto extends Model
{
    protected $table = 'agendamento_produtos';

    public function agendamento()
    {
        return $this->belongsTo(Agendamento::class, 'id_agendamento');
    }

    public function produto()
    {
        return $this->belongsTo(Produto::class, 'id_produto');
    }
}