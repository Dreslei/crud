<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Cria o usuário de teste somente se o e-mail ainda não existir.
        User::firstOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Usuário de Teste',
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
            ],
        );

        // A factory gera os nomes, e-mails e demais dados fictícios.
        User::factory()->count(10)->create();
    }
}
