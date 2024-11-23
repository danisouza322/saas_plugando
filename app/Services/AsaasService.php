<?php

namespace App\Services;

use App\Models\Empresa;
use App\Models\AsaasCobranca;
use Illuminate\Support\Facades\Log;
use Exception;
use CodePhix\Asaas\Asaas;
use Throwable;
use Carbon\Carbon;

class AsaasService
{
    protected $asaas;
    protected $validBillingTypes = ['BOLETO', 'CREDIT_CARD', 'PIX'];

    public function __construct()
    {
        try {
            $apiKey = config('asaas.api_key');
            if (empty($apiKey)) {
                throw new Exception('API Key do Asaas não configurada');
            }

            // Remove aspas extras se houver
            $apiKey = trim($apiKey, '"');

            // Converte ambiente para o formato esperado pelo SDK
            $mode = config('asaas.environment', 'sandbox');
            $sdkMode = $mode === 'production' ? 'producao' : 'homologacao';
            
            Log::debug('Iniciando serviço Asaas', [
                'environment' => $mode,
                'sdk_mode' => $sdkMode,
                'api_key_length' => strlen($apiKey)
            ]);

            $this->asaas = new Asaas($apiKey, $sdkMode);
        } catch (Throwable $e) {
            Log::error('Erro ao inicializar serviço Asaas', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    /**
     * Formatar telefone para o padrão aceito pelo Asaas
     * Formato esperado: 99999999999 (DDD + número)
     */
    protected function formatPhone($phone)
    {
        Log::debug('Formatando telefone', ['telefone_original' => $phone]);

        // Remove tudo que não for número
        $phone = preg_replace('/[^0-9]/', '', $phone);
        Log::debug('Telefone após limpeza', ['telefone_limpo' => $phone]);
        
        // Garante que o telefone tem 10 ou 11 dígitos
        $len = strlen($phone);
        
        // Se tiver menos que 10 dígitos
        if ($len < 10) {
            Log::error('Telefone com menos de 10 dígitos', [
                'telefone' => $phone,
                'quantidade_digitos' => $len
            ]);
            throw new Exception('Telefone deve ter pelo menos 10 dígitos (incluindo DDD)');
        }
        
        // Se tiver mais que 11 dígitos, pega apenas os últimos 11
        if ($len > 11) {
            $phone = substr($phone, -11);
            Log::debug('Telefone com mais de 11 dígitos, pegando últimos 11', ['telefone_ajustado' => $phone]);
        }

        // Se tiver 11 dígitos, converte para 10 removendo o 9
        if (strlen($phone) === 11) {
            $ddd = substr($phone, 0, 2);
            $numero = substr($phone, 3); // Pula o 9
            $phone = $ddd . $numero;
            Log::debug('Convertendo celular para telefone fixo', [
                'ddd' => $ddd,
                'numero' => $numero,
                'telefone_final' => $phone
            ]);
        }

        // Valida o DDD
        $ddd = substr($phone, 0, 2);
        if ($ddd < '11' || $ddd > '99') {
            Log::error('DDD inválido', ['ddd' => $ddd]);
            throw new Exception('DDD inválido');
        }

        Log::debug('Telefone formatado com sucesso', [
            'telefone_final' => $phone,
            'ddd' => substr($phone, 0, 2),
            'numero' => substr($phone, 2)
        ]);

        return $phone;
    }

    /**
     * Criar ou atualizar cliente no Asaas
     */
    public function syncCustomer(Empresa $empresa)
    {
        try {
            Log::info('Iniciando sincronização do cliente com Asaas', [
                'empresa_id' => $empresa->id,
                'razao_social' => $empresa->razao_social,
                'cnpj' => $empresa->cnpj,
                'telefone_original' => $empresa->telefone
            ]);
            
            // Validação dos dados obrigatórios
            if (empty($empresa->razao_social)) {
                throw new Exception('Razão social é obrigatória');
            }
            if (empty($empresa->cnpj)) {
                throw new Exception('CNPJ é obrigatório');
            }
            if (empty($empresa->email)) {
                throw new Exception('Email é obrigatório');
            }
            if (empty($empresa->telefone)) {
                throw new Exception('Telefone é obrigatório');
            }

            // Limpa o CNPJ/CPF
            $cpfCnpj = preg_replace('/[^0-9]/', '', $empresa->cnpj);
            if (strlen($cpfCnpj) !== 11 && strlen($cpfCnpj) !== 14) {
                throw new Exception('CNPJ/CPF com formato inválido');
            }

            try {
                // Formata o telefone
                $telefone = $this->formatPhone($empresa->telefone);
            } catch (Exception $e) {
                throw new Exception('Telefone inválido: ' . $e->getMessage());
            }
            
            $customerData = [
                'name' => $empresa->razao_social,
                'email' => $empresa->email,
                'phone' => $telefone, // Sempre usa como telefone fixo
                'cpfCnpj' => $cpfCnpj,
                'postalCode' => preg_replace('/[^0-9]/', '', $empresa->cep),
                'address' => $empresa->endereco,
                'addressNumber' => $empresa->numero,
                'complement' => $empresa->complemento,
                'province' => $empresa->bairro,
                'city' => $empresa->cidade,
                'state' => $empresa->estado,
                'country' => 'BR',
                'observations' => 'Cliente sincronizado via sistema'
            ];

            // Validação adicional dos dados
            if (empty($customerData['postalCode'])) {
                throw new Exception('CEP é obrigatório');
            }
            if (empty($customerData['address'])) {
                throw new Exception('Endereço é obrigatório');
            }
            if (empty($customerData['addressNumber'])) {
                throw new Exception('Número do endereço é obrigatório');
            }
            if (empty($customerData['city'])) {
                throw new Exception('Cidade é obrigatória');
            }
            if (empty($customerData['state'])) {
                throw new Exception('Estado é obrigatório');
            }

            Log::debug('Dados do cliente preparados para envio', [
                'customer_data' => array_merge(
                    $customerData,
                    ['phone' => $telefone]
                ),
                'has_asaas_id' => !empty($empresa->asaas_customer_id)
            ]);

            try {
                if ($empresa->asaas_customer_id) {
                    Log::info('Atualizando cliente existente no Asaas', ['asaas_id' => $empresa->asaas_customer_id]);
                    $response = $this->asaas->Cliente()->update($empresa->asaas_customer_id, $customerData);
                } else {
                    Log::info('Criando novo cliente no Asaas');
                    $response = $this->asaas->Cliente()->create($customerData);
                }

                Log::debug('Resposta bruta da API', [
                    'response_type' => gettype($response),
                    'response' => $response
                ]);

                if (empty($response)) {
                    throw new Exception('Resposta vazia do Asaas');
                }

                if (isset($response->errors)) {
                    $errors = [];
                    foreach ($response->errors as $error) {
                        $errors[] = $error->description ?? 'Erro desconhecido';
                    }
                    throw new Exception(implode(', ', $errors));
                }

                if (!isset($response->id)) {
                    throw new Exception('ID do cliente não retornado pelo Asaas');
                }

                // Atualiza o asaas_id na empresa
                $empresa->asaas_customer_id = $response->id;
                $empresa->save();

                Log::info('Cliente sincronizado com sucesso', [
                    'empresa_id' => $empresa->id,
                    'asaas_id' => $empresa->asaas_customer_id
                ]);

                return $response;

            } catch (Throwable $e) {
                Log::error('Erro na chamada à API do Asaas', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                    'request_data' => array_merge(
                        $customerData,
                        ['phone' => $telefone]
                    )
                ]);
                throw new Exception($e->getMessage());
            }

        } catch (Exception $e) {
            Log::error('Erro ao sincronizar cliente com Asaas', [
                'empresa_id' => $empresa->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    /**
     * Criar assinatura no Asaas
     */
    public function createSubscription(Empresa $empresa, float $valor, string $formaPagamento)
    {
        try {
            Log::info('Iniciando criação de assinatura', [
                'empresa_id' => $empresa->id,
                'valor' => $valor,
                'forma_pagamento' => $formaPagamento
            ]);

            // Validações básicas
            if ($valor <= 0) {
                throw new Exception('Valor da assinatura deve ser maior que zero');
            }

            if (!in_array($formaPagamento, $this->validBillingTypes)) {
                throw new Exception('Forma de pagamento inválida. Use: ' . implode(', ', $this->validBillingTypes));
            }

            // Sincroniza o cliente se necessário
            if (!$empresa->asaas_customer_id) {
                Log::info('Cliente não possui ID Asaas, sincronizando...');
                $this->syncCustomer($empresa);
                
                // Recarrega a empresa para garantir que temos o asaas_id atualizado
                $empresa->refresh();
            }

            if (!$empresa->asaas_customer_id) {
                throw new Exception('Não foi possível sincronizar o cliente com o Asaas');
            }

            // Prepara os dados da assinatura
            $dueDays = intval(config('asaas.defaults.due_days', 5));
            $nextDueDate = Carbon::now()->addDays($dueDays)->format('Y-m-d');
            
            $data = [
                'customer' => $empresa->asaas_customer_id,
                'billingType' => $formaPagamento,
                'value' => number_format($valor, 2, '.', ''),
                'nextDueDate' => $nextDueDate,
                'cycle' => 'MONTHLY',
                'description' => 'Assinatura ' . config('app.name'),
                'maxPayments' => 12
            ];

            // Adiciona campos específicos baseado no tipo de pagamento
            switch ($formaPagamento) {
                case 'BOLETO':
                case 'PIX':
                    $data['dueDate'] = $nextDueDate;
                    break;
                case 'CREDIT_CARD':
                    // Para cartão de crédito, vamos apenas criar a assinatura
                    // O cliente preencherá os dados do cartão depois
                    break;
            }

            Log::debug('Dados da assinatura preparados', [
                'subscription_data' => array_merge(
                    $data,
                    ['creditCard' => isset($data['creditCard']) ? '[HIDDEN]' : null]
                )
            ]);

            try {
                $response = $this->asaas->Assinatura()->create($data);
                
                Log::debug('Resposta bruta da criação de assinatura', [
                    'response_type' => gettype($response),
                    'response' => $response
                ]);

                if (empty($response)) {
                    throw new Exception('Resposta vazia do Asaas');
                }

                if (isset($response->errors)) {
                    $errors = [];
                    foreach ($response->errors as $error) {
                        $errors[] = $error->description ?? 'Erro desconhecido';
                    }
                    throw new Exception('Erro na criação da assinatura: ' . implode(', ', $errors));
                }

                if (!isset($response->id)) {
                    throw new Exception('ID da assinatura não retornado pelo Asaas');
                }

                // Atualiza os dados da empresa
                $empresa->update([
                    'asaas_subscription_id' => $response->id,
                    'assinatura_ativa' => true,
                    'assinatura_status' => $response->status ?? 'PENDING',
                    'valor_assinatura' => $valor,
                    'forma_pagamento' => $formaPagamento,
                    'data_proximo_vencimento' => $nextDueDate
                ]);

                Log::info('Assinatura criada com sucesso', [
                    'subscription_id' => $response->id,
                    'empresa_id' => $empresa->id,
                    'status' => $response->status ?? 'PENDING'
                ]);

                return $response;

            } catch (Throwable $e) {
                Log::error('Erro na chamada à API de assinatura', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                    'request_data' => $data
                ]);
                throw new Exception('Erro ao criar assinatura no Asaas: ' . $e->getMessage());
            }

        } catch (Exception $e) {
            Log::error('Erro ao criar assinatura', [
                'empresa_id' => $empresa->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    /**
     * Sincronizar cobrança do Asaas
     */
    public function syncCobranca(array $paymentData)
    {
        try {
            $empresa = Empresa::where('asaas_customer_id', $paymentData['customer'])->firstOrFail();

            $cobranca = AsaasCobranca::updateOrCreate(
                ['asaas_id' => $paymentData['id']],
                [
                    'empresa_id' => $empresa->id,
                    'valor' => $paymentData['value'],
                    'status' => $paymentData['status'],
                    'forma_pagamento' => $paymentData['billingType'],
                    'data_vencimento' => $paymentData['dueDate'],
                    'link_pagamento' => $paymentData['invoiceUrl'] ?? null,
                    'link_boleto' => $paymentData['bankSlipUrl'] ?? null,
                    'data_pagamento' => $paymentData['paymentDate'] ?? null,
                    'valor_pago' => $paymentData['netValue'] ?? null,
                ]
            );

            return $cobranca;

        } catch (Exception $e) {
            Log::error('Erro ao sincronizar cobrança', [
                'payment_data' => $paymentData,
                'error' => $e->getMessage()
            ]);
            throw new Exception('Erro ao sincronizar cobrança: ' . $e->getMessage());
        }
    }

    /**
     * Cancelar assinatura
     */
    public function cancelSubscription(Empresa $empresa)
    {
        try {
            if (!$empresa->asaas_subscription_id) {
                throw new Exception('Empresa não possui assinatura ativa');
            }

            $response = $this->asaas->Assinatura()->cancel($empresa->asaas_subscription_id);
            $responseData = is_object($response) ? json_decode(json_encode($response), true) : $response;

            Log::debug('Resposta do cancelamento de assinatura', ['response' => $responseData]);

            if (!isset($responseData['id'])) {
                $errorMessage = $this->extractErrorMessage($responseData);
                Log::error('Erro na resposta do Asaas', [
                    'error' => $errorMessage,
                    'response' => $responseData
                ]);
                throw new Exception($errorMessage);
            }

            $empresa->update([
                'status_assinatura' => 'cancelada',
                'data_proximo_vencimento' => null
            ]);

            return true;

        } catch (Exception $e) {
            Log::error('Erro ao cancelar assinatura no Asaas', [
                'empresa_id' => $empresa->id,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    /**
     * Atualizar status da empresa baseado no status do pagamento
     */
    protected function updateEmpresaStatus(Empresa $empresa, string $paymentStatus)
    {
        $status = match ($paymentStatus) {
            'RECEIVED', 'CONFIRMED', 'RECEIVED_IN_CASH' => 'ativa',
            'OVERDUE' => 'inadimplente',
            'REFUNDED' => 'pendente',
            default => $empresa->status_assinatura
        };

        if ($status !== $empresa->status_assinatura) {
            $empresa->update(['status_assinatura' => $status]);
        }
    }

    /**
     * Obter detalhes de uma assinatura
     */
    public function getSubscriptionDetails($subscriptionId)
    {
        try {
            Log::info('Buscando detalhes da assinatura', [
                'subscription_id' => $subscriptionId
            ]);

            $response = $this->asaas->Assinatura()->getById($subscriptionId);

            if (empty($response)) {
                throw new Exception('Resposta vazia do Asaas');
            }

            if (isset($response->errors)) {
                $errors = [];
                foreach ($response->errors as $error) {
                    $errors[] = $error->description ?? 'Erro desconhecido';
                }
                throw new Exception(implode(', ', $errors));
            }

            Log::debug('Resposta da assinatura:', [
                'response' => json_decode(json_encode($response), true)
            ]);

            // Se for pagamento BOLETO, buscar a URL do boleto
            if ($response->billingType === 'BOLETO') {
                try {
                    // Buscar cobranças da assinatura
                    Log::info('Buscando cobranças para BOLETO', [
                        'customer_id' => $response->customer,
                        'subscription_id' => $subscriptionId
                    ]);

                    $cobrancas = $this->asaas->Cobranca()->getByCustomer($response->customer);
                    
                    Log::debug('Cobranças encontradas:', [
                        'quantidade' => is_array($cobrancas) ? count($cobrancas) : 0,
                        'cobrancas' => json_decode(json_encode($cobrancas), true)
                    ]);

                    // Encontrar a cobrança mais recente desta assinatura
                    $cobrancaAssinatura = null;
                    if (is_array($cobrancas)) {
                        foreach ($cobrancas as $cobranca) {
                            Log::debug('Verificando cobrança:', [
                                'id' => $cobranca->id ?? 'N/A',
                                'subscription' => $cobranca->subscription ?? 'N/A',
                                'status' => $cobranca->status ?? 'N/A',
                                'billingType' => $cobranca->billingType ?? 'N/A'
                            ]);

                            if (isset($cobranca->subscription) && $cobranca->subscription === $subscriptionId) {
                                if ($cobranca->status === 'PENDING' && $cobranca->billingType === 'BOLETO') {
                                    $cobrancaAssinatura = $cobranca;
                                    Log::info('Cobrança BOLETO pendente encontrada:', [
                                        'cobranca_id' => $cobranca->id,
                                        'valor' => $cobranca->value,
                                        'status' => $cobranca->status,
                                        'billing_type' => $cobranca->billingType,
                                        'subscription_id' => $cobranca->subscription
                                    ]);
                                    break;
                                }
                            }
                        }
                    }
                    
                    if ($cobrancaAssinatura && isset($cobrancaAssinatura->id)) {
                        // Buscar URL do boleto
                        Log::info('Buscando URL do boleto para cobrança', [
                            'cobranca_id' => $cobrancaAssinatura->id
                        ]);

                        // Primeiro, vamos tentar pegar direto do objeto de cobrança
                        if (!empty($cobrancaAssinatura->bankSlipUrl)) {
                            Log::info('URL do boleto encontrada diretamente na cobrança', [
                                'url' => $cobrancaAssinatura->bankSlipUrl
                            ]);
                            $response->bankSlipUrl = $cobrancaAssinatura->bankSlipUrl;
                        } else {
                            // Se não tiver direto na cobrança, buscar via API
                            $boletoUrl = $this->getBoletoUrl($cobrancaAssinatura->id);
                            $response->bankSlipUrl = $boletoUrl;
                            Log::info('URL do boleto obtida via API', [
                                'url' => $boletoUrl
                            ]);
                        }

                        // Adicionar código de barras se disponível
                        if (!empty($cobrancaAssinatura->identificationField)) {
                            $response->identificationField = $cobrancaAssinatura->identificationField;
                        }

                        Log::info('Dados do boleto obtidos com sucesso', [
                            'tem_url' => !empty($response->bankSlipUrl),
                            'tem_codigo_barras' => !empty($response->identificationField)
                        ]);
                    } else {
                        Log::warning('Nenhuma cobrança pendente encontrada para a assinatura', [
                            'subscription_id' => $subscriptionId
                        ]);
                    }
                } catch (Exception $e) {
                    Log::error('Erro ao buscar dados do boleto', [
                        'error' => $e->getMessage(),
                        'subscription_id' => $subscriptionId,
                        'trace' => $e->getTraceAsString()
                    ]);
                    throw new Exception('Erro ao buscar dados do boleto: ' . $e->getMessage());
                }
            }

            return $response;

        } catch (Exception $e) {
            Log::error('Erro ao buscar detalhes da assinatura', [
                'subscription_id' => $subscriptionId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    /**
     * Buscar URL do boleto
     */
    public function getBoletoUrl($paymentId)
    {
        try {
            Log::info('Iniciando busca de URL do boleto', [
                'payment_id' => $paymentId
            ]);

            // Primeiro, verificar se já temos a URL salva no banco
            $cobranca = AsaasCobranca::where('asaas_id', $paymentId)->first();
            if ($cobranca && !empty($cobranca->link_boleto)) {
                Log::info('URL do boleto encontrada no banco de dados', [
                    'url' => $cobranca->link_boleto
                ]);
                return $cobranca->link_boleto;
            }

            $apiUrl = $this->getApiUrl();
            $endpoint = "/api/v3/payments/{$paymentId}/bankSlipUrl";
            
            Log::debug('Buscando URL do boleto via API:', [
                'payment_id' => $paymentId,
                'url' => $apiUrl . $endpoint
            ]);

            $curl = curl_init();
            curl_setopt_array($curl, [
                CURLOPT_URL => $apiUrl . $endpoint,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "GET",
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_VERBOSE => true,
                CURLOPT_HTTPHEADER => [
                    "Content-Type: application/json",
                    "Authorization: Bearer " . config('asaas.api_key')
                ],
            ]);

            // Capturar output detalhado do cURL
            $verbose = fopen('php://temp', 'w+');
            curl_setopt($curl, CURLOPT_STDERR, $verbose);

            $response = curl_exec($curl);
            $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            $err = curl_error($curl);
            
            // Log do output verbose do cURL
            rewind($verbose);
            $verboseLog = stream_get_contents($verbose);
            fclose($verbose);

            Log::debug('Detalhes da requisição cURL:', [
                'verbose_log' => $verboseLog,
                'http_code' => $httpCode,
                'response' => $response,
                'curl_error' => $err
            ]);
            
            curl_close($curl);

            if ($err) {
                throw new Exception("Erro ao buscar URL do boleto: " . $err);
            }

            if ($httpCode !== 200) {
                // Se o código for 404, tentar buscar os detalhes do pagamento
                if ($httpCode === 404) {
                    Log::warning('URL do boleto não encontrada, tentando buscar detalhes do pagamento', [
                        'payment_id' => $paymentId
                    ]);
                    
                    $paymentDetails = $this->asaas->Cobranca()->getById($paymentId);
                    if ($paymentDetails && !empty($paymentDetails->bankSlipUrl)) {
                        Log::info('URL do boleto encontrada nos detalhes do pagamento', [
                            'url' => $paymentDetails->bankSlipUrl
                        ]);
                        
                        // Atualizar a cobrança no banco se existir
                        if ($cobranca) {
                            $cobranca->update(['link_boleto' => $paymentDetails->bankSlipUrl]);
                        }
                        
                        return $paymentDetails->bankSlipUrl;
                    }
                }
                
                Log::error('Erro na resposta da API:', [
                    'http_code' => $httpCode,
                    'response' => $response
                ]);
                throw new Exception("Erro na API do Asaas ao buscar URL do boleto. HTTP Code: " . $httpCode);
            }

            $boletoInfo = json_decode($response);
            
            if (!$boletoInfo) {
                Log::error('Falha ao decodificar resposta JSON', [
                    'response' => $response
                ]);
                throw new Exception("Resposta inválida da API");
            }

            if (!isset($boletoInfo->bankSlipUrl)) {
                Log::error('URL do boleto não encontrada na resposta', [
                    'response' => json_decode(json_encode($boletoInfo), true)
                ]);
                throw new Exception("URL do boleto não encontrada na resposta");
            }

            // Atualizar a URL no banco de dados se a cobrança existir
            if ($cobranca) {
                $cobranca->update(['link_boleto' => $boletoInfo->bankSlipUrl]);
                Log::info('URL do boleto atualizada no banco de dados', [
                    'cobranca_id' => $cobranca->id,
                    'url' => $boletoInfo->bankSlipUrl
                ]);
            }

            Log::info('URL do boleto obtida com sucesso', [
                'url' => $boletoInfo->bankSlipUrl
            ]);

            return $boletoInfo->bankSlipUrl;

        } catch (Exception $e) {
            Log::error('Erro ao buscar URL do boleto:', [
                'payment_id' => $paymentId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    /**
     * Buscar QR Code PIX de uma cobrança
     */
    private function getPixQrCode($paymentId)
    {
        try {
            $apiUrl = $this->getApiUrl();
            
            // Primeiro busca o código de identificação
            $endpoint = "/api/v3/payments/{$paymentId}/identificationField";
            
            Log::debug('Buscando código de identificação PIX:', [
                'payment_id' => $paymentId,
                'url' => $apiUrl . $endpoint
            ]);

            $curl = curl_init();
            curl_setopt_array($curl, [
                CURLOPT_URL => $apiUrl . $endpoint,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "GET",
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_HTTPHEADER => [
                    "Content-Type: application/json",
                    "Authorization: Bearer " . config('asaas.api_key')
                ],
            ]);

            $response = curl_exec($curl);
            $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            $err = curl_error($curl);
            
            curl_close($curl);

            if ($err) {
                throw new Exception("Erro ao buscar código PIX: " . $err);
            }

            if ($httpCode !== 200) {
                throw new Exception("Erro na API do Asaas ao buscar código PIX: " . $response);
            }

            $pixInfo = json_decode($response);
            
            if (!$pixInfo || !isset($pixInfo->identificationField)) {
                throw new Exception("Código PIX não encontrado na resposta");
            }

            // Agora busca o QR Code
            $endpoint = "/api/v3/payments/{$paymentId}/pixQrCode";
            
            Log::debug('Buscando QR Code PIX:', [
                'payment_id' => $paymentId,
                'url' => $apiUrl . $endpoint
            ]);

            $curl = curl_init();
            curl_setopt_array($curl, [
                CURLOPT_URL => $apiUrl . $endpoint,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "GET",
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_HTTPHEADER => [
                    "Content-Type: application/json",
                    "Authorization: Bearer " . config('asaas.api_key')
                ],
            ]);

            $response = curl_exec($curl);
            $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            $err = curl_error($curl);
            
            curl_close($curl);

            if ($err) {
                throw new Exception("Erro ao buscar QR Code: " . $err);
            }

            if ($httpCode !== 200) {
                throw new Exception("Erro na API do Asaas ao buscar QR Code: " . $response);
            }

            $qrCodeInfo = json_decode($response);
            
            if (!$qrCodeInfo || !isset($qrCodeInfo->encodedImage)) {
                throw new Exception("QR Code não encontrado na resposta");
            }

            return [
                'qrCode' => $qrCodeInfo->encodedImage,
                'qrCodeText' => $pixInfo->identificationField
            ];

        } catch (Exception $e) {
            Log::error('Erro ao buscar QR Code PIX:', [
                'payment_id' => $paymentId,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    /**
     * Obter URL base da API do Asaas com base no ambiente
     */
    private function getApiUrl()
    {
        $environment = config('asaas.environment', 'sandbox');
        return $environment === 'production' 
            ? 'https://www.asaas.com'
            : 'https://sandbox.asaas.com';
    }
}
