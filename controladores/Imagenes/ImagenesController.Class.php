<?php

include_once(dirname(__FILE__) . "/../../modelos/dao/GenericDAO.Class.php");
include_once(dirname(__FILE__) . "/../../tribunal/json/JsonEncod.Class.php");
include_once(dirname(__FILE__) . "/../planeacion/SeguimientoProyectosController.Class.php");
require_once(dirname(__FILE__) . '/../../tribunal/pdf/html2pdf.class.php');

class ImagenesController {

    private $_proveedor;

    public function __construct() {
        
    }

    public function selectImagen($param) {
        $genericoDao = new GenericDAO();
        $Imagenes = $genericoDao->select($param);
        return $Imagenes;
    }

    public function insertImagen($param) {
        $genericoDao = new GenericDAO();
        $Imagenes = $genericoDao->insert($param);
        return $Imagenes;
    }

    public function updateImagen($param) {
        $genericoDao = new GenericDAO();
        $Imagenes = $genericoDao->update($param);
        return $Imagenes;
    }

    public function deleteImagen($param) {
        $genericoDao = new GenericDAO();
        $Imagenes = $genericoDao->deleteTable($param);
        return $Imagenes;
    }

    public function crearDocumentoImg($params) {

        $genericoDao = new GenericDAO();
        $documentoImagenes = $genericoDao->insert($params);
        if ($documentoImagenes["totalCount"] > 0) {
            $documentoImagenes = $this->consultarTiposDocumentos($documentoImagenes, $params["proveedor"]);
            return $documentoImagenes;
        } else {
            return 0;
        }
    }

