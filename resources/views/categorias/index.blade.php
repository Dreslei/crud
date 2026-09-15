@extends('categorias.layout', ['titulo' => 'Categorias'])

@section('conteudo')
    <flux:button :href="route('categorias.create')" variant="primary">Nova categoria</flux:button>

    <div class="overflow-x-auto rounded-lg border border-zinc-200 dark:border-zinc-700">
        <table class="w-full text-left text-sm">
            <caption class="sr-only">Lista de categorias cadastradas</caption>
            <thead class="bg-zinc-100 dark:bg-zinc-900">
                <tr>
                    <th scope="col" class="p-4">Código</th>
                    <th scope="col" class="p-4">Nome</th>
                    <th scope="col" class="p-4">Descrição</th>
                    <th scope="col" class="p-4">Ações</th>
                </tr>
            </thead>
            <tbody>
                {{-- O forelse também trata o caso de a tabela estar vazia. --}}
                @forelse ($categorias as $categoria)
                    <tr class="border-t border-zinc-200 dark:border-zinc-700">
                        <td class="p-4">{{ $categoria->id }}</td>
                        <td class="max-w-xs break-words p-4">{{ $categoria->nome }}</td>
                        <td class="max-w-sm break-words p-4">{{ $categoria->descricao ?? 'Sem descrição' }}</td>
                        <td class="p-4">
                            <div class="flex flex-wrap items-center gap-3">
                                <a class="underline" href="{{ route('categorias.show', $categoria) }}">Ver</a>
                                <a class="underline" href="{{ route('categorias.edit', $categoria) }}">Editar</a>
                                @include('categorias.excluir')
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="p-6 text-center">Nenhuma categoria cadastrada.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Navegação simples, com os textos em português. --}}
    @if ($categorias->hasPages())
        <nav aria-label="Paginação de categorias" class="flex items-center justify-between gap-4">
            <div>
                @if ($categorias->previousPageUrl())
                    <a class="underline" href="{{ $categorias->previousPageUrl() }}">Anterior</a>
                @endif
            </div>
            <span>Página {{ $categorias->currentPage() }} de {{ $categorias->lastPage() }}</span>
            <div>
                @if ($categorias->nextPageUrl())
                    <a class="underline" href="{{ $categorias->nextPageUrl() }}">Próxima</a>
                @endif
            </div>
        </nav>
    @endif
@endsection
