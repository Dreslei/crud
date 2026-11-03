<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // A migration define a estrutura da tabela no banco de dados.
    public function up(): void
    {
        Schema::create('categorias', function (Blueprint $table) {
            $table->id();
            $table->string('nome', 100);
            $table->text('descricao')->nullable();
            $table->timestamps();
        });
    }

    // O Laravel chama down() quando esta migration é desfeita.
    public function down(): void
    {
        Schema::dropIfExists('categorias');
    }
};
