{{-- Reutiliza o layout do projeto em resources/views/layouts/app.blade.php. --}}
<x-layouts::app title="Nova categoria">
    <section lang="pt-BR" class="mx-auto w-full max-w-4xl space-y-6">
        <h1 class="text-2xl font-semibold">Nova categoria</h1>

        <form action="{{ route('categorias.store') }}" method="POST" class="space-y-4">
            @include('categorias.form')
        </form>
    </section>
</x-layouts::app>


