<?php

namespace App\Filters\Calllogs;

use Illuminate\Support\Facades\Storage;

class User
{
    public function filter($builder , $value)
    {
        return $builder->whereIn('user_id',$value);
    }
}
