<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Admin\Bots\callQueueControler;
use App\Http\Controllers\main\sendNotificationController;
use App\Models\Casts;
use App\Models\kpi_values;
use App\Models\kpis;
use App\Models\oldQueue;
use App\Models\Percentages;
use App\Models\Platforms;
use App\Models\ProjectPercentages;
use App\Models\Projects;
use App\Models\User;
use App\Models\user_attr;
use App\Models\user_kpi;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;
use Spatie\Permission\Models\Permission;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Str;

class CustomAuthController extends Controller
{
    public function logout(Request $request)
    {
        Auth::logout();
        if ($request->session()->has('error')) {
            return redirect('login')->with('error', Session::get('error'));
        }
        return redirect('/');
    }

    public function forget_password(){
        return view('auth.forget_password');
    }

    public function send_forget_password_code(Request $request){
        $request->validate([
            'phone' => 'required'
        ]);

        $user = User::where('phone',$request->post('phone'))->first();

        if($user != ''){
            $code = Str::random(10);
            User::where('id',$user['id'])->update([
                'reset_password_code' => $code,
                'reset_password_code_time' => date("Y-m-d H:i:s")
            ]);

            $sendNotif = new sendNotificationController();
            $sendNotif->sms([$user['phone']],"your recovery link: 
" .  url("UP/" . $code) . "

KayTask");
        }

        return view('auth.forget_password_confirmation');
    }

    public function Update_Password(Request $request,$code){
        
        $user = User::where('reset_password_code',$code)->first();

        if($user == '' || $code == ''){
            abort(403);
        }
        $last_time_updated = strtotime($user['reset_password_code_time']);
        $now = time();

        if($last_time_updated + 60 * 5 < $now){
            return view('auth.forget_password_expire');
        }

        Auth::login($user);
        return view('auth.Update_Password',[
            'user' => $user
        ]);
    }

    public function Update_Password_save(Request $request){
        $request->validate([
            'password' => 'min:8|confirmed'
        ]);

        User::where('id',Auth::user()->id)->update([
            'password' => Hash::make($request->post('password'))
        ]);

        return redirect('Admin/Dashboard');
    }

    public function test(Request $request)
    {

        Permission::create([
            'name' => 'Kanban'
        ]);

        Permission::create([
            'name' => 'Create_Board'
        ]);

        // Permission::create([
        //     'name' => 'Call_Queue_List'
        // ]);
        // Permission::create([
        //     'name' => 'Call_Logs'
        // ]);
        // Permission::create([
        //     'name' => 'Accounting Add Debit'
        // ]);
        // Permission::create([
        //     'name' => 'Edit Categories'
        // ]);
        // Permission::create([
        //     'name' => 'Delete Categories'
        // ]);
        // Permission::create([
        //     'name' => 'Categories List'
        // ]);
        
        // Permission::create([
        //     'name' => 'Call_Queue'
        // ]);

        // $client = new Client();

        // $options = [
        //     'headers' => [
        //           'Authorization' => 'Basic OWMxMGZlZDRmYjhlNGZmOWI0MDI1NGNlNzllMmEwMTE6MDAzYjkzMmQ4ZGUxNDg4ZDk4MzUzMGMwMmZhNzlhMTU=',
        //           'Cookie' => 'AWSALB=S9meeCAOwkQE4y5GARCHZ9VeRVXHt0VKoEySLCkUtwIyLqDWaBMf05Osxd6srWoRXAzHGTR7Z28ic0sI6QWAXGuORIPozD2r4SbbotJ50Je/ARrEOXgf6zoKnApf; AWSALBCORS=S9meeCAOwkQE4y5GARCHZ9VeRVXHt0VKoEySLCkUtwIyLqDWaBMf05Osxd6srWoRXAzHGTR7Z28ic0sI6QWAXGuORIPozD2r4SbbotJ50Je/ARrEOXgf6zoKnApf'
        //     ],
        //     'multipart' => [
        //         [
        //           'name' => 'grant_type',
        //           'contents' => 'client_credentials'
        //         ],
        //         [
        //           'name' => 'scope',
        //           'contents' => 'basic'
        //         ],
        //     ],
        // ];

        // $response = $client->request('POST', 'https://oauth.fatsecret.com/connect/token', $options);

        // $body = $response->getBody()->getContents();
        // $j = json_decode($body,1);

        // $d= Http::withToken($j['access_token'])->get("https://platform.fatsecret.com/rest/server.api",[
        //     'method' => 'food.get.v3',
        //     'food_id' => '26000',
        //     'format' => 'json'
        // ]);
        // $body = $d->json();
        // return  $body;
    }

    public function ttt(Request $request)
    {
        Storage::put("ttt1.txt",$request);
        return "test";
    }
}
