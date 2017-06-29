<?php

include_once(dirname(__FILE__) . "/../../../tribunal/host/Host.Class.php");
include_once(dirname(__FILE__) . "/../../../tribunal/json/JsonDecod.Class.php");

class ActualizarRefPeritoCliente {

    private $host = null;

    public function __construct() {
        //$this->host = new Host("../../../tribunal/host/config.xml", "PERITOS");
        //$this->host = $this->host->getConnect();
    }

    public function ActualizarRefPeritoCliente($jsonRequest) {
        try {
            ini_set("default_socket_timeout", 600);
            ini_set("soap.wsdl_cache_enabled", "0");
            $soapClient = new SoapClient("http://peritos.pjedomex.gob.mx/peritos/webservice/servidor/solicitudesperitos/SolicitudesPeritosServer.php?wsdl");
            $jsonResponse = $soapClient->ServiceSolicitudPeritoActualizar($jsonRequest, '1', '1');            
            return $jsonResponse;
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }

}

@$action = $_POST["action"];
switch ($action) {
    case "insert":
        //$SolicitudPeritoCliente = new SolicitudPeritoCliente();         
        //$rs = $SolicitudPeritoCliente->ServiceSolicitudesPeritos($json);
        break;
}
?>
