<?php

$textError = "";
ob_start();
try {
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_PORT => "80",
        CURLOPT_URL => "http://10.22.165.43/codebase/webservice/servidor/generos",
        CURLOPT_RETURNTRANSFER => false,
        CURLOPT_ENCODING => "",
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_SSL_VERIFYHOST => false,
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
//        CURLOPT_FOLLOWLOCATION => true,
//        CURLOPT_HTTPGET => true,
//        CURLOPT_POST => 1,"cveGenero=3&activo=N",
        CURLOPT_POSTFIELDS => json_encode(array("cveGenero"=>3,"activo"=>"S")),//
//        CURLOPT_POST => true,//
//        CURLOPT_POSTFIELDS => "cveGenero=3&activo=N",//
        CURLOPT_HTTPHEADER => array(
            "Authorization: Basic cmVjZXB0b3JTb2xpY2l0dWQxOnNlY3JldA==",
            "cache-control: no-cache",
            "content-type: application/x-www-form-urlencoded"
//            "content-type: application/json"
        ),
    ));

    $response = curl_exec($curl);
//    print_r($response);
    $err = curl_error($curl);
    curl_close($curl);
    json_decode($response);
    if (json_last_error() == JSON_ERROR_SYNTAX) {
        throw new Exception("{error:Los parametros no son correctos}");
    } else {
        $textError = $response;
    }
} catch (Exception $e) {
    $response = "{\"error\":\"" . $e->getMessage() . "\"}";
    $textError = $response;
} 

    