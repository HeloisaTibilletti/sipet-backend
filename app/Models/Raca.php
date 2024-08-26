<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Raca extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'nome',
    ];

    public function agendamentos()
    {
        return $this->hasMany(Agendamento::class, 'id_raca');
    }

    // Relacionamento com a model Pet
    public function pets()
    {
        return $this->hasMany(Pets::class, 'raca_id');
    }
}

