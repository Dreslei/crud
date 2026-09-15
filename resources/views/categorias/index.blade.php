{{-- Reutiliza o layout do projeto em resources/views/layouts/app.blade.php. --}}
<x-layouts::app title="Categorias">
    <section lang="pt-BR" class="mx-auto w-full max-w-4xl space-y-6">
        <h1 class="text-2xl font-semibold">Categorias</h1>

        {{-- A mensagem de sucesso aparece após cadastrar, editar ou excluir. --}}
        @if (session('sucesso'))
            <p role="status" class="rounded-lg border border-green-600 p-4 text-green-700 dark:text-green-400">
                {{ session('sucesso') }}
            </p>
        @endif

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
                                    {{-- DELETE indica a exclusão; @csrf protege o envio do formulário. --}}
                                    <form action="{{ route('categorias.destroy', $categoria) }}" method="POST" onsubmit="return confirm('Deseja excluir esta categoria?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="cursor-pointer text-red-600 underline dark:text-red-400">Excluir</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="p-6 text-center">Nenhuma categoria cadastrada.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
</x-layouts::app>
