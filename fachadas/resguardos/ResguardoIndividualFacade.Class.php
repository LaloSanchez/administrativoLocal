<?php

session_start();
include_once(dirname(__FILE__) . "/../../modelos/dao/GenericDAO.Class.php");
include_once(dirname(__FILE__) . "/../../tribunal/json/JsonEncod.Class.php");
include_once(dirname(__FILE__) . "/../../controladores/resguardos/ResguardoIndividualController.Class.php");

class ResguardoIndividualFacade {

    private $proveedor;

    public function __construct() {
        
    }
    public function cargarDatosEmpleado($params) {
        $ResguardoIndividualController = new ResguardoIndividualController();
        return $ResguardoIndividualController->cargarDatosEmpleado($params);
    }
    public function getBien($params) {
        $ResguardoIndividualController = new ResguardoIndividualController();
        return $ResguardoIndividualController->getBien($params);
    }
    public function validarGuardarNuevoBien($params) {
        $ResguardoIndividualController = new ResguardoIndividualController();
        return $ResguardoIndividualController->validarGuardarNuevoBien($params);
    }
    public function guardarNuevoBien($params) {
        $ResguardoIndividualController = new ResguardoIndividualController();
        return $ResguardoIndividualController->guardarNuevoBien($params);
    }
    public function finalizarRegistro($params) {
        $ResguardoIndividualController = new ResguardoIndividualController();
        return $ResguardoIndividualController->finalizarRegistro($params);
    }
    public function getEmpleado($params) {
        $ResguardoIndividualController = new ResguardoIndividualController();
        return $ResguardoIndividualController->getEmpleado($params);
    }
    public function registrarBien($params) {
        $ResguardoIndividualController = new ResguardoIndividualController();
        return $ResguardoIndividualController->registrarBien($params);
    }
    public function eliminarBien($params) {
        $ResguardoIndividualController = new ResguardoIndividualController();
        return $ResguardoIndividualController->eliminarBien($params);
    }
    public function cargarFaltantes($params) {
        $ResguardoIndividualController = new ResguardoIndividualController();
        return $ResguardoIndividualController->cargarFaltantes($params);
    }
    public function cargarSobrantes($params) {
        $ResguardoIndividualController = new ResguardoIndividualController();
        return $ResguardoIndividualController->cargarSobrantes($params);
    }
    public function cargarDatosAdscripcion($params) {
        $ResguardoIndividualController = new ResguardoIndividualController();
        return $ResguardoIndividualController->cargarDatosAdscripcion($params);
    }
    public function cargarFaltantesAdscripcion($params) {
        $ResguardoIndividualController = new ResguardoIndividualController();
        return $ResguardoIndividualController->cargarFaltantesAdscripcion($params);
    }
    public function cargarSobrantesAdscripcion($params) {
        $ResguardoIndividualController = new ResguardoIndividualController();
        return $ResguardoIndividualController->cargarSobrantesAdscripcion($params);
    }
}
@$accion = $_POST["accion"];
@$draw = trim($_POST["draw"]);
if (isset($_POST["order"])) {
    @$order["column"] = trim($_POST["order"][0]["column"]) + 1;
    @$order["dir"] = trim($_POST["order"][0]["dir"]);
} else {
    $order = "";
}
if (!is_null($_POST)) {
    foreach ($_POST as $key => $value) {
        if (!is_array($value))
            @$extrasPOST[$key] = utf8_decode($value);
        else
            @$extrasPOST[$key] = ($value);
    }
}
if (isset($_POST["search"])) {
    @$search["value"] = trim($_POST["search"]["value"]);
} else {
    $search = "";
}

if (isset($_POST["start"]) && isset($_POST["length"])) {
    @$pag = trim($_POST["start"]);
    @$maxRows = trim($_POST["length"]);
    $limit = array("max" => $maxRows, "pag" => $pag);
} else {
    $limit = null;
}
$ResguardoIndividualFacade = new ResguardoIndividualFacade();
$param = array("order" => $order, "search" => $search, "draw" => $draw, "limit" => $limit,"extrasPost" => $extrasPOST);
switch ($accion) {
    case "cargarDatosEmpleado":
        echo $ResguardoIndividualFacade->cargarDatosEmpleado($param);
    break;
    case "consultarCogGiros":
        echo $ResguardoIndividualFacade->consultarCogGiros($param);
    break;
    case "agregarGiro":
        echo $ResguardoIndividualFacade->agregarGiro($param);
    break;
    case "eliminarGiro":
        echo $ResguardoIndividualFacade->eliminarGiro($param);
    break;
    case "getBien":
        echo $ResguardoIndividualFacade->getBien($param);
    break;
    case "validarGuardarNuevoBien":
        echo $ResguardoIndividualFacade->validarGuardarNuevoBien($param);
    break;
    case "guardarNuevoBien":
        echo $ResguardoIndividualFacade->guardarNuevoBien($param);
    break;
    case "finalizarRegistro":
        echo $ResguardoIndividualFacade->finalizarRegistro($param);
    break;
    case "registrarBien":
        echo $ResguardoIndividualFacade->registrarBien($param);
    break;
    case "eliminarBien":
        echo $ResguardoIndividualFacade->eliminarBien($param);
    break;
    case "cargarFaltantes":
        echo $ResguardoIndividualFacade->cargarFaltantes($param);
    break;
    case "cargarSobrantes":
        echo $ResguardoIndividualFacade->cargarSobrantes($param);
    break;
    case "cargarDatosAdscripcion":
        echo $ResguardoIndividualFacade->cargarDatosAdscripcion($param);
    break;
    case "cargarFaltantesAdscripcion":
        echo $ResguardoIndividualFacade->cargarFaltantesAdscripcion($param);
    break;
    case "cargarSobrantesAdscripcion":
        echo $ResguardoIndividualFacade->cargarSobrantesAdscripcion($param);
    break;
    default : echo '{"totalCount":0,"error":"No se recibieron parametros"}';
        break;
}

?>