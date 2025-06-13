<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chamado extends Model
{
    use HasFactory;

    // A chave primária na sua tabela é 'id', não 'id_chamado'.
    // Remova ou altere esta linha para corresponder ao seu DB.
    // Se a sua PK for 'id' (padrão do Laravel), você pode até remover esta linha.
    // Se você usa 'id_chamado' em outros locais (como foreign key em 'anexos'),
    // é porque a coluna se chama 'id' aqui e 'id_chamado' na outra tabela.
    // No seu caso, sua PK é 'id', então remova a linha abaixo ou altere para 'id'.
    // protected $primaryKey = 'id_chamado'; // Esta linha estava errada para o seu DB
    protected $primaryKey = 'id'; // Corrigido para refletir o nome real da PK no seu DB

    protected $fillable = [
        'user_id',
        'titulo',
        'descricao',
        'prioridade',
        'status',
        // Adicione outros campos que você permite mass assignment
    ];

    /**
     * Um chamado pode ter vários anexos.
     * O 'id_chamado' aqui refere-se à chave estrangeira na tabela 'anexos'.
     * O terceiro parâmetro é a chave local ('id' da tabela 'chamados').
     * É importante que 'id_chamado' seja o nome da coluna no modelo Anexo.
     */
    public function anexos()
    {
        // Se a FK em `anexos` é `id_chamado` e a PK em `chamados` é `id`:
        return $this->hasMany(Anexo::class, 'id_chamado', 'id');
    }

    /**
     * Um chamado pertence a um usuário.
     * O Laravel assume 'user_id' como a chave estrangeira por convenção.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
