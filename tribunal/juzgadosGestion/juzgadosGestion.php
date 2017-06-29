<?php

include_once (dirname(__FILE__) . "/../../webservice/cliente/juzgados/JuzgadoCliente.php");

 date_default_timezone_set("America/Mexico_City");
$dateToday = date("Ymd");

$dateYesterday = date('Ymd', strtotime('-1 day'));

$fileName = dirname(__FILE__) . "/../../archivos/juzgados" . $dateToday . ".json";
$fileNameAnt = dirname(__FILE__) . "/../../archivos/juzgados" . $dateYesterday . ".json";

//echo "file: " . $fileName;
if (!file_exists($fileName)) {
//    echo "No existe archivo: " .$fileName;	
    if (file_exists($fileNameAnt)) {
        unlink($fileNameAnt);
    }

  //  $file = fopen($fileName, "w");
    $file = fopen($fileName, "x");
    $JuzgadoCliente = new JuzgadoCliente();
    $JsonRsp = $JuzgadoCliente->getJuzgadoStatus("S");

    if ($JsonRsp != "") {
        $arrJuzgados = json_decode($JsonRsp);
        $totalCount = $arrJuzgados->totalCount;
        $json = "{";
        if ($totalCount > 0) {
            $json .= '"estatus":"ok",';
            $json .= '"totalCount":"' . $totalCount . '",';
            $json .= '"resultados":{';
            foreach ($arrJuzgados->data as $juzgado) {                
                $json .= json_encode($juzgado->idJuzgado) . ":";
                $json .= "{";
                $json .= '"idJuzgado":' . json_encode($juzgado->idJuzgado) . ",";
                $json .= '"cveLug":' . json_encode($juzgado->cveLug) . ",";
                $json .= '"cveAnterior":' . json_encode($juzgado->cveAnterior) . ",";
                $json .= '"desJuz":' . json_encode($juzgado->desJuz) . ",";
                $json .= '"cveMunicipio":' . json_encode($juzgado->cveMunicipio) . ",";
                $json .= '"cveDistrito":' . json_encode($juzgado->cveDistrito) . ",";
                $json .= '"cveIns":' . json_encode($juzgado->cveIns) . ",";
                $json .= '"cveRamo":' . json_encode($juzgado->cveRamo) . ",";
                $json .= '"cveRegion":' . json_encode($juzgado->cveRegion) . ",";
                $json .= '"cveAds":' . json_encode($juzgado->cveAds) . ",";
                $json .= '"activo":' . json_encode($juzgado->activo) . ",";
                $json .= '"telefono":' . json_encode($juzgado->telefono) . ",";
                $json .= '"cveFianzas":' . json_encode($juzgado->cveFianzas) . ",";
                $json .= '"cveOrganigrama":' . json_encode($juzgado->cveOrganigrama) . ",";
                $json .= '"tipoAdscripcion":' . json_encode($juzgado->tipoAdscripcion) . ",";
                $json .= '"nvaInstancia":' . json_encode($juzgado->nvaInstancia) . "";          
                $json .= '},';
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
  //  echo "Borra el archivo";	
    if (file_exists($fileNameAnt)) {
        unlink($fileNameAnt);
    }
}

print_r(error_get_last());
