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
        // 1. Verificar e criar tabelas de dependência primeiro
        $this->createDependencies();
        
        // 2. Criar tabela funcionários
        Schema::create('funcionarios', function (Blueprint $table) {
            $table->id();
            
            // Dados pessoais
            $table->string('nome');
            $table->string('cpf', 14)->unique()->nullable();
            $table->string('rg', 20)->nullable();
            $table->date('data_nascimento')->nullable();
            $table->enum('genero', ['masculino', 'feminino', 'outro', 'prefiro_nao_informar'])->nullable();
            $table->string('email')->unique();
            
            // Dados profissionais
            $table->string('matricula', 20)->unique()->nullable();
            $table->enum('status', ['ativo', 'inativo', 'afastado', 'demitido'])->default('ativo');
            $table->unsignedBigInteger('departamento_id')->nullable();
            $table->unsignedBigInteger('cargo_id')->nullable();
            $table->date('data_admissao')->nullable();
            $table->date('data_demissao')->nullable();
            
            // Contato e endereço
            $table->string('telefone', 20)->nullable();
            $table->string('telefone_emergencia', 20)->nullable();
            $table->string('contato_emergencia', 100)->nullable();
            $table->string('cep', 10)->nullable();
            $table->string('cidade', 100)->nullable();
            $table->string('estado', 2)->nullable();
            $table->string('endereco')->nullable();
            $table->text('endereco_completo')->nullable();
            
            // Observações
            $table->text('observacoes')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
            
            // Índices para performance
            $table->index(['status', 'departamento_id']);
            $table->index('data_admissao');
            $table->index(['nome', 'email']);
        });
        
        // 3. Criar foreign keys após todas as tabelas existirem
        $this->addForeignKeys();
    }

    /**
     * Criar tabelas de dependência se não existirem
     */
    private function createDependencies(): void
    {
        // Criar tabela departamentos se não existir
        if (!Schema::hasTable('departamentos')) {
            Schema::create('departamentos', function (Blueprint $table) {
                $table->id();
                $table->string('nome');
                $table->string('codigo', 10)->unique();
                $table->text('descricao')->nullable();
                $table->string('cor', 7)->default('#667eea'); // Cor hexadecimal
                $table->enum('status', ['ativo', 'inativo'])->default('ativo');
                $table->timestamps();
                $table->softDeletes();
                
                $table->index(['status', 'codigo']);
            });
            
            // Inserir departamentos padrão
            DB::table('departamentos')->insert([
                [
                    'nome' => 'Engenharia',
                    'codigo' => 'ENG',
                    'descricao' => 'Departamento de Engenharia e Projetos',
                    'cor' => '#3498db',
                    'status' => 'ativo',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'nome' => 'Segurança do Trabalho',
                    'codigo' => 'SEG',
                    'descricao' => 'Departamento de Segurança e Saúde Ocupacional',
                    'cor' => '#e74c3c',
                    'status' => 'ativo',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'nome' => 'Produção',
                    'codigo' => 'PROD',
                    'descricao' => 'Departamento de Produção Industrial',
                    'cor' => '#f39c12',
                    'status' => 'ativo',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'nome' => 'Recursos Humanos',
                    'codigo' => 'RH',
                    'descricao' => 'Departamento de Recursos Humanos',
                    'cor' => '#9b59b6',
                    'status' => 'ativo',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'nome' => 'Manutenção',
                    'codigo' => 'MAN',
                    'descricao' => 'Departamento de Manutenção Industrial',
                    'cor' => '#27ae60',
                    'status' => 'ativo',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
            ]);
        }

        // Criar tabela cargos se não existir
        if (!Schema::hasTable('cargos')) {
            Schema::create('cargos', function (Blueprint $table) {
                $table->id();
                $table->string('nome');
                $table->string('codigo', 20)->unique();
                $table->text('descricao')->nullable();
                $table->enum('nivel', ['operacional', 'tecnico', 'superior', 'gerencial'])->default('operacional');
                $table->enum('status', ['ativo', 'inativo'])->default('ativo');
                $table->timestamps();
                $table->softDeletes();
                
                $table->index(['status', 'nivel']);
            });
            
            // Inserir cargos padrão
            DB::table('cargos')->insert([
                [
                    'nome' => 'Engenheiro Civil',
                    'codigo' => 'ENG_CIVIL',
                    'descricao' => 'Responsável por projetos de engenharia civil',
                    'nivel' => 'superior',
                    'status' => 'ativo',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'nome' => 'Técnico de Segurança',
                    'codigo' => 'SEG_TEC',
                    'descricao' => 'Responsável pela segurança do trabalho',
                    'nivel' => 'tecnico',
                    'status' => 'ativo',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'nome' => 'Operador de Produção',
                    'codigo' => 'PROD_OP',
                    'descricao' => 'Operador de máquinas e equipamentos de produção',
                    'nivel' => 'operacional',
                    'status' => 'ativo',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'nome' => 'Analista de RH',
                    'codigo' => 'RH_ANALISTA',
                    'descricao' => 'Analista de recursos humanos',
                    'nivel' => 'tecnico',
                    'status' => 'ativo',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'nome' => 'Mecânico de Manutenção',
                    'codigo' => 'MAN_MEC',
                    'descricao' => 'Mecânico responsável pela manutenção de equipamentos',
                    'nivel' => 'tecnico',
                    'status' => 'ativo',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
            ]);
        }
    }

    /**
     * Adicionar foreign keys após todas as tabelas existirem
     */
    private function addForeignKeys(): void
    {
        Schema::table('funcionarios', function (Blueprint $table) {
            if (Schema::hasTable('departamentos')) {
                $table->foreign('departamento_id')->references('id')->on('departamentos')->onDelete('set null');
            }
            
            if (Schema::hasTable('cargos')) {
                $table->foreign('cargo_id')->references('id')->on('cargos')->onDelete('set null');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('funcionarios');
        // Não dropar departamentos e cargos aqui pois podem ser usados por outras tabelas
    }
};
