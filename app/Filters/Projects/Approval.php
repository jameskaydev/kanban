<?php

namespace App\Filters\Projects;

use Illuminate\Support\Facades\Storage;

class Approval
{
    public function filter($builder , $value)
    {
        return $builder->where('projects.approval',$value);
    }
}
