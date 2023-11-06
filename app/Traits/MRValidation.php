<?php
namespace App\Traits;

use Illuminate\Http\Request;

trait MRValidation{
    public static function AuthValidateMessage(){
        return [
            'email.required' => 'email is required',
            'password.required' => 'password is required',
            'name.required' => 'name is required',
            'name.string' => 'name is wrong!',
            'name.max' => 'name is too long',
            'email.string' => 'email is wrong',
            'email.email' => 'email is wrong',
            'email.max' => 'email is too long',
            'email.unique' => 'email alreday exist',
            'password.string' => 'password is too weak',
            'password.min' => 'password is too short',
            'password.confirmed' => 'th password confirmation is wrong'
        ];
    }
    public static function GENERAL(){
        return [
            'name.required' => __('messages.name_required'),
            'fullname.required' => __('messages.fullname_required'),
            'email.required' => __('messages.email_required'),
            'email.unique' => __('messages.email_unique'),
            'email.email' => __('messages.email_email'),
            'password.required' => __('messages.password_required'),
            'password.min' => __('messages.password_min'),
            'role.required' => __('messages.role_required'),
            'title.required' => __('messages.title_required'),
            'type.required' => __('messages.type_required'),
            'date.required' => __('messages.date_required'),
            'color.required' => __('messages.color_is_required'),
            'platform.required' => __('messages.platform_is_required'),
            'type.required' => __('messages.type_is_required'),
            'price.required' => __('messages.price_is_required'),
            'currency.required' => __('messages.currency_is_required'),
            'employer.required' => __('messages.employer_is_required'),
            'deadline.required' => __('messages.deadline_is_required'),
            'fee.required' => __('messages.fee_is_required'),
            'tax.required' => __('messages.fee_is_required'),
            'icon.required' => __('messages.icon_is_required'),
            'image.required' => __('messages.image_is_required'),
            'username.required' =>  __('messages.username_is_required'),
            'abbr.required' =>  __('messages.abbreviation_is_required'),
            'rate.required' => __('messages.rate_is_required'),
            'start_time.required' => __('messages.start_time_is_required'),
            'milestone.required' => __('messages.milestone_is_required'),
            'billing.required' => __('messages.billing_is_required'),
            'time.required' => __('messages.time_is_required'),
            'message.required' => __('messages.message_is_required'),
            'receivers.required' => __('messages.receivers_is_required'),
            'color_type.required' => __('messages.color_type_is_required'),
        ];
    }
}
