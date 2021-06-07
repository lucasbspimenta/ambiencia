<?php

namespace App\Http\Helpers;

use Carbon\Carbon;

class DateHelper extends Carbon
{
    public static function getInicioTrimestre($data)
    {
        switch(self::getTrimestre($data)){
            case 1:
                return Carbon::parse('first day of january ' . $data->format('Y'));
            case 2:
                return Carbon::parse('first day of april ' . $data->format('Y'));
            case 3:
                return Carbon::parse('first day of july ' . $data->format('Y'));
            case 4:
                return Carbon::parse('first day of october ' . $data->format('Y'));
        }
    }

    public static function getFinalTrimestre($data)
    {
        switch(self::getTrimestre($data)){
            case 1:
                return Carbon::parse('last day of march ' . $data->format('Y'));
            case 2:
                return Carbon::parse('last day of june ' . $data->format('Y'));
            case 3:
                return Carbon::parse('last day of september ' . $data->format('Y'));
            case 4:
                return Carbon::parse('last day of december ' . $data->format('Y'));
        }
    }

    public static function getTrimestre($data){
        $mes = $data->format('m');

        if($mes <= 3) return 1;
        if($mes <= 6) return 2;
        if($mes <= 9) return 3;

        return 4;
    }
}
