<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Models\Produto;
use Illuminate\Http\Request;

class ProdutoController extends Controller
{
    public function index()
    {
        $produtos = Produto::with('categoria')->get();

        return view('produtos.index', compact('produtos'));
    }

    public function create()
    {
        $categorias = Categoria::orderBy('nome')->get();

        return view('produtos.create', compact('categorias'));
    }

    public function store(Request $request)
    {
        Produto::create($this->validar($request));

        return redirect()->route('produtos.index')
            ->with('sucesso', 'Produto cadastrado com sucesso!');
    }

    public function show(Produto $produto)
    {
        $produto->load('categoria');

        return view('produtos.show', compact('produto'));
    }

    public function edit(Produto $produto)
    {
        $categorias = Categoria::orderBy('nome')->get();

        return view('produtos.edit', compact('produto', 'categorias'));
    }

    public function update(Request $request, Produto $produto)
    {
        $produto->update($this->validar($request));

        return redirect()->route('produtos.index')
            ->with('sucesso', 'Produto atualizado com sucesso!');
    }

    public function destroy(Produto $produto)
    {
        $produto->delete();

        return redirect()->route('produtos.index')
            ->with('sucesso', 'Produto excluído com sucesso!');
    }

    private function validar(Request $request)
    {
        return $request->validate([
            'nome' => ['required', 'string', 'max:100'],
            'descricao' => ['nullable', 'string', 'max:1000'],
            'preco' => ['required', 'numeric', 'decimal:0,2', 'min:0', 'max:99999999.99'],
            'categoria_id' => ['required', 'integer', 'exists:categorias,id'],
        ], [
            'nome.required' => 'Informe o nome do produto.',
            'nome.string' => 'O nome deve ser um texto.',
            'nome.max' => 'O nome deve ter no máximo 100 caracteres.',
            'descricao.string' => 'A descrição deve ser um texto.',
            'descricao.max' => 'A descrição deve ter no máximo 1000 caracteres.',
            'preco.required' => 'Informe o preço do produto.',
            'preco.numeric' => 'O preço deve ser um número.',
            'preco.decimal' => 'O preço deve ter no máximo duas casas decimais.',
            'preco.min' => 'O preço não pode ser negativo.',
            'preco.max' => 'O preço deve ser no máximo 99999999.99.',
            'categoria_id.required' => 'Selecione uma categoria.',
            'categoria_id.integer' => 'Selecione uma categoria válida.',
            'categoria_id.exists' => 'A categoria selecionada não existe.',
        ]);
    }
}
