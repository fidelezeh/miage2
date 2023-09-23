<?php
/**
 * function for sendning response to uesr
 */
if (!function_exists("include_post")) {
    function include_post()
    {
        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json; charset=UTF-8");
        header("Access-Control-Allow-Methods: POST");
        header("Access-Control-Max-Age: 3600");
        header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
  }
}

if (!function_exists("include_get")) {
    function include_get()
    {
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Headers: access");
        header("Access-Control-Allow-Methods: GET");
        header("Access-Control-Allow-Credentials: true");
        header('Content-Type: application/json');
        header("Content-Type: text/html; charset=utf-8");
    }
}


if (!function_exists("sendResponse")) {
    function sendResponse($resp_code,$data,$message)
    {
        echo json_encode(array('code'=>$resp_code,'message'=>$message,'data'=>$data));
    }
 }