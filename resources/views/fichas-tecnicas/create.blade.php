<x-layouts::app title="Nova ficha técnica">
    <section lang="pt-BR" class="mx-auto w-full max-w-4xl space-y-6">
        <h1 class="text-2xl font-semibold">Nova ficha técnica</h1>

        <form action="{{ route('fichas-tecnicas.store') }}" method="POST" class="space-y-4">
            @include('fichas-tecnicas.form')
        </form>
    </section>
</x-layouts::app>
