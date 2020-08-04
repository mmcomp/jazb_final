<?php 
namespace App\Classes;
class wpApi{
    public function getWpData($command,$params){
        //$url = "https://192.168.2.81:8443/api/";
        $url = "https://aref-group.ir:2096/api/";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$url . $command);
        $data_string = json_encode($params, JSON_UNESCAPED_UNICODE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($ch, CURLOPT_HTTPHEADER,[
            'Content-Type: application/json',
            'Content-Length: ' . strlen($data_string)
        ]);
    
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }
}