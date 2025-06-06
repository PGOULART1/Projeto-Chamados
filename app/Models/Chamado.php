<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chamado extends Model
{
    use HasFactory;
    protected $primaryKey = 'id_chamado';
    protected $fillable = [
        'id_chamado', // Adicione 'user_id' aqui
        'titulo',
        'descricao',
        'prioridade',
        'status', // Se você permitir que o status seja definido na criação
        // Adicione outros campos que você permite mass assignment
    ];

    public function anexos()
    {
        return $this->hasMany(Anexo::class, 'id_chamado', 'id_chamado');
        // 'id_chamado' é a FK na tabela 'anexos'
        // 'id_chamado' é a PK na tabela 'chamados'
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}