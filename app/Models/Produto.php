<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Produto extends Model
{
    protected $table = 'produtos';

    protected $fillable = ['nome', 'descricao', 'preco', 'categoria_id'];

    protected function casts(): array
    {
        return ['preco' => 'decimal:2'];
    }

    // Cada produto pertence a uma categoria, ligada por categoria_id.
    /** @return BelongsTo<Categoria, $this> */
    public function categoria(): BelongsTo
    {
        return $this->belongsTo(Categoria::class);
    }

    // Um produto pode ter uma ficha técnica.
    /** @return HasOne<FichaTecnica, $this> */
    public function fichaTecnica(): HasOne
    {
        return $this->hasOne(FichaTecnica::class);
    }
}
