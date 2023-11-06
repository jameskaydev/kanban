<?php

namespace App\Filters\Calllogs;

use Illuminate\Support\Facades\Storage;

class Username
{
    public function filter($builder , $value)
    {
        return $builder->where('username','like','%'.$value.'%');
    }
}
