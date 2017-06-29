<?php

//include_once '../../../tribunal/dtotojson/DtoToJson.Class.php';
//include_once '../../../tribunal/host/Host.Class.php';
//
include_once(dirname(__FILE__) . "/../../../tribunal/dtotojson/DtoToJson.Class.php");
include_once(dirname(__FILE__) . "/../../../tribunal/host/Host.Class.php");

//include_once(dirname(__FILE__) . "/../../../tribunal/host/Host.Class.php");
//include_once(dirname(__FILE__) . "/../../../tribunal/json/JsonDecod.Class.php");

if (!defined("ruta")) {
    define("ruta", dirname(__FILE__));
}

class PersonasNotificar {

    private $host = null;

    public function __construct() {
        $this->host = new Host(ruta . "/../../../tribunal/host/config.xml", "NOTIFICACION");
        $this->host = $this->host->getConnect();
    }

//
    public function getPersonasNotificar($list) {
        ini_set("default_socket_timeout", 200);
        ini_set("soap.wsdl_cache_enabled", "0");

        $personasNotificar = new SoapClient($this->host . "personasnotificar/PersonasNotificar.Class.php?wsdl");
        $jsonRequest = $list;
        $personasNotificar = $personasNotificar->select($jsonRequest);
        return $personasNotificar;
    }
    public function insertPersonasNotificar($list) {
        
//        echo $list;
        ini_set("default_socket_timeout", 200);
        ini_set("soap.wsdl_cache_enabled", "0");

        $personasNotificar = new SoapClient($this->host . "personasnotificar/PersonasNotificar.Class.php?wsdl");
        $jsonRequest = $list;
        
        $personasNotificar = $personasNotificar->insert($jsonRequest);
        return $personasNotificar;
    }

}

//$prueba = new PersonasNotificar();
//$json = '{
//  "idtblPersonasNotificar": "",
//  "fk_idtblAcuerdos": "1",
//  "idNotificacion": "1",
//  "fk_idtblRelacionExpedienteJuzgado": "1",
//  "Activo": "1",
//  "FechaAlta": "now()",
//  "FechaModificacion": "1",
//  "CadOriginal": "1",
//  "SelloDigital": "1",
//  "Instructivo": "1"
//  }';
//$response = $prueba->insertPersonasNotificar($json);
//
//var_dump($response);

?>
