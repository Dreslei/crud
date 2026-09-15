<x-layouts::app title="Editar categoria">
    <section lang="pt-BR">
        <h1>Editar categoria</h1>

        <form action="{{ route('categorias.update', $categoria) }}" method="POST">
            @method('PUT')
            @include('categorias.form')
        </form>
    </section>
</x-layouts::app>
