<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('clientes', function (Blueprint $table) {
            $table->string('nome')->after('empresa_id')->nullable();
        });

        // Atualizar registros existentes copiando o nome_fantasia ou razao_social para o campo nome
        DB::statement('UPDATE clientes SET nome = COALESCE(nome_fantasia, razao_social) WHERE nome IS NULL');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('clientes', function (Blueprint $table) {
            $table->dropColumn('nome');
        });
    }
};
