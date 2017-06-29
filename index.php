<?php
error_reporting(E_ALL);
error_reporting(-1);

define('DS', "/");
define('ROOT', realpath(dirname(__FILE__)) . DS);
define('APP_PATH', ROOT . 'aplicacion' . DS);

include_once(trim(dirname(__FILE__) . "/aplicacion/configuracion.php"));
include_once(trim(dirname(__FILE__) . "/aplicacion/controller.php"));
include_once(trim(dirname(__FILE__) . "/aplicacion/facade.php"));
include_once(trim(dirname(__FILE__) . "/aplicacion/modelo.php"));
include_once(trim(dirname(__FILE__) . "/aplicacion/solicitud.php"));
include_once(trim(dirname(__FILE__) . "/aplicacion/view.php"));

date_default_timezone_set('America/Mexico_City');

if (DEFECTO_MODELO) {
    try {
        trim(Modelo::run());
    } catch (Exception $e) {
        echo $e->getMessage();
    }
}

if (DEFECTO_CONTROLLER) {
    try {
        Controller::run();
    } catch (Exception $e) {
        echo $e->getMessage();
    }
}

if (DEFECTO_FACADE) {
    try {
        Facade::run();
    } catch (Exception $e) {
        echo $e->getMessage();
    }
}

if (DEFECTO_VISTA) {
    try {
        View::run();
    } catch (Exception $e) {
        echo $e->getMessage();
    }
}

// $solicitud = new Solicitud();

// if ($solicitud->getFachada() == "index") {
    header('Location: vistas/inicio.php');
// } else {
//     $accion = $solicitud->getFachada() . "" ;
//     $argumentos = $solicitud->getArgumentos();
    
//     if (count($argumentos) > 0) {
//         for ($index = 0; $index < count($argumentos); $index++) {
//             $accion.="/" . $argumentos[$index];
//         }
//     }
    
//     if (file_exists($accion)) {
//         header('Location: ' . '/' . DEFECTO_CODE_BASE.'/' .$accion);
//     } else {
//         $generic = strrpos(strtoupper($accion), "VIEW");

//         if ((boolean) $generic !== false) {
//             header('Location: /' . DEFECTO_CODE_BASE . '/vistas/generic/frmGenericView.php?frm=' . $accion);
//         } else {
//             header("HTTP/1.0 404 Not Found");
//             header("Status: 404 Not Found");

//             echo "<html>";
//             echo "<head><title>Pagina no encontrada </title></head>";
//             echo "<body>";
//             echo "<img src=\"/" . DEFECTO_CODE_BASE . "/vistas/img/404.jpg\" />";
//             echo "</body>";
//             echo "</html>";
//         }
//     }
// }
?>
