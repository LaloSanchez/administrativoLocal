<?php
include_once(dirname(__FILE__) . "/../../../tribunal/host/Host.Class.php");
//define("ruta",    dirname(__FILE__));
class PersonasautorizadaselectronicoCliente {
    private $host = null;

    /*public function __construct() {
        $this->host = new Host(ruta . "/../../../tribunal/host/config.xml", "GESTION"); 
        $this->host = $this->host->getConnect();
    }*/
    public function esPersonaAutorizada($json) {
        ini_set("default_socket_timeout", 200);
        ini_set("soap.wsdl_cache_enabled", "0");//http://localhost:8080/electronico/desarrollo/electronico/webservice/servidor/personasautorizadas/PersonasautorizadasScramble.wsdl
//        $personaAutorizada = new SoapClient("http://10.22.165.204/electronico/webservice/servidor/personasautorizadassigejupe/PersonasautorizadasServer.php?wsdl");
        $personaAutorizada = new SoapClient("http://electronico.pjedomex.gob.mx/electronico/webservice/servidor/personasautorizadassigejupe/PersonasautorizadasServer.php?wsdl");
        $autorizada = $personaAutorizada->esPersonaAutorizada($json);
        return $autorizada;
    }
    public function buscarPersonaAutorizada($json){
        ini_set("default_socket_timeout", 200);
        ini_set("soap.wsdl_cache_enabled", "0");
//        $personaAutorizada = new SoapClient("http://10.22.165.204/electronico/webservice/servidor/personasautorizadassigejupe/PersonasautorizadasServer.php?wsdl");
        $personaAutorizada = new SoapClient("http://electronico.pjedomex.gob.mx/electronico/webservice/servidor/personasautorizadassigejupe/PersonasautorizadasServer.php?wsdl");
        $autorizada = $personaAutorizada->buscarPersonaAutorizada($json);
        return $autorizada;
    }
    public function selectPersonaAutorizada($json){
        ini_set("default_socket_timeout", 200);
        ini_set("soap.wsdl_cache_enabled", "0");//http://localhost:8080/electronico/desarrollo/electronico/webservice/servidor/personasautorizadas/PersonasautorizadasScramble.wsdl
//        $personaAutorizada = new SoapClient("http://10.22.165.204/electronico/webservice/servidor/personasautorizadassigejupe/PersonasautorizadasServer.php?wsdl");
        $personaAutorizada = new SoapClient("http://electronico.pjedomex.gob.mx/electronico/webservice/servidor/personasautorizadassigejupe/PersonasautorizadasServer.php?wsdl");
        $autorizada = $personaAutorizada->selectPersonaAutorizada($json);
        return $autorizada;
    }
    
    public function getPaginasPersonaAutorizada($json){
        ini_set("default_socket_timeout", 200);
        ini_set("soap.wsdl_cache_enabled", "0");//http://localhost:8080/electronico/desarrollo/electronico/webservice/servidor/personasautorizadas/PersonasautorizadasScramble.wsdl
//        $personaAutorizada = new SoapClient("http://10.22.165.204/electronico/webservice/servidor/personasautorizadassigejupe/PersonasautorizadasServer.php?wsdl");
        $personaAutorizada = new SoapClient("http://electronico.pjedomex.gob.mx/electronico/webservice/servidor/personasautorizadassigejupe/PersonasautorizadasServer.php?wsdl");
        $autorizada = $personaAutorizada->getPaginasPersonaAutorizada($json);
        return $autorizada;
    }
    
    
}
?>