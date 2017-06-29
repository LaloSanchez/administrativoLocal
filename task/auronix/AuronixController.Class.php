<?php

error_reporting(0);

class AuronixController {

    private $ulrAuronix;

    public function __construct() {
        
    }

    public function getDetalleAudiencia($IdAudiencia, $url) {
        $this->urlAuronix = $url;
        #obtiene información de:
        #audiencia registrada#juzgado#tipo de audiencia#jueces#ofendidos#defensor ofendido#imputado#defensor imputado#delitos
        $arrayDetalleAudiencias = Array();
        $arrayReturn = array();
        $arrayAudiencias = Array();
        $sql = "select A.idAudiencia,A.idSolicitudAudiencia,";
        $sql .= "A.fechaInicial,A.fechaFinal,A.idAudienciaAuronix,";
        $sql .= "A.cveJuzgadoDesahoga,J.desJuzgado, JA.descJuzgadoAuronix,";
        $sql .= "A.cveSala,S.desSala,";
        $sql .= "A.cveCatAudiencia, CA.desCatAudiencia ";
        $sql .= "from tblaudiencias A, tbljuzgados J, tblsalas S, tblcataudiencias CA, tbljuzgadosauronix as JA ";
        $sql .= "where A.idAudiencia = " . $IdAudiencia . " ";
        $sql .= "and A.cveJuzgadoDesahoga = J.cveJuzgado ";
        $sql .= "and A.cveSala = S.cveSala  ";
        $sql .= "and A.cveCatAudiencia = CA.cveCatAudiencia  ";
        $sql .= "and J.CveJuzgado = JA.cveJuzgadoDepende ";

        $result = mysql_query($sql);
        if (!mysql_error()) {
            if (mysql_num_rows($result) > 0) {
                $index = 0;
                while ($rowsAudiencias = mysql_fetch_array($result)) {
                    $arrayAudiencias[$index]["idAudiencia"] = utf8_encode($rowsAudiencias["idAudiencia"]);
                    $arrayAudiencias[$index]["idSolicitudAudiencia"] = utf8_encode($rowsAudiencias["idSolicitudAudiencia"]);
                    $arrayAudiencias[$index]["fechaInicial"] = utf8_encode($rowsAudiencias["fechaInicial"]);
                    $arrayAudiencias[$index]["fechaFinal"] = utf8_encode($rowsAudiencias["fechaFinal"]);
                    $arrayAudiencias[$index]["idAudienciaAuronix"] = utf8_encode($rowsAudiencias["idAudienciaAuronix"]);
                    $arrayAudiencias[$index]["cveJuzgadoDesahoga"] = utf8_encode($rowsAudiencias["cveJuzgadoDesahoga"]);
                    $arrayAudiencias[$index]["desJuzgado"] = utf8_encode($rowsAudiencias["desJuzgado"]);
                    $arrayAudiencias[$index]["descJuzgadoAuronix"] = utf8_encode($rowsAudiencias["descJuzgadoAuronix"]);
                    $arrayAudiencias[$index]["cveSala"] = utf8_encode($rowsAudiencias["cveSala"]);
                    $arrayAudiencias[$index]["desSala"] = utf8_encode($rowsAudiencias["desSala"]);
                    $arrayAudiencias[$index]["cveCatAudiencia"] = utf8_encode($rowsAudiencias["cveCatAudiencia"]);
                    $arrayAudiencias[$index]["desCatAudiencia"] = utf8_encode($rowsAudiencias["desCatAudiencia"]);
                    $index++;
                }
            }
        }

        if ($arrayAudiencias != "" && count($arrayAudiencias) > 0) {
            foreach ($arrayAudiencias as $arrayAudiencia) {
                $arrayJueces = array();
                $arraySolicitud = array();
                $arrayOfendidos = array();
                $arrayDefensoresOfendidos = array();
                $arrayImputados = array();
                $arrayDefensoresImputados = array();
                $arrayDelitos = array();

                $sql = "select AJ.idAudienciaJuez, AJ.idAudiencia, AJ.idJuzgador, J.cveTipoJuzgador, J.numEMpleado, J.nombre, J.paterno, J.materno ";
                $sql .= "from tblaudienciasjuzgador as AJ, tbljuzgadores as J ";
                $sql .= "where AJ.idAudiencia = " . $arrayAudiencia["idAudiencia"] . " ";
                $sql .= "and  AJ.idJuzgador =  J.idJuzgador ";

                $result = mysql_query($sql);
                if (!mysql_error()) {
                    if (mysql_num_rows($result) > 0) {
                        $index = 0;
                        while ($rowsJueces = mysql_fetch_array($result)) {
                            $arrayJueces[$index]["idAudienciaJuez"] = utf8_encode($rowsJueces["idAudienciaJuez"]);
                            $arrayJueces[$index]["idAudiencia"] = utf8_encode($rowsJueces["idAudiencia"]);
                            $arrayJueces[$index]["idJuzgador"] = utf8_encode($rowsJueces["idJuzgador"]);
                            $arrayJueces[$index]["cveTipoJuzgador"] = utf8_encode($rowsJueces["cveTipoJuzgador"]);
                            $arrayJueces[$index]["numEMpleado"] = utf8_encode($rowsJueces["numEMpleado"]);
                            $arrayJueces[$index]["nombre"] = utf8_encode($rowsJueces["nombre"]);
                            $arrayJueces[$index]["paterno"] = utf8_encode($rowsJueces["paterno"]);
                            $arrayJueces[$index]["materno"] = utf8_encode($rowsJueces["materno"]);
                            $index++;
                        }
                    }
                }

                $sql = "select idSolicitudAudiencia,cveTipoCarpeta,numero,anio,carpetaInv,nuc,cveNaturaleza ";
                $sql .= "from tblsolicitudesaudiencias ";
                $sql .= "where idSolicitudAudiencia = " . $arrayAudiencia["idSolicitudAudiencia"] . " ";
                $result = mysql_query($sql);
                if (!mysql_error()) {
                    if (mysql_num_rows($result) > 0) {
                        while ($rowsSolicitud = mysql_fetch_array($result)) {
                            $arraySolicitud["idSolicitudAudiencia"] = utf8_encode($rowsSolicitud["idSolicitudAudiencia"]);
                            $arraySolicitud["cveTipoCarpeta"] = utf8_encode($rowsSolicitud["cveTipoCarpeta"]);
                            $arraySolicitud["numero"] = utf8_encode($rowsSolicitud["numero"]);
                            $arraySolicitud["anio"] = utf8_encode($rowsSolicitud["anio"]);
                            $arraySolicitud["carpetaInv"] = utf8_encode($rowsSolicitud["carpetaInv"]);
                            $arraySolicitud["nuc"] = utf8_encode($rowsSolicitud["nuc"]);
                            $arraySolicitud["cveNaturaleza"] = utf8_encode($rowsSolicitud["cveNaturaleza"]);
                        }
                    }
                }

                $sql = "Select idOfendidoSolicitud,idSolicitudAudiencia,nombre,paterno,materno,cveTipoPersona,nombreMoral ";
                $sql .= "from tblofendidossolicitudes ";
                $sql .= "where idSolicitudAudiencia = " . $arrayAudiencia["idSolicitudAudiencia"] . " ";
                $result = mysql_query($sql);
                if (!mysql_error()) {
                    if (mysql_num_rows($result) > 0) {
                        $index = 0;
                        while ($rowsOfendidos = mysql_fetch_array($result)) {
                            $arrayOfendidos[$index]["idOfendidoSolicitud"] = utf8_encode($rowsOfendidos["idOfendidoSolicitud"]);
                            $arrayOfendidos[$index]["idSolicitudAudiencia"] = utf8_encode($rowsOfendidos["idSolicitudAudiencia"]);
                            $arrayOfendidos[$index]["nombre"] = utf8_encode($rowsOfendidos["nombre"]);
                            $arrayOfendidos[$index]["paterno"] = utf8_encode($rowsOfendidos["paterno"]);
                            $arrayOfendidos[$index]["materno"] = utf8_encode($rowsOfendidos["materno"]);
                            $arrayOfendidos[$index]["cveTipoPersona"] = utf8_encode($rowsOfendidos["cveTipoPersona"]);
                            $arrayOfendidos[$index]["nombreMoral"] = utf8_encode($rowsOfendidos["nombreMoral"]);
                            $index++;
                        }
                    }
                }

                if ($arrayOfendidos != "" && count($arrayOfendidos) > 0) {
                    $index = 0;
                    foreach ($arrayOfendidos as $arrayOfendido) {
                        $sql = "Select idDefensorOfendidoSolicitud,idOfendidoSolicitud,nombre ";
                        $sql .= "from tbldefensoresofendidossolicitudes ";
                        $sql .= "where idOfendidoSolicitud = " . $arrayOfendido["idOfendidoSolicitud"] . " ";
                        $result = mysql_query($sql);
                        if (!mysql_error()) {
                            if (mysql_num_rows($result) > 0) {
                                while ($rowsDefensoresOfendidos = mysql_fetch_array($result)) {
                                    $arrayDefensoresOfendidos[$index]["idDefensorOfendidoSolicitud"] = utf8_encode($rowsDefensoresOfendidos["idDefensorOfendidoSolicitud"]);
                                    $arrayDefensoresOfendidos[$index]["idOfendidoSolicitud"] = utf8_encode($rowsDefensoresOfendidos["idOfendidoSolicitud"]);
                                    $arrayDefensoresOfendidos[$index]["nombre"] = utf8_encode($rowsDefensoresOfendidos["nombre"]);
                                    $index++;
                                }
                            }
                        }
                    }
                }

                $sql = "Select idImputadoSolicitud,idSolicitudAudiencia,nombre,paterno,materno,cveTipoPersona,nombreMoral ";
                $sql .= "from tblimputadossolicitudes ";
                $sql .= "where idSolicitudAudiencia = " . $arrayAudiencia["idSolicitudAudiencia"] . " ";
                $result = mysql_query($sql);
                if (!mysql_error()) {
                    if (mysql_num_rows($result) > 0) {
                        $index = 0;
                        while ($rowsImputados = mysql_fetch_array($result)) {
                            $arrayImputados[$index]["idImputadoSolicitud"] = utf8_encode($rowsImputados["idImputadoSolicitud"]);
                            $arrayImputados[$index]["idSolicitudAudiencia"] = utf8_encode($rowsImputados["idSolicitudAudiencia"]);
                            $arrayImputados[$index]["nombre"] = utf8_encode($rowsImputados["nombre"]);
                            $arrayImputados[$index]["paterno"] = utf8_encode($rowsImputados["paterno"]);
                            $arrayImputados[$index]["materno"] = utf8_encode($rowsImputados["materno"]);
                            $arrayImputados[$index]["cveTipoPersona"] = utf8_encode($rowsImputados["cveTipoPersona"]);
                            $arrayImputados[$index]["nombreMoral"] = utf8_encode($rowsImputados["nombreMoral"]);
                            $index++;
                        }
                    }
                }

                if ($arrayImputados != "" && count($arrayImputados) > 0) {
                    $index = 0;
                    foreach ($arrayImputados as $arrayImputado) {
                        $sql = "Select idDefensorImputadoSolicitud,idImputadoSolicitud,nombre ";
                        $sql .= "from tbldefensoresimputadossolicitudes ";
                        $sql .= "where idImputadoSolicitud = " . $arrayImputado["idImputadoSolicitud"] . " ";
                        $result = mysql_query($sql);
                        if (!mysql_error()) {
                            if (mysql_num_rows($result) > 0) {
                                while ($rowsDefensoresImputados = mysql_fetch_array($result)) {
                                    $arrayDefensoresImputados[$index]["idDefensorImputadoSolicitud"] = utf8_encode($rowsDefensoresImputados["idDefensorImputadoSolicitud"]);
                                    $arrayDefensoresImputados[$index]["idImputadoSolicitud"] = utf8_encode($rowsDefensoresImputados["idImputadoSolicitud"]);
                                    $arrayDefensoresImputados[$index]["nombre"] = utf8_encode($rowsDefensoresImputados["nombre"]);
                                    $index++;
                                }
                            }
                        }
                    }
                }

                $sql = "Select DS.idDelitoSolicitud, DS.cveDelito, DS.idSolicitudAudiencia, D.desDelito ";
                $sql .= "from tbldelitossolicitudes as DS, tbldelitos as D ";
                $sql .= "where DS.idSolicitudAudiencia = " . $arrayAudiencia["idSolicitudAudiencia"] . " ";
                $sql .= "and DS.CveDelito = d.CveDelito ";
                $result = mysql_query($sql);
                if (!mysql_error()) {
                    if (mysql_num_rows($result) > 0) {
                        $index = 0;
                        while ($rowsDelitos = mysql_fetch_array($result)) {
                            $arrayDelitos[$index]["idDelitoSolicitud"] = utf8_encode($rowsDelitos["idDelitoSolicitud"]);
                            $arrayDelitos[$index]["cveDelito"] = utf8_encode($rowsDelitos["cveDelito"]);
                            $arrayDelitos[$index]["idSolicitudAudiencia"] = utf8_encode($rowsDelitos["idSolicitudAudiencia"]);
                            $arrayDelitos[$index]["desDelito"] = utf8_encode($rowsDelitos["desDelito"]);
                            $index++;
                        }
                    }
                }

                $arrayDetalleAudiencias["audiencia"] = $arrayAudiencia;
                $arrayDetalleAudiencias["solicitud"] = $arraySolicitud;
                $arrayDetalleAudiencias["jueces"] = $arrayJueces;
                $arrayDetalleAudiencias["ofendidos"] = $arrayOfendidos;
                $arrayDetalleAudiencias["ofendidosDefensor"] = $arrayDefensoresOfendidos;
                $arrayDetalleAudiencias["imputados"] = $arrayImputados;
                $arrayDetalleAudiencias["imputadosDefensor"] = $arrayDefensoresImputados;
                $arrayDetalleAudiencias["delitos"] = $arrayDelitos;

                if ($arrayAudiencia["idAudienciaAuronix"] === "" || $arrayAudiencia["idAudienciaAuronix"] === "0") {
                    echo "ENTRA A INSERTAR";
                    echo "<br>";
                    $arrayReturn = $this->insertAudiencia($arrayDetalleAudiencias);
                    echo "*******<br>";
                } else {
                    echo "++++++ENTRA A ACTUALIZAR";
                    echo "<br>";
                    $arrayReturn = $this->updateAudiencia($arrayDetalleAudiencias);
                }
            }
        }
        return $arrayReturn;
    }

