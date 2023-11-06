<?php

namespace App\Providers;

use App\Models\Settings;
use App\Models\User;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use App\Libraries\Jalali;
use App\Traits\AvatarColors;
use Carbon\Carbon;
use Morilog\Jalali\Jalalian;

class GlobalVariable extends ServiceProvider
{
    use AvatarColors;
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        View::composer('*', function($view)
        {
            $month_convertor = [
                -3 => __('admin.month_azar'),
                -2 => __('admin.month_dey'),
                -1 => __('admin.month_bahman'),
                0 => __('admin.month_esfand'),
                1 => __('admin.month_farvardin'),
                2 => __('admin.month_ordibehesht'),
                3 => __('admin.month_khordad'),
                4 => __('admin.month_tir'),
                5 => __('admin.month_mordad'),
                6 => __('admin.month_shahrivar'),
                7 => __('admin.month_mehr'),
                8 => __('admin.month_aban'),
                9 => __('admin.month_azar'),
                10 => __('admin.month_dey'),
                11 => __('admin.month_bahman'),
                12 => __('admin.month_esfand')
            ];

            
            $GL_PROFILE_COLOR = AvatarColors::getColor();
            $GL_Jalalian = new Jalalian(1402,1,7);
            $view->with(['month_convertor' => $month_convertor,'GL_Jalalian' => $GL_Jalalian,'GL_PROFILE_COLOR' => $GL_PROFILE_COLOR]);
        });
    }
}


