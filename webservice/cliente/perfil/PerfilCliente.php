<?php

include_once(dirname(__FILE__) . "/../../../tribunal/host/Host.Class.php");
include_once(dirname(__FILE__) . "/../../../tribunal/json/JsonDecod.Class.php");

class PerfilCliente {

    private $host = null;

    public function __construct() {
        $this->host = new Host("../../../tribunal/host/config.xml", "GESTION");
        $this->host = $this->host->getConnect();
    }

    public function getPerfil() {
        ini_set("default_socket_timeout", 200);
        ini_set("soap.wsdl_cache_enabled", "0");
        $perfil = new SoapClient($this->host . "controller/perfiles/PerfilServer.php?wsdl");
        $perfil = $perfil->getPerfil($_SESSION['cvePerfil'], $_SESSION['cveSistema'], '3a332bac303f6e9536b36731090f66800abee04c', 'cf3387f0417a09352af09b2926e3e38522bef9f5');
        return $perfil;
    }

    public function getPerfilesDisponibles() {
        ini_set("default_socket_timeout", 200);
        ini_set("soap.wsdl_cache_enabled", "0");
        error_log($this->host . "controller/perfiles/PerfilServer.php?wsdl");
        $perfil = new SoapClient($this->host . "controller/perfiles/PerfilServer.php?wsdl");
        $perfil = $perfil->getPerfilesDisponibles($_SESSION['cveUsuarioSistema'], $_SESSION['cveSistema'], '3a332bac303f6e9536b36731090f66800abee04c', 'cf3387f0417a09352af09b2926e3e38522bef9f5');
        return $perfil;
    }
    public function getPerfilesDesc($cvePerfil, $cveSistema) {
        ini_set("default_socket_timeout", 200);
        ini_set("soap.wsdl_cache_enabled", "0");
        $perfil = new SoapClient($this->host . "controller/perfiles/PerfilServer.php?wsdl");
        $perfil = $perfil->getPerfilesDisponibles($cvePerfil, $cveSistema, '3a332bac303f6e9536b36731090f66800abee04c', 'cf3387f0417a09352af09b2926e3e38522bef9f5');
        return $perfil;
    }

}

?>
