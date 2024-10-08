<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produto extends Model
{
    use HasFactory;

    public $timestamps = false;

    public function agendamentos()
    {
        return $this->hasMany(Agendamento::class, 'id_produto');
    }
}
