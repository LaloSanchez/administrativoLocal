<?php

/**
 * Incluimos librerias, DAO's y DTO's
 */
date_default_timezone_set("America/Mexico_City");

include_once(dirname(__FILE__) . "/../../../modelos/sigejupe/dto/solicitudesordenes/SolicitudesordenesDTO.Class.php");
include_once(dirname(__FILE__) . "/../../../modelos/sigejupe/dao/solicitudesordenes/SolicitudesordenesDAO.Class.php");

include_once(dirname(__FILE__) . "/../../../modelos/sigejupe/dto/juzgadoresordenes/JuzgadoresordenesDTO.Class.php");
include_once(dirname(__FILE__) . "/../../../modelos/sigejupe/dao/juzgadoresordenes/JuzgadoresordenesDAO.Class.php");

include_once(dirname(__FILE__) . "/../../../modelos/sigejupe/dto/telefonosjuzgadores/TelefonosjuzgadoresDTO.Class.php");
include_once(dirname(__FILE__) . "/../../../modelos/sigejupe/dao/telefonosjuzgadores/TelefonosjuzgadoresDAO.Class.php");

include_once(dirname(__FILE__) . "/../../../modelos/sigejupe/dto/juzgadores/JuzgadoresDTO.Class.php");
include_once(dirname(__FILE__) . "/../../../modelos/sigejupe/dao/juzgadores/JuzgadoresDAO.Class.php");

include_once(dirname(__FILE__) . "/../../../modelos/sigejupe/dto/bitacoranotificaciones/BitacoranotificacionesDTO.Class.php");
include_once(dirname(__FILE__) . "/../../../modelos/sigejupe/dao/bitacoranotificaciones/BitacoranotificacionesDAO.Class.php");

include_once(dirname(__FILE__) . "/../../../modelos/sigejupe/dto/ordenes/OrdenesDTO.Class.php");
include_once(dirname(__FILE__) . "/../../../modelos/sigejupe/dao/ordenes/OrdenesDAO.Class.php");

