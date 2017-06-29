<?php 
include_once("Fechas.Class.php");
include_once(dirname(__FILE__) . "/../logger/Logger.Class.php");

$logger = new Logger("/../../logs/", "TestFechas");
$logger->w_onError("**********COMIENZA EL TEST DE FECHAS**********");

$diasFestivos[] = array("fecha" => "2016-07-15", "Tipo" => "S");
$diasFestivos[] = array("fecha" => "2016-07-18", "Tipo" => "V");
$diasFestivos[] = array("fecha" => "2016-07-19", "Tipo" => "V");
$diasFestivos[] = array("fecha" => "2016-07-20", "Tipo" => "V");
$diasFestivos[] = array("fecha" => "2016-07-21", "Tipo" => "V");
$diasFestivos[] = array("fecha" => "2016-07-22", "Tipo" => "V");
$diasFestivos[] = array("fecha" => "2016-07-25", "Tipo" => "V");
$diasFestivos[] = array("fecha" => "2016-07-26", "Tipo" => "V");
$diasFestivos[] = array("fecha" => "2016-07-27", "Tipo" => "V");
$diasFestivos[] = array("fecha" => "2016-07-28", "Tipo" => "V");
$diasFestivos[] = array("fecha" => "2016-07-29", "Tipo" => "V");


$fechas = new Fechas($logger);
$numHoras = $fechas->avanzaDiaXHora("2016-06-29 10:00", 384, true, $diasFestivos, "N", 0);
$logger->w_onError("-->Numero de Horas: ".$numHoras);

?>
