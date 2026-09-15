<?php

namespace Tests\Feature;

use App\Models\Categoria;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoriaTest extends TestCase
{
    // Os testes usam o SQLite em memória configurado no phpunit.xml.
    use RefreshDatabase;

    public function test_visitantes_nao_podem_acessar_o_crud(): void
    {
        $categoria = Categoria::create(['nome' => 'Livros']);

        foreach (['index', 'create', 'show', 'edit'] as $acao) {
            $this->get(route('categorias.'.$acao, $categoria))
                ->assertRedirect(route('login'));
        }

        $this->post(route('categorias.store'), ['nome' => 'Roupas'])->assertRedirect(route('login'));
        $this->put(route('categorias.update', $categoria), ['nome' => 'Roupas'])->assertRedirect(route('login'));
        $this->delete(route('categorias.destroy', $categoria))->assertRedirect(route('login'));
        $this->assertDatabaseCount('categorias', 1);
        $this->assertDatabaseHas('categorias', ['nome' => 'Livros']);
    }

    public function test_usuario_pode_cadastrar_consultar_editar_e_excluir_categoria(): void
    {
        $this->actingAs(User::factory()->create());

        $this->get(route('categorias.index'))->assertOk()->assertSee('Nenhuma categoria cadastrada.');
        $this->get(route('categorias.create'))->assertOk()->assertSee('Nova categoria');

        $this->post(route('categorias.store'), ['nome' => 'Livros', 'descricao' => 'Material de leitura'])
            ->assertRedirect(route('categorias.index'))->assertSessionHas('sucesso');

        $categoria = Categoria::firstOrFail();
        $this->assertNotNull($categoria->getAttribute('criado_em'));
        $this->assertNotNull($categoria->getAttribute('atualizado_em'));
        $this->get(route('categorias.index'))->assertOk()->assertSee('Livros');
        $this->get(route('categorias.show', $categoria))->assertOk()->assertSee('Material de leitura');
        $this->get(route('categorias.edit', $categoria))->assertOk()->assertSee('Livros');

        $this->put(route('categorias.update', $categoria), ['nome' => 'Papelaria', 'descricao' => ''])
            ->assertRedirect(route('categorias.index'))->assertSessionHas('sucesso');
        $this->assertDatabaseHas('categorias', ['id' => $categoria->id, 'nome' => 'Papelaria', 'descricao' => null]);

        $this->delete(route('categorias.destroy', $categoria))
            ->assertRedirect(route('categorias.index'))->assertSessionHas('sucesso');
        $this->assertDatabaseMissing('categorias', ['id' => $categoria->id]);
    }

    public function test_validacao_impede_dados_invalidos_e_preserva_os_valores_digitados(): void
    {
        $this->actingAs(User::factory()->create());

        $this->from(route('categorias.create'))->post(route('categorias.store'), [
            'nome' => '', 'descricao' => 'Manter esta descrição',
        ])->assertRedirect(route('categorias.create'))
            ->assertSessionHasErrors(['nome' => 'Informe o nome da categoria.'])
            ->assertSessionHasInput('descricao', 'Manter esta descrição');

        $this->post(route('categorias.store'), ['nome' => str_repeat('a', 101), 'descricao' => str_repeat('a', 1001)])
            ->assertSessionHasErrors(['nome', 'descricao']);
        $this->post(route('categorias.store'), ['nome' => ['inválido'], 'descricao' => ['inválido']])
            ->assertSessionHasErrors(['nome', 'descricao']);
        $this->assertDatabaseCount('categorias', 0);

        $categoria = Categoria::create(['nome' => 'Original']);
        $this->put(route('categorias.update', $categoria), ['nome' => ''])
            ->assertSessionHasErrors('nome');
        $this->assertDatabaseHas('categorias', ['id' => $categoria->id, 'nome' => 'Original']);
    }

    public function test_descricao_e_opcional_e_campos_extras_nao_sao_salvos(): void
    {
        $this->actingAs(User::factory()->create());
        $this->post(route('categorias.store'), ['nome' => 'Livros', 'id' => 999])
            ->assertSessionHasNoErrors()->assertRedirect(route('categorias.index'));
        $this->assertDatabaseHas('categorias', ['nome' => 'Livros', 'descricao' => null]);
        $this->assertDatabaseMissing('categorias', ['id' => 999]);
    }

    public function test_categoria_inexistente_retorna_404(): void
    {
        $this->actingAs(User::factory()->create());
        $this->get(route('categorias.show', 999))->assertNotFound();
        $this->get(route('categorias.edit', 999))->assertNotFound();
        $this->put(route('categorias.update', 999), ['nome' => 'Teste'])->assertNotFound();
        $this->delete(route('categorias.destroy', 999))->assertNotFound();
    }

    public function test_listagem_tem_paginacao_e_escapa_html(): void
    {
        $this->actingAs(User::factory()->create());
        $categoria = Categoria::create(['nome' => '<script>alert(1)</script>']);
        for ($i = 1; $i <= 10; $i++) {
            Categoria::create(['nome' => sprintf('Categoria %02d', $i)]);
        }

        $this->get(route('categorias.index'))->assertOk()
            ->assertSee('Página 1 de 2')->assertSee('Próxima')->assertDontSee('Categoria 10')
            ->assertSee('&lt;script&gt;', false)->assertDontSee('<script>alert(1)</script>', false);
        $this->get(route('categorias.index', ['page' => 2]))->assertOk()->assertSee('Categoria 10')->assertSee('Anterior');
        $this->get(route('categorias.show', $categoria))->assertOk()
            ->assertSee('&lt;script&gt;', false)->assertDontSee('<script>alert(1)</script>', false);
    }
}
