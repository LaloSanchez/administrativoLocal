<?php

/**
 * Incluimos librerias, DAO's y DTO's
 */
date_default_timezone_set("America/Mexico_City");

include_once(dirname(__FILE__) . "/../../../modelos/sigejupe/dto/solicitudesmuestras/SolicitudesmuestrasDTO.Class.php");
include_once(dirname(__FILE__) . "/../../../modelos/sigejupe/dao/solicitudesmuestras/SolicitudesmuestrasDAO.Class.php");

include_once(dirname(__FILE__) . "/../../../modelos/sigejupe/dto/juzgadoresmuestras/JuzgadoresmuestrasDTO.Class.php");
include_once(dirname(__FILE__) . "/../../../modelos/sigejupe/dao/juzgadoresmuestras/JuzgadoresmuestrasDAO.Class.php");

include_once(dirname(__FILE__) . "/../../../modelos/sigejupe/dto/telefonosjuzgadores/TelefonosjuzgadoresDTO.Class.php");
include_once(dirname(__FILE__) . "/../../../modelos/sigejupe/dao/telefonosjuzgadores/TelefonosjuzgadoresDAO.Class.php");

include_once(dirname(__FILE__) . "/../../../modelos/sigejupe/dto/juzgadores/JuzgadoresDTO.Class.php");
include_once(dirname(__FILE__) . "/../../../modelos/sigejupe/dao/juzgadores/JuzgadoresDAO.Class.php");

include_once(dirname(__FILE__) . "/../../../modelos/sigejupe/dto/respuestamuestra/RespuestamuestraDTO.Class.php");
include_once(dirname(__FILE__) . "/../../../modelos/sigejupe/dao/respuestamuestra/RespuestamuestraDAO.Class.php");

include_once(dirname(__FILE__) . "/../../../modelos/sigejupe/dto/juzgados/JuzgadosDTO.Class.php");
include_once(dirname(__FILE__) . "/../../../modelos/sigejupe/dao/juzgados/JuzgadosDAO.Class.php");

include_once(dirname(__FILE__) . "/../../../modelos/sigejupe/dto/bitacoranotificaciones/BitacoranotificacionesDTO.Class.php");
include_once(dirname(__FILE__) . "/../../../modelos/sigejupe/dao/bitacoranotificaciones/BitacoranotificacionesDAO.Class.php");

include_once(dirname(__FILE__) . "/../../libs/NumToText.Class.php");
include_once(dirname(__FILE__) . "/../../../webservice/cliente/usuarios/UsuarioCliente.php");

include_once(dirname(__FILE__) . "/../../../tribunal/connect/Proveedor.Class.php");
include_once(dirname(__FILE__) . "/../../../tribunal/host/Host.Class.php");
include_once(dirname(__FILE__) . "/../../../tribunal/logger/Logger.Class.php");
include_once(dirname(__FILE__) . "/../../../tribunal/correo/EmailHELPER.Class.php");

class Email {

    private $objDatCorreo;
    private $proveedor;
    private $intentos;
    private $logger;
    private $numText;
    private $usuariosCliente;

    public function __construct() {
        $this->objDatCorreo = $this->plantillaCorreo("cateos");
        $this->proveedor = new Proveedor("mysql", "sigejupe");
        $this->intentos = 1;
        $this->proveedor->connect();
        $this->logger = new Logger("../", "cateosCorreos");
        $this->numText = new NumToText();
        $this->usuariosCliente = new UsuarioCliente();
    }

    /**
     * Obtiene la plantilla de Correo Electronico
     * @param string $attNom Nombre de la Plantilla que se utilizara
     * @return object Objeto de la Plantilla de Correo electronico
     */
    function plantillaCorreo($attNom) {
        $objPlantilla = "";
        $xml = simplexml_load_file("/database/html/sigejupev2/tribunal/correo/servidorCorreo.xml");
//        $xml = simplexml_load_file("./../../../tribunal/correo/servidorCorreo.xml");
        for ($i = 0; $i < count($xml->Correo); $i++) {
            $planNombre = $xml->Correo[$i]->attributes()->nombre;
            if ($planNombre == $attNom) {
                $objPlantilla = $xml->Correo[$i];
                break;
            }
        }

        return $objPlantilla;
    }

