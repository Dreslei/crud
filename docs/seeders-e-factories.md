# Criando usuários com Seeder e Factory

## Objetivo

Preencher o banco com usuários de teste usando a estrutura nativa do Laravel, sem cadastrar um por vez pela tela.

O trabalho está na branch `seeder`, que parte do mesmo commit da `main`. Não foi feito commit nem merge.

## 1. Entender os arquivos

| Arquivo | Responsabilidade |
|---|---|
| `database/factories/UserFactory.php` | Define os dados fictícios de cada usuário. Já existia no projeto e foi reaproveitado. |
| `database/seeders/UserSeeder.php` | Cria um usuário de teste fixo e dez usuários fictícios. |
| `database/seeders/DatabaseSeeder.php` | Chama o seeder de usuários. |

**Factory define como gerar os dados. Seeder define quais registros criar e em qual quantidade.**

## 2. Reaproveitar a UserFactory

A factory existente contém, entre outros campos:

```php
'name' => fake()->name(),
'email' => fake()->unique()->safeEmail(),
'email_verified_at' => now(),
'password' => static::$password ??= Hash::make('password'),
```

- `fake()` gera dados fictícios.
- `unique()->safeEmail()` gera e-mails fictícios distintos durante a geração.
- `email_verified_at` deixa o usuário com e-mail marcado como verificado.
- `Hash::make()` gera o hash da senha, sem salvá-la como texto puro.
- A senha padrão desses usuários de teste é `password`.

O model `User` já possui `HasFactory`, que permite chamar `User::factory()`.

## 3. Criar o UserSeeder

O comando para gerar a estrutura do arquivo é:

```powershell
php artisan make:seeder UserSeeder
```

O arquivo já foi criado nesta implementação. Seu método `run()` utiliza:

```php
User::firstOrCreate(
    ['email' => 'test@example.com'],
    [
        'name' => 'Usuário de Teste',
        'email_verified_at' => now(),
        'password' => Hash::make('password'),
    ],
);

User::factory()->count(10)->create();
```

`firstOrCreate()` procura o e-mail informado. Se ele não existir, cria o usuário. Se já existir, mantém os dados atuais, inclusive a senha.

`count(10)->create()` gera e salva dez usuários com os dados da factory. Para criar vinte, basta trocar `10` por `20`.

Em um banco vazio, a primeira execução cria onze usuários: um fixo e dez fictícios. Cada nova execução acrescenta mais dez fictícios; o usuário fixo não é duplicado. O seeder não apaga usuários existentes.

## 4. Chamar no DatabaseSeeder

Em `database/seeders/DatabaseSeeder.php`:

```php
public function run(): void
{
    $this->call(UserSeeder::class);
}
```

Assim, o comando geral de seed também executa o cadastro dos usuários.

## 5. Executar no banco de desenvolvimento

Confira a conexão configurada no `.env`. Se as tabelas ainda não foram criadas, execute as migrations pendentes:

```powershell
php artisan migrate
```

Para executar o `DatabaseSeeder`:

```powershell
php artisan db:seed
```

Ou execute somente o seeder de usuários:

```powershell
php artisan db:seed --class=UserSeeder
```

Escolha um dos dois comandos de seed. Executar ambos adiciona dois lotes de dez usuários fictícios.

## 6. Conferir e entrar

Para consultar a quantidade e os e-mails pelo Tinker:

```powershell
php artisan tinker
```

Dentro dele:

```php
App\Models\User::count();
App\Models\User::select('id', 'name', 'email')->get();
```

Se o usuário fixo foi criado pelo seeder, use na tela de login:

- E-mail: `test@example.com`
- Senha: `password`

Se esse e-mail já existia, continua valendo a senha anterior. Esses dados e senhas são destinados ao ambiente de desenvolvimento e testes.

Referências: [seeders no Laravel 13](https://laravel.com/docs/13.x/seeding) e [factories](https://laravel.com/docs/13.x/eloquent-factories).