    public function crearImagenesByHTML($arrayConfig = null, $html, $documentoImagenes, $proveedor = null, $contenido = null) {
        if ($arrayConfig == null) {
            $arrayConfig = array(
                "orientation" => "P",
                "format" => "A4",
                "langue" => "es",
                "unicode" => true,
                "encoding" => 'UTF-8',
                "marges" => array(0, 0, 0, 0));
        }
        $error = false;
        $d = array();
        $values = array();
        $genericoDao = new GenericDAO();
        $ruta = $this->crearRuta($documentoImagenes, $proveedor);

        $this->CreaDirectorio($ruta);
        unset($d);
        unset($values);
        $proyectosProgramaticos = "";
        $values["idDocumentoImg"] = $documentoImagenes["data"][0]["idDocumentoImg"];
        $values["ruta"] = $ruta;
        $values["adjunto"] = "N";
        $values["descripcion"] = "descripcion";
        $values["posicion"] = 1;
        $values["activo"] = "S";
        $values["fechaRegistro"] = "now()";
        $values["fechaActualizacion"] = "now()";
        $d["values"] = $values;
        $param = array("tabla" => "tblimagenes", "d" => $d, "tmpSql" => "", "proveedor" => $proveedor);
        $imagenes = $genericoDao->insert($param);
        if ($imagenes["totalCount"] > 0) {
            $nombreArchivo = $documentoImagenes["data"][0]["extension"] . $imagenes["data"][0]["idImagen"] . ".pdf";
            $updateArchivo = array(
                "tabla" => "tblimagenes", "d" => array(
                    "values" => array(
                        "ruta" => $imagenes["data"][0]["ruta"] . $nombreArchivo,
                        "fechaActualizacion" => "now()"
                    ), "where" => array(
                        "idImagen" => $imagenes["data"][0]["idImagen"]
                    )), "proveedor" => $proveedor
            );
            $updatArchivoRs = $genericoDao->update($updateArchivo);
            if ($updatArchivoRs["totalCount"] > 0) {
                if (!$error && $html != "") {
//                    var_dump("ENTREA");
                    #GENERAMOS EL ARCHIVO PDF EN BASE AL CODIGO HTML ANTERIOR
                    date_default_timezone_set("America/Mexico_City");
                    ob_start();

                    echo '<style>';
                    echo '#encabezado {';
                    echo '	padding: 10px 0;';
                    echo '	width: 100%;';
                    echo '}';
                    echo '#encabezado .fila #col_1 {';
                    echo '	width: 25%';
                    echo '}';
                    echo '#encabezado .fila #col_2 {';
                    echo '	text-align: center;';
                    echo '	width: 50%;';
                    echo '}';
                    echo '#encabezado .fila #col_2 #span1 {';
                    echo '	font-family: Arial, Calibri;';
                    echo '	font-size: 18px;';
                    echo '	font-weight: bold;';
                    echo '}';
                    echo '#encabezado .fila #col_2 #span2 {';
                    echo '	font-size: 12px;';
                    echo '	color: #4d9;';
                    echo '}';
                    echo '#encabezado .fila #col_3 {';
                    echo '	width: 25%';
                    echo '}';
                    echo '#footer {';
                    echo '	padding-top: 5px 0;';
                    echo '	border-top: gray 1px solid;';
                    echo '	width: 100%;';
                    echo '}';
                    echo '#footer .fila td {';
                    echo '	text-align: right;';
                    echo '	width: 100%;';
                    echo '}';
                    echo '#footer .fila td span {';
                    echo '	font-size: 10px;';
                    echo '	color: grey;';
                    echo '}';
                    echo 'body {';
                    echo '	font-family: calibri, arial;';
                    echo '}';
                    echo '.tabla {';
                    echo '	width: 100%;';
                    echo '	border: solid 0px;';
                    echo '	border-collapse: collapse;';
                    echo '}';
                    echo '</style>';

                    echo '<page backtop="25mm" backbottom="10mm" backleft="10mm" backright="10mm"';
                    echo '	> <page_header>';
                    echo '<table id="encabezado">';
                    echo '	<tr class="fila">';
                    echo '		<td';
                    echo '			style="width: 33%; font-size: 9px; text-align: left; border-bottom: #881518 5px; border-style: double;">';
                    echo '                    <img src="../../vistas/img/logoPj.png" width="100px"';
                    echo '			height="100px">';
                    echo '		</td>';
                    echo '		<td';
                    echo '			style="width: 33%; font-size: 20px; text-align: center; border-bottom: #881518 5px; border-style: double;">';
                    echo '                    ';
                    echo '                    ';
                    echo '		</td>';
                    echo '		<td';
                    echo '			style="width: 33%; font-size: 9px; text-align: right; border-bottom: #881518 5px; border-style: double;">';
                    echo '                    <img src="../../vistas/img/escudoedomex.jpg" width="100px" height="100px">';
                    echo '                </td>';
                    echo '	</tr>';
                    echo '</table>';
                    echo '</page_header> <page_footer>';
                    echo '    <div style="text-align: center">';
                    echo '<table id="footer" style="width: 100%">';
                    echo '	<tr class="fila">';
                    echo '		<td';
                    echo '			style="width: 33%; font-size: 9px; text-align: right; border-right: #881518 1px solid;">';
                    echo '			Av. Independencia Ote. 616. Colonia Santa Clara, Toluca,';
                    echo '			M&eacute;xico<br> Tel. (722) 167 9200 Ext. 16613<br>';
                    echo '			control.presupuestal@pjedomex.gob.mx';
                    echo '		</td>';
                    echo '		<td style="width: 33%; font-size: 9px; text-align: left;"><strong>DIRECCI&Oacute;N';
                    echo '				GENERAL DE FINANZAS Y PLANEACI&Oacute;N<br>';
                    echo '		</strong> DIRECCI&Oacute;N DEFINANZAS</td>';
                    echo '		<td style="width: 17%; font-size: 9px; text-align: right;"><strong>FECHA DE ELABORACION:<br></strong>';
                    echo '		</td>';
                    echo '		<td style="width: 17%; font-size: 9px; text-align: right;"><strong><span>';
                    echo '					P&aacute;gina [[page_cu]]/[[page_nb]] </span></strong></td>';
                    echo '	</tr>';
                    echo '</table>';
                    echo '    </div>';
                    echo '</page_footer> ';


                    echo utf8_encode($html);
                    echo '</page> ';
                    $content = ob_get_clean();
                    if ($contenido != null) {
                        $content = $html;
                    }
                    $ruta = $imagenes["data"][0]["ruta"] . $nombreArchivo;

                    try {
                        // init HTML2PDF
                        $html2pdf = new HTML2PDF($arrayConfig["orientation"], $arrayConfig["format"], $arrayConfig["langue"], $arrayConfig["unicode"], $arrayConfig["encoding"], $arrayConfig["marges"]);
                        //$html2pdf = new HTML2PDF('L', 'A4', 'ES');
                        $html2pdf->pdf->SetAuthor("PODER JUDICIAL DEL ESTADO DE MEXICO");
                        $html2pdf->writeHTML($content);
                        $html2pdf->Output($ruta, 'F');
                    } catch (HTML2PDF_exception $e) {
                        print_r($e);
                        $error = true;
                    }
                }
            }
        } else {
            $error = true;
        }
        return $error;
    }

