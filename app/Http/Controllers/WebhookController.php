<?php

namespace App\Http\Controllers;

use App\Models\AsaasCobranca;
use App\Services\AsaasService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WebhookController extends Controller
{
    protected $asaasService;

    public function __construct(AsaasService $asaasService)
    {
        $this->asaasService = $asaasService;
    }

    public function handleAsaasWebhook(Request $request)
    {
        try {
            Log::info('Webhook recebido do Asaas:', [
                'event' => $request->input('event'),
                'payment_id' => $request->input('payment.id')
            ]);

            $event = $request->input('event');
            $payment = $request->input('payment');

            if (!$payment) {
                throw new \Exception('Dados do pagamento não encontrados no webhook');
            }

            // Atualizar ou criar cobrança
            $cobranca = AsaasCobranca::updateOrCreate(
                ['asaas_id' => $payment['id']],
                [
                    'valor' => $payment['value'],
                    'status' => $payment['status'],
                    'forma_pagamento' => $payment['billingType'],
                    'data_vencimento' => $payment['dueDate'],
                    'data_pagamento' => $payment['paymentDate'],
                    'valor_pago' => $payment['netValue'],
                    'link_pagamento' => $payment['invoiceUrl'],
                    'link_boleto' => $payment['bankSlipUrl'] ?? null,
                    'codigo_barras' => $payment['nossoNumero'] ?? null,
                ]
            );

            Log::info('Cobrança atualizada via webhook:', [
                'cobranca_id' => $cobranca->id,
                'asaas_id' => $payment['id'],
                'status' => $payment['status'],
                'tem_boleto' => !empty($payment['bankSlipUrl'])
            ]);

            // Atualizar status da empresa se necessário
            if ($payment['subscription']) {
                $empresa = $cobranca->empresa;
                if ($empresa) {
                    $this->asaasService->updateEmpresaStatus($empresa, $payment['status']);
                }
            }

            return response()->json(['status' => 'success']);

        } catch (\Exception $e) {
            Log::error('Erro ao processar webhook do Asaas:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'payload' => $request->all()
            ]);

            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
