<?php

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
        Schema::create('epis', function (Blueprint $table) {
            $table->id();
            $table->string('nome');

            $table->unsignedBigInteger('tipo_epi_id')->nullable()->after('nome');
            
            // Adicionar foreign key
            $table->foreign('tipo_epi_id')->references('id')->on('tipos_epi')->onDelete('restrict');
            
            $table->text('descricao')->nullable();
            $table->string('codigo')->unique();
            $table->date('data_aquisicao');
            $table->date('data_vencimento')->nullable();
            $table->enum('status', ['ativo', 'inativo', 'manutencao', 'descartado'])->default('ativo');
            $table->string('fabricante')->nullable();
            $table->string('lote')->nullable();
            $table->foreignId('funcionario_id')->nullable()->constrained('funcionarios')->onDelete('set null');
            $table->timestamps();

            $table->index('data_vencimento');
            $table->index('funcionario_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('epis');
    }
};
