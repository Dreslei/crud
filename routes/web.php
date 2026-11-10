<?php

use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\FichaTecnicaController;
use App\Http\Controllers\FornecedorController;
use App\Http\Controllers\ProdutoController;
use App\Models\Categoria;
use App\Models\Fornecedor;
use App\Models\Produto;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', function () {
        return view('dashboard', [
            'totalProdutos' => Produto::count(),
            'totalCategorias' => Categoria::count(),
            'totalFornecedores' => Fornecedor::count(),
        ]);
    })->name('dashboard');
    Route::resource('categorias', CategoriaController::class);
    Route::resource('produtos', ProdutoController::class);
    Route::resource('fornecedores', FornecedorController::class)
        ->parameters(['fornecedores' => 'fornecedor']);
    Route::resource('fichas-tecnicas', FichaTecnicaController::class)
        ->parameters(['fichas-tecnicas' => 'fichaTecnica']);
});

require __DIR__.'/settings.php';
