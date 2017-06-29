<?php

include_once (dirname(__FILE__) . "/../../webservice/cliente/adscripciones/AdscripcionesCliente.php");
 date_default_timezone_set("America/Mexico_City");
$dateToday = date("Ymd");
$dateYesterday = date('Ymd', strtotime('-1 day'));

$fileName = dirname(__FILE__) . "/../../archivos/adscripciones" . $dateToday . ".json";
$fileNameAnt = dirname(__FILE__) . "/../../archivos/adscripciones" . $dateYesterday . ".json";

if (!file_exists($fileName)) {

    if (file_exists($fileNameAnt)) {
        unlink($fileNameAnt);
    }

    $file = fopen($fileName, "w");
    $AdscripcionesCliente = new AdscripcionesCliente();
    $JsonRsp = $AdscripcionesCliente->getAdscripciones();

    if ($JsonRsp != "") {
        $arrJuzgados = json_decode($JsonRsp);
        $totalCount = $arrJuzgados->totalCount;
        $json = "{";
        if ($totalCount > 0) {
            $json .= '"estatus":"ok",';
            $json .= '"totalCount":"' . $totalCount . '",';
            $json .= '"resultados":{';
            foreach ($arrJuzgados->data as $juzgado) {
                $json .= json_encode($juzgado->cveAdscripcion) . ":";
                $json .= "{";
                $json .= '"cveAdscripcion":' . json_encode($juzgado->cveAdscripcion) . ",";
                $json .= '"cveEdificio":' . json_encode($juzgado->cveEdificio) . ",";
                $json .= '"cveRamo":' . json_encode($juzgado->cveRamo) . ",";
                $json .= '"cveInstancia":' . json_encode($juzgado->cveInstancia) . ",";
                $json .= '"cveOficialia":' . json_encode($juzgado->cveOficialia) . ",";
                $json .= '"cveMunicipio":' . json_encode($juzgado->cveMunicipio) . ",";
                $json .= '"cveDistrito":' . json_encode($juzgado->cveDistrito) . ",";
                $json .= '"cveRegion":' . json_encode($juzgado->cveRegion) . ",";
                $json .= '"titular":' . json_encode($juzgado->titular) . ",";
                $json .= '"nomAdscripcion":' . json_encode($juzgado->nomAdscripcion) . ",";
                $json .= '"telefono1":' . json_encode($juzgado->telefono1) . ",";
                $json .= '"telefono2":' . json_encode($juzgado->telefono2) . ",";
                $json .= '"email":' . json_encode($juzgado->email) . ",";
                $json .= '"tipoAdscripcion":' . json_encode($juzgado->tipoAdscripcion) . ",";
                $json .= '"desTipAds":' . json_encode($juzgado->desTipAds) . ",";
                $json .= '"activo":' . json_encode($juzgado->activo) . ",";
                $json .= '"proporcion":' . json_encode($juzgado->proporcion) . ",";
                $json .= '"numSecretarias":' . json_encode($juzgado->numSecretarias) . ",";
                $json .= '"ip":' . json_encode($juzgado->ip) . ",";
                $json .= '"cveAdscripcionAnt":' . json_encode($juzgado->cveAdscripcionAnt) . "";
                $json .= "},";                
            }
            $json = substr($json, 0, -1);
            $json .= '}';
        } else {

            $json .= '"estatus":"fail"';
            $json .= '"totalCount":"0"';
        }
        $json .= "}";
    }
    fwrite($file, $json);
    fclose($file);
} else {
    if (file_exists($fileNameAnt)) {
        unlink($fileNameAnt);
    }
}
?>