    public function selectTodasAudiencias() {
        $ch = curl_init();
//        curl_setopt($ch, CURLOPT_URL, "http://187.176.14.101:8090/SASA/index.php?r=api/hearings");
        curl_setopt($ch, CURLOPT_URL, $this->urlAuronix . "/index.php?r=api/hearings");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERPWD, "sigejupe:BsKfxi2REvKS");
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        $output = curl_exec($ch);
        $output = json_decode($output, true);
        curl_close($ch);
        return $output;
    }

    public function selectTodasAudienciasFechas($fecha, $juzgado = null) {
        $sql = "SELECT UNIX_TIMESTAMP('" . $fecha . " 00:00:00') as FechaAudienciasInicio";
        $result = mysql_query($sql);
        if (!mysql_error()) {
            if (mysql_num_rows($result) > 0) {
                while ($rowsFecha = mysql_fetch_array($result)) {
                    $fechaInicio = $rowsFecha["FechaAudienciasInicio"];
                }
            }
        }

        $sql = "SELECT UNIX_TIMESTAMP('" . $fecha . " 23:59:59') as FechaAudienciasFin";
        $result = mysql_query($sql);
        if (!mysql_error()) {
            if (mysql_num_rows($result) > 0) {
                while ($rowsFecha = mysql_fetch_array($result)) {
                    $fechaFinal = $rowsFecha["FechaAudienciasFin"];
                }
            }
        }
        if ($juzgado != null) {
            $this->urlAuronix = $juzgado;
        }
        echo "<br>FECHAS DE CONSULTA: starttime=" . $fechaInicio . "&endtime=" . $fechaFinal . "<br>";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->urlAuronix . "/index.php?r=api/hearings&starttime=" . $fechaInicio . "&endtime=" . $fechaFinal);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERPWD, "sigejupe:BsKfxi2REvKS");
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        $output = curl_exec($ch);
        echo $output;
        $output = json_decode($output, true);
