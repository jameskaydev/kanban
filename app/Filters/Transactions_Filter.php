<?php
/**
 * Created by PhpStorm.
 * User: mohammadreza
 * Date: 3/25/2019
 * Time: 2:12 PM
 */

namespace App\Filters;

use App\Filters\AbstractFilter;
use App\Filters\Transactions\User;
use App\Filters\Transactions\Date;


use Illuminate\Database\Eloquent\Builder;

class Transactions_Filter extends AbstractFilter
{
    protected $filters = [
        'user'                          => User::class,
        'date'                          => Date::class,
    ];
}
