<?php
//actualizacion
include_once(dirname(__FILE__) . "/../../../tribunal/host/Host.Class.php");
include_once(dirname(__FILE__) . "/../../../tribunal/json/JsonDecod.Class.php");

@define("ruta", dirname(__FILE__));

class PersonalCliente {

    private $host = null;

    public function __construct() {
        $this->host = new Host(ruta . "/../../../tribunal/host/config.xml", "GESTION");
        $this->host = $this->host->getConnect();
    }

    public function getPersonalJuzgado($cveAdscripcion, $cvecategoria) {
        ini_set("default_socket_timeout", 200);
        ini_set("soap.wsdl_cache_enabled", "0");
        $distrito = new SoapClient($this->host . "controller/personal/PersonalServer.php?wsdl");
        $distrito = $distrito->getPersonalJuzgado($cveAdscripcion, $cvecategoria, '3a332bac303f6e9536b36731090f66800abee04c', 'cf3387f0417a09352af09b2926e3e38522bef9f5');
        return $distrito;
    }
    public function getPersonalAdscripcion($cveAdscripcion) {
        ini_set("default_socket_timeout", 200);
        ini_set("soap.wsdl_cache_enabled", "0");
        $distrito = new SoapClient($this->host . "controller/personal/PersonalServer.php?wsdl");
        $distrito = $distrito->getPersonalAdscripcion($cveAdscripcion, '3a332bac303f6e9536b36731090f66800abee04c', 'cf3387f0417a09352af09b2926e3e38522bef9f5');
        return $distrito;
    }

    public function getPersonalNombre($NumEmpleado, $nombre, $cveJuzgado) {
        ini_set("default_socket_timeout", 200);
        ini_set("soap.wsdl_cache_enabled", "0");
        $distrito = new SoapClient($this->host . "controller/personal/PersonalServer.php?wsdl");
        $distrito = $distrito->getNombre($NumEmpleado, $nombre, $cveJuzgado, '3a332bac303f6e9536b36731090f66800abee04c', 'cf3387f0417a09352af09b2926e3e38522bef9f5');
        return $distrito;
    }
    public function getNumEmpleado($NumEmpleado) {
        ini_set("default_socket_timeout", 200);
        ini_set("soap.wsdl_cache_enabled", "0");
        $distrito = new SoapClient($this->host . "controller/personal/PersonalServer.php?wsdl");
        $distrito = $distrito->getNumEmpleado($NumEmpleado, '3a332bac303f6e9536b36731090f66800abee04c', 'cf3387f0417a09352af09b2926e3e38522bef9f5');
        return $distrito;
    }
    
    public function getPersonalAds($adscripciones, $categorias){
        ini_set("default_socket_timeout", 200);
        ini_set("soap.wsdl_cache_enabled", "0");
        $distrito = new SoapClient($this->host . "controller/personal/PersonalServer.php?wsdl");
        $distrito = $distrito->getPersonalAds($adscripciones, $categorias, '3a332bac303f6e9536b36731090f66800abee04c', 'cf3387f0417a09352af09b2926e3e38522bef9f5');
        return $distrito;
    }

}

//$personalCliente = new PersonalCliente();
//$result = $personalCliente->getPersonalJuzgado(10083);
//var_dump($result);
?>
