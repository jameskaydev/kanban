<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;


use App\Events\Notifications\SendSMS;
use App\Events\Notifications\SendMail;
use App\Events\Notifications\SendNotification;

class sendNotif
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    private $param = [];
    public function __construct($param)
    {
        $this->param = $param;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        if(in_array("sms",$this->param)){
            event(new SendSMS($this->param));
        }

        if(in_array("notif",$this->param)){
            event(new SendNotification($this->param));
        }

        if(in_array("mail",$this->param)){
            event(new SendMail($this->param));
        }
    }
}
