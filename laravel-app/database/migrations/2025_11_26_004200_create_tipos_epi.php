<?php
// filepath: c:\Users\mrros\source\repos\Projeto-Integrador-IV\laravel-app\database\migrations\2025_11_26_004200_create_tipos_epi.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Verificar se a tabela já existe antes de criar
        if (!Schema::hasTable('tipos_epi')) {
            Schema::create('tipos_epi', function (Blueprint $table) {
                $table->id();
                $table->string('nome');
                $table->string('codigo', 20)->unique();
                $table->text('descricao')->nullable();
                $table->string('icone', 10)->nullable(); // Para emojis
                $table->string('cor', 7)->default('#667eea'); // Cor hexadecimal
                $table->string('categoria')->nullable();
                $table->integer('validade_meses')->default(12);
                $table->enum('status', ['ativo', 'inativo'])->default('ativo');
                $table->timestamps();
                $table->softDeletes();
                
                $table->index(['status', 'categoria']);
                $table->index('codigo');
            });
            
            // Inserir dados iniciais apenas se a tabela foi criada agora
            $this->insertInitialData();
        } else {
            // Adicionar colunas faltantes se a tabela já existe
            $this->addMissingColumns();
        }
    }

    /**
     * Adicionar colunas que podem estar faltando
     */
    private function addMissingColumns(): void
    {
        Schema::table('tipos_epi', function (Blueprint $table) {
            // Verificar e adicionar coluna icone
            if (!Schema::hasColumn('tipos_epi', 'icone')) {
                $table->string('icone', 10)->nullable();
            }
            
            // Verificar e adicionar coluna cor
            if (!Schema::hasColumn('tipos_epi', 'cor')) {
                $table->string('cor', 7)->default('#667eea');
            }
            
            // Verificar e adicionar coluna categoria se não existir
            if (!Schema::hasColumn('tipos_epi', 'categoria')) {
                $table->string('categoria')->nullable();
            }
        });

        // Verificar se precisa inserir dados que estão faltando
        $this->checkAndInsertMissingData();
    }

    /**
     * Verificar e inserir dados que podem estar faltando
     */
    private function checkAndInsertMissingData(): void
    {
        $existingCodes = DB::table('tipos_epi')->pluck('codigo')->toArray();
        
        if (empty($existingCodes)) {
            // Se não tem dados, inserir todos
            $this->insertInitialData();
        } else {
            // Verificar tipos específicos que podem estar faltando
            $requiredTypes = [
                [
                    'codigo' => 'capacete',
                    'nome' => 'Capacete de Segurança',
                    'icone' => '⛑️',
                    'cor' => '#FF6B35',
                    'categoria' => 'Proteção da Cabeça'
                ],
                [
                    'codigo' => 'oculos', 
                    'nome' => 'Óculos de Proteção',
                    'icone' => '🥽',
                    'cor' => '#4ECDC4',
                    'categoria' => 'Proteção dos Olhos'
                ],
                [
                    'codigo' => 'luvas',
                    'nome' => 'Luvas de Segurança', 
                    'icone' => '🧤',
                    'cor' => '#45B7D1',
                    'categoria' => 'Proteção das Mãos'
                ],
            ];

            $missingTypes = [];
            foreach ($requiredTypes as $type) {
                if (!in_array($type['codigo'], $existingCodes)) {
                    $missingTypes[] = [
                        'nome' => $type['nome'],
                        'codigo' => $type['codigo'],
                        'descricao' => 'Tipo de EPI essencial',
                        'icone' => $type['icone'],
                        'cor' => $type['cor'],
                        'categoria' => $type['categoria'],
                        'validade_meses' => 12,
                        'status' => 'ativo',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
            }

            if (!empty($missingTypes)) {
                DB::table('tipos_epi')->insert($missingTypes);
            }
        }
    }

    /**
     * Inserir dados iniciais dos tipos de EPI
     */
    private function insertInitialData(): void
    {
        $now = now();
        
        DB::table('tipos_epi')->insert([
            [
                'nome' => 'Capacete de Segurança',
                'codigo' => 'CAP',
                'descricao' => 'Proteção craniana contra impactos',
                'categoria' => 'Proteção da Cabeça',
                'validade_meses' => 60,
                'status' => 'ativo',
                'icone' => null,
                'cor' => '#667eea',
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'nome' => 'Óculos de Proteção',
                'codigo' => 'OCU', 
                'descricao' => 'Proteção dos olhos contra partículas e respingos',
                'categoria' => 'Proteção dos Olhos',
                'validade_meses' => 12,
                'status' => 'ativo',
                'icone' => null,
                'cor' => '#667eea',
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'nome' => 'Luvas de Segurança',
                'codigo' => 'LUV',
                'descricao' => 'Proteção das mãos contra riscos diversos',
                'categoria' => 'Proteção das Mãos', 
                'validade_meses' => 6,
                'status' => 'ativo',
                'icone' => null,
                'cor' => '#667eea',
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'nome' => 'Capacete de Segurança',
                'codigo' => 'capacete',
                'descricao' => 'Tipo de EPI essencial',
                'categoria' => 'Proteção da Cabeça',
                'validade_meses' => 12,
                'status' => 'ativo',
                'icone' => '⛑️',
                'cor' => '#FF6B35',
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'nome' => 'Óculos de Proteção',
                'codigo' => 'oculos',
                'descricao' => 'Tipo de EPI essencial',
                'categoria' => 'Proteção dos Olhos',
                'validade_meses' => 12,
                'status' => 'ativo',
                'icone' => '🥽',
                'cor' => '#4ECDC4',
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'nome' => 'Luvas de Segurança',
                'codigo' => 'luvas',
                'descricao' => 'Tipo de EPI essencial',
                'categoria' => 'Proteção das Mãos',
                'validade_meses' => 12,
                'status' => 'ativo',
                'icone' => '🧤',
                'cor' => '#45B7D1',
                'created_at' => $now,
                'updated_at' => $now
            ]
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tipos_epi');
    }
};
