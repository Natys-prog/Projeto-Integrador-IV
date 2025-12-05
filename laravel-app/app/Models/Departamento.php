<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Departamento extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'departamentos';

    protected $fillable = [
        'nome',
        'codigo',
        'descricao',
        'cor',
        'status',
    ];

    protected $casts = [
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
     * Scope para departamentos ativos
     */
    public function scopeAtivo($query)
    {
        return $query->where('status', 'ativo');
    }
}
