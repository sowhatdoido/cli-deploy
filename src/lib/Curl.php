<?php

class Curl {
    public static function post($url, $data = []){
        $ch = curl_init();        
        
        //set the url, number of POST vars, POST data
        curl_setopt($ch,CURLOPT_URL, $url);
        if(!empty($data) && is_array($data)){            
            curl_setopt($ch,CURLOPT_POST, count($data));
            curl_setopt($ch,CURLOPT_POSTFIELDS, http_build_query($data));
        }
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 

        //execute post
        $result = curl_exec($ch);
        
        curl_close($ch);
        
        return $result;
    }
}