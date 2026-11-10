<?php

namespace App\Http\Controllers;

use App\Models\Fornecedor;
use App\Models\Produto;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FornecedorController extends Controller
{
    public function index(): View
    {
        $fornecedores = Fornecedor::withCount('produtos')->orderBy('nome')->orderBy('id')->paginate(10);

        return view('fornecedores.index', compact('fornecedores'));
    }

    public function create(): View
    {
        $produtos = Produto::orderBy('nome')->get();

        return view('fornecedores.create', compact('produtos'));
    }

    public function store(Request $request): RedirectResponse
    {
        $dados = $this->validar($request);
        $produtos = $dados['produtos'] ?? [];
        unset($dados['produtos']);

        // Salva o fornecedor e seus vínculos juntos; se falhar, desfaz a operação.
        DB::transaction(function () use ($dados, $produtos) {
            $fornecedor = Fornecedor::create($dados);
            $fornecedor->produtos()->sync($produtos);
        });

        return redirect()->route('fornecedores.index')
            ->with('sucesso', 'Fornecedor cadastrado com sucesso!');
    }

    public function show(Fornecedor $fornecedor): View
    {
        $fornecedor->load('produtos');

        return view('fornecedores.show', compact('fornecedor'));
    }

    public function edit(Fornecedor $fornecedor): View
    {
        $fornecedor->load('produtos');
        $produtos = Produto::orderBy('nome')->get();

        return view('fornecedores.edit', compact('fornecedor', 'produtos'));
    }

    public function update(Request $request, Fornecedor $fornecedor): RedirectResponse
    {
        $dados = $this->validar($request);
        $produtos = $dados['produtos'] ?? [];
        unset($dados['produtos']);

        DB::transaction(function () use ($fornecedor, $dados, $produtos) {
            $fornecedor->update($dados);
            // sync() mantém os marcados e remove os vínculos desmarcados.
            $fornecedor->produtos()->sync($produtos);
        });

        return redirect()->route('fornecedores.index')
            ->with('sucesso', 'Fornecedor atualizado com sucesso!');
    }

    public function destroy(Fornecedor $fornecedor): RedirectResponse
    {
        // A FK remove apenas os vínculos da tabela intermediária; os produtos continuam.
        $fornecedor->delete();

        return redirect()->route('fornecedores.index')
            ->with('sucesso', 'Fornecedor excluído com sucesso!');
    }

    /** @return array<string, mixed> */
    private function validar(Request $request): array
    {
        return $request->validate([
            'nome' => ['required', 'string', 'max:100'],
            'email' => ['nullable', 'email', 'max:255'],
            'telefone' => ['nullable', 'string', 'max:30'],
            'produtos' => ['sometimes', 'array'],
            'produtos.*' => ['required', 'integer', 'distinct', 'exists:produtos,id'],
        ], [
            'nome.required' => 'Informe o nome do fornecedor.',
            'nome.string' => 'O nome deve ser um texto.',
            'nome.max' => 'O nome deve ter no máximo 100 caracteres.',
            'email.email' => 'Informe um e-mail válido.',
            'email.max' => 'O e-mail deve ter no máximo 255 caracteres.',
            'telefone.string' => 'O telefone deve ser um texto.',
            'telefone.max' => 'O telefone deve ter no máximo 30 caracteres.',
            'produtos.array' => 'Selecione os produtos usando as opções disponíveis.',
            'produtos.*.required' => 'Selecione um produto válido.',
            'produtos.*.integer' => 'Selecione um produto válido.',
            'produtos.*.distinct' => 'Não selecione o mesmo produto mais de uma vez.',
            'produtos.*.exists' => 'Um dos produtos selecionados não existe.',
        ]);
    }
}
