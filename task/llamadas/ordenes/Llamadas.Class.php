<?php

/**
 * Incluimos librerias, DAO's y DTO's
 */
include_once(dirname(__FILE__) . "/../../../modelos/sigejupe/dto/ordenes/OrdenesDTO.Class.php");
include_once(dirname(__FILE__) . "/../../../modelos/sigejupe/dao/ordenes/OrdenesDAO.Class.php");

include_once(dirname(__FILE__) . "/../../../modelos/sigejupe/dto/solicitudesordenes/SolicitudesordenesDTO.Class.php");
include_once(dirname(__FILE__) . "/../../../modelos/sigejupe/dao/solicitudesordenes/SolicitudesordenesDAO.Class.php");

include_once(dirname(__FILE__) . "/../../../modelos/sigejupe/dto/juzgadoresordenes/JuzgadoresordenesDTO.Class.php");
include_once(dirname(__FILE__) . "/../../../modelos/sigejupe/dao/juzgadoresordenes/JuzgadoresordenesDAO.Class.php");

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
        $this->log = new Logger("../../../logs/", "Ordenes");
        $this->bitacoraDao = new BitacoranotificacionesDAO();
    }

    public function call() {
        date_default_timezone_set("America/Mexico_City");
        // Obtenemos las Solicitudes de Ordenes de Aprehension
        $solicitudeOrdenesDto = new SolicitudesordenesDTO();
        $solicitudeOrdenesDto->setCveEstatusSolicitudOrden("1");
        $solicitudeOrdenesDao = new SolicitudesordenesDAO();
        $solicitudeOrdenesDto = $solicitudeOrdenesDao->selectSolicitudesordenes($solicitudeOrdenesDto);
        $info = array();
        $countinfo = 0;
        if ($solicitudeOrdenesDto != "") {
            foreach ($solicitudeOrdenesDto as $solicitudOrdenDto) {
                //--> Obtenemos la relacion Juzgador-Orden Aprehension
                $juzgadoresOrdenesDto = new JuzgadoresordenesDTO();
                $juzgadoresOrdenesDto->setActivo("S");
                $juzgadoresOrdenesDto->setIdSolicitudOrden($solicitudOrdenDto->getIdSolicitudOrden());
                $juzgadoresOrdenesDao = new JuzgadoresordenesDAO();
                $juzgadoresOrdenesDto = $juzgadoresOrdenesDao->selectJuzgadoresordenes($juzgadoresOrdenesDto);

                //-> Obtenemos la Informacion del Orde de Aprehension
                $ordenDto = new OrdenesDTO();
                $ordenDto->setIdSolicitudOrden($solicitudOrdenDto->getIdSolicitudOrden());
                $ordenDao = new OrdenesDAO();
                $ordenDto = $ordenDao->selectOrdenes($ordenDto);

                if ($juzgadoresOrdenesDto != "") {
                    foreach ($juzgadoresOrdenesDto as $juzgadorOrdenes) {
                        //-> Obtenemos la informcion del Juzdor
                        $juzgadoresDto = new JuzgadoresDTO();
                        $juzgadoresDto->setActivo("S");
                        $juzgadoresDto->setIdJuzgador($juzgadorOrdenes->getIdJuzgador());
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
                                        $info["informacion"][$countinfo]["idSolicitudOrden"] = $solicitudOrdenDto->getIdSolicitudOrden();
                                        $info["informacion"][$countinfo]["idOrden"] = $ordenDto[0]->getIdOrden();
                                        $info["informacion"][$countinfo]["cveUsuario"] = $juzgador->getCveUsuario();
                                        $info["informacion"][$countinfo]["numeroCarpeta"] = $ordenDto[0]->getNumeroOrden();
                                        $info["informacion"][$countinfo]["anioOrden"] = $ordenDto[0]->getAnioOrden();
                                        $info["informacion"][$countinfo]["juzgadoProcedencia"] = $solicitudOrdenDto->getCveJuzgado();
                                        $info["informacion"][$countinfo]["juzgadoSolicita"] = $solicitudOrdenDto->getCveJuzgadoDesahoga();
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
                    echo "NO SE ENCONTRO LA RELACION JUZGADOR ORDEN DE APREHENSION";
                }
            }
        } else {
            echo "NO SE ENCONTRARON SOLICITUDES DE ORDEN DE APREHENSION";
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
                $bitacoraDto->setCveTipoActuacion("15");
                $bitacoraDto->setIdReferencia($info["idOrden"]);
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
        $error = $asterisk->llamar("10.22.165.2", $celular, "./../../../llamadas/", $nombrearchivo,"orden");
        //$error = "";
        $cveEstatus = 1;
        if (preg_match("/problema/", $error)) {
            $cveEstatus = 3;
        }

        $bitacoraDto = new BitacoranotificacionesDTO();
        $bitacoraDto->setCveMedioNotificacion("2");
        $bitacoraDto->setCveTipoCarpeta("");
        $bitacoraDto->setCveTipoActuacion("15");
        $bitacoraDto->setCveEstatusNotificacion($cveEstatus);
        $bitacoraDto->setIdReferencia($info["idOrden"]);
        $bitacoraDto->setAnio($info["anioOrden"]);
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
                if (preg_match("/" . "orden_" . $info["idOrden"] . "_" . $celular . "_" . $info["cveUsuario"] . "_" . "/", $bitacora->getMovimiento())) {
                    $numIntentos = $numIntentos + 1;
                }
            }
        }

        $nombrearchivo = "";
        if (($numIntentos <= $this->intentos) && (strlen($celular) >= 10)) {
            echo $nombrearchivo = "orden_" . $info["idOrden"] . "_" . $celular . "_" . $info["cveUsuario"] . "_" . $numIntentos . ".txt";
            $archivoGenerado = $this->asteriskCall($celular, $nombrearchivo, $info);
        }

        return $archivoGenerado;
    }

}

$makeCall = new Llamadas();
$makeCall->call();
