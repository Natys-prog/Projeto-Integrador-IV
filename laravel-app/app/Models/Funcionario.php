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
        'email',
        'telefone',
        'departamento',
        'cargo',
        'data_admissao',
        'data_demissao',
        'status',
        'endereco',
        'cep',
        'cidade',
        'estado',
    ];

    protected $casts = [
        'data_admissao' => 'date',
    ];

    protected $dates = ['deleted_at'];

    /**
     * Relacionamento com EPIs
     */
    public function epis()
    {
        return $this->hasMany(Epi::class);
    }

    /**
     * EPIs ativos do funcionário
     */
    public function episAtivos()
    {
        return $this->epis()->where('status', 'ativo');
    }

    /**
     * EPIs próximos ao vencimento do funcionário
     */
    public function episProximosVencimento($dias = 30)
    {
        return $this->epis()
                   ->where('data_vencimento', '<=', now()->addDays($dias))
                   ->where('data_vencimento', '>', now());
    }

    /**
     * EPIs vencidos do funcionário
     */
    public function episVencidos()
    {
        return $this->epis()
                   ->where('data_vencimento', '<', now());
    }

    /**
     * Verifica se o funcionário tem EPIs vencidos
     */
    public function hasEpisVencidos()
    {
        return $this->episVencidos()->count() > 0;
    }

    /**
     * Scope para funcionários ativos
     */
    public function scopeAtivos($query)
    {
        return $query->where('status', 'ativo');
    }

    /**
     * Scope para funcionários por departamento
     */
    public function scopePorDepartamento($query, $departamento)
    {
        return $query->where('departamento', $departamento);
    }

    /**
     * Scope para funcionários novos (últimos 30 dias)
     */
    public function scopeNovos($query, $dias = 30)
    {
        return $query->where('data_admissao', '>=', now()->subDays($dias));
    }

    /**
     * Get the full name attribute
     */
    public function getFullNameAttribute()
    {
        return $this->nome;
    }

    /**
     * Get formatted CPF
     */
    public function getFormattedCpfAttribute()
    {
        return preg_replace('/(\d{3})(\d{3})(\d{3})(\d{2})/', '$1.$2.$3-$4', $this->cpf);
    }
}