    /**
     * Envia Correo
     */
    public function sendEmails() {
        $solicitudMuestraDto = new SolicitudesmuestrasDTO();
        $solicitudMuestraDao = new SolicitudesmuestrasDAO();
        $orden = " WHERE cveEstatusSolicitudMuestra in (1,2)";
        $solicitudMuestraDto = $solicitudMuestraDao->selectSolicitudesmuestras($solicitudMuestraDto, "", $orden, $this->proveedor);
        $juzgadores = array();
        if ($solicitudMuestraDto != "") {
            $count = 0;
            foreach ($solicitudMuestraDto as $muestraSolicitud) {
                #Buscamos relacion Muestras -Juez
                $juzgadoresDto = new JuzgadoresmuestrasDTO();
                $juzgadoresDto->setIdSolicitudMuestra($muestraSolicitud->getIdSolicitudMuestra());
                $juzgadoresDto->setActivo("S");
                $juzgadoresDao = new JuzgadoresmuestrasDAO();
                $juzgadoresDto = $juzgadoresDao->selectJuzgadoresmuestras($juzgadoresDto);

                #Obteneos la muestra
                $muestraDto = new RespuestamuestraDTO();
                $muestraDao = new RespuestamuestraDAO();
                $muestraDto->setIdSolicitudMuestra($muestraSolicitud->getIdSolicitudMuestra());
                $muestraDto = $muestraDao->selectRespuestamuestra($muestraDto);
                if ($muestraDto != "") {
                    $muestraDto = $muestraDto[0];
                }

                #Obtenemos el Juzgado
                $JuzgadosDao = new JuzgadosDAO();
                $JuzgadosDto = new JuzgadosDTO();
                $JuzgadosDto->setCveJuzgado($muestraSolicitud->getCveJuzgadoDesahoga());
                $JuzgadosDto->setActivo("S");
                $JuzgadosDto = $JuzgadosDao->selectJuzgados($JuzgadosDto);
                if ($JuzgadosDto != "" && count($JuzgadosDto) > 0) {
                    $JuzgadosDto = $JuzgadosDto[0];
                }

                if ($juzgadoresDto != "") {
                    foreach ($juzgadoresDto as $juzgadorRelacion) {
                        $juzgadorMuestra = new JuzgadoresDTO();
                        $juzgadorMuestra->setActivo("S");
                        $juzgadorMuestra->setIdJuzgador($juzgadorRelacion->getIdJuzgador());
                        $juzgadorMuestraDao = new JuzgadoresDAO();
                        $juzgadorMuestra = $juzgadorMuestraDao->selectJuzgadores($juzgadorMuestra);
                        if ($juzgadorMuestra != "") {
                            $telefonosDto = new TelefonosjuzgadoresDTO();
                            $telefonosDao = new TelefonosjuzgadoresDAO();
                            $telefonosDto->setIdJuzgador($juzgadorMuestra[0]->getIdJuzgador());
                            $telefonosDto->setActivo("S");
                            $telefonosDto = $telefonosDao->selectTelefonosjuzgadores($telefonosDto);
                            if ($telefonosDto != "") {
                                $arrayEmail = array();
                                $arrayTelefonos = array();
                                foreach ($telefonosDto as $telefonos) {
                                    if ($telefonos->getEmail() != "") {
                                        $arrayEmail[] = $telefonos->getEmail();
                                    }
                                    if ($telefonos->getTelefono() != "") {
                                        $arrayTelefonos[] = $telefonos->getTelefono();
                                    }
                                    if ($telefonos->getCelular() != "") {
                                        $arrayTelefonos[] = $telefonos->getCelular();
                                    }
                                }
                                $juzgadores[$count]["juzgador"] = $juzgadorMuestra[0]->getNombre() . " " . $juzgadorMuestra[0]->getPaterno() . " " . $juzgadorMuestra[0]->getMaterno();

                                $juzgadores[$count]["email"] = $arrayEmail;
                                $juzgadores[$count]["telefono"] = $arrayTelefonos;
                                $fecha = $muestraDto->getFechaRegistro();
                                $horaMuestra = explode(' ', $fecha);
                                $horaMuestra = $horaMuestra[1];
                                $juzgadores[$count]["horaMuestra"] = $horaMuestra;
                                $juzgadores[$count]["fecha"] = $this->numText->FechaLarga($fecha);
                                $listaUsuarios = "";
                                try {
                                    $UsuarioCliente = new UsuarioCliente();
                                    $listaUsuarios = json_decode($UsuarioCliente->selectUsuarios_In($muestraSolicitud->getCveUsuario()), true);
                                } catch (Exception $ex) {
                                    $error = true;
                                    echo "ERROR AL CONSUMIR WEB SERVICE DEL MINISTERIO PUBLICO EN EL SISTEMA DE GESTION";
                                }
                                $nombreMP = "NO ENCONTRADO EN GESTION";
                                if ($listaUsuarios != "") {
                                    $nombreMP = $listaUsuarios["data"][0]["nombre"] . " " . $listaUsuarios["data"][0]["paterno"] . " " . $listaUsuarios["data"][0]["materno"];
                                } else {
                                    $error = true;
                                    echo "NO SE ENCONTRO INFORMACION DEL MINISTERIO PUBLICO EN EL SISTEMA DE GESTION";
                                }
                                $nombreMP = strtoupper($nombreMP);
                                $juzgadores[$count]["nombre"] = $nombreMP;
                                $juzgadores[$count]["numeroMuestra"] = $muestraDto->getNumeroMuestra();
                                $juzgadores[$count]["anioMuestra"] = $muestraDto->getAnioMuestra();
                                $juzgadores[$count]["juzgado"] = $JuzgadosDto->getDesJuzgado();
                                $juzgadores[$count]["idMuestra"] = $muestraDto->getIdMuestra();
                                $juzgadores[$count]["idjuzgado"] = $JuzgadosDto->getCveJuzgado();
                                $juzgadores[$count]["idJuzgador"] = $juzgadorMuestra[0]->getCveUsuario();
                            }
                            $count++;
                        } else {
                            error_log("NO SE ENCONTRO JUZGADOR: " . $muestraSolicitud->getIdSolicitudMuestra());
                        }
                    }
                }
            }
        } else {
            error_log("NO SE ENCONTRARON CATEOS -- CRON JOB");
        }

        // Envia Email a Jueces
        $idsJuzgadosArray = array();
        if (count($juzgadores) != 0) {

            // -->Body para Coordinacion
            $bodyCoordinacion = "";
            foreach ($juzgadores as $juzgadorCord) {
                $bodyCoordinacion.="Num. Muestra: " . str_pad($juzgadorCord["numeroMuestra"], 6, '0', STR_PAD_LEFT) . "/" . $juzgadorCord["anioMuestra"] . "<br>";
                $bodyCoordinacion.="Fecha registro: " . $juzgadorCord["fecha"] . "<br>";
                $bodyCoordinacion.="Hora registro: " . $juzgadorCord["horaMuestra"] . "<br>";
                $bodyCoordinacion.="Juzgado: " . $juzgadorCord["juzgado"] . "<br>";
                $bodyCoordinacion.="Juez: " . $juzgadorCord["juzgador"] . "<br>";
                $bodyCoordinacion.="Telefonos: " . str_replace(["\"", "[", "]"], "", json_encode($juzgadorCord["telefono"])) . "<br>";
                $bodyCoordinacion.="Correos Electronicos: " . str_replace(["\"", "[", "]"], "", json_encode($juzgadorCord["email"])) . "<br>";
                $bodyCoordinacion.="==================================================<br />";
            }

            foreach ($juzgadores as $juzgador) {
                $idJuzgado = $juzgador["idjuzgado"];
                $body = "<b>NOTIFICACI&Oacute;N</b>. En Toluca, M&eacute;xico, siendo las <b>" . $juzgador["horaMuestra"] .
                        "</b> horas del d&iacute;a <b>" . $juzgador["fecha"] . "</b>, mediante el sistema inform&aacute;tico (SIGEJUPE),  el <b>C. " . $juzgador["nombre"] . "</b>, Agente del Ministerio P&uacute;blico, 
                        formul&oacute; ante el <b>" . $juzgador["juzgado"] . "</b>, la solicitud de Toma de Muestras n&uacute;mero <b>" . str_pad($juzgador["numeroMuestra"], 6, '0', STR_PAD_LEFT) . "/" . $juzgador["anioMuestra"] . "</b>, 
                        la cual se encuentra en el referido sistema inform&aacute;tico para su consulta y atenci&oacute;n respectiva.";
                $subject = "Solicitud de Toma de Muestra - JUEZ";

                // --> Enviamos Email al Juzgador
                foreach ($juzgador["email"] as $email) {
                    $this->sendMail($email, $juzgador["juzgador"], $body, $this->objDatCorreo, $juzgador, $subject);
                }

                if (!in_array($idJuzgado, $idsJuzgadosArray)) {
                    // --> Obtenemos Informacion de Administracion
                    $idsJuzgadosArray[] = $idJuzgado;
                    $admons = $this->usuariosCliente->selectUsuariosGrupoSistema(97, 38, $idJuzgado);
                    $admons = json_decode($admons, true);
                    if (isset($admons["type"]) == "OK") {
                        foreach ($admons["usuarios"] as $usuarios) {
                            $nombre = strtoupper(utf8_decode($usuarios["Nombre"] . " " . $usuarios["Paterno"] . " " . $usuarios["Materno"]));
                            // --> Enviamos Email a Administradores
                            if ($usuarios["email"] != "") {
                                $subjectAdmin = "Solicitud de Toma de Muestra - ADMINISTRADOR";
                                $this->sendMail($usuarios["email"], $nombre, $body, $this->objDatCorreo, $juzgador, $subjectAdmin);
                            }
                        }
                    }

                    // --> Obtenemos Informacion de Coordinacion
                    $coordinacion = $this->usuariosCliente->selectUsuariosGrupoSistema(133, 38, $idJuzgado);
                    $coordinacion = json_decode($coordinacion, true);
                    if (isset($coordinacion["type"]) == "OK") {
                        foreach ($coordinacion["usuarios"] as $usuarioCoordinacion) {
                            $nombreCoordinacion = strtoupper(utf8_decode($usuarioCoordinacion["Nombre"] . " " .
                                            $usuarioCoordinacion["Paterno"] . " " . $usuarioCoordinacion["Materno"]));

                            // --> Enviamos Email a Administradores
                            if ($usuarioCoordinacion["email"] != "") {
                                $subjectCordinacion = "Solicitudes de Toma de Muestra - COORDINACION";
                                $this->sendMail($usuarioCoordinacion["email"], $nombreCoordinacion, $bodyCoordinacion, $this->objDatCorreo, $juzgador, $subjectCordinacion);
                            }
                        }
                    }
                }
            }
        }
    }

