<?php

namespace App\Filters\Calllogs;

use Illuminate\Support\Facades\Storage;

class Status
{
    public function filter($builder , $value)
    {
        return $builder->where('call_logs.status',$value);
    }
}
