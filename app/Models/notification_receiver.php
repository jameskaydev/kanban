<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class notification_receiver extends Model
{
    use HasFactory;
    protected $table = 'notification_receiver';
    protected $fillable = [
        'notif_id',
        'user_id',
        'is_read'
    ];
}
