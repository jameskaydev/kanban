<?php
/**
 * Created by PhpStorm.
 * User: mohammadreza
 * Date: 3/25/2019
 * Time: 2:12 PM
 */

namespace App\Filters;

use App\Filters\AbstractFilter;
use App\Filters\Vacations\Title;
use App\Filters\Vacations\Date;
use Illuminate\Database\Eloquent\Builder;

class vacations_Filter extends AbstractFilter
{
    protected $filters = [
        'title'                         => Title::class,
        'date'                          => Date::class
    ];
}
