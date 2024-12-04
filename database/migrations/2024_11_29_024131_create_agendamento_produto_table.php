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
    Schema::create('agendamento_produto', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('agendamento_id');
        $table->unsignedBigInteger('produto_id');
        $table->timestamps();

        // Chaves estrangeiras
        $table->foreign('agendamento_id')->references('id')->on('agendamentos')->onDelete('cascade');
        $table->foreign('produto_id')->references('id')->on('produtos')->onDelete('cascade');
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agendamento_produto');
    }
};
