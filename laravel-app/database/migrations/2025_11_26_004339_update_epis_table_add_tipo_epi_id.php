<?php
// Executar: php artisan make:migration update_epis_table_add_tipo_epi_id
// filepath: c:\Users\mrros\source\repos\Projeto-Integrador-IV\laravel-app\database\migrations\2024_11_25_update_epis_table_add_tipo_epi_id.php

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
        Schema::table('epis', function (Blueprint $table) {
            // Adicionar nova coluna
            $table->unsignedBigInteger('tipo_epi_id')->nullable()->after('nome');
            
            // Adicionar foreign key
            $table->foreign('tipo_epi_id')->references('id')->on('tipos_epi')->onDelete('restrict');
            
            // Manter a coluna tipo temporariamente para migração de dados
            // $table->string('tipo')->nullable()->change(); // Deixar nullable temporariamente
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('epis', function (Blueprint $table) {
            $table->dropForeign(['tipo_epi_id']);
            $table->dropColumn('tipo_epi_id');
        });
    }
};
