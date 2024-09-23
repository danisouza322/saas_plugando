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
        $table->string('tipo', 50)->nullable();
        $table->string('uf', 2)->nullable();
        $table->string('numero')->nullable();
        $table->string('situacao', 50)->nullable();
        $table->timestamps();

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
