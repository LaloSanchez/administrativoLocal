<?php

include_once(dirname(__FILE__) . "/../../modelos/dao/GenericDAO.Class.php");
include_once(dirname(__FILE__) . "/../../tribunal/json/JsonEncod.Class.php");
include_once(dirname(__FILE__) . "/../../tribunal/json/JsonDecod.Class.php");
include_once(dirname(__FILE__) . "/../../controladores/planeacion/SeguimientoProyectosController.Class.php");
include_once(dirname(__FILE__) . "/../../controladores/cuadrocomparativo/CuadroComparativoController.Class.php");

class EtiquetasController {

    private $proveedor;
    private $adscripcionPadreArray = array();
    private $logger;

    public function __construct() {
        $this->logger = new Logger("/../../logs/", "GenericDAO");
        $this->proveedor = new Proveedor('mysql', 'ADMINISTRATIVO');
        $seguimientoProyectosController = new SeguimientoProyectosController();
        if (substr($_SESSION["cveOrganigrama"], -3) != "000") {
            $adscripcionPadre = $seguimientoProyectosController->getAdscripcionPadre($_SESSION["cveAdscripcion"]);
            $this->adscripcionPadreArray["cveAdscripcion"] = $adscripcionPadre["cveAdscripcion"];
            $this->adscripcionPadreArray["cveOrganigrama"] = $adscripcionPadre["cveOrganigrama"];
        } else {
            $this->adscripcionPadreArray["cveAdscripcion"] = $_SESSION["cveAdscripcion"];
            $this->adscripcionPadreArray["cveOrganigrama"] = $_SESSION["cveOrganigrama"];
        }
    }

    function getAdscripcionPadreArray() {
        return $this->adscripcionPadreArray;
    }

    function setAdscripcionPadreArray($adscripcionPadreArray) {
        $this->adscripcionPadreArray = $adscripcionPadreArray;
    }

    public function varDumpToString($var) {
        ob_start();
        var_dump($var);
        $result = ob_get_clean();
        return $result;
    }

    public function guardarLogger($msn = null, $variable = null) {
        $this->logger->w_onError("######### " . $msn . " ##########");
        $this->logger->w_onError($this->varDumpToString($variable));
    }

    /**
     * FunciOn para transformar el formato de salida o entrada, segUn sea el caso
     * @param date|datetime $fechaEntrada Opciones: AAAA-MM-DD | AAAA-MM-DD HH:MM:SS | DD/MM/AAAA | DD/MM/AAAA HH:MM:SSS Es la fecha de entrada
     * @param text $fechaEntrada Opciones: fecha | fechaHora Es el formato en que se recibe la fecha
     * @param text $formatoSalida Opciones: pjem | mysql Corresponde al formato de salida. pjem: DD/MM/AAAA | DD/MM/AAA HH:MM:SS, mysql: AAAA-MM-DD | AAAA-MM-DD HH:MM:SS
     */
    public function formatoFecha($fechaEntrada, $tipo, $formatoSalida, $tipoSalida) {
        $fechaEntrada = ( $fechaEntrada != '' ) ? $fechaEntrada : '0000-00-00 00:00:00';
        $formatoSalida = ( $formatoSalida == 'pjem') ? 'pjem' : 'mysql';
        $tipo = ( $tipo == 'fecha' ) ? 'fecha' : 'fechaHora';
        $tipoSalida = ( $tipoSalida == 'fecha') ? 'fecha' : 'fechaHora';
        $delimitador = ( $formatoSalida == 'mysql' ) ? array('origen' => '/', 'destino' => '-') : array('origen' => '-', 'destino' => '/');
        $fechaSalida = '';
        if ($tipo == 'fecha') {
            $tmpFecha = explode($delimitador['origen'], $fechaEntrada);
            $fechaSalida = $tmpFecha[2] . $delimitador['destino'] . $tmpFecha[1] . $delimitador['destino'] . $tmpFecha[0];
            if ($tipoSalida == 'fechaHora') {
                $fechaSalida .= ' 00:00:00';
            }
        } elseif ($tipo == 'fechaHora') {
            $tmpCompleto = explode(' ', $fechaEntrada);
            $tmpFecha = explode($delimitador['origen'], $tmpCompleto[0]);
            $fechaSalida = $tmpFecha[2] . $delimitador['destino'] . $tmpFecha[1] . $delimitador['destino'] . $tmpFecha[0];
            if ($tipoSalida == 'fechaHora') {
                $fechaSalida .= ' ' . $tmpCompleto[1];
            }
        }
        return $fechaSalida;
    }

