<?php

namespace App\Http\Controllers\main;

use App\Http\Controllers\Controller;
use App\Models\notification_receiver;
use App\Models\Notifications;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\ConnectionException;

class sendNotificationController extends Controller
{
    public function send($user_id,$params){
        $user = User::where('id',$user_id)->first();

        $notif = Notifications::create([
            'message' => $params['message'],
            'fa_message' => $params['fa_message'],
            'fa_title' => $params['fa_title'],
            'title' => $params['title'],
            'icon' => $params['icon'],
            'link' => $params['link'],
            'send_sms' => $params['send_sms'],
            'send_notif' => $params['send_notif'],
            'send_push_notification' => $params['send_notif'],
            'sended_by' => $params['sended_by'],
            'receivers' => $params['receivers']
        ]);
        notification_receiver::create([
            'notif_id' => $notif['id'],
            'user_id' => $user['id']
        ]);

        $providers = $params['providers'];
        if(in_array("sms",$providers)){
            $this->sms([$user->phone],$params['fa_message']);
        }elseif(in_array("push_notification",$providers)){
            $this->push_notification($user->device_token,$params['title'],$params['message']);
        }
    }
    private $failed_send_sms = 0;
    private $failed_send_call = 0;
    public function sms($number,$message)
    {
        try{
            $api_key = "425635424D72325058667A564863743455514972742F344D2F4163753672756133642B6F534A4662574A553D";
            $http = Http::get('https://api.kavenegar.com/v1/'. $api_key .'/sms/send.json',[
                'receptor' => implode(",",$number),
                'message' => $message,
                'sender' => "1000100080080"
            ]);
    
            return $http->json();
        } catch (ConnectionException $n){
            $this->failed_send_sms++;
            if($this->failed_send_sms < 4){
                $this->sms($number,$message);
            }
            return [];
        }
        
    }

    public function call($number,$message)
    {
        try{
            $api_key = "425635424D72325058667A564863743455514972742F344D2F4163753672756133642B6F534A4662574A553D";
            $res = Http::get('https://api.kavenegar.com/v1/'. $api_key .'/call/maketts.json',[
                'receptor' => implode(",",$number),
                'message' => $message,
            ]);

            $body = $res->body();
            $body = json_decode($body,1);
            return $body;
        } catch (ConnectionException $n){
            $this->failed_send_call++;
            if($this->failed_send_call < 4){
                $this->call($number,$message);
            }
            return [];
        }
    }

    public function check_status($type,$messageid)
    {
        $api_key = "425635424D72325058667A564863743455514972742F344D2F4163753672756133642B6F534A4662574A553D";
        $res = Http::get('https://api.kavenegar.com/v1/'. $api_key .'/'. $type .'/status.json',[
            'messageid' => $messageid
        ]);

        $body = $res->body();
        $body = json_decode($body,1);
        return $body;
    }
    public function push_notification($resc,$title,$message){
        try{
            $url = 'https://fcm.googleapis.com/fcm/send';

            $serverKey = "AAAAIG3sbYk:APA91bErkw4a2XsRkhdlgrEzNtoIgEhtHifNQI-iTY2YugtCXUBYcaSuvUZFMp1PAXbUbvuE1PakkUZLsz5NBX8yuY7k8IQkUd2z9Vyrv-zFXuCKtkBfiimYayuV5a4VrtLkHhMu_9yE";
            $data = [
                "registration_ids" => $resc,
                "notification" => [
                    "title" => $title,
                    "body" => $message,
                ]
            ];
            $encodedData = json_encode($data);

            $headers = [
                'Authorization:key=' . $serverKey,
                'Content-Type: application/json',
            ];

            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
            // Disabling SSL Certificate support temporarly
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $encodedData);
            // Execute post
            $result = curl_exec($ch);
            if ($result === FALSE) {
                die('Curl failed: ' . curl_error($ch));
            }
            // Close connection
            curl_close($ch);
        } catch (ConnectionException $n){
            $this->failed_send_call++;
            if($this->failed_send_call < 4){
                $this->call($number,$message);
            }
            return [];
        }
    }
}