//        print_r($output);
        curl_close($ch);
        return $output;
    }

    public function selectDetalleAudiencia($idAudienciaAuronix) {
        $ch = curl_init();
        /*        curl_setopt($ch, CURLOPT_URL, "http://187.176.14.101:8090/SASA/index.php?r=api/hearing&id=" . $idAudienciaAuronix); */
        curl_setopt($ch, CURLOPT_URL, $this->urlAuronix . "/index.php?r=api/hearing&id=" . $idAudienciaAuronix);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERPWD, "sigejupe:BsKfxi2REvKS");
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
        $output = curl_exec($ch);
        curl_close($ch);
        print_r($output);
    }

    public function insertAudiencia($arrayDetalleAudiencias) {
        $paramsReturn = array();
        $paramsReturn["idAudiencia"] = "";
        $paramsReturn["idAuronix"] = "";
        $paramsBitacora = array();
        $paramsBitacora["descAccion"] = "1"; #INSERTA
        $paramsBitacora["idAudiencia"] = $arrayDetalleAudiencias["audiencia"]["idAudiencia"];
        $paramsBitacora["idAuronix"] = "";
        $paramsBitacora["descEnvio"] = "";
        $paramsBitacora["descRespuesta"] = "";
        $paramsBitacora["fechaRegistro"] = "";

        $sql = "SELECT UNIX_TIMESTAMP('" . $arrayDetalleAudiencias["audiencia"]["fechaInicial"] . "') as fechaInicial";
        $result = mysql_query($sql);
        if (!mysql_error()) {
            if (mysql_num_rows($result) > 0) {
                while ($rowsFecha = mysql_fetch_array($result)) {
                    $arrayDetalleAudiencias["audiencia"]["fechaInicial"] = $rowsFecha["fechaInicial"];
                }
            }
        }

        $sql = "SELECT UNIX_TIMESTAMP('" . $arrayDetalleAudiencias["audiencia"]["fechaFinal"] . "') as fechaFinal";
        $result = mysql_query($sql);
        if (!mysql_error()) {
            if (mysql_num_rows($result) > 0) {
                while ($rowsFecha = mysql_fetch_array($result)) {
                    $arrayDetalleAudiencias["audiencia"]["fechaFinal"] = $rowsFecha["fechaFinal"];
                }
            }
        }

        $sql = "SELECT NOW() as FechaRegistro";
        $result = mysql_query($sql);
        if (!mysql_error()) {
            if (mysql_num_rows($result) > 0) {
                while ($rowsFecha = mysql_fetch_array($result)) {
                    $paramsBitacora["fechaRegistro"] = $rowsFecha["FechaRegistro"];
                }
            }
        }

        $tipoCausa = "";
        switch ($arrayDetalleAudiencias["solicitud"]["cveTipoCarpeta"]) {
            case 1:
                $tipoCausa = "AUXILIAR";
                break;
            case 2:
                $tipoCausa = "CONTROL";
                break;
            case 3:
                $tipoCausa = "JUICIO";
                break;
            case 4:
                $tipoCausa = "TRIBUNAL";
                break;
            case 5:
                $tipoCausa = "EXPEDIENTE";
                break;
            default:
                $tipoCausa = "CAUSA";
        }

        if ($arrayDetalleAudiencias["solicitud"]["cveNaturaleza"] == "1") { #si es publica cambia a cero para auronix
            $arrayDetalleAudiencias["solicitud"]["cveNaturaleza"] = "0";
        } else { //si es privada cambia a uno para auronix
            $arrayDetalleAudiencias["solicitud"]["cveNaturaleza"] = "1";
        }

        $requestString = "hearing[case]=" . $arrayDetalleAudiencias["solicitud"]["numero"] . "/" . $arrayDetalleAudiencias["solicitud"]["anio"] . "/" . $tipoCausa . "&";
        $requestString .= "hearing[starttime]=" . $arrayDetalleAudiencias["audiencia"]["fechaInicial"] . "&";
        $requestString .= "hearing[endtime]=" . $arrayDetalleAudiencias["audiencia"]["fechaFinal"] . "&";
        $requestString .= "hearing[court]=" . $arrayDetalleAudiencias["audiencia"]["descJuzgadoAuronix"] . "&";
        $requestString .= "hearing[room]=" . $arrayDetalleAudiencias["audiencia"]["desSala"] . "&";
        $requestString .= "hearing[hearing_type]=" . $arrayDetalleAudiencias["audiencia"]["desCatAudiencia"] . "&";
        $requestString .= "hearing[trial_type]=Penal&";
        $requestString .= "hearing[NUC]=" . $arrayDetalleAudiencias["solicitud"]["nuc"] . "&";
        $requestString .= "hearing[private]=" . $arrayDetalleAudiencias["solicitud"]["cveNaturaleza"] . "&";

        if ($arrayDetalleAudiencias["jueces"] != "" && count($arrayDetalleAudiencias["jueces"]) > 0) {
            $stringJuzgadores = "";
            foreach ($arrayDetalleAudiencias["jueces"] as $juzgadores) {
                $stringJuzgadores .= $juzgadores["nombre"] . " " . $juzgadores["paterno"] . " " . $juzgadores["materno"] . ",";
            }
            $requestString .= "hearing[judge][]=" . substr($stringJuzgadores, 0, -1) . "&";
        }

        if ($arrayDetalleAudiencias["solicitud"]["cveNaturaleza"] == "0") { //si es publica envia  datos de ofendidos, victimas y defensores
            if ($arrayDetalleAudiencias["ofendidos"] != "" && count($arrayDetalleAudiencias["ofendidos"]) > 0) {
                $stringOfendidos = "";
                foreach ($arrayDetalleAudiencias["ofendidos"] as $ofendidos) {
                    if ($ofendidos["cveTipoPersona"] == "1") {
                        $stringOfendidos .= $ofendidos["nombre"] . " " . $ofendidos["paterno"] . " " . $ofendidos["materno"] . ",";
                    } else {
                        $stringOfendidos .= $ofendidos["nombreMoral"] . ",";
                    }
                }
                $requestString .= "hearing[plaintiff][]=" . substr($stringOfendidos, 0, -1) . "&";
            }

            if ($arrayDetalleAudiencias["ofendidosDefensor"] != "" && count($arrayDetalleAudiencias["ofendidosDefensor"]) > 0) {
                $stringOfendidosDefensores = "";
                foreach ($arrayDetalleAudiencias["ofendidosDefensor"] as $ofendidosDefensores) {
                    $stringOfendidosDefensores .= $ofendidosDefensores["nombre"] . ",";
                }
                $requestString .= "hearing[plaintiffAdvocate][]=" . substr($stringOfendidosDefensores, 0, -1) . "&";
            }

            if ($arrayDetalleAudiencias["imputados"] != "" && count($arrayDetalleAudiencias["imputados"]) > 0) {
                $stringImputados = "";
                foreach ($arrayDetalleAudiencias["imputados"] as $imputados) {
                    if ($imputados["cveTipoPersona"] == "1") {
                        $stringImputados .= $imputados["nombre"] . " " . $imputados["paterno"] . " " . $imputados["materno"] . ",";
                    } else {
                        $stringImputados .= $imputados["nombreMoral"] . ",";
                    }
                }
                $requestString .= "hearing[defendant][]=" . substr($stringImputados, 0, -1) . "&";
            }

            if ($arrayDetalleAudiencias["imputadosDefensor"] != "" && count($arrayDetalleAudiencias["imputadosDefensor"]) > 0) {
                $stringImputadosDefensores = "";
                foreach ($arrayDetalleAudiencias["imputadosDefensor"] as $imputadosDefensores) {
                    $stringImputadosDefensores .= $imputadosDefensores["nombre"] . ",";
                }
                $requestString .= "hearing[defendantAdvocate][]=" . substr($stringImputadosDefensores, 0, -1) . "&";
            }

            if ($arrayDetalleAudiencias["delitos"] != "" && count($arrayDetalleAudiencias["delitos"]) > 0) {
                $stringDelitos = "";
                foreach ($arrayDetalleAudiencias["delitos"] as $delitos) {
                    $stringDelitos .= $delitos["desDelito"] . ",";
                }
                $requestString .= "hearing[offense]=" . substr($stringDelitos, 0, -1) . "&";
            }
        }

        $requestString = substr($requestString, 0, -1);
        echo "URL DEL CURL:" . $this->urlAuronix . "<br>";
        echo "DATOS ENVIADOS:" . $requestString . "<br>";
        $paramsBitacora["descEnvio"] = $requestString;
        $output = "";
        try {
            $ch = curl_init();
//            curl_setopt($ch, CURLOPT_URL, "http://187.176.14.101:8090/SASA/index.php?r=api/hearings");
            curl_setopt($ch, CURLOPT_URL, $this->urlAuronix . "/index.php?r=api/hearings");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_USERPWD, "sigejupe:BsKfxi2REvKS");
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $requestString);
            $output = curl_exec($ch);
            curl_close($ch);
            $paramsBitacora["descRespuesta"] = $output;
            $output = json_decode($output, true);
        } catch (Exception $e) {
            $output = "";
            echo "ERROR EN TRY:" . $e;
        }
        echo "RESPUESTA CURL:";
        print_r($output);
        if ($output != "") {
            if (array_key_exists('errors', $output)) {
                //echo "con errores";
                echo "ERRORES:";
                print_r($output["errors"]);
                echo "<br>hearing[court]=" . $arrayDetalleAudiencias["audiencia"]["DesJuzgado"] . "&"; #quitar en produccion
                echo "<br>hearing[room]=" . $arrayDetalleAudiencias["audiencia"]["DesSala"] . "&"; #quitar en produccion
            } else {
                if (array_key_exists('hearing', $output)) {
                    if (array_key_exists('id', $output["hearing"])) {
                        $paramsBitacora["idAuronix"] = $output["hearing"]["id"];
                        $sql = "UPDATE tblaudiencias Set idAudienciaAuronix='" . $output["hearing"]["id"] . "' Where idAudiencia='" . $arrayDetalleAudiencias["audiencia"]["idAudiencia"] . "'";
                        mysql_query($sql);
                        if (!mysql_error()) {
                            $paramsReturn["idAudiencia"] = $arrayDetalleAudiencias["audiencia"]["idAudiencia"];
                            $paramsReturn["idAuronix"] = $output["hearing"]["id"];
                            echo "<br>";
                            echo "INSERTO CORRECTAMENTE LA INFORMACIÓN DE LA AUDIENCIA";
                        } else {
                            echo "ERROR AL ACTUALIZAR AUDIENCIA";
                        }
                    } else {
                        echo "NO TRAJO EL ID DE AUDIENCIAAURONIX";
                    }
                } else {
                    echo "NO TRAJO HEARING";
                }
            }
        } else {
            echo "SIN RESPUESTA DEL CURL";
        }
        $this->insetBitacoraAuronix($paramsBitacora);
        return $paramsReturn;
    }

    public function updateAudiencia($arrayDetalleAudiencias) {
        $paramsReturn = array();
        $paramsReturn["idAudiencia"] = "";
        $paramsReturn["idAuronix"] = "";
        $paramsBitacora = array();
        $paramsBitacora["descAccion"] = "2"; #ACTUALIZA
        $paramsBitacora["idAudiencia"] = $arrayDetalleAudiencias["audiencia"]["idAudiencia"];
        $paramsBitacora["idAuronix"] = $arrayDetalleAudiencias["audiencia"]["idAudienciaAuronix"];
        $paramsBitacora["descEnvio"] = "";
        $paramsBitacora["descRespuesta"] = "";
        $paramsBitacora["fechaRegistro"] = "";


        $sql = "SELECT UNIX_TIMESTAMP('" . $arrayDetalleAudiencias["audiencia"]["fechaInicial"] . "') as fechaInicial";
        $result = mysql_query($sql);
        if (!mysql_error()) {
            if (mysql_num_rows($result) > 0) {
                while ($rowsFecha = mysql_fetch_array($result)) {
                    $arrayDetalleAudiencias["audiencia"]["fechaInicial"] = $rowsFecha["fechaInicial"];
                }
            }
        }

        $sql = "SELECT UNIX_TIMESTAMP('" . $arrayDetalleAudiencias["audiencia"]["fechaFinal"] . "') as fechaFinal";
        $result = mysql_query($sql);
        if (!mysql_error()) {
            if (mysql_num_rows($result) > 0) {
                while ($rowsFecha = mysql_fetch_array($result)) {
                    $arrayDetalleAudiencias["audiencia"]["fechaFinal"] = $rowsFecha["fechaFinal"];
                }
            }
        }

        $sql = "SELECT NOW() as FechaRegistro";
        $result = mysql_query($sql);
        if (!mysql_error()) {
            if (mysql_num_rows($result) > 0) {
                while ($rowsFecha = mysql_fetch_array($result)) {
                    $paramsBitacora["fechaRegistro"] = $rowsFecha["FechaRegistro"];
                }
            }
        }

        $tipoCausa = "";
        switch ($arrayDetalleAudiencias["solicitud"]["cveTipoCarpeta"]) {
            case 1:
                $tipoCausa = "AUXILIAR";
                break;
            case 2:
                $tipoCausa = "CONTROL";
                break;
            case 3:
                $tipoCausa = "JUICIO";
                break;
            case 4:
                $tipoCausa = "TRIBUNAL";
                break;
            case 5:
                $tipoCausa = "EXPEDIENTE";
                break;
            default:
                $tipoCausa = "CAUSA";
        }

        if ($arrayDetalleAudiencias["solicitud"]["cveNaturaleza"] == "1") { #si es publica cambia a cero para auronix
            $arrayDetalleAudiencias["solicitud"]["cveNaturaleza"] = "0";
        } else { //si es privada cambia a uno para auronix
            $arrayDetalleAudiencias["solicitud"]["cveNaturaleza"] = "1";
        }

        $requestString = "hearing[case]=" . $arrayDetalleAudiencias["solicitud"]["numero"] . "/" . $arrayDetalleAudiencias["solicitud"]["anio"] . "/" . $tipoCausa . "&";
        $requestString .= "hearing[starttime]=" . $arrayDetalleAudiencias["audiencia"]["fechaInicial"] . "&";
        $requestString .= "hearing[endtime]=" . $arrayDetalleAudiencias["audiencia"]["fechaFinal"] . "&";
        $requestString .= "hearing[court]=" . $arrayDetalleAudiencias["audiencia"]["descJuzgadoAuronix"] . "&";
        $requestString .= "hearing[room]=" . $arrayDetalleAudiencias["audiencia"]["desSala"] . "&";
        $requestString .= "hearing[hearing_type]=" . $arrayDetalleAudiencias["audiencia"]["desCatAudiencia"] . "&";
        $requestString .= "hearing[trial_type]=Penal&";
        $requestString .= "hearing[NUC]=" . $arrayDetalleAudiencias["solicitud"]["nuc"] . "&";
        $requestString .= "hearing[private]=" . $arrayDetalleAudiencias["solicitud"]["cveNaturaleza"] . "&";

        if ($arrayDetalleAudiencias["jueces"] != "" && count($arrayDetalleAudiencias["jueces"]) > 0) {
            $stringJuzgadores = "";
            foreach ($arrayDetalleAudiencias["jueces"] as $juzgadores) {
                $stringJuzgadores .= $juzgadores["nombre"] . " " . $juzgadores["paterno"] . " " . $juzgadores["materno"] . ",";
            }
            $requestString .= "hearing[judge][]=" . substr($stringJuzgadores, 0, -1) . "&";
        }

        if ($arrayDetalleAudiencias["solicitud"]["cveNaturaleza"] == "0") { //si es publica envia  datos de ofendidos, victimas y defensores
            if ($arrayDetalleAudiencias["ofendidos"] != "" && count($arrayDetalleAudiencias["ofendidos"]) > 0) {
                $stringOfendidos = "";
                foreach ($arrayDetalleAudiencias["ofendidos"] as $ofendidos) {
                    if ($ofendidos["cveTipoPersona"] == "1") {
                        $stringOfendidos .= $ofendidos["nombre"] . " " . $ofendidos["paterno"] . " " . $ofendidos["materno"] . ",";
                    } else {
                        $stringOfendidos .= $ofendidos["nombreMoral"] . ",";
                    }
                }
                $requestString .= "hearing[plaintiff][]=" . substr($stringOfendidos, 0, -1) . "&";
            }

            if ($arrayDetalleAudiencias["ofendidosDefensor"] != "" && count($arrayDetalleAudiencias["ofendidosDefensor"]) > 0) {
                $stringOfendidosDefensores = "";
                foreach ($arrayDetalleAudiencias["ofendidosDefensor"] as $ofendidosDefensores) {
                    $stringOfendidosDefensores .= $ofendidosDefensores["nombre"] . ",";
                }
                $requestString .= "hearing[plaintiffAdvocate][]=" . substr($stringOfendidosDefensores, 0, -1) . "&";
            }

            if ($arrayDetalleAudiencias["imputados"] != "" && count($arrayDetalleAudiencias["imputados"]) > 0) {
                $stringImputados = "";
                foreach ($arrayDetalleAudiencias["imputados"] as $imputados) {
                    if ($imputados["cveTipoPersona"] == "1") {
                        $stringImputados .= $imputados["nombre"] . " " . $imputados["paterno"] . " " . $imputados["materno"] . ",";
                    } else {
                        $stringImputados .= $imputados["nombreMoral"] . ",";
                    }
                }
                $requestString .= "hearing[defendant][]=" . substr($stringImputados, 0, -1) . "&";
            }

            if ($arrayDetalleAudiencias["imputadosDefensor"] != "" && count($arrayDetalleAudiencias["imputadosDefensor"]) > 0) {
                $stringImputadosDefensores = "";
                foreach ($arrayDetalleAudiencias["imputadosDefensor"] as $imputadosDefensores) {
                    $stringImputadosDefensores .= $imputadosDefensores["nombre"] . ",";
                }
                $requestString .= "hearing[defendantAdvocate][]=" . substr($stringImputadosDefensores, 0, -1) . "&";
            }

            if ($arrayDetalleAudiencias["delitos"] != "" && count($arrayDetalleAudiencias["delitos"]) > 0) {
                $stringDelitos = "";
                foreach ($arrayDetalleAudiencias["delitos"] as $delitos) {
                    $stringDelitos .= $delitos["desDelito"] . ",";
                }
                $requestString .= "hearing[offense]=" . substr($stringDelitos, 0, -1) . "&";
            }
        }

        $requestString = substr($requestString, 0, -1);
        echo "URL DEL CURL:" . $this->urlAuronix . "<br>";
        echo "DATOS ENVIADOS:" . $requestString . "<br>";
        $paramsBitacora["descEnvio"] = $requestString;
        $output = "";
        try {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $this->urlAuronix . "/index.php?r=api/hearing&id=" . $arrayDetalleAudiencias["audiencia"]["idAudienciaAuronix"] . "");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_USERPWD, "sigejupe:BsKfxi2REvKS");
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $requestString);
            $output = curl_exec($ch);
            curl_close($ch);
            $paramsBitacora["descRespuesta"] = $output;
            $output = json_decode($output, true);
        } catch (Exception $e) {
            $output = "";
            echo "ERROR EN TRY:" . $e;
        }

        echo "RESPUESTA CURL:";
        print_r($output);

        if ($output != "") {
            if (array_key_exists('errors', $output)) {
                echo "con errores";
                print_r($output["errors"]);
            } else {
                if (array_key_exists('hearing', $output) || array_key_exists('result_message', $output)) {
                    if (array_key_exists('id', $output["hearing"]) || $output["result_message"] == "Already up to date") {
                        echo "regreso sin errores:";
                        $paramsReturn["idAudiencia"] = $arrayDetalleAudiencias["audiencia"]["idAudiencia"];
                        $paramsReturn["idAuronix"] = $arrayDetalleAudiencias["audiencia"]["idAudienciaAuronix"];
                    } else {
                        echo "NO TRAJO EL ID DE AUDIENCIAAURONIX";
                    }
                } else {
                    echo "NO TRAJO HEARING";
                }
            }
        } else {
            echo "SIN RESPUESTA DEL CURL";
        }
        $this->insetBitacoraAuronix($paramsBitacora);
        return $paramsReturn;
