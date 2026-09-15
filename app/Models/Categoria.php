<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// MODEL: representa uma categoria e permite consultar e alterar seus dados.

class Categoria extends Model
{
    protected $table = 'categorias';
    protected $fillable = ['nome', 'descricao'];

}

