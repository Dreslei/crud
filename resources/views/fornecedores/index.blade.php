<x-layouts::app title="Fornecedores">
    <section lang="pt-BR" class="mx-auto w-full max-w-4xl space-y-6">
        <h1 class="text-2xl font-semibold">Fornecedores</h1>

        @if (session('sucesso'))
            <p role="status" class="rounded-lg border border-green-600 p-4 text-green-700 dark:text-green-400">
                {{ session('sucesso') }}
            </p>
        @endif

        <flux:button :href="route('fornecedores.create')" variant="primary">Novo fornecedor</flux:button>

        <div class="overflow-x-auto rounded-lg border border-zinc-200 dark:border-zinc-700">
            <table class="w-full text-left text-sm">
                <caption class="sr-only">Lista de fornecedores cadastrados</caption>
                <thead class="bg-zinc-100 dark:bg-zinc-900">
                    <tr>
                        <th scope="col" class="p-4">Código</th>
                        <th scope="col" class="p-4">Nome</th>
                        <th scope="col" class="p-4">E-mail</th>
                        <th scope="col" class="p-4">Telefone</th>
                        <th scope="col" class="p-4">Produtos</th>
                        <th scope="col" class="p-4">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($fornecedores as $fornecedor)
                        <tr class="border-t border-zinc-200 dark:border-zinc-700">
                            <td class="p-4">{{ $fornecedor->id }}</td>
                            <td class="max-w-xs break-words p-4">{{ $fornecedor->nome }}</td>
                            <td class="max-w-xs break-words p-4">{{ $fornecedor->email ?? 'Não informado' }}</td>
                            <td class="p-4">{{ $fornecedor->telefone ?? 'Não informado' }}</td>
                            <td class="p-4">{{ $fornecedor->produtos_count }}</td>
                            <td class="p-4">
                                <div class="flex flex-wrap items-center gap-3">
                                    <a class="underline" href="{{ route('fornecedores.show', $fornecedor) }}">Ver</a>
                                    <a class="underline" href="{{ route('fornecedores.edit', $fornecedor) }}">Editar</a>
                                    <form action="{{ route('fornecedores.destroy', $fornecedor) }}" method="POST" data-confirm-delete="Deseja realmente excluir este fornecedor? Os produtos continuarão cadastrados. Esta ação não pode ser desfeita.">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="cursor-pointer text-red-600 underline dark:text-red-400">Excluir</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="p-6 text-center">Nenhum fornecedor cadastrado.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
</x-layouts::app>
