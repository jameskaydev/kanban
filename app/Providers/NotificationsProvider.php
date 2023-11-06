<?php

namespace App\Providers;

use App\Models\notification_receiver;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

class NotificationsProvider extends ServiceProvider
{
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
            if(Auth::check()){
                $GL_Notifications = notification_receiver::join('notification','notification.id','=','notification_receiver.notif_id')
                    ->where('user_id',Auth::user()->id)
                    ->where('send_notif',true)
                    ->select(['notification.*','is_read'])
                    ->orderBy('id','desc')
                    ->limit(10)
                    ->get();

                $GL_Notifications_count = notification_receiver::join('notification','notification.id','=','notification_receiver.notif_id')
                ->where('user_id',Auth::user()->id)
                ->where('send_notif',true)
                ->where('is_read',false)
                ->count();
                if($GL_Notifications == ''){
                    $GL_Notifications = [];
                }
                $view->with(['GL_Notifications' => $GL_Notifications,'GL_Notifications_count' => $GL_Notifications_count]);
            }
        });
    }

}
