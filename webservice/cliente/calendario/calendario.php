<?php

include_once dirname(__FILE__) . '/../../../tribunal/host/Host.Class.php';
include_once dirname(__FILE__) . '/../../../tribunal/json/JsonDecod.Class.php';

if (!defined('ruta')) define("ruta", dirname(__FILE__));

class Calendario {

    private $host = null;

    public function __construct() {
        $this->host = new Host(ruta . "/../../../tribunal/host/config.xml", "GESTION");
        $this->host = $this->host->getConnect();
    }

    public function getCalendario($json) {
        ini_set("default_socket_timeout", 200);
        ini_set("soap.wsdl_cache_enabled", "0");
        
        $login = new SoapClient($this->host . "controller/calendario/CalendarioServer.php?wsdl",array("trace" => 1, "exception" => 0, 'cache_wsdl'    => WSDL_CACHE_NONE,'encoding'=>'UTF-8'));
        $login = $login->selectCalendario($json, '3a332bac303f6e9536b36731090f66800abee04c', 'cf3387f0417a09352af09b2926e3e38522bef9f5');
        return $login;
    }

}

?>
