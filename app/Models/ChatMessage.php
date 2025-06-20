<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ChatMessage extends Model
{
    use HasFactory;

    protected $fillable = ['chamado_id', 'user_id', 'mensagem'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function chamado()
    {
        return $this->belongsTo(Chamado::class);
    }
}

