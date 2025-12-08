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
        // 1. Verificar e criar tabela tipos_epi se não existir
        $this->createTiposEpiIfNotExists();
        
        // 2. Criar tabela epis
        Schema::create('epis', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->text('descricao_manual_tipo_epi')->nullable();
            $table->string('codigo')->unique();
            $table->date('data_aquisicao')->nullable();
            $table->date('data_vencimento')->nullable();
            $table->enum('status', ['ativo', 'inativo', 'manutencao', 'descartado'])->default('ativo');
            $table->string('fabricante')->nullable();
            $table->string('lote')->nullable();
            $table->unsignedBigInteger('funcionario_id')->nullable();
            $table->unsignedBigInteger('tipo_epi_id')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['tipo_epi_id', 'status']);
            $table->index('data_vencimento');
            $table->index('funcionario_id');
        });
        
        // 3. Adicionar foreign keys se as tabelas existirem
        $this->addEpiForeignKeys();
    }

    /**
     * Criar tabela tipos_epi se não existir
     */
    private function createTiposEpiIfNotExists(): void
    {
        if (!Schema::hasTable('tipos_epi')) {
            Schema::create('tipos_epi', function (Blueprint $table) {
                $table->id();
                $table->string('nome');
                $table->string('codigo')->unique();
                $table->text('descricao')->nullable();
                $table->string('categoria')->nullable();
                $table->integer('validade_meses')->default(12);
                $table->enum('status', ['ativo', 'inativo'])->default('ativo');
                $table->string('icone')->nullable();
                $table->string('cor')->default('#667eea');
                $table->timestamps();
                $table->softDeletes();
                
                $table->index('status');
                $table->index('categoria');
            });
        }
    }

    /**
     * Adicionar foreign keys se as tabelas existirem
     */
    private function addEpiForeignKeys(): void
    {
        Schema::table('epis', function (Blueprint $table) {
            if (Schema::hasTable('funcionarios')) {
                $table->foreign('funcionario_id')->references('id')->on('funcionarios')->onDelete('set null');
            }
            
            if (Schema::hasTable('tipos_epi')) {
                $table->foreign('tipo_epi_id')->references('id')->on('tipos_epi')->onDelete('set null');
            }
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
