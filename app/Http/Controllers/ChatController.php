<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Chamado;
use App\Models\ChatMessage;

class ChatController extends Controller
{
    //
    public function abrirChat($id)
    {
        $chamado = Chamado::findOrFail($id);

        // Verificar se o usuário tem permissão para acessar o chat
        if (
            auth()->user()->id !== $chamado->user_id &&
            auth()->user()->id !== $chamado->tecnico_id &&
            !auth()->user()->isAdmin()
        ) {
            abort(403, 'Você não tem permissão para acessar esse chat.');
        }

        // Buscar mensagens relacionadas ao chamado
        $mensagens = ChatMessage::where('chamado_id', $id)
            ->orderBy('created_at')
            ->get();

        return view('chat.chamado', compact('chamado', 'mensagens'));
    }

    public function enviarMensagem(Request $request, $id)
    {
        $request->validate([
            'mensagem' => 'required|string|max:1000',
        ]);

        $chamado = Chamado::findOrFail($id);

        ChatMessage::create([
            'chamado_id' => $chamado->id,
            'user_id' => auth()->id(),
            'mensagem' => $request->mensagem,
        ]);

        return redirect()->route('chat.chamado', $id);
    }


}
