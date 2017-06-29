<?php

include_once(dirname(__FILE__) . "/../../../tribunal/host/Host.Class.php");
include_once(dirname(__FILE__) . "/../../../tribunal/json/JsonDecod.Class.php");

if (!defined('ruta'))
    define("ruta", dirname(__FILE__));

class EdificiosCliente {

    private $host = null;

    public function __construct() {
        $this->host = new Host(ruta . "/../../../tribunal/host/config.xml", "GESTION");
        $this->host = $this->host->getConnect();
    }

    public function getEdificios($cveEdificio = '', $cveDistrito = '', $cveRegion = '', $u = '', $p = '') {
        try {
            ini_set("default_socket_timeout", 200);
            ini_set("soap.wsdl_cache_enabled", "0");
            $edificios = new SoapClient($this->host . "controller/edificios/EdificiosServer.php?wsdl");
            $edificios = $edificios->selectEdificios($cveEdificio, "", "", "", "", $cveDistrito, $cveRegion, "", "", "", '3a332bac303f6e9536b36731090f66800abee04c', 'cf3387f0417a09352af09b2926e3e38522bef9f5');

            if (is_soap_fault($edificios)) {
                trigger_error("SOAP Fault: (faultcode: {$edificios->faultcode}, faultstring: {$edificios->faultstring})", E_USER_ERROR);
            }
        } catch (SoapFault $exception) {
            
        }
        return $edificios;
    }

}

$edificiosCliente = new EdificiosCliente();
@$accion = $_POST["action"];
if ($accion == "getEdificios") {
    $rs = $edificiosCliente->getEdificios();
    echo $rs;
}