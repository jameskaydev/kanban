<?php

namespace App\Filters\Users;


class Status
{
    public function filter($builder , $value)
    {
        return $builder->where('status',$value);
    }
}
