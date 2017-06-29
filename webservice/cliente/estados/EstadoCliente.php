<?php

include_once(dirname(__FILE__) . "/../../../tribunal/host/Host.Class.php");
include_once(dirname(__FILE__) . "/../../../tribunal/json/JsonDecod.Class.php");

@define("ruta", dirname(__FILE__));
class EstadoCliente {

    private $host = null;

    public function __construct() {
        $this->host = new Host(ruta . "/../../../tribunal/host/config.xml", "GESTION");
        $this->host = $this->host->getConnect();
    }

    public function getEstados($cveEstado, $desEstado, $activo) {
        ini_set("default_socket_timeout", 200);
        ini_set("soap.wsdl_cache_enabled", "0");

        $estado = new SoapClient($this->host . "controller/estados/EstadosServer.php?wsdl");
        $estado = $estado->selectEstado($cveEstado, $desEstado, $activo, '3a332bac303f6e9536b36731090f66800abee04c', 'cf3387f0417a09352af09b2926e3e38522bef9f5');
        return $estado;
    }

    public function setEstado($cveEstado, $desEstado, $activo) {
        ini_set("default_socket_timeout", 200);
        ini_set("soap.wsdl_cache_enabled", "0");

        $estado = new SoapClient($this->host . "controller/estados/EstadosServer.php?wsdl");
        $estado = $estado->guardarEstado($cveEstado, $desEstado, $activo, '3a332bac303f6e9536b36731090f66800abee04c', 'cf3387f0417a09352af09b2926e3e38522bef9f5');
        return $estado;
    }

}

?>
