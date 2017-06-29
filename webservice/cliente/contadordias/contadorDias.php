<?php
include_once(dirname(__FILE__) . "/../../../tribunal/host/Host.Class.php");
include_once(dirname(__FILE__) . "/../../../tribunal/json/JsonDecod.Class.php");

class contadorDias {

    private $host = null;

    public function __construct() {
//        $this->host = new Host("../../../tribunal/host/config.xml", "GESTION");
//        $this->host = $this->host->getConnect();
    }

    public function serviceDias($json) {
        ini_set("default_socket_timeout", 600);
        ini_set("soap.wsdl_cache_enabled", "0");
//        $calendario = new SoapClient($this->host . "controller/calendario/CalendarioServer.php?wsdl");
        $calendario = new SoapClient("http://gestion.pjedomex.gob.mx/gestion2/servidor/controller/calendario/CalendarioServer.php?wsdl");
        $calendarioRs = $calendario->selectCalendario($json, '3a332bac303f6e9536b36731090f66800abee04c', 'cf3387f0417a09352af09b2926e3e38522bef9f5');
        return $calendarioRs;
    }

}
