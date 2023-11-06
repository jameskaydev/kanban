<?php

namespace App\Filters\Projects;

use Illuminate\Support\Facades\Storage;

class Title
{
    public function filter($builder , $value)
    {
        return $builder->where('title','like','%'.$value.'%');
    }
}
