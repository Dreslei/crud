<x-layouts::app title="Detalhes da ficha técnica">
    <section lang="pt-BR" class="mx-auto w-full max-w-4xl space-y-6">
        <h1 class="text-2xl font-semibold">Detalhes da ficha técnica</h1>

        <p><strong>Código:</strong> {{ $fichaTecnica->id }}</p>
        <p class="break-words">
            <strong>Produto:</strong>
            <a class="underline" href="{{ route('produtos.show', $fichaTecnica->produto) }}">{{ $fichaTecnica->produto->nome }}</a>
        </p>
        <p class="break-words"><strong>Marca:</strong> {{ $fichaTecnica->marca }}</p>
        <p class="break-words"><strong>Modelo:</strong> {{ $fichaTecnica->modelo }}</p>
        <p class="whitespace-pre-wrap break-words"><strong>Especificações:</strong> {{ $fichaTecnica->especificacoes ?? 'Sem especificações' }}</p>

        <div class="flex flex-wrap items-center gap-4">
            <flux:button :href="route('fichas-tecnicas.edit', $fichaTecnica)">Editar</flux:button>
            <a class="underline" href="{{ route('fichas-tecnicas.index') }}">Voltar</a>
        </div>
    </section>
</x-layouts::app>
