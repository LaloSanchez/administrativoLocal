<?php

//include_once '../../../tribunal/dtotojson/DtoToJson.Class.php';
//include_once '../../../tribunal/host/Host.Class.php';
//
include_once(dirname(__FILE__) . "/../../../tribunal/dtotojson/DtoToJson.Class.php");
include_once(dirname(__FILE__) . "/../../../tribunal/host/Host.Class.php");

//include_once(dirname(__FILE__) . "/../../../tribunal/host/Host.Class.php");
//include_once(dirname(__FILE__) . "/../../../tribunal/json/JsonDecod.Class.php");

if (!defined("ruta")) {
    define("ruta", dirname(__FILE__));
}

class RelacionExpedienteJuzgado {

    private $host = null;

    public function __construct() {
        $this->host = new Host(ruta . "/../../../tribunal/host/config.xml", "NOTIFICACION");
        $this->host = $this->host->getConnect();
    }

//
    public function getRelacionExpedienteJuzgado($list) {
        ini_set("default_socket_timeout", 200);
        ini_set("soap.wsdl_cache_enabled", "0");

        $relacionExpedienteJuzgado = new SoapClient($this->host . "relacionexpedientejuzgado/RelacionExpedienteJuzgado.Class.php?wsdl");
        $jsonRequest = $list;
        $relacionExpedienteJuzgado = $relacionExpedienteJuzgado->select($jsonRequest);
        return $relacionExpedienteJuzgado;
    }

}

//$prueba = new RelacionExpedienteJuzgado();
//$json = '{
//  "idtblRelacionExpedienteJuzgado": "5",
//  "fk_idtblJuzgados": "",
//  "fk_idtblPersonaAutorizadas": "",
//  "fk_idtblTipoPersonaAsunto": "",
//  "fk_idtblTipoParteAsunto": "",
//  "CodigoBarras": "",
//  "NumExpediente": "",
//  "AnioExpediente": "",
//  "FechaAlta": "",
//  "FechaModificacion": "",
//  "FechaBaja": "",
//  "Observaciones": "",
//  "Activo": "",
//  "TipoDocumento": ""
//  }';
//$response = $prueba->getRelacionExpedienteJuzgado($json);
//
//var_dump($response);

?>
