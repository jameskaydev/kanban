<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notifications extends Model
{
    use HasFactory;
    protected $table = 'notification';
    protected $fillable = [
        'message',
        'fa_message',
        'title',
        'fa_title',
        'icon',
        'link',
        'send_sms',
        'send_notif',
        'send_push_notification',
        'sended_by',
        'receivers'
    ];
}
