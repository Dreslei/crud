<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fornecedores', function (Blueprint $table) {
            $table->id();
            $table->string('nome', 100);
            $table->string('email')->nullable();
            $table->string('telefone', 30)->nullable();
            $table->timestamps();
        });

        // A tabela intermediária guarda os vínculos N:N entre fornecedores e produtos.
        Schema::create('fornecedor_produto', function (Blueprint $table) {
            $table->foreignId('fornecedor_id')->constrained('fornecedores')->cascadeOnDelete();
            $table->foreignId('produto_id')->constrained('produtos')->cascadeOnDelete();
            // Impede cadastrar o mesmo vínculo duas vezes.
            $table->primary(['fornecedor_id', 'produto_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fornecedor_produto');
        Schema::dropIfExists('fornecedores');
    }
};
