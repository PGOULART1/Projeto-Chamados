<?php

namespace App\Http\Controllers;

use App\Models\Anexo;
use App\Models\Chamado; // Importe o modelo Chamado
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage; // Para lidar com o armazenamento de arquivos

class AnexoController extends Controller
{
    /**
     * Exibe uma listagem de todos os anexos (pode ser filtrado por chamado).
     * @param  \App\Models\Chamado|null  $chamado
     * @return \Illuminate\Http\Response
     */
    public function index(Chamado $chamado = null)
    {
        if ($chamado) {
            $anexos = $chamado->anexos; // Supondo um relacionamento hasMany em Chamado
        } else {
            $anexos = Anexo::all(); // Ou com paginação: Anexo::paginate(10);
        }

        return view('anexos.index', compact('anexos', 'chamado'));
    }

    /**
     * Mostra o formulário para criar um novo recurso (anexo).
     * Geralmente não é um formulário separado, mas parte do formulário de criação/edição de chamado.
     * @param  \App\Models\Chamado  $chamado
     * @return \Illuminate\Http\Response
     */
    public function create(Chamado $chamado)
    {
        // Esta view pode ter um formulário para upload de anexo para um chamado específico
        return view('anexos.create', compact('chamado'));
    }

    /**
     * Armazena um recurso recém-criado no armazenamento.
     * Este método deve ser chamado quando um anexo é enviado para um Chamado específico.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Chamado  $chamado
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Chamado $chamado)
    {
        $request->validate([
            'arquivo' => 'required|file|max:5120|mimes:jpeg,png,pdf,doc,docx,zip', // Max 5MB, tipos permitidos
            // Ajuste as regras de validação conforme necessário
        ]);

        if ($request->hasFile('arquivo')) {
            $uploadedFile = $request->file('arquivo');

            // Armazena o arquivo em storage/app/public/chamados/ID_DO_CHAMADO/
            $path = $uploadedFile->store('public/chamados/' . $chamado->id_chamado, 'public'); // 'public' é o disco configurado em config/filesystems.php

            // Cria um novo registro de Anexo no banco de dados
            $anexo = new Anexo();
            $anexo->id_chamado = $chamado->id_chamado;
            $anexo->nome_arquivo = $uploadedFile->getClientOriginalName();
            $anexo->file_path = Storage::url($path); // Salva o URL público acessível
            $anexo->mime_type = $uploadedFile->getMimeType();
            $anexo->size = $uploadedFile->getSize();
            $anexo->save();

            return back()->with('success', 'Anexo enviado com sucesso!'); // Retorna para a página anterior
        }

        return back()->with('error', 'Nenhum arquivo enviado.');
    }

    /**
     * Exibe o recurso especificado (permite download do anexo).
     *
     * @param  \App\Models\Anexo  $anexo
     * @return \Illuminate\Http\Response
     */
    public function show(Anexo $anexo)
    {
        // Verifica se o arquivo existe no disco
        if (Storage::disk('public')->exists(str_replace('/storage/', '', $anexo->file_path))) {
            return Storage::disk('public')->download(str_replace('/storage/', '', $anexo->file_path), $anexo->nome_arquivo);
        }

        abort(404, 'Anexo não encontrado.');
    }

    /**
     * Mostra o formulário para editar o recurso especificado.
     * Edição de anexo geralmente não é permitida, apenas substituição ou exclusão.
     * @param  \App\Models\Anexo  $anexo
     * @return \Illuminate\Http\Response
     */
    public function edit(Anexo $anexo)
    {
        // Esta função pode não ser usada para anexos.
        // Se você quiser permitir a substituição de um anexo, pode ter um formulário aqui.
        return view('anexos.edit', compact('anexo'));
    }

    /**
     * Atualiza o recurso especificado no armazenamento.
     * Geralmente não é usada para "atualizar" um anexo, mas sim para substituí-lo.
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Anexo  $anexo
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Anexo $anexo)
    {
        // Se você quiser permitir a substituição de um anexo:
        $request->validate([
            'arquivo' => 'required|file|max:5120|mimes:jpeg,png,pdf,doc,docx,zip',
        ]);

        // Apaga o anexo antigo do disco
        Storage::disk('public')->delete(str_replace('/storage/', '', $anexo->file_path));

        // Salva o novo arquivo
        $uploadedFile = $request->file('arquivo');
        $path = $uploadedFile->store('public/chamados/' . $anexo->chamado->id_chamado, 'public'); // Acessa o chamado via relacionamento

        // Atualiza os dados do anexo no banco de dados
        $anexo->nome_arquivo = $uploadedFile->getClientOriginalName();
        $anexo->file_path = Storage::url($path);
        $anexo->mime_type = $uploadedFile->getMimeType();
        $anexo->size = $uploadedFile->getSize();
        $anexo->save();

        return back()->with('success', 'Anexo atualizado com sucesso!');
    }

    /**
     * Remove o recurso especificado do armazenamento.
     *
     * @param  \App\Models\Anexo  $anexo
     * @return \Illuminate\Http\Response
     */
    public function destroy(Anexo $anexo)
    {
        // Remove o arquivo do sistema de arquivos
        Storage::disk('public')->delete(str_replace('/storage/', '', $anexo->file_path));

        // Remove o registro do banco de dados
        $anexo->delete();

        return back()->with('success', 'Anexo removido com sucesso!');
    }
}