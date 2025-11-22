<?php

namespace Database\Factories;

use App\Models\Funcionario;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Funcionario>
 */
class FuncionarioFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Funcionario::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $departamentos = ['producao', 'manutencao', 'logistica', 'qualidade', 'seguranca'];
        $cargos = [
            'producao' => ['Operador de Máquina', 'Operador de Produção', 'Auxiliar de Produção', 'Supervisor de Produção'],
            'manutencao' => ['Técnico de Manutenção', 'Eletricista', 'Mecânico', 'Supervisor de Manutenção'],
            'logistica' => ['Auxiliar de Logística', 'Operador de Empilhadeira', 'Conferente', 'Supervisor de Logística'],
            'qualidade' => ['Inspetor de Qualidade', 'Analista de Qualidade', 'Técnico em Qualidade'],
            'seguranca' => ['Técnico de Segurança', 'Inspetor de Segurança', 'Analista de Segurança'],
        ];

        $departamento = $this->faker->randomElement($departamentos);
        
        return [
            'nome' => $this->faker->name(),
            'cpf' => $this->faker->unique()->numerify('###########'),
            'email' => $this->faker->unique()->safeEmail(),
            'telefone' => $this->faker->phoneNumber(),
            'departamento' => $departamento,
            'cargo' => $this->faker->randomElement($cargos[$departamento]),
            'data_admissao' => $this->faker->dateTimeBetween('-2 years', 'now'),
            'status' => $this->faker->randomElement(['ativo', 'ativo', 'ativo', 'inativo']), // 75% ativo
            'endereco' => $this->faker->streetAddress(),
            'cep' => $this->faker->numerify('########'),
            'cidade' => $this->faker->city(),
            'estado' => $this->faker->stateAbbr(),
        ];
    }
}
