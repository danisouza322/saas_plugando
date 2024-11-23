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
        Schema::table('empresas', function (Blueprint $table) {
            $table->dropColumn([
                'status_assinatura',
                'valor_assinatura',
                'data_proximo_vencimento',
                'forma_pagamento',
                'asaas_customer_id',
                'asaas_subscription_id',
                'assinatura_ativa',
                'assinatura_status',
                'assinatura_expira'
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('empresas', function (Blueprint $table) {
            $table->string('status_assinatura')->nullable();
            $table->decimal('valor_assinatura', 10, 2)->nullable();
            $table->date('data_proximo_vencimento')->nullable();
            $table->string('forma_pagamento')->nullable();
            $table->string('asaas_customer_id')->nullable();
            $table->string('asaas_subscription_id')->nullable();
            $table->boolean('assinatura_ativa')->default(false);
            $table->string('assinatura_status')->nullable();
            $table->timestamp('assinatura_expira')->nullable();
        });
    }
};
