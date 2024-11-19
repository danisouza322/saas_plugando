<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('clientes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('empresa_id');
            $table->string('cnpj')->unique()->nullable();
            $table->string('cpf_cnpj')->unique()->nullable();
            $table->string('tipo_cliente')->nullable();
            $table->string('razao_social')->nullable();
            $table->string('nome_fantasia')->nullable();
            $table->string('regime_tributario')->nullable();
            $table->string('simples')->nullable();
            $table->string('mei')->nullable();
            $table->date('data_abertura')->nullable();
            $table->string('porte', 50)->nullable();
            $table->double('capital_social')->nullable();
            $table->string('natureza_juridica', 50)->nullable();
            $table->string('tipo', 20)->nullable();
            $table->string('situacao_cadastral', 50)->nullable();
            $table->string('email')->nullable();
            $table->string('telefone')->nullable();
            $table->string('celular')->nullable();
            $table->timestamps();
    
            $table->foreign('empresa_id')->references('id')->on('empresas')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('clientes');
    }
};
