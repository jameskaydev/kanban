<?php

namespace App\Filters\Users;


class Email
{
    public function filter($builder , $value)
    {
        return $builder->where('email',$value);
    }
}
