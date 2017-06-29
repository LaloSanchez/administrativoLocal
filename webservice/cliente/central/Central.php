<?php

include_once(dirname(__FILE__) . "/../../../tribunal/host/Host.Class.php");
include_once(dirname(__FILE__) . "/../../../tribunal/json/JsonDecod.Class.php");

class Central {

    private $host = null;

    public function __construct() {
        $this->host = new Host("../../../tribunal/host/config.xml", "CENTRALES");
        $this->host = $this->host->getConnect();
    }

    public function serviceCentrales($jsonRequest) {
        ini_set("default_socket_timeout", 600);
        ini_set("soap.wsdl_cache_enabled", "0");
        $soapClient = new SoapClient("http://centrales.pjedomex.gob.mx/centrales/webservice/servidor/actuaciones/ActuacionesServer.php?wsdl");
        $jsonResponse = $soapClient->ServiceActuaciones($jsonRequest, '3a332bac303f6e9536b36731090f66800abee04c', 'cf3387f0417a09352af09b2926e3e38522bef9f5');
        return $jsonResponse;
    }

}

?>
