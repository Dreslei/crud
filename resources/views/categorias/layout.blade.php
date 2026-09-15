{{-- VIEW: o layout reúne a estrutura visual compartilhada pelas telas do CRUD. --}}
<x-layouts::app :title="$titulo">
    <section lang="pt-BR" class="mx-auto w-full max-w-4xl space-y-6">
        <h1 class="text-2xl font-semibold">{{ $titulo }}</h1>

        {{-- A mensagem fica na sessão apenas para a próxima requisição. --}}
        @if (session('sucesso'))
            <p role="status" class="rounded-lg border border-green-600 p-4 text-green-700 dark:text-green-400">
                {{ session('sucesso') }}
            </p>
        @endif

        @yield('conteudo')
    </section>
</x-layouts::app>
