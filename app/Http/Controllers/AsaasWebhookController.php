<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use App\Models\Empresa;
use App\Models\AsaasCobranca;
use Carbon\Carbon;

class AsaasWebhookController extends Controller
{
    public function handle(Request $request)
    {
        // Salva o payload completo em um arquivo JSON para debug
        $filename = 'webhook_'.time().'.json';
        try {
            Storage::put($filename, json_encode($request->all(), JSON_PRETTY_PRINT));
        } catch (\Exception $e) {
            Log::error('Erro ao salvar payload do webhook', [
                'error' => $e->getMessage()
            ]);
        }
        
        // Log inicial
        Log::info('Webhook Asaas recebido', [
            'event' => $request->input('event'),
            'method' => $request->method(),
            'filename' => $filename,
            'payload' => $request->all()
        ]);

        try {
            // Teste do endpoint via GET
            if ($request->isMethod('get')) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Webhook endpoint está funcionando'
                ]);
            }

            // Validação básica do payload
            if (!$request->has('event')) {
                Log::error('Webhook sem evento especificado', [
                    'payload' => $request->all()
                ]);
                return response()->json([
                    'status' => 'error',
                    'message' => 'Evento não especificado'
                ], 400);
            }

            // Extrai dados principais com validação
            $event = $request->input('event');
            $payment = $request->input('payment', []);
            $subscription = $request->input('subscription', []);

            // Validação adicional dos dados
            if (empty($event)) {
                Log::error('Evento vazio recebido', [
                    'payload' => $request->all()
                ]);
                return response()->json([
                    'status' => 'error',
                    'message' => 'Evento vazio'
                ], 400);
            }

            // Log detalhado
            Log::info('Dados do webhook', [
                'event' => $event,
                'payment' => $payment,
                'subscription' => $subscription
            ]);

            // Processa diferentes tipos de eventos com tratamento de erro para cada caso
            try {
                switch ($event) {
                    case 'PAYMENT_CREATED':
                        $this->handlePaymentCreated($payment);
                        break;

                    case 'PAYMENT_RECEIVED':
                    case 'PAYMENT_CONFIRMED':
                        $this->handlePaymentConfirmed($payment);
                        break;

                    case 'PAYMENT_OVERDUE':
                        $this->handlePaymentOverdue($payment);
                        break;

                    case 'SUBSCRIPTION_CREATED':
                    case 'SUBSCRIPTION_UPDATED':
                    case 'SUBSCRIPTION_DELETED':
                    case 'SUBSCRIPTION_RENEWED':
                        $this->handleSubscriptionEvent($event, $subscription);
                        break;

                    default:
                        Log::info('Evento não processado', ['event' => $event]);
                }
            } catch (\Exception $e) {
                Log::error('Erro ao processar evento específico', [
                    'event' => $event,
                    'error' => $e->getMessage(),
                    'line' => $e->getLine(),
                    'file' => $e->getFile()
                ]);
                // Continua a execução
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Webhook processado',
                'event' => $event
            ]);

        } catch (\Exception $e) {
            Log::error('Erro no webhook', [
                'error' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
                'trace' => $e->getTraceAsString(),
                'payload' => $request->all()
            ]);

            // Retorna um erro 500 mais informativo
            return response()->json([
                'status' => 'error',
                'message' => 'Erro ao processar webhook: ' . $e->getMessage(),
                'error_type' => get_class($e)
            ], 500);
        }
    }

    protected function handlePaymentCreated(array $payment)
    {
        Log::info('Processando pagamento criado', $payment);

        try {
            // Verifica se customer_id existe
            if (!isset($payment['customer'])) {
                Log::error('Customer ID não encontrado no payload', ['payment' => $payment]);
                return;
            }

            // Verifica se payment ID existe
            if (!isset($payment['id'])) {
                Log::error('Payment ID não encontrado no payload', ['payment' => $payment]);
                return;
            }

            // Log dos dados do cliente
            Log::info('Buscando empresa', ['customer_id' => $payment['customer']]);

            // Encontra a empresa com log mais detalhado
            $empresa = Empresa::where('asaas_customer_id', $payment['customer'])->first();
            
            if (!$empresa) {
                Log::error('Empresa não encontrada', [
                    'customer_id' => $payment['customer']
                ]);
                return;
            }

            Log::info('Empresa encontrada', ['empresa_id' => $empresa->id]);

            // Prepara dados com validação
            $cobrancaData = [
                'empresa_id' => $empresa->id,
                'asaas_id' => $payment['id'], // Adicionado aqui também
                'status' => $payment['status'] ?? 'PENDING',
                'valor' => $payment['value'] ?? 0,
                'vencimento' => isset($payment['dueDate']) 
                    ? Carbon::parse($payment['dueDate'])->format('Y-m-d')
                    : now()->format('Y-m-d'),
            ];

            // Log dos dados da cobrança
            Log::info('Dados da cobrança preparados', ['cobrancaData' => $cobrancaData]);

            // Adiciona campos opcionais
            if (isset($payment['bankSlipUrl'])) {
                $cobrancaData['url_boleto'] = $payment['bankSlipUrl'];
            }
            if (isset($payment['nossoNumero'])) {
                $cobrancaData['linha_digitavel'] = $payment['nossoNumero'];
            }
            if (isset($payment['pixQrCode'])) {
                $cobrancaData['qrcode'] = $payment['pixQrCode'];
            }
            if (isset($payment['pixCode'])) {
                $cobrancaData['qrcode_text'] = $payment['pixCode'];
            }

            // Log antes de salvar
            Log::info('Tentando salvar cobrança', [
                'asaas_id' => $payment['id'],
                'dados' => $cobrancaData
            ]);

            try {
                // Tenta primeiro encontrar a cobrança existente
                $cobranca = AsaasCobranca::where('asaas_id', $payment['id'])->first();
                
                if ($cobranca) {
                    Log::info('Atualizando cobrança existente', ['asaas_id' => $payment['id']]);
                    $cobranca->update($cobrancaData);
                } else {
                    Log::info('Criando nova cobrança', ['asaas_id' => $payment['id']]);
                    AsaasCobranca::create($cobrancaData);
                }

                Log::info('Cobrança processada com sucesso', [
                    'empresa_id' => $empresa->id,
                    'asaas_id' => $payment['id']
                ]);

            } catch (\Exception $e) {
                Log::error('Erro ao salvar cobrança no banco', [
                    'error' => $e->getMessage(),
                    'sql' => $e instanceof \Illuminate\Database\QueryException ? $e->getSql() : null,
                    'bindings' => $e instanceof \Illuminate\Database\QueryException ? $e->getBindings() : null
                ]);
                throw $e; // Re-throw para ser capturado pelo catch externo
            }

        } catch (\Exception $e) {
            Log::error('Erro ao processar pagamento', [
                'error' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
                'trace' => $e->getTraceAsString(),
                'payment' => $payment
            ]);
        }
    }

    protected function handlePaymentConfirmed(array $payment)
    {
        Log::info('Processando pagamento confirmado', $payment);

        try {
            // Verifica se customer_id existe
            if (!isset($payment['customer'])) {
                Log::error('Customer ID não encontrado no payload');
                return;
            }

            // Encontra a empresa
            $empresa = Empresa::where('asaas_customer_id', $payment['customer'])->first();

            if (!$empresa) {
                Log::error('Empresa não encontrada', [
                    'customer_id' => $payment['customer']
                ]);
                return;
            }

            // Atualiza o status da empresa
            $empresa->update([
                'status_assinatura' => 'ativa',
                'assinatura_status' => 'RECEIVED'
            ]);

            // Atualiza a cobrança
            if (isset($payment['id'])) {
                AsaasCobranca::where('asaas_id', $payment['id'])->update([
                    'status' => $payment['status'] ?? 'RECEIVED'
                ]);
            }

            Log::info('Pagamento confirmado processado com sucesso', [
                'empresa_id' => $empresa->id,
                'asaas_id' => $payment['id'] ?? null
            ]);

        } catch (\Exception $e) {
            Log::error('Erro ao processar pagamento confirmado', [
                'error' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
                'trace' => $e->getTraceAsString(),
                'payment' => $payment
            ]);
        }
    }

    protected function handlePaymentOverdue(array $payment)
    {
        Log::info('Processando pagamento atrasado', $payment);

        try {
            // Verifica se customer_id existe
            if (!isset($payment['customer'])) {
                Log::error('Customer ID não encontrado no payload');
                return;
            }

            // Encontra a empresa
            $empresa = Empresa::where('asaas_customer_id', $payment['customer'])->first();

            if (!$empresa) {
                Log::error('Empresa não encontrada', [
                    'customer_id' => $payment['customer']
                ]);
                return;
            }

            // Atualiza o status da empresa
            $empresa->update([
                'status_assinatura' => 'inadimplente',
                'assinatura_status' => 'OVERDUE'
            ]);

            // Atualiza a cobrança
            if (isset($payment['id'])) {
                AsaasCobranca::where('asaas_id', $payment['id'])->update([
                    'status' => $payment['status'] ?? 'OVERDUE'
                ]);
            }

            Log::info('Pagamento atrasado processado com sucesso', [
                'empresa_id' => $empresa->id,
                'asaas_id' => $payment['id'] ?? null
            ]);

        } catch (\Exception $e) {
            Log::error('Erro ao processar pagamento atrasado', [
                'error' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
                'trace' => $e->getTraceAsString(),
                'payment' => $payment
            ]);
        }
    }

    protected function handleSubscriptionEvent(string $event, array $subscription)
    {
        Log::info('Processando evento de assinatura', [
            'event' => $event,
            'subscription' => $subscription
        ]);

        try {
            // Validação dos dados necessários
            if (!isset($subscription['customer'])) {
                Log::error('Customer ID não encontrado no payload da assinatura', [
                    'event' => $event,
                    'subscription' => $subscription
                ]);
                return;
            }

            // Encontra a empresa com tratamento de erro mais suave
            $empresa = Empresa::where('asaas_customer_id', $subscription['customer'])->first();
            
            if (!$empresa) {
                Log::error('Empresa não encontrada para o evento de assinatura', [
                    'customer_id' => $subscription['customer'],
                    'event' => $event
                ]);
                return;
            }

            switch ($event) {
                case 'SUBSCRIPTION_CREATED':
                    if (!isset($subscription['id'], $subscription['value'], $subscription['nextDueDate'], $subscription['billingType'])) {
                        Log::error('Dados incompletos para SUBSCRIPTION_CREATED', ['subscription' => $subscription]);
                        return;
                    }
                    
                    $empresa->update([
                        'asaas_subscription_id' => $subscription['id'],
                        'status_assinatura' => 'pendente',
                        'assinatura_status' => 'ACTIVE',
                        'valor_assinatura' => $subscription['value'],
                        'data_proximo_vencimento' => Carbon::parse($subscription['nextDueDate'])->format('Y-m-d'),
                        'forma_pagamento' => $subscription['billingType']
                    ]);
                    break;

                case 'SUBSCRIPTION_UPDATED':
                    if (!isset($subscription['value'], $subscription['nextDueDate'], $subscription['billingType'])) {
                        Log::error('Dados incompletos para SUBSCRIPTION_UPDATED', ['subscription' => $subscription]);
                        return;
                    }
                    
                    $empresa->update([
                        'valor_assinatura' => $subscription['value'],
                        'data_proximo_vencimento' => Carbon::parse($subscription['nextDueDate'])->format('Y-m-d'),
                        'forma_pagamento' => $subscription['billingType']
                    ]);
                    break;

                case 'SUBSCRIPTION_DELETED':
                case 'SUBSCRIPTION_CANCELLED':
                    $empresa->update([
                        'status_assinatura' => 'cancelada',
                        'assinatura_status' => 'INACTIVE'
                    ]);
                    break;

                case 'SUBSCRIPTION_RENEWED':
                    if (!isset($subscription['nextDueDate'])) {
                        Log::error('Data de próximo vencimento não encontrada para SUBSCRIPTION_RENEWED', ['subscription' => $subscription]);
                        return;
                    }
                    
                    $empresa->update([
                        'data_proximo_vencimento' => Carbon::parse($subscription['nextDueDate'])->format('Y-m-d')
                    ]);
                    break;
            }

            Log::info('Evento de assinatura processado com sucesso', [
                'empresa_id' => $empresa->id,
                'event' => $event
            ]);

        } catch (\Exception $e) {
            Log::error('Erro ao processar evento de assinatura', [
                'error' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
                'trace' => $e->getTraceAsString(),
                'event' => $event,
                'subscription' => $subscription
            ]);
            // Não lança a exceção, apenas loga o erro
        }
    }
}
