<?php
// Executar: php artisan make:model TipoEpi
// filepath: c:\Users\mrros\source\repos\Projeto-Integrador-IV\laravel-app\app\Models\TipoEpi.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoEpi extends Model
{
    use HasFactory;

    protected $table = 'tipos_epi';

    protected $fillable = [
        'nome',
        'codigo',
        'descricao',
        'icone',
        'cor',
        'ativo'
    ];

    protected $casts = [
        'ativo' => 'boolean',
    ];

    // Relacionamento com EPIs
    public function epis()
    {
        return $this->hasMany(Epi::class, 'tipo_epi_id');
    }

    // Scopes
    public function scopeAtivos($query)
    {
        return $query->where('status', 'ativo');
    }

    public function scopePorCodigo($query, $codigo)
    {
        return $query->where('codigo', $codigo);
    }
}