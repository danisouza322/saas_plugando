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
    Schema::create('inscricoes_estaduais', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('cliente_id');
        $table->string('estado', 2);
        $table->string('numero');
        $table->boolean('ativa')->default(true);
        $table->date('data_status')->nullable();
        $table->unsignedBigInteger('status_id')->nullable();
        $table->string('status_texto')->nullable();
        $table->unsignedBigInteger('tipo_id')->nullable();
        $table->string('tipo_texto')->nullable();
        $table->timestamps();

        // Chave estrangeira para o cliente
        $table->foreign('cliente_id')->references('id')->on('clientes')->onDelete('cascade');
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inscricoes_estaduais');
    }
};
