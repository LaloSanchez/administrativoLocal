<?php

session_start();
include_once(dirname(__FILE__) . "/../../modelos/dao/GenericDAO.Class.php");
include_once(dirname(__FILE__) . "/../../tribunal/json/JsonEncod.Class.php");
include_once(dirname(__FILE__) . "/../../controladores/controlpatrimonial/EtiquetasController.Class.php");
include_once(dirname(__FILE__) . "/../../controladores/controlpatrimonial/AdministradorBienesController.Class.php");

class AdministradorBienesFacade {

    private $proveedor;

    public function __construct() {
        
    }

    public function cargarClasificadorBienes($params) {
        $AdministradorBienesController = new AdministradorBienesController();
        return $AdministradorBienesController->cargarClasificadorBienes($params);
    }

    public function cargarGrupos($params) {
        $AdministradorBienesController = new AdministradorBienesController();
        return $AdministradorBienesController->cargarGrupos($params);
    }

    public function cargarGruposInmuebles($params) {
        $AdministradorBienesController = new AdministradorBienesController();
        return $AdministradorBienesController->cargarGruposInmuebles($params);
    }

    public function cargarSubGruposMueble($params) {
        $AdministradorBienesController = new AdministradorBienesController();
        return $AdministradorBienesController->cargarSubGruposMueble($params);
    }

    public function cargarSubGrupoInmueble($params) {
        $AdministradorBienesController = new AdministradorBienesController();
        return $AdministradorBienesController->cargarSubGrupoInmueble($params);
    }

    public function cargarClaseMueble($params) {
        $AdministradorBienesController = new AdministradorBienesController();
        return $AdministradorBienesController->cargarClaseMueble($params);
    }

    public function cargarClaseInmueble($params) {
        $AdministradorBienesController = new AdministradorBienesController();
        return $AdministradorBienesController->cargarClaseInmueble($params);
    }

    public function cargarSubClaseInmueble($params) {
        $AdministradorBienesController = new AdministradorBienesController();
        return $AdministradorBienesController->cargarSubClaseInmueble($params);
    }

    public function cargarCog($params) {
        $AdministradorBienesController = new AdministradorBienesController();
        return $AdministradorBienesController->cargarCog($params);
    }

    public function cargarBienes($params) {
        $AdministradorBienesController = new AdministradorBienesController();
        return $AdministradorBienesController->cargarBienes($params);
    }

    public function cargarColores($params) {
        $AdministradorBienesController = new AdministradorBienesController();
        return $AdministradorBienesController->cargarColores($params);
    }

    public function cargarMaterial($params) {
        $AdministradorBienesController = new AdministradorBienesController();
        return $AdministradorBienesController->cargarMaterial($params);
    }

    public function cargarFrecuenciaUso($params) {
        $AdministradorBienesController = new AdministradorBienesController();
        return $AdministradorBienesController->cargarFrecuenciaUso($params);
    }

    public function cargarUnidadMedida($params) {
        $AdministradorBienesController = new AdministradorBienesController();
        return $AdministradorBienesController->cargarUnidadMedida($params);
    }

    public function cargarMotivoBaja($params) {
        $AdministradorBienesController = new AdministradorBienesController();
        return $AdministradorBienesController->cargarMotivoBaja($params);
    }

    public function consultarInventarioEspecifico($params) {
        $AdministradorBienesController = new AdministradorBienesController();
        return $AdministradorBienesController->consultarInventarioEspecifico($params);
    }

    public function guardarInventarioDatos($params) {
        $AdministradorBienesController = new AdministradorBienesController();
        return $AdministradorBienesController->guardarInventarioDatos($params);
    }

    public function guardarAsignacionBienes($params) {
        $AdministradorBienesController = new AdministradorBienesController();
        return $AdministradorBienesController->guardarAsignacionBienes($params);
    }

    public function eliminarBien($params) {
        $AdministradorBienesController = new AdministradorBienesController();
        return $AdministradorBienesController->eliminarBien($params);
    }

    public function consultarBienesIntegrados($params) {
        $AdministradorBienesController = new AdministradorBienesController();
        return $AdministradorBienesController->consultarBienesIntegrados($params);
    }

    public function desintegrarBien($params) {
        $AdministradorBienesController = new AdministradorBienesController();
        return $AdministradorBienesController->desintegrarBien($params);
    }

    public function reincorporarBien($params) {
        $AdministradorBienesController = new AdministradorBienesController();
        return $AdministradorBienesController->reincorporarBien($params);
    }

    public function cargarRegiones($params) {
        $AdministradorBienesController = new AdministradorBienesController();
        return $AdministradorBienesController->cargarRegiones($params);
    }

    public function cargarMotivosBaja($params) {
        $AdministradorBienesController = new AdministradorBienesController();
        return $AdministradorBienesController->cargarMotivosBaja($params);
    }

    public function consultarTablaGenericInventariosReportes($params) {
        $AdministradorBienesController = new AdministradorBienesController();
        return $AdministradorBienesController->consultarTablaGenericInventariosReportes($params);
    }

    public function datatableConsultaBienes($params) {
        $AdministradorBienesController = new AdministradorBienesController();
        return $AdministradorBienesController->datatableConsultaBienes($params);
    }

