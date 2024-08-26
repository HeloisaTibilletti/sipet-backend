<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    use HasFactory;

    public $timestamps = false;


    public function pets()
    {
        return $this->hasMany(Pets::class);
    }

    public function agendamentos()
    {
        return $this->hasMany(Agendamento::class, 'id_cliente');
    }
}