    private function sendMail($email, $name, $body, $objDatCorreo, $param, $subject) {
        $emailLibrary = new EmailHELPER();
        $emailLibrary->setSenderAddress($objDatCorreo->CorreoRemite);
        $emailLibrary->setToAddress($email);
        $emailLibrary->setToName($name);
        $emailLibrary->setSubject($subject);
        $emailLibrary->setHostSmtp($objDatCorreo->IpSMTP);
        $emailLibrary->setPortSmtp($objDatCorreo->PortSMTP);
        $emailLibrary->setIsHTMLFormat(true);
        $strCuerpoEmailAdm = $body;
        $emailLibrary->setBody($strCuerpoEmailAdm);
        $intentos = 1;
        $emailLibrary->send();
        $bolStatusMailAdm = $emailLibrary->send();
        $cveEstatusNotificacion = "1";
        if ($bolStatusMailAdm) {
            $cveEstatusNotificacion = "2";
            $movimiento = "SE LOGRO ENVIAR CORREO ELECTRONICO";
        } else {
            $cveEstatusNotificacion = "3";
            $movimiento = "NO SE LOGRO ENVIAR CORREO ELECTRONICO";
        }

        $BitacoranotificacionesDao = new BitacoranotificacionesDAO();
        $BitacoranotificacionesDto = new BitacoranotificacionesDTO();
        $BitacoranotificacionesDto->setCveMedioNotificacion("1"); #1 - CORREO ELECTRONICO
        $BitacoranotificacionesDto->setCveTipoCarpeta(""); #VACIO - NO ES CARPETA
        $BitacoranotificacionesDto->setCveTipoActuacion("25"); #12 - Toma de Muestras
        $BitacoranotificacionesDto->setCveEstatusNotificacion($cveEstatusNotificacion);
        $BitacoranotificacionesDto->setIdReferencia($param["idMuestra"]); #ID DEL CATEO
        $BitacoranotificacionesDto->setNumero($param["numeroMuestra"]); #NUMERO DE CATEO
        $BitacoranotificacionesDto->setAnio($param["anioMuestra"]); #AÑO DEL CATEO
        $BitacoranotificacionesDto->setCvejuzgado($param["idjuzgado"]); #JUZGADO A SOLICITAR
        $BitacoranotificacionesDto->setCveUsuario($param["idJuzgador"]); #Id de el usuario al que se le envio el correo
        $BitacoranotificacionesDto->setMedio($email);
        $BitacoranotificacionesDto->setMovimiento($movimiento);
        $BitacoranotificacionesDto = $BitacoranotificacionesDao->insertBitacoranotificaciones($BitacoranotificacionesDto);
    }

}

$sendEmail = new Email();
$result = $sendEmail->sendEmails();
