<?php
include_once(dirname(__FILE__) . "/../../../tribunal/host/Host.Class.php");
include_once(dirname(__FILE__) . "/../../../tribunal/json/JsonDecod.Class.php");
ini_set('memory_limit', '-1');

define("ruta",    dirname(__FILE__));

class MigracionAdministrativo {

    private $host = null;

    public function __construct() {
        $this->host = new Host(ruta . "/../../../tribunal/host/config.xml", "GESTION");
        $this->host = $this->host->getConnect();
    }

    public function migracionAdministrativo() {
        ini_set("default_socket_timeout", 200);
        ini_set("soap.wsdl_cache_enabled", "0");
        $datos = new SoapClient("http://10.22.165.179/administrativo/webservice/servidor/AdministrativoLocal//AdministrativoLocalServer.php?wsdl");//$this->host . "controller/regiones/RegionesServer.php?wsdl");
        $datos = $datos->iniciarMigracion('3a332bac303f6e9536b36731090f66800abee04c', 'cf3387f0417a09352af09b2926e3e38522bef9f5');
        $datos = base64_decode($datos);
        return $datos;
    }

}
?>
