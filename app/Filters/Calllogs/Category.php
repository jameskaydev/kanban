<?php

namespace App\Filters\Calllogs;

use Illuminate\Support\Facades\Storage;

class Category
{
    public function filter($builder , $value)
    {
        return $builder->whereIn('call_logs.category_id',$value);
    }
}
