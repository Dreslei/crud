<?php

namespace App\Http\Controllers;

use App\Models\FichaTecnica;
use App\Models\Produto;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class FichaTecnicaController extends Controller
{
    public function index(): View
    {
        return view('fichas-tecnicas.index');
    }

    public function create(): View
    {
        // Só oferece produtos que ainda não possuem ficha técnica.
        $produtos = Produto::doesntHave('fichaTecnica')->orderBy('nome')->get();

        return view('fichas-tecnicas.create', compact('produtos'));
    }

    public function store(Request $request): RedirectResponse
    {
        FichaTecnica::create($this->validar($request));

        return redirect()->route('fichas-tecnicas.index')
            ->with('sucesso', 'Ficha técnica cadastrada com sucesso!');
    }

    public function show(FichaTecnica $fichaTecnica): View
    {
        $fichaTecnica->load('produto');

        return view('fichas-tecnicas.show', compact('fichaTecnica'));
    }

    public function edit(FichaTecnica $fichaTecnica): View
    {
        // Na edição, inclui também o produto da própria ficha.
        $produtos = Produto::whereDoesntHave('fichaTecnica')
            ->orWhere('id', $fichaTecnica->produto_id)->orderBy('nome')->get();

        return view('fichas-tecnicas.edit', compact('fichaTecnica', 'produtos'));
    }

    public function update(Request $request, FichaTecnica $fichaTecnica): RedirectResponse
    {
        $fichaTecnica->update($this->validar($request, $fichaTecnica));

        return redirect()->route('fichas-tecnicas.index')
            ->with('sucesso', 'Ficha técnica atualizada com sucesso!');
    }

    public function destroy(FichaTecnica $fichaTecnica): RedirectResponse
    {
        $fichaTecnica->delete();

        return redirect()->route('fichas-tecnicas.index')
            ->with('sucesso', 'Ficha técnica excluída com sucesso!');
    }

    /** @return array<string, mixed> */
    private function validar(Request $request, ?FichaTecnica $fichaTecnica = null): array
    {
        return $request->validate([
            'produto_id' => [
                'required', 'integer', 'exists:produtos,id',
                // Na edição, desconsidera a própria ficha ao verificar duplicidade.
                Rule::unique('fichas_tecnicas', 'produto_id')->ignore($fichaTecnica),
            ],
            'marca' => ['required', 'string', 'max:100'],
            'modelo' => ['required', 'string', 'max:100'],
            'especificacoes' => ['nullable', 'string', 'max:2000'],
        ], [
            'produto_id.required' => 'Selecione um produto.',
            'produto_id.integer' => 'Selecione um produto válido.',
            'produto_id.exists' => 'O produto selecionado não existe.',
            'produto_id.unique' => 'Este produto já possui uma ficha técnica.',
            'marca.required' => 'Informe a marca.',
            'marca.string' => 'A marca deve ser um texto.',
            'marca.max' => 'A marca deve ter no máximo 100 caracteres.',
            'modelo.required' => 'Informe o modelo.',
            'modelo.string' => 'O modelo deve ser um texto.',
            'modelo.max' => 'O modelo deve ter no máximo 100 caracteres.',
            'especificacoes.string' => 'As especificações devem ser um texto.',
            'especificacoes.max' => 'As especificações devem ter no máximo 2000 caracteres.',
        ]);
    }
}
