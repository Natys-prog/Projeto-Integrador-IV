<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Epi extends Model
{
    use HasFactory, SoftDeletes; // Ativa soft deletes

    protected $table = 'epis';
    
    protected $fillable = [
        'nome',
        'tipo_epi_id', // Mudança: agora é FK para tipos_epi
        'codigo',
        'status',
        'fabricante',
        'lote',
        'funcionario_id',
        'data_aquisicao',
        'data_vencimento',
        'descricao',
    ];

    protected $casts = [
        'data_aquisicao' => 'date',
        'data_vencimento' => 'date',
    ];

    protected $dates = ['deleted_at']; // Define deleted_at como data

    /**
     * Relacionamento com TipoEpi
     */
    public function tipoEpi()
    {
        return $this->belongsTo(TipoEpi::class, 'tipo_epi_id');
    }

    /**
     * Relacionamento com Funcionário
     */
    public function funcionario()
    {
        return $this->belongsTo(Funcionario::class);
    }

    /**
     * Verifica se o EPI está próximo ao vencimento
     */
    public function isProximoVencimento($dias = 30)
    {
        return $this->data_vencimento && 
               $this->data_vencimento->diffInDays(now()) <= $dias;
    }

    /**
     * Verifica se o EPI está vencido
     */
    public function isVencido()
    {
        return $this->data_vencimento && 
               $this->data_vencimento->isPast();
    }

    /**
     * Scope para EPIs ativos
     */
    public function scopeAtivos($query)
    {
        return $query->where('status', 'ativo');
    }

    /**
     * Scope para EPIs próximos ao vencimento
     */
    public function scopeProximosVencimento($query, $dias = 30)
    {
        return $query->where('data_vencimento', '<=', now()->addDays($dias))
                    ->where('data_vencimento', '>', now());
    }
}
