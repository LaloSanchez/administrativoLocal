<?php

include_once(dirname(__FILE__) . "/../../../tribunal/host/Host.Class.php");
include_once(dirname(__FILE__) . "/../../../tribunal/json/JsonDecod.Class.php");

class MateriasPericialesCliente {

    private $host = null;

    public function __construct() {
        //$this->host = new Host("../../../tribunal/host/config.xml", "PERITOS");
        //$this->host = $this->host->getConnect();
    }

    public function ServiceMateriasPericiales($jsonRequest) {        
        ini_set("default_socket_timeout", 600);
        ini_set("soap.wsdl_cache_enabled", "0");
        $soapClient = new SoapClient("http://peritos.pjedomex.gob.mx/peritos/webservice/servidor/materiaspericiales/MateriasPericialesServer.php?wsdl");
        $jsonResponse = $soapClient->ServiceMateriasPericiales($jsonRequest, '1', '1');
        return $jsonResponse;
    }

}
?>
