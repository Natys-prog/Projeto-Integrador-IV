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
        Schema::create('funcionarios', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->string('cpf', 11)->unique();
            $table->string('email')->unique();
            $table->string('telefone')->nullable();
            $table->string('departamento');
            $table->string('cargo');
            $table->date('data_admissao');
            $table->date('deleted_at')->nullable();
            $table->enum('status', ['ativo', 'inativo', 'afastado'])->default('ativo');
            $table->text('endereco')->nullable();
            $table->string('cep', 8)->nullable();
            $table->string('cidade')->nullable();
            $table->string('estado', 2)->nullable();
            $table->timestamps();

            $table->index(['departamento', 'status']);
            $table->index('data_admissao');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('funcionarios');
    }
};
