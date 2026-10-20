<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Produto extends Model
{
    protected $table = 'produtos';

    protected $fillable = ['nome', 'descricao', 'preco', 'categoria_id'];

    protected $casts = ['preco' => 'decimal:2'];

    public function categoria(): BelongsTo
    {
        return $this->belongsTo(Categoria::class);
    }
}
