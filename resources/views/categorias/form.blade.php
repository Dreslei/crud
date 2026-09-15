{{-- Este formulário é compartilhado pelo cadastro e pela edição. --}}
{{-- O token CSRF protege o envio do formulário contra requisições de outros sites. --}}


{{-- old() recupera o texto digitado se a validação falhar; na edição, usamos os dados salvos. --}}

@csrf
<flux:input name="nome" label="Nome" :value="old('nome', $categoria->nome ?? '')" required maxlength="100" />
@error('nome')
    <p role="alert" class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
@enderror

<flux:textarea name="descricao" label="Descrição (opcional)" rows="4" maxlength="1000">{{ old('descricao', $categoria->descricao ?? '') }}</flux:textarea>
@error('descricao')
    <p role="alert" class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
@enderror

<div class="flex items-center gap-4">
    <flux:button type="submit" variant="primary">Salvar</flux:button>
    <a class="underline" href="{{ route('categorias.index') }}">Cancelar</a>
</div>


