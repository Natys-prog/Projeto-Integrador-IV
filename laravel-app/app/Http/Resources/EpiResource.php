<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EpiResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'nome' => $this->nome,
            'tipo' => $this->tipo,
            'descricao' => $this->descricao,
            'codigo' => $this->codigo,
            'data_aquisicao' => $this->data_aquisicao?->format('Y-m-d'),
            'data_vencimento' => $this->data_vencimento?->format('Y-m-d'),
            'status' => $this->status,
            'fabricante' => $this->fabricante,
            'lote' => $this->lote,
            'funcionario_id' => $this->funcionario_id,
            'funcionario' => $this->whenLoaded('funcionario', function () {
                return [
                    'id' => $this->funcionario->id,
                    'nome' => $this->funcionario->nome,
                    'departamento' => $this->funcionario->departamento,
                    'cargo' => $this->funcionario->cargo,
                ];
            }),
            'is_vencido' => $this->isVencido(),
            'is_proximo_vencimento' => $this->isProximoVencimento(),
            'dias_para_vencimento' => $this->data_vencimento ? 
                $this->data_vencimento->diffInDays(now(), false) : null,
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at?->format('Y-m-d H:i:s'),
            'deleted_at' => $this->deleted_at?->format('Y-m-d H:i:s'),
        ];
    }
}