    public function crearImagenes($paramFiles, $documentoImagenes, $proveedor = null) {
        $error = false;
        $d = array();
        $values = array();
        $genericoDao = new GenericDAO();
        $ruta = $this->crearRuta($documentoImagenes, $proveedor);
        $this->CreaDirectorio($ruta);
        foreach ($paramFiles as $key => $value) {
            unset($d);
            unset($values);
            $proyectosProgramaticos = "";
            $values["idDocumentoImg"] = $documentoImagenes["data"][0]["idDocumentoImg"];
            $values["ruta"] = $ruta;
            $values["adjunto"] = "S";
            $values["descripcion"] = utf8_decode($value["name"]);
            $values["posicion"] = 1;
            $values["activo"] = "S";
            $values["fechaRegistro"] = "now()";
            $values["fechaActualizacion"] = "now()";
            $d["values"] = $values;
            $param = array("tabla" => "tblimagenes", "d" => $d, "tmpSql" => "", "proveedor" => $proveedor);
            $imagenes = $genericoDao->insert($param);
            if ($imagenes["totalCount"] > 0) {
                $nombreArchivo = $documentoImagenes["data"][0]["extension"] . $imagenes["data"][0]["idImagen"] . "." . pathinfo(utf8_decode($value["name"]), PATHINFO_EXTENSION);
                $updateArchivo = array(
                    "tabla" => "tblimagenes", "d" => array(
                        "values" => array(
                            "ruta" => $imagenes["data"][0]["ruta"] . $nombreArchivo,
                            "fechaActualizacion" => "now()"
                        ), "where" => array(
                            "idImagen" => $imagenes["data"][0]["idImagen"]
                        )), "proveedor" => $proveedor
                );
                $updatArchivoRs = $genericoDao->update($updateArchivo);

                if ($updatArchivoRs["totalCount"] > 0) {
                    if (move_uploaded_file(($value["tmp_name"]), $ruta . basename($nombreArchivo))) {
                        
                    } else {
                        $error = true;
                    }
                }
            } else {
                $error = true;
            }
        }
        return $error;
    }

    public function crearImagenesPolizas($cveTipoDocumento, $idReferencia, $documentoImagenes, $proveedor = null) {

        $genericoDao = new GenericDAO();
        $sql["campos"] = "img.*";
        $sql["tablas"] = "tblimagenes img inner join tbldocumentosimg di on (img.idDocumentoImg = di.idDocumentoImg) ";
        $sql["where"] = "di.activo='S' AND img.activo='S' AND idReferencia= " . $idReferencia . " AND cveTipoDocumento=" . $cveTipoDocumento;

        $param = array("tabla" => "tblimagenes", "d" => "", "tmpSql" => $sql, "proveedor" => $proveedor);
        $tiposDocumentos = $genericoDao->select($param);

        $error = false;
        $d = array();
        $values = array();
        $genericoDao = new GenericDAO();
        $ruta = $this->crearRuta($documentoImagenes, $proveedor);
        $this->CreaDirectorio($ruta);

        foreach ($tiposDocumentos["data"] as $key => $value) {
            unset($d);
            unset($values);
            $proyectosProgramaticos = "";
            $values["idDocumentoImg"] = $documentoImagenes["data"][0]["idDocumentoImg"];
            $values["ruta"] = $ruta;
            $values["adjunto"] = "S";
            $values["descripcion"] = utf8_decode($value["descripcion"]);
            $values["posicion"] = 1;
            $values["activo"] = "S";
            $values["fechaRegistro"] = "now()";
            $values["fechaActualizacion"] = "now()";
            $d["values"] = $values;
            $param = array("tabla" => "tblimagenes", "d" => $d, "tmpSql" => "", "proveedor" => $proveedor);
            $imagenes = $genericoDao->insert($param);
            if ($imagenes["totalCount"] > 0) {
                $nombreArchivo = $imagenes["data"][0]["idImagen"] . $documentoImagenes["data"][0]["extension"] . "." . pathinfo(utf8_decode($value["descripcion"]), PATHINFO_EXTENSION);
                $updateArchivo = array(
                    "tabla" => "tblimagenes", "d" => array(
                        "values" => array(
                            "ruta" => $imagenes["data"][0]["ruta"] . $nombreArchivo,
                            "fechaActualizacion" => "now()"
                        ), "where" => array(
                            "idImagen" => $imagenes["data"][0]["idImagen"]
                        )), "proveedor" => $proveedor
                );
                $updatArchivoRs = $genericoDao->update($updateArchivo);

                if ($updatArchivoRs["totalCount"] > 0) {
                    if (copy(($value["ruta"]), $ruta . basename($nombreArchivo))) {
                        
                    } else {
                        $error = true;
                    }
                }
            } else {
                $error = true;
            }
        }
        return $error;
    }

