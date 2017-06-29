<?php

ini_set('max_execution_time', 300); //300 seconds = 5 minutes

include_once(dirname(__FILE__) . "/AuronixController.Class.php");

// Funcion para conectarse a una Base de Datos
function Conectar() {
    $Conexion = mysql_connect("10.22.157.19", "sigejupe", "sigejupe_2016"); #prod
//    $Conexion = mysql_connect("10.22.157.48", "sigejupe", "sigejupe2015"); #pruebas
    mysql_select_db("htsj_sigejupe", $Conexion);
    return $Conexion;
}

//  Funcion que cierra una conexion
function Desconectar($Conexion) {
    mysql_close($Conexion);
}

class AuronixFacade {

    public function __construct() {
        
    }

    public function getConsultaAudiencias() {
        $conexion = Conectar();
        #OBTENEMOS FECHA ACTUAL
        $fecha = "";
        $sql = "SELECT now() as fecha";
        $result = mysql_query($sql);
        if (!mysql_error()) {
            if (mysql_num_rows($result) > 0) {
                while ($rowsFecha = mysql_fetch_array($result)) {
                    $fecha = $rowsFecha["fecha"];
                }
            }
        }

        $fechayhora = explode(' ', $fecha);  //Separamos la hora de la fecha
        $fecha = $fechayhora[0];                  //Obtenemos la fecha

        ##################################
//        $fecha = "2015-08-04";
        ##################################
        
        $auronixController = new AuronixController();
        $tmpArrayAudiencias = $auronixController->selectTodasAudienciasFechas($fecha, "http://187.176.14.101:8090/SASA");
        $tmpArrayAudiencias = $auronixController->selectTodasAudienciasFechas($fecha, "http://187.176.14.101:8090/SASA_Barrientos");

        Desconectar($conexion);
    }

    public function getEliminaTodasAudiencias() {
        $conexion = Conectar();
        #OBTENEMOS FECHA ACTUAL
        $fecha = "";
        $sql = "SELECT now() as fecha";
        $result = mysql_query($sql);
        if (!mysql_error()) {
            if (mysql_num_rows($result) > 0) {
                while ($rowsFecha = mysql_fetch_array($result)) {
                    $fecha = $rowsFecha["fecha"];
                }
            }
        }

        $fechayhora = explode(' ', $fecha);  //Separamos la hora de la fecha
        $fecha = $fechayhora[0];                  //Obtenemos la fecha

        ##################################
//        $fecha = "2015-08-04";
        ##################################
        
        $auronixController = new AuronixController();
        $tmpArrayAudiencias = $auronixController->eliminaTodoFechas($fecha, "http://187.176.14.101:8090/SASA");
        $tmpArrayAudiencias = $auronixController->eliminaTodoFechas($fecha, "http://187.176.14.101:8090/SASA_Barrientos");

        Desconectar($conexion);
    }

}

$AuronixFacade = new AuronixFacade();
$AuronixFacade->getConsultaAudiencias();
//$AuronixFacade->getEliminaTodasAudiencias();
?>
