# Pesquisa em tempo real com Livewire

## Como funciona

Nas listagens de categorias, produtos, fornecedores e fichas técnicas, basta digitar no campo de pesquisa. Após uma pausa de **300 milissegundos**, o Livewire atualiza os resultados sem recarregar a página inteira.

Não há botões de Pesquisar ou Limpar. Para voltar à listagem completa, apague o texto do campo. Nas fichas técnicas, a pesquisa usa o nome do produto.

O projeto já utiliza Livewire 4. Não foi necessário instalar pacotes nem criar rotas novas. As alterações continuam na branch `pesquisa`, sem merge.

## 1. Colocar a listagem em um componente

O index de categorias, em `resources/views/categorias/index.blade.php`, mantém o layout e carrega o componente:

```blade
<x-layouts::app title="Categorias">
    <livewire:listagens.categorias />
</x-layouts::app>
```

A lógica PHP, o campo e a tabela ficam juntos em `resources/views/livewire/listagens/categorias.blade.php`. Esse é o formato de componente em arquivo único do Livewire 4. Não é necessário criar uma classe separada em `app/Livewire`.

Cada listagem usa apenas um componente novo. O index existente continua responsável pelo layout, e o controller abre esse index.

## 2. Ligar o campo à propriedade busca

O campo de pesquisa fica no próprio componente:

```blade
<div role="search">
    <flux:input type="search"
        wire:model.live.debounce.300ms="busca"
        label="Pesquisar por nome"
        maxlength="100"
        placeholder="Digite para pesquisar" />
    @error('busca')
        <p role="alert" class="text-sm text-red-600">{{ $message }}</p>
    @enderror
</div>
```

- `wire:model` liga o campo à propriedade pública `busca`.
- `.live` envia as alterações enquanto o usuário digita.
- `.debounce.300ms` aguarda uma pausa de 300 ms antes de enviar a consulta.

Não precisa de JavaScript próprio para essa interação.

## 3. Consultar os dados no componente

No começo de `resources/views/livewire/listagens/categorias.blade.php`, antes do HTML:

```php
<?php

use App\Models\Categoria;
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
        $consulta = Categoria::query();

        if ($busca !== '') {
            $consulta->where('nome', 'like', '%'.$busca.'%');
        }

        $categorias = $consulta->orderBy('nome')->orderBy('id')->paginate(10);

        return $this->view(compact('categorias'));
    }
};
?>
```

Logo após `?>`, fica o `<section>` com o campo de pesquisa e a tabela. `$this->view()` entrega os dados ao HTML do próprio arquivo.

A propriedade `busca` guarda o texto digitado. `Validate` aceita um texto opcional de até cem caracteres. `mount()` valida o valor inicial, inclusive quando vem da URL. `is_string()` evita usar valores inválidos na consulta. `Url` mantém o filtro na URL para poder atualizar ou compartilhar a página com a mesma pesquisa.

`render()` executa a consulta e devolve o Blade. `LIKE` com `%` antes e depois procura parte do nome. O Eloquent envia o valor por parâmetros, sem montar SQL manualmente. `%` e `_` são curingas do LIKE; o comportamento de maiúsculas e acentos depende do banco.

Quando o campo fica vazio ou contém apenas espaços, a consulta não aplica o filtro e todos os registros voltam, ainda paginados.

## 4. Manter a paginação

`WithPagination` integra `paginate(10)` ao Livewire. No Blade, a chamada permanece:

```blade
{{ $categorias->links() }}
```

`updatedBusca()` é chamado quando o texto muda. `resetPage()` volta à primeira página, evitando que uma nova pesquisa fique em uma página sem resultados.

`Url` cuida do termo e `WithPagination` cuida da página.

Cada linha da tabela recebeu uma identificação estável para o Livewire reconhecer os registros:

```blade
<tr wire:key="categorias-{{ $categoria->id }}">
```

## 5. Papel do controller

O index continua acessível pela rota existente. O controller apenas abre a view:

```php
public function index()
{
    return view('categorias.index');
}
```

A pesquisa, a validação e a consulta ficam somente no componente Livewire. Os métodos de cadastrar, editar e excluir continuam nos controllers. Os formulários de exclusão mantêm o POST com CSRF e DELETE, com confirmação pelo SweetAlert2.

## 6. As outras listagens

| Componente em `resources/views/livewire/listagens` | Pesquisa |
|---|---|
| `categorias.blade.php` | Nome da categoria |
| `produtos.blade.php` | Nome do produto |
| `fornecedores.blade.php` | Nome do fornecedor |
| `fichas-tecnicas.blade.php` | Nome do produto relacionado |

Produtos mantêm `with('categoria')` e fornecedores mantêm `withCount('produtos')`. Fichas técnicas usam `whereHas('produto', ...)` para filtrar pelo nome do produto. Todas mantêm dez registros por página.

## 7. Conferir

1. Digite parte de um nome e aguarde a pausa de 300 ms: a tabela deve atualizar.
2. Apague o texto: todos os registros devem voltar, ainda paginados.
3. Pesquise um termo inexistente: deve aparecer a mensagem de nenhum resultado.
4. Vá à segunda página de uma pesquisa e mude o texto: deve voltar à primeira página.
5. Atualize o navegador com uma busca na URL: o filtro deve ser restaurado.
6. Repita nas quatro listagens e confira a confirmação ao excluir um registro de teste.

Os testes em `tests/Feature/PesquisaTest.php` exercitam a mudança da propriedade `busca`, paginação, retorno à primeira página, limpeza e validação, usando SQLite em memória:

```powershell
php artisan test --compact --filter='PesquisaTest|CategoriaTest'
```

A aparência e o tempo de espera devem ser conferidos no navegador. Em desenvolvimento, mantenha `npm run dev` em execução; para compilar os arquivos, use `npm run build`.

Referências oficiais: [campos e debounce](https://livewire.laravel.com/docs/4.x/forms), [paginação](https://livewire.laravel.com/docs/4.x/pagination) e [estado na URL](https://livewire.laravel.com/docs/4.x/url).
