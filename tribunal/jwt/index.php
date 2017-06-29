<?php

include_once(dirname(__FILE__) . '/JWT.php');

use tribunal\JWT\JWT;


$mydate = date("Y-m-d H:i:s");
$mydate = strtotime($mydate);
//$mydate = strtotime('06.04.2010');

$key = base64_encode("dgaonam:dgaonam");
//$token = array(
//    "name" => "Poder Judicial del Estado de Mexico",
//    "siglas" => "PJEM",
//    "iat" => $mydate,
//    "nbf" => $mydate);
$token = array(
        "cveEstado" => "1",
        "activo" => "",
        "desEstado" => "",
        "cvePais" => "",
        
        "iat" => $mydate,
        "nbf" => $mydate);

$jwt = JWT::encode($token, $key, 'HS256');


echo "Token: ". $jwt;
echo "<br>";
echo "<br>";
//var_dump($jwt);


//$jwt = JWT::encode($token, $key);
$jwt = "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJjdmVFc3RhZG8iOiIxIiwiYWN0aXZvIjoiIiwiZGVzRXN0YWRvIjoiIiwiY3ZlUGFpcyI6IiIsImlhdCI6MTQ3Mzg3OTk4MCwibmJmIjoxNDczODc5OTgwfQ.kj-7hvNOyt6gr7FLy7ltBzPqxeLLmC7BxlvQoKDdpTc";
$tks = explode('.', $jwt);
var_dump($tks);
$decoded = JWT::decode($jwt, $key, array('HS256'),  $token);
echo json_encode($decoded);
?>