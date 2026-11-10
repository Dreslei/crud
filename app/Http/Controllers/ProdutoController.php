<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Models\Produto;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ProdutoController extends Controller
{
    public function index(): View
    {
        // with() carrega as categorias junto da listagem, evitando consultas repetidas.
        $produtos = Produto::with('categoria')->orderBy('nome')->orderBy('id')->paginate(10);

        return view('produtos.index', compact('produtos'));
    }

    public function create(): View
    {
        $categorias = Categoria::orderBy('nome')->get();

        return view('produtos.create', compact('categorias'));
    }

    public function store(Request $request): RedirectResponse
    {
        Produto::create($this->validar($request));

        return redirect()->route('produtos.index')
            ->with('sucesso', 'Produto cadastrado com sucesso!');
    }

    public function show(Produto $produto): View
    {
        $produto->load('categoria', 'fichaTecnica', 'fornecedores');

        return view('produtos.show', compact('produto'));
    }

    public function edit(Produto $produto): View
    {
        $categorias = Categoria::orderBy('nome')->get();

        return view('produtos.edit', compact('produto', 'categorias'));
    }

    public function update(Request $request, Produto $produto): RedirectResponse
    {
        $produto->update($this->validar($request));

        return redirect()->route('produtos.index')
            ->with('sucesso', 'Produto atualizado com sucesso!');
    }

    public function destroy(Produto $produto): RedirectResponse
    {
        if ($produto->fichaTecnica()->exists()) {
            return redirect()->route('produtos.index')
                ->with('erro', 'Este produto possui uma ficha técnica. Exclua a ficha antes de excluir o produto.');
        }

        $produto->delete();

        return redirect()->route('produtos.index')
            ->with('sucesso', 'Produto excluído com sucesso!');
    }

    private function validar(Request $request): array
    {
        return $request->validate([
            'nome' => ['required', 'string', 'max:100'],
            'descricao' => ['nullable', 'string', 'max:1000'],
            'preco' => ['required', 'numeric', 'min:0', 'max:99999999.99', 'decimal:0,2'],
            'categoria_id' => ['required', 'integer', 'exists:categorias,id'],
        ], [
            'nome.required' => 'Informe o nome do produto.',
            'nome.string' => 'O nome deve ser um texto.',
            'nome.max' => 'O nome deve ter no máximo 100 caracteres.',
            'descricao.string' => 'A descrição deve ser um texto.',
            'descricao.max' => 'A descrição deve ter no máximo 1000 caracteres.',
            'preco.required' => 'Informe o preço do produto.',
            'preco.numeric' => 'Informe um preço válido.',
            'preco.min' => 'O preço não pode ser negativo.',
            'preco.max' => 'O preço deve ser de no máximo 99.999.999,99.',
            'preco.decimal' => 'O preço deve ter no máximo duas casas decimais.',
            'categoria_id.required' => 'Selecione uma categoria.',
            'categoria_id.integer' => 'Selecione uma categoria válida.',
            'categoria_id.exists' => 'A categoria selecionada não existe.',
        ]);
    }
}
