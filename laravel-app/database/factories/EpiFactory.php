<?php

namespace Database\Factories;

use App\Models\Epi;
use App\Models\Funcionario;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Epi>
 */
class EpiFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Epi::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $tipos = ['capacete', 'oculos', 'luvas', 'botas', 'cinto_seguranca', 'mascara', 'protetor_auditivo', 'colete_refletivo'];
        $tipo = $this->faker->randomElement($tipos);
        
        $nomes = [
            'capacete' => ['Capacete de Segurança', 'Capacete Industrial', 'Capacete com Carneira'],
            'oculos' => ['Óculos de Proteção', 'Óculos de Segurança', 'Óculos Anti-Respingo'],
            'luvas' => ['Luvas de Segurança', 'Luvas de Proteção', 'Luvas Industriais'],
            'botas' => ['Bota de Segurança', 'Bota Industrial', 'Bota com Biqueira'],
            'cinto_seguranca' => ['Cinto de Segurança', 'Cinto Paraquedista', 'Cinto Industrial'],
            'mascara' => ['Máscara de Proteção', 'Máscara Respiratória', 'Máscara Industrial'],
            'protetor_auditivo' => ['Protetor Auricular', 'Abafador de Ruído', 'Protetor de Ouvido'],
            'colete_refletivo' => ['Colete Refletivo', 'Colete de Segurança', 'Colete Sinalizador'],
        ];

        $fabricantes = ['3M', 'MSA', 'Honeywell', 'Uvex', 'Ansell', 'Kimberly-Clark', 'Marluvas', 'Bracol', 'Altiseg', 'Delta Plus'];
        
        return [
            'nome' => $this->faker->randomElement($nomes[$tipo]) . ' ' . $this->faker->word(),
            'tipo' => $tipo,
            'descricao' => $this->faker->sentence(),
            'codigo' => strtoupper($this->faker->unique()->bothify('???###')),
            'data_aquisicao' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'data_vencimento' => $this->faker->dateTimeBetween('now', '+3 years'),
            'status' => $this->faker->randomElement(['ativo', 'ativo', 'ativo', 'manutencao', 'inativo']), // 60% ativo
            'fabricante' => $this->faker->randomElement($fabricantes),
            'lote' => 'LT' . $this->faker->year() . $this->faker->numberBetween(1000, 9999),
            'funcionario_id' => $this->faker->boolean(70) ? Funcionario::factory() : null, // 70% assigned
        ];
    }
}
