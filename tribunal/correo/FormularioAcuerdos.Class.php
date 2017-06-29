<?php
session_start();
error_reporting( E_ALL );
ini_set( 'display_errors', 1);

include_once(dirname(__FILE__) . "/../../helppers/log4php/config/log4php.php");
include_once(dirname(__FILE__) . "/../../helppers/CabeceraHELPER.Class.php");

$GBLstrAccion = $_POST["Accion"];
$log = Logger::getLogger('sinoe');
$objCabecera = new CabeceraHELPER();

$arrNombreMes = array("","ENERO","FEBRERO","MARZO","ABRIL","MAYO", "JUNIO","JULIO","AGOSTO","SEPTIEMBRE","OCTUBRE","NOVIEMBRE","DICIEMBRE");
$arrNombreDia = array("","DOMINGO","LUNES","MARTES","MIERCOLES", "JUEVES","VIERNES","SABADO");
$realPath = "";
$realPathConf = dirname(__FILE__);
$strDiaIns = date("d",time());
$strMesIns = date("m",time());
$strAnioIns = date("Y",time());
$strHoraIns = date("H",time());
$strMinutosIns = date("i",time());
$intDiaSemana = date("N",time());
$arrServJuzgado = array();
//$sdft = new SimpleDateFormat("HH:mm:ss");
$horaActual = "";

