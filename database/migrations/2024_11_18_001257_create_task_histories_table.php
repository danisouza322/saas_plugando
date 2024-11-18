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
        Schema::create('task_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('task_id')->constrained('tasks')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('action_type'); // create, update, delete, comment, etc
            $table->string('field_name')->nullable(); // campo alterado (em caso de update)
            $table->text('old_value')->nullable(); // valor antigo
            $table->text('new_value')->nullable(); // valor novo
            $table->text('description')->nullable(); // descrição da alteração
            $table->text('comment')->nullable(); // comentário do usuário
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('task_histories');
    }
};