//        echo "<br>";
    }

    public function deleteAudiencia($idAudienciaAuronix) {


        $output = "";
        try {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $this->urlAuronix . "/index.php?r=api/hearing&id=" . $idAudienciaAuronix);

            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_USERPWD, "sigejupe:BsKfxi2REvKS");
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
            $output = curl_exec($ch);
            curl_close($ch);
            $paramsBitacora["descRespuesta"] = $output;
            $output = json_decode($output, true);
        } catch (Exception $e) {
            $output = "";
            echo "ERROR EN TRY:" . $e;
        }


        if ($output != "") {
            if (array_key_exists('errors', $output)) {
                echo "con errores";
                print_r($output["errors"]);
            } else {
                if (array_key_exists('status', $output)) {
                    if ($output["status"] == "OK") {
                        $sql = "UPDATE tblaudiencias Set idAudienciaAuronix='0' Where idAudienciaAuronix='" . $idAudienciaAuronix . "'";
                        echo $sql;
                        mysql_query($sql);
                        if (!mysql_error()) {
                            echo "<br>";
                            echo "Elimino Audiencia Auronix -  actualizo de forma correcta la audiencia en sigejupe";
                        } else {
                            echo "ERROR AL ACTUALIZAR AUDIENCIA";
                        }
                    } else {
                        echo "ERROR AL BORRAR AUDIENCIA DE AURONIX";
                    }
                } else {
                    echo "NO TRAJO STATUS";
                }
            }
        } else {
            echo "SIN RESPUESTA DEL CURL";
        }
    }

    public function insetBitacoraAuronix($params = null) {
//        echo "<br>BITACORA<br>";
//        print_r($params);
        echo "<br>  ";
        if ($params != null && $params != "") {
            $sql = "INSERT INTO tblbitacoraauronix(descAccion,idAudiencia,idAuronix,descEnvio,descRespuesta,fechaRegistro) ";
            $sql .= "values('" . $params["descAccion"] . "','" . $params["idAudiencia"] . "','" . $params["idAuronix"] . "',";
            $sql .= "'" . $params["descEnvio"] . "','" . $params["descRespuesta"] . "','" . $params["fechaRegistro"] . "')";
//            echo $sql;
//            echo "<br>";
            mysql_query($sql);
            if (!mysql_error()) {
                echo "INSERTO CORRECTAMENTE LA INFORMACIÓN DE LA AUDIENCIA EN LA BITACORA";
            } else {
                echo "ERROR AL INSERTAR EN BITACORA";
            }
        }
    }

    public function eliminaTodoFechas($fecha = null, $juzgado = null) {
        $Conexion = mysql_connect("10.22.157.19", "sigejupe", "sigejupe_2016"); #prod
        mysql_select_db("htsj_sigejupe", $Conexion);


        $this->urlAuronix = $juzgado;
        $audienciasAuronix = $this->selectTodasAudienciasFechas($fecha);
        foreach ($audienciasAuronix["hearings"] as $audAuronix) {
            print_r($this->deleteAudiencia($audAuronix["id"]));
        }

        mysql_close($Conexion);
    }

}

//$auronixController = new AuronixController();
//$auronixController->eliminaTodoFechas("6141234626540082567");
//$auronixController->selectTodasAudiencias();
?>
