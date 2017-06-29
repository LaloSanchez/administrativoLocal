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

class TipoPersonaAsunto {

    private $host = null;

    public function __construct() {
        $this->host = new Host(ruta . "/../../../tribunal/host/config.xml", "NOTIFICACION");
        $this->host = $this->host->getConnect();
    }

//
    public function getTipoPersonaAsundo($list) {
        ini_set("default_socket_timeout", 200);
        ini_set("soap.wsdl_cache_enabled", "0");

        $TipoPersonaAsundo = new SoapClient($this->host . "tipopersonaasunto/TipoPersonaAsunto.Class.php?wsdl");
        $jsonRequest = $list;
        $TipoPersonaAsundo = $TipoPersonaAsundo->select($jsonRequest);
        return $TipoPersonaAsundo;
    }

}

//$prueba = new TipoPersonaAsunto();
//$json = '{
//  "idtblTipoPersonaAsunto": "1",
//  "Descripcion": "",
//  "Activo": ""
//  }';
//$response = $prueba->getTipoPersonaAsundo($json);
//
//var_dump($response);

?>
