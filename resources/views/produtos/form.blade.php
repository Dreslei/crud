


@csrf
@if ($categorias->isEmpty())
    <p role="alert" class="text-sm text-red-600 dark:text-red-400">
        Cadastre uma categoria antes de salvar um produto.
        <a class="underline" href="{{ route('categorias.create') }}">Cadastrar categoria</a>
    </p>
@endif
<flux:input name="nome" label="Nome" :value="old('nome', $produto->nome ?? '')" required maxlength="100" />
@error('nome')
    <p role="alert" class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
@enderror

<flux:textarea name="descricao" label="Descrição (opcional)" rows="4" maxlength="1000">{{ old('descricao', $produto->descricao ?? '') }}</flux:textarea>
@error('descricao')
    <p role="alert" class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
@enderror

<flux:input type="number" name="preco" label="Preço (R$)" :value="old('preco', $produto->preco ?? '')" required min="0" max="99999999.99" step="0.01" />
@error('preco')
    <p role="alert" class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
@enderror

<flux:select name="categoria_id" label="Categoria" required>
    <option value="">Selecione uma categoria</option>
    @foreach ($categorias as $categoria)
        <option value="{{ $categoria->id }}" @selected(old('categoria_id', $produto->categoria_id ?? '') == $categoria->id)>{{ $categoria->nome }}</option>
    @endforeach
</flux:select>
@error('categoria_id')
    <p role="alert" class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
@enderror

<div class="flex items-center gap-4">
    <flux:button type="submit" variant="primary" :disabled="$categorias->isEmpty()">Salvar</flux:button>
    <a class="underline" href="{{ route('produtos.index') }}">Cancelar</a>
</div>


