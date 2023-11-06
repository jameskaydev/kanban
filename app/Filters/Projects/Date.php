<?php

namespace App\Filters\Projects;

use Illuminate\Support\Facades\Storage;
use App\Libraries\Jalali;
use Carbon\Carbon;
use Morilog\Jalali\CalendarUtils;
use Morilog\Jalali\Jalalian;

class Date
{
    public function filter($builder , $value)
    {
        if($value['from'] != ''){
            $persian_number  = CalendarUtils::convertNumbers($value['from'],true);
            $from  = Jalalian::fromFormat('Y/m/d',$persian_number)->toCarbon();
        }else{
            $from = (new Jalalian(1401,1,1))->toCarbon();
        }
        if($value['to'] != ''){
            $persian_number  = CalendarUtils::convertNumbers($value['to'],true);
            $to  = Jalalian::fromFormat('Y/m/d',$persian_number)->toCarbon();
        }else{
            $to = Jalalian::fromCarbon(new Carbon())->toCarbon()->addDay();
        }
        return $builder->whereBetween('projects.created_at',[$from,$to]);
    }
}
