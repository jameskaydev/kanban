<?php

namespace App\Filters\Vacations;

use Illuminate\Support\Facades\Storage;
use App\Libraries\Jalali;
class Date
{
    public function filter($builder , $value)
    {
        if(@$this->check_jalali($value['from']) == true){
            $from = $this->jalali_to_gregorian($value['from']);
        }elseif(@$this->check_jalali($value['from']) == ''){
            $from = date('Y-m-d H:i:s',strtotime('2022-1-1 00:00:00'));
        }else{
            $from = $value['from'];
        }
        if(@$this->check_jalali($value['to']) == true){
            $to = $this->jalali_to_gregorian($value['to']);
        }elseif(@$this->check_jalali($value['to']) == ''){
            $to = date('Y-m-d H:i:s');
        }else{
            $to = $value['to'];
        }
        $from = date('Y-m-d H:i:s',strtotime($from));
        $to = date('Y-m-d H:i:s',strtotime($to));
        return $builder->whereBetween('date',[$from,$to]);
    }
    private function jalali_to_gregorian($date){
        $date = explode(' ',$date);
        $from = explode('/',$date[0]);
        $jy = $from[0];
        $jm = $from[1];
        $jd = $from[2];
        $j = new Jalali();
        $from = $j->jalali_to_gregorian($jy,$jm,$jd);
        $from = $from[0] . '-' . $from[1] . '-' . $from[2];
        $hour = $j->tr_num($date[1]);
        return $from . ' ' . $hour;
    }
    private function check_jalali($date){
        $jalali = false;
        if(strpos($date,'۱') != false) $jalali = true;
        if(strpos($date,'۲') != false) $jalali = true;
        if(strpos($date,'۳') != false) $jalali = true;
        if(strpos($date,'۴') != false) $jalali = true;
        if(strpos($date,'۵') != false) $jalali = true;
        if(strpos($date,'۶') != false) $jalali = true;
        if(strpos($date,'۷') != false) $jalali = true;
        if(strpos($date,'۸') != false) $jalali = true;
        if(strpos($date,'۹') != false) $jalali = true;
        if(strpos($date,'۰') != false) $jalali = true;
        return $jalali;
    }
}
