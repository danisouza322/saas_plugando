<?php

namespace App\Http\Controllers;

use App\Models\AsaasCobranca;
use App\Models\Empresa;
use App\Services\AsaasService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PagamentoController extends Controller
{
    protected $asaasService;

    public function __construct(AsaasService $asaasService)
    {
        $this->asaasService = $asaasService;
    }

    /**
     * Exibe a página de pagamento PIX
     */
    public function pix($assinatura)
    {
        try {
            Log::info('Iniciando processamento de pagamento PIX', [
                'assinatura_id' => $assinatura
            ]);

            // Buscar detalhes da assinatura no Asaas
            $asaasService = app(AsaasService::class);
            $detalhes = $asaasService->getSubscriptionDetails($assinatura);

            Log::debug('Detalhes da assinatura recebidos:', [
                'detalhes' => json_decode(json_encode($detalhes), true)
            ]);

            // Verificar se é uma assinatura válida
            if (!$detalhes || !isset($detalhes->id)) {
                Log::error('Assinatura não encontrada ou inválida', [
                    'assinatura_id' => $assinatura,
                    'detalhes' => $detalhes
                ]);
                throw new Exception('Assinatura não encontrada ou inválida');
            }

            // Verificar se o pagamento é PIX
            if ($detalhes->billingType !== 'PIX') {
                Log::error('Forma de pagamento inválida', [
                    'assinatura_id' => $assinatura,
                    'billing_type' => $detalhes->billingType
                ]);
                throw new Exception('Forma de pagamento inválida. Esperado PIX, recebido: ' . $detalhes->billingType);
            }

            // Verificar se temos os dados do PIX
            if (empty($detalhes->qrCode) || empty($detalhes->qrCodeText)) {
                Log::error('Dados do PIX não encontrados', [
                    'assinatura_id' => $assinatura,
                    'tem_qrcode' => !empty($detalhes->qrCode),
                    'tem_qrcodetext' => !empty($detalhes->qrCodeText),
                    'detalhes_completos' => json_decode(json_encode($detalhes), true)
                ]);
                throw new Exception('Dados do PIX não disponíveis. Por favor, tente novamente em alguns instantes.');
            }

            Log::debug('Dados sendo enviados para a view:', [
                'tem_qrcode' => !empty($detalhes->qrCode),
                'tem_qrcodetext' => !empty($detalhes->qrCodeText),
                'valor' => $detalhes->value ?? 0
            ]);

            // Retornar view com os dados do PIX
            return view('pagamentos.pix', [
                'assinatura' => $detalhes,
                'qrcode' => $detalhes->qrCode ?? null,
                'qrCodeText' => $detalhes->qrCodeText ?? null,
                'valor' => $detalhes->value ?? 0
            ]);

        } catch (Exception $e) {
            Log::error('Erro ao processar pagamento PIX', [
                'error' => $e->getMessage(),
                'assinatura_id' => $assinatura,
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()
                ->route('painel.dashboard')
                ->with('error', 'Erro ao processar pagamento: ' . $e->getMessage());
        }
    }

    /**
     * Exibe a página de pagamento via Boleto
     */
    public function boleto($assinatura)
    {
        try {
            Log::info('Iniciando processamento de pagamento via Boleto', [
                'assinatura_id' => $assinatura
            ]);

            // Buscar detalhes da assinatura no Asaas
            $detalhes = $this->asaasService->getSubscriptionDetails($assinatura);

            Log::debug('Detalhes da assinatura recebidos:', [
                'detalhes' => json_decode(json_encode($detalhes), true)
            ]);

            // Verificar se é uma assinatura válida
            if (!$detalhes || !isset($detalhes->id)) {
                throw new Exception('Assinatura não encontrada ou inválida');
            }

            // Verificar se o pagamento é via Boleto
            if ($detalhes->billingType !== 'BOLETO') {
                throw new Exception('Forma de pagamento inválida. Esperado BOLETO, recebido: ' . $detalhes->billingType);
            }

            // Buscar cobrança existente ou criar nova
            $cobranca = AsaasCobranca::where('asaas_id', $detalhes->id)->first();
            
            if (!$cobranca) {
                // Se não encontrou cobrança, criar nova
                $cobranca = AsaasCobranca::create([
                    'empresa_id' => auth()->user()->empresa_id,
                    'asaas_id' => $detalhes->id,
                    'valor' => $detalhes->value,
                    'status' => $detalhes->status,
                    'forma_pagamento' => $detalhes->billingType,
                    'data_vencimento' => $detalhes->nextDueDate ?? now()->addDays(5),
                    'link_pagamento' => $detalhes->invoiceUrl ?? null,
                    'link_boleto' => $detalhes->bankSlipUrl ?? null,
                    'codigo_barras' => $detalhes->identificationField ?? null,
                ]);
            }

            // Se não temos a URL do boleto, tentar buscar novamente
            if (empty($cobranca->link_boleto)) {
                try {
                    $boletoUrl = $this->asaasService->getBoletoUrl($detalhes->id);
                    if ($boletoUrl) {
                        $cobranca->update(['link_boleto' => $boletoUrl]);
                        $cobranca->refresh(); // Recarrega os dados da cobrança
                    }
                } catch (Exception $e) {
                    Log::warning('Erro ao buscar URL do boleto, tentando continuar', [
                        'error' => $e->getMessage()
                    ]);
                }
            }

            // Se ainda não temos a URL do boleto, verificar se temos na resposta da assinatura
            if (empty($cobranca->link_boleto) && !empty($detalhes->bankSlipUrl)) {
                $cobranca->update(['link_boleto' => $detalhes->bankSlipUrl]);
                $cobranca->refresh(); // Recarrega os dados da cobrança
            }

            // Se ainda não temos URL do boleto, lançar erro
            if (empty($cobranca->link_boleto)) {
                throw new Exception('URL do boleto não disponível. Por favor, tente novamente em alguns instantes.');
            }

            Log::debug('Dados sendo enviados para a view:', [
                'tem_url_boleto' => !empty($cobranca->link_boleto),
                'tem_codigo_barras' => !empty($cobranca->codigo_barras),
                'valor' => $cobranca->valor,
                'vencimento' => $cobranca->data_vencimento
            ]);

            // Retornar view com os dados do Boleto
            return view('pagamentos.boleto', [
                'assinatura' => $detalhes,
                'cobranca' => $cobranca,
                'boletoUrl' => $cobranca->link_boleto,
                'codigoBarras' => $cobranca->codigo_barras,
                'valor' => $cobranca->valor,
                'vencimento' => $cobranca->data_vencimento
            ])->layout('layouts.master');

        } catch (Exception $e) {
            Log::error('Erro ao processar pagamento via Boleto', [
                'error' => $e->getMessage(),
                'assinatura_id' => $assinatura,
                'trace' => $e->getTraceAsString()
            ]);

            return view('pagamentos.boleto', [
                'error' => 'Erro ao processar boleto: ' . $e->getMessage()
            ])->layout('layouts.master');
        }
    }
}