    public function consultarTiposDocumentos($documentoImagenes, $proveedor = null) {
        $sql = array();
        $genericoDao = new GenericDAO();
        $sql["campos"] = "di.*, td.cveTipoDocumento, td.descTipoDocumento, td.extension";
        $sql["tablas"] = "tbldocumentosimg di INNER JOIN tbltiposdocumentos td ON (di.cveTipoDocumento = td.cveTipoDocumento)";
        $sql["where"] = "di.activo='S' AND td.activo='S' AND di.idDocumentoImg =" . $documentoImagenes["data"][0]["idDocumentoImg"];

        $param = array("tabla" => "tblimagenes", "d" => "", "tmpSql" => $sql, "proveedor" => $proveedor);
        $tiposDocumentos = $genericoDao->select($param);
        if ($tiposDocumentos["totalCount"] > 0) {
            return $tiposDocumentos;
        } else {
            return 0;
        }
    }

    public function crearRuta($documentoImagenes, $proveedor = null) {
        $documentos = new Host(dirname(__FILE__) . "/../../tribunal/host/config.xml", "DOCUMENTOSADMINISTRATIVO");
        $documentos = $documentos->getConnect();
        $path = $documentos->DOCUMENTOS;

        $SeguimientoProyectoController = new SeguimientoProyectosController();
        $cveAdscripcion = $SeguimientoProyectoController->getAdscripcionPadre($_SESSION["cveAdscripcion"]);
        if ($cveAdscripcion != 0) {
            $path .= $cveAdscripcion["cveAdscripcion"] . "/" . $documentoImagenes["data"][0]["cveTipoDocumento"] . "/" . $documentoImagenes["data"][0]["idReferencia"] . "/";
        } else {
            $path .= "externos/" . $documentoImagenes["data"][0]["cveTipoDocumento"] . "/" . $documentoImagenes["data"][0]["idReferencia"] . "/";
        }
        return $path;
    }

    private function CreaDirectorio($NomDirectorio) { //Crea el directorio deonde se almacenara la imagen
        $VectorDirectorio = explode("/", $NomDirectorio);
        $ruta = ".";
        foreach ($VectorDirectorio as $Carpeta) {
            if ($Carpeta != "." && trim($Carpeta) != "") {
                $ruta = $ruta . "/" . $Carpeta;
                if (!file_exists($ruta)) {
                    mkdir($ruta);
                }
            }
        }
    }

    public function borrarImagenes($arrIdImagenes) { //Crea el directorio deonde se almacenara la imagen
        $proveedor = new Proveedor('mysql', 'ADMINISTRATIVO');
        $proveedor->connect();
        $proveedor->execute("BEGIN");

        $d = array();
        $sql = array();
        $error = false;
        $where = array();
        $genericoDao = new GenericDAO();
        foreach ($arrIdImagenes as $idImagen) {
            unset($d);
            $valores["activo"] = "N";
            $valores["fechaActualizacion"] = "now()";
            $d["values"] = $valores;
            $where["idImagen"] = $idImagen;
            $d["where"] = $where;
            $param = array("tabla" => "tblimagenes", "d" => $d, "tmpSql" => $sql, "proveedor" => $proveedor);
            $imagen = $genericoDao->update($param);
            if ($imagen["totalCount"] == 0) {
                $error = true;
            }
        }
        if (!$error) {
            $proveedor->execute("COMMIT");
            $respuesta = array("type" => "success");
        } else {
            $proveedor->execute("ROLLBACK");
            $respuesta = array("type" => "error");
        }
        $proveedor->close();
        return json_encode($respuesta);
    }

