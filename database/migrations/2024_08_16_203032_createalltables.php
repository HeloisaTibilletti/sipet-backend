<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function(Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->string('sobrenome');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('endereco');
            $table->integer('telefone');        
        });

        Schema::create('pets', function(Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->date('data_nasc');
            $table->string('raca');
            $table->string('sexo');
            $table->string('especie');
            $table->string('porte');
            $table->string('condicoes_fisicas');
            $table->string('tratamentos_especiais')->nullable();;   
            $table->integer('id_owner');
        });

        Schema::create('agendamentos', function(Blueprint $table) {
            $table->id();
            $table->integer('id_owner');
            $table->integer('id_pet');
            $table->integer('id_raca');
            $table->integer('id_funcionario');
            $table->date('data_reserva');
            $table->time('horario_reserva');
            $table->string('status');
            $table->string('valor_total');
            $table->string('observacoes')->nullable();
            $table->boolean('transporte')->nullable();     
        });

        Schema::create('raca', function(Blueprint $table) {
            $table->id();
            $table->string('nome');
        });

        Schema::create('status', function(Blueprint $table) {
            $table->id();
            $table->string('status');
        });

        Schema::create('funcionarios', function(Blueprint $table) {
            $table->id();
            $table->string('email')->unique();
            $table->string('password');
            $table->string('nome');
            $table->string('sobrenome');
            $table->integer('telefone');
            $table->string('funcao');
            $table->date('data_nasc');
            
        });

        Schema::create('produtos', function(Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->float('valor');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('pets');
        Schema::dropIfExists('agendamentos');
        Schema::dropIfExists('raca');
        Schema::dropIfExists('status');
        Schema::dropIfExists('funcionarios');
        Schema::dropIfExists('produtos');
    }
};
