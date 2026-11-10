<x-layouts::app title="Dashboard">
    <section lang="pt-BR" class="mx-auto w-full max-w-6xl space-y-6">
        <div>
            <h1 class="text-2xl font-semibold">Visão geral</h1>
            <p class="mt-1 text-sm text-zinc-600 dark:text-zinc-400">Acompanhe os totais cadastrados e acesse as listagens.</p>
        </div>

        <div class="grid gap-4 md:grid-cols-3">
            <article class="rounded-xl border border-zinc-200 bg-white p-6 dark:border-zinc-700 dark:bg-zinc-900">
                <h2 class="text-sm font-medium text-zinc-600 dark:text-zinc-400">Produtos</h2>
                <p class="mt-3 text-4xl font-semibold tabular-nums">{{ number_format($totalProdutos, 0, ',', '.') }}</p>
                <a href="{{ route('produtos.index') }}" class="mt-5 inline-block text-sm font-medium underline">Ver produtos</a>
            </article>

            <article class="rounded-xl border border-zinc-200 bg-white p-6 dark:border-zinc-700 dark:bg-zinc-900">
                <h2 class="text-sm font-medium text-zinc-600 dark:text-zinc-400">Categorias</h2>
                <p class="mt-3 text-4xl font-semibold tabular-nums">{{ number_format($totalCategorias, 0, ',', '.') }}</p>
                <a href="{{ route('categorias.index') }}" class="mt-5 inline-block text-sm font-medium underline">Ver categorias</a>
            </article>

            <article class="rounded-xl border border-zinc-200 bg-white p-6 dark:border-zinc-700 dark:bg-zinc-900">
                <h2 class="text-sm font-medium text-zinc-600 dark:text-zinc-400">Fornecedores</h2>
                <p class="mt-3 text-4xl font-semibold tabular-nums">{{ number_format($totalFornecedores, 0, ',', '.') }}</p>
                <a href="{{ route('fornecedores.index') }}" class="mt-5 inline-block text-sm font-medium underline">Ver fornecedores</a>
            </article>
        </div>
    </section>
</x-layouts::app>
