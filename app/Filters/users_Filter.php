<?php
/**
 * Created by PhpStorm.
 * User: mohammadreza
 * Date: 3/25/2019
 * Time: 2:12 PM
 */

namespace App\Filters;

use App\Filters\AbstractFilter;
use App\Filters\Users\Fullname;
use App\Filters\Users\Email;
use App\Filters\Users\Access_id;
use App\Filters\Users\Status;
use App\Filters\Users\Type;
use App\Filters\Users\Date;
use Illuminate\Database\Eloquent\Builder;

class users_Filter extends AbstractFilter
{
    protected $filters = [
        'fullname'                      => Fullname::class,
        'email'                         => Email::class,
        'access_id'                     => Access_id::class,
        'status'                        => Status::class,
        'type'                          => Type::class,
        'date'                          => Date::class
    ];
}
