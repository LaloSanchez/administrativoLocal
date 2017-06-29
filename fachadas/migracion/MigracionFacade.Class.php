<?php

session_start();
include_once(dirname(__FILE__) . "/../../modelos/dao/GenericDAO.Class.php");
include_once(dirname(__FILE__) . "/../../tribunal/json/JsonEncod.Class.php");
include_once(dirname(__FILE__) . "/../../controladores/migracion/MigracionController.Class.php");

class MigracionFacade {

    private $proveedor;

    public function __construct() {
        
    }

    public function migrarDatosDescarga($params) {
        $MigracionController = new MigracionController();
        return $MigracionController->migrarDatosDescarga($params);
    }
    public function cargarDatos() {
        $MigracionController = new MigracionController();
        return $MigracionController->cargarDatos();
    }
    public function obtenerAdscripciones() {
        $MigracionController = new MigracionController();
        return $MigracionController->obtenerAdscripciones();
    }
    
}

@$accion = $_POST["accion"];
@$draw = trim($_POST["draw"]);
$jsonEncode = new Encode_JSON();
if (isset($_POST["order"])) {
    @$order["column"] = trim($_POST["order"][0]["column"]) + 1;
    @$order["dir"] = trim($_POST["order"][0]["dir"]);
} else {
    $order = "";
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
if (!is_null($_POST)) {
    foreach ($_POST as $key => $value) {
        if (!is_array($value))
            @$extrasPOST[$key] = utf8_decode($value);
        else
            @$extrasPOST[$key] = ($value);
    }
}

$MigracionFacade = new MigracionFacade();
$param = array("order" => $order, "search" => $search, "draw" => $draw, "limit" => $limit,"extrasPost" => $extrasPOST);
switch ($accion) {
    case "migrarDatosDescarga":
        echo $MigracionFacade->migrarDatosDescarga($param);
    break;
    case "cargarDatos":
        echo $MigracionFacade->cargarDatos();
    break;
    case "obtenerAdscripciones":
        echo $MigracionFacade->obtenerAdscripciones();
    break;
    default : echo '{"totalCount":0,"error":"No se recibieron parametros"}';
        break;
}
?>