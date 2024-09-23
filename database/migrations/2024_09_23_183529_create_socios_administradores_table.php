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
    Schema::create('socios_administradores', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('cliente_id');
        $table->string('tipo', 2)->nullable();
        $table->date('entrada')->nullable();
        $table->string('socio')->nullable();
        $table->timestamps();

        $table->foreign('cliente_id')->references('id')->on('clientes')->onDelete('cascade');
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('socios_administradores');
    }
};
