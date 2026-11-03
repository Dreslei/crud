<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Fornecedor extends Model
{
    protected $table = 'fornecedores';

    protected $fillable = ['nome', 'email', 'telefone'];

    /** @return BelongsToMany<Produto, $this> */
    public function produtos(): BelongsToMany
    {
        return $this->belongsToMany(Produto::class, 'fornecedor_produto');
    }
}
