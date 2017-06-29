<?php

include_once(dirname(__FILE__) . "/../../../tribunal/host/Host.Class.php");
include_once(dirname(__FILE__) . "/../../../tribunal/json/JsonDecod.Class.php");
class GenericCliente {

    private $hostGestionIndicadores = "http://gestion.pjedomex.gob.mx/gestion2/gestion3/webservice/servidor/indicadores/ReportesServerScramble.wsdl";
    public function __construct() {
    }

    public function selectDAO( $json ){
        ini_set("default_socket_timeout", 200);
        ini_set("soap.wsdl_cache_enabled", "0");
        $gestionCliente = new SoapClient( $this->hostGestionIndicadores );
        return $gestionCliente->selectDAO( $json );
    }
}

?>
