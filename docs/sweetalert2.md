# SweetAlert2: confirmação antes de excluir

## Objetivo

Ao clicar em **Excluir**, o usuário verá uma janela perguntando se deseja realmente excluir o registro. **Sim, excluir** envia o formulário; **Cancelar**, `Esc` ou clicar fora da janela mantém o registro. O foco inicial fica em Cancelar.

A confirmação foi aplicada às listagens de categorias, produtos, fornecedores e fichas técnicas. O modal acompanha o tema claro ou escuro da página. A exclusão da conta em Configurações continua com seu fluxo próprio, que pede senha.

## 1. Conferir o ambiente

```powershell
npm install sweetalert2
```

Esse comando adiciona a dependência ao `package.json`, atualiza o `package-lock.json` e instala os arquivos em `node_modules`. Ao baixar este projeto em outra máquina, use `npm ci` para instalar as versões registradas no lockfile.

## 2. Importar a biblioteca e os estilos

Em `resources/js/app.js`:

```javascript
import Swal from 'sweetalert2/dist/sweetalert2.esm.js';
import 'sweetalert2/dist/sweetalert2.min.css';
```

O primeiro import carrega o JavaScript; o segundo carrega a aparência do modal. Os arquivos são processados localmente pelo Vite, sem depender de CDN no navegador.

O projeto já carrega a entrada JavaScript em `resources/views/partials/head.blade.php`:

```blade
@vite(['resources/css/app.css', 'resources/js/app.js'])
```

Não é necessário duplicar essa diretiva nas listagens.

## 3. Marcar os formulários que pedem confirmação

Antes, os formulários usavam `onsubmit="return confirm(...)"`. Esse atributo foi substituído por `data-confirm-delete`, que guarda o texto a apresentar.

Exemplo real em `resources/views/categorias/index.blade.php`:

```blade
<form action="{{ route('categorias.destroy', $categoria) }}"
      method="POST"
      data-confirm-delete="Deseja realmente excluir esta categoria? Esta ação não pode ser desfeita.">
    @csrf
    @method('DELETE')
    <button type="submit" class="cursor-pointer text-red-600 underline dark:text-red-400">Excluir</button>
</form>
```

`@csrf` mantém a proteção do formulário. Como formulários HTML não enviam DELETE diretamente, `@method('DELETE')` gera o campo oculto `_method` para o Laravel interpretar o POST como uma exclusão.

O mesmo atributo foi aplicado em:

- `resources/views/produtos/index.blade.php`;
- `resources/views/fornecedores/index.blade.php`;
- `resources/views/fichas-tecnicas/index.blade.php`.

Para fornecedores, a mensagem também informa que os produtos continuarão cadastrados.

## 4. Entender a confirmação em JavaScript

A implementação completa está em `resources/js/app.js`. O fluxo é:

1. Um listener em `document` recebe o evento `submit` dos formulários.
2. Ele trata apenas os formulários com `data-confirm-delete`.
3. `event.preventDefault()` interrompe o envio enquanto o usuário decide.
4. `Swal.fire(...)` abre a janela com aviso e os dois botões.
5. `await` aguarda a resposta. Apenas `result.isConfirmed` permite enviar o formulário.
6. Os botões de envio são desabilitados e `HTMLFormElement.prototype.submit.call(form)` envia o formulário original, com CSRF e DELETE, sem disparar outra confirmação.

O `WeakSet` chamado `pendingForms` evita abrir várias confirmações para o mesmo formulário enquanto existe uma decisão pendente. A verificação `form.isConnected` impede enviar um formulário removido da página durante a espera.

Como o listener fica em `document`, também atende formulários inseridos pela navegação do Livewire. Não é necessário registrar novos listeners a cada página.

O texto é passado pela opção `text` do SweetAlert2, sem interpretar HTML. Para personalizar a pergunta, basta alterar `data-confirm-delete` no Blade. Para mudar os rótulos dos botões ou a cor vermelha, edite `confirmButtonText`, `cancelButtonText` e `confirmButtonColor` em `app.js`.

A confirmação é uma interação no navegador e exige JavaScript carregado. As regras de exclusão, validação e permissões continuam sendo responsabilidade do backend. O modal não informa sucesso antecipadamente: o resultado continua sendo exibido pela resposta do Laravel.

## 5. Compilar os arquivos

Para desenvolvimento:

```powershell
npm run dev
```

Mantenha esse processo em execução enquanto desenvolve. Em outro terminal, inicie o Laravel se necessário:

```powershell
php artisan serve
```

Para gerar os arquivos de produção:

```powershell
npm run build
```

O script deste projeto executa `vp build`, configurado em `package.json`. Os arquivos compilados ficam em `public/build`.

## 6. Verificar o comportamento

Use registros de teste para as exclusões confirmadas:

1. Acesse cada uma das quatro listagens e clique em Excluir.
2. Confira o texto, os dois botões e o foco inicial em Cancelar.
3. Cancele e confira que o registro continua na listagem.
4. Abra novamente e pressione `Esc`; repita clicando fora do modal. Nenhuma dessas ações deve excluir.
5. Confirme em **Sim, excluir** e confira o resultado retornado pelo Laravel.
6. Confira os temas claro e escuro e repita após navegar pelo menu.
7. Em fornecedores, confira o aviso de que os produtos permanecerão cadastrados.
8. Tente excluir uma categoria vinculada a produtos: o backend deve continuar impedindo a operação conforme a regra existente.

Para executar os testes PHP existentes do CRUD de categorias, que usam SQLite em memória:

```powershell
php artisan test --compact --filter=CategoriaTest
```

Esses testes verificam o backend; a interação visual deve ser conferida no navegador.

### Verificações realizadas nesta implementação

- `npm run build`: concluído com sucesso. Houve um aviso sobre o pacote opcional `fontaine` para otimização das fontes, sem impedir a compilação.
- Sintaxe JavaScript e `git diff --check`: sem erros.
- Verificação isolada da lógica JavaScript, com simulação do formulário e da resposta do modal: cancelamento não envia, confirmação envia, eventos repetidos não abrem outro modal pendente, formulário removido não envia e formulários sem o atributo são ignorados.
- `CategoriaTest`: cinco testes passaram e um falhou por esperar paginação (`Página 1 de 2`). O controller existente usa `Categoria::all()` e a view original não apresenta paginação; essa divergência é anterior à integração.
- Os testes existentes de acesso e do ciclo cadastrar/consultar/editar/excluir também foram executados separadamente e passaram.
- A aparência e a interação do modal no navegador ainda precisam da conferência manual descrita acima.

## Referências oficiais

- [SweetAlert2: instalação, uso e exemplos](https://sweetalert2.github.io/)
- [Laravel 13: integração com Vite](https://laravel.com/docs/13.x/vite)
- [Livewire 4: navegação e JavaScript](https://livewire.laravel.com/docs/4.x/navigate)
