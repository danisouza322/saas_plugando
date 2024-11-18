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
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->foreignId('empresa_id')->constrained('empresas')->onDelete('cascade');
            $table->foreignId('task_type_id')->constrained('task_types')->onDelete('cascade');
            $table->foreignId('cliente_id')->nullable()->constrained('clientes')->nullOnDelete();
            
            // Datas
            $table->dateTime('start_date')->nullable();
            $table->dateTime('due_date')->nullable();
            $table->dateTime('completed_at')->nullable();
            
            // Status e Prioridade
            $table->enum('status', ['pending', 'in_progress', 'completed', 'delayed', 'cancelled'])->default('pending');
            $table->enum('priority', ['low', 'medium', 'high', 'urgent'])->default('medium');
            
            // Tempo e Orçamento
            $table->integer('estimated_minutes')->nullable();
            $table->integer('spent_minutes')->default(0);
            $table->decimal('budget', 10, 2)->nullable();
            $table->decimal('spent_amount', 10, 2)->default(0);
            
            // Campos adicionais
            $table->string('location')->nullable();
            $table->json('tags')->nullable();
            $table->boolean('requires_approval')->default(false);
            $table->boolean('is_approved')->default(false);
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->dateTime('approved_at')->nullable();
            
            // Recorrência
            $table->boolean('is_recurring')->default(false);
            $table->string('recurrence_pattern')->nullable(); // daily, weekly, monthly, custom
            $table->json('recurrence_config')->nullable(); // configurações específicas de recorrência
            
            // SLA
            $table->integer('sla_minutes')->nullable();
            $table->boolean('sla_breached')->default(false);
            
            // Campos padrão
            $table->timestamps();
            $table->softDeletes();
        });

        // Tabela para equipe envolvida
        Schema::create('task_users', function (Blueprint $table) {
            $table->id();
            $table->foreignId('task_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('role')->nullable(); // observador, colaborador, revisor
            $table->timestamps();
        });

        // Tabela para subtarefas
        Schema::create('subtasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('task_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('description')->nullable();
            $table->boolean('completed')->default(false);
            $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete();
            $table->dateTime('completed_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // Tabela para comentários
        Schema::create('task_comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('task_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->text('content');
            $table->timestamps();
            $table->softDeletes();
        });

        // Tabela para anexos
        Schema::create('task_attachments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('task_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('filename');
            $table->string('original_filename');
            $table->string('mime_type');
            $table->integer('size')->unsigned();
            $table->timestamps();
            $table->softDeletes();
        });

        // Tabela para histórico de atualizações
        Schema::create('task_history', function (Blueprint $table) {
            $table->id();
            $table->foreignId('task_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('field_name');
            $table->text('old_value')->nullable();
            $table->text('new_value')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('task_history');
        Schema::dropIfExists('task_attachments');
        Schema::dropIfExists('task_comments');
        Schema::dropIfExists('subtasks');
        Schema::dropIfExists('task_users');
        Schema::dropIfExists('tasks');
    }
};
