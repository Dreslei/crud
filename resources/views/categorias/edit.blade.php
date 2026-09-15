@extends('categorias.layout', ['titulo' => 'Editar categoria'])

@section('conteudo')
    <form action="{{ route('categorias.update', $categoria) }}" method="POST" class="space-y-4">
        {{-- Formulários HTML usam POST; esta diretiva informa ao Laravel que a ação é PUT. --}}
        @method('PUT')
        @include('categorias.form')
    </form>
@endsection
