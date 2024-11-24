<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // 1. Criar tabela empresas
        Schema::create('empresas', function (Blueprint $table) {
            $table->id();
            // Campos básicos da empresa
            $table->string('nome');
            $table->string('razao_social')->nullable();
            $table->string('cnpj', 14)->unique()->nullable();
            $table->string('email')->unique()->nullable();
            $table->string('telefone', 20)->nullable();
            $table->string('endereco')->nullable();
            $table->string('cidade')->nullable();
            $table->string('estado', 2)->nullable();
            $table->string('cep', 8)->nullable();
            $table->string('bairro')->nullable();
            $table->string('numero')->nullable();
            $table->string('complemento')->nullable();
            $table->string('logo')->nullable();
            $table->boolean('ativo')->default(true);
            $table->string('plano')->default('trial')->nullable(); // Adicionando coluna plano

            // Campos para controle de status e plano
            $table->string('status_assinatura')->default('pendente');
            $table->decimal('valor_assinatura', 10, 2)->nullable();
            $table->date('data_proximo_vencimento')->nullable();
            $table->string('forma_pagamento')->nullable();
            $table->string('assinatura_status')->default('PENDING')->nullable();
            
            // Campos do Asaas
            $table->string('asaas_customer_id')->nullable()->unique();
            $table->string('asaas_subscription_id')->nullable()->unique();
            
            // Campos adicionais de controle
            $table->boolean('assinatura_ativa')->default(false);
            $table->timestamp('assinatura_expira')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });

        // 2. Adicionar empresa_id na tabela users
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('empresa_id')->nullable()->constrained('empresas')->onDelete('cascade');
            $table->boolean('ativo')->default(true);
        });

        // 3. Criar tabela clientes
        Schema::create('clientes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('empresa_id')->constrained('empresas')->onDelete('cascade');
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
            $table->boolean('ativo')->default(true);
            $table->text('atividades_economicas')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // 4. Criar tabela certificados
        Schema::create('certificados', function (Blueprint $table) {
            $table->id();
            $table->foreignId('empresa_id')->constrained('empresas')->onDelete('cascade');
            $table->string('nome');
            $table->string('tipo'); // A1, A3, etc
            $table->string('cnpj_cpf');
            $table->date('data_vencimento');
            $table->string('arquivo_path');
            $table->string('senha')->nullable();
            $table->timestamps();
        });


       

        // 7. Criar tabela enderecos
        Schema::create('enderecos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cliente_id')->constrained('clientes')->onDelete('cascade');
            $table->string('complemento')->nullable();
            $table->string('cep')->nullable();
            $table->string('rua')->nullable();
            $table->string('bairro')->nullable();
            $table->string('cidade')->nullable();
            $table->string('numero', 15)->nullable();
            $table->integer('municipio_ibge')->nullable();
            $table->string('estado', 4)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // 8. Criar tabela inscricoes_estaduais
        Schema::create('inscricoes_estaduais', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cliente_id')->constrained('clientes')->onDelete('cascade');
            $table->string('estado', 2);
            $table->string('numero');
            $table->string('ativa')->default(true);
            $table->date('data_status')->nullable();
            $table->unsignedBigInteger('status_id')->nullable();
            $table->string('status_texto')->nullable();
            $table->unsignedBigInteger('tipo_id')->nullable();
            $table->string('tipo_texto')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // 9. Criar tabela socios_administradores
        Schema::create('socios_administradores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cliente_id')->constrained('clientes')->onDelete('cascade');
            $table->string('tipo', 2)->nullable();
            $table->date('entrada')->nullable();
            $table->string('socio')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('socios_administradores');
        Schema::dropIfExists('inscricoes_estaduais');
        Schema::dropIfExists('enderecos');
        Schema::dropIfExists('asaas_cobrancas');
        Schema::dropIfExists('planos');
        Schema::dropIfExists('certificados');
        Schema::dropIfExists('clientes');
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['empresa_id']);
            $table->dropColumn(['empresa_id', 'ativo']);
        });
        Schema::dropIfExists('empresas');
    }
};
