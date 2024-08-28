<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pets extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = ['nome', 'raca', 'idade', 'cliente_id'];

    public function raca()
    {
        return $this->belongsTo(Raca::class, 'raca_id', 'id');
    }

    // Define the relationship with Cliente
    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'cliente_id', 'id');
    }

    public function agendamentos()
    {
        return $this->hasMany(Agendamento::class, 'id_pet');
    }
}
