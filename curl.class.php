<?php

class basic_curl {
  public function curl($url,$data="",$action="GET"){
    $ch = curl_init($url);
    
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    
    $action = strtoupper($action);
    if($action == "POST" || $action == "P"){
      curl_setopt($ch, CURLOPT_URL, $url);
      curl_setopt($ch, CURLOPT_POST, true);
      curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    } else {
      curl_setopt($ch, CURLOPT_URL, $url . "?" . $data);
    }
    
    $result = curl_exec($ch);
    curl_close($ch);
    return $result;
  }
}