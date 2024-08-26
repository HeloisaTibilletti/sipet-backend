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
            $table->string('usuario')->unique();
            $table->string('senha');
      
        });

        Schema::create('clientes', function(Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->string('sobrenome');
            $table->string('email')->unique();
            $table->string('endereco');
            $table->integer('telefone');        
        });

        Schema::create('racas', function(Blueprint $table) {
            $table->id();
            $table->string('nome');
        });

        Schema::create('produtos', function(Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->float('valor');
        });

        Schema::create('funcao', function(Blueprint $table) {
            $table->id();
            $table->string('nome');
        });

        Schema::create('status', function(Blueprint $table) {
            $table->id();
            $table->string('nome');
        });

        Schema::create('pets', function(Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->date('data_nasc');
            $table->unsignedBigInteger('raca_id');
            $table->string('sexo');
            $table->string('especie');
            $table->string('porte');
            $table->string('condicoes_fisicas');
            $table->string('tratamentos_especiais')->nullable();;   
            $table->unsignedBigInteger('cliente_id'); // Chave estrangeira

            $table->foreign('cliente_id')->references('id')->on('clientes')->onDelete('cascade');
            $table->foreign('raca_id')->references('id')->on('racas')->onDelete('cascade');
        });

        Schema::create('funcionarios', function(Blueprint $table) {
            $table->id();
            $table->string('email')->unique();
            $table->string('senha');
            $table->string('nome');
            $table->string('sobrenome');
            $table->integer('telefone');
            $table->date('data_nasc');
            $table->unsignedBigInteger('id_funcao');
            $table->unsignedBigInteger('id_user');

            // Define a chave estrangeira
            $table->foreign('id_user')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('id_funcao')->references('id')->on('funcao')->onDelete('cascade');
            
        });

        Schema::create('agendamentos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_cliente');  // Referencia à tabela 'clientes'
            $table->unsignedBigInteger('id_pet');     // Referencia à tabela 'pets'
            $table->unsignedBigInteger('id_raca');    // Referencia à tabela 'racas'
            $table->unsignedBigInteger('id_funcionario'); // Referencia à tabela 'funcionarios'
            $table->unsignedBigInteger('id_status');
            $table->unsignedBigInteger('id_produto');
            $table->date('data_reserva');
            $table->time('horario_reserva');   
            $table->string('valor_total');
            $table->string('observacoes')->nullable();
            $table->boolean('transporte')->nullable();     
        
            // Define as chaves estrangeiras
            $table->foreign('id_cliente')->references('id')->on('clientes')->onDelete('cascade');
            $table->foreign('id_status')->references('id')->on('status')->onDelete('cascade');
            $table->foreign('id_pet')->references('id')->on('pets')->onDelete('cascade');
            $table->foreign('id_raca')->references('id')->on('racas')->onDelete('cascade');
            $table->foreign('id_produto')->references('id')->on('produtos')->onDelete('cascade');
            $table->foreign('id_funcionario')->references('id')->on('funcionarios')->onDelete('cascade');
        
            $table->timestamps(); // Adiciona as colunas created_at e updated_at
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
        Schema::dropIfExists('funcao');
    }
};
