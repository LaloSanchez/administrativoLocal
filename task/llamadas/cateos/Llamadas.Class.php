<?php

/**
 * Incluimos librerias, DAO's y DTO's
 */
include_once(dirname(__FILE__) . "/../../../modelos/sigejupe/dto/cateos/CateosDTO.Class.php");
include_once(dirname(__FILE__) . "/../../../modelos/sigejupe/dao/cateos/CateosDAO.Class.php");

include_once(dirname(__FILE__) . "/../../../modelos/sigejupe/dto/solicitudescateos/SolicitudescateosDTO.Class.php");
include_once(dirname(__FILE__) . "/../../../modelos/sigejupe/dao/solicitudescateos/SolicitudescateosDAO.Class.php");

include_once(dirname(__FILE__) . "/../../../modelos/sigejupe/dto/juzgadorescateos/JuzgadorescateosDTO.Class.php");
include_once(dirname(__FILE__) . "/../../../modelos/sigejupe/dao/juzgadorescateos/JuzgadorescateosDAO.Class.php");

include_once(dirname(__FILE__) . "/../../../modelos/sigejupe/dto/juzgadores/JuzgadoresDTO.Class.php");
include_once(dirname(__FILE__) . "/../../../modelos/sigejupe/dao/juzgadores/JuzgadoresDAO.Class.php");

include_once(dirname(__FILE__) . "/../../../modelos/sigejupe/dto/telefonosjuzgadores/TelefonosjuzgadoresDTO.Class.php");
include_once(dirname(__FILE__) . "/../../../modelos/sigejupe/dao/telefonosjuzgadores/TelefonosjuzgadoresDAO.Class.php");

include_once(dirname(__FILE__) . "/../../../modelos/sigejupe/dto/bitacoranotificaciones/BitacoranotificacionesDTO.Class.php");
include_once(dirname(__FILE__) . "/../../../modelos/sigejupe/dao/bitacoranotificaciones/BitacoranotificacionesDAO.Class.php");

include_once(dirname(__FILE__) . "/../../../tribunal/connect/Proveedor.Class.php");
include_once(dirname(__FILE__) . "/../../../tribunal/host/Host.Class.php");
include_once(dirname(__FILE__) . "/../../../tribunal/logger/Logger.Class.php");
include_once(dirname(__FILE__) . "/../../../tribunal/ssh/asterisk.php");

class Llamadas {

    private $proveedor;
    private $log;
    private $intentos;
    private $bitacoraDto;
    private $bitacoraDao;

    public function __construct() {
        $this->proveedor = new Proveedor("mysql", "sigejupe");
        $this->intentos = 3;
        $this->proveedor->connect();
        $this->log = new Logger("../../../logs/", "Cateos");
        $this->bitacoraDao = new BitacoranotificacionesDAO();
    }

    public function call() {
        date_default_timezone_set("America/Mexico_City");
        // Obtenemos las Solicitudes de Cateo
        $solicitudeCateoDto = new SolicitudescateosDTO();
        $solicitudeCateoDto->setCveEstatusSolicitudCateo("1");
        $solicitudeCateoDao = new SolicitudescateosDAO();
        $solicitudeCateoDto = $solicitudeCateoDao->selectSolicitudescateos($solicitudeCateoDto);
        $info = array();
        $countinfo = 0;
        if ($solicitudeCateoDto != "") {
            foreach ($solicitudeCateoDto as $solicitudCateoDto) {
                //--> Obtenemos la relacion Juzgador-Cateo
                $juzgadoresCateosDto = new JuzgadorescateosDTO();
                $juzgadoresCateosDto->setActivo("S");
                $juzgadoresCateosDto->setIdSolicitudCateo($solicitudCateoDto->getIdSolicitudCateo());
                $juzgadoresCateosDao = new JuzgadorescateosDAO();
                $juzgadoresCateosDto = $juzgadoresCateosDao->selectJuzgadorescateos($juzgadoresCateosDto);

                //-> Obtenemos la Informacion del Cateo
                $cateoDto = new CateosDTO();
                $cateoDto->setIdSolicitudCateo($solicitudCateoDto->getIdSolicitudCateo());
                $cateoDao = new CateosDAO();
                $cateoDto = $cateoDao->selectCateos($cateoDto);

                if ($juzgadoresCateosDto != "") {
                    foreach ($juzgadoresCateosDto as $juzgadorCateos) {
                        //-> Obtenemos la informcion del Juzdor
                        $juzgadoresDto = new JuzgadoresDTO();
                        $juzgadoresDto->setActivo("S");
                        $juzgadoresDto->setIdJuzgador($juzgadorCateos->getIdJuzgador());
                        $juzgadoresDao = new JuzgadoresDAO();
                        $juzgadoresDto = $juzgadoresDao->selectJuzgadores($juzgadoresDto);
                        if ($juzgadoresDto != "") {
                            foreach ($juzgadoresDto as $juzgador) {
                                // --> Obtenemos los Telefonos del Juzgador
                                $telefonosDto = new TelefonosjuzgadoresDTO();
                                $telefonosDto->setActivo("S");
                                $telefonosDto->setIdJuzgador($juzgador->getIdJuzgador());
                                $telefonosDao = new TelefonosjuzgadoresDAO();
                                $telefonosDto = $telefonosDao->selectTelefonosjuzgadores($telefonosDto);
                                if ($telefonosDto != "") {
                                    foreach ($telefonosDto as $telefono) {
                                        $info["informacion"][$countinfo]["idSolicitudCateo"] = $solicitudCateoDto->getIdSolicitudCateo();
                                        $info["informacion"][$countinfo]["idCateo"] = $cateoDto[0]->getIdCateo();
                                        $info["informacion"][$countinfo]["cveUsuario"] = $juzgador->getCveUsuario();
                                        $info["informacion"][$countinfo]["numeroCarpeta"] = $cateoDto[0]->getNumeroCateo();
                                        $info["informacion"][$countinfo]["anioCateo"] = $cateoDto[0]->getAnioCateo();
                                        $info["informacion"][$countinfo]["juzgadoProcedencia"] = $solicitudCateoDto->getCveJuzgado();
                                        $info["informacion"][$countinfo]["juzgadoSolicita"] = $solicitudCateoDto->getCveJuzgadoDesahoga();
                                        $info["informacion"][$countinfo]["nombre"] = $juzgador->getNombre() . " " . $juzgador->getPaterno() . " " . $juzgador->getMaterno();
                                        $info["informacion"][$countinfo]["email"] = $telefono->getEmail();
                                        $info["informacion"][$countinfo]["phone"] = $telefono->getTelefono();
                                        $info["informacion"][$countinfo]["cellphone"] = $telefono->getCelular();
                                        $countinfo++;
                                    }
                                } else {
                                    echo "NO SE ENCONTRARON DATOS DE COMUNICACION";
                                }
                            }
                        } else {
                            echo "NO SE ENCONTRO AL JUZGADOR";
                        }
                    }
                } else {
                    echo "NO SE ENCONTRO LA RELACION JUZGADOR CATEO";
                }
            }
        } else {
            echo "NO SE ENCONTRARON SOLICITUDES DE CATEO";
        }

        if (count($info) > 0) {
            $this->makeCall($info);
        } else {
            
        }
    }

