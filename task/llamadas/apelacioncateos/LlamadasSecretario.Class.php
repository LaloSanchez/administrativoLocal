<?php

/**
 * Incluimos librerias, DAO's y DTO's
 */
date_default_timezone_set("America/Mexico_City");
include_once(dirname(__FILE__) . "/../../../modelos/sigejupe/dto/solicitudescateos/SolicitudescateosDTO.Class.php");
include_once(dirname(__FILE__) . "/../../../modelos/sigejupe/dao/solicitudescateos/SolicitudescateosDAO.Class.php");

include_once(dirname(__FILE__) . "/../../../modelos/sigejupe/dto/telefonosjuzgadores/TelefonosjuzgadoresDTO.Class.php");
include_once(dirname(__FILE__) . "/../../../modelos/sigejupe/dao/telefonosjuzgadores/TelefonosjuzgadoresDAO.Class.php");

include_once(dirname(__FILE__) . "/../../../modelos/sigejupe/dto/juzgadores/JuzgadoresDTO.Class.php");
include_once(dirname(__FILE__) . "/../../../modelos/sigejupe/dao/juzgadores/JuzgadoresDAO.Class.php");

include_once(dirname(__FILE__) . "/../../../modelos/sigejupe/dto/cateos/CateosDTO.Class.php");
include_once(dirname(__FILE__) . "/../../../modelos/sigejupe/dao/cateos/CateosDAO.Class.php");

include_once(dirname(__FILE__) . "/../../../modelos/sigejupe/dto/apelacioncateos/ApelacioncateosDTO.Class.php");
include_once(dirname(__FILE__) . "/../../../modelos/sigejupe/dao/apelacioncateos/ApelacioncateosDAO.Class.php");

include_once(dirname(__FILE__) . "/../../../modelos/sigejupe/dto/juzgadoresapelacateos/JuzgadoresapelacateosDTO.Class.php");
include_once(dirname(__FILE__) . "/../../../modelos/sigejupe/dao/juzgadoresapelacateos/JuzgadoresapelacateosDAO.Class.php");

include_once(dirname(__FILE__) . "/../../../modelos/sigejupe/dto/juzgados/JuzgadosDTO.Class.php");
include_once(dirname(__FILE__) . "/../../../modelos/sigejupe/dao/juzgados/JuzgadosDAO.Class.php");

include_once(dirname(__FILE__) . "/../../../modelos/sigejupe/dto/bitacoranotificaciones/BitacoranotificacionesDTO.Class.php");
include_once(dirname(__FILE__) . "/../../../modelos/sigejupe/dao/bitacoranotificaciones/BitacoranotificacionesDAO.Class.php");

include_once(dirname(__FILE__) . "/../../libs/NumToText.Class.php");
include_once(dirname(__FILE__) . "/../../../webservice/cliente/usuarios/UsuarioCliente.php");

include_once(dirname(__FILE__) . "/../../../tribunal/connect/Proveedor.Class.php");
include_once(dirname(__FILE__) . "/../../../tribunal/host/Host.Class.php");
include_once(dirname(__FILE__) . "/../../../tribunal/logger/Logger.Class.php");
include_once(dirname(__FILE__) . "/../../../tribunal/ssh/asterisk.php");

class Llamadas {

    private $proveedor;
    private $log;
    private $intentos;
    private $bitacoraDao;

    public function __construct() {
        $this->proveedor = new Proveedor("mysql", "sigejupe");
        $this->intentos = 3;
        $this->proveedor->connect();
        $this->log = new Logger("../../../logs/", "ApelacionCateos");
        $this->bitacoraDao = new BitacoranotificacionesDAO();
    }

