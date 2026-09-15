<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use Illuminate\Http\Request;

// CONTROLLER: recebe a requisição, usa o Model e devolve uma View ou redirecionamento.
class CategoriaController extends Controller
{
    // READ: busca as categorias e envia os dados para a tela de listagem.
    public function index()
    {
        $categorias = Categoria::all();
        return view('categorias.index', compact('categorias'));
    }

    // Exibe o formulário de cadastro
    public function create()
    {
        return view('categorias.create');
    }

    // CREATE: valida os campos e salva uma nova categoria.
    public function store(Request $request)
    {
        Categoria::create($this->validar($request));

        return redirect()->route('categorias.index')
            ->with('sucesso', 'Categoria cadastrada com sucesso!');
    }

    // O Laravel busca a categoria pelo ID da rota e retorna 404 se não existir.
    public function show(Categoria $categoria)
    {
        return view('categorias.show', compact('categoria'));
    }

    // Abre o formulário com os dados atuais da categoria.
    public function edit(Categoria $categoria)
    {
        return view('categorias.edit', compact('categoria'));
    }

    // UPDATE: valida e atualiza a categoria encontrada pelo Laravel.
    public function update(Request $request, Categoria $categoria)
    {
        $categoria->update($this->validar($request));

        return redirect()->route('categorias.index')
            ->with('sucesso', 'Categoria atualizada com sucesso!');
    }

    // DELETE: remove a categoria e volta para a listagem.
    public function destroy(Categoria $categoria)
    {
        $categoria->delete();

        return redirect()->route('categorias.index')
            ->with('sucesso', 'Categoria excluída com sucesso!');
    }


     //Cadastro e edição compartilham as mesmas regras.

    private function validar(Request $request)
    {
        return $request->validate([
            'nome' => ['required', 'string', 'max:100'],
            'descricao' => ['nullable', 'string', 'max:1000'],
        ], [
            'nome.required' => 'Informe o nome da categoria.',
            'nome.string' => 'O nome deve ser um texto.',
            'nome.max' => 'O nome deve ter no máximo 100 caracteres.',
            'descricao.string' => 'A descrição deve ser um texto.',
            'descricao.max' => 'A descrição deve ter no máximo 1000 caracteres.',
        ]);
    }
}
