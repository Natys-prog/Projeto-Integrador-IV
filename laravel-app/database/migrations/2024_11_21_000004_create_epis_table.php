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
            $table->enum('tipo', [
                'capacete', 
                'oculos', 
                'luvas', 
                'botas', 
                'cinto_seguranca', 
                'mascara', 
                'protetor_auditivo',
                'colete_refletivo',
                'outros'
            ]);
            $table->text('descricao')->nullable();
            $table->string('codigo')->unique();
            $table->date('data_aquisicao');
            $table->date('data_vencimento')->nullable();
            $table->enum('status', ['ativo', 'inativo', 'manutencao', 'descartado'])->default('ativo');
            $table->string('fabricante')->nullable();
            $table->string('lote')->nullable();
            $table->foreignId('funcionario_id')->nullable()->constrained('funcionarios')->onDelete('set null');
            $table->timestamps();

            $table->index(['tipo', 'status']);
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
