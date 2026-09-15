<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// MODEL: representa uma categoria e permite consultar e alterar seus dados.
class Categoria extends Model
{
    // Indicamos explicitamente a tabela para manter o nome em português.
    protected $table = 'categorias';

    // Somente estes campos podem ser preenchidos por create() e update().
    protected $fillable = ['nome', 'descricao'];

    // O Eloquent preenche as datas automaticamente usando estes nomes.
    public const CREATED_AT = 'criado_em';

    public const UPDATED_AT = 'atualizado_em';
}
