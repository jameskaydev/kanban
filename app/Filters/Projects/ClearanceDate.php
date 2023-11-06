<?php

namespace App\Filters\Projects;

use Illuminate\Support\Facades\Storage;
use App\Libraries\Jalali;
use Morilog\Jalali\CalendarUtils;
use Morilog\Jalali\Jalalian;

class ClearanceDate
{
    public function filter($builder , $value)
    {
        if($value['from'] != ''){
            $from_is_jalali = $this->check_jalali($value['from']);
            if($from_is_jalali){
                $from = $this->jalali_to_gregorian($value['from']);
                $from = $from->toCarbon();
            }else{
                $from = Jalalian::fromDateTime($value['from'])->toCarbon();
            }
        }else{
            $from = date('Y/m/d',strtotime('2020-01-01 01:01:01'));
        }
        if($value['to'] != ''){
            $to_is_jalali = $this->check_jalali($value['to']);
            if($to_is_jalali){
                $to = $this->jalali_to_gregorian($value['to']);
                $to = $to->addDays(1);
                $to = $to->toCarbon();
            }else{
                $from = Jalalian::fromDateTime($value['to'])->addDays(1)->toCarbon();
            }
        }else{
            $to = date('Y/m/d');
        }
        return $builder->whereBetween('projects.clearance_time',[$from,$to]);
    }
    private function jalali_to_gregorian($date){
        $converted = CalendarUtils::convertNumbers($date,true);
        $ex = explode(' ',$converted);
        $ex = explode('/',$ex[0]);

        return (new Jalalian($ex[0],$ex[1],$ex[2]));
    }
    private function check_jalali($date){
        $converted = CalendarUtils::convertNumbers($date,true);
        $ex = explode(' ',$converted);
        $ex = explode('/',$ex[0]);

        return CalendarUtils::checkDate($ex[0],$ex[1],$ex[2]);
    }
}