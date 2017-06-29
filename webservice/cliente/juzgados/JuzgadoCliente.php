<?php

include_once(dirname(__FILE__) . "/../../../tribunal/host/Host.Class.php");
include_once(dirname(__FILE__) . "/../../../tribunal/json/JsonDecod.Class.php");

if (!defined("ruta")) {
    define("ruta", dirname(__FILE__));
}

class JuzgadoCliente {

    private $host = null;

    public function __construct() {
        $this->host = new Host(ruta . "/../../../tribunal/host/config.xml", "GESTION");
        $this->host = $this->host->getConnect();
    }

//
    public function getJuzgadoList($list) {
        ini_set("default_socket_timeout", 200);
        ini_set("soap.wsdl_cache_enabled", "0");

        $leyenda = new SoapClient($this->host . "controller/juzgados/JuzgadosServer.php?wsdl");
        $leyenda = $leyenda->selectList($list, '3a332bac303f6e9536b36731090f66800abee04c', 'cf3387f0417a09352af09b2926e3e38522bef9f5');
        return $leyenda;
    }

    public function getJuzgadoInstancia($cveJuzgado) {
        ini_set("default_socket_timeout", 200);
        ini_set("soap.wsdl_cache_enabled", "0");

        $juzgados = new SoapClient($this->host . "controller/juzgados/JuzgadosServer.php?wsdl");
        $juzgados = $juzgados->selectInstancia($cveJuzgado, '3a332bac303f6e9536b36731090f66800abee04c', 'cf3387f0417a09352af09b2926e3e38522bef9f5');
        return $juzgados;
    }

    public function getJuzgadoStatus($status) {
        ini_set("default_socket_timeout", 200);
        ini_set("soap.wsdl_cache_enabled", "0");

        $juzgados = new SoapClient($this->host . "controller/juzgados/JuzgadosServer.php?wsdl");
        $juzgados = $juzgados->selectJuzgadoStatus($status, '3a332bac303f6e9536b36731090f66800abee04c', 'cf3387f0417a09352af09b2926e3e38522bef9f5');
        return $juzgados;
    }
    
    public function getJuzgadosDistritos($distrito) {
        ini_set("default_socket_timeout", 200);
        ini_set("soap.wsdl_cache_enabled", "0");

        $juzgados = new SoapClient($this->host . "controller/juzgados/JuzgadosServer.php?wsdl");
        $juzgados = $juzgados->selectJuzgadosDistritos($distrito, '3a332bac303f6e9536b36731090f66800abee04c', 'cf3387f0417a09352af09b2926e3e38522bef9f5');
        return $juzgados;
    }
    
    public function getJuzgadosMunicipios($municipio) {
        ini_set("default_socket_timeout", 200);
        ini_set("soap.wsdl_cache_enabled", "0");

        $juzgados = new SoapClient($this->host . "controller/juzgados/JuzgadosServer.php?wsdl");
        $juzgados = $juzgados->selectJuzgadoMunicipios($municipio, '3a332bac303f6e9536b36731090f66800abee04c', 'cf3387f0417a09352af09b2926e3e38522bef9f5');
        return $juzgados;
    }

    public function getJuzgadosRamos($ramo) {
        ini_set("default_socket_timeout", 200);
        ini_set("soap.wsdl_cache_enabled", "0");

        $juzgados = new SoapClient($this->host . "controller/juzgados/JuzgadosServer.php?wsdl");
        $juzgados = $juzgados->selectJuzgadosRamos($ramo, '3a332bac303f6e9536b36731090f66800abee04c', 'cf3387f0417a09352af09b2926e3e38522bef9f5');
        return $juzgados;
    }

}

//$juzgadoCliente = new JuzgadoCliente();
//echo $juzgadoCliente->getJuzgadosDistritos("3");
?>
