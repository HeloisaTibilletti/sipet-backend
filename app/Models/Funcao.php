<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Funcao extends Model
{
    use HasFactory;

    public function funcionarios()
    {
        return $this->hasMany(Funcionario::class, 'id_funcao');
    }
}
