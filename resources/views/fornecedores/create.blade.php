<x-layouts::app title="Novo fornecedor">
    <section lang="pt-BR" class="mx-auto w-full max-w-4xl space-y-6">
        <h1 class="text-2xl font-semibold">Novo fornecedor</h1>

        <form action="{{ route('fornecedores.store') }}" method="POST" class="space-y-4">
            @include('fornecedores.form')
        </form>
    </section>
</x-layouts::app>
