<?php
// Executar no terminal: php artisan make:migration create_tipos_epi_table
// filepath: c:\Users\mrros\source\repos\Projeto-Integrador-IV\laravel-app\database\migrations\2024_11_25_create_tipos_epi_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tipos_epi', function (Blueprint $table) {
            $table->id();
            $table->string('nome', 100)->unique(); // Ex: "Capacete de Segurança"
            $table->string('codigo', 50)->unique(); // Ex: "capacete"
            $table->string('descricao')->nullable();
            $table->string('icone', 10)->default('🦺'); // Emoji/ícone
            $table->string('cor', 7)->default('#667eea'); // Cor hexadecimal
            $table->boolean('ativo')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tipos_epi');
    }
};
