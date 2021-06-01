<?php

namespace Database\Seeders;

use App\Models\Agendamento;
use App\Models\Checklist;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use bfinlay\SpreadsheetSeeder\SpreadsheetSeeder;
use Faker\Generator as Faker;

class AgendamentoSeeder extends Seeder
{
    public function run(Faker $faker)
    {
        $tipos = [1,1,1,1,2,3,4];
        $agentes = User::get()->where('is_relog',true);

        foreach($agentes as $agente)
        {
            $unidades = $agente->unidades->toArray();
            if(sizeof($unidades) > 0) {
                $unidades_selecionadas = array_rand($unidades, 20);

                foreach ($unidades_selecionadas as $idx => $unidade_index) {
                    $data = $faker->dateTimeBetween($startDate = 'now', $endDate = '+30 days', $timezone = null)->format('Y-m-d');

                    $agendamento = [
                        'unidade_id' => $unidades[$unidade_index]['id'],
                        'inicio' => $data,
                        'final' => $data,
                        'agendamento_tipos_id' => $tipos[$idx] ?? $tipos[array_rand($tipos)],
                        'descricao' => 'Agendamento falso criado por script para teste'
                    ];

                    Agendamento::create($agendamento);
                }
            }
        }
    }
}
