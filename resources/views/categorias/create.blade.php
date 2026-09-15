@extends('categorias.layout', ['titulo' => 'Nova categoria'])

@section('conteudo')
    {{-- POST envia os dados para o método store() do controller. --}}
    <form action="{{ route('categorias.store') }}" method="POST" class="space-y-4">
        @include('categorias.form')
    </form>
@endsection