switch ($GBLstrAccion){
    case "listaJuicioJuzgado":
        $objCabecera->CabeceraHTML();
        $log->info("Inicia, FormularioAcuerdos > listaJuicioJuzgado");
        $intCveInstancia = (int)$_SESSION["CveInstancia"];
        switch($intCveInstancia){
            case 3:
            case 4:
                include_once(dirname(__FILE__) . "/../../gestion/Modelo/DTO/JuzgadoDTO.Class.php");
                include_once(dirname(__FILE__) . "/../../gestion/Control/JuzgadosCONTROL.Class.php");
                include_once(dirname(__FILE__) . "/../Modelo/DTO/JuzgadoBoletinDTO.Class.php");
                include_once(dirname(__FILE__) . "/../Control/JuzgadosBoletinCONTROL.Class.php");
                $juzGestionDTO = new JuzgadoDTO();
                $juzGestionCTRL = new JuzgadosCONTROL();
                $juzBoletinDTO = new JuzgadoBoletinDTO();
                $juzBoletinCTRL = new JuzgadosBoletinCONTROL();

                $arrJuzBoletin = $juzBoletinCTRL->listaJuzBoletin($juzBoletinDTO);
                $arrJuzGestion = $juzGestionCTRL->listaJuzGestion();
                
                if(count($arrJuzBoletin) == 0){
                    $recibeHTML = "Sin datos...";
                    $log->info("Sin datos...");
                } else{
                    $log->info("Cargando datos...");
                    $arrCveJuzBoletin = array();
                    for($x=0;$x<count($arrJuzBoletin);$x++){
                        $juzBoletinDTO = $arrJuzBoletin[$x];
                        $arrCveJuzBoletin[$x] = (int)$juzBoletinDTO->getIdJuzgadoGestion();
                    }
                    $recibeHTML = "<select name=\"cmbJuicioJuzgado\" id=\"cmbJuicioJuzgado\" class=\"frmCaja\" onChange=\"\">";
                    $recibeHTML .= "<option value=\"0\" title=\"SELECCIONE JUZGADO\">SELECCIONE JUZGADO</option>";
                    for($x=0;$x<count($arrJuzGestion);$x++) {
                        $juzGestionDTO = $arrJuzGestion[$x];
                        if(in_array($juzGestionDTO->getIdJuzgado(),$arrCveJuzBoletin)){
                            $juzGestionDTO = $arrJuzGestion[$x];
                            $recibeHTML .= "<option value=\"".$juzGestionDTO->getIdJuzgado()."\" title=\"".$juzGestionDTO->getDesJuz()."\">".$juzGestionDTO->getDesJuz()."</option>\n";
                        }
                    }
                    $recibeHTML .= "</select>\n";
                }
                break;
            default:
                include_once(dirname(__FILE__) . "/../Modelo/DTO/TipoJuicioDTO.Class.php");
                include_once(dirname(__FILE__) . "/../Control/TipoJuicioCONTROL.Class.php");
                $tipoJuicioDTO = new TipoJuicioDTO();
                $tipoJuicioCTRL = new TipoJuicioCONTROL();
                $recibeHTML = $tipoJuicioCTRL->consultaTipoJuicio($tipoJuicioDTO);
        }
        
        echo $recibeHTML;
        $log->info("Finaliza, FormularioAcuerdos > listaJuicioJuzgado");
        break;
    case "listaResoluciones":
        $objCabecera->CabeceraHTML();
        $log->info("Inicia, FormularioAcuerdos > listaResoluciones");
        include_once(dirname(__FILE__) . "/../Modelo/DTO/ResolucionesBoletinDTO.Class.php");
        include_once(dirname(__FILE__) . "/../Control/ResolucionesBoletinCONTROL.Class.php");
        
        $resolucionesDTO = new ResolucionesBoletinDTO();
        $resolucionCTRL = new ResolucionesBoletinCONTROL();

        $recibeHTML = $resolucionCTRL->consultaResoluciones($resolucionesDTO);
        echo $recibeHTML;
                                        
        $log->info("Finaliza, FormularioAcuerdos > listaResoluciones");
        break;
    case "obtieneDatosUsuBoletin":
        $objCabecera->CabeceraHTML();
        $log->info("Inicia, FormularioAcuerdos > obtieneDatosUsuBoletin");
        include_once(dirname(__FILE__) . "/../Modelo/DTO/UsuarioSecretariasDTO.Class.php");
        include_once(dirname(__FILE__) . "/../Control/UsuarioSecretariasCONTROL.Class.php");
        
        $intCveUsuario = (int)$_SESSION["CveUsuario"];
        $usuSecretariasDTO = new UsuarioSecretariasDTO();
        $usuSecretariasCTRL = new UsuarioSecretariasCONTROL();
        $recibeHTML = "";

        $usuSecretariasDTO->setCveUsuario($intCveUsuario);

        $arrUsuSecretarias = $usuSecretariasCTRL->consultaUsuarioSecretarias($usuSecretariasDTO);
        if(count($arrUsuSecretarias) == 0){
            $recibeHTML = "Sin datos...";
        } else if(count($arrUsuSecretarias) == 1){
//            $dto = new UsuarioSecretariasDTO();
            $dto = $arrUsuSecretarias[0];
            $recibeHTML  = $dto->getNomSecretaria()."\n";
            $recibeHTML .= "<input type=\"hidden\" name=\"cmbCveSecretaria\" id=\"cmbCveSecretaria\" value=\"".$dto->getCveSecretaria()."\">\n";
        } else if(count($arrUsuSecretarias) > 1){
            $recibeHTML = "<select name=\"cmbCveSecretaria\" id=\"cmbCveSecretaria\" class=\"frmCaja\" onChange=\"cargaListadoAcuerdos();\">\n";
            for($x=0;$x<count($arrUsuSecretarias);$x++){
//                $dto = new UsuarioSecretariasDTO();
                $dto = $arrUsuSecretarias[$x];
                $recibeHTML .= "<option value=\"".$dto->getCveSecretaria()."\">".$dto->getNomSecretaria()."</option>\n";
            }
            $recibeHTML .= "</select>\n";
        }
                                        
        echo $recibeHTML;
                                        
        $log->info("Finaliza, FormularioAcuerdos > obtieneDatosUsuBoletin");
        break;
    case "listaAcuerdosBoletin":
        $log->info("Inicia, FormularioAcuerdos > listaAcuerdosBoletin");
        include_once(dirname(__FILE__) . "/../Modelo/DTO/AcuerdosBoletinDTO.Class.php");
        include_once(dirname(__FILE__) . "/../Control/AcuerdosBoletinCONTROL.Class.php");
        
        $acuerdosBoletinDTO  = new AcuerdosBoletinDTO();
        $acuerdoBoletinCTRL = new AcuerdosBoletinControl();

        $acuerdosBoletinDTO->setFk_idtblJuzgados((int)$_SESSION["CveAdscripcion"]);
        $acuerdosBoletinDTO->setFk_idtblSecretarias((int)$_POST["CveSecretaria"]);
        $acuerdosBoletinDTO->setCveUsuario((int)$_SESSION["CveUsuario"]);
        $acuerdosBoletinDTO->setFechaAlta($_POST["FechaAlta"]);
//        $acuerdosBoletinDTO->setFechaAlta('2012-08-22');
        
        $XML = $acuerdoBoletinCTRL->listadoAcuerdosBoletinXML($acuerdosBoletinDTO);
        $log->info("Finaliza, FormularioAcuerdos > listaAcuerdosBoletin");
        echo $XML;
        break;
    case "consultarAcuerdo":
        include_once(dirname(__FILE__) . "/../Modelo/DTO/AcuerdosBoletinDTO.Class.php");
        include_once(dirname(__FILE__) . "/../Control/AcuerdosBoletinCONTROL.Class.php");
        include_once(dirname(__FILE__) . "/../../helppers/BitacoraHELPER.Class.php");
        
        $log->info("Inicia, FormularioAcuerdos > consultarAcuerdo");
        $bitacoraHELPER = new BitacoraHELPER();
        $strXML = "";
//        $objCabecera->CabeceraXML();
        $acuerdosBoletinDTO  = new AcuerdosBoletinDTO();

        $acuerdosBoletinDTO->setIdtblAcuerdos($_POST["idAcuerdo"]);

        $acuerdoBoletinCTRL = new AcuerdosBoletinCONTROL();
        $strXML = $acuerdoBoletinCTRL->consultarAcuerdoXML($acuerdosBoletinDTO);

        $bitacoraHELPER->setIdAccion(5);
        $bitacoraHELPER->setCveUsuario($_SESSION["CveUsuario"]);
        if(strlen($strXML) == 0){
                $bitacoraHELPER->setDescripcion("Acuerdo no existe: idAcuerdo[".$acuerdosBoletinDTO->getIdtblAcuerdos()."]");
                $strXML = "<Resultado>\n<Error> No se pudo consultar el acuerdo. Intentelo más tarde.</Error>\n</Resultado>";
        } else{
                $bitacoraHELPER->setDescripcion("Acuerdo consultado: idAcuerdo[".$acuerdosBoletinDTO->getIdtblAcuerdos()."]");
        }

        if($bitacoraHELPER->guardaBitacora()){
                $log->info("Registro en BITACORA correcto");
        } else{
                $log->info("Registro en BITACORA incorrecto");
        }
       
        $log->info("Finaliza, FormularioAcuerdos > consultarAcuerdo");
        echo $strXML;
        break;
    case "borrarAcuerdo":
        include_once(dirname(__FILE__) . "/../Control/ServiciosJuzgadoCONTROL.Class.php");
        include_once(dirname(__FILE__) . "/../Modelo/DTO/ServiciosJuzgadoDTO.Class.php");
        include_once(dirname(__FILE__) . "/../Modelo/DTO/AcuerdosBoletinDTO.Class.php");
        include_once(dirname(__FILE__) . "/../Control/AcuerdosBoletinCONTROL.Class.php");
        include_once(dirname(__FILE__) . "/../../helppers/BitacoraHELPER.Class.php");

        $log->info("Inicia, FormularioAcuerdos > borrarAcuerdo");
        /******************* Carga Servicios del Juzgado *********************/
        $servJuzCTRL = new ServiciosJuzgadoCONTROL();
        $servJuzDTO = new ServiciosJuzgadoDTO();

        $servJuzDTO->setIdJuzgadoGestion($_SESSION["CveAdscripcion"]);
        $servJuzDTO->setIdtblServicios(3);

        $arrServJuzgado = $servJuzCTRL->arrServJuzDTO($servJuzDTO);

        $bitacoraHELPER = new BitacoraHELPER();
        $RecibeHMTL = "";

        if(count($arrServJuzgado) > 0){

            $servServicioDTO = $arrServJuzgado[0];

            $horaServicio = strtotime($servServicioDTO->getHoraFin());
            if(time() < $horaServicio){
                    $acuerdosBoletinDTO  = new AcuerdosBoletinDTO();

                    $acuerdosBoletinDTO->setIdtblAcuerdos($_POST["idAcuerdo"]);

                    $acuerdoBoletinCTRL = new AcuerdosBoletinControl();
                    $acuerdosBoletinDTO = $acuerdoBoletinCTRL->borrarAcuerdo($acuerdosBoletinDTO);

                    $bitacoraHELPER->setIdAccion(4);
//                    $bitacoraHELPER->setCveUsuario($acuerdosBoletinDTO->getCveUsuario());
                    $bitacoraHELPER->setCveUsuario($_SESSION["CveUsuario"]);
                    if($acuerdosBoletinDTO->getActivo() == 0){
                            $bitacoraHELPER->setDescripcion($acuerdosBoletinDTO->toString());
                            $RecibeHMTL = "<OK>Acuerdo eliminado correctamente.".date("H:i:s",time())." < ".date("H:i:s",$horaServicio);
                    } else{
                            $bitacoraHELPER->setDescripcion("Error al eliminar el acuerdo: ".$_POST["idAcuerdo"]);
                            $RecibeHMTL = "<Error>No se pudo eliminar el acuerdo.\nIntentelo más tarde.";
                    }
                    if($bitacoraHELPER->guardaBitacora()){
                            $log->info("Registro en BITACORA correcto");
                    } else{
                            $log->info("Registro en BITACORA incorrecto");
                    }
            } else{
                    $RecibeHMTL = "<Error>No se pudo eliminar el acuerdo.\nHora limite para modificar la capturar: ".$servServicioDTO->getHoraFin()." hrs.";
            }
        } else{
                $RecibeHMTL = "<Error>No se pudo eliminar el acuerdo.\nNo cuenta con permisos para este servicio.";
        }
        $log->info("Finaliza, FormularioAcuerdos > consultarAcuerdo");
        
        echo $RecibeHMTL;
        break;
    case "buscaAcuerdoBoletin":
        include_once(dirname(__FILE__) . "/../Modelo/DTO/AcuerdosBoletinDTO.Class.php");
        include_once(dirname(__FILE__) . "/../Control/AcuerdosBoletinCONTROL.Class.php");
                
        $log->info("Inicia, FormularioAcuerdos > buscaAcuerdoBoletin");
        $RecibeHMTL = "";
        $acuerdosBoletinDTO  = new AcuerdosBoletinDTO();
        $acuerdoBoletinCTRL = new AcuerdosBoletinCONTROL();

        $acuerdosBoletinDTO->setIdtblAcuerdos(0);
        $acuerdosBoletinDTO->setFk_idtblJuzgados($_SESSION["CveAdscripcion"]);
        $acuerdosBoletinDTO->setCveUsuario($_SESSION["CveUsuario"]);
        $acuerdosBoletinDTO->setNumExpediente($_POST["NumExp"]);
        $acuerdosBoletinDTO->setAnioExpediente($_POST["AnioExp"]);
        $acuerdosBoletinDTO->setObservaciones($_POST["TipoDoc"]);
        $acuerdosBoletinDTO = $acuerdoBoletinCTRL->buscaAcuerdoBoletin($acuerdosBoletinDTO);

        if($acuerdosBoletinDTO->getIdtblAcuerdos() > 0){
            $acuerdosBoletinDTO->setActores(utf8_encode($acuerdosBoletinDTO->getActores()));
            $acuerdosBoletinDTO->setDemandados(utf8_encode($acuerdosBoletinDTO->getDemandados()));
            $RecibeHMTL = $acuerdosBoletinDTO->toJSON();
        } else{
            $RecibeHMTL = "<Error>No se encontro antecedente.";
        }
        $log->info("Finaliza, FormularioAcuerdos > consultarAcuerdo");
        echo $RecibeHMTL;
        break;
    case "encabezadoAcuerdosBoletin":
        include_once(dirname(__FILE__) . "/../Modelo/DTO/SecretariaBoletinDTO.Class.php");
        include_once(dirname(__FILE__) . "/../Control/SecretariaBoletinCONTROL.Class.php");
        
        $log->info("Inicia, FormularioAcuerdos > encabezadoAcuerdosBoletin");
        $RecibeHMTL = "";
        $secreBoletinDTO = new SecretariaBoletinDTO();
        $secreBoletinCTRL = new SecretariaBoletinCONTROL();

        $secreBoletinDTO->setIdtblSecretarias($_POST["CveSecretaria"]);
        $secreBoletinDTO = $secreBoletinCTRL->consultaSecreBoletin($secreBoletinDTO);

        $RecibeHMTL  = "<table cellpadding=\"0\" celspacing=\"0\" border=\"0\" width=\"100%\" class=\"Arial12 tblAcuse\" bgcolor=\"#FFFFFF\">";
                $RecibeHMTL .= "<tr>\n";
                        $RecibeHMTL .= "<td width=\"50\"><img src=\"../img/LogoEMmin.gif\" border=\"0\"></td>\n";
                        $RecibeHMTL .= "<td width=\"275\" align=\"left\" valign=\"middle\">\n";
                        $RecibeHMTL .= "GOBIERNO DEL ESTADO DE MÉXICO<br>\n";
                        $RecibeHMTL .= "PODER JUDICIAL DEL ESTADO DE MÉXICO<br>\n";
                        $RecibeHMTL .= "CONSEJO DE LA JUDICATURA\n";
                        $RecibeHMTL .= "</td>\n";
                        $RecibeHMTL .= "<td width=\"275\" align=\"right\" valign=\"bottom\">\n";
                        $RecibeHMTL .= $arrNombreDia[$intDiaSemana].", ".$strDiaIns." DE ".$arrNombreMes[$strMesIns]." DE ".$strAnioIns;
                        $RecibeHMTL .= "</td>\n";
                        $RecibeHMTL .= "<td width=\"50\"><img src=\"../img/LogoPJmin.gif\" border=\"0\"></td>\n";
                $RecibeHMTL .= "</tr>\n";
                $RecibeHMTL .= "<tr>\n";
                        $RecibeHMTL .= "<td colspan=\"4\">";
                        $RecibeHMTL .= "JUZGADO: <b>".$_SESSION["Adscripcion"]."</b><br>\n";
                        $RecibeHMTL .= "SECRETARIA: <b>".$secreBoletinDTO->getNombre()."</b><br>\n";
                        $RecibeHMTL .= "C. NOTIFICADOR: <b>".$_SESSION["Usuario"]."</b><br>\n";
                        $RecibeHMTL .= "ACUERDOS DEL DÍA: <b>".$strDiaIns."/".$strMesIns."/".$strAnioIns."</b>\n";
                        $RecibeHMTL .= "</td>\n";
                $RecibeHMTL .= "</tr>\n";
        $RecibeHMTL .= "</table>\n";
        
        $log->info("Finaliza, FormularioAcuerdos > encabezadoAcuerdosBoletin");
        echo $RecibeHMTL;
        break;
    case "verMostrarAcuse":
        $log->info("Inicia, FormularioAcuerdos > verMostrarAcuse");
        include_once(dirname(__FILE__) . "/../Modelo/DTO/AcuerdosBoletinDTO.Class.php");
        include_once(dirname(__FILE__) . "/../Control/AcuerdosBoletinCONTROL.Class.php");
        include_once(dirname(__FILE__) . "/../../helppers/BitacoraHELPER.Class.php");
        
        $bitacoraHELPER = new BitacoraHELPER();
        $acuerdosBoletinDTO  = new AcuerdosBoletinDTO();
        $acuerdoBoletinCTRL = new AcuerdosBoletinCONTROL();

        $acuerdosBoletinDTO->setFk_idtblJuzgados($_SESSION["CveAdscripcion"]);
        $acuerdosBoletinDTO->setFk_idtblSecretarias($_POST["CveSecretaria"]);
        $acuerdosBoletinDTO->setCveUsuario($_SESSION["CveUsuario"]);
        $acuerdosBoletinDTO->setFechaAlta($_POST["FechaAlta"]);
        $XML = $acuerdoBoletinCTRL->verMostrarAcuseXML($acuerdosBoletinDTO);

        if($XML != ""){
                $bitacoraHELPER->setIdAccion(8);
                $bitacoraHELPER->setCveUsuario($_SESSION["CveUsuario"]);
                $bitacoraHELPER->setDescripcion("FechaAlta[".$_POST["FechaAlta"]."] Secretaria[".$_POST["CveSecretaria"]."] CveJuzGestion[".$_SESSION["CveAdscripcion"]."]");
                if($bitacoraHELPER->guardaBitacora()){
                        $log->info("Registro en BITACORA correcto");
                } else{
                        $log->info("Registro en BITACORA incorrecto");
                }

                echo $XML;
        }
        $log->info("Finaliza, FormularioAcuerdos > verMostrarAcuse");
        break;
    case "verMostrarAcuerdos":
        $log->info("Inicia, FormularioAcuerdos > verMostrarAcuerdos");
        include_once(dirname(__FILE__) . "/../Modelo/DTO/AcuerdosBoletinDTO.Class.php");
        include_once(dirname(__FILE__) . "/../Control/AcuerdosBoletinCONTROL.Class.php");
        include_once(dirname(__FILE__) . "/../../helppers/BitacoraHELPER.Class.php");
        
        $bitacoraHELPER = new BitacoraHELPER();
        $acuerdosBoletinDTO  = new AcuerdosBoletinDTO();
        $acuerdoBoletinCTRL = new AcuerdosBoletinCONTROL();

        $acuerdosBoletinDTO->setFk_idtblJuzgados($_SESSION["CveAdscripcion"]);
        $acuerdosBoletinDTO->setFk_idtblSecretarias($_POST["CveSecretaria"]);
        $acuerdosBoletinDTO->setCveUsuario($_SESSION["CveUsuario"]);
        $acuerdosBoletinDTO->setFechaAlta($_POST["FechaAlta"]);
        $XML = $acuerdoBoletinCTRL->verMostrarAcuerdosXML($acuerdosBoletinDTO);

        if($XML != ""){
                $bitacoraHELPER->setIdAccion(7);
                $bitacoraHELPER->setCveUsuario($_SESSION["CveUsuario"]);
                $bitacoraHELPER->setDescripcion("FechaAlta[".$_POST["FechaAlta"]."] Secretaria[".$_POST["CveSecretaria"]."] CveJuzGestion[".$_SESSION["CveAdscripcion"]."]");
                if($bitacoraHELPER->guardaBitacora()){
                        $log->info("Registro en BITACORA correcto");
                } else{
                        $log->info("Registro en BITACORA incorrecto");
                }

                echo $XML;
        }
        $log->info("Finaliza, FormularioAcuerdos > verMostrarAcuerdos");
        break;
    case "guardaAcuerdo":
        include_once(dirname(__FILE__) . "/../../helppers/BitacoraHELPER.Class.php");
        include_once(dirname(__FILE__) . "/../../helppers/NumeroALetraHELPER.Class.php");
        include_once(dirname(__FILE__) . "/../../helppers/EmailHELPER.Class.php");
        include_once(dirname(__FILE__) . "/../../helppers/AnalizadorWordHELPER.Class.php");
        include_once(dirname(__FILE__) . "/../../helppers/SelloDigitalHELPER.Class.php");
        include_once(dirname(__FILE__) . "/../Modelo/DTO/AcuerdosBoletinDTO.Class.php");
        include_once(dirname(__FILE__) . "/../Control/AcuerdosBoletinCONTROL.Class.php");
        include_once(dirname(__FILE__) . "/../Modelo/DTO/ServiciosJuzgadoDTO.Class.php");
        include_once(dirname(__FILE__) . "/../Control/ServiciosJuzgadoCONTROL.Class.php");
        include_once(dirname(__FILE__) . "/../Modelo/DTO/EstadoEnvioDTO.Class.php");
        include_once(dirname(__FILE__) . "/../Control/EstadoEnvioCONTROL.Class.php");
        include_once(dirname(__FILE__) . "/../Modelo/DTO/PersonaNotificarDTO.Class.php");
        include_once(dirname(__FILE__) . "/../Control/PersonaNotificarCONTROL.Class.php");
        include_once(dirname(__FILE__) . "/../Modelo/DTO/TipoPersonaAsuntoDTO.Class.php");
        include_once(dirname(__FILE__) . "/../Control/TipoPersonaAsuntoCONTROL.Class.php");
        
        $log->info("Inicia, FormularioAcuerdos > guardaAcuerdo");

        $propAcuerdo = array("idDoc"=>"0","URLDoc"=>"");
        $bolAdjunto = false;
        $bolBorrarAdjunto = true;
        
        $servJuzCTRL = new ServiciosJuzgadoCONTROL();
        $servJuzDTO = new ServiciosJuzgadoDTO();
        $servJuzDTO->setIdJuzgadoGestion($_SESSION["CveAdscripcion"]);
        $servJuzDTO->setIdtblServicios(3); // 3 = Captura Acuerdos Boletín

        $arrServJuzgado = $servJuzCTRL->arrServJuzDTO($servJuzDTO);

        $strInstructivo = utf8_decode(plantillaInstructivo());
        

        if(count($arrServJuzgado) > 0){
            $NumLetra = new NumeroALetraHELPER();
            $NumLetra->setPrefijo("");
            $NumLetra->setSufijo("");
            $NumLetra->setMoneda("");
            $NumLetra->setVerCentavos(false);

            $servServicioDTO = $arrServJuzgado[0];
            $horaServicio = strtotime($servServicioDTO->getHoraFin());
            if(time() < $horaServicio){

                $strJuzgado = $_SESSION["CveAdscripcion"];
                $strCveInstancia = $_SESSION["CveInstancia"];
                $acuerdosBoletinDTO  = new AcuerdosBoletinDTO();

                $acuerdosBoletinDTO->setIdtblAcuerdos(0);
                $acuerdosBoletinDTO->setFk_idtblJuzgados($strJuzgado);
                $acuerdosBoletinDTO->setFk_idtblSecretarias($_POST["cmbCveSecretaria"]);
                switch($strCveInstancia){
                    case 3:
                    case 4:
                        $acuerdosBoletinDTO->setFk_idtblJuzgadoPro($_POST["cmbJuicioJuzgado"]);
                        $acuerdosBoletinDTO->setFk_idtblTipoJuicio(0);
                        break;
                    default:
                        $acuerdosBoletinDTO->setFk_idtblJuzgadoPro(0);
                        $acuerdosBoletinDTO->setFk_idtblTipoJuicio($_POST["cmbJuicioJuzgado"]);
                }
                $acuerdosBoletinDTO->setFk_idtblResoluciones($_POST["cmbResolucion"]);
                $acuerdosBoletinDTO->setFk_idtblEstatusBoletin(1);
                $acuerdosBoletinDTO->setFk_idtblIntegrarBoletin(0);
                $acuerdosBoletinDTO->setCveAcuerdo(0);
                $acuerdosBoletinDTO->setCveUsuario($_SESSION["CveUsuario"]);
                $acuerdosBoletinDTO->setCveAcuerdoExpVirtual(0);
                $acuerdosBoletinDTO->setNumExpediente($_POST["txtNumExp"]);
                $acuerdosBoletinDTO->setAnioExpediente($_POST["txtAnioExp"]);
                $acuerdosBoletinDTO->setFechaPublicacion($_POST["txtFechaPub"]);
                $acuerdosBoletinDTO->setObservaciones(utf8_decode($_POST["txtObservaciones"]));
                $acuerdosBoletinDTO->setActores(utf8_decode($_POST["txtActor"]));
                $acuerdosBoletinDTO->setDemandados(utf8_decode($_POST["txtDemandado"]));
                $acuerdosBoletinDTO->setActivo(1);
                $acuerdosBoletinDTO->setNumAcuerdos($_POST["txtNumAcuerdos"]);
                $NumLetra->setNumero((int)$acuerdosBoletinDTO->getNumAcuerdos());
                $acuerdosBoletinDTO->setNumAcuerdosLetra(trim($NumLetra->letra()));

                $acuerdoBoletinCTRL = new AcuerdosBoletinCONTROL();
                $acuerdosBoletinDTO = $acuerdoBoletinCTRL->guardaAcuerdoBoletin($acuerdosBoletinDTO);

                if($acuerdosBoletinDTO->getIdtblAcuerdos() > 0){
                    $tmpEstadoEnvioDTO = new EstadoEnvioDTO();
                    $estadoEnvioControl = new EstadoEnvioCONTROL();

                    $bitacoraHELPER = new BitacoraHELPER();
                    $bitacoraHELPER->setIdAccion(3);
                    $bitacoraHELPER->setCveUsuario($_SESSION["CveUsuario"]);
                    $bitacoraHELPER->setDescripcion("idAcuerdo[".$acuerdosBoletinDTO->getIdtblAcuerdos()."] Expediente[".$acuerdosBoletinDTO->getNumExpediente()."/"+$acuerdosBoletinDTO->getAnioExpediente()."] CveJuzGestion["+$_SESSION["CveAdscripcion"]."] CveJuzBoletin[".$acuerdosBoletinDTO->getFk_idtblJuzgados()."]");
                    if($bitacoraHELPER->guardaBitacora()){
                            $log->info("guardaAcuerdo > Registro en BITACORA correcto");
                    } else{
                            $log->info("guardaAcuerdo > Registro en BITACORA incorrecto");
                    }
                    if(strlen($_POST["hidPersonasAuto"]) > 0){

                        $tipoPerAsuntoControl = new TipoPersonaAsuntoCONTROL();
                        $arrPersonasAuto = explode(",",$_POST["hidPersonasAuto"]);
                        $textoArchivo = new AnalizadorWord();

                        $strDocExpVirtual = $_POST["rdbDocAdjunto"];

                        if($strDocExpVirtual == "0"){
                            $strDocAdjTamano = $_FILES["fileDocAdjunto"]['size'];
                            $strDocAdjTipo = $_FILES["fileDocAdjunto"]['type'];
                            $strDocAdjArchivo = $_FILES["fileDocAdjunto"]['name'];
                            $arrPartes = explode(".",$strDocAdjArchivo);
                            $extension = $arrPartes[count($arrPartes)-1];
                            $realPathAndFileNew = "../../vista/adjuntos/".$acuerdosBoletinDTO->getNumExpediente()."-".$acuerdosBoletinDTO->getAnioExpediente()."-".$acuerdosBoletinDTO->getFk_idtblJuzgados()."-".$acuerdosBoletinDTO->getIdtblAcuerdos().".".$extension;
                            if (move_uploaded_file($_FILES['fileDocAdjunto']['tmp_name'],$realPathAndFileNew)){
                                chmod($realPathAndFileNew,0777);
                                echo "Archivo subido: <b>".$realPathAndFileNew."</b><br>\n";
                                $textoArchivo->setNombreFichero($realPathAndFileNew);
                                $textoArchivo->setDocURL(false);
                                $textoArchivoDoc = $textoArchivo->extraeTexto();
                                $textoArchivoDoc = utf8_encode(trim($textoArchivoDoc));
                                if(strlen($textoArchivoDoc) > 0){
                                    if($bolBorrarAdjunto) borraArchivo($realPathAndFileNew);
                                } else{
                                    $bolAdjunto = true;
                                }
                            } else {
                                echo "Error al subir el archivo<br>\n";
                            }
                            
                        } else{
                                $arrDocExpVir = explode("-",$strDocExpVirtual);
                                $propAcuerdo["idDoc"] = $arrDocExpVir[0];
                                $propAcuerdo["URLDoc"] = $arrDocExpVir[1];
                                $textoArchivo->setNombreFichero($propAcuerdo["URLDoc"]);
                                $textoArchivo->setDocURL(true);
                                $textoArchivoDoc = $textoArchivo->extraeTexto();
                                $textoArchivoDoc = utf8_encode(trim($textoArchivoDoc)); //htmlspecialchars
                                if(strlen($textoArchivoDoc) > 0){
                                    //
                                } else{
                                    $arrPartesArchivo = explode("/",$propAcuerdo["URLDoc"]);
                                    $realPathAndFileNew = "../../vista/adjuntos/".$arrPartesArchivo[count($arrPartesArchivo)-1];
                                    if (copy("http://".$propAcuerdo["URLDoc"],$realPathAndFileNew)){
                                        chmod($realPathAndFileNew,0777);
                                        echo "Archivo subido: <b>".$realPathAndFileNew."</b><br>\n";
                                        $bolAdjunto = true;
                                    } else {
                                        echo "Error al subir el archivo<br>\n";
                                    }
                                }
                        }
//
                        for($i = 0; $i < count($arrPersonasAuto); $i++){
                            unset($arrPersona);
                            unset($arrCorreos);
                            $arrPersona = explode(";",$arrPersonasAuto[$i]);
                            $arrCorreos = explode(":",$arrPersona[1]);
//                                $strDatosExpediente  = "";
//                                $strCadenaOri = "";
//                                $strSelloDig = "";
//                                $strSelloDigital = "";
                            $personaNotificarDTO  = new PersonaNotificarDTO();
                            $personaNotificarControl = new PersonaNotificarCONTROL();
                            $tipoPersonaAsuntoDTO =  new TipoPersonaAsuntoDTO();

                            $tipoPersonaAsuntoDTO = $tipoPerAsuntoControl->consultaTipoPersona($arrPersona[3]);

                            $servServicioDTO = $arrServJuzgado[0];
                            $strSelloDig = "";
                            $horaServicio = strtotime($servServicioDTO->getHoraFin());
                            if(time() < $horaServicio){
                                $strCadenaOri = "||".$acuerdosBoletinDTO->getNumExpediente()."/".$acuerdosBoletinDTO->getAnioExpediente()."/".$_SESSION["CveAdscripcion"]."/".$acuerdosBoletinDTO->getIdtblAcuerdos()."|".$_SESSION["Adscripcion"]."|". utf8_decode($arrPersona[2])."|".str_replace(":", "; ",$arrPersona[1])."|".strtoupper($tipoPersonaAsuntoDTO->getDescripcion())."|".$strDiaIns."/".$strMesIns."/".$strAnioIns."|".$strHoraIns.":".$strMinutosIns."||";
                                
                                $sello = new SelloDigitalHELPER();
                                $strLlavePrivada = extraeLlavePrivada("keystoreTSJEDOMEX.key.pem");
                                $sello->setPrivKey($strLlavePrivada);
                                $sello->setCadenaOriginal($strCadenaOri);
                                $strSelloDig = $sello->getSelloDigital();
                                $personaNotificarDTO->setCadOriginal($strCadenaOri);
                                $personaNotificarDTO->setSelloDigital($strSelloDig);
                                $personaNotificarDTO = $personaNotificarControl->personasNotificar($personaNotificarDTO,$acuerdosBoletinDTO,$arrPersonasAuto[$i]);

                                $tmpEstadoEnvioDTO->setFk_idtblPersonasNotificar($personaNotificarDTO->getIdtblPersonasNotificar());
                                $tmpEstadoEnvioDTO->setFk_idtblEstatusNotificacion(1);
                                $tmpEstadoEnvioDTO->setFechaEstatus(date("Y-m-d H:i:s",time()));
                                $estadoEnvioControl->guardaEstadoEnvio($tmpEstadoEnvioDTO);

                                $utfNombre = htmlspecialchars($arrPersona[2]); // UTF-8
                                $strDatosExpediente  = "EXPEDIENTE: ".$acuerdosBoletinDTO->getNumExpediente()."/".$acuerdosBoletinDTO->getAnioExpediente()."<br>";
                                $strDatosExpediente .= "JUZGADO: ".$_SESSION["Adscripcion"]."<br><br>";

                                $strInstructivo = str_replace("@br@", "<br>",$strInstructivo);
                                $strInstructivo = str_replace("@Nombre@", $utfNombre,$strInstructivo);
                                $strInstructivo = str_replace("@Correo@", str_replace(":", "; ",$arrPersona[1]),$strInstructivo);
                                $strInstructivo = str_replace("@TipoPersona@", strtoupper($tipoPersonaAsuntoDTO->getDescripcion()),$strInstructivo);
                                $strInstructivo = str_replace("@Hora@", $strHoraIns.":".$strMinutosIns,$strInstructivo);
                                $strInstructivo = str_replace("@Dia@", $strDiaIns,$strInstructivo);
                                $strInstructivo = str_replace("@Mes@", $arrNombreMes[$strMesIns],$strInstructivo);
                                $NumLetra->setNumero($strAnioIns);
                                $strInstructivo = str_replace("@Anio@", trim($NumLetra->letra()),$strInstructivo);

                                $strSelloDigital  = "<br><br>";
                                $strSelloDigital .= "Cadena original:<br>".htmlspecialchars(utf8_encode($strCadenaOri));
                                $strSelloDigital .= "<br><br>";
                                $strSelloDigital .= "Sello digital:<br>".$strSelloDig;
                                
                                /******************* Carga plantilla de servidorCorreo.xml *********************/
                                $objDatCorreo = plantillaCorreo("Notificacion");
                                $correo = new EmailHELPER();
                                $correo->setSenderAddress($objDatCorreo->CorreoRemite);
                                $correo->setToAddress(trim($arrCorreos[0]));
                                $correo->setToName($arrPersona[2]);
                                if(count($arrCorreos) > 1)  $correo->setCcAddress($arrCorreos[1]);
                                $correo->setSubject($objDatCorreo->Subject);
                                $correo->setHostSmtp($objDatCorreo->IpSMTP);
                                $correo->setPortSmtp($objDatCorreo->PortSMTP);
                                $correo->setIsHTMLFormat(true);
                                $strCuerpoEmail  = "<html>\n<head>\n<title>\Poder Judicial del Estado de México</title>\n</head>\n<body>\n";
                                $strCuerpoEmail .= $strDatosExpediente.$textoArchivoDoc."<br><br>".$strInstructivo.$strSelloDigital;
                                $strCuerpoEmail .= "</body>\n</html>\n\n";
                                $correo->setBody($strCuerpoEmail);
                                if($bolAdjunto){
                                    $correo->setSendAttach(true);
                                    $correo->setFilename($realPathAndFileNew);
                                }
                                $bolStatusMail = $correo->send();
                                if($bolStatusMail){
                                    $log->info("Correo enviado a ".$arrPersona[2].": ".$arrPersona[1]);
                                    echo "Correo enviado a ".$arrPersona[2].": ".$arrPersona[1]."<br>\n";
                                    if($bolAdjunto){
                                        if($bolBorrarAdjunto) borraArchivo($realPathAndFileNew);
                                    }
                                    $tmpEstadoEnvioDTO->setFk_idtblPersonasNotificar($personaNotificarDTO->getIdtblPersonasNotificar());
                                    $tmpEstadoEnvioDTO->setFk_idtblEstatusNotificacion(2);
                                    $tmpEstadoEnvioDTO->setFechaEstatus($strAnioIns."-".($strMesIns+1)."-".$strDiaIns." ".$strHoraIns.":".$strMinutosIns);
                                    $tmpEstadoEnvioDTO = $estadoEnvioControl->guardaEstadoEnvio($tmpEstadoEnvioDTO);
//
                                    $bitacoraHELPER->setIdAccion(6);
                                    $bitacoraHELPER->setCveUsuario($_SESSION["CveUsuario"]);
                                    $bitacoraHELPER->setDescripcion("idEstadoEnvio[".$tmpEstadoEnvioDTO->getIdtblEstadoEnvio()."] Persona[".$arrPersona[2]."] Correo[".$arrPersona[1]."] FechaEstatus[".$tmpEstadoEnvioDTO->getFechaEstatus()."]");
                                    if($bitacoraHELPER->guardaBitacora()){
                                        $log->info("Registro en BITACORA correcto");
                                    } else{
                                        $log->info("Registro en BITACORA incorrecto");
                                    }
                                } else{
                                    $log->info("ERROR al enviar correo: ".$arrPersona[1]);
                                    echo "ERROR al enviar correo: ".$arrPersona[1]."<br>\n";
                                }
                            }
                        }
//
                    }

                    $strCadena = "<script language=\"javascript\">\n";
                    $strCadena .= "var arrTextoAcuse = new Array();\n";
                    $strCadena .= "alert('Acuerdo guardado correctamente.');\n";
                    $strCadena .= "parent.limpiaForma('frmAcuerdos');\n";
                    $strCadena .= "parent.cargaListadoAcuerdos();\n";
                    $strCadena .= "parent.Form.Element.enable('btnGuardar');\n";
                    if(strlen($_POST["hidPersonasAuto"]) > 0){
                        $strCadena .= "parent.imprimeAcuseNotificacion(".$acuerdosBoletinDTO->getIdtblAcuerdos().");\n";
                    }
                    $strCadena .= "</script>\n";
                    
                } else{
                    $log->info("Error: Acuerdo NO guardado. INSERT fallido.");
                    $strCadena = "<script language=\"javascript\">\n";
                    $strCadena .= "alert('Error al guardar el acuerdo.');\n";
                    $strCadena .= "parent.Form.Element.enable('btnGuardar');";
                    $strCadena .= "</script>\n";
                }
            } else{
                $log->info("Error: Acuerdo NO guardado. Limite de tiempo.");
                $strCadena = "<script language=\"javascript\">\n";
                $strCadena .= "alert('Aviso: Hora limite para capturar acuerdos: ".$servServicioDTO->getHoraFin()." hrs. Acuerdo NO guardado!');\n";
                $strCadena .= "parent.Form.Element.enable('btnGuardar');";
                $strCadena .= "</script>\n";
            }
        } else{
            $log->info("Error: Acuerdo NO guardado. Sin permisos para este servicios [Captura Acuerdos Boletín]");
            $strCadena = "<script language=\"javascript\">\n";
            $strCadena .= "alert('Aviso: No cuenta con permisos para este servicio. Acuerdo NO guardado!');\n";
            $strCadena .= "parent.Form.Element.enable('btnGuardar');";
            $strCadena .= "</script>\n";
        }
        $log->info("Finaliza, FormularioAcuerdos > guardaAcuerdo");
        
        echo $strCadena;

        break;
    case "listaPersonasAuto":
        include_once(dirname(__FILE__) . "/../Modelo/DTO/RelacionExpedienteJuzgadoDTO.Class.php");
        include_once(dirname(__FILE__) . "/../Control/RelacionExpedienteJuzgadoCONTROL.Class.php");
        
        $log->info("Inicia, FormularioAcuerdos > listaPersonasAuto");
        $relacionExpJuzDTO = new RelacionExpedienteJuzgadoDTO();
        $relacionExpJuzCTRL = new RelacionExpedienteJuzgadoCONTROL();
        

        $relacionExpJuzDTO->setNumExpediente($_POST["NumExp"]);
        $relacionExpJuzDTO->setAnioExpediente($_POST["AnioExp"]);
        $relacionExpJuzDTO->setIdtblJuzgados($_SESSION["CveAdscripcion"]);
        
        $recibeHTML = $relacionExpJuzCTRL->consultaRelacionExpJuz($relacionExpJuzDTO);
        $log->info("Finaliza, FormularioAcuerdos > listaPersonasAuto");
        echo $recibeHTML;
        break;
    case "listaDocsExpVir":
        include_once(dirname(__FILE__) . "/../Modelo/DTO/JuzgadoBoletinDTO.Class.php");
        include_once(dirname(__FILE__) . "/../Control/JuzgadosBoletinCONTROL.Class.php");
        include_once(dirname(__FILE__) . "/../Modelo/DTO/OficialiaExpVirDTO.Class.php");
        include_once(dirname(__FILE__) . "/../Control/OficialiaExpVirCONTROL.Class.php");
        include_once(dirname(__FILE__) . "/../Modelo/DTO/DocsExpVirtualDTO.Class.php");
        include_once(dirname(__FILE__) . "/../Control/DocsExpVirtualCONTROL.Class.php");
        
        $log->info("Inicia, FormularioAcuerdos > listaDocsExpVir");
        $bolNumRenglon = true;
        $juzBoletinDTO = new JuzgadoBoletinDTO();
        $juzBoletinCTRL = new JuzgadosBoletinCONTROL();

        $juzBoletinDTO->setIdJuzgadoGestion($_SESSION["CveAdscripcion"]);
        $juzBoletinDTO = $juzBoletinCTRL->consultaJuzgadosBoletin($juzBoletinDTO);

        $RecibeHMTL  = "<table cellpadding=\"0\" celspacing=\"0\" border=\"0\" width=\"100%\" class=\"Arial12 tblListaPerAuto\" bgcolor=\"#FFFFFF\">";
        $RecibeHMTL .= "<tr>\n";
        $RecibeHMTL .= "<td width=\"25\">&nbsp;</td>\n";
        $RecibeHMTL .= "<td colspan=\"2\" align=\"center\">Archivo</td>\n";
        $RecibeHMTL .= "</tr>\n";
        $RecibeHMTL .= "<tr id=\"trDocExpVir0\" class=\"trRenImpar\">\n";
        $RecibeHMTL .= "<td width=\"25\">\n<input id=\"rdbDocExpVir_0\" type=\"radio\" name=\"rdbDocAdjunto\" value=\"0\" onClick=\"cambiaColorTR(0);\" checked>\n</td>\n";
        $RecibeHMTL .= "<td colspan=\"2\">\n<input type=\"file\" name=\"fileDocAdjunto\" id=\"fileDocAdjunto\" size=\"10\">\n</td>\n";
        $RecibeHMTL .= "</tr>\n";

        if($juzBoletinDTO->getIdJuzgadoExpedienteVirtual() == 0 || $juzBoletinDTO->getIdOficialiaExpedienteVirtual() == 0){
                $RecibeHMTL .= "<tr class=\"trRenImpar\">\n";
                $RecibeHMTL .= "<td colspan=\"3\" align=\"center\">\n";
                $RecibeHMTL .= "Alerta > Expediente Electrónico<br>[";
                $RecibeHMTL .= ($juzBoletinDTO->getIdJuzgadoExpedienteVirtual() == 0 ? "idJuzgado" : "");
                if($juzBoletinDTO->getIdJuzgadoExpedienteVirtual() == 0 && $juzBoletinDTO->getIdOficialiaExpedienteVirtual() == 0) $RecibeHMTL .= " & ";
                $RecibeHMTL .= ($juzBoletinDTO->getIdOficialiaExpedienteVirtual() == 0 ? "idOficialia" : "");
                $RecibeHMTL .= " = 0]\n";
                $RecibeHMTL .= "</td>\n";
                $RecibeHMTL .= "</tr>\n";
        } else{

                $ofiExpVirDTO = new OficialiaExpVirDTO();
                $ofiExpVirCTRL = new OficialiaExpVirCONTROL();

                $ofiExpVirDTO->setCveAdscripcion($juzBoletinDTO->getIdOficialiaExpedienteVirtual());
                $ofiExpVirDTO = $ofiExpVirCTRL->oficialiaExpVirtual($ofiExpVirDTO);

                $intValorGet = getResponseCode("http://".$ofiExpVirDTO->getRutaDocs());
                if ($intValorGet != 200) {
                        switch($intValorGet){
                                case 404:
                                        $strErrorURL = "URL no existe";
                                        break;
                                case 0:
                                        $strErrorURL = "URL incorrecta";
                                        break;
                        }
                        $RecibeHMTL .= "<tr class=\"trRenImpar\">\n";
                        $RecibeHMTL .= "<td colspan=\"3\" align=\"center\">\nAlerta > Expediente Electrónico<br>[".$strErrorURL."]\n</td>\n";
                        $RecibeHMTL .= "</tr>\n";
                }else if($ofiExpVirDTO->getActivo() == "S"){
                        $docsExpVirDTO = new DocsExpVirtualDTO();
                        $docsExpVirCTRL = new DocsExpVirtualCONTROL();

                        $docsExpVirDTO->setNumExp($_POST["NumExp"]);
                        $docsExpVirDTO->setAnioExp($_POST["AnioExp"]);
                        $docsExpVirDTO->setIdJuzExpVir($juzBoletinDTO->getIdJuzgadoExpedienteVirtual());
                        $docsExpVirDTO->setIdOfiExpVir($juzBoletinDTO->getIdOficialiaExpedienteVirtual());
                        $docsExpVirDTO->setTipoDocumento($_POST["TipoDocExpVir"]);

                        $arrDocsExpVir = $docsExpVirCTRL->listaDocsExpVirtual($docsExpVirDTO, $ofiExpVirDTO);

                        if(count($arrDocsExpVir) > 0){
                                for ($i=0;$i<count($arrDocsExpVir);$i++) {
                                        $dto = $arrDocsExpVir[$i];
                                        $strFechaDoc = $dto->getFechaDocumento();
                                        $intValorGet = getResponseCode("http://".$dto->getRutaDocumento());
                                        if($intValorGet == 200){
                                                $strClaseRenglon = ($bolNumRenglon) ? "trRenImpar" : "trRenPar";
                                                $RecibeHMTL .= "<tr id=\"trDocExpVir".$dto->getIdDocumento()."\" class=\"".$strClaseRenglon."\">\n";
                                                $RecibeHMTL .= "<td width=\"25\">\n<input id=\"rdbDocExpVir_".$dto->getIdDocumento()."\" type=\"radio\" name=\"rdbDocAdjunto\" value=\"".$dto->getIdDocumento()."-".$dto->getRutaDocumento()."\" onClick=\"cambiaColorTR(".$dto->getIdDocumento().");\">\n</td>\n";
                                                $RecibeHMTL .= "<td alt=\"".$dto->getRutaDocumento()."\" align=\"left\">\n";
                                                $RecibeHMTL .= "<label for=\"rdbDocExpVir_".$dto->getIdDocumento()."\">".$dto->getDescDoc()." (".substr($strFechaDoc,6,2)."/".substr($strFechaDoc,4,2)."/".substr($strFechaDoc,0,4).")</label>\n";
                                                $RecibeHMTL .= "</td>\n";
                                                $RecibeHMTL .= "<td width=\"25\">\n<img src=\"../img/btnConsultar_32x32.gif\" width=\"20\" height=\"20\" border=\"0\" style=\"cursor: pointer;\" onClick=\"cambiaColorTR(".$dto->getIdDocumento()."); verDocExpVirtual('".$dto->getRutaDocumento()."');\">\n</td>\n";
                                                $RecibeHMTL .= "</tr>\n";
                                                $bolNumRenglon = !$bolNumRenglon;
                                        }
                                }
                        } else{
                                $RecibeHMTL .= "<tr class=\"trRenImpar\">\n";
                                $RecibeHMTL .= "<td colspan=\"3\" align=\"center\">\nAlerta > Expediente Electrónico<br>[Sin documentos]\n</td>\n";
                                $RecibeHMTL .= "</tr>\n";
                        }

                } else{
                        $RecibeHMTL .= "<tr class=\"trRenImpar\">\n";
                        $RecibeHMTL .= "<td colspan=\"3\" align=\"center\">\nAlerta > Expediente Electrónico<br>[Oficialia inactiva]\n</td>\n";
                        $RecibeHMTL .= "</tr>\n";
                }


        }

        $RecibeHMTL .= "</table>\n";
        $log->info("Termina, FormularioAcuerdos > listaDocsExpVir");
        echo $RecibeHMTL;
        break;
    case "acuseNotificacion":
        include_once(dirname(__FILE__) . "/../../helppers/NumeroALetraHELPER.Class.php");
        include_once(dirname(__FILE__) . "/../Modelo/DTO/AcuerdosBoletinDTO.Class.php");
        include_once(dirname(__FILE__) . "/../Control/AcuerdosBoletinCONTROL.Class.php");
        include_once(dirname(__FILE__) . "/../Modelo/DTO/PersonaNotificarDTO.Class.php");
        include_once(dirname(__FILE__) . "/../Control/PersonaNotificarCONTROL.Class.php");
        
        $log->info("Inicia, FormularioAcuerdos > acuseNotificacion");
        $strEncabezado = "";
        $RecibeHMTL = "";
        $strInstructivo = "";
        $strInsPersona = "";
        $strSelloDigital = "";
        $NumLetra = new NumeroALetraHELPER();
        $NumLetra->setPrefijo("");
        $NumLetra->setSufijo("");
        $NumLetra->setMoneda("");
        $NumLetra->setVerCentavos(false);
            
        $intIdAcuerdo = $_POST["IdAcuerdo"];
        $acuerdoBoletinDTO = new AcuerdosBoletinDTO();
        $acuerdoBoletinDTO->setIdtblAcuerdos($intIdAcuerdo);
        $acuerdoBoletinCTRL = new AcuerdosBoletinControl();
        $personasNotificarDTO = new PersonaNotificarDTO();
        $personasNotificarDTO->setFk_idtblAcuerdos($intIdAcuerdo);
        $personaNotificarCTRL = new PersonaNotificarControl();


        $acuerdoBoletinDTO = $acuerdoBoletinCTRL->consultarAcuerdo($acuerdoBoletinDTO);

        $strInstructivo = utf8_decode(plantillaInstructivo());

        $strEncabezado  = "EXPEDIENTE: ".$acuerdoBoletinDTO->getNumExpediente()."/".$acuerdoBoletinDTO->getAnioExpediente()."<br>";
        $strEncabezado .= "JUZGADO: ".$_SESSION["Adscripcion"]."<br><br>";

        $listaAcuseNotificar = $personaNotificarCTRL->listaPersonasNotificar($personasNotificarDTO);

        if(count($listaAcuseNotificar) == 0){
                $RecibeHMTL = "Sin datos...";
        } else{
                for ($x=0;$x<count($listaAcuseNotificar);$x++) {
                        $strInsPersona = $strInstructivo;
                        $dto = $listaAcuseNotificar[$x];
                        $arrFechaHora = explode(" ",$dto->getFechaEstatus());
                        $arrFecha = explode("/",$arrFechaHora[0]);
                        $arrHora = explode(":",$arrFechaHora[1]);
                        
                        $strUFTNombre = utf8_encode($dto->getNomPersona());
                        $strInsPersona = str_replace("@br@", "<br>", $strInsPersona);
                        $strInsPersona = str_replace("@Nombre@", $strUFTNombre, $strInsPersona);
                        $strInsPersona = str_replace("@Correo@", $dto->getEmail(), $strInsPersona);
                        $strInsPersona = str_replace("@TipoPersona@", $dto->getTipoPersona(), $strInsPersona);
                        $strInsPersona = str_replace("@Hora@", $arrHora[0].":".$arrHora[1], $strInsPersona);
                        $strInsPersona = str_replace("@Dia@", $arrFecha[0], $strInsPersona);
                        $strInsPersona = str_replace("@Mes@", $arrNombreMes[$arrFecha[1]], $strInsPersona);
                        $NumLetra->setNumero((int)$arrFecha[2]);
                        $strInsPersona = str_replace("@Anio@", trim($NumLetra->letra()), $strInsPersona);
                        $strSelloDigital  = "<br><br>";
                        $strSelloDigital .= "Cadena original:<br>".utf8_encode($dto->getCadOriginal());
                        $strSelloDigital .= "<br><br>";
                        $strSelloDigital .= "Sello digital:<br>".$dto->getSelloDigital();

                        $RecibeHMTL .= "<table class=\"tblAcuse\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" align=\"center\" width=\"650\">\n";
                        $RecibeHMTL .= "<tr>\n";
                        $RecibeHMTL .= "<td>\n";
                        $RecibeHMTL .= $strEncabezado.$strInsPersona.$strSelloDigital;
                        $RecibeHMTL .= "</td>\n";
                        $RecibeHMTL .= "</tr>\n";
                        $RecibeHMTL .= "</table>\n";
                        $RecibeHMTL .= "<br class=\"saltopagina\">";
                }
        }

        $log->info("Termina, FormularioAcuerdos > acuseNotificacion");
        
        echo $RecibeHMTL;
        break;
    case "publicar":
        include_once(dirname(__FILE__) . "/../../helppers/BitacoraHELPER.Class.php");
        include_once(dirname(__FILE__) . "/../Control/AcuerdosBoletinCONTROL.Class.php");
        include_once(dirname(__FILE__) . "/../Control/ControlIntegrarBoletin.Class.php");
        include_once(dirname(__FILE__) . "/../Modelo/DTO/IntegrarBoletinDTO.Class.php");
        
        $objCabecera->CabeceraHTML();
        
        $integraBolCTRL = new ControlIntegrarBoletin();
        $integraBolDTO = new IntegrarBoletinDTO();
        
        $integraBolDTO->setFechaPublicacion($_POST["fecha"]);
        
        $integraBolDTO = $integraBolCTRL->guardaIntegrarBoletin($integraBolDTO);
        
        $acuerdoBoletinControl = new AcuerdosBoletinControl();
        
        $strDesBitacora = "Fecha de publicaci&oacute;n - ".$_POST["fecha"]." ".date("H:i:s",time())."\n\n";
        $arrDatIntegra = $acuerdoBoletinControl->publicar($integraBolDTO);
        foreach ($arrDatIntegra as $valor) {
            $strDesBitacora .= "- ".$valor["Msg"]."\n";
        }
        echo $strDesBitacora;

        $bitacoraHELPER = new BitacoraHELPER();
        $bitacoraHELPER->setIdAccion(66);
        $bitacoraHELPER->setCveUsuario($_SESSION["CveUsuario"]);
        $bitacoraHELPER->setDescripcion($strDesBitacora);
        if($bitacoraHELPER->guardaBitacora()){
                $log->info("guardaAcuerdo > Registro en BITACORA correcto");
        } else{
                $log->info("guardaAcuerdo > Registro en BITACORA incorrecto");
        }
        break;
}


