<?php
if (!isset($_SESSION)) {
    session_start();
}
//ini_set("error_log", dirname(__FILE__) . "/../../logs/CargaImagenesFirmaController_log.txt");
ini_set("log_errors", 1);
ini_set('display_errors', 1);
ini_set('error_reporting', E_ALL ^ E_NOTICE);

//include_once(dirname(__FILE__) . "/CargaImagenesController.Class.php");
include_once(dirname(__FILE__) . "/../../tribunal/logger/Logger.Class.php");
include_once(dirname(__FILE__) . "/ImagenesController.Class.php");
include_once (dirname(__FILE__) . "/../../model/dto/actuacionesfirmadas/ActuacionesFirmadasDTO.Class.php");
include_once (dirname(__FILE__) . "/../../model/dao/actuacionesfirmadas/ActuacionesFirmadasDAO.Class.php");

class CargaImagenesFirmaController {

    private $log;
    private $name;

    public function cargaImagenes($specifications, $archivo) {
        $this->name = "CargaImagenesFirmaController";
        $this->log = new Logger("", $this->name);
        $this->log->w_onError("******INICIA PROCESO SUBIR IMAGEN FIRMADA******");
        $this->log->w_onError("******----" . $specifications);

        $specifications = json_decode($specifications, true);

        $param = array();
        $param["idDocumentoImg"] = $specifications["idDocumento"];
        $param["idImagenOriginal"] = $specifications["idImagenOriginal"];
        //$param["cveOrigen"] = $specifications["cveOrigen"];
        $param["cveTipoDocumento"] = $specifications["cveTipoDocumento"];
        $param["archivo"] = $archivo;


        print_r("carga imagenes controller:");
        print_r("\n\n");
        print_r($specifications);
        print_r("\n\n");
        print_r($archivo);
        exit();








        $ImagenesController = new ImagenesController();
        $jsonImagen = $ImagenesController->getRuta($param["idCarpetaJudicial"], $param["idActuacion"], $param["idAudiencia"], 0);
        $copiaCorrecta = false;
        $datosImagenCopiada = "";
        if ($jsonImagen != "") {
            $arrImagen = json_decode($jsonImagen, true);
            if ($arrImagen["data"]["type"] == "OK") {
                $datosImagenCopiada = $arrImagen;
                $extencion = explode(".", $param["archivo"]['name']);
                if ((string) $extencion[1] === "pdf") {
                    if (move_uploaded_file($param["archivo"]['tmp_name'], "../../imagenes" . $arrImagen["data"]["ruta"])) {
                        $jsonImagen = $ImagenesController->setUpdateRuta($arrImagen["data"]["ruta"], "S");
                        $arrImagen = json_decode($jsonImagen, true);
                        if ($arrImagen["data"]["type"] == "OK") {
                            $copiaCorrecta = true;
                            $this->log->w_onError("RESPUESTA:" . json_encode(array("type" => "OK", "text" => "Archivo copiado de forma correcta")));
                        } else {
                            $this->log->w_onError("RESPUESTA:" . json_encode(array("type" => "Error", "text" => "Ocurrio un error al Actualizar la informacion de la imagen")));
                        }
                    } else {
                        $this->log->w_onError("RESPUESTA:" . json_encode(array("type" => "Error", "text" => "Ocurrio un error al copiar el archivo")));
                    }
                } else {
                    $this->log->w_onError("RESPUESTA:" . json_encode(array("type" => "Error", "text" => "Tipo de Archivo no valido. Archivo con extencion:" . (string) $extencion[1])));
                }
            }
        }

        if ($copiaCorrecta) {
            $this->log->w_onError("RESPUESTA:" . json_encode($datosImagenCopiada));
            if ($datosImagenCopiada["data"]["idImagen"] != "" && $datosImagenCopiada["data"]["idImagen"] > 0) {
                $ActuacionesFirmadasDTO = new ActuacionesFirmadasDTO();
                $ActuacionesFirmadasDTO->setIdImagenOriginal($param["idImagenOriginal"]);
                $ActuacionesFirmadasDTO->setCveOrigen($param["cveOrigen"]);
                $ActuacionesFirmadasDTO->setIdReferencia($param["idReferencia"]);
                $ActuacionesFirmadasDTO->setCveTipoReferencia($param["cveTipoReferencia"]);
                $ActuacionesFirmadasDAO = new ActuacionesFirmadasDAO();
                $ActuacionesFirmadasDTO = $ActuacionesFirmadasDAO->selectActuacionesFirmadas($ActuacionesFirmadasDTO);
                if ($ActuacionesFirmadasDTO != "" && count($ActuacionesFirmadasDTO) > 0) {
                    $this->log->w_onError("OK1");
                    $indice = 1;
                    foreach ($ActuacionesFirmadasDTO as $ActuacionFirmada) {
                        $this->log->w_onError("OK-CICLO:" . $indice);
                        $this->log->w_onError("+++++++++++++++++++:");
                        $ActuacionesFirmadasUpdateDTO = new ActuacionesFirmadasDTO();
                        $ActuacionesFirmadasUpdateDTO->setIdActuacionFirmada($ActuacionFirmada->getIdActuacionFirmada());
                        $ActuacionesFirmadasUpdateDTO->setIdImagenFirmada($datosImagenCopiada["data"]["idImagen"]);
                        $ActuacionesFirmadasUpdateDTO = $ActuacionesFirmadasDAO->updateActuacionesFirmadas($ActuacionesFirmadasUpdateDTO);
                        $this->log->w_onError(json_encode($ActuacionesFirmadasUpdateDTO[0]));
                        
                        if ($ActuacionesFirmadasUpdateDTO != "" && count($ActuacionesFirmadasUpdateDTO) > 0) {
                            $this->log->w_onError("OK2");
                            $this->log->w_onError("DATOS:" . $ActuacionesFirmadasUpdateDTO[0]->getIdImagenFirmada());
                            $ActuacionesFirmadasUpdateDTO = $ActuacionesFirmadasUpdateDTO[0];
                            if ($ActuacionesFirmadasUpdateDTO->getIdImagenFirmada() != "" && count($ActuacionesFirmadasUpdateDTO->getIdImagenFirmada()) > 0) {
                                $this->log->w_onError("RESPUESTA CORRECTA: SE ACTUALIZO EL ID FIRMADA:" . $ActuacionesFirmadasUpdateDTO->getIdActuacionFirmada() . " - SE ACTUALIZO CON EL IDIMAGEN:" . $ActuacionesFirmadasUpdateDTO->getIdImagenFirmada());
                                #ELIMINA IMAGEN ORIGINAL
                                $ImagenesController->borrarImagenes($param["idImagenOriginal"], $param["idCarpetaJudicial"], $param["idActuacion"], $param["idAudiencia"]);
                            } else {
                                $this->log->w_onError("ERROR AL ACTUALIZAR");
                            }
                        }
                        $this->log->w_onError("+++++++++++++++++++:");
                        $indice++;
                    }
                }
            }
        }
    }

}

if (isset($_FILES)) {
    @$specifications = $_POST['detalleParametros'];
    @$archivo = $_FILES['archivo'];
    $CargaImagenesFirmaController = new CargaImagenesFirmaController();
    $CargaImagenesFirmaController->cargaImagenes($specifications, $archivo);
} else {
    echo "viene vacio";
}
//----------------------------------------------------------