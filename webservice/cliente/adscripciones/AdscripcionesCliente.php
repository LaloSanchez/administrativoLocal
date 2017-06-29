<?php

include_once(dirname(__FILE__) . "/../../../tribunal/host/Host.Class.php");
include_once(dirname(__FILE__) . "/../../../tribunal/json/JsonDecod.Class.php");

if (!defined("ruta")) {
    define("ruta", dirname(__FILE__));
}

class AdscripcionesCliente {

    private $host = null;

    public function __construct() {
        $this->host = new Host(ruta . "/../../../tribunal/host/config.xml", "GESTION");
        $this->host = $this->host->getConnect();
    }

    public function getAdscripciones($id = '') {
        try {
            ini_set("default_socket_timeout", 200);
            ini_set("soap.wsdl_cache_enabled", "0");
            $adscripciones = new SoapClient($this->host . "controller/adscripciones/AdscripcionesServer.php?wsdl");
            $adscripciones = $adscripciones->listaAdscripciones($id);

            if (is_soap_fault($adscripciones)) {
                trigger_error("SOAP Fault: (faultcode: {$adscripciones->faultcode}, faultstring: {$adscripciones->faultstring})", E_USER_ERROR);
            }
        } catch (SoapFault $exception) {
            trigger_error("error");
        }

        return $adscripciones;
    }

    public function selectTipoAdscripciones($id = '') {
        try {

            ini_set("default_socket_timeout", 200);
            ini_set("soap.wsdl_cache_enabled", "0");
            $adscripciones = new SoapClient($this->host . "controller/adscripciones/AdscripcionesServer.php?wsdl");
            $adscripciones = $adscripciones->selectTipoAdscripcion($id);

            if (is_soap_fault($adscripciones)) {
                trigger_error("SOAP Fault: (faultcode: {$adscripciones->faultcode}, faultstring: {$adscripciones->faultstring})", E_USER_ERROR);
            }
        } catch (SoapFault $exception) {
            trigger_error("error");
        }

        return $adscripciones;
    }

}
