<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Funcionario extends Model
{
    use HasFactory;

    public $timestamps = false;

    public function agendamentos()
    {
        return $this->hasMany(Agendamento::class, 'id_funcionario');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    // Relacionamento com a model Funcao
    public function funcao()
    {
        return $this->belongsTo(Funcao::class, 'id_funcao');
    }
}
