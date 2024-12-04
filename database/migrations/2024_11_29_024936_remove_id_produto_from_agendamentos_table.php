<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('agendamentos', function (Blueprint $table) {
        // Remover a chave estrangeira
        $table->dropForeign(['id_produto']);  // Essa é a forma genérica de remover a chave estrangeira.
    });
}

public function down()
{
    Schema::table('agendamentos', function (Blueprint $table) {
        // Recriar a chave estrangeira, caso seja necessário
        $table->foreign('id_produto')->references('id')->on('produtos')->onDelete('cascade');
    });
}

};