function plantillaInstructivo(){
    global $log;
    $log->info("Inicia, FormularioAcuerdos > plantillaInstructivo()");
    $xml = simplexml_load_file("../../vista/conf/instructivoNotificacion.xml");

    for($i = 0; $i < count($xml->Plantilla); $i++) {
      $planNombre = $xml->Plantilla[$i]->attributes()->nombre;
      if($planNombre == "Instructivo"){
          $strInstructivo = utf8_encode($xml->Plantilla[$i]->Texto);
          break;
      }
    } 
    $log->info("Finaliza, FormularioAcuerdos > plantillaInstructivo()");
    return $strInstructivo;
}

function plantillaCorreo($attNom){
    global $log;
    $log->info("Inicia, FormularioAcuerdos > plantillaCorreo()");
    $xml = simplexml_load_file("../../vista/conf/servidorCorreo.xml");
    
    for($i = 0; $i < count($xml->Correo); $i++) {
      $planNombre = $xml->Correo[$i]->attributes()->nombre;
      if($planNombre == $attNom){
          $objPlantilla = $xml->Correo[$i];
//          var_dump($objPlantilla);
          break;
      }
    } 
    $log->info("Finaliza, FormularioAcuerdos > plantillaCorreo()");
    return $objPlantilla;
     
}

function getResponseCode($urlString){
    $intStatusURL = 0;
    $AgetHeaders = get_headers($urlString,1);
    
    if (preg_match("|200|", $AgetHeaders["0"])){
        $intStatusURL = 200;
    }else if (preg_match("|404|", $AgetHeaders["0"])){
        $intStatusURL = 404;
    }
    
    return $intStatusURL;
}

function borraArchivo($rutaArchivo){
    $bolEstado = false;
    if(file_exists($rutaArchivo)){
        if (!unlink($rutaArchivo)){
            echo "El fichero " .$rutaArchivo . " no puede ser borrado<br>\n";
        } else{
            echo "El fichero ".$rutaArchivo." ha sido borrado satisfactoriamente<br>\n";
            $bolEstado = true;
        }
    } else{
        echo "El fichero ".$rutaArchivo." NO EXISTE<br>\n";
    }
    
    return $bolEstado;
}

function extraeLlavePrivada($archivo){
    $fp = fopen ($archivo,"r");
    $privKey = fread ($fp,8192);
    fclose($fp);
    
    return $privKey;
}

?>