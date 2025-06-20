<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h1>Olá, {{ Auth::user()->name }}!</h1>
                    <p class="mt-2">Bem-vindo ao seu painel de controle.</p>

                    <div class="mt-8 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        <a href="{{ route('chamados.create') }}"
                            class="bg-green-500 hover:bg-green-700 text-white font-bold py-4 px-6 rounded-md focus:outline-none focus:shadow-outline">
                            Criar Novo Chamado
                        </a>
                        <a href="{{ route('chamados.index') }}"
                            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-4 px-6 rounded-md focus:outline-none focus:shadow-outline">
                            Ver Meus Chamados
                        </a>
                        <a href="{{ route('profile.edit') }}"
                            class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-4 px-6 rounded-md focus:outline-none focus:shadow-outline">
                            Editar Perfil
                        </a>
                        @if(session('success'))
                        <div class="alert alert-success mt-3">
                            {{ session('success') }}
                        </div>
                    @endif

                    <form action="{{ route('setores.store') }}" method="POST" class="mt-4">
                        @csrf
                        <label for="nome" class="block text-sm font-medium text-white">Cadastrar novo setor</label>
                        <div class="flex mt-2">
                            <input type="text" name="nome" id="nome"
                                class="rounded-l-md p-2 w-full border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500"
                                placeholder="Nome do setor" required>
                            <button type="submit"
                                class="bg-indigo-600 text-white px-4 rounded-r-md hover:bg-indigo-700 transition">
                                Cadastrar
                            </button>
                        </div>
                        @error('nome')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </form>
                    </div>
                    


                    {{-- <h3 class="mt-8 font-semibold text-lg">Seus Chamados Recentes</h3>
                    @if ($chamados->isNotEmpty())
                    <ul>
                        @foreach ($chamados as $chamado)
                        <li><a href="{{ route('chamados.show', $chamado->id) }}">{{ $chamado->titulo }}</a> - {{
                            $chamado->status }}</li>
                        @endforeach
                    </ul>
                    @else
                    <p>Você ainda não possui chamados.</p>
                    @endif --}}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>