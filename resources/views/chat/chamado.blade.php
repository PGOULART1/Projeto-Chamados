<x-app-layout>
    <div class="max-w-3xl mx-auto p-6">
        <h2 class="text-2xl font-semibold mb-4 text-white">Chat do Chamado #{{ $chamado->id }}</h2>

        <div class="bg-gray-800 rounded-lg p-4 h-96 overflow-y-auto mb-4 space-y-3">
            @foreach($mensagens as $msg)
                <div class="flex {{ $msg->user_id === auth()->id() ? 'justify-end' : 'justify-start' }}">
                    <div class="bg-{{ $msg->user_id === auth()->id() ? 'green' : 'gray' }}-700 text-white p-3 rounded-lg max-w-xs">
                        <div class="text-sm font-semibold">
                            {{ $msg->user->name }}
                        </div>
                        <div class="text-sm">
                            {{ $msg->mensagem }}
                        </div>
                        <div class="text-xs text-gray-300 mt-1 text-right">
                            {{ $msg->created_at->format('d/m/Y H:i') }}
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <form action="{{ route('chat.enviar', $chamado->id) }}" method="POST" class="space-y-3">
            @csrf
            <textarea name="mensagem" class="w-full p-3 rounded border border-gray-300 bg-gray-900 text-white focus:outline-none focus:ring focus:border-blue-500" rows="3" placeholder="Digite sua mensagem..." required></textarea>
            <div class="flex justify-between">
                <a href="{{ route('chamados.show', $chamado->id) }}" class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-500">Voltar</a>
                <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-500">Enviar</button>
            </div>
        </form>
    </div>
</x-app-layout>
