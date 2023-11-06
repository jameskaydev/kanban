<?php

namespace App\Filters\Projects;

use Illuminate\Support\Facades\Storage;

class Link
{
    public function filter($builder , $value)
    {
        return $builder->where('link',$value);
    }
}
