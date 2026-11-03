{{-- Cadastro e edição compartilham este formulário. --}}
@csrf

<flux:input name="nome" label="Nome" :value="old('nome', $fornecedor->nome ?? '')" required maxlength="100" />
@error('nome')
    <p role="alert" class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
@enderror

<flux:input type="email" name="email" label="E-mail (opcional)" :value="old('email', $fornecedor->email ?? '')" maxlength="255" />
@error('email')
    <p role="alert" class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
@enderror

<flux:input type="tel" name="telefone" label="Telefone (opcional)" :value="old('telefone', $fornecedor->telefone ?? '')" maxlength="30" />
@error('telefone')
    <p role="alert" class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
@enderror

@php
    // Após erro, preserva a seleção enviada, inclusive quando tudo foi desmarcado.
    $produtosSelecionados = old('produtos', session()->hasOldInput() ? [] : (isset($fornecedor) ? $fornecedor->produtos->modelKeys() : []));
@endphp

<fieldset class="space-y-3 rounded-lg border border-zinc-200 p-4 dark:border-zinc-700">
    <legend class="px-1 font-medium">Produtos fornecidos (opcional)</legend>
    <p class="text-sm text-zinc-600 dark:text-zinc-300">Marque os produtos que este fornecedor fornece.</p>
    @forelse ($produtos as $produto)
        <label class="flex items-center gap-3">
            <input type="checkbox" name="produtos[]" value="{{ $produto->id }}" @checked(is_array($produtosSelecionados) && in_array($produto->id, $produtosSelecionados)) class="size-4 rounded border-zinc-300" />
            <span class="break-words">{{ $produto->nome }} (código {{ $produto->id }})</span>
        </label>
    @empty
        <p class="text-sm">Nenhum produto cadastrado. Você pode vincular os produtos depois.</p>
        <a class="underline" href="{{ route('produtos.create') }}">Cadastrar produto</a>
    @endforelse
</fieldset>
@error('produtos')
    <p role="alert" class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
@enderror
@foreach ($errors->get('produtos.*') as $mensagens)
    @foreach ($mensagens as $mensagem)
        <p role="alert" class="text-sm text-red-600 dark:text-red-400">{{ $mensagem }}</p>
    @endforeach
@endforeach

<div class="flex items-center gap-4">
    <flux:button type="submit" variant="primary">Salvar</flux:button>
    <a class="underline" href="{{ route('fornecedores.index') }}">Cancelar</a>
</div>
