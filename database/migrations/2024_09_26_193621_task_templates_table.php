<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('task_templates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('empresa_id')->constrained()->onDelete('cascade'); // Relação com Company
            $table->string('titulo');
            $table->text('descricao')->nullable();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Usuário responsável
            $table->foreignId('cliente_id')->constrained()->onDelete('cascade'); // Cliente associado
            $table->enum('frequencia', ['monthly']); // Frequências futuras podem ser adicionadas
            $table->integer('dia_do_mes')->default(1); // Dia do mês para tarefas mensais
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('task_templates');
    }
};