    public function getAdscripcionNombre($ads = null) {
        $fileJson = dirname(__FILE__) . "/../../archivos/informacionEmpleados.json";
        if (file_exists($fileJson)) {
            $json = file_get_contents($fileJson);
            $decode=new Decode_JSON();
            $array=$decode->decode($json);
            foreach($array->data as $key => $value){
                if($value->idJuzgado == $ads){
                    $encode= new Encode_JSON();
                    return $encode->encode(utf8_decode($value->desJuz));
                }
            }
        }else{
            return 0;
        }
    }
    
    function getIdsJuzgados(){
        $fileJson = dirname(__FILE__) . "/../../archivos/informacionEmpleados.json";
        $resp="";
        if (file_exists($fileJson)) {
            $json = file_get_contents($fileJson);
            $decode=new Decode_JSON();
            $array=$decode->decode($json);
            foreach($array->data as $key => $value){
                $resp .=$value->idJuzgado.",";
            }
        }
        $resp= trim($resp,",");
        $resp="(".$resp.")";
        return $resp;
    }

    function validateDate($date, $format = 'Y-m-d H:i:s') {
        $d = DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) == $date;
    }

    function fechaNormal($fecha, $hora = false) {
        $arrFecha = explode(" ", $fecha);
        list($year, $mes, $dia) = explode("-", $arrFecha[0]);
        if ($hora)
            return $dia . "/" . $mes . "/" . $year . " " . $arrFecha[1];
        else
            return $dia . "/" . $mes . "/" . $year . " ";
    }

    public function datatableGenerico($params, $param, $limit, $nombreTabla, $condiciones = "", $agrupacion = "", $orders = "", $extras = null) {
        $personal = new SeguimientoProyectosController();

        $genericoDao = new GenericDAO();
        $row = $genericoDao->select($param);
        if (($row != "") && (sizeof($row) > 0)) {
            $d = array("campos" => "");
            $d = array_merge($d, array());

            $sql = array("campos" => "count(*) as Total", "values" => "", "tablas" => $nombreTabla, "where" => $condiciones, "groups" => $agrupacion, "orders" => $orders);

            $param = array("tabla" => "", "d" => $d, "tmpSql" => $sql, "proveedor" => null);
            $arrayTot = $genericoDao->select($param);
            $data = array();
            for ($index = 0; $index < sizeof(@$row["data"]); $index++) {
                $registro = array();
                foreach ($row["data"][$index] as $key => $value) {
                    $nombreEmpleado = "";
                    if ($key == "numEmpleadoResponsable" || $key == "numEmpleadoElaboro" || $key == "numEmpleadoReviso" || $key == "numEmpleadoAprobo" || $key == "numEmpleadoRealiza" || $key == "numEmpleadoResguardo") {
                        if ($value != "") {
                            $nombreEmpleadoRs = json_decode($personal->getNombrePersonalCliente($value));
                            if (intval($nombreEmpleadoRs->totalCount) > 0) {
                                if ($nombreEmpleadoRs->data[0]->TituloTrato == "NULL") {
                                    $nombreEmpleado = $nombreEmpleadoRs->data[0]->Nombre . " " . $nombreEmpleadoRs->data[0]->Paterno . " " . $nombreEmpleadoRs->data[0]->Materno;
                                    $registro[] = $nombreEmpleado;
                                } else {
                                    $nombreEmpleado = $nombreEmpleadoRs->data[0]->TituloTrato . " " . $nombreEmpleadoRs->data[0]->Nombre . " " . $nombreEmpleadoRs->data[0]->Paterno . " " . $nombreEmpleadoRs->data[0]->Materno;
                                    $registro[] = $nombreEmpleado;
                                }
                            } else {
                                
                            }
                        } else {
                            $registro[] = "";
                        }
                    }
                    if ($key == "cveRegion") {
                        $registro[] = $this->getRegionesConsulta($value, '')->data[0]->nomRegion;
                    }
                    if ($key == "idTrimestre") {
                        $registro[] = $this->getTrimestresConsulta($value)->data[0]->desTrimestre;
                    }
                    if ($key == "cveAdscripcion" || $key == "cveAdscripcionDevuelve" || $key == "cveAdsAlmacen") {
                        if ($value != "") {
                            $registro[] = $value;
                            $registro[] = $this->getAdscripcionNombre($value);
                        } else {
                            $registro[] = "";
                        }
                    } else {
                        if ($this->validateDate($value)) {
                            if (array_key_exists("fechaHora", $extras) && $extras["fechaHora"]) {
                                $registro[] = $this->fechaNormal($value, true);
                            } elseif (array_key_exists("fecha", $extras) && $extras["fecha"]) {
                                $registro[] = $this->fechaNormal($value);
                            }
                        } else {
                            $registro[] = $value;
                        }
                    }
                }
                $data[] = $registro;
            }
            $output = array(
                "draw" => $params["draw"],
                "recordsTotal" => (int) $row["totalCount"],
                "recordsFiltered" => (int) @$arrayTot["data"][0]["Total"],
                "start" => $limit["pag"],
                "length" => $limit["max"],
                "data" => $data);
            $json = new Encode_JSON();

            return $json->encode($output);
        } else {
            return json_encode(array("status" => "error", "totalCount" => 0, "msg" => "sin informacion a mostrar"));
        }
    }

    public function getContador($param, $p = null) {
        $contadoresController = new ContadoresController();
        $contadores["cveAdscripcion"] = $this->adscripcionPadreArray["cveAdscripcion"];
        $contadores["cveTipoDocContador"] = $param["cveTipoDocContador"];
        $contadores["cveOrganigrama"] = $this->adscripcionPadreArray["cveOrganigrama"];
        $contadores["mes"] = "N";
        $contadores["cveMes"] = 0;
        $contadores["anio"] = date("Y");
        $contadoresRs = $contadoresController->getContador($contadores, $p);
        $this->guardarLogger("GENERA CONTADOR", $contadoresRs);
        return $contadoresRs;
    }

    function cargarClasificadorBienes($param, $p = null) {
        $d = array();
        $genenericDAO = new GenericDAO();
        $sql = array(
            "campos" => "
                tblclasificadoresbienes.*
            ",
            "tablas" => "
                tblclasificadoresbienes tblclasificadoresbienes
            ",
            "where" => "
                tblclasificadoresbienes.activo = 'S'                
            "
        );
        $sqlSelect = array("tabla" => "", "d" => $d, "tmpSql" => $sql, "proveedor" => $p);
        $rs = $genenericDAO->select($sqlSelect);

        return $rs;
    }

    public function verDetalleCBM($param, $inventario, $p = null) {
        $d = array();
        $genenericDAO = new GenericDAO();
        $sql = array(
            "campos" => "    
                tblcbm.idCbm,
                tblcolores.cveColor,
                tblcolores.desColor,
                tblmateriales.cveMaterial,
                tblmateriales.desMaterial,
                tblfrecuenciasusos.cveFrecuenciaUso,
                tblfrecuenciasusos.desFrecuenciaUso,
                tblunidadesmedida.cveUnidadMedida,
                tblunidadesmedida.desUnidadMedida,
                tblcbm.denominacion,
                tblcbm.marca,
                tblcbm.modelo,
                tblestadosbienes.cveEstadoBien,
                tblestadosbienes.desEstadoBien,
                tblinventarios.numeroSerie,
                tblinventarios.codigoPropio,
                tblinventarios.codigoAnterior,
                tblinventarios.precioCompra,
                tblinventarios.precioActual,
                tblinventarios.aniosVidaUtil,
                tblinventarios.fechaCompra,
                tblinventarios.garantia,
                tblinventarios.fechaInicioGarantia,
                tblinventarios.fechaFinGarantia,
                tblresguardos.*
            ",
            "tablas" => "  
                tblinventarios tblinventarios 
                INNER JOIN tblcbm tblcbm 
                ON tblinventarios.idReferencia = tblcbm.idCbm 
                INNER JOIN tblestadosbienes tblestadosbienes 
                ON tblinventarios.cveEstadoBien = tblestadosbienes.cveEstadoBien 
                INNER JOIN tblcolores tblcolores 
                ON tblcbm.cveColor = tblcolores.cveColor 
                INNER JOIN tblmateriales tblmateriales 
                ON tblcbm.cveMaterial = tblmateriales.cveMaterial 
                INNER JOIN tblfrecuenciasusos tblfrecuenciasusos 
                ON tblcbm.cvefrecuenciaUso = tblfrecuenciasusos.cveFrecuenciaUso 
                INNER JOIN tblunidadesmedida tblunidadesmedida 
                ON tblcbm.cveUnidadMedida = tblunidadesmedida.cveUnidadMedida 
                INNER JOIN tblresguardos tblresguardos
                ON tblresguardos.idInventario = tblinventarios.idInventario
            ",
            "where" => "  
                (tblinventarios.idInventario = " . $param["extrasPost"]["idInventario"] . ") AND
                 tblresguardos.activo = 'S'
            "
        );
        $sqlSelect = array("tabla" => "", "d" => $d, "tmpSql" => $sql, "proveedor" => $p);
        $rs = $genenericDAO->select($sqlSelect);

        return $rs;
    }

    public function verDetalleCBI($param, $inventario, $p = null) {
        $d = array();
        $genenericDAO = new GenericDAO();
        $sql = array(
            "campos" => "    
                tblcbi.idCbi,
                tblcogbienes.descripcion,
                tbltipospropiedad.cveTipoPropiedad,
                tbltipospropiedad.desTipoPropiedad,
                tblcbi.cveCatastral,
                tblcbi.superficie,
                tblcbi.desEscritura,
                tblcbi.denominacion,
                tblsituaciones.cveSituacion,
                tblsituaciones.desSituacion,
                tblinventarios.numeroSerie,
                tblinventarios.codigoPropio,
                tblinventarios.codigoAnterior,
                tblinventarios.precioCompra,
                tblinventarios.precioActual,
                tblinventarios.aniosVidaUtil,
                tblinventarios.fechaCompra,
                tblinventarios.garantia,
                tblinventarios.fechaInicioGarantia,
                tblinventarios.fechaFinGarantia,
                tblestadosbienes.cveEstadoBien,
                tblestadosbienes.desEstadoBien ,
                tblresguardos.*
            ",
            "tablas" => "  
                tblinventarios tblinventarios 
                INNER JOIN tblcbi tblcbi 
                ON tblinventarios.idReferencia = tblcbi.idCbi 
                INNER JOIN tblestadosbienes tblestadosbienes 
                ON tblinventarios.cveEstadoBien = tblestadosbienes.cveEstadoBien 
                INNER JOIN tblcogbienes tblcogbienes 
                ON tblcbi.idCogBien = tblcogbienes.idCogBien 
                INNER JOIN tbltipospropiedad tbltipospropiedad 
                ON tblcbi.cveTipoPropiedad = tbltipospropiedad.cveTipoPropiedad 
                INNER JOIN tblsituaciones tblsituaciones 
                ON tblcbi.cveSituacion = tblsituaciones.cveSituacion 
                INNER JOIN tblresguardos tblresguardos
                ON tblresguardos.idInventario = tblinventarios.idInventario
            ",
            "where" => "  
                (tblinventarios.idInventario = " . $param["extrasPost"]["idInventario"] . ") AND
                 tblresguardos.activo = 'S'
            "
        );
        $sqlSelect = array("tabla" => "", "d" => $d, "tmpSql" => $sql, "proveedor" => $p);
        $rs = $genenericDAO->select($sqlSelect);

        return $rs;
    }

    public function verDetalleAAH($param, $inventario, $p = null) {
        $d = array();
        $genenericDAO = new GenericDAO();
        $sql = array(
            "campos" => "    
                tblaah.idAah,
                tblaah.denominacion,
                tblcogbienes.descripcion,
                tblestadosbienes.cveEstadoBien,
                tblestadosbienes.desEstadoBien,
                tblinventarios.numeroSerie,
                tblinventarios.codigoPropio,
                tblinventarios.codigoAnterior,
                tblinventarios.precioCompra,
                tblinventarios.precioActual,
                tblinventarios.valorDesecho,
                tblinventarios.aniosVidaUtil,
                tblinventarios.fechaCompra,
                tblinventarios.garantia,
                tblinventarios.fechaInicioGarantia,
                tblinventarios.fechaFinGarantia ,
                tblinventarios.cveClasificadorBien ,
                tblresguardos.*
            ",
            "tablas" => "  
                tblinventarios tblinventarios 
                INNER JOIN tblaah tblaah 
                ON tblinventarios.idReferencia = tblaah.idAah 
                INNER JOIN tblestadosbienes tblestadosbienes 
                ON tblinventarios.cveEstadoBien = tblestadosbienes.cveEstadoBien 
                INNER JOIN tblcogbienes tblcogbienes 
                ON tblaah.idCogBien = tblcogbienes.idCogBien 
                INNER JOIN tblresguardos tblresguardos
                ON tblresguardos.idInventario = tblinventarios.idInventario
            ",
            "where" => "  
                (tblinventarios.idInventario = " . $param["extrasPost"]["idInventario"] . ") AND
                 tblresguardos.activo = 'S'
            "
        );
        $sqlSelect = array("tabla" => "", "d" => $d, "tmpSql" => $sql, "proveedor" => $p);
        $rs = $genenericDAO->select($sqlSelect);

        return $rs;
    }

    public function verDetalle($param, $p = null) {
        $d = array();
        $respuesta = "";
        $genenericDAO = new GenericDAO();
        $sql = array(
            "campos" => "    
                tblinventarios.*
            ",
            "tablas" => "  
                tblinventarios tblinventarios
            ",
            "where" => "  
                tblinventarios.activo = 'S' AND 
                tblinventarios.idInventario = " . $param["extrasPost"]["idInventario"] . " 
            "
        );
        $sqlSelect = array("tabla" => "", "d" => $d, "tmpSql" => $sql, "proveedor" => $p);
        $rs = $genenericDAO->select($sqlSelect);
        if ($rs["totalCount"] > 0) {
            if ($rs["data"][0]["cveClasificadorBien"] == "1") {
                $respuesta = $this->verDetalleCBM($param, $rs["data"][0], $p);
            } elseif ($rs["data"][0]["cveClasificadorBien"] == "2") {
                $respuesta = $this->verDetalleCBI($param, $rs["data"][0], $p);
            } elseif ($rs["data"][0]["cveClasificadorBien"] == "7") {
                $respuesta = $this->verDetalleAAH($param, $rs["data"][0], $p);
            }
        } else {
            return $rs;
        }
        $SeguimientoProyectosController = new SeguimientoProyectosController();
        $cuadro = new CuadroComparativoController();
        $json = new Decode_JSON();
        foreach ($respuesta["data"] as $key => $value) {
//            var_dump($key);
//            var_dump($value);
            $nombre = $json->decode($SeguimientoProyectosController->getNombrePersonalCliente($value["numEmpleadoResguardo"]));
//            var_dump($nombre);
            $respuesta["data"][$key]["nombreAdscripcion"] = $cuadro->getAdscripcionNombre($value["cveAdscripcion"]);

            if ($nombre->data[0]->TituloTrato == "NULL") {
                $respuesta["data"][$key]["nombreEmpleadoResguardo"] = $nombre->data[0]->Nombre . " " . $nombre->data[0]->Paterno . " " . $nombre->data[0]->Materno;
            } else {
                $respuesta["data"][$key]["nombreEmpleadoResguardo"] = $nombre->data[0]->TituloTrato . " " . $nombre->data[0]->Nombre . " " . $nombre->data[0]->Paterno . " " . $nombre->data[0]->Materno;
            }
        }

        return $respuesta;
    }

    function cargarEstadosBienes($param, $p = null) {
        $d = array();
        $genenericDAO = new GenericDAO();
        $sql = array(
            "campos" => "
                tblestadosbienes.*
            ",
            "tablas" => "
                tblestadosbienes tblestadosbienes
            ",
            "where" => "
                tblestadosbienes.activo = 'S'                
            "
        );
        $sqlSelect = array("tabla" => "", "d" => $d, "tmpSql" => $sql, "proveedor" => $p);
        $rs = $genenericDAO->select($sqlSelect);

        return $rs;
    }

    public function consultarEmpleadosAdscripcion($param) {
        $fileJson = dirname(__FILE__) . "/../../archivos/informacionEmpleados.json";
        if (file_exists($fileJson)) {
            $json = file_get_contents($fileJson);
            $decode=new Decode_JSON();
            $array=$decode->decode($json);
            foreach($array->data as $key => $value){
                if($value->idJuzgado == $param["extrasPost"]["cveAdscripcion"]){
                    $encode= new Encode_JSON();
                    return $encode->encode($value->personal);
                }
            }
        }else{
            return '{"estatus":"sinAds","totalCount":"0"}';
        }
    }
    
    public function getInventariosIN($inInventarios, $p = null) {
        $genericoDao = new GenericDAO();
        $sql = array(
            "campos" => "   
                tblinventarios.*
            ",
            "tablas" => "
                tblinventarios tblinventarios
            ",
            "where" => " 
                tblinventarios.idInventario IN (" . $inInventarios . ")
            "
        );
        $sqlSelect = array("tabla" => "", "d" => $d, "tmpSql" => $sql, "proveedor" => $p);
        $rs = $genericoDao->select($sqlSelect);
        return $rs;
    }

    public function datatableConsultaBienes($params) {
        $limit = array("max" => $params["limit"]["max"],
            "pag" => $params["limit"]["pag"]);
        $d = array("limit" => $limit);
        $sql = array();
//        var_dump($params);
        $sql["campos"] = "
                tblinventarios.idInventario,
                tblinventarios.cveClasificadorBien,
                tblclasificadoresbienes.desClasificadorBien,
                tblinventarios.cveEstadoBien,
                tblestadosbienes.desEstadoBien,
                tblinventarios.numeroSerie,
                tblresguardos.cveAdscripcion,
                tblresguardos.numEmpleadoResguardo,
                tblresguardos.fechaAsigancion
            ";
        $sql["tablas"] = "
                tblinventarios tblinventarios 
                INNER JOIN tblestadosbienes tblestadosbienes 
                ON (tblinventarios.cveEstadoBien = tblestadosbienes.cveEstadoBien AND tblestadosbienes.activo = 'S')
                INNER JOIN tblclasificadoresbienes tblclasificadoresbienes 
                ON (tblinventarios.cveClasificadorBien = tblclasificadoresbienes.cveClasificadorBien AND tblclasificadoresbienes.activo = 'S')
                LEFT JOIN tblresguardos tblresguardos 
                ON (tblinventarios.idInventario = tblresguardos.idInventario AND tblresguardos.activo = 'S')
            ";
        $sql["where"] = "            
            (tblinventarios.activo = 'S')
        ";

        if ($params["extrasPost"]["cveAdscripcion"] != "") {
            $sql["where"] .= "
            AND
            (tblresguardos.cveAdscripcion = " . $params["extrasPost"]["cveAdscripcion"] . ")
        ";
        }else{
            $ids=$this->getIdsJuzgados();
            $sql["where"] .= "
            AND
            (tblresguardos.cveAdscripcion in " . $ids . ")
        ";
        }

        if ($params["extrasPost"]["cveClasificadorBien"] != "") {
            $sql["where"] .= "
            AND
            (tblclasificadoresbienes.cveClasificadorBien = " . $params["extrasPost"]["cveClasificadorBien"] . ")
        ";
        }

        if ($params["extrasPost"]["numEmpleadoResguardo"] != "") {
            $sql["where"] .= "
            AND
            (tblresguardos.numEmpleadoResguardo = " . $params["extrasPost"]["numEmpleadoResguardo"] . ")
        ";
        }

        if ($params["extrasPost"]["cveEstadoBien"] != "") {
            $sql["where"] .= "
            AND
            (tblestadosbienes.cveEstadoBien = " . $params["extrasPost"]["cveEstadoBien"] . ")
        ";
        }


        if ($params["extrasPost"]["cveClasificadorBien"] == "1") {
            $sql["campos"] .= ",";
            $sql["campos"] .= "
                tblcbm.idCbm,
                tblcbm.idCogBien,                                
                tblcbm.denominacion,
                tblinventarios.codigoPropio,
                tblcbm.marca,
                tblcbm.modelo
            ";

            $sql["tablas"] .= "
                INNER JOIN tblcbm tblcbm
                ON tblcbm.idCbm = tblinventarios.idReferencia 
            ";

            $sql["where"] .= "
                AND
               tblcbm.activo = 'S'
            ";
            if ($params["extrasPost"]["codigoPropioInicio"] != "" && $params["extrasPost"]["codigoPropioFin"]) {
                $sql["where"] .= "
                    AND 
                    tblinventarios.idInventario >= " . explode("M", $params["extrasPost"]["codigoPropioInicio"])[1] . " AND
                        tblinventarios.idInventario <= " . explode("M", $params["extrasPost"]["codigoPropioFin"])[1] . "
                ";
            }
        } elseif ($params["extrasPost"]["cveClasificadorBien"] == "7") {
            $sql["campos"] .= ",";
            $sql["campos"] .= "
                tblaah.idAah,
                tblaah.idCogBien,
                tblaah.denominacion,
                tblinventarios.codigoPropio,
                tblcogbienes.descripcion,
                '' as nada
            ";

            $sql["tablas"] .= "
                INNER JOIN tblaah tblaah
                ON tblaah.idAah = tblinventarios.idReferencia
                INNER JOIN tblcogbienes tblcogbienes
                ON tblcogbienes.idCogBien = tblaah.idCogBien
            ";

            $sql["where"] .= "
                AND
               tblaah.activo = 'S'
            ";
            if ($params["extrasPost"]["codigoPropioInicio"] != "" && $params["extrasPost"]["codigoPropioFin"]) {
                $sql["where"] .= "
                    AND 
                    tblinventarios.idInventario >= " . explode("A", $params["extrasPost"]["codigoPropioInicio"])[1] . " AND
                    tblinventarios.idInventario <= " . explode("A", $params["extrasPost"]["codigoPropioFin"])[1] . "
                ";
            }
        } elseif ($params["extrasPost"]["cveClasificadorBien"] == "2") {
            $sql["campos"] .= ",";
            $sql["campos"] .= "
                tblcbi.idCbi,
                tblcbi.idCogBien,   
                tblcbi.denominacion,
                tblinventarios.codigoPropio,
                tblcogbienes.descripcion,
                '' as nada
            ";

            $sql["tablas"] .= "
                INNER JOIN tblcbi tblcbi
                ON tblcbi.idCbi = tblinventarios.idReferencia
                INNER JOIN tblcogbienes tblcogbienes
                ON tblcogbienes.idCogBien = tblcbi.idCogBien
            ";

            $sql["where"] .= "
                AND
               tblcbi.activo = 'S'
            ";
            if ($params["extrasPost"]["codigoPropioInicio"] != "" && $params["extrasPost"]["codigoPropioFin"]) {
                $sql["where"] .= "
                    AND 
                    tblinventarios.idInventario >= " . explode("I", $params["extrasPost"]["codigoPropioInicio"])[1] . " AND
                    tblinventarios.idInventario <= " . explode("I", $params["extrasPost"]["codigoPropioFin"])[1] . "
                ";
            }
        }
        $sql["orders"] = $params["order"]["column"] . " " . $params["order"]["dir"];


//        $sql = array(
//            "campos" => " 
//                
//            ",
//            "tablas" => "
//               
//            ",
//            "orders" => $params["order"]["column"] . " " . $params["order"]["dir"],
//            "where" => " 
//                
//            "
//        );
        if ($params['search']['value'] != "") {
            $arrayCampos = explode(",", $sql["campos"]);
            foreach ($arrayCampos as $key => $value) {
                if ($key == 0)
                    $sql["where"] .= " AND ( " . $value . " like '%" . $params['search']['value'] . "%' ";
                else
                    $sql["where"] .= " OR " . $value . " like '%" . $params['search']['value'] . "%' ";
            }
            $sql["where"] .= " ) ";
        }
        $param = array("tabla" => "", "d" => $d, "tmpSql" => $sql, "proveedor" => null);
        return $this->datatableGenerico($params, $param, $limit, $nombreTabla = $sql['tablas'], $sql['where'], "", "", array("fechaHora" => true));
    }

}