    /**
     * @param int $idOficio
     * @param int $cveTipoDocumento el ID del elemento $tipo
     * @param int $idReferencia id del del oficio
     * @return array
     *      'status' => 1:Correcto, 0:Error, 
     *      'idImagen' => ID de imagen de tabla tblimagenes
     *      'ruta' => ../../../imagenes/cveJuzgado/anio/nombre_Tipo_Carpeta/expediente/idImagenTipoDocumento.fileExtension
     *      'message' => Texto que muestra resultado de la funciï¿½n
     */
    public function insertaImagenGlobal($idOficio, $cveTipoDocumento, $idReferencia, $cveAdscripcion, $anio, $contenido, $cveUsuario, $proveedor = null) {
//        echo "contenido:".$contenido;
        $proveedor = new Proveedor('mysql', 'ADMINISTRATIVO');
        $proveedor->connect();
        $proveedor->execute("BEGIN");
        $fileExtension = 'pdf';
        $fojas = 1;

        $genericoDao = new GenericDAO();
        $json_encode = new Encode_JSON();
        $d = array("limit" => "");
        $sql = array("campos" => "cveTipoDocumento,descTipoDocumento,extension",
            "tablas" => "tbltiposdocumentos",
            "where" => " cvetipoDocumento=" . $cveTipoDocumento);
        $param = array("tabla" => "", "d" => $d, "tmpSql" => $sql, "proveedor" => null);
        $documentos = $genericoDao->select($param);
        #devuelve descripcion tipo carpeta
        $nombreTipoCarpeta = $documentos['data'][0]['descTipoDocumento'];
        $tipoDocumento = $documentos['data'][0]['descTipoDocumento'];
        # devuelve extensiòn del tipo de documento
        $extension = $documentos['data'][0]['extension'];

        # crea documento
        #verificamos si existe el documento
        $d = array("limit" => "");
        $sql = array("campos" => "idDocumentoImg",
            "tablas" => "tbldocumentosImg",
            "where" => " cvetipoDocumento=" . $cveTipoDocumento . " AND idReferencia=" . $idOficio . " AND activo='S'");
        $param = array("tabla" => "", "d" => $d, "tmpSql" => $sql, "proveedor" => null);
        $row = $genericoDao->select($param);

        if ($row['totalCount'] == 0) {
            #Inserto
            $documentoImg = array(
                "tabla" => "tbldocumentosImg",
                "d" => array(
                    "values" => array(
                        "cveTipoDocumento" => $cveTipoDocumento,
                        "idReferencia" => $idReferencia,
                        "cveUsuario" => $cveUsuario,
                        "fojas" => 1,
                        "activo" => "S",
                        "fechaRegistro" => "now()",
                        "fechaActualizacion" => "now()"
                    )), "proveedor" => $proveedor);
            $array = $genericoDao->insert($documentoImg);


            if ($array['status'] == "success" && $array['totalCount'] != 0) {
                $idDocumentoImg = $array['data'][0]['idDocumentoImg'];
                #crea ruta fisica de directorios

//                $documentos = new Host(dirname(__FILE__) . "/../../tribunal/host/config.xml", "DOCUMENTOSADMINISTRATIVO");
//                $documentos = $documentos->getConnect();
//                $path = $documentos->DOCUMENTOS;
                $path = "../../imagenes"; //Nodo Raiz

                $ruta = $path . '/' . $cveAdscripcion . '/' . $cveTipoDocumento . '/' . $idReferencia;

//                if (!is_dir($ruta)) {
//                    $this->CreaDirectorio($ruta);
//                }
//
//                if (is_dir($ruta)) {
                    #
                    $sql = array("campos" => "idImagen",
                        "tablas" => "tblimagenes",
                        "orders" => " idImagen DESC LIMIT 1");
                    $param = array("tabla" => "", "d" => $d, "tmpSql" => $sql, "proveedor" => null);
                    $genericoDao = new GenericDAO();
                    $tmpImagenes = $genericoDao->select($param);
                    if ($tmpImagenes['totalCount'] == 0) {
                        $idImagen = 1;
                    } else {
                        $idImagen = ($tmpImagenes["data"][0]["idImagen"]) + 1;
                    }

                    # arma ruta con posicion
                    $ruta = $ruta . '/' . $idImagen . $extension . '.' . $fileExtension;

                    #Inserto tblimagenes
                    $Imagen = array(
                        "tabla" => "tblimagenes",
                        "d" => array(
                            "values" => array(
                                "idDocumentoImg" => $idDocumentoImg,
                                "ruta" => $ruta,
                                "activo" => "S",
                                "adjunto" => "N",
                                "posicion" => 1,
                                "descripcion" => $contenido,
                                "fechaRegistro" => "now()",
                                "fechaActualizacion" => "now()"
                            )), "proveedor" => $proveedor);
                    $arrayImagenes = $genericoDao->insert($Imagen);

                    if ($arrayImagenes['status'] == "success" && $arrayImagenes['totalCount'] != 0) {
                        $proveedor->execute("COMMIT");
                        $idImagen = $arrayImagenes['data'][0]['idImagen'];
                        $ruta = $arrayImagenes['data'][0]['ruta'];
                        return array('status' => 1, 'idImagen' => $idImagen, 'ruta' => $ruta, 'message' => 'Archivo agregado correctamente');
                    } else {
                        $proveedor->execute("ROLLBACK");
                        $proveedor->close();
                        return array('status' => 0, 'idImagen' => 0, 'ruta' => '', 'message' => 'ERROR al insertar los datos de la imagen.');
                    }
//                } else {
//                    $proveedor->execute("ROLLBACK");
//                    $proveedor->close();
//                    return array('status' => 0, 'idImagen' => 0, 'ruta' => '', 'message' => 'ERROR al crear el arbol de directorios de la imagen. [' . $ruta . '].');
//                }
            } else {
                $proveedor->execute("ROLLBACK");
                $proveedor->close();
                return array('status' => 0, 'idImagen' => 0, 'ruta' => '', 'message' => 'ERROR al crear el documentoIMG.');
            }
        } else {

            $idDocumentoImg = $row['data'][0]['idDocumentoImg'];
            $d = array("limit" => "");
            $sql = array("campos" => "idImagen,idDocumentoImg,ruta,adjunto,descripcion,posicion,activo",
                "tablas" => "tblimagenes",
                "where" => " idDocumentoImg=" . $idDocumentoImg . " AND activo='S'");
            $param = array("tabla" => "", "d" => $d, "tmpSql" => $sql, "proveedor" => null);
            $arrayImagenes = $genericoDao->select($param);

            $idImagen = $arrayImagenes['data'][0]['idImagen'];
            $ruta = $arrayImagenes['data'][0]['ruta'];

            return array('status' => 1, 'idImagen' => $idImagen, 'ruta' => $ruta, 'message' => 'Consulta correc');
        }
    }
    public function obtenerRutaFormatos($tipo) {
		$documentos = new Host(dirname(__FILE__) . "/../../tribunal/host/config.xml", "DOCUMENTOSADMINISTRATIVO");
        $documentos = $documentos->getConnect();
		if($tipo=="FORMATOS_GENERALES"){
            return $documentos->FORMATOS_GENERALES;    
        }elseif($tipo=="PROCEDIMIENTOS"){
            return $documentos->PROCEDIMIENTOS;    
        }elseif($tipo=="FORMATOS"){
            return $documentos->FORMATOS;    
        }
		return $documentos->FORMATOS;
 }

