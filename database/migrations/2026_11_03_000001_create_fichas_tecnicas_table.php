<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fichas_tecnicas', function (Blueprint $table) {
            $table->id();
            // unique() garante que cada produto tenha no máximo uma ficha (1:1).
            $table->foreignId('produto_id')->unique()->constrained('produtos')->restrictOnDelete();
            $table->string('marca', 100);
            $table->string('modelo', 100);
            $table->text('especificacoes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fichas_tecnicas');
    }
};
