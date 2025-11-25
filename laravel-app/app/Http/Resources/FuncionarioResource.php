<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FuncionarioResource extends JsonResource
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
            'cpf' => $this->cpf,
            'cpf_formatado' => $this->formatted_cpf,
            'email' => $this->email,
            'telefone' => $this->telefone,
            'departamento' => $this->departamento,
            'cargo' => $this->cargo,
            'data_admissao' => $this->data_admissao?->format('Y-m-d'),
            'data_demissao' => $this->data_demissao?->format('Y-m-d'),
            'status' => $this->status,
            'endereco' => $this->endereco,
            'cep' => $this->cep,
            'cidade' => $this->cidade,
            'estado' => $this->estado,
            'epis' => EpiResource::collection($this->whenLoaded('epis')),
            'epis_count' => $this->whenLoaded('epis', function () {
                return $this->epis->count();
            }),
            'epis_ativos_count' => $this->whenLoaded('episAtivos', function () {
                return $this->episAtivos->count();
            }),
            'epis_vencidos_count' => $this->whenLoaded('episVencidos', function () {
                return $this->episVencidos->count();
            }),
            'has_epis_vencidos' => $this->hasEpisVencidos(),
            'tempo_empresa_dias' => $this->data_admissao ? 
                $this->data_admissao->diffInDays(now()) : null,
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at?->format('Y-m-d H:i:s'),
            'deleted_at' => $this->deleted_at?->format('Y-m-d H:i:s'),
        ];
    }
}