    public function copiarFormatos($paramFiles, $documentoImagenes, $proveedor = null) {
        $error = false;
        $d = array();
        $values = array();
        $genericoDao = new GenericDAO();
        $ruta = $this->obtenerRutaFormatos("FORMATOS_GENERALES");
        $this->CreaDirectorio($ruta);
        foreach ($paramFiles as $key => $value) {
            unset($d);
            unset($values);            
            $values["idDocumentoImg"] = $documentoImagenes["data"][0]["idDocumentoImg"];
            $values["ruta"] = str_replace("../", "", $ruta);
            $values["adjunto"] = "S";
            $values["descripcion"] = utf8_decode($documentoImagenes["data"][0]["descripcion"] . '.' . pathinfo(utf8_decode($value["name"]), PATHINFO_EXTENSION));
            $values["posicion"] = 1;
            $values["activo"] = "S";
            $values["fechaRegistro"] = "now()";
            $values["fechaActualizacion"] = "now()";
            $d["values"] = $values;
            $param = array("tabla" => "tblimagenes", "d" => $d, "tmpSql" => "", "proveedor" => $proveedor);
            $imagenes = $genericoDao->insert($param);
            if ($imagenes["totalCount"] > 0) {
                $nombreArchivo = $imagenes["data"][0]["idImagen"] . $documentoImagenes["data"][0]["extension"] . "." . pathinfo(utf8_decode($value["name"]), PATHINFO_EXTENSION);
                $updateArchivo = array(
                    "tabla" => "tblimagenes", "d" => array(
                        "values" => array(
                            "ruta" => $imagenes["data"][0]["ruta"] . $nombreArchivo,
                            "fechaActualizacion" => "now()"
                        ), "where" => array(
                            "idImagen" => $imagenes["data"][0]["idImagen"]
                        )), "proveedor" => $proveedor
                );
                $updatArchivoRs = $genericoDao->update($updateArchivo);
                if ($updatArchivoRs["totalCount"] > 0) {
                    if (move_uploaded_file(($value["tmp_name"]), $ruta . basename($nombreArchivo))) {
                        //echo "ruta:". $ruta . basename($nombreArchivo)."<br>";
                    } else {
                        $error = true;
                    }
                }
            } else {
                $error = true;
            }
        }
        return $error;
    }
    public function copiarArchivosIntranet($tipo,$file, $documentoImagenes, $proveedor = null) {
        $error = false;
        $posicion=1;
        $d = array();
        $values = array();
        $genericoDao = new GenericDAO();        
        $ruta = $this->obtenerRutaFormatos($tipo);
        $this->CreaDirectorio($ruta);        
        // foreach ($paramFiles as $key => $value) {            
            $values["idDocumentoImg"] = $documentoImagenes["data"][0]["idDocumentoImg"];
            $values["ruta"] = str_replace("../", "", $ruta);
            $values["adjunto"] = "S";            
            $values["descripcion"] = utf8_decode($documentoImagenes["data"][0]["descripcion"].'.'.pathinfo(utf8_decode($file["name"]), PATHINFO_EXTENSION));
            $values["posicion"] = $posicion++;
            $values["activo"] = "S";
            $values["fechaRegistro"] = "now()";
            $values["fechaActualizacion"] = "now()";
            $d["values"] = $values;
            $param = array("tabla" => "tblimagenes", "d" => $d, "tmpSql" => "", "proveedor" => $proveedor);
            $imagenes = $genericoDao->insert($param);            
            // var_dump($documentoImagenes);            
            if ($imagenes["totalCount"] > 0) {
                $nombreArchivo = $imagenes["data"][0]["idImagen"] . $documentoImagenes["data"][0]["extension"] . "." . pathinfo(utf8_decode($file["name"]), PATHINFO_EXTENSION);                
                $updateArchivo = array(
                    "tabla" => "tblimagenes", "d" => array(
                        "values" => array(
                            "ruta" => $imagenes["data"][0]["ruta"] . $nombreArchivo,
                            "fechaActualizacion" => "now()"
                        ), "where" => array(
                            "idImagen" => $imagenes["data"][0]["idImagen"]
                        )), "proveedor" => $proveedor
                );
                $updatArchivoRs = $genericoDao->update($updateArchivo);                
                if ($updatArchivoRs["totalCount"] > 0) {
                    if (move_uploaded_file(($file["tmp_name"]), $ruta . basename($nombreArchivo))) {
                        //echo "ruta:". $ruta . basename($nombreArchivo)."<br>";
                    }else {
                        $error = true;
                    }
                }
            } else {
                $error = true;
            }
        // }
        return $error;
    }

//# cierra insertaImagenglobal
}
