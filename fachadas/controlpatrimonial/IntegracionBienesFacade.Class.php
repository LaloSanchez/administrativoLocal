<?php

session_start();
include_once(dirname(__FILE__) . "/../../modelos/dao/GenericDAO.Class.php");
include_once(dirname(__FILE__) . "/../../tribunal/json/JsonEncod.Class.php");
include_once(dirname(__FILE__) . "/../../controladores/controlpatrimonial/IntegracionBienesController.Class.php");

class IntegracionBienesFacade {

    private $proveedor;


    public function __construct() {
        
    }

    public function datatableIntegracionBienes($params) {
        $IntegracionBienesController = new IntegracionBienesController();
        return $IntegracionBienesController->datatableIntegracionBienes($params);
    } 
    public function comboClasificadorBien() {
        $IntegracionBienesController = new IntegracionBienesController();
        return $IntegracionBienesController->comboClasificadorBien();
    }
    public function datatableBienPadre($param,$listaUsados) {
        $IntegracionBienesController = new IntegracionBienesController();
        return $IntegracionBienesController->datatableBienPadre($param,$listaUsados);
    }
    public function guardarBienIntegrado($listaBienPadre,$listaBienHijo,$desBienIntegrado) {
        $IntegracionBienesController = new IntegracionBienesController();
        return $IntegracionBienesController->guardarBienIntegrado($listaBienPadre,$listaBienHijo,$desBienIntegrado);
    }
    public function actualizarBienIntegrado($listaBienPadre,$listaBienHijo,$desBienIntegrado,$idIntegracionInventario) {
        $IntegracionBienesController = new IntegracionBienesController();
        return $IntegracionBienesController->actualizarBienIntegrado($listaBienPadre,$listaBienHijo,$desBienIntegrado,$idIntegracionInventario);
    }
    public function borrarInventario($idIntegracionInventario) {
        $IntegracionBienesController = new IntegracionBienesController();
        return $IntegracionBienesController->borrarInventario($idIntegracionInventario);
    }
    public function consultarBienPadre($idIntegracionInventario) {
        $IntegracionBienesController = new IntegracionBienesController();
        return $IntegracionBienesController->consultarBienPadre($idIntegracionInventario);
    }
    public function consultarBienHijo($idIntegracionInventario) {
        $IntegracionBienesController = new IntegracionBienesController();
        return $IntegracionBienesController->consultarBienHijo($idIntegracionInventario);
    }
    public function consultarBienesUsados() {
        $IntegracionBienesController = new IntegracionBienesController();
        return $IntegracionBienesController->consultarBienesUsados();
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

$IntegracionBienesFacade = new IntegracionBienesFacade();
$param = array("order" => $order, "search" => $search, "draw" => $draw, "limit" => $limit, "extras" => $extras, "extrasPost" => $extrasPOST);
switch ($accion) {
    case "datatableIntegracionBienes":
        echo $IntegracionBienesFacade->datatableIntegracionBienes($param);
    break;
    case "comboClasificadorBien":
        echo $IntegracionBienesFacade->comboClasificadorBien();
    break;
    case "datatableBienPadre":
        @$listaUsados = $_POST["listaUsados"];
        echo $IntegracionBienesFacade->datatableBienPadre($param,$listaUsados);
    break;
    case "guardarBienIntegrado":
        @$listaBienPadre = $_POST["listaBienPadre"];
        @$listaBienHijo = $_POST["listaBienHijo"];
        @$desBienIntegrado = utf8_decode($_POST["desBienIntegrado"]);
        echo $IntegracionBienesFacade->guardarBienIntegrado($listaBienPadre,$listaBienHijo,$desBienIntegrado);
    break;
    case "actualizarBienIntegrado":
        @$listaBienPadre = $_POST["listaBienPadre"];
        @$listaBienHijo = $_POST["listaBienHijo"];
        @$idIntegracionInventario = $_POST["idIntegracionInventario"];
        @$desBienIntegrado = utf8_decode($_POST["desBienIntegrado"]);
        echo $IntegracionBienesFacade->actualizarBienIntegrado($listaBienPadre,$listaBienHijo,$desBienIntegrado,$idIntegracionInventario);
    break;
    case "borrarInventario":
        @$idIntegracionInventario = $_POST["idIntegracionInventario"];
        @$desBienIntegrado = $_POST["desBienIntegrado"];
        echo $IntegracionBienesFacade->borrarInventario($idIntegracionInventario);
    break;
    case "consultarBienPadre":
        @$idIntegracionInventario = $_POST["idIntegracionInventario"];
       
        echo $IntegracionBienesFacade->consultarBienPadre($idIntegracionInventario);
    break;
     case "consultarBienHijo":
        @$idIntegracionInventario = $_POST["idIntegracionInventario"];
       
        echo $IntegracionBienesFacade->consultarBienHijo($idIntegracionInventario);
    break;
    case "consultarBienesUsados":
       
        echo $IntegracionBienesFacade->consultarBienesUsados();
    break;
    default : echo '{"totalCount":0,"error":"No se recibieron parametros"}';
        break;
}

?>