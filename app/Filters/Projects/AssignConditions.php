<?php

namespace App\Filters\Projects;

use App\Models\Casts;
use Illuminate\Support\Facades\Storage;
use Morilog\Jalali\Jalalian;

class AssignConditions
{
    public function filter($builder , $value)
    {
        if($value != ""){
                $v= explode("/",$value['date']);
                $from = (new Jalalian($v[0],$v[1],1))->toCarbon();
                $to = (new Jalalian($v[0],$v[1],$v[2]))->toCarbon();
                $to = $to->addDays(1);
                $user_id = $value['user_id'];
                return $builder ->where(function ($q) use ($to, $from, $user_id) {
                    $q->where([
                        ['price_type', '=', 'variable'],
                        ['clearance_time', '<', $to],
                        ['clearance_time', '>=', $from],
                    ])->orWhere([
                        ['price_type', 'fixed'],
                        ['pay', 'yes'],
                        ['early_pay_checked_time', '<', $to],
                        ['early_pay_checked_time', '>=', $from],
                    ])->orWhere([
                        ['price_type', 'fixed'],
                        ['clearance_time', '<', $to],
                        ['clearance_time', '>=', $from],
                    ])->orWhere('user_id',$user_id);
                });
        }
    }
}
