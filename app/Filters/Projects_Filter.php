<?php
/**
 * Created by PhpStorm.
 * User: mohammadreza
 * Date: 3/25/2019
 * Time: 2:12 PM
 */

namespace App\Filters;

use App\Filters\AbstractFilter;
use App\Filters\Projects\Title;
use App\Filters\Projects\Status;
use App\Filters\Projects\Type;
use App\Filters\Projects\Date;
use App\Filters\Projects\Owner;
use App\Filters\Projects\Employers;
use App\Filters\Projects\ClearanceDate;
use App\Filters\Projects\EndDate;
use App\Filters\Projects\Link;
use App\Filters\Projects\StartDate;
use App\Filters\Projects\AssignConditions;
use App\Filters\Projects\Approval;
use App\Filters\Projects\PaymentStatus;

use Illuminate\Database\Eloquent\Builder;

class Projects_Filter extends AbstractFilter
{
    protected $filters = [
        'title'                         => Title::class,
        'price'                         => Price::class,
        'type'                          => Type::class,
        'owner'                         => Owner::class,
        'employer'                      => Employers::class,
        'ClearanceDate'                 => ClearanceDate::class,
        'EndDate'                       => EndDate::class,
        'StartDate'                     => StartDate::class,
        'status'                        => Status::class,
        'link'                          => Link::class,
        'assign_conditions'             => AssignConditions::class,
        'approval'                      => Approval::class,
        'payment_status'                => PaymentStatus::class
    ];
}
