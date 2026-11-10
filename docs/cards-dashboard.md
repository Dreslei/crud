# Cards no dashboard

## O que foi feito

O dashboard mostra três cards: total de produtos, categorias e fornecedores. Cada card possui um link para a sua listagem. Em telas pequenas, ficam um abaixo do outro; em telas maiores, ficam em três colunas. As cores acompanham os temas claro e escuro.

Foram alterados somente dois arquivos de código, sem instalar pacotes ou criar componentes novos. A funcionalidade está na branch `cards-dashboard`, criada a partir da `main`.

## 1. Consultar os totais na rota

Em `routes/web.php`, importamos os models:

```php
use App\Models\Categoria;
use App\Models\Fornecedor;
use App\Models\Produto;
```

A rota do dashboard utiliza uma função para consultar os totais a cada acesso:

```php
Route::get('dashboard', function () {
    return view('dashboard', [
        'totalProdutos' => Produto::count(),
        'totalCategorias' => Categoria::count(),
        'totalFornecedores' => Fornecedor::count(),
    ]);
})->name('dashboard');
```

`count()` pede ao banco apenas a quantidade de registros. Não precisa buscar todos os produtos com `all()` para depois contá-los.

O segundo argumento de `view()` envia os três totais ao Blade. A rota continua dentro do grupo com os middlewares `auth` e `verified`.

## 2. Exibir os valores no Blade

Em `resources/views/dashboard.blade.php`, os espaços vazios foram substituídos pelos cards. Exemplo do card de produtos:

```blade
<article class="rounded-xl border border-zinc-200 bg-white p-6 dark:border-zinc-700 dark:bg-zinc-900">
    <h2 class="text-sm font-medium text-zinc-600 dark:text-zinc-400">Produtos</h2>
    <p class="mt-3 text-4xl font-semibold tabular-nums">{{ number_format($totalProdutos, 0, ',', '.') }}</p>
    <a href="{{ route('produtos.index') }}" class="mt-5 inline-block text-sm font-medium underline">Ver produtos</a>
</article>
```

`number_format()` apresenta números inteiros com ponto como separador de milhar. `route()` gera o endereço da listagem usando o nome da rota existente.

O contêiner dos três cards utiliza:

```blade
<div class="grid gap-4 md:grid-cols-3">
```

`grid` organiza os cards, `gap-4` define o espaço entre eles e `md:grid-cols-3` ativa três colunas a partir do tamanho médio de tela.

## 3. Conferir

1. Entre no sistema e abra o dashboard.
2. Confira os totais. Sem registros, os cards mostram zero.
3. Acesse cada listagem pelos links.
4. Cadastre um registro e volte ao dashboard: o total deve aumentar.
5. Confira em uma janela estreita e nos temas claro e escuro.

Os números são consultados quando a página é aberta ou atualizada. Não há atualização automática em tempo real.

Para compilar os estilos:

```powershell
npm run build
```

Em desenvolvimento, você pode manter `npm run dev` em execução. Para executar os testes existentes de acesso ao dashboard:

```powershell
php artisan test --compact --filter=DashboardTest
```
