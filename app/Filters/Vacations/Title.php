<?php

namespace App\Filters\Vacations;


class Title
{
    public function filter($builder , $value)
    {
        return $builder->where('title','like','%'.$value.'%');
    }
}
