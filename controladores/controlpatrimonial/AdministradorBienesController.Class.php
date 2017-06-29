<?php

include_once(dirname(__FILE__) . "/../../modelos/dao/GenericDAO.Class.php");
include_once(dirname(__FILE__) . "/../../tribunal/json/JsonEncod.Class.php");
include_once(dirname(__FILE__) . "/../../tribunal/json/JsonDecod.Class.php");
include_once(dirname(__FILE__) . "/../../controladores/generic/GenericController.Class.php");
include_once(dirname(__FILE__) . "/../../controladores/bitacora/BitacoraController.Class.php");
include_once(dirname(__FILE__) . "/../../controladores/planeacion/SeguimientoProyectosController.Class.php");
include_once(dirname(__FILE__) . "/../../webservice/cliente/regiones/RegionCliente.php");
include_once(dirname(__FILE__) . "/../../webservice/cliente/personal/PersonalCliente.php");

class AdministradorBienesController {

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
        $fileJson = "../../archivos/juzgados" . date("Ymd") . ".json";
        if (file_exists($fileJson)) {
            $json = file_get_contents($fileJson);
            $json = json_decode($json, true);
            $buscarPadre = false;
            $cadenaBuscarPadre = "";
            if ($json["totalCount"] > 0) {
                foreach ($json["resultados"] as $key => $value) {
                    foreach ($value as $key2 => $value2) {
                        if ($key2 == "idJuzgado" && $value2 == $ads) {
                            return utf8_decode($value["desJuz"]);
                        }
                    }
                }
            } else {
                return 0;
            }
        } else {
            return 0;
        }
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
            if ($agrupacion != "") {
                $condicion = "";
                if ($condiciones != "") {
                    $condicion = " where " . $condiciones;
                }
                if ($agrupacion != "") {
                    $grupo = " group by " . $agrupacion;
                }
                #$sql = array("campos" => "count(*) as Total", "values" => "", "tablas" => " ( select count(*) as Total from " . $nombreTabla . " " . $condicion . " " . $grupo . ") as Total", "where" => "", "groups" => "", "orders" => "");
                $sql = array("campos" => "count(*) as Total", "values" => "", "tablas" => $nombreTabla, "where" => $condiciones, "groups" => $agrupacion, "orders" => $orders);
            } else {
                $sql = array("campos" => "count(*) as Total", "values" => "", "tablas" => $nombreTabla, "where" => $condiciones, "groups" => $agrupacion, "orders" => $orders);
            }