    private function makeCall($param) {
        if (isset($param["informacion"]) != "" || count(isset($param["informacion"])) > 0) {
            $cateosSin = array();
            $pendiente = 0;
            foreach ($param["informacion"] as $info) {
                $archivoGenerado = false;
                $bitacoraDto = new BitacoranotificacionesDTO();
                $bitacoraDto->setCveMedioNotificacion("2");
                $bitacoraDto->setCveTipoActuacion("12");
                $bitacoraDto->setIdReferencia($info["idCateo"]);
                $bitacoraDto = $this->bitacoraDao->selectBitacoranotificaciones($bitacoraDto);

                $celulares = explode(",", $info["cellphone"]);
                $fijos = explode(",", $info["phone"]);

                // --> Realiza la llamada a los numeros de Celular
                foreach ($celulares as $celular) {
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

                // --> Realiza las llamdas a los numeros de telefono
                if ($archivoGenerado == false) {
                    foreach ($fijos as $fijo) {
                        if ($fijo != "") {
//                            if (substr($fijo, 0, 3) == "722") {
//                                $fijo .= "098" . $fijo;
//                                $fijo = "" . substr($fijo, 3, 7);
//                            } else {
//                                $fijo = "01" . $fijo;
//                            }
                            if (substr($fijo, 0, 3) == "722") {
                                $fijo = "044" . $fijo;
                            } else {
                                $fijo = "045" . $fijo;
                            }
                        }
                        $archivoGenerado = $this->bitacoraAction($fijo, $info, $bitacoraDto);
                        break;
                    }
                }

                if ($archivoGenerado == false) {
                    $cateosSin[$pendiente] = $info;
                    $pendiente++;
                }
            }
        } else {
            echo "NO SE ENCONTRO MEDIOS DE COMUNICACION";
        }
    }

    private function asteriskCall($celular, $nombrearchivo, $info) {
        // -> Realiza la llamda
        $error = "";
        $asterisk = new asterisk2();
        $error = $asterisk->llamar("10.22.157.61", $celular, "./../../../llamadas/", $nombrearchivo,"outboundmsg2");
        //$error = "";
        $cveEstatus = 1;
        if (preg_match("/problema/", $error)) {
            $cveEstatus = 3;
        }

        $bitacoraDto = new BitacoranotificacionesDTO();
        $bitacoraDto->setCveMedioNotificacion("2");
        $bitacoraDto->setCveTipoCarpeta("");
        $bitacoraDto->setCveTipoActuacion("12");
        $bitacoraDto->setCveEstatusNotificacion($cveEstatus);
        $bitacoraDto->setIdReferencia($info["idCateo"]);
        $bitacoraDto->setAnio($info["anioCateo"]);
        $bitacoraDto->setNumero($info["numeroCarpeta"]);
        $bitacoraDto->setCvejuzgado($info["juzgadoSolicita"]);
        $bitacoraDto->setCveUsuario($info["cveUsuario"]);
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
                if (preg_match("/" . "cateo_" . $info["idCateo"] . "_" . $celular . "_" . $info["cveUsuario"] . "_" . "/", $bitacora->getMovimiento())) {
                    $numIntentos = $numIntentos + 1;
                }
            }
        }

        $nombrearchivo = "";
        if (($numIntentos <= $this->intentos) && (strlen($celular) >= 10)) {
            echo $nombrearchivo = "cateo_" . $info["idCateo"] . "_" . $celular . "_" . $info["cveUsuario"] . "_" . $numIntentos . ".txt";
            $archivoGenerado = $this->asteriskCall($celular, $nombrearchivo, $info);
        }

        return $archivoGenerado;
    }

}

$makeCall = new Llamadas();
$makeCall->call();
