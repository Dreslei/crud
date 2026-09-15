{{-- A exclusão usa um formulário DELETE com proteção CSRF e confirmação no navegador. --}}
<form action="{{ route('categorias.destroy', $categoria) }}" method="POST" onsubmit="return confirm('Deseja excluir esta categoria?')">
    @csrf
    @method('DELETE')
    <button type="submit" class="cursor-pointer text-red-600 underline dark:text-red-400">Excluir</button>
</form>
