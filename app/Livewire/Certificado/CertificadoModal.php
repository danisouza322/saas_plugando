<?php

namespace App\Livewire\Certificado;

use App\Models\Certificado;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;

class CertificadoModal extends Component
{
    use WithFileUploads;

    public $certificadoId;
    public $certificado = [
        'empresa_id' => '',
        'nome' => '',
        'tipo' => '',
        'cnpj_cpf' => '',
        'data_vencimento' => '',
        'senha' => ''
    ];
    public $arquivo;
    public $tentarLerCertificado = false;
    public $senhaInvalida = false;

    protected $listeners = ['showModal', 'lerCertificado'];

    protected $rules = [
        'certificado.nome' => 'required|min:3',
        'certificado.tipo' => 'required|in:CPF,CNPJ',
        'certificado.cnpj_cpf' => 'required',
        'certificado.data_vencimento' => 'required|date',
        'certificado.senha' => 'nullable|min:4',
        'arquivo' => 'nullable|file|max:10240' // máximo 10MB
    ];

    public function mount()
    {
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->certificadoId = null;
        $this->certificado = [
            'empresa_id' => Auth::user()->empresa_id,
            'nome' => '',
            'tipo' => '',
            'cnpj_cpf' => '',
            'data_vencimento' => '',
            'senha' => ''
        ];
        $this->arquivo = null;
        $this->tentarLerCertificado = false;
        $this->senhaInvalida = false;
    }

    public function showModal($certificadoId = null)
    {
        $this->resetForm();
        
        if (isset($certificadoId['certificadoId'])) {
            $certificadoId = $certificadoId['certificadoId'];
        }
        
        if ($certificadoId) {
            $certificado = Certificado::where('empresa_id', Auth::user()->empresa_id)
                                    ->findOrFail($certificadoId);
            $this->certificadoId = $certificadoId;
            $this->certificado = [
                'empresa_id' => $certificado->empresa_id,
                'nome' => $certificado->nome,
                'tipo' => $certificado->tipo,
                'cnpj_cpf' => $certificado->cnpj_cpf,
                'data_vencimento' => $certificado->data_vencimento->format('Y-m-d'),
                'senha' => $certificado->senha
            ];
        }
    }

    public function lerCertificado()
    {
        if (!$this->arquivo || empty($this->certificado['senha'])) {
            return;
        }

        $this->tentarLerCertificado = true;
        $this->senhaInvalida = false;

        try {
            $tempPath = $this->arquivo->getRealPath();
            $pkcs12 = file_get_contents($tempPath);
            
            if (openssl_pkcs12_read($pkcs12, $certs, $this->certificado['senha'])) {
                $dados = openssl_x509_parse(openssl_x509_read($certs['cert']));
                
                // Preenche o nome com o CN (Common Name)
                if (isset($dados['subject']['CN'])) {
                    $nomeCompleto = $dados['subject']['CN'];
                    
                    // Divide o nome no caractere ':'
                    $partes = explode(':', $nomeCompleto);
                    
                    // O nome é a primeira parte
                    $this->certificado['nome'] = trim($partes[0]);
                    
                    // Se houver uma segunda parte, verifica se é CNPJ
                    if (isset($partes[1])) {
                        $documento = preg_replace('/[^0-9]/', '', $partes[1]);
                        
                        // Se tiver 14 dígitos, é CNPJ
                        if (strlen($documento) === 14) {
                            $this->certificado['tipo'] = 'CNPJ';
                            $this->certificado['cnpj_cpf'] = $documento;
                        }
                        // Se tiver 11 dígitos, é CPF
                        elseif (strlen($documento) === 11) {
                            $this->certificado['tipo'] = 'CPF';
                            $this->certificado['cnpj_cpf'] = $documento;
                        }
                    }
                }
                
                // Preenche a data de vencimento
                if (isset($dados['validTo_time_t'])) {
                    $this->certificado['data_vencimento'] = date('Y-m-d', $dados['validTo_time_t']);
                }

                $this->tentarLerCertificado = false;
            } else {
                $this->senhaInvalida = true;
                $this->tentarLerCertificado = false;
            }
        } catch (\Exception $e) {
            $this->addError('arquivo', 'Erro ao ler o certificado: ' . $e->getMessage());
            $this->tentarLerCertificado = false;
        }
    }

    public function updatedArquivo()
    {
        $this->reset(['tentarLerCertificado', 'senhaInvalida']);
        $this->certificado['senha'] = '';
        $this->certificado['nome'] = '';
        $this->certificado['tipo'] = '';
        $this->certificado['cnpj_cpf'] = '';
        $this->certificado['data_vencimento'] = '';
        $this->resetErrorBag();
    }

    public function updatedCertificadoTipo()
    {
        // Limpa o campo cnpj_cpf quando o tipo é alterado
        $this->certificado['cnpj_cpf'] = '';
    }

    public function updatedCertificadoCnpjCpf()
    {
        // Remove caracteres não numéricos
        $this->certificado['cnpj_cpf'] = preg_replace('/[^0-9]/', '', $this->certificado['cnpj_cpf']);
    }

    public function updatedCertificadoSenha()
    {
        if ($this->arquivo && !empty($this->certificado['senha'])) {
            $this->senhaInvalida = false;
            $this->resetErrorBag('certificado.senha');
            $this->lerCertificado();
        }
    }

    public function save()
    {
        $this->validate();

        try {
            if ($this->arquivo) {
                $path = $this->arquivo->store('certificados', 'public');
                $this->certificado['arquivo_path'] = $path;
            }

            $this->certificado['empresa_id'] = Auth::user()->empresa_id;

            if ($this->certificadoId) {
                $certificado = Certificado::where('empresa_id', Auth::user()->empresa_id)
                                        ->findOrFail($this->certificadoId);
                $certificado->update($this->certificado);
            } else {
                Certificado::create($this->certificado);
            }

            session()->flash('message', 'Certificado salvo com sucesso!');
            $this->dispatch('certificadoSaved');
            $this->dispatch('refresh-list');
            $this->dispatch('closeModal');
            $this->resetForm();
            
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            session()->flash('error', 'Erro ao salvar certificado: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function render()
    {
        return view('livewire.certificado.certificado-modal');
    }
}
