<?php

//include_once '../../../tribunal/dtotojson/DtoToJson.Class.php';
//include_once '../../../tribunal/host/Host.Class.php';

include_once(dirname(__FILE__) . "/../../../tribunal/dtotojson/DtoToJson.Class.php");
include_once(dirname(__FILE__) . "/../../../tribunal/host/Host.Class.php");

//include_once(dirname(__FILE__) . "/../../../tribunal/host/Host.Class.php");
//include_once(dirname(__FILE__) . "/../../../tribunal/json/JsonDecod.Class.php");

if (!defined("ruta")) {
    define("ruta", dirname(__FILE__));
}

class Notificaciones {

    private $host = null;

    public function __construct() {
        $this->host = new Host(ruta . "/../../../tribunal/host/config.xml", "NOTIFICACION");
        $this->host = $this->host->getConnect();
    }

//
    public function getNotificaciones($list, $param) {
        
        ini_set("default_socket_timeout", 200);
        ini_set("soap.wsdl_cache_enabled", "0");
        
        $jsonRequest = $list;
        $notificaciones = new SoapClient($this->host . "notificaciones/Notificaciones.Class.php?wsdl");
        $notificaciones = $notificaciones->select($jsonRequest, $param);
        return $notificaciones;
    }
    
    public function getTotalesNotificaciones($list, $param) {
        
        ini_set("default_socket_timeout", 200);
        ini_set("soap.wsdl_cache_enabled", "0");
        
        $jsonRequest = $list;
        $notificaciones = new SoapClient($this->host . "notificaciones/Notificaciones.Class.php?wsdl");
        $notificaciones = $notificaciones->selectTotales($jsonRequest, $param);
        return $notificaciones;
    }
    
    public function insertNotificaciones($list) {
        ini_set("default_socket_timeout", 200);
        ini_set("soap.wsdl_cache_enabled", "0");

        $notificaciones = new SoapClient($this->host . "notificaciones/Notificaciones.Class.php?wsdl");
        $jsonRequest = $list;
        $notificaciones = $notificaciones->insert($jsonRequest);
        return $notificaciones;
    }
    
    public function updateNotificaciones($list) {
        ini_set("default_socket_timeout", 200);
        ini_set("soap.wsdl_cache_enabled", "0");

        $notificaciones = new SoapClient($this->host . "notificaciones/Notificaciones.Class.php?wsdl");
        $jsonRequest = $list;
        $notificaciones = $notificaciones->update($jsonRequest);
        return $notificaciones;
    }

}

//$prueba = new Notificaciones();
//$json = '{
//  "idNotificacion": "",
//  "idJuzgadoGestion": "",
//  "idAcuerdo": "",
//  "FechaNotificacion": "",
//  "Juez": "",
//  "Destinatario": "",
//  "Promovente": "",
//  "idNotificador": "",
//  "Notificador": "",
//  "idJuicio": "",
//  "Juicio": "",
//  "TipoDoc": "",
//  "Acuerdo": ""
//  }';
//
//$array = 'Array
//(
//    [aniActuacion] => 
//    [pag] => 1
//    [cantxPag] => 10
//    [paginacion] => true
//    [CveAdscripcion] => 
//    [fechaDesde] => 
//    [fechaHasta] => 
//)';
//$response = $prueba->getNotificaciones($json, $array);
//
//var_dump($response);

?>
