<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    const ROLE_USER = 'user';
    const ROLE_TECNICO = 'tecnico';
    const ROLE_ADMIN = 'admin';




    protected $fillable = [
        'name',
        'email',
        'password',
        'role', // Adiciona o campo 'role' para preenchimento em massa
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Método auxiliar para verificar se o usuário é admin
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    // Método auxiliar para verificar se o usuário é técnico
    public function isTecnico(): bool
    {
        return $this->role === 'tecnico';
    }
}
