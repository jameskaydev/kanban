<?php

namespace App\Filters\Projects;

use Illuminate\Support\Facades\Storage;

class Employers
{
    public function filter($builder , $value)
    {

        return $builder->where('employer_id',$value);
    }
}
