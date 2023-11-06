<?php
namespace App\Http\Controllers\main;


use NotificationChannels\Fcm\FcmChannel;
use NotificationChannels\Fcm\FcmMessage;
use Illuminate\Notifications\Notification;

class PushNotification extends Notification
{
    public function via($notifiable)
    {
        return [FcmChannel::class];
    }

    public function toFcm($notifiable)
    {
        return FcmMessage::create()
            ->setData([
                'key' => 'AIzaSyBM_u3KcQhs-AMKUsxPKRd-haTQvJGhWeM'
            ])
            ->setNotification([
                'title' => 'Notification Title',
                'body' => 'Notification Body',
            ]);
    }
}
