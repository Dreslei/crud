<x-layouts::app title="Detalhes do fornecedor">
    <section lang="pt-BR" class="mx-auto w-full max-w-4xl space-y-6">
        <h1 class="text-2xl font-semibold">Detalhes do fornecedor</h1>

        <p><strong>Código:</strong> {{ $fornecedor->id }}</p>
        <p class="break-words"><strong>Nome:</strong> {{ $fornecedor->nome }}</p>
        <p class="break-words"><strong>E-mail:</strong> {{ $fornecedor->email ?? 'Não informado' }}</p>
        <p class="break-words"><strong>Telefone:</strong> {{ $fornecedor->telefone ?? 'Não informado' }}</p>

        <div class="space-y-2">
            <h2 class="font-semibold">Produtos fornecidos</h2>
            @forelse ($fornecedor->produtos as $produto)
                <p class="break-words"><a class="underline" href="{{ route('produtos.show', $produto) }}">{{ $produto->nome }} (código {{ $produto->id }})</a></p>
            @empty
                <p>Nenhum produto vinculado.</p>
            @endforelse
        </div>

        <div class="flex flex-wrap items-center gap-4">
            <flux:button :href="route('fornecedores.edit', $fornecedor)">Editar</flux:button>
            <a class="underline" href="{{ route('fornecedores.index') }}">Voltar</a>
        </div>
    </section>
</x-layouts::app>
