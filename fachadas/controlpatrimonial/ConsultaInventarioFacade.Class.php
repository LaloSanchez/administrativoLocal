<?php

session_start();
include_once(dirname(__FILE__) . "/../../modelos/dao/GenericDAO.Class.php");
include_once(dirname(__FILE__) . "/../../tribunal/json/JsonEncod.Class.php");
include_once(dirname(__FILE__) . "/../../controladores/controlpatrimonial/ConsultaInventarioController.Class.php");

class ConsultaInventario {

    private $proveedor;

    public function __construct() {
        
    }

    public function datatableConsultaInventario($params) {
        $ConsultaInventarioController = new ConsultaInventarioController();
        return $ConsultaInventarioController->datatableConsultaInventario($params);
    }
    public function consultaSeguros($params) {
        $ConsultaInventarioController = new ConsultaInventarioController();
        return $ConsultaInventarioController->consultaAsegurados($params);
    }
    public function consultaInventarios($params) {
        $ConsultaInventarioController = new ConsultaInventarioController();
        return $ConsultaInventarioController->consultaInventarios($params);
    }
    public function consultaCoberturas($params,$extrasPOST) {
        $ConsultaInventarioController = new ConsultaInventarioController();
        return $ConsultaInventarioController->consultaCoberturas($params,$extrasPOST);
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
if (!is_null(@$_POST['filtros'])) {
    foreach ($_POST['filtros'] as $key => $value) {
        if ($value != "" && $value != null && $value != "null")
            @$extras[$key] = $value;
    }
}
if (!is_null($_POST)) {
    foreach ($_POST as $key => $value) {
        @$extrasPOST[$key] = $value;
    }
}
if (!is_null($_FILES)) {
//    var_dump($_FILES);
    foreach ($_FILES as $key => $value) {
        @$extrasFILES[$key] = $value;
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

$ConsultaInventarioFacade = new ConsultaInventario();
$param = array("order" => $order, "search" => $search, "draw" => $draw, "limit" => $limit, "extras" => $extras, "extrasPost" => $extrasPOST);
switch ($accion) {
    case "datatableConsultaInventario":
        echo $ConsultaInventarioFacade->datatableConsultaInventario($param);
    break;
    case "consultaSeguros":
        echo $ConsultaInventarioFacade->consultaSeguros($param);
    break;
    case "consultaInventarios":
        echo $ConsultaInventarioFacade->consultaInventarios($param);
    break;
    case "consultaCoberturas":
        echo $ConsultaInventarioFacade->consultaCoberturas($param,$extrasPOST);
    break;
    default : echo '{"totalCount":0,"error":"No se recibieron parametros"}';
        break;
}

?>