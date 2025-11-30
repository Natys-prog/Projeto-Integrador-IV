<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Funcionario extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'funcionarios';

    protected $fillable = [
        'nome',
        'cpf',
        'rg',
        'data_nascimento',
        'genero',
        'email',
        'matricula',
        'status',
        'departamento_id',
        'cargo_id',
        'data_admissao',
        'data_demissao',
        'telefone',
        'telefone_emergencia',
        'contato_emergencia',
        'cep',
        'cidade',
        'estado',
        'endereco',
        'endereco_completo',
        'observacoes',
    ];

    protected $casts = [
        'data_nascimento' => 'date',
        'data_admissao' => 'date',
        'data_demissao' => 'date',
        'status' => 'string',
        'genero' => 'string',
    ];

    /**
     * Relacionamento com departamento
     */
    public function departamento()
    {
        return $this->belongsTo(Departamento::class);
    }

    /**
     * Relacionamento com cargo
     */
    public function cargo()
    {
        return $this->belongsTo(Cargo::class);
    }

    /**
     * Relacionamento com EPIs
     */
    public function epis()
    {
        return $this->hasMany(Epi::class);
    }

    /**
     * Scope para funcionários ativos
     */
    public function scopeAtivo($query)
    {
        return $query->where('status', 'ativo');
    }
}
