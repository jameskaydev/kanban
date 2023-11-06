<?php
/**
 * Created by PhpStorm.
 * User: mohammadreza
 * Date: 3/25/2019
 * Time: 2:12 PM
 */

namespace App\Filters;

use App\Filters\AbstractFilter;
use App\Filters\Calllogs\Accounts;
use App\Filters\Calllogs\Category;
use App\Filters\Calllogs\Date;
use App\Filters\Calllogs\Status;
use App\Filters\Calllogs\User;
use App\Filters\Calllogs\Username;

use Illuminate\Database\Eloquent\Builder;

class Call_Filter extends AbstractFilter
{
    protected $filters = [
        'accounts'                      => Accounts::class,
        'category'                      => Category::class,
        'date'                          => Date::class,
        'status'                        => Status::class,
        'user'                          => User::class,
        'username'                      => Username::class,
    ];
}
