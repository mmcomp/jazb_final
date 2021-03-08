<?php
namespace App\Utils;

class Sms {
    public static function send($receptor, $message){
        $params = [
            'receptor'=>$receptor,
            'message'=>$message
        ];
        $ch = \curl_init();
        $url = env("SMS_URl") . env("SMS_API_KEY") . "/sms/send.json";
        \curl_setopt($ch, CURLOPT_URL,$url);
        \curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        \curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        \curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
        $result = \curl_exec($ch);
        \curl_close($ch);
        return json_decode($result);
    }
}