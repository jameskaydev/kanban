<?php

namespace App\Filters\Users;


class Access_id
{
    public function filter($builder , $value)
    {
        return $builder->where('access_id',$value);
    }
}
