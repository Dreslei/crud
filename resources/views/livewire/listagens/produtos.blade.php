<?php

use App\Models\Produto;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Url;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithPagination;

new class extends Component
{
    use WithPagination;

    #[Url(except: '')]
    #[Validate('nullable|string|max:100')]
    public $busca = '';

    public function mount(): void
    {
        $this->validate();
    }

    public function updatedBusca(): void
    {
        $this->resetPage();
    }

    public function render(): View
    {
        $busca = is_string($this->busca) ? trim($this->busca) : '';
        $consulta = Produto::with('categoria');

        if ($busca !== '') {
            $consulta->where('nome', 'like', '%'.$busca.'%');
        }

        $produtos = $consulta->orderBy('nome')->orderBy('id')->paginate(10);

        return $this->view(compact('produtos'));
    }
};
?>

<section lang="pt-BR" class="mx-auto w-full max-w-4xl space-y-6">
    <h1 class="text-2xl font-semibold">Produtos</h1>

    @if (session('sucesso'))
        <p role="status" class="rounded-lg border border-green-600 p-4 text-green-700 dark:text-green-400">
            {{ session('sucesso') }}
        </p>
    @endif

    <flux:button :href="route('produtos.create')" variant="primary">Novo produto</flux:button>

    @if (session('erro'))
        <p role="alert" class="rounded-lg border border-red-600 p-4 text-red-600 dark:text-red-400">
            {{ session('erro') }}
        </p>
    @endif

    <div role="search">
        <flux:input type="search" wire:model.live.debounce.300ms="busca" label="Pesquisar por nome" maxlength="100" placeholder="Digite para pesquisar" />
        @error('busca')
            <p role="alert" class="text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div class="overflow-x-auto rounded-lg border border-zinc-200 dark:border-zinc-700">
        <table class="w-full text-left text-sm">
            <caption class="sr-only">Lista de produtos cadastrados</caption>
            <thead class="bg-zinc-100 dark:bg-zinc-900">
                <tr>
                    <th scope="col" class="p-4">Código</th>
                    <th scope="col" class="p-4">Nome</th>
                    <th scope="col" class="p-4">Categoria</th>
                    <th scope="col" class="p-4">Preço</th>
                    <th scope="col" class="p-4">Ações</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($produtos as $produto)
                    <tr wire:key="produtos-{{ $produto->id }}" class="border-t border-zinc-200 dark:border-zinc-700">
                        <td class="p-4">{{ $produto->id }}</td>
                        <td class="max-w-xs break-words p-4">{{ $produto->nome }}</td>
                        <td class="max-w-xs break-words p-4">{{ $produto->categoria->nome }}</td>
                        <td class="whitespace-nowrap p-4">R$ {{ number_format((float) $produto->preco, 2, ',', '.') }}</td>
                        <td class="p-4">
                            <div class="flex flex-wrap items-center gap-3">
                                <a class="underline" href="{{ route('produtos.show', $produto) }}">Ver</a>
                                <a class="underline" href="{{ route('produtos.edit', $produto) }}">Editar</a>
                                <form action="{{ route('produtos.destroy', $produto) }}" method="POST" data-confirm-delete="Deseja realmente excluir este produto? Esta ação não pode ser desfeita.">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="cursor-pointer text-red-600 underline dark:text-red-400">Excluir</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="p-6 text-center">{{ is_string($busca) && trim($busca) !== '' ? 'Nenhum resultado encontrado para esta pesquisa.' : 'Nenhum produto cadastrado.' }}</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if ($produtos->hasPages())
        <p class="text-sm text-zinc-600 dark:text-zinc-400">Página {{ $produtos->currentPage() }} de {{ $produtos->lastPage() }}</p>
        {{ $produtos->links() }}
    @endif
</section>
