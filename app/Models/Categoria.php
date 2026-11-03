<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

// MODEL: representa uma categoria e permite consultar e alterar seus dados.

class Categoria extends Model
{
    protected $table = 'categorias';

    protected $fillable = ['nome', 'descricao'];

    // Uma categoria pode ter vários produtos.
    /** @return HasMany<Produto, $this> */
    public function produtos(): HasMany
    {
        return $this->hasMany(Produto::class);
    }
}
