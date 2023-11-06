<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\notification_receiver;
use App\Models\Notifications;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UsersNotifController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display the specified resource.
     *
     * @param
     * @return view auth.Admin.Roles.List
     *
     * GET Admin/KPIs
     */
    public function index()
    {
        $Content = notification_receiver::join('notification','notification.id','=','notification_receiver.notif_id')
        ->where('user_id',Auth::user()->id)
        ->where('send_notif',true)
        ->select(['notification.*','is_read','sended_by'])
        ->orderBy('id','desc')
        ->paginate();
        return view('auth.Admin.UsersNotif.List',[
            'Content' => $Content,
            "pageName" => __('admin.Notifications'),
        ]);
    }
    public function fcm_token(Request $request)
    {
        try{
            $request->user()->update(['device_token'=>$request->token]);
            return response()->json([
                'success'=>true
            ]);
        }catch(\Exception $e){
            report($e);
            return response()->json([
                'success'=>false
            ],500);
        }
    }
}
