<?php

namespace App\Filters\Users;


class Fullname
{
    public function filter($builder , $value)
    {
        return $builder->where('fullname','like','%'.$value.'%');
    }
}
