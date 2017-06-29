<?php

session_start();
error_reporting(E_ALL);
ini_set('display_errors', 0);

$GBLstrAccion = $_POST["Accion"];
$FechaHoy = time();
$arrNomMes = array("", "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");

include_once(dirname(__FILE__) . "/../connect/Proveedor.Class.php");

$conBDTest = new Proveedor("mysql", "local");
$conBDTest->connect();

if ($conBDTest->isConnected()) {
    switch ($GBLstrAccion) {
        case "guardaTexto":
            $strComentario = utf8_decode(mysql_real_escape_string($_POST["Comentario"]));
            $intIdComentario = (int) $_POST["idComentario"];

            if ($intIdComentario > 0) {
                $strSQL = "UPDATE tblComentarios TC ";
                $strSQL .= "SET TC.Comentario='" . $strComentario . "' ";
                $strSQL .= "WHERE TC.idComentarios='" . $intIdComentario . "' ";
                $strSQL .= "LIMIT 1;";
            } else {
                $strSQL = "INSERT INTO tblComentarios (Comentario) ";
                $strSQL .= "VALUES ('" . $strComentario . "');";
            }
//                echo $strSQL;
            $conBDTest->execute($strSQL);
            if (!$conBDTest->error()) {
                echo "Guardado exitoso!";
            } else {
                echo "Ocurrio un error al comunicarse con la base de datos";
            }
            $conBDTest->stmt = $conBDTest->free_result($conBDTest->stmt);
            break;
        case "cargaTexto":
            $strSQL = "SELECT * ";
            $strSQL .= "FROM tblComentarios TC ";
            $strSQL .= "WHERE TC.idComentarios=1;";
            $conBDTest->execute($strSQL);
            if (!$conBDTest->error()) {
                if ($conBDTest->rows($conBDTest->stmt) > 0) {
                    $rsCentroM = mysql_fetch_object($conBDTest->stmt);
                    echo utf8_encode(htmlspecialchars_decode($rsCentroM->Comentario));
                } else {
                    echo "Error: no se encontraron registros";
                }
            } else {
                echo "Ocurrio un error al comunicarse con la base de datos";
            }
            $conBDTest->stmt = $conBDTest->free_result($conBDTest->stmt);
            break;
    }


    $conBDTest->close();
} else {
    $strMsgError = "Error en la Conexi&oacute;n[BDMediaci&oacute;n]!<br />Intentelo m&aacute;s tarde.";
    include('errorBD.php');
}
?>