@extends('categorias.layout', ['titulo' => 'Detalhes da categoria'])

@section('conteudo')
    {{-- As chaves duplas do Blade escapam o conteúdo para exibi-lo como texto seguro. --}}
    <dl class="space-y-4 rounded-lg border border-zinc-200 p-6 dark:border-zinc-700">
        <div>
            <dt class="font-semibold">Código</dt>
            <dd>{{ $categoria->id }}</dd>
        </div>
        <div>
            <dt class="font-semibold">Nome</dt>
            <dd class="break-words">{{ $categoria->nome }}</dd>
        </div>
        <div>
            <dt class="font-semibold">Descrição</dt>
            <dd class="whitespace-pre-wrap break-words">{{ $categoria->descricao ?? 'Sem descrição' }}</dd>
        </div>
    </dl>
    <div class="flex flex-wrap items-center gap-4">
        <flux:button :href="route('categorias.edit', $categoria)">Editar</flux:button>
        @include('categorias.excluir')
        <a class="underline" href="{{ route('categorias.index') }}">Voltar</a>
    </div>
@endsection
