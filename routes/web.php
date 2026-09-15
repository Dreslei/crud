<?php

use App\Http\Controllers\CategoriaController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');

    // Cria as sete rotas do CRUD e associa cada uma ao método do controller.
    // O parâmetro singular permite ao Laravel encontrar o Model Categoria pelo ID.
    Route::resource('categorias', CategoriaController::class)
        ->parameters(['categorias' => 'categoria']);
});

require __DIR__.'/settings.php';
