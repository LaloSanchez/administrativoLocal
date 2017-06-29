<?php
include_once(dirname(__FILE__) . "/../../../tribunal/host/Host.Class.php");
include_once(dirname(__FILE__) . "/../../../tribunal/json/JsonDecod.Class.php");

define("ruta",    dirname(__FILE__));

class PermisosCliente {

    private $host = null;

    public function __construct() {
        $this->host = new Host(ruta . "/../../../tribunal/host/config.xml", "GESTION");
        $this->host = $this->host->getConnect();
    }

    public function getEstados($nombre,$cveSistema,$cvePerfil) {
        ini_set("default_socket_timeout", 200);
        ini_set("soap.wsdl_cache_enabled", "0");
        
        $permisos = new SoapClient($this->host . "controller/permisos/PermisosServer.php?wsdl");
        $permisos = $permisos->getPermisosFormulario($nombre,$cveSistema,$cvePerfil,'3a332bac303f6e9536b36731090f66800abee04c', 'cf3387f0417a09352af09b2926e3e38522bef9f5');
        return $permisos;
    }

}
//$PermisosCliente = new PermisosCliente();
//echo $PermisosCliente->getEstados("Edictos", "30", "4143");
?>
