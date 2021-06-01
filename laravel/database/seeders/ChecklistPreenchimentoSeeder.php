<?php

namespace Database\Seeders;

use App\Models\Checklist;
use App\Models\ChecklistResposta;
use App\Models\Demanda;
use App\Models\DemandaSistema;
use App\Services\ImagemService;
use Illuminate\Database\Seeder;
use Faker\Generator as Faker;
use Intervention\Image\Facades\Image;

class ChecklistPreenchimentoSeeder extends Seeder
{
    public function random_pic($dir)
    {
        $files = glob($dir . '/*.*');
        $file = array_rand($files);
        return $files[$file];
    }

    public function run(Faker $faker)
    {
        $respostas_possiveis = [1,0,-1];
        $checklists = Checklist::all();
        $checklists = $checklists->shuffle()->skip(intdiv($checklists->count(),4));

        $conta = intdiv($checklists->count(),2);
        $soma = 1;
        foreach($checklists as $checklist)
        {
            if($soma == $conta)
            {
                $soma = 1;
                array_pop($respostas_possiveis);
            }
            $abrirDemandas = rand(0,1) == 1;

            foreach($checklist->respostas as $resposta)
            {
                if($resposta->item->foto == 'S')
                {
                    $img = $this->random_pic(realpath('./database/seeders/imagens/checklists/'));

                    $resposta->foto = (string)Image::make($img)
                        ->resize(ImagemService::WIDTH, ImagemService::HEIGHT, function ($constraint) {
                            $constraint->aspectRatio();
                            $constraint->upsize();
                        })
                        ->encode('data-url');
                }

                $resposta->resposta = $respostas_possiveis[array_rand($respostas_possiveis)];

                if($abrirDemandas)
                    $this->geraDemanda($resposta);

                $resposta->save();
            }

            $soma++;
        }

        $checklists_com_100_porcento = Checklist::get()->where('percentual_preenchimento','>=',100);
        foreach($checklists_com_100_porcento as $checklist_para_finalizar){
            $checklist_para_finalizar->concluido = true;
            $checklist_para_finalizar->save();
        }
    }

    public function geraDemanda(&$resposta)
    {
        if($resposta->resposta != -1)
            return true;

        $sistemas = DemandaSistema::all();
        $sistema_selecionado = array_rand($sistemas->toArray());
        $sistema = $sistemas[$sistema_selecionado];

        $itens = $sistema->itens;
        $item_selecionado = array_rand($itens->toArray());
        $item = $itens[$item_selecionado];

        $dados_demandas = [
            'sistema_id' => $sistema->id,
            'sistema_item_id' => $item->id,
            'descricao' => 'Demanda de teste aberta automaticamente',
            'unidade_id' => $resposta->checklist->agendamento->unidade->id,
            'migracao' => 'P'
        ];

        $demanda = Demanda::create($dados_demandas);

        $resposta->demandas()->attach($demanda);

        return true;
    }
}
