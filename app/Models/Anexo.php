<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Anexo extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_anexo'; // Se o PK da tabela 'anexos' é 'id_anexo'
    protected $fillable = ['id_chamado', 'nome_arquivo', 'file_path', 'mime_type', 'size'];

    /**
     * Um Anexo pertence a um Chamado.
     */
    public function chamado()
    {
        return $this->belongsTo(Chamado::class, 'id_chamado', 'id_chamado');
        // 'id_chamado' é a FK na tabela 'anexos'
        // 'id_chamado' é a PK na tabela 'chamados'
    }
}