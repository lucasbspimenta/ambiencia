<?php

namespace Database\Seeders;

use App\Models\Agendamento;
use App\Models\User;
use Faker\Generator as Faker;
use Illuminate\Database\Seeder;

class AgendamentoSeeder extends Seeder
{
    public function run(Faker $faker)
    {
        $tipos = [1, 1, 1, 1, 2, 3, 4];
        $agentes = User::get()->where('is_relog', true)->where('is_admin', false)->where('is_gestor', false);

        echo 'Agentes fakes: ' . sizeof($agentes) . PHP_EOL;

        foreach ($agentes as $agente) {
            echo 'Agentes: ' . $agente->name . PHP_EOL;

            //$unidades = $agente->unidades()->take(1)->get()->toArray();
            $unidades = $agente->unidades()->get()->toArray();

            echo 'Unidades: ' . sizeof($unidades) . PHP_EOL;

            if (sizeof($unidades) > 0) {
                //$unidades_selecionadas = array_rand($unidades, 20);

                foreach ($unidades as $idx => $unidade) {
                    //$data = $faker->dateTimeBetween($startDate = '-90 days', $endDate = '+90 days', $timezone = null)->format('Y-m-d');
                    $data = $faker->dateTimeBetween($startDate = '-6 days', $endDate = 'tomorrow', $timezone = null)->format('Y-m-d');

                    $agendamento = [
                        'unidade_id' => $unidade['id'],
                        'inicio' => $data,
                        'final' => $data,
                        'agendamento_tipos_id' => $tipos[$idx] ?? $tipos[array_rand($tipos)],
                        'descricao' => 'Agendamento falso criado por script para teste',
                    ];

                    Agendamento::create($agendamento);
                }
            }
        }
    }
}
