<?php

namespace App\Filters\Projects;

use Illuminate\Support\Facades\Storage;

class Status
{
    public function filter($builder , $value)
    {
        return $builder->where('projects.status',$value);
    }
}
