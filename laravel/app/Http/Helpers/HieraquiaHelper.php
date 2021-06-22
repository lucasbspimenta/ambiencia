<?php
namespace App\Http\Helpers;

class HieraquiaHelper
{
    public static function Agrupador($itens, $niveis, $nivel_atual)
    {
        if (is_countable($itens) && $itens->count() && isset($niveis[$nivel_atual])) {
            $saida = [];
            foreach ($itens as $key => $itens) {
                $saida[$key] = (isset($niveis[$nivel_atual + 1])) ? self::Agrupador($itens->groupBy($niveis[$nivel_atual + 1]), $niveis, $nivel_atual + 1) : $itens;
            }
            return $saida;
        } else {
            return $itens;
        }

    }
}
