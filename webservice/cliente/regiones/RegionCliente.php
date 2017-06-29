<?php
include_once(dirname(__FILE__) . "/../../../tribunal/host/Host.Class.php");
include_once(dirname(__FILE__) . "/../../../tribunal/json/JsonDecod.Class.php");

define("ruta",    dirname(__FILE__));

class RegionCliente {

    private $host = null;

    public function __construct() {
        $this->host = new Host(ruta . "/../../../tribunal/host/config.xml", "GESTION");
        $this->host = $this->host->getConnect();
    }

    public function getRegiones($cveRegion,$nomRegion) {
        ini_set("default_socket_timeout", 200);
        ini_set("soap.wsdl_cache_enabled", "0");
        $region = new SoapClient($this->host . "controller/regiones/RegionesServer.php?wsdl");
        $region = $region->selectRegiones($cveRegion,$nomRegion,'3a332bac303f6e9536b36731090f66800abee04c', 'cf3387f0417a09352af09b2926e3e38522bef9f5');
        return $region;
    }
    
    public function setRegion($cveRegion,$nomRegion) {
        ini_set("default_socket_timeout", 200);
        ini_set("soap.wsdl_cache_enabled", "0");
        
        $region = new SoapClient($this->host . "controller/regiones/RegionesServer.php?wsdl");
        $region = $region->guardarRegion($cveRegion,$nomRegion,'3a332bac303f6e9536b36731090f66800abee04c', 'cf3387f0417a09352af09b2926e3e38522bef9f5');
        return $region;
    }

}

?>
