<?php
error_reporting(E_ALL);
error_reporting(-1);
include_once(dirname(__FILE__) . "/../../../aplicacion/configuracion.php");
include_once(dirname(__FILE__) . "/solicitud/Solicitud.Class.php");


define('DS', "/");
define('ROOT', realpath(dirname(__FILE__)) . DS);
define('APP_PATH', ROOT . 'aplicacion' . DS);

$solicitud = new Solicitud();

$url = explode('/', trim($_GET['url']));
$url = array_filter($url);

strtolower(array_shift($url));
$metodo = strtolower(array_shift($url));
$file = array_shift($url);

if (file_exists(dirname(__FILE__) . "/servicio/" . $metodo . "/" . $file . "")) {
    //include dirname(__FILE__) . "/servicio/".$metodo."/".$file.""  
    //No hacemos nada aqui
} else {
//    $_POST = array("frm" => $metodo);
    $_POST = array_merge($_GET, array("frm" => $metodo));
    include_once(dirname(__FILE__) . "/servicio/generic/GenericFachada.Class.php");
    $rutaWsdl =  GenericFachada::generaWsdl($metodo);
    $rutaServicio =  GenericFachada::creaDirectorio(dirname(__FILE__) . "/../../servicio/$metodo/");
    $ruta =  GenericFachada::generaServicio($metodo,$rutaWsdl);
    header('Location: /' . DEFECTO_CODE_BASE . '/webservice/servidor/soap/'.$_GET['url']."?wsdl");
}
?>
