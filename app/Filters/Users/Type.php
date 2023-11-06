<?php

namespace App\Filters\Users;


class Type
{
    public function filter($builder , $value)
    {
        if($value == "employee"){
            return $builder->where('employee',"yes");
        }
        if($value == "outside"){
            return $builder->where('employee',"no");
        }
        if($value == "deactive"){
            return $builder->where('status',"off");
        }
    }
}
