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
include_once(dirname(__FILE__) . "/../../../tribunal/correo/EmailHELPER.Class.php");

class Email {

    private $objDatCorreo;
    private $proveedor;
    private $intentos;
    private $logger;
    private $numText;
    private $usuariosCliente;

    public function __construct() {
        $this->objDatCorreo = $this->plantillaCorreo("apelaciones");
        $this->proveedor = new Proveedor("mysql", "sigejupe");
        $this->intentos = 1;
        $this->proveedor->connect();
        $this->logger = new Logger("../", "apelacioncaeosCorreos");
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
        #Obtenemos todas las apelaciones que se encuntren en el status
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

                            $juezApel = new JuzgadoresapelacateosDTO();
                            $juezApelDao = new JuzgadoresapelacateosDAO();
                            $juezApel->setActivo("S");
                            $juezApel->setIdJuzgadorApelaCateo($apelacion->getIdApelacionCateo());
                            $juezApel = $juezApelDao->selectJuzgadoresapelacateos($juezApel);
                            if ($juezApel != "") {
                                $juezApel = $juezApel[0];
                                $juez = new JuzgadoresDTO();
                                $juezDao = new JuzgadoresDAO();
                                $juez->setActivo("S");
                                $juez->setIdJuzgador($juezApel->getIdJuzgador());
                                $juez = $juezDao->selectJuzgadores($juez);
                                if ($juez != "") {
                                    $juez = $juez[0];
                                    $juzgadores[$count]["nombre"] = utf8_encode($juez->getNombre() . " " .
                                            $juez->getPaterno() . " " . $juez->getMaterno());
                                } else {
                                    $juzgadores[$count]["nombre"] = "";
                                }
                            } else {
                                $juzgadores[$count]["nombre"] = "";
                            }

                            $juzgadores[$count]["horaCateo"] = $horaCateo;
                            $juzgadores[$count]["fecha"] = $this->numText->FechaLarga($fecha);
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

        // Envia Email a SECRETARIOS
        if (count($juzgadores) != 0) {

            foreach ($juzgadores as $juzgador) {
                $idJuzgado = $juzgador["idjuzgado"];
                $body = "<b>NOTIFICACI&Oacute;N</b>. En Toluca, M&eacute;xico, siendo las <b>" . $juzgador["horaCateo"] .
                "</b> horas del d&iacute;a <b>" . $juzgador["fecha"] . "</b>, mediante el sistema inform&aacute;tico (SIGEJUPE),  el <b>Juez: " . $juzgador["nombre"] . "</b>, 
                        formul&oacute; ante el <b>" . $juzgador["juzgado"] . "</b>, el acuerdo a la solicitud de apelaci&oacute;n de cateo n&uacute;mero <b>" . str_pad($juzgador["numeroCateo"], 6, '0', STR_PAD_LEFT) . "/" . $juzgador["anioCateo"] . "</b>, 
                        la cual se encuentra en el referido sistema inform&aacute;tico para su consulta y atenci&oacute;n respectiva.";
                $subject = "Acuerdo Apelacion Cateo - SECRETARIO";

                foreach ($juzgador["email"] as $email) {
                    $this->sendMail($email, $juzgador["juzgador"], $body, $this->objDatCorreo, $juzgador, $subject);
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
        $BitacoranotificacionesDto->setCveTipoActuacion("29"); #12 - CATEO
        $BitacoranotificacionesDto->setCveEstatusNotificacion($cveEstatusNotificacion);
        $BitacoranotificacionesDto->setIdReferencia($param["idCateo"]); #ID DEL CATEO
        $BitacoranotificacionesDto->setNumero($param["numeroCateo"]); #NUMERO DE CATEO
        $BitacoranotificacionesDto->setAnio($param["anioCateo"]); #AÑO DEL CATEO
        $BitacoranotificacionesDto->setCvejuzgado($param["idjuzgado"]); #JUZGADO A SOLICITAR
        $BitacoranotificacionesDto->setCveUsuario($param["idJuzgador"]);
        $BitacoranotificacionesDto->setMedio($email);
        $BitacoranotificacionesDto->setMovimiento($movimiento);
        $BitacoranotificacionesDto = $BitacoranotificacionesDao->insertBitacoranotificaciones($BitacoranotificacionesDto);
    }

}

$sendEmail = new Email();
$result = $sendEmail->sendEmails();
