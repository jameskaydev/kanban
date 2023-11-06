<?php

namespace App\Filters\Projects;

use Illuminate\Support\Facades\Storage;
use App\Models\Projects;

class PaymentStatus
{
    public function filter($builder , $value)
    {
        return $builder->where(function($q) use ($value,$builder){
            $q->where([
                ['cast_payment_status','=',$value['status']],
                ['to_user','=',$value['user_id']]
            ])->orWhere([
                ['payment_status','=',$value['status']],
                ['user_id','=',$value['user_id']]
            ]);
        });
    }
}
