<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('categorias', 'criado_em')) {
            Schema::table('categorias', function (Blueprint $table) {
                $table->renameColumn('criado_em', 'created_at');
                $table->renameColumn('atualizado_em', 'updated_at');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('categorias', 'created_at')) {
            Schema::table('categorias', function (Blueprint $table) {
                $table->renameColumn('created_at', 'criado_em');
                $table->renameColumn('updated_at', 'atualizado_em');
            });
        }
    }
};
