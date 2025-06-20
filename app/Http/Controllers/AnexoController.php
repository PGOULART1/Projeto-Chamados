<?php

namespace App\Http\Controllers;

use App\Models\Anexo;
use App\Models\Chamado; // Importe o modelo Chamado
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage; // Para lidar com o armazenamento de arquivos
use Illuminate\Http\RedirectResponse; // Para tipos de retorno explícitos
use Illuminate\View\View; // Para tipos de retorno explícitos

class AnexoController extends Controller
{
    /**
     * Exibe uma listagem de todos os anexos (pode ser filtrado por chamado).
     *
     * @param  \App\Models\Chamado|null  $chamado
     * @return \Illuminate\View\View
     */
    public function index(Chamado $chamado = null): View
    {
        // Se um chamado for fornecido, pega os anexos relacionados a ele.
        // Caso contrário, pega todos os anexos com paginação para evitar carregar muitos dados.
        if ($chamado) {
            $anexos = $chamado->anexos()->latest()->paginate(10); // Adicionado ->latest() e paginação
        } else {
            $anexos = Anexo::latest()->paginate(10); // Adicionado ->latest() e paginação
        }

        return view('anexos.index', compact('anexos', 'chamado'));
    }

    /**
     * Mostra o formulário para criar um novo recurso (anexo).
     * Geralmente não é um formulário separado, mas parte do formulário de criação/edição de chamado.
     *
     * @param  \App\Models\Chamado  $chamado
     * @return \Illuminate\View\View
     */
    public function create(Chamado $chamado): View
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
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request, Chamado $chamado): RedirectResponse
    {
        $request->validate([
            'arquivo' => 'required|file|max:5120|mimes:jpeg,png,pdf,doc,docx,zip', // Max 5MB, tipos permitidos
            // Ajuste as regras de validação conforme necessário.
            // Certifique-se de que o 'id_chamado' no Anexo está preenchido, o que já está sendo feito abaixo.
        ]);

        if ($request->hasFile('arquivo')) {
            $uploadedFile = $request->file('arquivo');

            // Gera um nome único para o arquivo para evitar colisões
            $fileName = time() . '_' . uniqid() . '.' . $uploadedFile->getClientOriginalExtension();

            // Armazena o arquivo em storage/app/public/chamados/ID_DO_CHAMADO/
            // Usa o nome de arquivo gerado
            $path = $uploadedFile->storeAs('chamados/' . $chamado->id_chamado, $fileName, 'public');

            // Cria um novo registro de Anexo no banco de dados
            $anexo = new Anexo();
            $anexo->id_chamado = $chamado->id_chamado;
            $anexo->nome_arquivo = $uploadedFile->getClientOriginalName(); // Nome original para exibição
            $anexo->file_path = $path; // Salva o caminho relativo diretamente
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
     * @return \Symfony\Component\HttpFoundation\StreamedResponse|\Illuminate\Http\Response
     */
    public function show(Anexo $anexo)
    {
        // Remove a parte '/storage/' do caminho para verificar no disco 'public'
        $filePathOnDisk = str_replace('/storage/', '', $anexo->file_path);

        // Verifica se o arquivo existe no disco
        if (Storage::disk('public')->exists($filePathOnDisk)) {
            return Storage::disk('public')->download($filePathOnDisk, $anexo->nome_arquivo);
        }

        abort(404, 'Anexo não encontrado ou não acessível.'); // Mensagem mais descritiva
    }

    /**
     * Mostra o formulário para editar o recurso especificado.
     * Edição de anexo geralmente não é permitida, apenas substituição ou exclusão.
     * @param  \App\Models\Anexo  $anexo
     * @return \Illuminate\View\View
     */
    public function edit(Anexo $anexo): View
    {
        // Esta função pode não ser usada para anexos.
        // Se você quiser permitir a substituição de um anexo, pode ter um formulário aqui.
        return view('anexos.edit', compact('anexo'));
    }

    /**
     * Atualiza o recurso especificado no armazenamento.
     * Geralmente não é usada para "atualizar" um anexo, mas sim para substituí-lo.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Anexo  $anexo
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Anexo $anexo): RedirectResponse
    {
        // Se você quiser permitir a substituição de um anexo:
        $request->validate([
            'arquivo' => 'required|file|max:5120|mimes:jpeg,png,pdf,doc,docx,zip',
        ]);

        if ($request->hasFile('arquivo')) {
            // Apaga o anexo antigo do disco
            Storage::disk('public')->delete(str_replace('/storage/', '', $anexo->file_path));

            $uploadedFile = $request->file('arquivo');
            $fileName = time() . '_' . uniqid() . '.' . $uploadedFile->getClientOriginalExtension();

            // Salva o novo arquivo, acessando o ID do chamado via relacionamento
            // Certifique-se que o relacionamento 'chamado' está definido no modelo Anexo
            $path = $uploadedFile->storeAs('public/chamados/' . $anexo->chamado->id_chamado, $fileName, 'public');

            // Atualiza os dados do anexo no banco de dados
            $anexo->nome_arquivo = $uploadedFile->getClientOriginalName();
            $anexo->file_path = Storage::url($path);
            $anexo->mime_type = $uploadedFile->getMimeType();
            $anexo->size = $uploadedFile->getSize();
            $anexo->save();

            return back()->with('success', 'Anexo atualizado com sucesso!');
        }

        return back()->with('error', 'Nenhum arquivo enviado para atualização.');
    }

    /**
     * Remove o recurso especificado do armazenamento.
     *
     * @param  \App\Models\Anexo  $anexo
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Anexo $anexo): RedirectResponse
    {
        // Remove o arquivo do sistema de arquivos
        Storage::disk('public')->delete(str_replace('/storage/', '', $anexo->file_path));

        // Remove o registro do banco de dados
        $anexo->delete();

        return back()->with('success', 'Anexo removido com sucesso!');
    }
}
