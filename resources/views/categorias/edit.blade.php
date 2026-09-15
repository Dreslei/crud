{{-- Reutiliza o layout do projeto em resources/views/layouts/app.blade.php. --}}
<x-layouts::app title="Editar categoria">
    <section lang="pt-BR" class="mx-auto w-full max-w-4xl space-y-6">
        <h1 class="text-2xl font-semibold">Editar categoria</h1>

        <form action="{{ route('categorias.update', $categoria) }}" method="POST" class="space-y-4">
            @method('PUT')
            @include('categorias.form')
        </form>
    </section>
</x-layouts::app>
