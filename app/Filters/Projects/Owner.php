<?php

namespace App\Filters\Projects;

use Illuminate\Support\Facades\Storage;

class Owner
{
    public function filter($builder , $value)
    {

        return $builder->where(function($q) use ($value){
            $q->where('user_id',$value)
            ->orWhere('to_user',$value);
        });
    }
}
