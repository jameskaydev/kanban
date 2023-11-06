<?php

namespace App\Filters\Calllogs;

use Illuminate\Support\Facades\Storage;

class Accounts
{
    public function filter($builder , $value)
    {
        return $builder->whereIn('call_logs.account_id',$value);
    }
}
