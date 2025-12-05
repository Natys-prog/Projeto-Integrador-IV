<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cargo extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'cargos';

    protected $fillable = [
        'nome',
        'codigo',
        'descricao',
        'nivel',
        'status',
    ];

    protected $casts = [
        'nivel' => 'string',
        'status' => 'string',
    ];

    /**
     * Relacionamento com funcionários
     */
    public function funcionarios()
    {
        return $this->hasMany(Funcionario::class);
    }

    /**
     * Scope para cargos ativos
     */
    public function scopeAtivo($query)
    {
        return $query->where('status', 'ativo');
    }
}
