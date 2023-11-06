<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Morilog\Jalali\Jalalian;

class kanbanEvents extends Model
{
    use HasFactory;
    protected $table = 'kanban_events';
    protected $fillable = [
        'title',
        'description',
        'board_id',
        'user_id',
        'card_id',
        'array_content'
    ];

    protected $appends = [
        'profile_image',
        'jalali_time',
        'array_content_json'
    ];
    public function getProfileImageAttribute()
    {
        $user = User::where('id',$this->user_id)->first();
        if($user->avatar != '' && file_exists(public_path().'/uploads/'.$user->avatar)){
            return [
                'type' => 'image',
                'image' => url('uploads/' . $user->avatar)
            ]; 
        }else{
            return [
                'type' => 'text',
                'text' => substr($user->fullname,0,2)
            ]; 
        }
    }
    public function getJalaliTimeAttribute()
    {
        return Jalalian::fromDateTime($this->created_at)->format('Y/m/d H:i');
    }
    public function getArrayContentJsonAttribute()
    {
        if($this->array_content == ''){
            return [];
        }else{
            return json_decode($this->array_content,1);
        }
    }
}