include_once(dirname(__FILE__) . "/../../../modelos/sigejupe/dto/juzgados/JuzgadosDTO.Class.php");
include_once(dirname(__FILE__) . "/../../../modelos/sigejupe/dao/juzgados/JuzgadosDAO.Class.php");

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
        $this->objDatCorreo = $this->plantillaCorreo("ordenes");
        $this->proveedor = new Proveedor("mysql", "sigejupe");
        $this->intentos = 1;
        $this->proveedor->connect();
        $this->logger = new Logger("../", "ordenesCorreos");
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
//        $xml = simplexml_load_file("/database/html/sigejupev2/tribunal/correo/servidorCorreo.xml");
        $xml = simplexml_load_file("./../../../tribunal/correo/servidorCorreo.xml");
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
        $solicitudOrden = new SolicitudesordenesDTO();
        $solicitudOrdenDao = new SolicitudesordenesDAO();
        $orden = " WHERE cveEstatusSolicitudOrden in (1,2)";
        $solicitudOrden = $solicitudOrdenDao->selectSolicitudesordenes($solicitudOrden, $orden);
        
        $juzgadores = array();
        if ($solicitudOrden != "") {
            $count = 0;
            foreach ($solicitudOrden as $ordenSolicitud) {
                // Buscamos la Relacion Juez - ORDEN DE APREHNSION por medio de la solicitud de ORDEN DE AREHENSION
                $juzgadoresDto = new JuzgadoresordenesDTO();
                $juzgadoresDto->setIdSolicitudOrden($ordenSolicitud->getIdSolicitudOrden());
                $juzgadoresDto->setActivo("S");
                $juzgadoresDao = new JuzgadoresordenesDAO();
                $juzgadoresDto = $juzgadoresDao->selectJuzgadoresordenes($juzgadoresDto);

                // Obatenemos la orden de Aprehension 
                $ordenDto = new OrdenesDTO();
                $ordenDao = new OrdenesDAO();
                $ordenDto->setIdSolicitudOrden($ordenSolicitud->getIdSolicitudOrden());
                $ordenDto = $ordenDao->selectOrdenes($ordenDto);
                $ordenDto = $ordenDto[0];

                // Obtenemos el Juzgado
                $JuzgadosDao = new JuzgadosDAO();
                $JuzgadosDto = new JuzgadosDTO();
                $JuzgadosDto->setCveJuzgado($ordenSolicitud->getCveJuzgadoDesahoga()); #Id del juzgado a solicitar
                $JuzgadosDto->setActivo("S");
                $JuzgadosDto = $JuzgadosDao->selectJuzgados($JuzgadosDto);
                if ($JuzgadosDto != "" && count($JuzgadosDto) > 0) {
                    $JuzgadosDto = $JuzgadosDto[0];
                }

                if ($juzgadoresDto != "") {
                    foreach ($juzgadoresDto as $juzgadorRelacion) {
                        // Obtenemos la Informacion del Juez
                        $juzgadorOrden = new JuzgadoresDTO();
                        $juzgadorOrden->setActivo("S");
                        $juzgadorOrden->setIdJuzgador($juzgadorRelacion->getIdJuzgador());
                        $juzgadorOrdenDao = new JuzgadoresDAO();
                        $juzgadorOrden = $juzgadorOrdenDao->selectJuzgadores($juzgadorOrden);

                        if ($juzgadorOrden != "") {

                            $telefonosDto = new TelefonosjuzgadoresDTO();
                            $telefonosDao = new TelefonosjuzgadoresDAO();
                            $telefonosDto->setIdJuzgador($juzgadorOrden[0]->getIdJuzgador());
                            $telefonosDto->setActivo("S");
                            $telefonosDto = $telefonosDao->selectTelefonosjuzgadores($telefonosDto);
                            if ($telefonosDto != "") {
                                $juzgadores[$count]["juzgador"] = $juzgadorOrden[0]->getNombre() . " " . $juzgadorOrden[0]->getPaterno() . " " . $juzgadorOrden[0]->getMaterno();
                                $juzgadores[$count]["email"] = $telefonosDto[0]->getEmail();
                                $juzgadores[$count]["celular"] = $telefonosDto[0]->getCelular();
                                $juzgadores[$count]["telefono"] = $telefonosDto[0]->getTelefono();
                                $fecha = $ordenDto->getFechaRegistro();
                                $horaOrden = explode(' ', $fecha);
                                $horaOrden = $horaOrden[1];
                                $juzgadores[$count]["horaOrden"] = $horaOrden;
                                $juzgadores[$count]["fecha"] = $this->numText->FechaLarga($fecha);
                                $listaUsuarios = "";
                                try {
                                    $UsuarioCliente = new UsuarioCliente();
                                    $listaUsuarios = json_decode($UsuarioCliente->selectUsuarios_In($ordenSolicitud->getCveUsuario()), true);
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
                                $juzgadores[$count]["numeroOrden"] = $ordenDto->getNumeroOrden();
                                $juzgadores[$count]["anioOrden"] = $ordenDto->getAnioOrden();
                                $juzgadores[$count]["juzgado"] = $JuzgadosDto->getDesJuzgado();
                                $juzgadores[$count]["idOrden"] = $ordenDto->getIdOrden();
                                $juzgadores[$count]["idjuzgado"] = $JuzgadosDto->getCveJuzgado();
                            }

                            $count++;
                        } else {
                            echo "NO SE ENCONTRARON JUZGADORES -- SOLICITUD DE ORDEN " . $solicitudOrden->getIdSolicitudOrden();
                        }
                    }
                } else {
                    echo "NO SE ENCONTRARON JUZGADORES -- SOLICITUD DE ORDEN " . $solicitudOrden->getIdSolicitudOrden();
                }
            }
        } else {
            echo "NO SE ENCONTRARON ORDENES ** CRONJOB **";
        }

        // Envia Email a Jueces
        $idsJuzgadosArray = array();
        if (count($juzgadores) != 0) {

            // -->Body para Coordinacion
            $bodyCoordinacion = "";
            foreach ($juzgadores as $juzgadorCord) {
                $bodyCoordinacion.=utf8_encode("Num. Orden de Aprehensión: " . str_pad($juzgadorCord["numeroOrden"], 6, '0', STR_PAD_LEFT) . "/" . $juzgadorCord["anioOrden"] . "<br>");
                $bodyCoordinacion.="Fecha registro: " . $juzgadorCord["fecha"] . "<br>";
                $bodyCoordinacion.="Hora registro: " . $juzgadorCord["horaOrden"] . "<br>";
                $bodyCoordinacion.="Juzgado: " . $juzgadorCord["juzgado"] . "<br>";
                $bodyCoordinacion.="juez: " . $juzgadorCord["juzgador"] . "<br>";
                $bodyCoordinacion.="Celular: " . $juzgadorCord["celular"] . "<br>";
                $bodyCoordinacion.="Telefono: " . $juzgadorCord["telefono"] . "<br>";
                $bodyCoordinacion.="email: " . $juzgadorCord["email"] . "<br>";
                $bodyCoordinacion.="==================================================<br />";
            }
            foreach ($juzgadores as $juzgador) {
                $idJuzgado = $juzgador["idjuzgado"];
                $body = "<b>NOTIFICACI&Oacute;N</b>. En Toluca, M&eacute;xico, siendo las <b>" . $juzgador["horaOrden"] .
                        "</b> horas del d&iacute;a <b>" . $juzgador["fecha"] . "</b>, mediante el sistema inform&aacute;tico (SIGEJUPE),  el <b>C. " . $juzgador["nombre"] . "</b>, Agente del Ministerio P&uacute;blico, 
                        formul&oacute; ante el <b>" . $juzgador["juzgado"] . "</b>, la solicitud de orden de aprehensi&oacute;n n&uacute;mero <b>" . str_pad($juzgador["numeroOrden"], 6, '0', STR_PAD_LEFT) . "/" . $juzgador["anioOrden"] . "</b>, 
                        la cual se encuentra en el referido sistema inform&aacute;tico para su consulta y atenci&oacute;n respectiva.";
                $subject = "Solicitud de Orden de Aprehension - JUEZ";

                // --> Enviamos Email al Juzgador
                $this->sendMail($juzgador["email"], $juzgador["juzgador"], $body, $this->objDatCorreo, $juzgador, $subject);

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
                                $subjectAdmin = "Solicitud de Orden de Aprehension - ADMINISTRADOR";
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
                                $subjectCordinacion = "Solicitudes de Orden de Aprehension - COORDINACION";
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
        $BitacoranotificacionesDto->setCveTipoActuacion("15"); #12 - ORDE DE APREHENSION
        $BitacoranotificacionesDto->setCveEstatusNotificacion($cveEstatusNotificacion);
        $BitacoranotificacionesDto->setIdReferencia($param["idOrden"]); #ID DE LA ORDEN DE APREHENSION
        $BitacoranotificacionesDto->setNumero($param["numeroOrden"]); #NUMERO DE LA ORDEN DE APREHENSION
        $BitacoranotificacionesDto->setAnio($param["anioOrden"]); #AÑO DE LA ORDEN DE APREHENSION
        $BitacoranotificacionesDto->setCvejuzgado($param["idjuzgado"]); #JUZGADO A SOLICITAR
        $BitacoranotificacionesDto->setCveUsuario("46"); #Id de el usuario al que se le envio el correo
        $BitacoranotificacionesDto->setMedio($email);
        $BitacoranotificacionesDto->setMovimiento($movimiento);
        $BitacoranotificacionesDto = $BitacoranotificacionesDao->insertBitacoranotificaciones($BitacoranotificacionesDto);
    }

}

$sendEmail = new Email();
$result = $sendEmail->sendEmails();

