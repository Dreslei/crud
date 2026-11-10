# Paginação simples no Laravel

## O que foi implementado

As listagens de categorias, produtos, fornecedores e fichas técnicas agora mostram até **10 registros por página**, com os controles nativos do Laravel abaixo da tabela.

Não é necessário instalar pacotes, criar rotas novas ou alterar o banco. A implementação se resume a paginar a consulta no controller e mostrar os links no Blade.

## 1. Paginar a consulta no controller

Em `app/Http/Controllers/CategoriaController.php`, o método `index()` ficou assim:

```php
public function index()
{
    $categorias = Categoria::orderBy('nome')->orderBy('id')->paginate(10);

    return view('categorias.index', compact('categorias'));
}
```

Antes, `Categoria::all()` buscava todos os registros. Agora:

- `orderBy('nome')` organiza as categorias pelo nome;
- `orderBy('id')` desempata nomes iguais, mantendo uma ordem definida;
- `paginate(10)` busca até dez registros da página solicitada e calcula o total de registros e páginas.

O Laravel identifica a página pelo parâmetro `page` da URL:

```text
/categorias         → primeira página
/categorias?page=2  → segunda página
```

Não é necessário ler esse parâmetro manualmente no controller.

**Importante:** use `paginate(10)` diretamente na consulta, sem chamar `get()` ou `all()` antes.

## 2. Exibir os links no Blade

Em `resources/views/categorias/index.blade.php`, depois do `div` que envolve a tabela e antes de fechar a seção:

```blade
@if ($categorias->hasPages())
    <p class="text-sm text-zinc-600 dark:text-zinc-400">Página {{ $categorias->currentPage() }} de {{ $categorias->lastPage() }}</p>
    {{ $categorias->links() }}
@endif
```

- `hasPages()` verifica se existe mais de uma página;
- `currentPage()` informa a página atual;
- `lastPage()` informa o total de páginas;
- `links()` gera os controles de navegação e as URLs com `?page=...`.

Os controles usam o estilo Tailwind padrão do Laravel, que já é utilizado neste projeto. Seus rótulos seguem o idioma e as traduções configurados na aplicação.

O `@forelse` da tabela continua igual. A variável `$categorias` passa a ser um paginador, que permite percorrer os registros da página com o mesmo loop.

Com até dez registros, os controles não aparecem porque todos cabem na primeira página. Sem registros, permanece a mensagem de lista vazia existente.

## 3. Aplicar nas outras listagens

O mesmo padrão foi aplicado aos métodos `index()` dos outros controllers:

```php
// ProdutoController.php
$produtos = Produto::with('categoria')->orderBy('nome')->orderBy('id')->paginate(10);

// FornecedorController.php
$fornecedores = Fornecedor::withCount('produtos')->orderBy('nome')->orderBy('id')->paginate(10);

// FichaTecnicaController.php
$fichasTecnicas = FichaTecnica::with('produto')->orderBy('id')->paginate(10);
```

`with()` continua carregando os relacionamentos necessários para exibir a tabela. `withCount()` continua contando os produtos de cada fornecedor. As fichas técnicas são ordenadas pelo código.

Nos respectivos arquivos `index.blade.php`, o bloco de paginação usa a variável correspondente: `$produtos`, `$fornecedores` ou `$fichasTecnicas`.

As consultas que preenchem os campos de seleção dos formulários continuam usando `get()`: nesses campos, queremos oferecer todas as opções.

## 4. Alterar a quantidade por página

Para mostrar cinco registros por página, altere o número no controller:

```php
$categorias = Categoria::orderBy('nome')->orderBy('id')->paginate(5);
```

Não precisa mudar o Blade. O Laravel recalcula as páginas automaticamente.

## 5. Conferir o resultado

1. Tenha pelo menos onze registros na listagem que deseja conferir.
2. Acesse a listagem: devem aparecer dez registros e a indicação de página 1 de 2.
3. Vá para a segunda página e confira o registro restante e `?page=2` na URL.
4. Volte à primeira página pelos controles.
5. Confira também uma listagem vazia e outra com até dez registros.
6. Confira que os botões de ver, editar e excluir continuam funcionando.

Ao cadastrar, atualizar ou excluir, o redirecionamento existente continua levando à primeira página da listagem.

Para rodar os testes existentes de categorias, incluindo o teste de paginação com onze registros:

```powershell
php artisan test --compact --filter=CategoriaTest
```

Esses testes usam SQLite em memória, sem inserir registros no banco de desenvolvimento. O teste verifica a divisão dos registros entre as páginas, os links de navegação e o escapamento de HTML.

Se estiver desenvolvendo com Vite, mantenha `npm run dev` em execução. Para atualizar os arquivos de produção, execute `npm run build`.

Referência: [documentação oficial de paginação do Laravel 13](https://laravel.com/docs/13.x/pagination).
