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
            $table->date('data_aquisicao')->nullable();
            $table->date('data_vencimento')->nullable();
            $table->enum('status', ['ativo', 'inativo', 'manutencao', 'descartado'])->default('ativo');
            $table->string('fabricante')->nullable();
            $table->string('lote')->nullable();
            $table->unsignedBigInteger('funcionario_id')->nullable();
            $table->unsignedBigInteger('tipo_epi_id')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['tipo', 'status']);
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
                $table->string('codigo', 20)->unique();
                $table->text('descricao')->nullable();
                $table->string('categoria')->nullable();
                $table->integer('validade_meses')->default(12);
                $table->enum('status', ['ativo', 'inativo'])->default('ativo');
                $table->timestamps();
                $table->softDeletes();
            });
            
            // Inserir tipos básicos
            DB::table('tipos_epi')->insert([
                [
                    'nome' => 'Capacete de Segurança',
                    'codigo' => 'CAP',
                    'descricao' => 'Proteção craniana contra impactos',
                    'categoria' => 'Proteção da Cabeça',
                    'validade_meses' => 60,
                    'status' => 'ativo',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'nome' => 'Óculos de Proteção',
                    'codigo' => 'OCU',
                    'descricao' => 'Proteção dos olhos contra partículas e respingos',
                    'categoria' => 'Proteção dos Olhos',
                    'validade_meses' => 12,
                    'status' => 'ativo',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'nome' => 'Luvas de Segurança',
                    'codigo' => 'LUV',
                    'descricao' => 'Proteção das mãos contra riscos diversos',
                    'categoria' => 'Proteção das Mãos',
                    'validade_meses' => 6,
                    'status' => 'ativo',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
            ]);
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
