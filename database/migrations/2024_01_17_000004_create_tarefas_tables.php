<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tabela principal de tarefas
        Schema::create('tarefas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('empresa_id')->constrained('empresas');
            $table->foreignId('cliente_id')->constrained('clientes');
            $table->string('titulo');
            $table->text('descricao')->nullable();
            $table->enum('status', ['novo', 'em_andamento', 'pendente', 'concluido'])->default('novo');
            $table->enum('prioridade', ['baixa', 'media', 'alta'])->default('media');
            $table->date('data_vencimento');
            $table->foreignId('criado_por')->constrained('users');
            $table->timestamps();
            $table->softDeletes();
        });

        // Tabela pivô para responsáveis da tarefa
        Schema::create('tarefa_responsaveis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tarefa_id')->constrained('tarefas')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('atribuido_por')->constrained('users');
            $table->timestamp('data_atribuicao');
            $table->timestamps();
            $table->unique(['tarefa_id', 'user_id']); // Evita duplicatas
        });

        // Tabela para arquivos da tarefa
        Schema::create('arquivos_tarefa', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tarefa_id')->constrained('tarefas')->onDelete('cascade');
            $table->string('nome_arquivo');
            $table->string('caminho_arquivo');
            $table->string('tipo_arquivo');
            $table->integer('tamanho');
            $table->foreignId('uploaded_por')->constrained('users');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('arquivos_tarefa');
        Schema::dropIfExists('tarefa_responsaveis');
        Schema::dropIfExists('tarefas');
    }
};
