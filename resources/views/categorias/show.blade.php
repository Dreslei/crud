{{-- Reutiliza o layout do projeto em resources/views/layouts/app.blade.php. --}}
<x-layouts::app title="Detalhes da categoria">
    <section lang="pt-BR" class="mx-auto w-full max-w-4xl space-y-6">
        <h1 class="text-2xl font-semibold">Detalhes da categoria</h1>

        {{-- Cada parágrafo mostra um campo; strong destaca o nome em negrito. --}}
        <p><strong>Código:</strong> {{ $categoria->id }}</p>
        <p class="break-words"><strong>Nome:</strong> {{ $categoria->nome }}</p>
        <p class="whitespace-pre-wrap break-words"><strong>Descrição:</strong> {{ $categoria->descricao ?? 'Sem descrição' }}</p>
        <div class="flex flex-wrap items-center gap-4">
            <flux:button :href="route('categorias.edit', $categoria)">Editar</flux:button>
            <a class="underline" href="{{ route('categorias.index') }}">Voltar</a>
        </div>
    </section>
</x-layouts::app>
