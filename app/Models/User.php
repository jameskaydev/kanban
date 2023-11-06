<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Filters\users_Filter;
use Illuminate\Database\Eloquent\Builder;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;
    public function scopeFilter(Builder $builder,$request){
        return (new users_Filter($request))->filter($builder);
    }
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'fullname',
        'email',
        'password',
        'role',
        'status',
        'leave_minute',
        'device_token',
        'phone',
        'last_leave_update',
        'employee',
        'wallet',
        'salary',
        'reset_password_code',
        'reset_password_code_time',
        'avatar',
        'cover'
    ];
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected $appends = [
        'wallet_address',
        'avatar_image',
        'tag',
    ];
    public function getWalletAddressAttribute()
    {
        $user_attr = user_attr::where('attr_name','wallet')
        ->where('user_id',$this->id)
        ->where('metadata','active')
        ->first();

        if($user_attr != ''){
            return $user_attr['attr_content'];
        }
    }
    public function getAvatarImageAttribute()
    {
        if($this->avatar != '' && file_exists(public_path().'/uploads/'.$this->avatar)){
            return [
                'type' => 'image',
                'image' => url('uploads/' . $this->avatar)
            ]; 
        }else{
            return [
                'type' => 'text',
                'text' => substr($this->fullname,0,2)
            ]; 
        }
    }
    public function getTagAttribute()
    {
        $user_attr = user_attr::where('attr_name','tag')
        ->where('user_id',$this->id)
        ->first();

        if($user_attr != ''){
            return $user_attr['attr_content'];
        }
    }
}
