<?php
/**
 * Created by PhpStorm.
 * User: mohammadreza
 * Date: 3/25/2019
 * Time: 2:12 PM
 */

namespace App\Filters;

use App\Filters\AbstractFilter;
use App\Filters\callStatidtics\Date;
use Illuminate\Database\Eloquent\Builder;

class callStatidtics_Filter extends AbstractFilter
{
    protected $filters = [
        'date'                          => Date::class
    ];
}
