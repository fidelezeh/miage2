<?php

    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Headers: access");
    header("Access-Control-Allow-Methods: POST");
    header("Access-Control-Allow-Credentials: true");
    header('Content-Type: application/json');

    //$data_received = file_get_contents("php://input");
    //file_put_contents('test.txt', file_get_contents('php://input'));
    //echo json_encode(array('data'=>$data_received));

    $responseBody = file_get_contents('php://input');
    $json = json_decode($responseBody);  
    
    //Save in json file
    if($json){
        $fp = fopen('results_'.time().'.json', 'w');
        fwrite($fp, json_encode($json));
        fclose($fp);
    }

?>