    public function consultarTablaGenericInventariosReportesBienes($params) {
        $AdministradorBienesController = new AdministradorBienesController();
        return $AdministradorBienesController->consultarTablaGenericInventariosReportesBienes($params);
    }

    public function datatableConsultaBienesInventariados($params) {
        $AdministradorBienesController = new AdministradorBienesController();
        return $AdministradorBienesController->datatableConsultaBienesInventariados($params);
    }

    public function datatableConsultaBienesInventariadosReincorporacion($params) {
        $AdministradorBienesController = new AdministradorBienesController();
        return $AdministradorBienesController->datatableConsultaBienesInventariadosReincorporacion($params);
    }

    public function datatableConsultaResguardo($params) {
        $AdministradorBienesController = new AdministradorBienesController();
        return $AdministradorBienesController->datatableConsultaResguardo($params);
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
$AdministradorBienesFacade = new AdministradorBienesFacade();
$param = array("order" => $order, "search" => $search, "draw" => $draw, "limit" => $limit, "extras" => $extras, "extrasPost" => $extrasPOST);
$json = new Encode_JSON();
switch ($accion) {
    case "cargarClasificadorBienes":
        echo $json->encode($AdministradorBienesFacade->cargarClasificadorBienes($param));
        break;
    case "cargarGrupos":
        echo $json->encode($AdministradorBienesFacade->cargarGrupos($param));
        break;
    case "cargarGruposInmuebles":
        echo $json->encode($AdministradorBienesFacade->cargarGruposInmuebles($param));
        break;
    case "cargarSubGruposMueble":
        echo $json->encode($AdministradorBienesFacade->cargarSubGruposMueble($param));
        break;
    case "cargarSubGrupoInmueble":
        echo $json->encode($AdministradorBienesFacade->cargarSubGrupoInmueble($param));
        break;
    case "cargarClaseMueble":
        echo $json->encode($AdministradorBienesFacade->cargarClaseMueble($param));
        break;
    case "cargarClaseInmueble":
        echo $json->encode($AdministradorBienesFacade->cargarClaseInmueble($param));
        break;
    case "cargarSubClaseInmueble":
        echo $json->encode($AdministradorBienesFacade->cargarSubClaseInmueble($param));
        break;
    case "cargarCog":
        echo $json->encode($AdministradorBienesFacade->cargarCog($param));
        break;
    case "cargarBienes":
        echo $json->encode($AdministradorBienesFacade->cargarBienes($param));
        break;
    case "cargarColores":
        echo $json->encode($AdministradorBienesFacade->cargarColores($param));
        break;
    case "cargarMaterial":
        echo $json->encode($AdministradorBienesFacade->cargarMaterial($param));
        break;
    case "cargarFrecuenciaUso":
        echo $json->encode($AdministradorBienesFacade->cargarFrecuenciaUso($param));
        break;
    case "cargarUnidadMedida":
        echo $json->encode($AdministradorBienesFacade->cargarUnidadMedida($param));
        break;
    case "cargarMotivoBaja":
        echo $json->encode($AdministradorBienesFacade->cargarMotivoBaja($param));
        break;
    case "datatableConsultaBienes":
        echo ($AdministradorBienesFacade->datatableConsultaBienes($param));
        break;
    case "datatableConsultaBienesInventariados":
        echo ($AdministradorBienesFacade->datatableConsultaBienesInventariados($param));
        break;
    case "datatableConsultaBienesInventariadosReincorporacion":
        echo ($AdministradorBienesFacade->datatableConsultaBienesInventariadosReincorporacion($param));
        break;
    case "datatableConsultaResguardo":
        echo ($AdministradorBienesFacade->datatableConsultaResguardo($param));
        break;
    case "consultarInventarioEspecifico":
        echo $json->encode($AdministradorBienesFacade->consultarInventarioEspecifico($param));
        break;
    case "guardarInventarioDatos":
        echo $json->encode($AdministradorBienesFacade->guardarInventarioDatos($param));
        break;
    case "guardarAsignacionBienes":
        echo $json->encode($AdministradorBienesFacade->guardarAsignacionBienes($param));
        break;
    case "eliminarBien":
        echo $json->encode($AdministradorBienesFacade->eliminarBien($param));
        break;
    case "consultarBienesIntegrados":
        echo $json->encode($AdministradorBienesFacade->consultarBienesIntegrados($param));
        break;
    case "desintegrarBien":
        echo $json->encode($AdministradorBienesFacade->desintegrarBien($param));
        break;
    case "reincorporarBien":
        echo $json->encode($AdministradorBienesFacade->reincorporarBien($param));
        break;
    case "cargarRegiones":
        echo $AdministradorBienesFacade->cargarRegiones($param);
        break;
    case "cargarMotivosBaja":
        echo $json->encode($AdministradorBienesFacade->cargarMotivosBaja($param));
        break;
    case "consultarTablaGenericInventariosReportes":
        echo $json->encode($AdministradorBienesFacade->consultarTablaGenericInventariosReportes($param));
        break;
    case "consultarTablaGenericInventariosReportesBienes":
        echo ($AdministradorBienesFacade->consultarTablaGenericInventariosReportesBienes($param));
        break;

    default : echo '{"totalCount":0,"error":"No se recibieron parametros"}';
        break;
}
