<?php

namespace App\Filters\Transactions;


class User
{
    public function filter($builder , $value)
    {
        return $builder->whereIn('user_id',$value);
    }
}
