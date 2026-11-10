<?php

use App\Models\FichaTecnica;
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
        $consulta = FichaTecnica::with('produto');

        if ($busca !== '') {
            $consulta->whereHas('produto', function ($query) use ($busca) {
                $query->where('nome', 'like', '%'.$busca.'%');
            });
        }

        $fichasTecnicas = $consulta->orderBy('id')->paginate(10);

        return $this->view(compact('fichasTecnicas'));
    }
};
?>

<section lang="pt-BR" class="mx-auto w-full max-w-4xl space-y-6">
    <h1 class="text-2xl font-semibold">Fichas técnicas</h1>

    @if (session('sucesso'))
        <p role="status" class="rounded-lg border border-green-600 p-4 text-green-700 dark:text-green-400">
            {{ session('sucesso') }}
        </p>
    @endif

    <flux:button :href="route('fichas-tecnicas.create')" variant="primary">Nova ficha técnica</flux:button>

    <div role="search">
        <flux:input type="search" wire:model.live.debounce.300ms="busca" label="Pesquisar por nome do produto" maxlength="100" placeholder="Digite para pesquisar" />
        @error('busca')
            <p role="alert" class="text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div class="overflow-x-auto rounded-lg border border-zinc-200 dark:border-zinc-700">
        <table class="w-full text-left text-sm">
            <caption class="sr-only">Lista de fichas técnicas cadastradas</caption>
            <thead class="bg-zinc-100 dark:bg-zinc-900">
                <tr>
                    <th scope="col" class="p-4">Código</th>
                    <th scope="col" class="p-4">Produto</th>
                    <th scope="col" class="p-4">Marca</th>
                    <th scope="col" class="p-4">Modelo</th>
                    <th scope="col" class="p-4">Ações</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($fichasTecnicas as $fichaTecnica)
                    <tr wire:key="fichas-tecnicas-{{ $fichaTecnica->id }}" class="border-t border-zinc-200 dark:border-zinc-700">
                        <td class="p-4">{{ $fichaTecnica->id }}</td>
                        <td class="max-w-xs break-words p-4">{{ $fichaTecnica->produto->nome }}</td>
                        <td class="max-w-xs break-words p-4">{{ $fichaTecnica->marca }}</td>
                        <td class="max-w-xs break-words p-4">{{ $fichaTecnica->modelo }}</td>
                        <td class="p-4">
                            <div class="flex flex-wrap items-center gap-3">
                                <a class="underline" href="{{ route('fichas-tecnicas.show', $fichaTecnica) }}">Ver</a>
                                <a class="underline" href="{{ route('fichas-tecnicas.edit', $fichaTecnica) }}">Editar</a>
                                <form action="{{ route('fichas-tecnicas.destroy', $fichaTecnica) }}" method="POST" data-confirm-delete="Deseja realmente excluir esta ficha técnica? Esta ação não pode ser desfeita.">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="cursor-pointer text-red-600 underline dark:text-red-400">Excluir</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="p-6 text-center">{{ is_string($busca) && trim($busca) !== '' ? 'Nenhum resultado encontrado para esta pesquisa.' : 'Nenhuma ficha técnica cadastrada.' }}</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if ($fichasTecnicas->hasPages())
        <p class="text-sm text-zinc-600 dark:text-zinc-400">Página {{ $fichasTecnicas->currentPage() }} de {{ $fichasTecnicas->lastPage() }}</p>
        {{ $fichasTecnicas->links() }}
    @endif
</section>
