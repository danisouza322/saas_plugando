<div>
    <!-- Lista de comentários -->
    <div class="card">
        <div class="card-body">
            <h5 class="card-title mb-4">Comentários</h5>

            <!-- Campo para novo comentário -->
            <div class="row g-3 mb-4">
                <div class="col-12">
                    <textarea class="form-control" 
                        rows="3" 
                        wire:model="novoComentario"
                        placeholder="{{ $respondendoA ? 'Responder comentário...' : 'Adicionar um comentário...' }}"></textarea>
                </div>
                @if($respondendoA)
                    <div class="col-12">
                        <button class="btn btn-sm btn-light" wire:click="cancelarResposta">
                            <i class="ri-close-line align-bottom"></i> Cancelar resposta
                        </button>
                    </div>
                @endif
                <div class="col-12">
                    <div class="d-flex justify-content-between align-items-center">
                        <input type="file" class="form-control form-control-sm w-auto" wire:model="anexos" multiple>
                        <button class="btn btn-primary" wire:click="salvarComentario">
                            {{ $respondendoA ? 'Responder' : 'Comentar' }}
                        </button>
                    </div>
                </div>
            </div>

            <!-- Lista de comentários -->
            @forelse($comentarios as $comentario)
                <div class="d-flex mb-4">
                    <div class="flex-shrink-0">
                        <div class="avatar-xs">
                            @if($comentario->user->profile_photo_url)
                                <img src="{{ $comentario->user->profile_photo_url }}" alt="" class="rounded-circle img-fluid">
                            @else
                                <span class="avatar-title rounded-circle bg-light text-primary">
                                    {{ substr($comentario->user->name, 0, 1) }}
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h5 class="fs-13">
                            {{ $comentario->user->name }}
                            <small class="text-muted">{{ $comentario->created_at->diffForHumans() }}</small>
                        </h5>
                        <p class="text-muted">{{ $comentario->conteudo }}</p>
                        
                        <!-- Anexos -->
                        @if($comentario->anexos)
                            <div class="row g-2 mb-3">
                                @foreach($comentario->anexos as $anexo)
                                    <div class="col-auto">
                                        <a href="{{ Storage::url($anexo['path']) }}" target="_blank" class="btn btn-soft-primary btn-sm">
                                            <i class="ri-file-text-line align-bottom"></i> {{ $anexo['nome'] }}
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        @endif

                        <!-- Ações -->
                        <div class="d-flex gap-2">
                            <button class="btn btn-link p-0" wire:click="responderA({{ $comentario->id }})">
                                Responder
                            </button>
                            @if(Auth::id() === $comentario->user_id || Auth::user()->can('excluir_comentarios'))
                                <button class="btn btn-link text-danger p-0" wire:click="excluirComentario({{ $comentario->id }})">
                                    Excluir
                                </button>
                            @endif
                        </div>

                        <!-- Respostas -->
                        @foreach($comentario->respostas as $resposta)
                            <div class="d-flex mt-4">
                                <div class="flex-shrink-0">
                                    <div class="avatar-xs">
                                        @if($resposta->user->profile_photo_url)
                                            <img src="{{ $resposta->user->profile_photo_url }}" alt="" class="rounded-circle img-fluid">
                                        @else
                                            <span class="avatar-title rounded-circle bg-light text-primary">
                                                {{ substr($resposta->user->name, 0, 1) }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h5 class="fs-13">
                                        {{ $resposta->user->name }}
                                        <small class="text-muted">{{ $resposta->created_at->diffForHumans() }}</small>
                                    </h5>
                                    <p class="text-muted">{{ $resposta->conteudo }}</p>

                                    <!-- Anexos da resposta -->
                                    @if($resposta->anexos)
                                        <div class="row g-2 mb-3">
                                            @foreach($resposta->anexos as $anexo)
                                                <div class="col-auto">
                                                    <a href="{{ Storage::url($anexo['path']) }}" target="_blank" class="btn btn-soft-primary btn-sm">
                                                        <i class="ri-file-text-line align-bottom"></i> {{ $anexo['nome'] }}
                                                    </a>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif

                                    <!-- Ações da resposta -->
                                    @if(Auth::id() === $resposta->user_id || Auth::user()->can('excluir_comentarios'))
                                        <button class="btn btn-link text-danger p-0" wire:click="excluirComentario({{ $resposta->id }})">
                                            Excluir
                                        </button>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @empty
                <div class="text-center text-muted">
                    Nenhum comentário ainda. Seja o primeiro a comentar!
                </div>
            @endforelse
        </div>
    </div>
</div>
