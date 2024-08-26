<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    use HasFactory;

    protected $table = 'status';
    public $timestamps = false;

    public function agendamentos()
    {
        return $this->hasMany(Agendamento::class, 'id_status');
    }
}
