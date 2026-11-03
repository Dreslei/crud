{{-- Cadastro e edição compartilham este formulário. --}}
@csrf

<flux:select name="produto_id" label="Produto" required>
    <option value="">Selecione um produto</option>
    @foreach ($produtos as $produto)
        <option value="{{ $produto->id }}" @selected(old('produto_id', $fichaTecnica->produto_id ?? '') == $produto->id)>
            {{ $produto->nome }} (código {{ $produto->id }})
        </option>
    @endforeach
</flux:select>
@error('produto_id')
    <p role="alert" class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
@enderror

@if ($produtos->isEmpty())
    <p role="alert" class="text-sm text-zinc-600 dark:text-zinc-300">
        Não há produtos disponíveis. Cadastre um produto ou edite uma ficha existente.
        <a class="underline" href="{{ route('produtos.create') }}">Cadastrar produto</a>
    </p>
@endif

<flux:input name="marca" label="Marca" :value="old('marca', $fichaTecnica->marca ?? '')" required maxlength="100" />
@error('marca')
    <p role="alert" class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
@enderror

<flux:input name="modelo" label="Modelo" :value="old('modelo', $fichaTecnica->modelo ?? '')" required maxlength="100" />
@error('modelo')
    <p role="alert" class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
@enderror

<flux:textarea name="especificacoes" label="Especificações (opcional)" rows="4" maxlength="2000">{{ old('especificacoes', $fichaTecnica->especificacoes ?? '') }}</flux:textarea>
@error('especificacoes')
    <p role="alert" class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
@enderror

<div class="flex items-center gap-4">
    <flux:button type="submit" variant="primary" :disabled="$produtos->isEmpty()">Salvar</flux:button>
    <a class="underline" href="{{ route('fichas-tecnicas.index') }}">Cancelar</a>
</div>
