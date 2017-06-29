<?php

include_once(dirname(__FILE__) . "/../../../tribunal/host/Host.Class.php");
include_once(dirname(__FILE__) . "/../../../tribunal/json/JsonDecod.Class.php");
@define("ruta", dirname(__FILE__));

class ImagenesCliente {

    private $host = null;

    public function __construct() {
        $this->host = new Host(ruta . "/../../../tribunal/host/config.xml", "GESTION");
        $this->host = $this->host->getConnect();
    }

    public function getImgEmployee($num) {
        ini_set("default_socket_timeout", 200);
        ini_set("soap.wsdl_cache_enabled", "0");
        $img = new SoapClient($this->host . "controller/imagenesPersonal/ImagenesPersonal.php?wsdl");        
        $img = $img->getImgEmployee($num, '3a332bac303f6e9536b36731090f66800abee04c', 'cf3387f0417a09352af09b2926e3e38522bef9f5');
        
        return $img;
    }

    public function getSignImgEmployee($cveUsuariosFirmas) {
        ini_set("default_socket_timeout", 200);
        ini_set("soap.wsdl_cache_enabled", "0");
        $img = new SoapClient($this->host . "controller/imagenesPersonal/ImagenesPersonal.php?wsdl");        
        $img = $img->getSignImgEmployee($cveUsuariosFirmas, '3a332bac303f6e9536b36731090f66800abee04c', 'cf3387f0417a09352af09b2926e3e38522bef9f5');
        
        return $img;
    }

}
