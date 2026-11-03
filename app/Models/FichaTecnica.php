<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FichaTecnica extends Model
{
    protected $table = 'fichas_tecnicas';

    protected $fillable = ['produto_id', 'marca', 'modelo', 'especificacoes'];

    // Cada ficha pertence a um produto.
    /** @return BelongsTo<Produto, $this> */
    public function produto(): BelongsTo
    {
        return $this->belongsTo(Produto::class);
    }
}
