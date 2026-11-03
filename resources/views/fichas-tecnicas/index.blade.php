<x-layouts::app title="Fichas técnicas">
    <section lang="pt-BR" class="mx-auto w-full max-w-4xl space-y-6">
        <h1 class="text-2xl font-semibold">Fichas técnicas</h1>

        @if (session('sucesso'))
            <p role="status" class="rounded-lg border border-green-600 p-4 text-green-700 dark:text-green-400">
                {{ session('sucesso') }}
            </p>
        @endif

        <flux:button :href="route('fichas-tecnicas.create')" variant="primary">Nova ficha técnica</flux:button>

        <div class="overflow-x-auto rounded-lg border border-zinc-200 dark:border-zinc-700">
            <table class="w-full text-left text-sm">
                <caption class="sr-only">Lista de fichas técnicas cadastradas</caption>
                <thead class="bg-zinc-100 dark:bg-zinc-900">
                    <tr>
                        <th scope="col" class="p-4">Código</th>
                        <th scope="col" class="p-4">Produto</th>
                        <th scope="col" class="p-4">Marca</th>
                        <th scope="col" class="p-4">Modelo</th>
                        <th scope="col" class="p-4">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($fichasTecnicas as $fichaTecnica)
                        <tr class="border-t border-zinc-200 dark:border-zinc-700">
                            <td class="p-4">{{ $fichaTecnica->id }}</td>
                            <td class="max-w-xs break-words p-4">{{ $fichaTecnica->produto->nome }}</td>
                            <td class="max-w-xs break-words p-4">{{ $fichaTecnica->marca }}</td>
                            <td class="max-w-xs break-words p-4">{{ $fichaTecnica->modelo }}</td>
                            <td class="p-4">
                                <div class="flex flex-wrap items-center gap-3">
                                    <a class="underline" href="{{ route('fichas-tecnicas.show', $fichaTecnica) }}">Ver</a>
                                    <a class="underline" href="{{ route('fichas-tecnicas.edit', $fichaTecnica) }}">Editar</a>
                                    <form action="{{ route('fichas-tecnicas.destroy', $fichaTecnica) }}" method="POST" onsubmit="return confirm('Deseja excluir esta ficha técnica?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="cursor-pointer text-red-600 underline dark:text-red-400">Excluir</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="p-6 text-center">Nenhuma ficha técnica cadastrada.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
</x-layouts::app>
