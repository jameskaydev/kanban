<?php

namespace App\Filters\Projects;

use Illuminate\Support\Facades\Storage;

class Type
{
    public function filter($builder , $value)
    {
        return $builder->where('type',$value);
    }
}
