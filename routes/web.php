<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChamadoController;
use App\Http\Controllers\AnexoController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::resource('chamados', ChamadoController::class); //Ela registra automaticamente sete rotas diferentes para as operações CRUD
    Route::prefix('chamados/{chamado}')->name('chamados.')->group(function () {
        Route::resource('anexos', AnexoController::class);
    // A rota para o store de anexo seria /chamados/{chamado}/anexos
    // A rota para o delete seria /chamados/{chamado}/anexos/{anexo}
    });
});

require __DIR__.'/auth.php';
