<?php

session_start();
include_once(dirname(__FILE__) . "/../../modelos/dao/GenericDAO.Class.php");
include_once(dirname(__FILE__) . "/../../tribunal/json/JsonEncod.Class.php");
include_once(dirname(__FILE__) . "/../../controladores/controlpatrimonial/EtiquetasController.Class.php");

class EtiquetasFacade {

    private $proveedor;

    public function __construct() {
        
    }

    public function cargarClasificadorBienes($params) {
        $EtiquetasController = new EtiquetasController();
        return $EtiquetasController->cargarClasificadorBienes($params);
    }

    public function cargarEstadosBienes($params) {
        $EtiquetasController = new EtiquetasController();
        return $EtiquetasController->cargarEstadosBienes($params);
    }

    public function verDetalle($params) {
        $EtiquetasController = new EtiquetasController();
        return $EtiquetasController->verDetalle($params);
    }

    public function consultarEmpleadosAdscripcion($params) {
        $EtiquetasController = new EtiquetasController();
        return $EtiquetasController->consultarEmpleadosAdscripcion($params);
    }

    public function datatableConsultaBienes($params) {
        $EtiquetasController = new EtiquetasController();
        return $EtiquetasController->datatableConsultaBienes($params);
    }

}

@$accion = $_POST["accion"];
@$draw = trim($_POST["draw"]);
@$extras = null;
@$extrasPOST = null;
@$extrasFILES = null;
if (isset($_POST["order"])) {
    @$order["column"] = trim($_POST["order"][0]["column"]) + 1;
    @$order["dir"] = trim($_POST["order"][0]["dir"]);
} else {
    $order = "";
}
if (array_key_exists("filtros", $_POST) && !is_null($_POST["filtros"])) {
    foreach ($_POST["filtros"] as $key => $value) {
        if ($value != "" && $value != null && $value != "null")
            @$extras[$key] = $value;
    }
}
if (!is_null($_POST)) {
    foreach ($_POST as $key => $value) {
        if (!is_array($value))
            @$extrasPOST[$key] = utf8_decode($value);
        else
            @$extrasPOST[$key] = ($value);
    }
}
if (!is_null($_FILES)) {
    foreach ($_FILES as $key => $value) {
        @$extrasFILES[$key] = ($value);
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
$EtiquetasFacade = new EtiquetasFacade();
$param = array("order" => $order, "search" => $search, "draw" => $draw, "limit" => $limit, "extras" => $extras, "extrasPost" => $extrasPOST);
$json = new Encode_JSON();
switch ($accion) {
    case "cargarClasificadorBienes":
        echo $json->encode($EtiquetasFacade->cargarClasificadorBienes($param));
        break;
    case "cargarEstadosBienes":
        echo $json->encode($EtiquetasFacade->cargarEstadosBienes($param));
        break;
    case "verDetalle":
        echo $json->encode($EtiquetasFacade->verDetalle($param));
        break;
    case "consultarEmpleadosAdscripcion":
        echo ($EtiquetasFacade->consultarEmpleadosAdscripcion($param));
        break;
    case "datatableConsultaBienes":
        echo ($EtiquetasFacade->datatableConsultaBienes($param));
        break;
    case "generarEtiquetas":
        echo ($EtiquetasFacade->generarEtiquetas($param));
        break;

    default : echo '{"totalCount":0,"error":"No se recibieron parametros"}';
        break;
}
