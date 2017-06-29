<?php

include_once(dirname(__FILE__) . "/../../../aplicacion/configuracion.php");
include_once(dirname(__FILE__) . "/solicitud/Solicitud.Class.php");

header("Access-Control-Allow-Orgin: *");
header("Access-Control-Allow-Methods: *");
header('WWW-Authenticate: Basic realm=""');
header('HTTP/1.0 401 Unauthorized');

$solicitud = new Solicitud($_SERVER);
if (isset($_GET['url'])) {
    $url = explode('/', trim($_GET['url']));
    $url = array_filter($url);
    $metodo = strtolower(array_shift($url));
    $argumentos = $url;
    $func = $metodo;

    if (file_exists(dirname(__FILE__) . "/servicio/" . $metodo . "/" . ucwords($metodo) . ".Class.php")) {
        $_POST = array_merge($_GET, array("frm" => $metodo));
        $_POST = array_merge($_POST, array("accion" => $solicitud->_accion));
        $_POST = array_merge($_POST, $solicitud->_peticion);
        include_once(dirname(__FILE__) . "/servicio/" . $metodo . "/" . ucwords($metodo) . ".Class.php");
    } else {
        $_POST = array_merge($_GET, array("frm" => $metodo));
        $_POST = array_merge($_POST, array("accion" => $solicitud->_accion));
        $_POST = array_merge($_POST, $solicitud->_peticion);

        if($solicitud->_estado==200){
            $solicitud->mostrar($solicitud->_estado);
            dirname(__FILE__) . "/../../../fachadas/generic/GenericFachada.Class.php";
            include_once(dirname(__FILE__) . "/../../../fachadas/generic/GenericFachada.Class.php");
        }else{
           $solicitud->mostrar($solicitud->_estado);
           echo json_encode(array("status"=>"error","code"=>$solicitud->_estado,"msg"=>$solicitud->_msg));
        }
    }

}
