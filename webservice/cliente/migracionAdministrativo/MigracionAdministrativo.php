<?php
include_once(dirname(__FILE__) . "/../../../tribunal/host/Host.Class.php");
include_once(dirname(__FILE__) . "/../../../tribunal/json/JsonDecod.Class.php");

define("ruta",    dirname(__FILE__));

class MigracionAdministrativo {

    private $host = null;

    public function __construct() {
        $this->host = new Host(ruta . "/../../../tribunal/host/config.xml", "GESTION");
        $this->host = $this->host->getConnect();
    }

    public function migracionAdministrativo($array) {
        ini_set("default_socket_timeout", 200);
        ini_set("soap.wsdl_cache_enabled", "0");
        $datos = new SoapClient("http://localhost/administrativo/webservice/servidor/AdministrativoLocal//AdministrativoLocalServer.php?wsdl");//$this->host . "controller/regiones/RegionesServer.php?wsdl");
        $datos = $datos->iniciarMigracion($array,'3a332bac303f6e9536b36731090f66800abee04c', 'cf3387f0417a09352af09b2926e3e38522bef9f5');
        return $datos;
    }
    
    public function migracionlocal($json){
        try{
            ini_set("default_socket_timeout", 200);
            ini_set("soap.wsdl_cache_enabled", "0");
            $datos = new SoapClient("http://localhost/administrativo/webservice/servidor/AdministrativoLocal//AdministrativoLocalServer.php?wsdl");//$this->host . "controller/regiones/RegionesServer.php?wsdl");
            $datos = $datos->cargarResguardos($json,'3a332bac303f6e9536b36731090f66800abee04c', 'cf3387f0417a09352af09b2926e3e38522bef9f5');
            return $datos;
        }catch(SoapFault $e){
            print_r($e);
        }
    }
    
    public function obtenerAdscripciones(){
        try{
            ini_set("default_socket_timeout", 200);
            ini_set("soap.wsdl_cache_enabled", "0");
            $datos = new SoapClient("http://10.22.165.204/administrativo/webservice/servidor/AdministrativoLocal//AdministrativoLocalServer.php?wsdl");//$this->host . "controller/regiones/RegionesServer.php?wsdl");
            $datos = $datos->obtenerAdscripciones('3a332bac303f6e9536b36731090f66800abee04c', 'cf3387f0417a09352af09b2926e3e38522bef9f5');
            return $datos;
            
        }catch(SoapFault $e){
            print_r($e);
        }
    }

}
?>
