<x-layouts::app title="Editar ficha técnica">
    <section lang="pt-BR" class="mx-auto w-full max-w-4xl space-y-6">
        <h1 class="text-2xl font-semibold">Editar ficha técnica</h1>

        <form action="{{ route('fichas-tecnicas.update', $fichaTecnica) }}" method="POST" class="space-y-4">
            @method('PUT')
            @include('fichas-tecnicas.form')
        </form>
    </section>
</x-layouts::app>
