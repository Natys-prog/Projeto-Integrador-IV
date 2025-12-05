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
        $this->call([
            UsuarioSeeder::class,      // Usuários de teste
            TiposEpiSeeder::class,      // Tipos de EPI (já tem dados da migration)
            DepartamentosSeeder::class, // Departamentos
            CargosSeeder::class,        // Cargos
            FuncionariosSeeder::class,   // Funcionários (se existir)
            EpiSeeder::class,           // EPIs (se existir)
        ]);
        
        $this->command->info('✅ Database seeded successfully!');
    }
}
