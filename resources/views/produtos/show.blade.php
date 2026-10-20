<x-layouts::app title="Detalhes do produto">
    <section lang="pt-BR" class="mx-auto w-full max-w-4xl space-y-6">
        <h1 class="text-2xl font-semibold">Detalhes do produto</h1>

        <p><strong>Código:</strong> {{ $produto->id }}</p>
        <p class="break-words"><strong>Nome:</strong> {{ $produto->nome }}</p>
        <p class="whitespace-pre-wrap break-words"><strong>Descrição:</strong> {{ $produto->descricao ?? 'Sem descrição' }}</p>
        <p><strong>Preço:</strong> R$ {{ number_format((float) $produto->preco, 2, ',', '.') }}</p>
        <p class="break-words"><strong>Categoria:</strong> <a class="underline" href="{{ route('categorias.show', $produto->categoria) }}">{{ $produto->categoria->nome }}</a></p>
        <div class="flex flex-wrap items-center gap-4">
            <flux:button :href="route('produtos.edit', $produto)">Editar</flux:button>
            <a class="underline" href="{{ route('produtos.index') }}">Voltar</a>
        </div>
    </section>
</x-layouts::app>
