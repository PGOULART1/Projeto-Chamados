<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setor;

class SetorController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
        ]);

        Setor::create([
            'nome' => $request->nome,
        ]);

        return back()->with('success', 'Setor cadastrado com sucesso!');
    }
}

