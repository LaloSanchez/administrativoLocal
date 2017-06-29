<?php

include_once(dirname(__FILE__) . "/../../../tribunal/dtotojson/DtoToJson.Class.php");
include_once(dirname(__FILE__) . "/../../../tribunal/host/Host.Class.php");

//include_once(dirname(__FILE__) . "/../../../tribunal/host/Host.Class.php");
//include_once(dirname(__FILE__) . "/../../../tribunal/json/JsonDecod.Class.php");

if (!defined("ruta")) {
    define("ruta", dirname(__FILE__));
}

class PersonaAutorizadas {

    private $host = null;

    public function __construct() {
        $this->host = new Host(ruta . "/../../../tribunal/host/config.xml", "NOTIFICACION");
        $this->host = $this->host->getConnect();
    }

//
    public function getPersonaAutorizadas($list) {
        ini_set("default_socket_timeout", 200);
        ini_set("soap.wsdl_cache_enabled", "0");

        $personaAutorizadas = new SoapClient($this->host . "personaautorizadas/PersonaAutorizadas.Class.php?wsdl");
        $jsonRequest = $list;
        $personaAutorizadas = $personaAutorizadas->select($jsonRequest);
        return $personaAutorizadas;
    }

    public function getPersonaAutorizadasIn($list) {
        ini_set("default_socket_timeout", 200);
        ini_set("soap.wsdl_cache_enabled", "0");

        $personaAutorizadas = new SoapClient($this->host . "personaautorizadas/PersonaAutorizadas.Class.php?wsdl");
        $jsonRequest = $list;
        $personaAutorizadas = $personaAutorizadas->selectIn($jsonRequest);
        return $personaAutorizadas;
    }

}

?>