    public function call() {
        date_default_timezone_set("America/Mexico_City");
        // Obtenemos las Solicitudes de Cateo
        $apelacionDto = new ApelacioncateosDTO();
        $apelacionDao = new ApelacioncateosDAO();
        $orden = " WHERE cveEstatusSolicitudCateo in (9, 10) ";
        $apelacionDto = $apelacionDao->selectApelacioncateos($apelacionDto, $orden);
        $juzgadores = array();
        if ($apelacionDto != "") {
            $count = 0;
            foreach ($apelacionDto as $apelacion) {
                #Obtenemos los datos del cateo relacionado a la apelacion
                $cateoDto = new CateosDTO();
                $cateoDao = new CateosDAO();
                $cateoDto->setIdCateo($apelacion->getIdCateo());
                $cateoDto = $cateoDao->selectCateos($cateoDto);

                if ($cateoDto != "") {
                    $cateoDto = $cateoDto[0];
                    #Obtenemos la Informacion de la solicitud de cateo 
                    $solCateoDto = new SolicitudescateosDTO();
                    $solCateoDao = new SolicitudescateosDAO();
                    $solCateoDto->setIdSolicitudCateo($cateoDto->getIdSolicitudCateo());
                    $solCateoDto = $solCateoDao->selectSolicitudescateos($solCateoDto);
                    if ($solCateoDto != "") {
                        $solCateoDto = $solCateoDto[0];
                        #Obtenemos el Juzgado
                        $JuzgadosDao = new JuzgadosDAO();
                        $JuzgadosDto = new JuzgadosDTO();
                        $JuzgadosDto->setCveJuzgado($solCateoDto->getCveJuzgadoDesahoga());
                        $JuzgadosDto->setActivo("S");
                        $JuzgadosDto = $JuzgadosDao->selectJuzgados($JuzgadosDto);
                        if ($JuzgadosDto != "" && count($JuzgadosDto) > 0) {
                            $JuzgadosDto = $JuzgadosDto[0];
                        }
                    }

                    #Obtenemos la informacion del Juzgador
                    $juzgadorDto = new JuzgadoresDTO();
                    $juzgadorDao = new JuzgadoresDAO();
                    $juzgadorDto->setActivo("S");
                    $juzgadorDto->setIdJuzgador($apelacion->getCveUsuarioSecretario());
                    $juzgadorDto = $juzgadorDao->selectJuzgadores($juzgadorDto);
                    if ($juzgadorDto != "") {
                        $juzgadorDto = $juzgadorDto[0];
                        #Obtenemos los medios de comunicacion de Juzgador
                        $telefonosDto = new TelefonosjuzgadoresDTO();
                        $telefonosDao = new TelefonosjuzgadoresDAO();
                        $telefonosDto->setActivo("S");
                        $telefonosDto->setIdJuzgador($juzgadorDto->getIdJuzgador());
                        $telefonosDto = $telefonosDao->selectTelefonosjuzgadores($telefonosDto);
                        $juzgadores[$count]["juzgador"] = utf8_encode($juzgadorDto->getNombre() . " " . $juzgadorDto->getPaterno() . " " . $juzgadorDto->getMaterno());

                        if ($telefonosDto != "") {
                            $countTel = 0;
                            foreach ($telefonosDto as $telefonos) {
                                if ($telefonos->getEmail() != "")
                                    $juzgadores[$count]["email"][$countTel] = $telefonos->getEmail();

                                if ($telefonos->getCelular() != "")
                                    $juzgadores[$count]["celular"][$countTel] = $telefonos->getCelular();

                                if ($telefonos->getTelefono() != "")
                                    $juzgadores[$count]["telefono"][$countTel] = $telefonos->getTelefono();

                                $countTel++;
                            }
                            $fecha = $apelacion->getFechaActualizacion();
                            $horaCateo = explode(' ', $fecha);
                            $horaCateo = $horaCateo[1];
                            $juzgadores[$count]["horaCateo"] = $horaCateo;
                            $juzgadores[$count]["numeroCateo"] = $cateoDto->getNumeroCateo();
                            $juzgadores[$count]["anioCateo"] = $cateoDto->getAnioCateo();
                            $juzgadores[$count]["juzgado"] = $JuzgadosDto->getDesJuzgado();
                            $juzgadores[$count]["idCateo"] = $cateoDto->getIdCateo();
                            $juzgadores[$count]["idjuzgado"] = $JuzgadosDto->getCveJuzgado();
                            $juzgadores[$count]["idJuzgador"] = $juzgadorDto->getCveUsuario();
                        }
                    }
                    $count++;
                }
            }
        }

        if (count($juzgadores) > 0) {
            $this->makeCall($juzgadores);
        }
    }