            $param = array("tabla" => "", "d" => $d, "tmpSql" => $sql, "proveedor" => null);
            $arrayTot = $genericoDao->select($param);
            #var_dump($arrayTot);
            #$arrayTot = sizeof($arrayTot);
            #$Tot =$arrayTot["totalCount"];
            if ($agrupacion != "") {
                $arrayTot["data"][0]["Total"] = $arrayTot["totalCount"];
            }
            #var_dump($arrayTot);
            $data = array();
            for ($index = 0; $index < sizeof(@$row["data"]); $index++) {
                $registro = array();
                foreach ($row["data"][$index] as $key => $value) {
                    $nombreEmpleado = "";
                    if ($key == "numEmpleadoResponsable" || $key == "numEmpleadoElaboro" || $key == "numEmpleadoReviso" || $key == "numEmpleadoAprobo" || $key == "numEmpleadoRealiza" || $key == "numEmpleadoResguardo") {
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
                    }
                    if ($key == "cveRegion") {
//                        $registro[] = $this->getRegionesConsulta($value, '')->data[0]->nomRegion;
                    }
                    if ($key == "idTrimestre") {
                        $registro[] = $this->getTrimestresConsulta($value)->data[0]->desTrimestre;
                    }
                    if ($key == "cveAdscripcion" || $key == "cveAdscripcionDevuelve" || $key == "cveAdsAlmacen") {
                        $registro[] = $value;
                        $registro[] = $this->getAdscripcionNombre($value);
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

    public function getTipoPadreHijo($param, $p = null) {
        $d = array();
        $genenericDAO = new GenericDAO();
        $respuesta = array();
        $sql = array(
            "campos" => " 
                tblintegracioninventarios.*,
                tblinventarios.*
            ",
            "tablas" => "   
                tblintegracioninventarios tblintegracioninventarios
                INNER JOIN tblinventarios tblinventarios
                ON tblinventarios.idInventario = tblintegracioninventarios.idInventarioPadre
            ",
            "where" => " 
                tblintegracioninventarios.activo = 'S' AND
                tblinventarios.activo = 'S' AND
                tblintegracioninventarios.idInventarioPadre = " . $param["extrasPost"]["idInventario"] . "
            "
        );
        if ($param["extrasPost"]["cveClasificadorBien"] == "1") {
            $sql["campos"] .= "
                ,
                tblcbm.*
            ";
            $sql["tablas"] .= "
                INNER JOIN tblcbm tblcbm 
                ON tblcbm.idCbm = tblinventarios.idReferencia
            ";
            $sql["where"] .= "
                AND 
                tblcbm.activo = 'S'
            ";
        }
        if ($param["extrasPost"]["cveClasificadorBien"] == "2") {
            $sql["campos"] .= "
                ,
                tblcbi.*
            ";
            $sql["tablas"] .= "
                INNER JOIN tblcbi tblcbi 
                ON tblcbi.idCbi = tblinventarios.idReferencia
            ";
            $sql["where"] .= "
                AND 
                tblcbi.activo = 'S'
            ";
        }
        if ($param["extrasPost"]["cveClasificadorBien"] == "7") {
            $sql["campos"] .= "
                ,
                tblaah.*
            ";
            $sql["tablas"] .= "
                INNER JOIN tblaah tblaah 
                ON tblaah.idAah = tblinventarios.idReferencia
            ";
            $sql["where"] .= "
                AND 
                tblaah.activo = 'S'
            ";
        }
        $sqlSelect = array("tabla" => "", "d" => $d, "tmpSql" => $sql, "proveedor" => $p);
        $rs = $genenericDAO->select($sqlSelect);

        $sql2 = array(
            "campos" => "
                    tblbienesintegrados.*,
                    tblinventarios.*
                ",
            "tablas" => "
                    tblbienesintegrados tblbienesintegrados
                    INNER JOIN tblinventarios tblinventarios
                    ON tblinventarios.idInventario = tblbienesintegrados.idInventario
                ",
            "where" => "
                    tblbienesintegrados.activo = 'S' AND
                    tblinventarios.activo = 'S' AND
                    tblbienesintegrados.idInventario = " . $param["extrasPost"]["idInventario"] . "
                "
        );
        if ($param["extrasPost"]["cveClasificadorBien"] == "1") {
            $sql2["campos"] .= "
                ,
                tblcbm.*
            ";
            $sql2["tablas"] .= "
                INNER JOIN tblcbm tblcbm 
                ON tblcbm.idCbm = tblinventarios.idReferencia
            ";
            $sql2["where"] .= "
                AND 
                tblcbm.activo = 'S'
            ";
        }
        if ($param["extrasPost"]["cveClasificadorBien"] == "2") {
            $sql2["campos"] .= "
                ,
                tblcbi.*
            ";
            $sql2["tablas"] .= "
                INNER JOIN tblcbi tblcbi 
                ON tblcbi.idCbi = tblinventarios.idReferencia
            ";
            $sql2["where"] .= "
                AND 
                tblcbi.activo = 'S'
            ";
        }
        if ($param["extrasPost"]["cveClasificadorBien"] == "7") {
            $sql2["campos"] .= "
                ,
                tblaah.*
            ";
            $sql2["tablas"] .= "
                INNER JOIN tblaah tblaah 
                ON tblaah.idAah = tblinventarios.idReferencia
            ";
            $sql2["where"] .= "
                AND 
                tblaah.activo = 'S'
            ";
        }
        $sqlSelect2 = array("tabla" => "", "d" => $d, "tmpSql" => $sql2, "proveedor" => $p);
        $rs2 = $genenericDAO->select($sqlSelect2);

        if ($rs["totalCount"] > 0) {
            foreach ($rs["data"] as $key => $value) {
                $param["extrasPost"]["idIntegracionInventario"] = $value["idIntegracionInventario"];
                $rs["data"][$key]["hijos"] = $this->consultarBienesIntegradosForeach($param, $p);
            }
            $respuesta = $rs;
        } else {
            if ($rs2["totalCount"] > 0) {
                $param["extrasPost"]["idIntegracionInventario"] = $rs2["data"][0]["idIntegracionInventario"];
                $respuesta = $this->consultarIntegracionInventariosForeach($param, $p);
                if ($respuesta["totalCount"] > 0) {
                    foreach ($respuesta["data"] as $key => $value) {
                        $respuesta["data"][$key]["hijos"] = $this->consultarBienesIntegradosForeach($param, $p);
                    }
                } else {
                    $respuesta = $this->consultarBienesIntegradosForeach($param, $p);
                }
            } else {
                return $this->consultaInventario($param, $p);
            }
        }
        return $respuesta;
    }

    public function consultaInventario($param, $p = null) {
        $d = array();
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
        if ($param["extrasPost"]["cveClasificadorBien"] == "1") {
            $sql["campos"] .= "
                ,
                tblcbm.*
            ";
            $sql["tablas"] .= "
                INNER JOIN tblcbm tblcbm 
                ON tblcbm.idCbm = tblinventarios.idReferencia
            ";
            $sql["where"] .= "
                AND 
                tblcbm.activo = 'S'
            ";
        }
        if ($param["extrasPost"]["cveClasificadorBien"] == "2") {
            $sql["campos"] .= "
                ,
                tblcbi.*
            ";
            $sql["tablas"] .= "
                INNER JOIN tblcbi tblcbi 
                ON tblcbi.idCbi = tblinventarios.idReferencia
            ";
            $sql["where"] .= "
                AND 
                tblcbi.activo = 'S'
            ";
        }
        if ($param["extrasPost"]["cveClasificadorBien"] == "7") {
            $sql["campos"] .= "
                ,
                tblaah.*
            ";
            $sql["tablas"] .= "
                INNER JOIN tblaah tblaah 
                ON tblaah.idAah = tblinventarios.idReferencia
            ";
            $sql["where"] .= "
                AND 
                tblaah.activo = 'S'
            ";
        }
        $sqlSelect = array("tabla" => "", "d" => $d, "tmpSql" => $sql, "proveedor" => $p);
        $rs = $genenericDAO->select($sqlSelect);
        return $rs;
    }

    public function consultarBienesIntegradosForeach($param, $p = null) {
        $d = array();
        $genenericDAO = new GenericDAO();
        $sql = array(
            "campos" => "
                tblbienesintegrados.*,
                tblinventarios.*
            ",
            "tablas" => " 
                tblbienesintegrados tblbienesintegrados
                INNER JOIN tblinventarios tblinventarios
                ON tblinventarios.idInventario = tblbienesintegrados.idInventario
            ",
            "where" => "    
                tblbienesintegrados.activo = 'S' AND 
                tblbienesintegrados.idIntegracionInventario = " . $param["extrasPost"]["idIntegracionInventario"] . "
            "
        );
        if ($param["extrasPost"]["cveClasificadorBien"] == "1") {
            $sql["campos"] .= "
                ,
                tblcbm.*
            ";
            $sql["tablas"] .= "
                INNER JOIN tblcbm tblcbm 
                ON tblcbm.idCbm = tblinventarios.idReferencia
            ";
            $sql["where"] .= "
                AND 
                tblcbm.activo = 'S'
            ";
        }
        if ($param["extrasPost"]["cveClasificadorBien"] == "2") {
            $sql["campos"] .= "
                ,
                tblcbi.*
            ";
            $sql["tablas"] .= "
                INNER JOIN tblcbi tblcbi 
                ON tblcbi.idCbi = tblinventarios.idReferencia
            ";
            $sql["where"] .= "
                AND 
                tblcbi.activo = 'S'
            ";
        }
        if ($param["extrasPost"]["cveClasificadorBien"] == "7") {
            $sql["campos"] .= "
                ,
                tblaah.*
            ";
            $sql["tablas"] .= "
                INNER JOIN tblaah tblaah 
                ON tblaah.idAah = tblinventarios.idReferencia
            ";
            $sql["where"] .= "
                AND 
                tblaah.activo = 'S'
            ";
        }
        $sqlSelect = array("tabla" => "", "d" => $d, "tmpSql" => $sql, "proveedor" => $p);
        $rs = $genenericDAO->select($sqlSelect);
        return $rs;
    }

    public function consultarIntegracionInventariosForeach($param, $p = null) {
        $d = array();
        $genenericDAO = new GenericDAO();
        $sql = array(
            "campos" => "
                tblintegracioninventarios.*,
                tblinventarios.*
            ",
            "tablas" => " 
                tblintegracioninventarios tblintegracioninventarios
                INNER JOIN tblinventarios tblinventarios
                ON tblinventarios.idInventario = tblintegracioninventarios.idInventarioPadre
            ",
            "where" => "    
                tblintegracioninventarios.activo = 'S' AND 
                tblintegracioninventarios.idIntegracionInventario = " . $param["extrasPost"]["idIntegracionInventario"] . "
            "
        );
        if ($param["extrasPost"]["cveClasificadorBien"] == "1") {
            $sql["campos"] .= "
                ,
                tblcbm.*
            ";
            $sql["tablas"] .= "
                INNER JOIN tblcbm tblcbm 
                ON tblcbm.idCbm = tblinventarios.idReferencia
            ";
            $sql["where"] .= "
                AND 
                tblcbm.activo = 'S'
            ";
        }
        if ($param["extrasPost"]["cveClasificadorBien"] == "2") {
            $sql["campos"] .= "
                ,
                tblcbi.*
            ";
            $sql["tablas"] .= "
                INNER JOIN tblcbi tblcbi 
                ON tblcbi.idCbi = tblinventarios.idReferencia
            ";
            $sql["where"] .= "
                AND 
                tblcbi.activo = 'S'
            ";
        }
        if ($param["extrasPost"]["cveClasificadorBien"] == "7") {
            $sql["campos"] .= "
                ,
                tblaah.*
            ";
            $sql["tablas"] .= "
                INNER JOIN tblaah tblaah 
                ON tblaah.idAah = tblinventarios.idReferencia
            ";
            $sql["where"] .= "
                AND 
                tblaah.activo = 'S'
            ";
        }
        $sqlSelect = array("tabla" => "", "d" => $d, "tmpSql" => $sql, "proveedor" => $p);
        $rs = $genenericDAO->select($sqlSelect);
        return $rs;
    }

    public function cargarRegiones($param) {
        $regionesCliente = new RegionCliente();
        return $regionesCliente->getRegiones("", "");
    }

    public function reincorporarBien($param) {
        $this->guardarLogger("desintegrarBien", $param);
        $genenericDAO = new GenericDAO();
        $this->proveedor->connect();
        $this->proveedor->execute("BEGIN");
        $error = false;
        $respuesta = "";

        $updateInventarios = array(
            "tabla" => "tblinventarios", "d" => array(
                "values" => array(
                    "activo" => "S",
                    "fechaActualizacion" => "now()"
                ), "where" => array(
                    "idInventario" => $param["extrasPost"]["idInventario"]
                )
            ), "proveedor" => $this->proveedor
        );
        $updatRs = $genenericDAO->update($updateInventarios);
        $this->guardarLogger("ERROR INVENTARIOS", $updatRs);
        if ($updatRs["totalCount"] > 0) {
            $respuesta = $updatRs;
        } else {
            $error = true;
            $respuesta = $updatRs;
            $this->guardarLogger("ERROR INVENTARIOS", $updatRs);
        }

        $this->guardarLogger("ERROR FINAL", $error);
//        $error = true;
        if (!$error) {
            $this->proveedor->execute("COMMIT");
        } else {
            $this->proveedor->execute("ROLLBACK");
        }
        return $respuesta;
    }

    public function desintegrarBien($param) {
        $this->guardarLogger("desintegrarBien", $param);
        $genenericDAO = new GenericDAO();
        $this->proveedor->connect();
        $this->proveedor->execute("BEGIN");
        $error = false;
        $respuesta = "";
        $padre = false;
        $hijo = false;
        $normal = false;
        foreach ($param["extrasPost"]["listado"] as $key => $value) {
            $this->guardarLogger("FOREACH KEY", $key);
            $this->guardarLogger("FOREACH VALUE", $value);
            if ($value["origen"] == "P") {
                $padre = true;
                $updateInventario = array(
                    "tabla" => "tblinventarios", "d" => array(
                        "values" => array(
                            "activo" => "N",
                            "cveMotivoBaja" => $param["extrasPost"]["cveMotivoBaja"],
                            "fechaActualizacion" => "now()"
                        ), "where" => array(
                            "idInventario" => $value["idinventario"]
                        )
                    ), "proveedor" => $this->proveedor
                );
                $updatInventarioRs = $genenericDAO->update($updateInventario);
                $this->guardarLogger("UPDATE INVENTARIO", $updatInventarioRs);
                if ($updatInventarioRs["totalCount"] > 0) {
                    $updateIntegracionInventario = array(
                        "tabla" => "tblintegracioninventarios", "d" => array(
                            "values" => array(
                                "activo" => "N",
                                "fechaActualizacion" => "now()"
                            ), "where" => array(
                                "idInventarioPadre" => $value["idinventario"]
                            )
                        ), "proveedor" => $this->proveedor
                    );
                    $updatIntegracionInventarioRs = $genenericDAO->update($updateIntegracionInventario);
                    $this->guardarLogger("UPDATE INTEGRACION INVENTARIOS", $updatIntegracionInventarioRs);
                    if ($updatIntegracionInventarioRs["totalCount"] > 0) {
                        $updateBienesIntegrados = array(
                            "tabla" => "tblbienesintegrados", "d" => array(
                                "values" => array(
                                    "activo" => "N",
                                    "fechaActualizacion" => "now()"
                                ), "where" => array(
                                    "idIntegracionInventario" => $updatIntegracionInventarioRs["data"][0]["idIntegracionInventario"]
                                )
                            ), "proveedor" => $this->proveedor
                        );
                        $updatBienesIntegradosRs = $genenericDAO->update($updateBienesIntegrados);
                        $this->guardarLogger("UPDATE BIENES INTEGRADOS", $updatBienesIntegradosRs);
                        if ($updatBienesIntegradosRs["totalCount"] > 0) {
                            $respuesta = $updatInventarioRs;
                        } else {
                            $error = true;
                            $respuesta = $updatBienesIntegradosRs;
                            $this->guardarLogger("ERROR BIENES INTEGRADOS", $updatBienesIntegradosRs);
                        }
                    } else {
                        $error = true;
                        $respuesta = $updatIntegracionInventarioRs;
                        $this->guardarLogger("ERROR INTEGRACION INVENTARIOS", $updatIntegracionInventarioRs);
                    }
                } else {
                    $error = true;
                    $respuesta = $updatInventarioRs;
                    $this->guardarLogger("ERROR INVENTARIOS", $updatInventarioRs);
                }
            } elseif ($value["origen"] == "H") {
                $hijo = true;
                $updateInventario = array(
                    "tabla" => "tblinventarios", "d" => array(
                        "values" => array(
                            "activo" => "N",
                            "cveMotivoBaja" => $param["extrasPost"]["cveMotivoBaja"],
                            "fechaActualizacion" => "now()"
                        ), "where" => array(
                            "idInventario" => $value["idinventario"]
                        )
                    ), "proveedor" => $this->proveedor
                );
                $updatInventarioRs = $genenericDAO->update($updateInventario);
                $this->guardarLogger("UPDATE INVENTARIOS", $updateInventario);
                if ($updatInventarioRs["totalCount"] > 0) {
                    if (!$padre) {
                        $updateBienesIntegrados = array(
                            "tabla" => "tblbienesintegrados", "d" => array(
                                "values" => array(
                                    "activo" => "N",
                                    "fechaActualizacion" => "now()"
                                ), "where" => array(
                                    "idInventario" => $value["idinventario"]
                                )
                            ), "proveedor" => $this->proveedor
                        );
                        $updatBienesIntegradosRs = $genenericDAO->update($updateBienesIntegrados);
                        $this->guardarLogger("UPDATE BIENES INTEGRADOS", $updatBienesIntegradosRs);
                        if ($updatBienesIntegradosRs["totalCount"] > 0) {
                            $respuesta = $updatInventarioRs;
                        } else {
                            $error = true;
                            $respuesta = $updatBienesIntegradosRs;
                            $this->guardarLogger("ERROR BIENES INTEGRADOS", $updatBienesIntegradosRs);
                        }
                    } else {
                        $respuesta = $updatInventarioRs;
                    }
                } else {
                    $error = true;
                    $respuesta = $updatInventarioRs;
                    $this->guardarLogger("ERROR INVENTARIOS", $updatInventarioRs);
                }
            } elseif ($value["origen"] == "N") {
                $normal = true;
                $updateInventario = array(
                    "tabla" => "tblinventarios", "d" => array(
                        "values" => array(
                            "activo" => "N",
                            "cveMotivoBaja" => $param["extrasPost"]["cveMotivoBaja"],
                            "fechaActualizacion" => "now()"
                        ), "where" => array(
                            "idInventario" => $value["idinventario"]
                        )
                    ), "proveedor" => $this->proveedor
                );
                $updatInventarioRs = $genenericDAO->update($updateInventario);
                $this->guardarLogger("UPDATE INVENTARIOS", $updatInventarioRs);
                if ($updatInventarioRs["totalCount"] > 0) {
                    $respuesta = $updatInventarioRs;
                } else {
                    $error = true;
                    $respuesta = $updatInventarioRs;
                    $this->guardarLogger("ERROR INVENTARIOS", $updatInventarioRs);
                }
            }
            if (!$error) {
                $updateResguardos = array(
                    "tabla" => "tblresguardos", "d" => array(
                        "values" => array(
                            "activo" => "N",
                            "fechaActualizacion" => "now()"
                        ), "where" => array(
                            "idInventario" => $value["idinventario"]
                        )
                    ), "proveedor" => $this->proveedor
                );
                $updatResguardoRs = $genenericDAO->update($updateResguardos);
                if ($updatResguardoRs["totalCount"] > 0) {
                    $respuesta = $updatInventarioRs;
                } else {
                    $error = true;
                    $respuesta = $updatResguardoRs;
                    $this->guardarLogger("ERROR RESGUARDO", $updatResguardoRs);
                }
            }
        }
        $this->guardarLogger("ERROR FINAL", $error);
//        $error = true;
        if (!$error) {
            $this->proveedor->execute("COMMIT");
        } else {
            $this->proveedor->execute("ROLLBACK");
        }
        return $respuesta;
    }

    public function consultarBienesIntegrados($param, $p = null) {
        return $this->getTipoPadreHijo($param, $p);
    }

    public function eliminarBien($param) {
        $genenericDAO = new GenericDAO();
        $this->proveedor->connect();
        $this->proveedor->execute("BEGIN");
        $error = false;
        $respuesta = "";



        $error = true;
        if (!$error) {
            $this->proveedor->execute("COMMIT");
        } else {
            $this->proveedor->execute("ROLLBACK");
        }
        return $respuesta;
    }

    public function guardarAsignacionBienes($param) {
        $genenericDAO = new GenericDAO();
        $this->proveedor->connect();
        $this->proveedor->execute("BEGIN");
        $error = false;
        $respuesta = "";

        $sql = array(
            "campos" => "
                tblresguardos.*
            ",
            "tablas" => "
        	tblresguardos tblresguardos
            ",
            "where" => "
                tblresguardos.activo = 'S' AND
                tblresguardos.idInventario = " . $param["extrasPost"]["idInventario"] . "
            "
        );
        $sqlSelect = array("tabla" => "", "d" => $d, "tmpSql" => $sql, "proveedor" => $this->proveedor);
        $rs = $genenericDAO->select($sqlSelect);

        if ($rs["totalCount"] > 0) {
            $updateResguardo = array(
                "tabla" => "tblresguardos", "d" => array(
                    "values" => array(
                        "activo" => "N",
                        "fechaActualizacion" => "now()"
                    ), "where" => array(
                        "idInventario" => $param["extrasPost"]["idInventario"]
                    )
                ), "proveedor" => $this->proveedor
            );
            $updatResguardoRs = $genenericDAO->update($updateResguardo);

            if ($updatResguardoRs["totalCount"] > 0) {
                $guardarResguardo = array(
                    "tabla" => "tblresguardos", "d" => array(
                        "values" => array(
                            "idInventario" => $param["extrasPost"]["idInventario"],
                            "cveAdscripcion" => $param["extrasPost"]["cveAdscripcion"],
                            "numEmpleadoResguardo" => $param["extrasPost"]["numEmpleadoResguardo"],
                            "precioActual" => $param["extrasPost"]["precioActual"],
                            "fechaAsigancion" => "now()",
                            "activo" => "S",
                            "fechaRegistro" => "now()",
                            "fechaActualizacion" => "now()",
                        )), "proveedor" => $this->proveedor
                );
                $guardarResguardoRs = $genenericDAO->insert($guardarResguardo);
                if ($guardarResguardoRs["totalCount"] > 0) {
                    $respuesta = $guardarResguardoRs;
                } else {
                    $error = true;
                    $respuesta = $guardarResguardoRs;
                }
            } else {
                $error = true;
                $respuesta = $updatResguardoRs;
            }
        } else {
            $guardarResguardo = array(
                "tabla" => "tblresguardos", "d" => array(
                    "values" => array(
                        "idInventario" => $param["extrasPost"]["idInventario"],
                        "cveAdscripcion" => $param["extrasPost"]["cveAdscripcion"],
                        "numEmpleadoResguardo" => $param["extrasPost"]["numEmpleadoResguardo"],
                        "precioActual" => $param["extrasPost"]["precioActual"],
                        "fechaAsigancion" => "now()",
                        "activo" => "S",
                        "fechaRegistro" => "now()",
                        "fechaActualizacion" => "now()",
                    )), "proveedor" => $this->proveedor
            );
            $guardarResguardoRs = $genenericDAO->insert($guardarResguardo);
            if ($guardarResguardoRs["totalCount"] > 0) {
                $respuesta = $guardarResguardoRs;
            } else {
                $error = true;
                $respuesta = $guardarResguardoRs;
            }
        }



//        $error = true;
        if (!$error) {
            $this->proveedor->execute("COMMIT");
        } else {
            $this->proveedor->execute("ROLLBACK");
        }
        return $respuesta;
    }

    public function guardarInventarioDatos($param, $p = null) {
        $genenericDAO = new GenericDAO();
        $this->proveedor->connect();
        $this->proveedor->execute("BEGIN");
        $error = false;
        $respuesta = "";

        if ($param["extrasPost"]["cveClasificadorBien"] == "1") {
            $updateInventarios = array(
                "tabla" => "tblinventarios", "d" => array(
                    "values" => array(
                        "numeroSerie" => $param["extrasPost"]["numeroSerie"],
                        "valorDesecho" => $param["extrasPost"]["valorDesecho"],
                        "aniosVidaUtil" => $param["extrasPost"]["aniosVidaUtil"],
                        "inventariado" => "S",
                        "garantia" => $param["extrasPost"]["garantia"],
                        "fechaActualizacion" => "now()"
                    ), "where" => array(
                        "idInventario" => $param["extrasPost"]["idInventario"]
                    )
                ), "proveedor" => $this->proveedor
            );
            if ($param["extrasPost"]["garantia"] != "N") {
                $updateInventarios["d"]["values"]["fechaInicioGarantia"] = $this->formatoFecha($param["extrasPost"]["fechaInicioGarantia"], 'fecha', 'mysql', 'fecha');
                $updateInventarios["d"]["values"]["fechaFinGarantia"] = $this->formatoFecha($param["extrasPost"]["fechaFinGarantia"], 'fecha', 'mysql', 'fecha');
            }
            $updatInventariosRs = $genenericDAO->update($updateInventarios);
            if ($updatInventariosRs["totalCount"] > 0) {
                $respuesta = $updatInventariosRs;
            } else {
                $error = true;
                $respuesta = $updatInventariosRs;
            }
        }
        if ($param["extrasPost"]["cveClasificadorBien"] == "2") {
            
        }
        if ($param["extrasPost"]["cveClasificadorBien"] == "7") {
            
        }


//        $error = true;
        if (!$error) {
            $this->proveedor->execute("COMMIT");
        } else {
            $this->proveedor->execute("ROLLBACK");
        }
        return $respuesta;
    }

    public function consultarInventarioEspecifico($param, $p = null) {
        $d = array();
        $genenericDAO = new GenericDAO();
        if ($param["extrasPost"]["cveClasificadorBien"] == "1") {
            $sql = array(
                "campos" => " 
                    tblinventarios.*,
                    tblcbm.*,
                    tblsubgruposmuebles.*,
                    tblclasesmuebles.*,
                    tblcogbienes.idCogBien,
                    tblcogbienes.idCog,
                    tblcogbienes.descripcion
                ",
                "tablas" => "
                    tblinventarios tblinventarios
                    INNER JOIN tblcbm tblcbm
                    ON tblinventarios.idReferencia = tblcbm.idCbm
                    INNER JOIN tblsubgruposmuebles tblsubgruposmuebles
                    ON tblsubgruposmuebles.subGrupoMueble = tblcbm.subGrupoMueble AND tblsubgruposmuebles.cveGrupo = tblcbm.cveGrupo
                    INNER JOIN tblclasesmuebles tblclasesmuebles
                    ON tblclasesmuebles.claseMueble = tblcbm.claseMueble AND tblclasesmuebles.cveSubGrupoMueble = tblsubgruposmuebles.cveSubGrupomueble
                    INNER JOIN tblcogbienes tblcogbienes
                    ON tblcogbienes.idCogBien = tblcbm.idCogBien                    
                ",
                "where" => " 
                    tblinventarios.activo = 'S' AND
                    tblinventarios.idInventario = " . $param["extrasPost"]["idInventario"] . "
                "
            );
        }
        if ($param["extrasPost"]["cveClasificadorBien"] == "2") {
            $sql = array(
                "campos" => " 
                    
                ",
                "tablas" => "
                    
                ",
                "where" => " 
                    
                "
            );
        }
        if ($param["extrasPost"]["cveClasificadorBien"] == "7") {
            $sql = array(
                "campos" => " 
                    
                ",
                "tablas" => "
                    
                ",
                "where" => " 
                    
                "
            );
        }

        $sqlSelect = array("tabla" => "", "d" => $d, "tmpSql" => $sql, "proveedor" => $p);
        $rs = $genenericDAO->select($sqlSelect);

        return $rs;
    }

    public function cargarUnidadMedida($param, $p = null) {
        $d = array();
        $genenericDAO = new GenericDAO();
        $sql = array(
            "campos" => "
               tblunidadesmedida.*
            ",
            "tablas" => "
               tblunidadesmedida tblunidadesmedida
            ",
            "where" => "
               tblunidadesmedida.activo = 'S'
            "
        );
        $sqlSelect = array("tabla" => "", "d" => $d, "tmpSql" => $sql, "proveedor" => $p);
        $rs = $genenericDAO->select($sqlSelect);

        return $rs;
    }

    public function cargarMotivoBaja($param, $p = null) {
        $d = array();
        $genenericDAO = new GenericDAO();
        $sql = array(
            "campos" => "
               tblmotivosbaja.*
            ",
            "tablas" => "
               tblmotivosbaja tblmotivosbaja
            ",
            "where" => "
               tblmotivosbaja.activo = 'S'
            "
        );
        $sqlSelect = array("tabla" => "", "d" => $d, "tmpSql" => $sql, "proveedor" => $p);
        $rs = $genenericDAO->select($sqlSelect);

        return $rs;
    }

    public function getRegiones($param, $p = null) {
        $d = array();
        $sql = array();
        $genenericDAO = new GenericDAO();

        $sql["campos"] = " tblregiones.* ";
        $sql["tablas"] = " tblregiones tblregiones ";
        $sql["where"] = " tblregiones.activo = 'S' ";

        if ($param["extrasPost"]["cveRegion"] != "") {
            $sql["where"] .= " AND tblregiones.activo = 'S' ";
        }

        $sqlSelect = array("tabla" => "", "d" => $d, "tmpSql" => $sql, "proveedor" => $p);
        $rs = $genenericDAO->select($sqlSelect);

        return $rs;
    }

    public function getAdscripciones($param, $cveRegion, $p = null) {
        
    }

    public function consultarTablaGenericInventariosReportes($param, $p = null) {
        $d = array();
        $sql = array();
        $genenericDAO = new GenericDAO();
        $respuesta = "";

        $sql["campos"] = "";
        $sql["tablas"] = "";
        $sql["where"] = "";

        $sql["tablas"] .= " tblresguardos tblresguardos  ";
        $sql["tablas"] .= " INNER JOIN tblinventarios tblinventarios  ";
        $sql["tablas"] .= " ON tblresguardos.idInventario = tblinventarios.idInventario  ";
        $sql["tablas"] .= " INNER JOIN tblregiones tblregiones  ";
        $sql["tablas"] .= " ON tblresguardos.cveRegion = tblregiones.cveRegion  ";
        $sql["tablas"] .= " INNER JOIN tblfacturas tblfacturas  ";
        $sql["tablas"] .= " ON tblinventarios.idFactura = tblfacturas.idFactura  ";
        $sql["tablas"] .= " INNER JOIN tblproveedores tblproveedores  ";
        $sql["tablas"] .= " ON tblfacturas.idProveedor = tblproveedores.idProveedor  ";
        $sql["tablas"] .= " LEFT JOIN tblmotivosbaja tblmotivosbaja  ";
        $sql["tablas"] .= " ON tblinventarios.cveMotivoBaja = tblmotivosbaja.cveMotivoBaja AND tblmotivosbaja.cveMotivoBaja = 'S'  ";
        $sql["tablas"] .= " INNER JOIN tblestadosbienes tblestadosbienes  ";
        $sql["tablas"] .= " ON tblinventarios.cveEstadoBien = tblestadosbienes.cveEstadoBien  ";
        $sql["tablas"] .= " INNER JOIN  tblclasificadoresbienes tblclasificadoresbienes  ";
        $sql["tablas"] .= " ON tblinventarios.cveClasificadorBien = tblclasificadoresbienes.cveClasificadorBien  ";

        if ($param["extrasPost"]["cveClasificadorBien"] == "1") {
            $sql["tablas"] .= " INNER JOIN tblcbm tblcbm  ";
            $sql["tablas"] .= " ON tblinventarios.idReferencia = tblcbm.idCbm  ";
        } elseif ($param["extrasPost"]["cveClasificadorBien"] == "2") {
            
        } elseif ($param["extrasPost"]["cveClasificadorBien"] == "7") {
            
        }

        $sql["tablas"] .= " INNER JOIN  tblunidadesmedida tblunidadesmedida  ";
        $sql["tablas"] .= " ON tblcbm.cveUnidadMedida = tblunidadesmedida.cveUnidadMedida  ";
        $sql["tablas"] .= " INNER JOIN  tblgruposmuebles tblgruposmuebles  ";
        $sql["tablas"] .= " ON tblcbm.cveGrupo = tblgruposmuebles.cveGrupo  ";
        $sql["tablas"] .= " INNER JOIN htsj_administrativo.tblsubgruposmuebles tblsubgruposmuebles  ";
        $sql["tablas"] .= " ON tblgruposmuebles.cveGrupo = tblsubgruposmuebles.cveGrupo AND tblcbm.subGrupoMueble = tblsubgruposmuebles.subGrupoMueble  ";
        $sql["tablas"] .= " INNER JOIN tblclasesmuebles tblclasesmuebles  ";
        $sql["tablas"] .= " ON tblsubgruposmuebles.cveSubGrupomueble = tblclasesmuebles.cveSubGrupoMueble AND tblcbm.claseMueble = tblclasesmuebles.claseMueble  ";

        $sql["where"] .= " (tblresguardos.activo = 'S') AND ";
        $sql["where"] .= " (tblinventarios.activo = 'S') AND ";
        $sql["where"] .= " (tblclasificadoresbienes.activo = 'S') AND ";
        $sql["where"] .= " (tblfacturas.activo = 'S') AND ";
        $sql["where"] .= " (tblestadosbienes.activo = 'S') AND ";
        $sql["where"] .= " (tblproveedores.activo = 'S') AND ";
        if ($param["extrasPost"]["cveClasificadorBien"] == "1") {
            $sql["where"] .= " (tblcbm.activo = 'S') AND ";
        } elseif ($param["extrasPost"]["cveClasificadorBien"] == "2") {
            
        } elseif ($param["extrasPost"]["cveClasificadorBien"] == "7") {
            
        }
        $sql["where"] .= " (tblunidadesmedida.activo = 'S') AND ";
        $sql["where"] .= " (tblgruposmuebles.activo = 'S') AND ";
        $sql["where"] .= " (tblsubgruposmuebles.activo = 'S')  ";

        $regiones = $this->getRegiones($param, $p);
        if ($regiones["totalCount"] > 0) {
            if ($param["extrasPost"]["cveRegion"] != "") {
                if ($param["extrasPost"]["cveClasificadorBien"] == "1") {
                    
                } elseif ($param["extrasPost"]["cveClasificadorBien"] == "2") {
                    
                } elseif ($param["extrasPost"]["cveClasificadorBien"] == "7") {
                    
                }
            } else {
                if ($param["extrasPost"]["cveClasificadorBien"] == "1") {

                    $sql["campos"] .= " tblresguardos.cveRegion, ";
                    $sql["campos"] .= " tblregiones.desRegion, ";
                    $sql["campos"] .= " tblgruposmuebles.desGrupo, ";
                    $sql["campos"] .= " COUNT(tblinventarios.idInventario) as cantidadBienes, ";
                    $sql["campos"] .= " SUM(tblinventarios.precioActual) as sumaBienes ";



                    $sql["groups"] .= " tblresguardos.cveRegion, ";
                    $sql["groups"] .= "	tblgruposmuebles.cveGrupo ";



                    if ($param["extrasPost"]["cveGrupo"] != "") {
                        $sql["where"] .= " AND (tblcbm.cveGrupo = " . $param["extrasPost"]["cveGrupo"] . ")  ";
                    }
                    if ($param["extrasPost"]["subGrupoMueble"] != "") {
                        $sql["where"] .= " AND (tblcbm.subGrupoMueble = " . $param["extrasPost"]["subGrupoMueble"] . ")  ";
                    }
                    if ($param["extrasPost"]["claseMueble"] != "") {
                        $sql["where"] .= " AND (tblcbm.claseMueble = " . $param["extrasPost"]["claseMueble"] . ")  ";
                    }
                    if ($param["extrasPost"]["cveColor"] != "") {
                        $sql["where"] .= " AND (tblcbm.cveColor = " . $param["extrasPost"]["cveColor"] . ")  ";
                    }
                    if ($param["extrasPost"]["cveMaterial"] != "") {
                        $sql["where"] .= " AND (tblcbm.cveMaterial = " . $param["extrasPost"]["cveMaterial"] . ")  ";
                    }
                    if ($param["extrasPost"]["cvefrecuenciaUso"] != "") {
                        $sql["where"] .= " AND (tblcbm.cvefrecuenciaUso = " . $param["extrasPost"]["cvefrecuenciaUso"] . ")  ";
                    }
                    if ($param["extrasPost"]["cveUnidadMedida"] != "") {
                        $sql["where"] .= " AND (tblcbm.cveUnidadMedida = " . $param["extrasPost"]["cveUnidadMedida"] . ")  ";
                    }
                    if ($param["extrasPost"]["denominacion"] != "") {
                        $sql["where"] .= " AND (tblcbm.denominacion like '% " . $param["extrasPost"]["denominacion"] . "%')  ";
                    }
                    if ($param["extrasPost"]["marca"] != "") {
                        $sql["where"] .= " AND (tblcbm.marca like '% " . $param["extrasPost"]["marca"] . "%')  ";
                    }
                    if ($param["extrasPost"]["modelo"] != "") {
                        $sql["where"] .= " AND (tblcbm.modelo like '% " . $param["extrasPost"]["modelo"] . "%')  ";
                    }
                    if ($param["extrasPost"]["requiereReorden"] != "") {
                        $sql["where"] .= " AND (tblcbm.requiereReorden = ' " . $param["extrasPost"]["requiereReorden"] . "')  ";
                    }
                    if ($param["extrasPost"]["porcentajeReorden"] != "") {
                        $sql["where"] .= " AND (tblcbm.porcentajeReorden =  " . $param["extrasPost"]["porcentajeReorden"] . ")  ";
                    }
                    if ($param["extrasPost"]["codigoPropio"] == "S") {
                        if ($param["extrasPost"]["codigoPropioInicio"] != "" && $param["extrasPost"]["codigoPropioFin"] != "") {
                            $sql["where"] .= " AND (tblinventarios.codigoPropio >=  " . $param["extrasPost"]["codigoPropioInicio"] . " AND tblinventarios.codigoPropio <= " . $param["extrasPost"]["codigoPropioFin"] . ")  ";
                        }
                    }
                    if ($param["extrasPost"]["fechaRegistro"] == "S") {
                        if ($param["extrasPost"]["fechaRegistroInicio"] != "" && $param["extrasPost"]["fechaRegistroFin"] != "") {
                            $sql["where"] .= " AND (tblinventarios.fechaRegistro >=  " . $param["extrasPost"]["fechaRegistroInicio"] . " AND tblinventarios.fechaRegistro <= " . $param["extrasPost"]["fechaRegistroFin"] . ")  ";
                        }
                    }
                } elseif ($param["extrasPost"]["cveClasificadorBien"] == "2") {
                    
                } elseif ($param["extrasPost"]["cveClasificadorBien"] == "7") {
                    
                }
            }
        } else {
            $respuesta = $regiones;
        }

        $sqlSelect = array("tabla" => "", "d" => $d, "tmpSql" => $sql, "proveedor" => $p);
        $respuesta = $genenericDAO->select($sqlSelect);

        return $respuesta;
    }

    public function cargarMotivosBaja($param, $p = null) {
        $d = array();
        $genenericDAO = new GenericDAO();
        $sql = array(
            "campos" => "
               tblmotivosbaja.*
            ",
            "tablas" => "
               tblmotivosbaja tblmotivosbaja
            ",
            "where" => "
               tblmotivosbaja.activo = 'S'
            "
        );
        $sqlSelect = array("tabla" => "", "d" => $d, "tmpSql" => $sql, "proveedor" => $p);
        $rs = $genenericDAO->select($sqlSelect);

        return $rs;
    }

    public function cargarFrecuenciaUso($param, $p = null) {
        $d = array();
        $genenericDAO = new GenericDAO();
        $sql = array(
            "campos" => "
                tblfrecuenciasusos.*
            ",
            "tablas" => "
               tblfrecuenciasusos tblfrecuenciasusos
            ",
            "where" => "
                tblfrecuenciasusos.activo = 'S'
            "
        );
        $sqlSelect = array("tabla" => "", "d" => $d, "tmpSql" => $sql, "proveedor" => $p);
        $rs = $genenericDAO->select($sqlSelect);

        return $rs;
    }

    public function cargarMaterial($param, $p = null) {
        $d = array();
        $genenericDAO = new GenericDAO();
        $sql = array(
            "campos" => "
                tblmateriales.*
            ",
            "tablas" => "
               tblmateriales tblmateriales
            ",
            "where" => "
                tblmateriales.activo = 'S'
            "
        );
        $sqlSelect = array("tabla" => "", "d" => $d, "tmpSql" => $sql, "proveedor" => $p);
        $rs = $genenericDAO->select($sqlSelect);

        return $rs;
    }

    public function cargarColores($param, $p = null) {
        $d = array();
        $genenericDAO = new GenericDAO();
        $sql = array(
            "campos" => "
                tblcolores.*
            ",
            "tablas" => "
               tblcolores tblcolores
            ",
            "where" => "
                tblcolores.activo = 'S'
            "
        );
        $sqlSelect = array("tabla" => "", "d" => $d, "tmpSql" => $sql, "proveedor" => $p);
        $rs = $genenericDAO->select($sqlSelect);

        return $rs;
    }

    public function cargarBienes($param, $p = null) {
        $d = array();
        $genenericDAO = new GenericDAO();
        $sql = array(
            "campos" => "
                tblcogbienes.*
            ",
            "tablas" => "
               tblcogbienes tblcogbienes
            ",
            "where" => "
                tblcogbienes.activo = 'S' AND 
                ( tblcogbienes.idCog = " . $param["extrasPost"]["idCog"] . " )
            "
        );
        $sqlSelect = array("tabla" => "", "d" => $d, "tmpSql" => $sql, "proveedor" => $p);
        $rs = $genenericDAO->select($sqlSelect);

        return $rs;
    }

    public function cargarCog($param, $p = null) {
        $d = array();
        $genenericDAO = new GenericDAO();
        $sql = array(
            "campos" => "
                tblcog.*
            ",
            "tablas" => "
               tblcog tblcog
            ",
            "where" => "
                tblcog.activo = 'S' AND 
                ( tblcog.cveCapitulo = 2 OR tblcog.cveCapitulo = 5)
            "
        );
        $sqlSelect = array("tabla" => "", "d" => $d, "tmpSql" => $sql, "proveedor" => $p);
        $rs = $genenericDAO->select($sqlSelect);

        return $rs;
    }

    public function cargarClaseMueble($param, $p = null) {
        $d = array();
        $genenericDAO = new GenericDAO();
        $sql = array(
            "campos" => "
                tblclasesmuebles.*
            ",
            "tablas" => "
               tblclasesmuebles tblclasesmuebles
            ",
            "where" => "
                tblclasesmuebles.activo = 'S' AND
                tblclasesmuebles.cveSubGrupomueble = " . $param["extrasPost"]["cveSubGrupomueble"] . "
            "
        );
        $sqlSelect = array("tabla" => "", "d" => $d, "tmpSql" => $sql, "proveedor" => $p);
        $rs = $genenericDAO->select($sqlSelect);

        return $rs;
    }

    public function cargarSubClaseInmueble($param, $p = null) {
        $d = array();
        $genenericDAO = new GenericDAO();
        $sql = array(
            "campos" => "
               tblsubclasesinmuebles.*
            ",
            "tablas" => "
               tblsubclasesinmuebles tblsubclasesinmuebles
            ",
            "where" => "
               tblsubclasesinmuebles.activo = 'S' AND 
               tblsubclasesinmuebles.cveClaseInmueble = " . $param["extrasPost"]["cveClaseInmueble"] . "
            "
        );
        $sqlSelect = array("tabla" => "", "d" => $d, "tmpSql" => $sql, "proveedor" => $p);
        $rs = $genenericDAO->select($sqlSelect);

        return $rs;
    }

    public function cargarClaseInmueble($param, $p = null) {
        $d = array();
        $genenericDAO = new GenericDAO();
        $sql = array(
            "campos" => "
               tblclasesinmuebles.*
            ",
            "tablas" => "
               tblclasesinmuebles tblclasesinmuebles
            ",
            "where" => "
                tblclasesinmuebles.activo = 'S' AND
                tblclasesinmuebles.cveSubGupoInmueble = " . $param["extrasPost"]["cveSubGupoInmueble"] . "
            "
        );
        $sqlSelect = array("tabla" => "", "d" => $d, "tmpSql" => $sql, "proveedor" => $p);
        $rs = $genenericDAO->select($sqlSelect);

        return $rs;
    }

    public function cargarSubGrupoInmueble($param, $p = null) {
        $d = array();
        $genenericDAO = new GenericDAO();
        $sql = array(
            "campos" => "
                tblsubgruposinmuebles.*
            ",
            "tablas" => "
               tblsubgruposinmuebles tblsubgruposinmuebles
            ",
            "where" => "
                tblsubgruposinmuebles.activo = 'S' AND
                tblsubgruposinmuebles.cveGrupoInmueble = " . $param["extrasPost"]["cveGrupoInmueble"] . "
            "
        );
        $sqlSelect = array("tabla" => "", "d" => $d, "tmpSql" => $sql, "proveedor" => $p);
        $rs = $genenericDAO->select($sqlSelect);

        return $rs;
    }

    public function cargarSubGruposMueble($param, $p = null) {
        $d = array();
        $genenericDAO = new GenericDAO();
        $sql = array(
            "campos" => "
                tblsubgruposmuebles.*
            ",
            "tablas" => "
               tblsubgruposmuebles tblsubgruposmuebles
            ",
            "where" => "
                tblsubgruposmuebles.activo = 'S' AND
                tblsubgruposmuebles.cveGrupo = " . $param["extrasPost"]["cveGrupo"] . "
            "
        );
        $sqlSelect = array("tabla" => "", "d" => $d, "tmpSql" => $sql, "proveedor" => $p);
        $rs = $genenericDAO->select($sqlSelect);

        return $rs;
    }

    public function cargarGruposInmuebles($param, $p = null) {
        $d = array();
        $genenericDAO = new GenericDAO();
        $sql = array(
            "campos" => "
               tblgruposinmuebles.*
            ",
            "tablas" => "
                tblgruposinmuebles tblgruposinmuebles
            ",
            "where" => "
                tblgruposinmuebles.activo = 'S'
            "
        );
        $sqlSelect = array("tabla" => "", "d" => $d, "tmpSql" => $sql, "proveedor" => $p);
        $rs = $genenericDAO->select($sqlSelect);

        return $rs;
    }

    function cargarGrupos($param, $p = null) {
        $d = array();
        $genenericDAO = new GenericDAO();
        $sql = array(
            "campos" => "
                tblgruposmuebles.*
            ",
            "tablas" => "
                tblgruposmuebles tblgruposmuebles
            ",
            "where" => "
                tblgruposmuebles.activo = 'S'
            "
        );
        $sqlSelect = array("tabla" => "", "d" => $d, "tmpSql" => $sql, "proveedor" => $p);
        $rs = $genenericDAO->select($sqlSelect);

        return $rs;
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

    public function datatableConsultaBienes($params, $p = null) {
        $limit = array("max" => $params["limit"]["max"],
            "pag" => $params["limit"]["pag"]);
        $d = array("limit" => $limit);
        $sql = array();
        $sql["campos"] = "
                tblinventarios.idInventario,
                tblinventarios.cveClasificadorBien,
                tblclasificadoresbienes.desClasificadorBien,
                tblinventarios.cveEstadoBien,
                tblestadosbienes.desEstadoBien,
                tblinventarios.numeroSerie,
                tblinventarios.codigoPropio,
                tblinventarios.precioActual
            ";
        $sql["tablas"] = "
                tblinventarios tblinventarios
                INNER JOIN tblestadosbienes tblestadosbienes
                ON (tblinventarios.cveEstadoBien = tblestadosbienes.cveEstadoBien)
                INNER JOIN tblclasificadoresbienes tblclasificadoresbienes
                ON (tblclasificadoresbienes.cveClasificadorBien = tblinventarios.cveClasificadorBien)                
            ";
        $sql["where"] = "
                tblinventarios.inventariado = 'N' AND
                tblinventarios.activo = 'S' AND
                tblestadosbienes.activo = 'S' AND
                tblclasificadoresbienes.activo = 'S'                
            ";

        if ($params["extrasPost"]["cveClasificadorBien"] == "1") {
            $sql["campos"] .= ",";
            $sql["campos"] .= "
                tblcbm.idCbm,
                tblcbm.idCogBien,                                
                tblcbm.denominacion,
                tblcbm.marca,
                tblcbm.modelo
            ";
            $sql["tablas"] .= "
                INNER JOIN tblcbm tblcbm
                ON tblcbm.idCbm = tblinventarios.idReferencia 
            ";
            $sql["where"] .= " AND tblcbm.activo = 'S' ";
            if ($params["extrasPost"]["cveGrupo"] != "") {
                $sql["where"] .= " AND tblcbm.cveGrupo = " . $params["extrasPost"]["cveGrupo"] . " ";
            }
            if ($params["extrasPost"]["subGrupoMueble"] != "") {
                $sql["where"] .= " AND tblcbm.subGrupoMueble = " . $params["extrasPost"]["subGrupoInmueble"] . " ";
            }
            if ($params["extrasPost"]["claseMueble"] != "") {
                $sql["where"] .= " AND tblcbm.claseMueble = " . $params["extrasPost"]["claseMueble"] . " ";
            }
            if ($params["extrasPost"]["idCogBien"] != "") {
                $sql["where"] .= " AND tblcbm.idCogBien = " . $params["extrasPost"]["idCogBien"] . " ";
            }
            if ($params["extrasPost"]["cveColor"] != "") {
                $sql["where"] .= " AND tblcbm.cveColor = " . $params["extrasPost"]["cveColor"] . " ";
            }
            if ($params["extrasPost"]["cveMaterial"] != "") {
                $sql["where"] .= " AND tblcbm.cveMaterial = " . $params["extrasPost"]["cveMaterial"] . " ";
            }
            if ($params["extrasPost"]["cveFrecuenciaUso"] != "") {
                $sql["where"] .= " AND tblcbm.cveFrecuenciaUso = " . $params["extrasPost"]["cveFrecuenciaUso"] . " ";
            }
            if ($params["extrasPost"]["cveUnidadMedida"] != "") {
                $sql["where"] .= " AND tblcbm.cveUnidadMedida = " . $params["extrasPost"]["cveUnidadMedida"] . " ";
            }

            if ($params["extrasPost"]["denominacion"] != "") {
                $sql['where'] .= " AND
                    tblcbm.denominacion like '%" . $params["extrasPost"]["denominacion"] . "%'
                ";
            }
            if ($params["extrasPost"]["marca"] != "") {
                $sql['where'] .= " AND
                    tblcbm.marca like '%" . $params["extrasPost"]["marca"] . "%'
                ";
            }
            if ($params["extrasPost"]["modelo"] != "") {
                $sql['where'] .= " AND
                    tblcbm.modelo like '%" . $params["extrasPost"]["modelo"] . "%'
                ";
            }
            if ($params["extrasPost"]["requiereReorden"] != "" && $params["extrasPost"]["requiereReorden"] == "S") {
                $sql['where'] .= " AND
                    tblcbm.requiereReorden = 'S'
                ";
                if ($params["extrasPost"]["porcentajeReorden"] != "") {
                    $sql['where'] .= " AND
                        tblcbm.porcentajeReorden = " . $params["extrasPost"]["porcentajeReorden"] . "
                    ";
                }
            }
            if ($params["extrasPost"]["numeroSerie"] != "") {
                $sql['where'] .= " AND
                    tblinventarios.numeroSerie = " . $params["extrasPost"]["numeroSerie"] . "
                ";
            }
            if ($params["extrasPost"]["valorDesecho"] != "") {
                $sql['where'] .= " AND
                    tblinventarios.valorDesecho = " . $params["extrasPost"]["valorDesecho"] . "
                ";
            }
            if ($params["extrasPost"]["aniosVidaUtil"] != "") {
                $sql['where'] .= " AND
                    tblinventarios.aniosVidaUtil = " . $params["extrasPost"]["aniosVidaUtil"] . "
                ";
            }
            if ($params["extrasPost"]["garantia"] != "" && $params["extrasPost"]["garantia"] == "S") {
                $sql['where'] .= " AND
                    tblinventarios.garantia = 'S'
                ";
                if ($params["extrasPost"]["fechaInicioGarantia"] != "" && $params["extrasPost"]["fechaFinGarantia"]) {
                    $sql['where'] .= " AND
                        tblinventarios.fechaInicioGarantia >= " . $this->formatoFecha($params["extrasPost"]["fechaInicioGarantia"], 'fecha', 'mysql', 'fecha') . " AND
                        tblinventarios.fechaFinGarantia <= " . $this->formatoFecha($params["extrasPost"]["fechaFinGarantia"], 'fecha', 'mysql', 'fecha') . " 
                    ";
                }
            }
        } elseif ($params["extrasPost"]["cveClasificadorBien"] == "2") {
            $sql["campos"] .= ",";
            $sql["campos"] .= "
                tblcbi.idCbi,
                tblcbi.idCogBien,                                
                tblcbi.denominacion,
                tblcogbienes.descripcion
            ";
            $sql["tablas"] .= "
                INNER JOIN tblcbi tblcbi
                ON tblcbi.idCbi = tblinventarios.idReferencia 
                INNER JOIN tblcogbienes tblcogbienes
                ON tblcogbienes.idCogBien = tblcbi.idCogBien
            ";
            $sql["where"] .= " AND tblcbi.activo = 'S' ";
            if ($params["extrasPost"]["cveGrupoInmueble"] != "") {
                $sql["where"] .= " AND tblcbi.cveGrupoInmueble = " . $params["extrasPost"]["cveGrupoInmueble"] . " ";
            }
            if ($params["extrasPost"]["subGrupoInmueble"] != "") {
                $sql["where"] .= " AND tblcbi.subGrupoInmueble = " . $params["extrasPost"]["subGrupoInmueble"] . " ";
            }
            if ($params["extrasPost"]["claseInmueble"] != "") {
                $sql["where"] .= " AND tblcbi.claseInmueble = " . $params["extrasPost"]["claseInmueble"] . " ";
            }
            if ($params["extrasPost"]["subClaseInmueble"] != "") {
                $sql["where"] .= " AND tblcbi.subClaseInmueble = " . $params["extrasPost"]["subClaseInmueble"] . " ";
            }
        } elseif ($params["extrasPost"]["cveClasificadorBien"] == "7") {
            $sql["campos"] .= ",";
            $sql["campos"] .= "
                tblaah.idAah,
                tblaah.idCogBien,                                
                tblaah.denominacion,
                tblcogbienes.descripcion
            ";
            $sql["tablas"] .= "
                INNER JOIN tblaah tblaah
                ON tblaah.idAah = tblinventarios.idReferencia 
            ";
            $sql["where"] .= " AND tblaah.activo = 'S' ";
        }
        $sql["orders"] = $params["order"]["column"] . " " . $params["order"]["dir"];

        if ($params['search']['value'] != "") {
            $arrayCampos = explore(",", $sql["campos"]);
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

    public function datatableConsultaBienesInventariados($params, $p = null) {
        $limit = array("max" => $params["limit"]["max"],
            "pag" => $params["limit"]["pag"]);
        $d = array("limit" => $limit);
        $sql = array();
        $sql["campos"] = "
                tblinventarios.idInventario,
                tblinventarios.cveClasificadorBien,
                tblclasificadoresbienes.desClasificadorBien,
                tblinventarios.cveEstadoBien,
                tblestadosbienes.desEstadoBien,
                tblinventarios.numeroSerie,
                tblinventarios.codigoPropio,
                tblinventarios.precioActual
            ";
        $sql["tablas"] = "
                tblinventarios tblinventarios
                INNER JOIN tblestadosbienes tblestadosbienes
                ON (tblinventarios.cveEstadoBien = tblestadosbienes.cveEstadoBien)
                INNER JOIN tblclasificadoresbienes tblclasificadoresbienes
                ON (tblclasificadoresbienes.cveClasificadorBien = tblinventarios.cveClasificadorBien)                
            ";
        $sql["where"] = "
                tblinventarios.inventariado = 'S' AND
                tblinventarios.activo = 'S' AND
                tblestadosbienes.activo = 'S' AND
                tblclasificadoresbienes.activo = 'S'                
            ";

        if ($params["extrasPost"]["cveClasificadorBien"] == "1") {
            $sql["campos"] .= ",";
            $sql["campos"] .= "
                tblcbm.idCbm,
                tblcbm.idCogBien,                                
                tblcbm.denominacion,
                tblcbm.marca,
                tblcbm.modelo
            ";
            $sql["tablas"] .= "
                INNER JOIN tblcbm tblcbm
                ON tblcbm.idCbm = tblinventarios.idReferencia 
            ";
            $sql["where"] .= " AND tblcbm.activo = 'S' ";
            if ($params["extrasPost"]["cveGrupo"] != "") {
                $sql["where"] .= " AND tblcbm.cveGrupo = " . $params["extrasPost"]["cveGrupo"] . " ";
            }
            if ($params["extrasPost"]["subGrupoMueble"] != "") {
                $sql["where"] .= " AND tblcbm.subGrupoMueble = " . $params["extrasPost"]["subGrupoInmueble"] . " ";
            }
            if ($params["extrasPost"]["claseMueble"] != "") {
                $sql["where"] .= " AND tblcbm.claseMueble = " . $params["extrasPost"]["claseMueble"] . " ";
            }
            if ($params["extrasPost"]["idCogBien"] != "") {
                $sql["where"] .= " AND tblcbm.idCogBien = " . $params["extrasPost"]["idCogBien"] . " ";
            }
            if ($params["extrasPost"]["cveColor"] != "") {
                $sql["where"] .= " AND tblcbm.cveColor = " . $params["extrasPost"]["cveColor"] . " ";
            }
            if ($params["extrasPost"]["cveMaterial"] != "") {
                $sql["where"] .= " AND tblcbm.cveMaterial = " . $params["extrasPost"]["cveMaterial"] . " ";
            }
            if ($params["extrasPost"]["cveFrecuenciaUso"] != "") {
                $sql["where"] .= " AND tblcbm.cveFrecuenciaUso = " . $params["extrasPost"]["cveFrecuenciaUso"] . " ";
            }
            if ($params["extrasPost"]["cveUnidadMedida"] != "") {
                $sql["where"] .= " AND tblcbm.cveUnidadMedida = " . $params["extrasPost"]["cveUnidadMedida"] . " ";
            }

            if ($params["extrasPost"]["denominacion"] != "") {
                $sql['where'] .= " AND
                    tblcbm.denominacion like '%" . $params["extrasPost"]["denominacion"] . "%'
                ";
            }
            if ($params["extrasPost"]["marca"] != "") {
                $sql['where'] .= " AND
                    tblcbm.marca like '%" . $params["extrasPost"]["marca"] . "%'
                ";
            }
            if ($params["extrasPost"]["modelo"] != "") {
                $sql['where'] .= " AND
                    tblcbm.modelo like '%" . $params["extrasPost"]["modelo"] . "%'
                ";
            }
            if ($params["extrasPost"]["requiereReorden"] != "" && $params["extrasPost"]["requiereReorden"] == "S") {
                $sql['where'] .= " AND
                    tblcbm.requiereReorden = 'S'
                ";
                if ($params["extrasPost"]["porcentajeReorden"] != "") {
                    $sql['where'] .= " AND
                        tblcbm.porcentajeReorden = " . $params["extrasPost"]["porcentajeReorden"] . "
                    ";
                }
            }
            if ($params["extrasPost"]["numeroSerie"] != "") {
                $sql['where'] .= " AND
                    tblinventarios.numeroSerie = '" . $params["extrasPost"]["numeroSerie"] . "'
                ";
            }
            if ($params["extrasPost"]["valorDesecho"] != "") {
                $sql['where'] .= " AND
                    tblinventarios.valorDesecho = " . $params["extrasPost"]["valorDesecho"] . "
                ";
            }
            if ($params["extrasPost"]["aniosVidaUtil"] != "") {
                $sql['where'] .= " AND
                    tblinventarios.aniosVidaUtil = " . $params["extrasPost"]["aniosVidaUtil"] . "
                ";
            }
            if ($params["extrasPost"]["garantia"] != "" && $params["extrasPost"]["garantia"] == "S") {
                $sql['where'] .= " AND
                    tblinventarios.garantia = 'S'
                ";
                if ($params["extrasPost"]["fechaInicioGarantia"] != "" && $params["extrasPost"]["fechaFinGarantia"]) {
                    $sql['where'] .= " AND
                        tblinventarios.fechaInicioGarantia >= " . $this->formatoFecha($params["extrasPost"]["fechaInicioGarantia"], 'fecha', 'mysql', 'fecha') . " AND
                        tblinventarios.fechaFinGarantia <= " . $this->formatoFecha($params["extrasPost"]["fechaFinGarantia"], 'fecha', 'mysql', 'fecha') . " 
                    ";
                }
            }
        } elseif ($params["extrasPost"]["cveClasificadorBien"] == "2") {
            $sql["campos"] .= ",";
            $sql["campos"] .= "
                tblcbi.idCbi,
                tblcbi.idCogBien,                                
                tblcbi.denominacion,
                tblcogbienes.descripcion
            ";
            $sql["tablas"] .= "
                INNER JOIN tblcbi tblcbi
                ON tblcbi.idCbi = tblinventarios.idReferencia 
                INNER JOIN tblcogbienes tblcogbienes
                ON tblcogbienes.idCogBien = tblcbi.idCogBien
            ";
            $sql["where"] .= " AND tblcbi.activo = 'S' ";
            if ($params["extrasPost"]["cveGrupoInmueble"] != "") {
                $sql["where"] .= " AND tblcbi.cveGrupoInmueble = " . $params["extrasPost"]["cveGrupoInmueble"] . " ";
            }
            if ($params["extrasPost"]["subGrupoInmueble"] != "") {
                $sql["where"] .= " AND tblcbi.subGrupoInmueble = " . $params["extrasPost"]["subGrupoInmueble"] . " ";
            }
            if ($params["extrasPost"]["claseInmueble"] != "") {
                $sql["where"] .= " AND tblcbi.claseInmueble = " . $params["extrasPost"]["claseInmueble"] . " ";
            }
            if ($params["extrasPost"]["subClaseInmueble"] != "") {
                $sql["where"] .= " AND tblcbi.subClaseInmueble = " . $params["extrasPost"]["subClaseInmueble"] . " ";
            }
        } elseif ($params["extrasPost"]["cveClasificadorBien"] == "7") {
            $sql["campos"] .= ",";
            $sql["campos"] .= "
                tblaah.idAah,
                tblaah.idCogBien,                                
                tblaah.denominacion,
                tblcogbienes.descripcion
            ";
            $sql["tablas"] .= "
                INNER JOIN tblaah tblaah
                ON tblaah.idAah = tblinventarios.idReferencia 
            ";
            $sql["where"] .= " AND tblaah.activo = 'S' ";
        }
        $sql["orders"] = $params["order"]["column"] . " " . $params["order"]["dir"];

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

    public function consultaParaReportePDF($params, $p = null) {
        $d = array();
        $genenericDAO = new GenericDAO();

        $sql = array();

        $sql["campos"] = "";
        $sql["tablas"] = "";
        $sql["where"] = "";

        if ($params["extrasPost"]["cveClasificadorBien"] == "1") {
            $sql["campos"] = "
                    tblregiones.desRegion,
                    tblgruposmuebles.desGrupo,
                    COUNT(tblinventarios.idInventario) AS bienesTotal,
                    SUM(tblinventarios.precioActual)   AS valorTotal,
                    tblcbm.denominacion,
                    tblclasesmuebles.desClase,
                    tblsubgruposmuebles.desSubGrupoMueble,
                    tblcbm.marca,
                    tblcbm.modelo,
                    tblresguardos.cveAdscripcion,
                    tblresguardos.numEmpleadoResguardo,
                    tblregiones.cveRegion,
                    tblgruposmuebles.cveGrupo,
                    tblsubgruposmuebles.cveSubGrupomueble,
                    tblsubgruposmuebles.subGrupoMueble,
                    tblclasesmuebles.cveClase,
                    tblclasesmuebles.claseMueble,
                    tblinventarios.codigoPropio,
                    tblinventarios.numeroSerie,
                    tblfacturas.fechaRecepcion,
                    tblfacturas.folioFiscal,
                    tblproveedores.razonSocial,
                    tblproveedores.RFC,
                    tblinventarios.idInventario 
                ";
            $sql["tablas"] = "
                    tblinventarios tblinventarios 
                    INNER JOIN tblcbm tblcbm 
                    ON (tblinventarios.idReferencia = tblcbm.idCbm AND tblcbm.activo = 'S')
                    LEFT JOIN tblresguardos tblresguardos 
                    ON (tblinventarios.idInventario = tblresguardos.idInventario AND tblresguardos.activo = '" . ($params["extrasPost"]["cveMotivoBaja"] != "" ? "N" : "S") . "')
                    INNER JOIN tblregiones tblregiones 
                    ON (tblresguardos.cveRegion = tblregiones.cveRegion )
                    INNER JOIN tblfacturas tblfacturas 
                    ON (tblinventarios.idFactura = tblfacturas.idFactura AND tblfacturas.activo = 'S')
                    INNER JOIN tblproveedores tblproveedores
                    ON (tblproveedores.idProveedor = tblfacturas.idProveedor AND tblproveedores.activo = 'S')
                    INNER JOIN tblgruposmuebles tblgruposmuebles 
                    ON (tblcbm.cveGrupo = tblgruposmuebles.cveGrupo )
                    INNER JOIN tblsubgruposmuebles tblsubgruposmuebles 
                    ON (tblgruposmuebles.cveGrupo = tblsubgruposmuebles.cveGrupo AND tblcbm.subGrupoMueble = tblsubgruposmuebles.subGrupoMueble )
                    INNER JOIN  tblclasesmuebles tblclasesmuebles 
                    ON (tblsubgruposmuebles.cveSubGrupomueble = tblclasesmuebles.cveSubGrupoMueble AND tblcbm.claseMueble = tblclasesmuebles.claseMueble )
                ";
            $sql["groups"] = " tblcbm.cveGrupo, tblresguardos.cveRegion ";
            $sql["orders"] = "
                    tblregiones.desRegion ASC,
                    tblgruposmuebles.desGrupo ASC,
                    tblsubgruposmuebles.desSubGrupoMueble ASC,
                    tblclasesmuebles.desClase ASC, 
                    tblcbm.denominacion ASC
                ";
            if ($params["extrasPost"]["cveMotivoBaja"] != "") {
                $sql['where'] .= "
                    (tblinventarios.activo = 'N') 
                ";
            } else {
                $sql['where'] .= "
                    (tblinventarios.activo = 'S') 
                ";
            }
//            if ($params["extrasPost"]["cveRegion"] != "") {
//                $sql['where'] .= " AND
//                        tblresguardos.cveRegion = " . $params["extrasPost"]["cveRegion"] . " 
//                    ";
//            } else {
            if ($params["extrasPost"]["cveGrupo"] != "") {
                $sql["groups"] .= " ,tblcbm.subGrupoMueble ";
                $sql['where'] .= " AND
                        tblcbm.cveGrupo = " . $params["extrasPost"]["cveGrupo"] . " 
                    ";
                if ($params["extrasPost"]["subGrupoMueble"] != "") {
                    $sql["groups"] .= " ,tblcbm.claseMueble ";
                    $sql['where'] .= " AND 
                        tblcbm.subGrupoMueble = " . $params["extrasPost"]["subGrupoMueble"] . " 
                    ";
                }
                if ($params["extrasPost"]["claseMueble"] != "") {
                    $sql["groups"] .= " ,tblcbm.idCbm ";
                    $sql['where'] .= " AND
                            tblcbm.claseMueble = " . $params["extrasPost"]["claseMueble"] . "
                        ";
                }
            }

//            }
            // CAMPOS FILTRADO
            if ($params["extrasPost"]["cveRegion"] != "") {
                $sql["groups"] .= " ,tblresguardos.cveAdscripcion ";
                $sql['where'] .= " AND
                    tblresguardos.cveRegion = " . $params["extrasPost"]["cveRegion"] . " 
                ";
                if ($params["extrasPost"]["cveAdscripcion"] != "") {
                    $sql["groups"] .= " ,tblresguardos.numEmpleadoResguardo ";
                    $sql['where'] .= " AND
                        tblresguardos.cveAdscripcion = " . $params["extrasPost"]["cveAdscripcion"] . "
                    ";
                    if ($params["extrasPost"]["numEmpleadoResguardo"] != "") {
                        $sql['where'] .= " AND
                            tblresguardos.numEmpleadoResguardo = " . $params["extrasPost"]["numEmpleadoResguardo"] . "
                        ";
                    }
                }
            }
//            var_dump($params["extrasPost"]["detalle"]);
//            var_dump($params["extrasPost"]["detalle"] == "true");
            if ($params["extrasPost"]["detalle"] == "true") {
                $sql["groups"] .= " ,tblcbm.idCbm ";
                $sql["groups"] .= " ,tblcbm.claseMueble ";
                $sql["groups"] .= " ,tblcbm.subGrupoMueble ";
            }
            if ($params["extrasPost"]["especifica"] == "true") {
                $sql["groups"] .= " ,tblresguardos.numEmpleadoResguardo ";
                $sql["groups"] .= " ,tblresguardos.cveAdscripcion ";
                $sql["groups"] .= " ,tblcbm.idCbm ";
                $sql["groups"] .= " ,tblcbm.claseMueble ";
                $sql["groups"] .= " ,tblcbm.subGrupoMueble ";
            }
            if ($params["extrasPost"]["cveColor"] != "") {
                $sql['where'] .= " AND
                    tblcbm.cveColor = " . $params["extrasPost"]["cveColor"] . "
                ";
            }
            if ($params["extrasPost"]["cveMaterial"] != "") {
                $sql['where'] .= " AND
                    tblcbm.cveMaterial = " . $params["extrasPost"]["cveMaterial"] . "
                ";
            }
            if ($params["extrasPost"]["cveFrecuenciaUso"] != "") {
                $sql['where'] .= " AND
                    tblcbm.cveFrecuenciaUso = " . $params["extrasPost"]["cveFrecuenciaUso"] . "
                ";
            }
            if ($params["extrasPost"]["cveUnidadMedida"] != "") {
                $sql['where'] .= " AND
                    tblcbm.cveUnidadMedida = " . $params["extrasPost"]["cveUnidadMedida"] . "
                ";
            }
            if ($params["extrasPost"]["denominacion"] != "") {
                $sql['where'] .= " AND
                    tblcbm.denominacion like '%" . $params["extrasPost"]["denominacion"] . "%'
                ";
            }
            if ($params["extrasPost"]["marca"] != "") {
                $sql['where'] .= " AND
                    tblcbm.marca like '%" . $params["extrasPost"]["marca"] . "%'
                ";
            }
            if ($params["extrasPost"]["modelo"] != "") {
                $sql['where'] .= " AND
                    tblcbm.modelo like '%" . $params["extrasPost"]["modelo"] . "%'
                ";
            }
            if ($params["extrasPost"]["requiereReorden"] != "" && $params["extrasPost"]["requiereReorden"] == "S") {
                $sql['where'] .= " AND
                    tblcbm.requiereReorden = 'S'
                ";
                if ($params["extrasPost"]["porcentajeReorden"] != "") {
                    $sql['where'] .= " AND
                        tblcbm.porcentajeReorden = " . $params["extrasPost"]["porcentajeReorden"] . "
                    ";
                }
            }
            if ($params["extrasPost"]["numeroSerie"] != "") {
                $sql['where'] .= " AND
                    tblinventarios.numeroSerie = " . $params["extrasPost"]["numeroSerie"] . "
                ";
            }
            if ($params["extrasPost"]["valorDesecho"] != "") {
                $sql['where'] .= " AND
                    tblinventarios.valorDesecho = " . $params["extrasPost"]["valorDesecho"] . "
                ";
            }
            if ($params["extrasPost"]["aniosVidaUtil"] != "") {
                $sql['where'] .= " AND
                    tblinventarios.aniosVidaUtil = " . $params["extrasPost"]["aniosVidaUtil"] . "
                ";
            }
            if ($params["extrasPost"]["garantia"] != "" && $params["extrasPost"]["garantia"] == "S") {
                $sql['where'] .= " AND
                    tblinventarios.garantia = 'S'
                ";
                if ($params["extrasPost"]["fechaInicioGarantia"] != "" && $params["extrasPost"]["fechaFinGarantia"]) {
                    $sql['where'] .= " AND
                        tblinventarios.fechaInicioGarantia >= " . $this->formatoFecha($params["extrasPost"]["fechaInicioGarantia"], 'fecha', 'mysql', 'fecha') . " AND
                        tblinventarios.fechaFinGarantia <= " . $this->formatoFecha($params["extrasPost"]["fechaFinGarantia"], 'fecha', 'mysql', 'fecha') . " 
                    ";
                }
            }
            if ($params["extrasPost"]["codigoPropio"] != "" && $params["extrasPost"]["codigoPropio"] == "S") {
                if ($params["extrasPost"]["codigoPropioInicio"] != "" && $params["extrasPost"]["codigoPropioFin"]) {
                    $sql['where'] .= " AND
                        tblinventarios.idInventario >= " . explode("M", $params["extrasPost"]["codigoPropioInicio"])[1] . " AND
                        tblinventarios.idInventario <= " . explode("M", $params["extrasPost"]["codigoPropioFin"])[1] . "    
                    ";
                }
            }
            if ($params["extrasPost"]["fechaRegistro"] != "" && $params["extrasPost"]["fechaRegistro"] == "S") {
                if ($params["extrasPost"]["fechaRegistroInicio"] != "" && $params["extrasPost"]["fechaRegistroFin"]) {
                    $sql['where'] .= " AND
                        tblinventarios.fechaRegistro >= '" . $this->formatoFecha($params["extrasPost"]["fechaRegistroInicio"], 'fecha', 'mysql', 'fecha') . " 00:00:00' AND
                        tblinventarios.fechaRegistro <= '" . $this->formatoFecha($params["extrasPost"]["fechaRegistroFin"], 'fecha', 'mysql', 'fecha') . " 59:59:59' 
                    ";
                }
            }
            if ($params["extrasPost"]["baja"] != "" && $params["extrasPost"]["baja"] == "S") {
                if ($params["extrasPost"]["cveMotivoBaja"] != "") {
                    $sql['where'] .= " AND
                        tblinventarios.cveMotivoBaja = " . $params["extrasPost"]["cveMotivoBaja"] . "
                    ";
                }
            }
            if ($params["extrasPost"]["fechaRecepcion"] != "" && $params["extrasPost"]["fechaRecepcion"] == "S") {
                if ($params["extrasPost"]["fechaRecepcionInicio"] != "" && $params["extrasPost"]["fechaRecepcionFin"]) {
                    $sql["groups"] .= " ,tblfacturas.idFactura ";
                    $sql['where'] .= " AND
                        tblfacturas.fechaRecepcion >= '" . $this->formatoFecha($params["extrasPost"]["fechaRecepcionInicio"], 'fecha', 'mysql', 'fecha') . " 00:00:00' AND
                        tblfacturas.fechaRecepcion <= '" . $this->formatoFecha($params["extrasPost"]["fechaRecepcionFin"], 'fecha', 'mysql', 'fecha') . " 59:59:59''
                    ";
                }
            }
            if ($params["extrasPost"]["rfc"] != "" && $params["extrasPost"]["rfc"] == "S") {
                if ($params["extrasPost"]["rfcProveedor"] != "") {
                    $sql["groups"] .= " ,tblfacturas.idFactura ";
                    $sql['where'] .= " AND
                        tblproveedores.RFC = '" . $params["extrasPost"]["rfcProveedor"] . "'
                    ";
                }
            }
            if ($params["extrasPost"]["folioFiscalFactura"] != "" && $params["extrasPost"]["folioFiscalFactura"] == "S") {
                if ($params["extrasPost"]["folioFiscal"] != "") {
                    $sql["groups"] .= " ,tblfacturas.idFactura ";
                    $sql['where'] .= " AND
                        tblfacturas.folioFiscal = " . $params["extrasPost"]["folioFiscal"] . "
                    ";
                }
            }
        } elseif ($params["extrasPost"]["cveClasificadorBien"] == "2") {
            
        } elseif ($params["extrasPost"]["cveClasificadorBien"] == "7") {
            
        }
//        var_dump($sql);
        $sqlSelect = array("tabla" => "", "d" => $d, "tmpSql" => $sql, "proveedor" => $p);
        $rs = $genenericDAO->select($sqlSelect);

        return $rs;
    }

    //REPORTE
    public function consultarTablaGenericInventariosReportesBienes($params, $p = null) {
        $limit = array("max" => $params["limit"]["max"],
            "pag" => $params["limit"]["pag"]);
        $d = array("limit" => $limit);
        $sql = array();

        $sql["campos"] = "";
        $sql["tablas"] = "";
        $sql["where"] = "";

        if ($params["extrasPost"]["cveClasificadorBien"] == "1") {
            $sql["campos"] = "
                    tblregiones.desRegion,
                    tblgruposmuebles.desGrupo,
                    COUNT(tblinventarios.idInventario) AS bienesTotal,
                    SUM(tblinventarios.precioActual)   AS valorTotal,
                    tblcbm.denominacion,
                    tblclasesmuebles.desClase,
                    tblsubgruposmuebles.desSubGrupoMueble,
                    tblcbm.marca,
                    tblcbm.modelo,
                    tblresguardos.cveAdscripcion,
                    tblresguardos.numEmpleadoResguardo,
                    tblregiones.cveRegion,
                    tblgruposmuebles.cveGrupo,
                    tblsubgruposmuebles.cveSubGrupomueble,
                    tblsubgruposmuebles.subGrupoMueble,
                    tblclasesmuebles.cveClase,
                    tblclasesmuebles.claseMueble,
                    tblinventarios.codigoPropio,
                    tblinventarios.numeroSerie,
                    tblfacturas.fechaRecepcion,
                    tblfacturas.folioFiscal,
                    tblproveedores.razonSocial,
                    tblproveedores.RFC,
                    tblinventarios.idInventario 
                ";
            $sql["tablas"] = "
                    tblinventarios tblinventarios 
                    INNER JOIN tblcbm tblcbm 
                    ON (tblinventarios.idReferencia = tblcbm.idCbm AND tblcbm.activo = 'S')
                    LEFT JOIN tblresguardos tblresguardos 
                    ON (tblinventarios.idInventario = tblresguardos.idInventario AND tblresguardos.activo = '" . ($params["extrasPost"]["cveMotivoBaja"] != "" ? "N" : "S") . "')
                    INNER JOIN tblregiones tblregiones 
                    ON (tblresguardos.cveRegion = tblregiones.cveRegion )
                    INNER JOIN tblfacturas tblfacturas 
                    ON (tblinventarios.idFactura = tblfacturas.idFactura AND tblfacturas.activo = 'S')
                    INNER JOIN tblproveedores tblproveedores
                    ON (tblproveedores.idProveedor = tblfacturas.idProveedor AND tblproveedores.activo = 'S')
                    INNER JOIN tblgruposmuebles tblgruposmuebles 
                    ON (tblcbm.cveGrupo = tblgruposmuebles.cveGrupo )
                    INNER JOIN tblsubgruposmuebles tblsubgruposmuebles 
                    ON (tblgruposmuebles.cveGrupo = tblsubgruposmuebles.cveGrupo AND tblcbm.subGrupoMueble = tblsubgruposmuebles.subGrupoMueble )
                    INNER JOIN  tblclasesmuebles tblclasesmuebles 
                    ON (tblsubgruposmuebles.cveSubGrupomueble = tblclasesmuebles.cveSubGrupoMueble AND tblcbm.claseMueble = tblclasesmuebles.claseMueble )
                ";
            $sql["groups"] = " tblcbm.cveGrupo, tblresguardos.cveRegion ";
            $sql["orders"] = "
                    tblregiones.desRegion ASC,
                    tblgruposmuebles.desGrupo ASC,
                    tblsubgruposmuebles.desSubGrupoMueble ASC,
                    tblclasesmuebles.desClase ASC, 
                    tblcbm.denominacion ASC
                ";
            if ($params["extrasPost"]["cveMotivoBaja"] != "") {
                $sql['where'] .= "
                    (tblinventarios.activo = 'N') 
                ";
            } else {
                $sql['where'] .= "
                    (tblinventarios.activo = 'S') 
                ";
            }

//            if ($params["extrasPost"]["cveRegion"] != "") {
//                $sql['where'] .= " AND
//                        tblresguardos.cveRegion = " . $params["extrasPost"]["cveRegion"] . " 
//                    ";
//            } else {
            if ($params["extrasPost"]["cveGrupo"] != "") {
                $sql["groups"] .= " ,tblcbm.subGrupoMueble ";
                $sql['where'] .= " AND
                        tblcbm.cveGrupo = " . $params["extrasPost"]["cveGrupo"] . " 
                    ";
                if ($params["extrasPost"]["subGrupoMueble"] != "") {
                    $sql["groups"] .= " ,tblcbm.claseMueble ";
                    $sql['where'] .= " AND 
                        tblcbm.subGrupoMueble = " . $params["extrasPost"]["subGrupoMueble"] . " 
                    ";
                }
                if ($params["extrasPost"]["claseMueble"] != "") {
                    $sql["groups"] .= " ,tblcbm.idCbm ";
                    $sql['where'] .= " AND
                            tblcbm.claseMueble = " . $params["extrasPost"]["claseMueble"] . "
                        ";
                }
            }

//            }
            // CAMPOS FILTRADO
            if ($params["extrasPost"]["cveRegion"] != "") {
                $sql["groups"] .= " ,tblresguardos.cveAdscripcion ";
                $sql['where'] .= " AND
                    tblresguardos.cveRegion = " . $params["extrasPost"]["cveRegion"] . " 
                ";
                if ($params["extrasPost"]["cveAdscripcion"] != "") {
                    $sql["groups"] .= " ,tblresguardos.numEmpleadoResguardo ";
                    $sql['where'] .= " AND
                        tblresguardos.cveAdscripcion = " . $params["extrasPost"]["cveAdscripcion"] . "
                    ";
                    if ($params["extrasPost"]["numEmpleadoResguardo"] != "") {
                        $sql['where'] .= " AND
                            tblresguardos.numEmpleadoResguardo = " . $params["extrasPost"]["numEmpleadoResguardo"] . "
                        ";
                    }
                }
            }
//            var_dump($params["extrasPost"]["detalle"]);
//            var_dump($params["extrasPost"]["detalle"] == "true");
            if ($params["extrasPost"]["detalle"] == "true") {
                $sql["groups"] .= " ,tblcbm.idCbm ";
                $sql["groups"] .= " ,tblcbm.claseMueble ";
                $sql["groups"] .= " ,tblcbm.subGrupoMueble ";
            }
            if ($params["extrasPost"]["especifica"] == "true") {
                $sql["groups"] .= " ,tblresguardos.numEmpleadoResguardo ";
                $sql["groups"] .= " ,tblresguardos.cveAdscripcion ";
                $sql["groups"] .= " ,tblcbm.idCbm ";
                $sql["groups"] .= " ,tblcbm.claseMueble ";
                $sql["groups"] .= " ,tblcbm.subGrupoMueble ";
            }
            if ($params["extrasPost"]["cveColor"] != "") {
                $sql['where'] .= " AND
                    tblcbm.cveColor = " . $params["extrasPost"]["cveColor"] . "
                ";
            }
            if ($params["extrasPost"]["cveMaterial"] != "") {
                $sql['where'] .= " AND
                    tblcbm.cveMaterial = " . $params["extrasPost"]["cveMaterial"] . "
                ";
            }
            if ($params["extrasPost"]["cveFrecuenciaUso"] != "") {
                $sql['where'] .= " AND
                    tblcbm.cveFrecuenciaUso = " . $params["extrasPost"]["cveFrecuenciaUso"] . "
                ";
            }
            if ($params["extrasPost"]["cveUnidadMedida"] != "") {
                $sql['where'] .= " AND
                    tblcbm.cveUnidadMedida = " . $params["extrasPost"]["cveUnidadMedida"] . "
                ";
            }
            if ($params["extrasPost"]["denominacion"] != "") {
                $sql['where'] .= " AND
                    tblcbm.denominacion like '%" . $params["extrasPost"]["denominacion"] . "%'
                ";
            }
            if ($params["extrasPost"]["marca"] != "") {
                $sql['where'] .= " AND
                    tblcbm.marca like '%" . $params["extrasPost"]["marca"] . "%'
                ";
            }
            if ($params["extrasPost"]["modelo"] != "") {
                $sql['where'] .= " AND
                    tblcbm.modelo like '%" . $params["extrasPost"]["modelo"] . "%'
                ";
            }
            if ($params["extrasPost"]["requiereReorden"] != "" && $params["extrasPost"]["requiereReorden"] == "S") {
                $sql['where'] .= " AND
                    tblcbm.requiereReorden = 'S'
                ";
                if ($params["extrasPost"]["porcentajeReorden"] != "") {
                    $sql['where'] .= " AND
                        tblcbm.porcentajeReorden = " . $params["extrasPost"]["porcentajeReorden"] . "
                    ";
                }
            }
            if ($params["extrasPost"]["numeroSerie"] != "") {
                $sql['where'] .= " AND
                    tblinventarios.numeroSerie = " . $params["extrasPost"]["numeroSerie"] . "
                ";
            }
            if ($params["extrasPost"]["valorDesecho"] != "") {
                $sql['where'] .= " AND
                    tblinventarios.valorDesecho = " . $params["extrasPost"]["valorDesecho"] . "
                ";
            }
            if ($params["extrasPost"]["aniosVidaUtil"] != "") {
                $sql['where'] .= " AND
                    tblinventarios.aniosVidaUtil = " . $params["extrasPost"]["aniosVidaUtil"] . "
                ";
            }
            if ($params["extrasPost"]["garantia"] != "" && $params["extrasPost"]["garantia"] == "S") {
                $sql['where'] .= " AND
                    tblinventarios.garantia = 'S'
                ";
                if ($params["extrasPost"]["fechaInicioGarantia"] != "" && $params["extrasPost"]["fechaFinGarantia"]) {
                    $sql['where'] .= " AND
                        tblinventarios.fechaInicioGarantia >= " . $this->formatoFecha($params["extrasPost"]["fechaInicioGarantia"], 'fecha', 'mysql', 'fecha') . " AND
                        tblinventarios.fechaFinGarantia <= " . $this->formatoFecha($params["extrasPost"]["fechaFinGarantia"], 'fecha', 'mysql', 'fecha') . " 
                    ";
                }
            }
            if ($params["extrasPost"]["codigoPropio"] != "" && $params["extrasPost"]["codigoPropio"] == "S") {
                if ($params["extrasPost"]["codigoPropioInicio"] != "" && $params["extrasPost"]["codigoPropioFin"]) {
                    $sql['where'] .= " AND
                        tblinventarios.idInventario >= " . explode("M", $params["extrasPost"]["codigoPropioInicio"])[1] . " AND
                        tblinventarios.idInventario <= " . explode("M", $params["extrasPost"]["codigoPropioFin"])[1] . "    
                    ";
                }
            }
            if ($params["extrasPost"]["fechaRegistro"] != "" && $params["extrasPost"]["fechaRegistro"] == "S") {
                if ($params["extrasPost"]["fechaRegistroInicio"] != "" && $params["extrasPost"]["fechaRegistroFin"]) {
                    $sql['where'] .= " AND
                        tblinventarios.fechaRegistro >= '" . $this->formatoFecha($params["extrasPost"]["fechaRegistroInicio"], 'fecha', 'mysql', 'fecha') . " 00:00:00' AND
                        tblinventarios.fechaRegistro <= '" . $this->formatoFecha($params["extrasPost"]["fechaRegistroFin"], 'fecha', 'mysql', 'fecha') . " 59:59:59' 
                    ";
                }
            }
            if ($params["extrasPost"]["baja"] != "" && $params["extrasPost"]["baja"] == "S") {
                if ($params["extrasPost"]["cveMotivoBaja"] != "") {
                    $sql['where'] .= " AND
                        tblinventarios.cveMotivoBaja = " . $params["extrasPost"]["cveMotivoBaja"] . "
                    ";
                }
            }
            if ($params["extrasPost"]["fechaRecepcion"] != "" && $params["extrasPost"]["fechaRecepcion"] == "S") {
                if ($params["extrasPost"]["fechaRecepcionInicio"] != "" && $params["extrasPost"]["fechaRecepcionFin"]) {
                    $sql["groups"] .= " ,tblfacturas.idFactura ";
                    $sql['where'] .= " AND
                        tblfacturas.fechaRecepcion >= '" . $this->formatoFecha($params["extrasPost"]["fechaRecepcionInicio"], 'fecha', 'mysql', 'fecha') . " 00:00:00' AND
                        tblfacturas.fechaRecepcion <= '" . $this->formatoFecha($params["extrasPost"]["fechaRecepcionFin"], 'fecha', 'mysql', 'fecha') . " 59:59:59''
                    ";
                }
            }
            if ($params["extrasPost"]["rfc"] != "" && $params["extrasPost"]["rfc"] == "S") {
                if ($params["extrasPost"]["rfcProveedor"] != "") {
                    $sql["groups"] .= " ,tblfacturas.idFactura ";
                    $sql['where'] .= " AND
                        tblproveedores.RFC = '" . $params["extrasPost"]["rfcProveedor"] . "'
                    ";
                }
            }
            if ($params["extrasPost"]["folioFiscalFactura"] != "" && $params["extrasPost"]["folioFiscalFactura"] == "S") {
                if ($params["extrasPost"]["folioFiscal"] != "") {
                    $sql["groups"] .= " ,tblfacturas.idFactura ";
                    $sql['where'] .= " AND
                        tblfacturas.folioFiscal = " . $params["extrasPost"]["folioFiscal"] . "
                    ";
                }
            }
        } elseif ($params["extrasPost"]["cveClasificadorBien"] == "2") {
            $sql["campos"] = "
                    tblregiones.desRegion,
                    tblgruposinmuebles.desGrupoInmueble,
                    COUNT(tblinventarios.idInventario) AS bienesTotal,
                    SUM(tblinventarios.precioActual)   AS valorTotal,
                    tblcbi.denominacion,
                    tblclasesinmuebles.desClaseInmueble,
                    tblsubgruposinmuebles.desSubGrupoInmueble,
                    tblcbi.desEscritura,
                    tblcogbienes.descripcion,
                    tblresguardos.cveAdscripcion,
                    tblresguardos.numEmpleadoResguardo,
                    tblregiones.cveRegion,
                    tblgruposinmuebles.cveGrupoInmueble,
                    tblsubgruposinmuebles.cveSubGupoInmueble,
                    tblsubgruposinmuebles.subGrupoInmueble,
                    tblclasesinmuebles.cveClaseInmueble,
                    tblclasesinmuebles.claseInmueble,
                    tblinventarios.codigoPropio,
                    tblinventarios.numeroSerie,
                    tblfacturas.fechaRecepcion,
                    tblfacturas.folioFiscal,
                    tblproveedores.razonSocial,
                    tblproveedores.RFC,
                    tblinventarios.idInventario 
                ";
            $sql["tablas"] = "
                    tblinventarios tblinventarios 
                    INNER JOIN tblcbi tblcbi 
                    ON (tblinventarios.idReferencia = tblcbi.idCbi AND tblcbi.activo = 'S')
                    INNER JOIN tblcogbienes tblcogbienes
                    ON (tblcogbienes.idCogBien = tblcbi.idCogBien)
                    LEFT JOIN tblresguardos tblresguardos 
                    ON (tblinventarios.idInventario = tblresguardos.idInventario AND tblresguardos.activo = 'S')
                    LEFT JOIN tblregiones tblregiones 
                    ON (tblresguardos.cveRegion = tblregiones.cveRegion )
                    INNER JOIN tblfacturas tblfacturas 
                    ON (tblinventarios.idFactura = tblfacturas.idFactura AND tblfacturas.activo = 'S')
                    INNER JOIN tblproveedores tblproveedores
                    ON (tblproveedores.idProveedor = tblfacturas.idProveedor AND tblproveedores.activo = 'S')
                    INNER JOIN tblgruposinmuebles tblgruposinmuebles 
                    ON (tblcbi.cveGrupoInmueble = tblgruposinmuebles.cveGrupoInmueble )
                    INNER JOIN tblsubgruposinmuebles tblsubgruposinmuebles 
                    ON (tblgruposinmuebles.cveGrupoInmueble = tblsubgruposinmuebles.cveGrupoInmueble AND tblcbi.subGrupoInmueble = tblsubgruposinmuebles.subGrupoInmueble )
                    INNER JOIN  tblclasesinmuebles tblclasesinmuebles 
                    ON (tblsubgruposinmuebles.cveSubGupoInmueble = tblclasesinmuebles.cveSubGupoInmueble AND tblcbi.claseInmueble = tblclasesinmuebles.claseInmueble )
                ";
            $sql["groups"] = " tblcbi.cveGrupoInmueble, tblresguardos.cveRegion ";
            $sql["orders"] = "
                    tblregiones.desRegion ASC,
                    tblgruposinmuebles.desGrupoInmueble ASC,
                    tblsubgruposinmuebles.desSubGrupoInmueble ASC,
                    tblclasesinmuebles.desClaseInmueble ASC, 
                    tblcbi.denominacion ASC
                ";
            $sql['where'] .= "
                    (tblinventarios.activo = 'S') 
                ";
        } elseif ($params["extrasPost"]["cveClasificadorBien"] == "7") {
            
        }
        if ($params["order"]["column"] != "" && $params["order"]["dir"] != "") {
//            var_dump("e");
            $sql["orders"] = $params["order"]["column"] . " " . $params["order"]["dir"];
        }

        if ($params['search']['value'] != "") {
            $arrayCampos = explode(",", $sql["campos"]);
            foreach ($arrayCampos as $key => $value) {
//                var_dump($key);
//                var_dump($value);
                if ($key == 0) {
                    $sql["where"] .= " AND ( " . $value . " like '%" . $params['search']['value'] . "%' ";
                } else {
                    if ($key != 2 && $key != 3) {
                        $sql["where"] .= " OR " . $value . " like '%" . $params['search']['value'] . "%' ";
                    }
                }
            }
            $sql["where"] .= " ) ";
        }
        $param = array("tabla" => "", "d" => $d, "tmpSql" => $sql, "proveedor" => null);
        return $this->datatableGenerico($params, $param, $limit, $nombreTabla = $sql['tablas'], $sql['where'], $sql["groups"], $sql["orders"], array("fechaHora" => true));
    }

    public function datatableConsultaBienesInventariadosReincorporacion($params, $p = null) {
        $limit = array("max" => $params["limit"]["max"],
            "pag" => $params["limit"]["pag"]);
        $d = array("limit" => $limit);
        $sql = array();
        $sql["campos"] = "
                tblinventarios.idInventario,
                tblinventarios.cveClasificadorBien,
                tblclasificadoresbienes.desClasificadorBien,
                tblinventarios.cveEstadoBien,
                tblestadosbienes.desEstadoBien,
                tblinventarios.numeroSerie,
                tblinventarios.codigoPropio,
                tblinventarios.precioActual
            ";
        $sql["tablas"] = "
                tblinventarios tblinventarios
                INNER JOIN tblestadosbienes tblestadosbienes
                ON (tblinventarios.cveEstadoBien = tblestadosbienes.cveEstadoBien)
                INNER JOIN tblclasificadoresbienes tblclasificadoresbienes
                ON (tblclasificadoresbienes.cveClasificadorBien = tblinventarios.cveClasificadorBien)                
            ";
        $sql["where"] = "
                tblinventarios.inventariado = 'S' AND
                tblinventarios.activo = 'N' AND
                tblestadosbienes.activo = 'S' AND
                tblclasificadoresbienes.activo = 'S'                
            ";

        if ($params["extrasPost"]["cveClasificadorBien"] == "1") {
            $sql["campos"] .= ",";
            $sql["campos"] .= "
                tblcbm.idCbm,
                tblcbm.idCogBien,                                
                tblcbm.denominacion,
                tblcbm.marca,
                tblcbm.modelo
            ";
            $sql["tablas"] .= "
                INNER JOIN tblcbm tblcbm
                ON tblcbm.idCbm = tblinventarios.idReferencia 
            ";
            $sql["where"] .= " AND tblcbm.activo = 'S' ";
            if ($params["extrasPost"]["cveGrupo"] != "") {
                $sql["where"] .= " AND tblcbm.cveGrupo = " . $params["extrasPost"]["cveGrupo"] . " ";
            }
            if ($params["extrasPost"]["subGrupoMueble"] != "") {
                $sql["where"] .= " AND tblcbm.subGrupoMueble = " . $params["extrasPost"]["subGrupoInmueble"] . " ";
            }
            if ($params["extrasPost"]["claseMueble"] != "") {
                $sql["where"] .= " AND tblcbm.claseMueble = " . $params["extrasPost"]["claseMueble"] . " ";
            }
            if ($params["extrasPost"]["idCogBien"] != "") {
                $sql["where"] .= " AND tblcbm.idCogBien = " . $params["extrasPost"]["idCogBien"] . " ";
            }
            if ($params["extrasPost"]["cveColor"] != "") {
                $sql["where"] .= " AND tblcbm.cveColor = " . $params["extrasPost"]["cveColor"] . " ";
            }
            if ($params["extrasPost"]["cveMaterial"] != "") {
                $sql["where"] .= " AND tblcbm.cveMaterial = " . $params["extrasPost"]["cveMaterial"] . " ";
            }
            if ($params["extrasPost"]["cveFrecuenciaUso"] != "") {
                $sql["where"] .= " AND tblcbm.cveFrecuenciaUso = " . $params["extrasPost"]["cveFrecuenciaUso"] . " ";
            }
            if ($params["extrasPost"]["cveUnidadMedida"] != "") {
                $sql["where"] .= " AND tblcbm.cveUnidadMedida = " . $params["extrasPost"]["cveUnidadMedida"] . " ";
            }

            if ($params["extrasPost"]["denominacion"] != "") {
                $sql['where'] .= " AND
                    tblcbm.denominacion like '%" . $params["extrasPost"]["denominacion"] . "%'
                ";
            }
            if ($params["extrasPost"]["marca"] != "") {
                $sql['where'] .= " AND
                    tblcbm.marca like '%" . $params["extrasPost"]["marca"] . "%'
                ";
            }
            if ($params["extrasPost"]["modelo"] != "") {
                $sql['where'] .= " AND
                    tblcbm.modelo like '%" . $params["extrasPost"]["modelo"] . "%'
                ";
            }
            if ($params["extrasPost"]["requiereReorden"] != "" && $params["extrasPost"]["requiereReorden"] == "S") {
                $sql['where'] .= " AND
                    tblcbm.requiereReorden = 'S'
                ";
                if ($params["extrasPost"]["porcentajeReorden"] != "") {
                    $sql['where'] .= " AND
                        tblcbm.porcentajeReorden = " . $params["extrasPost"]["porcentajeReorden"] . "
                    ";
                }
            }
            if ($params["extrasPost"]["numeroSerie"] != "") {
                $sql['where'] .= " AND
                    tblinventarios.numeroSerie = " . $params["extrasPost"]["numeroSerie"] . "
                ";
            }
            if ($params["extrasPost"]["valorDesecho"] != "") {
                $sql['where'] .= " AND
                    tblinventarios.valorDesecho = " . $params["extrasPost"]["valorDesecho"] . "
                ";
            }
            if ($params["extrasPost"]["aniosVidaUtil"] != "") {
                $sql['where'] .= " AND
                    tblinventarios.aniosVidaUtil = " . $params["extrasPost"]["aniosVidaUtil"] . "
                ";
            }
            if ($params["extrasPost"]["garantia"] != "" && $params["extrasPost"]["garantia"] == "S") {
                $sql['where'] .= " AND
                    tblinventarios.garantia = 'S'
                ";
                if ($params["extrasPost"]["fechaInicioGarantia"] != "" && $params["extrasPost"]["fechaFinGarantia"]) {
                    $sql['where'] .= " AND
                        tblinventarios.fechaInicioGarantia >= " . $this->formatoFecha($params["extrasPost"]["fechaInicioGarantia"], 'fecha', 'mysql', 'fecha') . " AND
                        tblinventarios.fechaFinGarantia <= " . $this->formatoFecha($params["extrasPost"]["fechaFinGarantia"], 'fecha', 'mysql', 'fecha') . " 
                    ";
                }
            }
        } elseif ($params["extrasPost"]["cveClasificadorBien"] == "2") {
            $sql["campos"] .= ",";
            $sql["campos"] .= "
                tblcbi.idCbi,
                tblcbi.idCogBien,                                
                tblcbi.denominacion,
                tblcogbienes.descripcion
            ";
            $sql["tablas"] .= "
                INNER JOIN tblcbi tblcbi
                ON tblcbi.idCbi = tblinventarios.idReferencia 
                INNER JOIN tblcogbienes tblcogbienes
                ON tblcogbienes.idCogBien = tblcbi.idCogBien
            ";
            $sql["where"] .= " AND tblcbi.activo = 'S' ";
            if ($params["extrasPost"]["cveGrupoInmueble"] != "") {
                $sql["where"] .= " AND tblcbi.cveGrupoInmueble = " . $params["extrasPost"]["cveGrupoInmueble"] . " ";
            }
            if ($params["extrasPost"]["subGrupoInmueble"] != "") {
                $sql["where"] .= " AND tblcbi.subGrupoInmueble = " . $params["extrasPost"]["subGrupoInmueble"] . " ";
            }
            if ($params["extrasPost"]["claseInmueble"] != "") {
                $sql["where"] .= " AND tblcbi.claseInmueble = " . $params["extrasPost"]["claseInmueble"] . " ";
            }
            if ($params["extrasPost"]["subClaseInmueble"] != "") {
                $sql["where"] .= " AND tblcbi.subClaseInmueble = " . $params["extrasPost"]["subClaseInmueble"] . " ";
            }
        } elseif ($params["extrasPost"]["cveClasificadorBien"] == "7") {
            $sql["campos"] .= ",";
            $sql["campos"] .= "
                tblaah.idAah,
                tblaah.idCogBien,                                
                tblaah.denominacion,
                tblcogbienes.descripcion
            ";
            $sql["tablas"] .= "
                INNER JOIN tblaah tblaah
                ON tblaah.idAah = tblinventarios.idReferencia 
            ";
            $sql["where"] .= " AND tblaah.activo = 'S' ";
        }
        $sql["orders"] = $params["order"]["column"] . " " . $params["order"]["dir"];

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

    public function datatableConsultaResguardo($params, $p = null) {
        $limit = array("max" => $params["limit"]["max"],
            "pag" => $params["limit"]["pag"]);
        $d = array("limit" => $limit);
        $sql = array();
        $sql["campos"] = "
                tblresguardos.idResguardo,
                tblresguardos.idInventario,
                tblresguardos.cveAdscripcion,
                tblresguardos.cveOrganigrama,
                tblresguardos.precioActual,
                tblresguardos.numEmpleadoResguardo,
                tblresguardos.fechaAsigancion,
                tblresguardos.activo,
                tblresguardos.fechaRegistro,
                tblresguardos.cveRegion,
                tblresguardos.fechaActualizacion
            ";
        $sql["tablas"] = "
                tblresguardos tblresguardos
            ";
        $sql["where"] = "
                 tblresguardos.idInventario = " . $params["extrasPost"]["idInventario"] . "
            ";

        $sql["orders"] = $params["order"]["column"] . " " . $params["order"]["dir"];

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
