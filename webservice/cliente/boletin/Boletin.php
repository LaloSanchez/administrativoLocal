<?php
    include_once(dirname(__FILE__) . "/../../../tribunal/host/Host.Class.php");
    include_once(dirname(__FILE__) . "/../../../tribunal/json/JsonDecod.Class.php");
class Boletin {
    
    private $host = null;

    public function __construct() {
        //$this->host = new Host("../../../tribunal/host/config.xml", "BOLETIN");
        //$this->host = $this->host->getConnect();
    }

    public function serviceBoletin($jsonRequest) {
        ini_set("default_socket_timeout", 600);
        ini_set("soap.wsdl_cache_enabled", "0");
        $soapClient = new SoapClient("http://10.22.165.204/notificacion/webservice/servidor/electronicoboletin/electronicoBoletinServer.php?wsdl");
        $jsonResponse = $soapClient->insertAcuerdoBoletin($jsonRequest, '3a332bac303f6e9536b36731090f66800abee04c', 'cf3387f0417a09352af09b2926e3e38522bef9f5');
        //print_r($jsonResponse);
        return $jsonResponse;
    }
}

?>