    private function makeCall($param) {
        if (isset($param["celular"]) != "" || count(isset($param["celular"])) > 0) {
            $cateosSin = array();
            $pendiente = 0;
            foreach ($param as $info) {
                $archivoGenerado = false;
                $bitacoraDto = new BitacoranotificacionesDTO();
                $bitacoraDto->setCveMedioNotificacion("2");
                $bitacoraDto->setCveTipoActuacion("28");
                $bitacoraDto->setIdReferencia($info["idCateo"]);
                $bitacoraDto = $this->bitacoraDao->selectBitacoranotificaciones($bitacoraDto);

                foreach ($info["celular"] as $celular) {
                    if ($celular != "") {
                        if (substr($celular, 0, 3) == "722") {
                            $celular = "044" . $celular;
                        } else {
                            $celular = "045" . $celular;
                        }
                        $archivoGenerado = $this->bitacoraAction($celular, $info, $bitacoraDto);
                        break;
                    }
                }

                if ($archivoGenerado == false) {
                    $cateosSin[$pendiente] = $info;
                    $pendiente++;
                }
            }
            return $archivoGenerado;
        } else {
            echo "NO SE ENCONTRO MEDIOS DE COMUNICACION";
        }
    }

    private function asteriskCall($celular, $nombrearchivo, $info) {
        // -> Realiza la llamda
        $error = "";
        $asterisk = new asterisk2();
        $error = $asterisk->llamar("10.22.157.61", $celular, "./../../../llamadas/", $nombrearchivo, "outboundmsg6");
        //$error = "";
        $cveEstatus = 1;
        if (preg_match("/problema/", $error)) {
            $cveEstatus = 3;
        }

        $bitacoraDto = new BitacoranotificacionesDTO();
        $bitacoraDto->setCveMedioNotificacion("2");
        $bitacoraDto->setCveTipoCarpeta("");
        $bitacoraDto->setCveTipoActuacion("29");
        $bitacoraDto->setCveEstatusNotificacion($cveEstatus);
        $bitacoraDto->setIdReferencia($info["idCateo"]);
        $bitacoraDto->setAnio($info["anioCateo"]);
        $bitacoraDto->setNumero($info["numeroCateo"]);
        $bitacoraDto->setCvejuzgado($info["idjuzgado"]);
        $bitacoraDto->setCveUsuario($info["idJuzgador"]);
        $bitacoraDto->setMedio($celular);
        $bitacoraDto->setMovimiento("Archivo:" . $nombrearchivo . "<br>Respuesta:" . $error);

        $bitacoraDto = $this->bitacoraDao->insertBitacoranotificaciones($bitacoraDto);
        if ($bitacoraDto != "") {
            return true;
        } else {
            return false;
        }
    }

    private function bitacoraAction($celular, $info, $bitacoraDto) {
        $archivoGenerado = false;
        $numIntentos = 1;
        if ($bitacoraDto != "") {
            foreach ($bitacoraDto as $bitacora) {
                if (preg_match("/" . "cateo_apelacion_" . $info["idCateo"] . "_" . $celular . "_" . $info["idJuzgador"] . "_" . "/", $bitacora->getMovimiento())) {
                    $numIntentos = $numIntentos + 1;
                }
            }
        }

        $nombrearchivo = "";
        if (($numIntentos <= $this->intentos) && (strlen($celular) >= 10)) {
            $nombrearchivo = "cateo_apelacion_" . $info["idCateo"] . "_" . $celular . "_" . $info["idJuzgador"] . "_" . $numIntentos . ".txt";
            $archivoGenerado = $this->asteriskCall($celular, $nombrearchivo, $info);
        }

        return $archivoGenerado;
    }

}

$makeCall = new Llamadas();
$resp = $makeCall->call();