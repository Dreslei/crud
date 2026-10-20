<x-layouts::app title="Novo produto">
    <section lang="pt-BR" class="mx-auto w-full max-w-4xl space-y-6">
        <h1 class="text-2xl font-semibold">Novo produto</h1>

        <form action="{{ route('produtos.store') }}" method="POST" class="space-y-4">
            @include('produtos.form')
        </form>
    </section>
</x-layouts::app>


