<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

// Remova o acento do nome da classe aqui se ele ainda existir
class UsuariosIniciaisSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['username' => 'cti.pdl'],
            [
                'name' => 'Coordenação de Tecnologia da Informação',
                'email' => 'cti.pdl@ifmt.edu.br',
                'password' => Hash::make('Admin@123'),
                'is_admin' => true,
            ]
        );
    }
}
