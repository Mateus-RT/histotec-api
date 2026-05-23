<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Remove ou comente a linha: User::factory()->create([...])

        // Chama estritamente o seeder com o usuário administrador do sistema (cti.pdl)
        $this->call([
            UsuariosIniciaisSeeder::class,
        ]);
    }
}
