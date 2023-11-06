<?php

namespace App\Filters\Projects;

use Illuminate\Support\Facades\Storage;
use App\Libraries\Jalali;
use Carbon\Carbon;
use Morilog\Jalali\CalendarUtils;
use Morilog\Jalali\Jalalian;

class EndDate
{
    public function filter($builder , $value)
    {
        $from = (new Jalalian(1401,10,1))->toCarbon();
        if($value['to'] != ''){
            $persian_number  = CalendarUtils::convertNumbers($value['to'],true);
            $to  = Jalalian::fromFormat('Y/m/d',$persian_number)->toCarbon();
        }else{
            $to = Jalalian::fromCarbon(new Carbon())->toCarbon()->addDay();
        }
        return $builder->whereBetween('projects.end_time',[$from,$to]);
    }
}
