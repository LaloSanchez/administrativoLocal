<?php

include_once(dirname(__FILE__) . "/appConfig.php");


if (!isset($_SERVER['PHP_AUTH_USER'])) {
    header('WWW-Authenticate: Basic realm=""');
    header('HTTP/1.0 401 Unauthorized');
    echo '<h1>HTTP/1.0 401 Authorization Required<h1>';
    exit;
} else {
    $user = $_SERVER['PHP_AUTH_USER'];
    $password = $_SERVER['PHP_AUTH_PW'];

    @$mysqli = new mysqli(host, $user, $password, "", port);

    if ($mysqli->connect_error) {
        die("Error en la conexion : " . $mysqli->connect_errno .
                "-" . $mysqli->connect_error);
        exit(0);
    }
    
    header('Location: appGen.php');
}
?>

