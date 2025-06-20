<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Detalhes do Chamado') }} #{{ $chamado->id }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8"> {{-- max-w-xl para detalhes --}}
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    <div class="mb-4">
                        <h3 class="text-lg font-bold text-gray-700 dark:text-gray-300">{{ __('Título:') }}</h3>
                        <p class="mt-1 text-gray-900 dark:text-gray-100">{{ $chamado->titulo }}</p>
                    </div>

                    <div class="mb-4">
                        <h3 class="text-lg font-bold text-gray-700 dark:text-gray-300">{{ __('Descrição:') }}</h3>
                        <p class="mt-1 text-gray-900 dark:text-gray-100">{{ $chamado->descricao }}</p>
                    </div>

                    <div class="mb-4">
                        <h3 class="text-lg font-bold text-gray-700 dark:text-gray-300">{{ __('Prioridade:') }}</h3>
                        <span class="px-2 inline-flex text-sm leading-5 font-semibold rounded-full
                            @if($chamado->prioridade == 'alta') bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300
                            @elseif($chamado->prioridade == 'media') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300
                            @else bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300 @endif">
                            {{ ucfirst($chamado->prioridade) }}
                        </span>
                    </div>

                    <div class="mb-4">
                        <h3 class="text-lg font-bold text-gray-700 dark:text-gray-300">{{ __('Status:') }}</h3>
                        <span class="px-2 inline-flex text-sm leading-5 font-semibold rounded-full
                            @if($chamado->status == 'aberto') bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300
                            @elseif($chamado->status == 'em andamento') bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-300
                            @else bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-300 @endif">
                            {{ ucfirst($chamado->status ?? 'Não Definido') }}
                        </span>
                    </div>

                    <h1>Detalhes do Chamado: {{ $chamado->titulo }}</h1>

                    <p><strong>Descrição:</strong> {{ $chamado->descricao }}</p>

                    <p><strong>Setor:</strong> {{ $chamado->setor->nome }}</p>

                    @if ($chamado->anexos->count() > 0)
                        <h2>Anexos:</h2>
                        <div style="display: flex; flex-wrap: wrap; gap: 20px; margin-top: 15px;">
                            @foreach ($chamado->anexos as $anexo)
                                <div style="border: 1px solid #e0e0e0; padding: 10px; border-radius: 5px; text-align: center;">
                                    {{-- Verifica se o tipo MIME indica que é uma imagem --}}
                                    @if (Str::startsWith($anexo->mime_type, 'image/'))
                                        <img src="{{ asset('storage/' . $anexo->file_path) }}" alt="{{ $anexo->nome_arquivo }}"
                                            style="max-width: 250px; height: auto; display: block; margin-bottom: 10px;">
                                        <p style="font-size: 0.9em; color: #555;">{{ $anexo->nome_arquivo }}</p>
                                    @else
                                        {{-- Para outros tipos de arquivos, exibe um ícone e um link para download --}}
                                        <p style="margin-bottom: 5px;">
                                            <span style="font-size: 2em; display: block;">📄</span> {{-- Ícone de documento, pode
                                            ser melhorado com Font Awesome etc. --}}
                                            <a href="{{ asset('storage/' . $anexo->file_path) }}" target="_blank"
                                                style="text-decoration: none; color: #007bff;">
                                                {{ $anexo->nome_arquivo }}
                                            </a>
                                        </p>
                                        <p style="font-size: 0.8em; color: #888;">({{ Str::afterLast($anexo->mime_type, '/') }})</p>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p>Nenhum anexo para este chamado.</p>
                    @endif

                    <a href="{{ route('chamados.index') }}"
                        style="display: inline-block; margin-top: 30px; padding: 8px 15px; background-color: #6c757d; color: white; text-decoration: none; border-radius: 4px;">Voltar
                        para a Lista</a>


                    <div class="mb-4">
                        <h3 class="text-lg font-bold text-gray-700 dark:text-gray-300">{{ __('Criado Em:') }}</h3>
                        <p class="mt-1 text-gray-900 dark:text-gray-100">{{ $chamado->created_at->format('d/m/Y H:i') }}
                        </p>
                    </div>

                    <div class="mb-6">
                        <h3 class="text-lg font-bold text-gray-700 dark:text-gray-300">{{ __('Última Atualização:') }}
                        </h3>
                        <p class="mt-1 text-gray-900 dark:text-gray-100">{{ $chamado->updated_at->format('d/m/Y H:i') }}
                        </p>
                    </div>

                    <div class="flex justify-between items-center mt-6">
                        <a href="{{ route('chamados.index') }}"
                            class="inline-flex items-center px-4 py-2 bg-gray-300 dark:bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest hover:bg-gray-400 dark:hover:bg-gray-500 focus:bg-gray-400 dark:focus:bg-gray-500 active:bg-gray-500 dark:active:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                            {{ __('Voltar para a Lista') }}
                        </a>
                        <a href="{{ route('chamados.edit', $chamado) }}"
                            class="inline-flex items-center px-4 py-2 bg-yellow-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-yellow-600 focus:bg-yellow-600 active:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                            {{ __('Editar Chamado') }}
                        </a>
                        <a href="{{ route('chat.chamado', $chamado->id) }}" class="btn btn-primary"
                            style="background-color: #3b82f6; border: none;">
                            💬 Chat
                        </a>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>