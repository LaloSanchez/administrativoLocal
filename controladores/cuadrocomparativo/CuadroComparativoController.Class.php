<?php

include_once(dirname(__FILE__) . "/../../modelos/dao/GenericDAO.Class.php");
include_once(dirname(__FILE__) . "/../../tribunal/json/JsonEncod.Class.php");
include_once(dirname(__FILE__) . "/../../tribunal/host/Host.Class.php");
include_once(dirname(__FILE__) . "/../../tribunal/connect/Proveedor.Class.php");
include_once(dirname(__FILE__) . "/../../controladores/bitacora/BitacoraController.Class.php");
include_once(dirname(__FILE__) . "/../../controladores/Imagenes/ImagenesController.Class.php");
include_once(dirname(__FILE__) . "/../../controladores/contadores/ContadoresController.Class.php");
include_once(dirname(__FILE__) . "/../../controladores/planeacion/SeguimientoProyectosController.Class.php");
include_once(dirname(__FILE__) . "/../../webservice/cliente/personal/PersonalCliente.php");
include_once(dirname(__FILE__) . "/../../task/notificaciones/NotificacionesAdministrativo.Class.php");
include_once(dirname(__FILE__) . "/../../tribunal/logger/Logger.Class.php");

class CuadroComparativoController {

    private $proveedor;
    private $adscripcionPadreArray = array();
    private $logger;

    public function __construct() {
        $this->logger = new Logger("/../../logs/", "CuadroComparativo");
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

    public function getTipoAdquisicion($monto, $p = null) {
        $monto = intval($monto);
        $tipoCompra = null;
        $d = array();
        $genericoDao = new GenericDAO();
        $sql = array(
            "campos" => " * ",
            "tablas" => " 
                tbltiposcompras tbltiposcompras
            "
        );
        $sqlSelect = array("tabla" => "", "d" => $d, "tmpSql" => $sql, "proveedor" => $p);
        $rs = $genericoDao->select($sqlSelect);
        if ($rs["totalCount"] > 0) {
            foreach ($rs["data"] as $key => $value) {
                if ($monto >= intval($value["minimo"]) && $monto <= intval($value["maximo"])) {
                    $tipoCompra = $value;
                } elseif ($value["cveTipoCompra"] == 5 && $monto >= intval($value["minimo"]) && $monto >= intval($value["maximo"])) {
                    $tipoCompra = $value;
                }
            }
            return $tipoCompra;
        } else {
            return 0;
        }
    }

    public function getMontoSolicitadoConIdSuficienciaPresupuestal($idSuficienciaPresupuestal, $p = null) {
        $d = array();
        $genericoDao = new GenericDAO();

        $sql = array(
            "campos" => " tblsuficienciaspresupuestales.montoSolicitado ",
            "tablas" => " 
                tblsuficienciaspresupuestales tblsuficienciaspresupuestales
            ",
            "where" => " 
                tblsuficienciaspresupuestales.idSuficienciaPresupuestal = " . $idSuficienciaPresupuestal
        );
        $sqlSelect = array("tabla" => "", "d" => $d, "tmpSql" => $sql, "proveedor" => $p);

        $rs = $genericoDao->select($sqlSelect);
        if ($rs["totalCount"] > 0) {
            return $rs["data"][0];
        } else {
            return 0;
        }
    }

    public function getMontoSolicitadoConIdSuficienciaPresupuestalIN($idSuficienciaPresupuestalIN, $p = null) {
        $d = array();
        $genericoDao = new GenericDAO();

        $sql = array(
            "campos" => " SUM(tblsuficienciaspresupuestales.montoSolicitado) as sumaMonto ",
            "tablas" => " 
                tblsuficienciaspresupuestales tblsuficienciaspresupuestales
            ",
            "where" => " 
                tblsuficienciaspresupuestales.activo = 'S' AND
                tblsuficienciaspresupuestales.idSuficienciaPresupuestal in ( " . $idSuficienciaPresupuestalIN . " ) "
        );
        $sqlSelect = array("tabla" => "", "d" => $d, "tmpSql" => $sql, "proveedor" => $p);

        $rs = $genericoDao->select($sqlSelect);
        if ($rs["totalCount"] > 0) {
            return $rs["data"][0];
        } else {
            return 0;
        }
    }

    public function getProveedores($param) {
        $d = array();
        $genericoDao = new GenericDAO();

        $sql = array(
            "campos" => " tblcedulaproveedores.* ",
            "tablas" => " 
                tblcedulaproveedores tblcedulaproveedores 
                INNER JOIN tblgirosproveedores tblgirosproveedores 
                ON tblcedulaproveedores.idCedulaProveedor = tblgirosproveedores.idCedulaProveedor 
            ",
            "where" => " 
                (tblgirosproveedores.activo = 'S') AND
                (tblcedulaproveedores.activo = 'S') 
            "
        );
//        var_dump($param["extrasPost"]);
//        var_dump(array_key_exists("cveGiro ", $param["extrasPost"]));
        if (array_key_exists("cveGiro", $param["extrasPost"]) && $param["extrasPost"]["cveGiro"] != "" && $param["extrasPost"]["cveGiro"] != null) {
            $sql["where"] .= "AND (tblgirosproveedores.cveGiro = " . $param["extrasPost"]["cveGiro"] . ")";
        }
        $sqlSelect = array("tabla" => "", "d" => $d, "tmpSql" => $sql, "proveedor" => null);

        $rs = $genericoDao->select($sqlSelect);

        $json = new Encode_JSON();

        return $json->encode($rs);
    }

    public function datatableConsultaBienes($params) {
        $limit = array("max" => $params["limit"]["max"],
            "pag" => $params["limit"]["pag"]);
        $d = array("limit" => $limit);

        $sql = array(
            "campos" => " 
                tblsuficienciascog.idSuficienciaCog,
                tblcog.idCog,
                tblcog.cogPropio,
                tblcog.denominacion,
                tblcog.anioCog,
                tblcogbienes.idCogBien,
                tblcogbienes.descripcion       AS descripcionCogBienes,
                tblsuficienciascog.descripcion AS descripcionSuficienciasCog,
                tblsuficienciascog.cantidad,
                tblunidadesmedida.cveUnidadMedida,
                tblunidadesmedida.desUnidadMedida,
                tblsuficienciascog.idSuficienciaPresupuestal, 
                tblsuficienciascog.numero 
            ",
            "tablas" => "
                tblsuficienciascog tblsuficienciascog 
                INNER JOIN tblcogbienes tblcogbienes 
                ON tblsuficienciascog.idCogBien = tblcogbienes.idCogBien 
                INNER JOIN tblcog tblcog 
                ON tblsuficienciascog.idCog = tblcog.idCog AND tblcogbienes.idCog = tblcog.idCog 
                INNER JOIN tblunidadesmedida tblunidadesmedida 
                ON tblcogbienes.cveUnidadMedida = tblunidadesmedida.cveUnidadMedida 
            ",
            "orders" => $params["order"]["column"] . " " . $params["order"]["dir"],
            "where" => " (tblsuficienciascog.idSuficienciaPresupuestal = " . $params["extrasPost"]["idSuficienciaPresupuestal"] . ") AND
                         (tblsuficienciascog.activo = 'S') AND
                         (tblcog.activo = 'S') AND
                         (tblcogbienes.activo = 'S') AND
                         (tblunidadesmedida.activo = 'S') "
        );
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

    public function eliminarAdquisicion($param) {
        $bitacora = new SeguimientoProyectosController();
        $genericoDao = new GenericDAO();
        $this->proveedor->connect();
        $this->proveedor->execute("BEGIN");
        $error = false;
        $respuesta = "";

        $updateAdquisicion = array(
            "tabla" => "tbladquisiciones", "d" => array(
                "values" => array(
                    "activo" => "N",
                    "fechaActualizacion" => "now()"
                ), "where" => array(
                    "idAdquisicion" => $param["extrasPost"]["idAdquisicion"]
                )
            ), "proveedor" => $this->proveedor
        );
        $bitacoraAntes = $bitacora->consultaAntesUpdate($updateAdquisicion);
        $updateAdquisicionRs = $genericoDao->update($updateAdquisicion);
//        var_dump($updateAdquisicionRs);
        if ($updateAdquisicionRs["totalCount"] > 0) {
            $bitacora->guardarBitacora(53, $updateAdquisicionRs, $bitacoraAntes, $this->proveedor);
            $respuesta = $updateAdquisicionRs;
        } else {
            $respuesta = $updateAdquisicionRs;
            $error = true;
        }
        if (!$error) {
            $this->proveedor->execute("COMMIT");
        } else {
            $this->proveedor->execute("ROLLBACK");
        }
        $this->proveedor->close();
        $jsonEncode = new Encode_JSON();
        return $jsonEncode->encode($respuesta);
    }

    public function getTipoCompra($idAdquisicion, $p = null) {
        $genenericDAO = new GenericDAO();
        $d = array();
        $selectTipoCompra = array(
            "campos" => " * ",
            "tablas" => "tbltiposcompras tbltiposcompras",
            "where" => " tbltiposcompras.cveTipoCompra = " . $idAdquisicion
        );

        $sqlSelect = array("tabla" => "", "d" => $d, "tmpSql" => $selectTipoCompra, "proveedor" => $p);
        $rs = $genenericDAO->select($sqlSelect);
        if ($rs["totalCount"] > 0) {
            return $rs["data"][0]["desTipoCompra"];
        } else {
            return 0;
        }
    }

    public function getCveGiro($idCedulaProveedor, $p = null) {
        $d = array();
        $genericoDao = new GenericDAO();
        $respuesta = "";
        $sql = array(
            "campos" => "
                tblgirosproveedores.*
            ",
            "tablas" => "
                tblgirosproveedores tblgirosproveedores 
            ",
            "where" => "
                (tblgirosproveedores.idCedulaProveedor = " . $idCedulaProveedor . ") AND
                (tblgirosproveedores.activo = 'S')
            "
        );
        $sqlSelect = array("tabla" => "", "d" => $d, "tmpSql" => $sql, "proveedor" => $p);
        $rs = $genericoDao->select($sqlSelect);

        if ($rs["totalCount"] > 0) {


            return ($rs);
        } else {
            return 0;
        }
    }

    public function getStringArray($array, $p = null) {
        $stringRs = "";
        foreach ($array as $key => $value) {
            $stringRs .= $value["idSuficienciaPresupuestal"] . ",";
        }
        return substr($stringRs, 0, -1);
    }

    public function consultarSuficienciasEspecial($idAdquisicionSolicitud, $p = null) {
        $genenericDAO = new GenericDAO();
        $respuesta = "";
        $d = array();
        $sql = array(
            "campos" => "
                tblsuficienciaspresupuestales.* 
            ",
            "tablas" => "
        	tblsuficienciaspresupuestales tblsuficienciaspresupuestales 
            ",
            "where" => "
                ((tblsuficienciaspresupuestales.activo = 'S') AND                
                (tblsuficienciaspresupuestales.cveEstatus = 32)) AND
                ((tblsuficienciaspresupuestales.idAdquisicionSolicitud IS NULL) OR 
                tblsuficienciaspresupuestales.idAdquisicionSolicitud = " . $idAdquisicionSolicitud . ")
            "
        );
        $sqlSelect = array("tabla" => "", "d" => $d, "tmpSql" => $sql, "proveedor" => $p);
        $rs = $genenericDAO->select($sqlSelect);
        if ($rs["totalCount"] > 0) {
            $respuesta = $rs;
        } else {
            $respuesta = $rs;
        }
//        $jsonEncode = new Encode_JSON();
        return ($respuesta);
    }

    public function consultarSuficienciasIdAdquisicionSolicitud($idAdquisicionSolicitud, $p = null) {
        $genenericDAO = new GenericDAO();
        $respuesta = "";
        $d = array();
        $sql = array(
            "campos" => "
                tblsuficienciaspresupuestales.* 
            ",
            "tablas" => "
        	tblsuficienciaspresupuestales tblsuficienciaspresupuestales 
            ",
            "where" => "
                ((tblsuficienciaspresupuestales.activo = 'S') AND                
                (tblsuficienciaspresupuestales.cveEstatus = 32)) AND
                (tblsuficienciaspresupuestales.idAdquisicionSolicitud = " . $idAdquisicionSolicitud . ")
            "
        );
        $sqlSelect = array("tabla" => "", "d" => $d, "tmpSql" => $sql, "proveedor" => $p);
        $rs = $genenericDAO->select($sqlSelect);
        if ($rs["totalCount"] > 0) {
            $respuesta = $rs;
        } else {
            $respuesta = $rs;
        }
//        $jsonEncode = new Encode_JSON();
        return ($respuesta);
    }

    public function eliminarSolicitudAdsquisicion($param, $p = null) {
        $bitacora = new SeguimientoProyectosController();
        $this->logger->w_onError("********** eliminarSolicitudAdsquisicion  **********");
        $this->logger->w_onError($this->varDumpToString($param));
        $genenericDAO = new GenericDAO();
        $d = array();
        $this->proveedor->connect();
        $this->proveedor->execute("BEGIN");
        $error = false;
        $respuesta = "";
        if ($this->validarEstatusSolicitud($param["extrasPost"]["idAdquisicionSolicitud"])) {
            $jsonEncode = new Encode_JSON();
            return $jsonEncode->encode(array(
                        "status" => "error",
                        "totalCount" => 0,
                        "mnj" => "Solicitud emitida, ya no es posible modificarla"
            ));
        } else {
            $sql = array(
                "campos" => "
                tblsuficienciaspresupuestales.*
            ",
                "tablas" => "
                tblsuficienciaspresupuestales tblsuficienciaspresupuestales
            ",
                "where" => "
                tblsuficienciaspresupuestales.idAdquisicionSolicitud = " . $param["extrasPost"]["idAdquisicionSolicitud"] . "
            "
            );
            $sqlSelect = array("tabla" => "", "d" => $d, "tmpSql" => $sql, "proveedor" => $this->proveedor);
            $rs = $genenericDAO->select($sqlSelect);
            $this->logger->w_onError("********** CONSULTA PARA SUFICIENCIAS PRESUPUESTALES  **********");
            $this->logger->w_onError($this->varDumpToString($rs));
            if ($rs["totalCount"] > 0) {
                foreach ($rs["data"] as $key => $value) {
                    $updateSuficienciaPresupuestal = array(
                        "tabla" => "tblsuficienciaspresupuestales", "d" => array(
                            "values" => array(
                                "idAdquisicionSolicitud" => "null",
                                "cveTipoCompra" => "null",
                                "fechaActualizacion" => "now()"
                            ), "where" => array(
                                "idSuficienciaPresupuestal" => $value["idSuficienciaPresupuestal"]
                            )
                        ), "proveedor" => $this->proveedor
                    );
                    $bitacoraAntes = $bitacora->consultaAntesUpdate($updateSuficienciaPresupuestal);
                    $updateSuficienciaPresupuestalRs = $genenericDAO->update($updateSuficienciaPresupuestal);
                    $this->logger->w_onError("********** UPDATE NULLL  **********");
                    $this->logger->w_onError($this->varDumpToString($updateSuficienciaPresupuestalRs));
                    if ($updateSuficienciaPresupuestalRs["totalCount"] > 0) {
                        $bitacora->guardarBitacora(53, $updateSuficienciaPresupuestalRs, $bitacoraAntes, $this->proveedor);
                    } else {
                        $respuesta = $updateSuficienciaPresupuestalRs;
                        $error = true;
                    }
                }
                if (!$error) {
                    $updateAdquisicionSolicitud = array(
                        "tabla" => "tbladquisicionsolicitudes", "d" => array(
                            "values" => array(
                                "activo" => "N",
                                "fechaActualizacion" => "now()"
                            ), "where" => array(
                                "idAdquisicionSolicitud" => $param["extrasPost"]["idAdquisicionSolicitud"]
                            )
                        ), "proveedor" => $this->proveedor
                    );
                    $bitacoraAntes = $bitacora->consultaAntesUpdate($updateAdquisicionSolicitud);
                    $updatAdquisicionSolicitudRs = $genenericDAO->update($updateAdquisicionSolicitud);
                    $this->logger->w_onError("********** UPDATE ADQUISICION SOLICITUD   **********");
                    $this->logger->w_onError($this->varDumpToString($updatAdquisicionSolicitudRs));
                    if ($updatAdquisicionSolicitudRs["totalCount"] > 0) {
                        $bitacora->guardarBitacora(53, $updatAdquisicionSolicitudRs, $bitacoraAntes, $this->proveedor);
                        $respuesta = $updatAdquisicionSolicitudRs;
                        $updateOficio = array(
                            "tabla" => "tbloficios", "d" => array(
                                "values" => array(
                                    "activo" => "N",
                                    "fechaActualizacion" => "now()"
                                ), "where" => array(
                                    "idOficio" => $updatAdquisicionSolicitudRs["data"][0]["idOficio"]
                                )
                            ), "proveedor" => $this->proveedor
                        );
                        $bitacoraAntes = $bitacora->consultaAntesUpdate($updateOficio);
                        $updatOficioRs = $genenericDAO->update($updateOficio);
                        $this->logger->w_onError("********** UPDATE OFICIO  **********");
                        $this->logger->w_onError($this->varDumpToString($updatOficioRs));
                        if ($updatOficioRs["totalCount"] > 0) {
                            $bitacora->guardarBitacora(53, $updatOficioRs, $bitacoraAntes, $this->proveedor);
                            $updateDocumentosImg = array(
                                "tabla" => "tbldocumentosimg", "d" => array(
                                    "values" => array(
                                        "activo" => "N",
                                        "fechaActualizacion" => "now()"
                                    ), "where" => array(
                                        "idReferencia" => $updatOficioRs["data"][0]["idOficio"],
                                        "cveTipoDocumento" => "9"
                                    )
                                ), "proveedor" => $this->proveedor
                            );
                            $bitacoraAntes = $bitacora->consultaAntesUpdate($updateDocumentosImg);
                            $updatDocumentosImgRs = $genenericDAO->update($updateDocumentosImg);
                            $this->logger->w_onError("********** UPDATE DOCUMENTOS IMG  **********");
                            $this->logger->w_onError($this->varDumpToString($updatDocumentosImgRs));
                            if ($updatDocumentosImgRs["totalCount"] > 0) {
                                $bitacora->guardarBitacora(53, $updatDocumentosImgRs, $bitacoraAntes, $this->proveedor);
                            } else {
                                $respuesta = $updatDocumentosImgRs;
                                $error = true;
                            }
                        } else {
                            $respuesta = $updatOficioRs;
                            $error = true;
                        }
                    } else {
                        $respuesta = $updatAdquisicionSolicitudRs;
                        $error = true;
                    }
                }
            } else {
                $respuesta = $rs;
                $error = true;
            }
        }

//        $error = true;
        if (!$error) {
            $this->proveedor->execute("COMMIT");
        } else {
            $this->proveedor->execute("ROLLBACK");
        }
        $jsonEncode = new Encode_JSON();
        return $jsonEncode->encode($respuesta);
    }

    public function cambiarEstatus($param, $p = null) {
        $bitacora = new SeguimientoProyectosController();
        $genenericDAO = new GenericDAO();
        $this->proveedor->connect();
        $this->proveedor->execute("BEGIN");
        $error = false;
        $respuesta = "";
        $updateAdquisicionSolicitides = array(
            "tabla" => "tbladquisicionsolicitudes", "d" => array(
                "values" => array(
                    "cveEstatus" => 48,
                    "fechaActualizacion" => "now()"
                ), "where" => array(
                    "idAdquisicionSolicitud" => $param["extrasPost"]["idAquisicionSolicitud"]
                )
            ), "proveedor" => $this->proveedor
        );
        $bitacoraAntes = $bitacora->consultaAntesUpdate($updateAdquisicionSolicitides);
        $updatAdquisicionSolicitudRs = $genenericDAO->update($updateAdquisicionSolicitides);
        if ($updatAdquisicionSolicitudRs["totalCount"] > 0) {
            $bitacora->guardarBitacora(52, $updatAdquisicionSolicitudRs, $bitacoraAntes, $this->proveedor);
            $respuesta = $updatAdquisicionSolicitudRs;
        } else {
            $respuesta = $updatAdquisicionSolicitudRs;
            $error = true;
        }
        if (!$error) {
            $this->proveedor->execute("COMMIT");
        } else {
            $this->proveedor->execute("ROLLBACK");
        }
        $jsonEncode = new Encode_JSON();
        return $jsonEncode->encode($respuesta);
    }

    public function verAdquisicionSolicitud($param, $p = null) {
        $genericoDao = new GenericDAO();
        $d = array();
        $sql = array(
            "campos" => "
                    tbladquisicionsolicitudes.*,
                    tbldocumentosimg.descripcion,
                    concat(tbloficios.numero, '/', 
                    tbloficios.anio) as numeroOficio,
                    tbloficios.sintesis
            ",
            "tablas" => "
        	tbladquisicionsolicitudes tbladquisicionsolicitudes
                INNER JOIN tbloficios tbloficios
                ON (tbloficios.idOficio = tbladquisicionsolicitudes.idOficio)
                INNER JOIN tbldocumentosimg tbldocumentosimg 
                ON (tbladquisicionsolicitudes.idOficio = tbldocumentosimg.idReferencia)
            ",
            "where" => "
                tbldocumentosimg.cveTipoDocumento = 9 AND 
                tbladquisicionsolicitudes.idAdquisicionSolicitud = " . $param["extrasPost"]["idAdquisicionSolicitud"] . "
            "
        );
        $sqlSelect = array("tabla" => "", "d" => $d, "tmpSql" => $sql, "proveedor" => $p);
        $rs = $genericoDao->select($sqlSelect);
        if ($rs["totalCount"] > 0) {
            $rs["data"][0]["listadoSuficienciasPresupuestales"] = $this->consultarSuficienciasEspecial($param["extrasPost"]["idAdquisicionSolicitud"]);
        } else {
            
        }
        $jsonEncode = new Encode_JSON();
        return $jsonEncode->encode($rs);
    }

    public function buscarEnArray($a, $k) {
        foreach ($a as $key => $value) {
            if ($value == $k) {
                return $key;
            }
        }
        return false;
    }

    public function agregarQuitarSuficienciasPresupuestales($anteriores, $nuevas, $p = null) {
        $bitacora = new SeguimientoProyectosController();
        $genenericDAO = new GenericDAO();
        $error = false;
        if ($anteriores["totalCount"] > 0) {
            foreach ($anteriores["data"] as $key => $value) {
                $updateSuficienciaPresupuestal = array(
                    "tabla" => "tblsuficienciaspresupuestales", "d" => array(
                        "values" => array(
                            "idAdquisicionSolicitud" => "null",
                            "cveTipoCompra" => "null",
                            "fechaActualizacion" => "now()"
                        ), "where" => array(
                            "idSuficienciaPresupuestal" => $value["idSuficienciaPresupuestal"]
                        )
                    ), "proveedor" => $p
                );
                $bitacoraAntes = $bitacora->consultaAntesUpdate($updateSuficienciaPresupuestal);
                $updateSuficienciaPresupuestalRs = $genenericDAO->update($updateSuficienciaPresupuestal);
                $this->logger->w_onError("********** UPDATE NULLL  **********");
                $this->logger->w_onError($this->varDumpToString($updateSuficienciaPresupuestalRs));
                if ($updateSuficienciaPresupuestalRs["totalCount"] > 0) {
                    $bitacora->guardarBitacora(51, $updateSuficienciaPresupuestalRs, $bitacoraAntes, $p);
                } else {
                    $error = true;
                }
            }
            if (!$error) {
                foreach ($nuevas as $key => $value) {
                    $updateSuficienciaPresupuestal = array(
                        "tabla" => "tblsuficienciaspresupuestales", "d" => array(
                            "values" => array(
                                "idAdquisicionSolicitud" => $anteriores["data"][0]["idAdquisicionSolicitud"],
                                "fechaActualizacion" => "now()"
                            ), "where" => array(
                                "idSuficienciaPresupuestal" => $value["idSuficienciaPresupuestal"]
                            )
                        ), "proveedor" => $p
                    );
                    if ($value["adjudicacionDirecta"] == "true") {
                        $updateSuficienciaPresupuestal["d"]["values"]["cveTipoCompra"] = 2;
                    } else {
                        $montoSuficiencia = $this->getMontoSolicitadoConIdSuficienciaPresupuestal($value["idSuficienciaPresupuestal"], $p);
//                                    var_dump($montoSuficiencia);
                        $tipoDeCompra = $this->getTipoAdquisicion($montoSuficiencia["montoSolicitado"], $p);
//                                    var_dump($tipoDeCompra);
                        $updateSuficienciaPresupuestal["d"]["values"]["cveTipoCompra"] = $tipoDeCompra["cveTipoCompra"];
                    }
                    $bitacoraAntes = $bitacora->consultaAntesUpdate($updateSuficienciaPresupuestal);
                    $updateSuficienciaPresupuestalRs = $genenericDAO->update($updateSuficienciaPresupuestal);
                    $this->logger->w_onError("********** UPDATE SUFICIENCIA PRESUPUESTAL  **********");
                    $this->logger->w_onError($this->varDumpToString($updateSuficienciaPresupuestalRs));
                    if ($updateSuficienciaPresupuestalRs["totalCount"] > 0) {
                        $bitacora->guardarBitacora(51, $updateSuficienciaPresupuestalRs, $bitacoraAntes, $p);
                    } else {
                        $error = true;
                    }
                }
//                foreach ($nuevas as $key => $value) {
//                    $updateSuficienciaPresupuestal = array(
//                        "tabla" => "tblsuficienciaspresupuestales", "d" => array(
//                            "values" => array(
//                                "idAdquisicionSolicitud" => $anteriores["data"][0]["idAdquisicionSolicitud"],
//                                "fechaActualizacion" => "now()"
//                            ), "where" => array(
//                                "idSuficienciaPresupuestal" => $value["idSuficienciaPresupuestal"]
//                            )
//                        ), "proveedor" => $p
//                    );
//                    $updateSuficienciaPresupuestalRs = $genenericDAO->update($updateSuficienciaPresupuestal);
//                    $this->logger->w_onError("********** UPDATE BIEN  **********");
//                    $this->logger->w_onError($this->varDumpToString($updateSuficienciaPresupuestalRs));
//                    if ($updateSuficienciaPresupuestalRs["totalCount"] > 0) {
//                        
//                    } else {
//                        $error = true;
//                    }
//                }
            }
        } else {
            $error = true;
        }
        return $error;
    }

    public function validarEstatusSolicitud($idAquisicionSolicitud, $p = null) {
        $genenericDAO = new GenericDAO();
        $d = array();
        $sql = array(
            "campos" => "
                    tbladquisicionsolicitudes.cveEstatus
            ",
            "tablas" => "
        	tbladquisicionsolicitudes tbladquisicionsolicitudes
            ",
            "where" => "
                tbladquisicionsolicitudes.idAdquisicionSolicitud = " . $idAquisicionSolicitud . "
            "
        );
        $sqlSelect = array("tabla" => "", "d" => $d, "tmpSql" => $sql, "proveedor" => $p);
        $rs = $genenericDAO->select($sqlSelect);
//        var_dump($rs);
        if ($rs["totalCount"] > 0) {
            if ($rs["data"][0]["cveEstatus"] == "48") {
                return true;
            } else {
                return false;
            }
        } else {
            return -1;
        }
    }

    public function guardarAdquisicionSolicitud($param, $p = null) {
        $bitacora = new SeguimientoProyectosController();
//        var_dump($param);
        $this->logger->w_onError("********** guardarAdquisicionSolicitud **********");
        $this->logger->w_onError($this->varDumpToString($param));
        $genericoDao = new GenericDAO();
        $controllerImagenes = new ImagenesController();
        $this->proveedor->connect();
        $this->proveedor->execute("BEGIN");
        $error = false;
        $respuesta = "";
        $guardarAdquisicionSolicitudRs = null;

        //ACTUALIZA ADQUISICION SOLICITUD
        if ($param["extrasPost"]["idAquisicionSolicitud"] != null && $param["extrasPost"]["idAquisicionSolicitud"] != "") {
            if ($this->validarEstatusSolicitud($param["extrasPost"]["idAquisicionSolicitud"])) {
                $jsonEncode = new Encode_JSON();
                return $jsonEncode->encode(array(
                            "status" => "error",
                            "totalCount" => 0,
                            "mnj" => "Solicitud publicada ya no es posible modificarla"
                ));
            } else {
                //            var_dump($param);
                $d = array();
                $suficienciasAnteriores = $this->consultarSuficienciasIdAdquisicionSolicitud($param["extrasPost"]["idAquisicionSolicitud"], $this->proveedor);
                $this->logger->w_onError("********** SUFICIENCIAS ANTERIORES  **********");
                $this->logger->w_onError($this->varDumpToString($suficienciasAnteriores));
                $suficienciasNuevas = $param["extrasPost"]["listaSuficiencia"];
                $this->logger->w_onError("********** SUFICIENCIAS NUEVAS  **********");
                $this->logger->w_onError($this->varDumpToString($suficienciasNuevas));
                $agregarYquitar = $this->agregarQuitarSuficienciasPresupuestales($suficienciasAnteriores, $suficienciasNuevas, $this->proveedor);
                $this->logger->w_onError("********** AGREGAR O QUITAR SUFICIENCIAS  **********");
                $this->logger->w_onError($this->varDumpToString($agregarYquitar));
                if (!$agregarYquitar) {
                    $sqlAdquisicionesSolicitudes = array(
                        "campos" => "
                        tbladquisicionsolicitudes.*
                    ",
                        "tablas" => "
                        tbladquisicionsolicitudes tbladquisicionsolicitudes
                    ",
                        "where" => "
                        tbladquisicionsolicitudes.activo = 'S' AND
                        tbladquisicionsolicitudes.idAdquisicionSolicitud = " . $param["extrasPost"]["idAquisicionSolicitud"] . "
                    "
                    );
                    if ($param["extrasPost"]["cveEstatus"] != "" && $param["extrasPost"]["cveEstatus"] != null) {
                        $updateSuficienciaPresupuestal = array(
                            "tabla" => "tbladquisicionsolicitudes", "d" => array(
                                "values" => array(
                                    "cveEstatus" => $param["extrasPost"]["cveEstatus"],
                                    "fechaActualizacion" => "now()"
                                ), "where" => array(
                                    "idAdquisicionSolicitud" => $param["extrasPost"]["idAquisicionSolicitud"]
                                )
                            ), "proveedor" => $this->proveedor
                        );
                        $bitacoraAntes = $bitacora->consultaAntesUpdate($updateSuficienciaPresupuestal);
                        $updateSuficienciasPresupuestalesRs = $genericoDao->update($updateSuficienciaPresupuestal);
                        $this->logger->w_onError("############# UPDATE SUFICIENCIAS PARA ESTATUS #############");
                        $this->logger->w_onError($this->varDumpToString($updateSuficienciasPresupuestalesRs));
                        if ($updateSuficienciasPresupuestalesRs["totalCount"] > 0) {
                            $bitacora->guardarBitacora(51, $updateSuficienciasPresupuestalesRs, $bitacoraAntes, $this->proveedor);
                        } else {
                            $this->logger->w_onError("############# ERROR AL UPDATE SUFICIENCIA #############");
                            $this->logger->w_onError($this->varDumpToString($error));
                            $error = true;
                        }
                    }
                    $sqlAdquisicionesSolicitudesSelect = array("tabla" => "", "d" => $d, "tmpSql" => $sqlAdquisicionesSolicitudes, "proveedor" => $this->proveedor);
                    $rsAdquisicionesSolicitudes = $genericoDao->select($sqlAdquisicionesSolicitudesSelect);

                    $this->logger->w_onError("********** SELECT ADQUISICION SOLICITUD  **********");
                    $this->logger->w_onError($this->varDumpToString($rsAdquisicionesSolicitudes));


                    if ($rsAdquisicionesSolicitudes["totalCount"] > 0) {
                        $idAdquisicionSolicitud = $rsAdquisicionesSolicitudes["data"][0]["idAdquisicionSolicitud"];
                        $respuesta = $rsAdquisicionesSolicitudes;
                        $updateOficio = array(
                            "tabla" => "tbloficios", "d" => array(
                                "values" => array(
                                    "sintesis" => $param["extrasPost"]["comentarios"],
                                    "fechaActualizacion" => "now()"
                                ), "where" => array(
                                    "idOficio" => $rsAdquisicionesSolicitudes["data"][0]["idOficio"]
                                )
                            ), "proveedor" => $this->proveedor
                        );
                        $bitacoraAntes = $bitacora->consultaAntesUpdate($updateOficio);
                        $updatOficioRs = $genericoDao->update($updateOficio);
                        $this->logger->w_onError("********** UPDATE OFICIO  **********");
                        $this->logger->w_onError($this->varDumpToString($updatOficioRs));
                        if ($updatOficioRs["totalCount"] > 0) {
                            $bitacora->guardarBitacora(51, $updatOficioRs, $bitacoraAntes, $this->proveedor);
                        } else {
                            $error = true;
                            $this->logger->w_onError("############# ERROR AL UPDATE OFICIO #############");
                            $this->logger->w_onError($this->varDumpToString($error));
                        }
                    } else {
                        $error = true;
                        $respuesta = $rsAdquisicionesSolicitudes;
                        $this->logger->w_onError("############# SELECT ADQUISICION SOLICITUD #############");
                        $this->logger->w_onError($this->varDumpToString($error));
                    }


//                var_dump($updateAdquisicionSolicitudesRs);
                    if ($rsAdquisicionesSolicitudes["totalCount"] > 0) {
                        $updateDocumentosImg = array(
                            "tabla" => "tbldocumentosimg", "d" => array(
                                "values" => array(
                                    "fechaActualizacion" => "now()",
                                    "descripcion" => str_ireplace("'", "\\'", ($param["extrasPost"]["observaciones"]))
                                ), "where" => array(
                                    "cveTipoDocumento" => 9,
                                    "idReferencia" => $rsAdquisicionesSolicitudes["data"][0]["idOficio"]
                                )
                            ), "proveedor" => $this->proveedor
                        );
                        $bitacoraAntes = $bitacora->consultaAntesUpdate($updateDocumentosImg);
                        $updateDocumentosImgRs = $genericoDao->update($updateDocumentosImg);


                        if ($updateDocumentosImgRs["totalCount"] > 0) {
                            $imagenesController = new ImagenesController();
                            $documentoImagenes = $imagenesController->consultarTiposDocumentos($updateDocumentosImgRs, $this->proveedor);
                            $pdf = new SeguimientoProyectosController();
                            $error = $pdf->convertirEditorPDF($documentoImagenes["data"][0]["descripcion"], $documentoImagenes, true, $this->proveedor);
                        } else {
                            $error = true;
                            $this->logger->w_onError("********** ERROR UPDATE DOCUMENTOS IMG  **********");
                            $this->logger->w_onError($this->varDumpToString($updateDocumentosImgRs));
                        }


                        $this->logger->w_onError("**********UPDATE DOCUMENTOS IMG  **********");
                        $this->logger->w_onError($this->varDumpToString($updateDocumentosImgRs));
                        if ($updateDocumentosImgRs["totalCount"] > 0) {
                            $bitacora->guardarBitacora(51, $updateDocumentosImgRs, $bitacoraAntes, $this->proveedor);
                            $respuesta = $rsAdquisicionesSolicitudes;
                        } else {
                            $error = true;
                            $this->logger->w_onError("############# UPDATE DOCUMENTOS IMG #############");
                            $this->logger->w_onError($this->varDumpToString($error));
                        }
                    } else {
                        $error = true;
                        $this->logger->w_onError("############# SELECT ADQUISICION SOLICITUD #############");
                        $this->logger->w_onError($this->varDumpToString($error));
                    }
                } else {
                    $error = true;
                    $this->logger->w_onError("############# ERROR AGREGAR QUITAR  #############");
                    $this->logger->w_onError($this->varDumpToString($error));
                }
//            $error = true;
            }
        } else {
//            $error = true;
            // CONTADORES 
            $contadoresController = new ContadoresController();
            $contadoresOficio["cveAdscripcion"] = $this->adscripcionPadreArray["cveAdscripcion"];
            $contadoresOficio["cveTipoDocContador"] = 1; // OFICIO
            $contadoresOficio["cveOrganigrama"] = $this->adscripcionPadreArray["cveOrganigrama"];
            $contadoresOficio["mes"] = "N";
            $contadoresOficio["cveMes"] = 0;
            $contadoresOficio["anio"] = date("Y");
            $contadoresOficioRs = $contadoresController->getContador($contadoresOficio, $this->proveedor);
            $this->logger->w_onError("********** CONSULTA DE CONTADORES **********");
            $this->logger->w_onError($this->varDumpToString($contadoresOficioRs));
            if ($contadoresOficioRs["totalCount"] > 0) {
                //INSERTA SOLICITUD ADQUISICION
                $guardarOficio = array(
                    "tabla" => "tbloficios", "d" => array(
                        "values" => array(
                            "numero" => $contadoresOficioRs["data"][0]["numero"],
                            "anio" => $contadoresOficioRs["data"][0]["anio"],
                            "cveAdscripcion" => $this->adscripcionPadreArray["cveAdscripcion"],
                            "cveOrganigrama" => $this->adscripcionPadreArray["cveOrganigrama"],
                            "cveAdscripcionDestinatario" => 10389,
                            "cveOrganigramaDestinatario" => 3013404100,
                            "sintesis" => $param["extrasPost"]["comentarios"],
                            "activo" => "S",
                            "fechaRegistro" => "now()",
                            "fechaActualizacion" => "now()",
                        )), "proveedor" => $this->proveedor
                );

                $guardarOficioRs = $genericoDao->insert($guardarOficio);
                $this->logger->w_onError("********** INSERTAR OFICIO **********");
                $this->logger->w_onError($this->varDumpToString($guardarOficioRs));
                if ($guardarOficioRs["totalCount"] > 0) {
                    $bitacora->guardarBitacora(50, $guardarOficioRs, null, $this->proveedor);
                    $contadoresSolicitudes["cveAdscripcion"] = $this->adscripcionPadreArray["cveAdscripcion"];
                    $contadoresSolicitudes["cveTipoDocContador"] = 2; // Solicitud de ADQUISICION
                    $contadoresSolicitudes["cveOrganigrama"] = $this->adscripcionPadreArray["cveOrganigrama"];
                    $contadoresSolicitudes["mes"] = "N";
                    $contadoresSolicitudes["cveMes"] = 0;
                    $contadoresSolicitudes["anio"] = date("Y");
                    $contadoresSolicitudesRs = $contadoresController->getContador($contadoresSolicitudes, $this->proveedor);
                    $this->logger->w_onError("********** CONSULTA DE CONTADORES **********");
                    $this->logger->w_onError($this->varDumpToString($contadoresSolicitudesRs));
//                if (($param["extrasPost"]["adjudicacionDirecta"]) == "true") {
                    //adjudicacion directa 2
//                    $guardarAdquisicionSolicitudes = array(
//                        "tabla" => "tbladquisicionsolicitudes", "d" => array(
//                            "values" => array(
//                                /*                                 * "cveTipoCompra" => 2, SE ELIMINO EL CAMPO * */
//                                "cveAdscripcion" => $this->adscripcionPadreArray["cveAdscripcion"],
//                                "cveOrganigrama" => $this->adscripcionPadreArray["cveOrganigrama"],
//                                "idOficio" => $guardarOficioRs["data"][0]["idOficio"],
//                                "cveEstatus" => "47",
//                                "numeroOficio" => "1",
//                                "numeroSolicitud" => "1",
//                                "anioSolicitud" => "YEAR(now())",
//                                "activo" => "S",
//                                "fechaRegistro" => "now()",
//                                "fechaActualizacion" => "now()",
//                            )), "proveedor" => $this->proveedor
//                    );
//                    $guardarAdquisicionSolicitudRs = $genericoDao->insert($guardarAdquisicionSolicitudes);
//                    $this->logger->w_onError("********** INSERTAR ADQUISICION SOLICITUD **********");
//                    $this->logger->w_onError($this->varDumpToString($guardarAdquisicionSolicitudRs));
//                    if ($guardarAdquisicionSolicitudRs["totalCount"] > 0) {
//                        $respuesta = $guardarAdquisicionSolicitudRs;
//                    } else {
//                        $error = true;
//                        $respuesta = $guardarAdquisicionSolicitudRs;
//                    }
//                } else {
//                    $stringArrayList = ($this->getStringArray($param["extrasPost"]["listaSuficiencia"]));
//                    $this->logger->w_onError("********** STRING ARRAY LIST  **********");
//                    $this->logger->w_onError($this->varDumpToString($stringArrayList));
//                    $sumaMonto = $this->getMontoSolicitadoConIdSuficienciaPresupuestalIN($stringArrayList, $this->proveedor);
//                    $this->logger->w_onError("********** SUMA MONTO  **********");
//                    $this->logger->w_onError($this->varDumpToString($sumaMonto));
//                    $tipoCompra = $this->getTipoAdquisicion($sumaMonto, $this->proveedor);
//                    $this->logger->w_onError("********** TIPO COMPRA  **********");
//                    $this->logger->w_onError($this->varDumpToString($tipoCompra));
//                    if ($tipoCompra != 0) {
                    if ($contadoresSolicitudesRs["totalCount"] > 0) {
                        $guardarAdquisicionSolicitudes = array(
                            "tabla" => "tbladquisicionsolicitudes", "d" => array(
                                "values" => array(
                                    /*                                     * "cveTipoCompra" => $tipoCompra["cveTipoCompra"], TIPO COMPRA SE ELIMINO* */
                                    "cveAdscripcion" => $this->adscripcionPadreArray["cveAdscripcion"],
                                    "cveOrganigrama" => $this->adscripcionPadreArray["cveOrganigrama"],
                                    "idOficio" => $guardarOficioRs["data"][0]["idOficio"],
                                    /*                                     * "descripcionSolicitud" => $param["extrasPost"]["comentarios"],* */
                                    "numeroOficio" => 1,
                                    "numeroSolicitud" => $contadoresSolicitudesRs["data"][0]["numero"],
                                    "cveEstatus" => 47,
                                    "anioSolicitud" => $contadoresSolicitudesRs["data"][0]["anio"],
                                    "activo" => "S",
                                    "fechaRegistro" => "now()",
                                    "fechaActualizacion" => "now()",
                                )), "proveedor" => $this->proveedor
                        );
                        $guardarAdquisicionSolicitudRs = $genericoDao->insert($guardarAdquisicionSolicitudes);
                        $this->logger->w_onError("********** INSERT ADQUISICION SOLICITUD  **********");
                        $this->logger->w_onError($this->varDumpToString($guardarAdquisicionSolicitudRs));
                        if ($guardarAdquisicionSolicitudRs["totalCount"] > 0) {
                            $bitacora->guardarBitacora(50, $guardarAdquisicionSolicitudRs, null, $this->proveedor);
                            $respuesta = $guardarAdquisicionSolicitudRs;
                        } else {
                            $error = true;
                            $respuesta = $guardarAdquisicionSolicitudRs;
                            $this->logger->w_onError("############# GUARDAR ADQUISICION SOLICITUD  #############");
                            $this->logger->w_onError($this->varDumpToString($error));
                        }
//                    } else {
//                        $error = true;
//                    }
//                }
                        if (!$error) {
                            if ($guardarAdquisicionSolicitudRs != null) {
                                if ($guardarAdquisicionSolicitudRs["totalCount"] > 0) {
                                    $idAdquisicionSolicitud = $guardarAdquisicionSolicitudRs["data"][0]["idAdquisicionSolicitud"];
                                    foreach ($param["extrasPost"]["listaSuficiencia"] as $key => $value) {
                                        $updateSuficienciaPresupuestal = array(
                                            "tabla" => "tblsuficienciaspresupuestales", "d" => array(
                                                "values" => array(
                                                    "idAdquisicionSolicitud" => $guardarAdquisicionSolicitudRs["data"][0]["idAdquisicionSolicitud"],
                                                    "fechaActualizacion" => "now()"
                                                ), "where" => array(
                                                    "idSuficienciaPresupuestal" => $value["idSuficienciaPresupuestal"]
                                                )
                                            ), "proveedor" => $this->proveedor
                                        );
                                        if ($value["adjudicacionDirecta"] == "true") {
                                            $updateSuficienciaPresupuestal["d"]["values"]["cveTipoCompra"] = 2;
                                        } else {
                                            $montoSuficiencia = $this->getMontoSolicitadoConIdSuficienciaPresupuestal($value["idSuficienciaPresupuestal"], $this->proveedor);
//                                    var_dump($montoSuficiencia);
                                            $tipoDeCompra = $this->getTipoAdquisicion($montoSuficiencia["montoSolicitado"], $this->proveedor);
//                                    var_dump($tipoDeCompra);
                                            $updateSuficienciaPresupuestal["d"]["values"]["cveTipoCompra"] = $tipoDeCompra["cveTipoCompra"];
                                        }
                                        $bitacoraAntes = $bitacora->consultaAntesUpdate($updateSuficienciaPresupuestal);
                                        $updateSuficienciaPresupuestalRs = $genericoDao->update($updateSuficienciaPresupuestal);
                                        $this->logger->w_onError("********** UPDATE SUFICIENCIA PRESUPUESTAL  **********");
                                        $this->logger->w_onError($this->varDumpToString($updateSuficienciaPresupuestalRs));
                                        if ($updateSuficienciaPresupuestalRs["totalCount"] > 0) {
                                            $bitacora->guardarBitacora(51, $updateSuficienciaPresupuestalRs, $bitacoraAntes, $this->proveedor);
                                        } else {
                                            $error = true;
                                            $this->logger->w_onError("############# UPDATE SUFICIENCIA PRESUPUESTAL  #############");
                                            $this->logger->w_onError($this->varDumpToString($error));
                                        }
                                    }
                                    if (!$error) {
//                                var_dump($param);
                                        $guardarDocumentosImg = array(
                                            "tabla" => "tbldocumentosimg",
                                            "d" => array(
                                                "values" => array(
                                                    "descripcion" => str_ireplace("'", "\\'", ($param["extrasPost"]["observaciones"])),
                                                    "cveUsuario" => $_SESSION["NumEmpleado"],
                                                    "cveTipoDocumento" => 9,
                                                    "idReferencia" => $guardarOficioRs["data"][0]["idOficio"],
                                                    "activo" => "S",
                                                    "fechaRegistro" => "now()",
                                                    "fechaActualizacion" => "now()",
                                                )
                                            ),
                                            "proveedor" => $this->proveedor
                                        );
                                        $imagenes = $controllerImagenes->crearDocumentoImg($guardarDocumentosImg);
                                        $this->logger->w_onError("********** INSERT DOCUMENTOS IMG  **********");
                                        $this->logger->w_onError($this->varDumpToString($imagenes));
                                        if ($imagenes["totalCount"] > 0) {
                                            $pdf = new SeguimientoProyectosController();
                                            $error = $pdf->convertirEditorPDF($imagenes["data"][0]["descripcion"], $imagenes, false, $this->proveedor);
                                        } else {
                                            $error = true;
                                            $this->logger->w_onError("############# CREAR DOCUMENTOS IMG  #############");
                                            $this->logger->w_onError($this->varDumpToString($error));
                                        }
                                    }
                                }
                            }
                        }
                    } else {
                        $error = true;
                    }
                } else {
                    $error = true;
                    $this->logger->w_onError("############# GUARDAR OFICIO  #############");
                    $this->logger->w_onError($this->varDumpToString($error));
                }
            } else {
                $error = true;
            }
        }
        if (!$error) {
            $respuesta = $this->verAdquisicionSolicitud(array("extrasPost" => array("idAdquisicionSolicitud" => $idAdquisicionSolicitud)), $this->proveedor);
        }
//        $error = true;
        $this->logger->w_onError("********** COMIIT  **********");
        $this->logger->w_onError($this->varDumpToString($error));
        if (!$error) {
            $this->proveedor->execute("COMMIT");
        } else {
            $this->proveedor->execute("ROLLBACK");
        }
        $jsonEncode = new Encode_JSON();
        if (gettype($respuesta) == "string") {
            return ($respuesta);
        } else {
            return $jsonEncode->encode($respuesta);
        }
    }

    public function varDumpToString($var) {
        ob_start();
        var_dump($var);
        $result = ob_get_clean();
        return $result;
    }

    public function consultarSuficienciasPresupuestales($param, $p = null) {
//        var_dump($this->adscripcionPadreArray);
        $genenericDAO = new GenericDAO();
        $respuesta = "";
        $d = array();
        $sql = array(
            "campos" => "
                tblsuficienciaspresupuestales.* 
            ",
            "tablas" => "
        	tblsuficienciaspresupuestales tblsuficienciaspresupuestales 
            ",
            "where" => "
                (tblsuficienciaspresupuestales.activo = 'S') AND                
                (tblsuficienciaspresupuestales.cveEstatus = 32) AND
                (tblsuficienciaspresupuestales.idAdquisicionSolicitud IS NULL)
            "
        );
        $sqlSelect = array("tabla" => "", "d" => $d, "tmpSql" => $sql, "proveedor" => $p);
        $rs = $genenericDAO->select($sqlSelect);
        if ($rs["totalCount"] > 0) {
            $respuesta = $rs;
        } else {
            $respuesta = $rs;
        }
        $jsonEncode = new Encode_JSON();
        return $jsonEncode->encode($respuesta);
    }

    public function eliminarPropuestaProveedor($param, $p = null) {
        $genenericDAO = new GenericDAO();
        $updatePropuestaProveedor = array(
            "tabla" => "tblsuficienciascogproveedores", "d" => array(
                "values" => array(
                    "activo" => "N",
                    "fechaActualizacion" => "now()"
                ), "where" => array(
                    "idSuficienciaPresupuestal" => $param["extrasPost"]["idSuficienciaPresupuestal"],
                    "idCedulaProveedor" => $param["extrasPost"]["idCedulaProveedor"]
                )
            ), "proveedor" => $p
        );
        $updatePropuestaProveedorRs = $genenericDAO->update($updatePropuestaProveedor);
        if ($updatePropuestaProveedorRs["totalCount"] > 0) {
            $jsonEncode = new Encode_JSON();
            return $jsonEncode->encode($updatePropuestaProveedorRs);
        } else {
            return 0;
        }
    }

    public function consultarPropuestaProveedor($param, $p = null) {
        $d = array();
        $genericoDao = new GenericDAO();
        $respuesta = "";
        $sql = array(
            "campos" => "
                tblsuficienciascogproveedores.*
            ",
            "tablas" => "
                tblsuficienciascogproveedores tblsuficienciascogproveedores 
            ",
            "where" => "
                (tblsuficienciascogproveedores.idSuficienciaPresupuestal = " . $param["extrasPost"]["idSuficienciaPresupuestal"] . ") AND
                (tblsuficienciascogproveedores.idCedulaProveedor = " . $param["extrasPost"]["idCedulaProveedor"] . ") AND
                (tblsuficienciascogproveedores.activo = 'S')
            "
        );
        $sqlSelect = array("tabla" => "", "d" => $d, "tmpSql" => $sql, "proveedor" => $p);
        $rs = $genericoDao->select($sqlSelect);

        if ($rs["totalCount"] > 0) {
            foreach ($rs["data"] as $key => $value) {
                foreach ($value as $key2 => $value2) {
                    if ($key2 == "idCedulaProveedor") {
                        $rs["data"][$key]["cveGiro"] = $this->getCveGiro($value2);
                    }
                }
            }
            $jsonEncode = new Encode_JSON();
            return $jsonEncode->encode($rs);
        } else {
            return 0;
        }
    }

    public function consultarListaProveedores($param, $p = null) {
        $d = array();
        $genericoDao = new GenericDAO();

        $sql = array(
            "campos" => "
                tblcedulaproveedores.*                 
            ",
            "tablas" => "
                tblsuficienciascogproveedores tblsuficienciascogproveedores 
                INNER JOIN tblcedulaproveedores tblcedulaproveedores 
                ON tblsuficienciascogproveedores.idCedulaProveedor = tblcedulaproveedores.idCedulaProveedor 
            ",
            "where" => "
                (tblsuficienciascogproveedores.idSuficienciaPresupuestal = " . $param["extrasPost"]["idSuficienciaPresupuestal"] . ") AND
                (tblsuficienciascogproveedores.activo = 'S') AND
                (tblcedulaproveedores.activo = 'S') 
            ",
            "groups" => " 
                tblcedulaproveedores.idCedulaProveedor
            "
        );
        $sqlSelect = array("tabla" => "", "d" => $d, "tmpSql" => $sql, "proveedor" => $p);
        $rs = $genericoDao->select($sqlSelect);
        $jsonEncode = new Encode_JSON();

        if ($rs["totalCount"] > 0) {
            return $jsonEncode->encode($rs);
        } else {
            return 0;
        }
    }

    public function guardarSuficienciasCogProveedores($param) {
//        var_dump($param);
        $genericoDao = new GenericDAO();
        $this->proveedor->connect();
        $this->proveedor->execute("BEGIN");
        $error = false;
        $respuesta = "";
        foreach ($param["extrasPost"]["lista"] as $key => $value) {
            $suficienciaCogProveedores = array(
                "tabla" => "tblsuficienciascogproveedores", "d" => array(
                    "values" => array(
                        "idSuficienciaCog" => $value["idSuficienciaCog"],
                        "idCedulaProveedor" => $value["idCedulaProveedor"],
                        "entrega" => $value["entrada"],
                        "subTotal" => $value["subTotal"],
                        "cveUnidadEntrega" => $value["cveUnidadEntrada"],
                        "precioUnitario" => $value["precioUnitario"],
                        "idSuficienciaPresupuestal" => $value["idSuficienciaPresupuestal"],
                        "iva" => $value["iva"],
                        "activo" => "S",
                        "fechaRegistro" => "now()",
                        "fechaActualizacion" => "now()",
                    )), "proveedor" => $this->proveedor
            );
            if ($value["idSuficienciaCogProveedor"] != "" && $value["idSuficienciaCogProveedor"] != null) {
                $suficienciaCogProveedores["d"]["where"] = array("idSuficienciaCogProveedor" => $value["idSuficienciaCogProveedor"]);
                $suficienciaCogProveedoresRS = $genericoDao->update($suficienciaCogProveedores);
            } else {
                $suficienciaCogProveedoresRS = $genericoDao->insert($suficienciaCogProveedores);
            }

            if ($suficienciaCogProveedoresRS["totalCount"] > 0) {
                $respuesta = $suficienciaCogProveedoresRS;
            } else {
                $respuesta = $suficienciaCogProveedoresRS;
                $error = true;
            }
        }

        if (!$error) {
            $this->proveedor->execute("COMMIT");
        } else {
            $this->proveedor->execute("ROLLBACK");
        }
        $this->proveedor->close();
        $jsonEncode = new Encode_JSON();
        return $jsonEncode->encode($respuesta);
    }

    public function getUnidadEntrega() {
        $genenericDAO = new GenericDAO();
        $d = array();
        $selectTipoCompra = array(
            "campos" => " * ",
            "tablas" => "tblunidadesentrega tblunidadesentrega"
        );

        $sqlSelect = array("tabla" => "", "d" => $d, "tmpSql" => $selectTipoCompra, "proveedor" => null);
        $rs = $genenericDAO->select($sqlSelect);
        $json = new Encode_JSON();
        if ($rs["totalCount"] > 0) {
            return $json->encode($rs);
        } else {
            return 0;
        }
    }

    public function guardarAdquisicion($param) {
//        var_dump($param);
        $genericoDao = new GenericDAO();
        $this->proveedor->connect();
        $this->proveedor->execute("BEGIN");
        $error = false;
        $respuesta = "";
        $montoSolicitado = $this->getMontoSolicitadoConIdSuficienciaPresupuestal($param["extrasPost"]["idSuficienciaPresupuestal"], $this->proveedor);
        $guardarAdquisicion = array(
            "tabla" => "tbladquisiciones",
            "d" => array(
                "values" => array(
                    "idSuficienciaPresupuestal" => $param["extrasPost"]["idSuficienciaPresupuestal"],
                    "activo" => "S",
                    "fechaRegistro" => "now()",
                    "fechaActualizacion" => "now()",
                )
            ),
            "proveedor" => $this->proveedor
        );
        if ($param["extrasPost"]["tipoAdjudicacion"] == "true") {
//            var_dump($param["extrasPost"]["tipoAdjudicacion"]);
            $guardarAdquisicion["d"]["values"]["cveTipoCompra"] = 2;
        } else {
//            var_dump($param["extrasPost"]["tipoAdjudicacion"]);
            $guardarAdquisicion["d"]["values"]["cveTipoCompra"] = $this->getTipoAdquisicion($montoSolicitado, $this->proveedor)["cveTipoCompra"];
        }

        $guardarAdquisicionRs = $genericoDao->insert($guardarAdquisicion);
        if ($guardarAdquisicionRs["totalCount"] > 0) {
            $guardarAdquisicionRs["data"][0]["desTipoCompra"] = $this->getTipoCompra($guardarAdquisicionRs["data"][0]["cveTipoCompra"], $this->proveedor);
            $respuesta = $guardarAdquisicionRs;
        } else {
            $error = true;
            $respuesta = $guardarAdquisicionRs;
        }
        if (!$error) {
            $this->proveedor->execute("COMMIT");
        } else {
            $this->proveedor->execute("ROLLBACK");
        }
        $this->proveedor->close();
        $jsonEncode = new Encode_JSON();
        return $jsonEncode->encode($respuesta);
    }

    public function datatableConsultaAdquisicionSolicitid($params) {
        $limit = array("max" => $params["limit"]["max"],
            "pag" => $params["limit"]["pag"]);
        $d = array("limit" => $limit);

        $sql = array(
            "campos" => " 
                tbladquisicionsolicitudes.idAdquisicionSolicitud,
                tbloficios.numero,
                tbloficios.anio,
                tbladquisicionsolicitudes.numeroSolicitud,
                tbladquisicionsolicitudes.anioSolicitud,
                tbladquisicionsolicitudes.fechaRegistro,
                tblestatus.desEstatus,
                tbloficios.sintesis,
                tbloficios.idOficio,
                tblestatus.cveEstatus 
            ",
            "tablas" => "
                tbladquisicionsolicitudes tbladquisicionsolicitudes 
                INNER JOIN tbloficios tbloficios 
                ON tbladquisicionsolicitudes.idOficio = tbloficios.idOficio 
                INNER JOIN tblestatus tblestatus 
                ON tbladquisicionsolicitudes.cveEstatus = tblestatus.cveEstatus 
            ",
            "orders" => $params["order"]["column"] . " " . $params["order"]["dir"],
            "where" => " (tbladquisicionsolicitudes.activo = 'S') AND
                        (tbloficios.activo = 'S') AND
                        (tbladquisicionsolicitudes.cveAdscripcion = " . $this->getAdscripcionPadreArray()["cveAdscripcion"] . ") "
        );
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

    public function datatableConsultaSuficienciasPresupuestales($params) {
        $limit = array("max" => $params["limit"]["max"],
            "pag" => $params["limit"]["pag"]);
        $d = array("limit" => $limit);

        $sql = array(
            "campos" => " 
                tblsuficienciaspresupuestales.idSuficienciaPresupuestal,
                tblsuficienciaspresupuestales.anioSufuciencia,
                tblsuficienciaspresupuestales.mesSuficiencia,
                tblsuficienciaspresupuestales.fechaRecepcion,
                tblsuficienciaspresupuestales.folio,
                tblsuficienciaspresupuestales.justificacion,
                tblsuficienciaspresupuestales.cveAdscripcion,
                tblsuficienciaspresupuestales.fechaRegistro,
                tblsuficienciaspresupuestales.cveOrganigrama,
                tblsuficienciaspresupuestales.cveEstatus,
                tblsuficienciaspresupuestales.montoSolicitado,
                tbladquisiciones.idAdquisicion,
                tbltiposcompras.cveTipoCompra,
                tbltiposcompras.desTipoCompra
            ",
            "tablas" => "
                tblsuficienciaspresupuestales tblsuficienciaspresupuestales 
                LEFT JOIN tbladquisiciones tbladquisiciones 
                ON (tblsuficienciaspresupuestales.idSuficienciaPresupuestal = tbladquisiciones.idSuficienciaPresupuestal 
                AND tbladquisiciones.activo='S')
                LEFT JOIN tbltiposcompras tbltiposcompras 
                ON (tbladquisiciones.cveTipoCompra = tbltiposcompras.cveTipoCompra
                AND tbltiposcompras.activo = 'S')
            ",
            "orders" => $params["order"]["column"] . " " . $params["order"]["dir"],
            "where" => " ( tblsuficienciaspresupuestales.activo = 'S' ) "
        );
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

    public function datatableGenerico($params, $param, $limit, $nombreTabla, $condiciones = "", $agrupacion = "", $orders = "", $extras = null) {

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
                    if ($key == "numEmpleadoResponsable") {
                        $nombreEmpleadoRs = json_decode($this->getNombrePersonalCliente($value));
                        if (intval($nombreEmpleadoRs->totalCount) > 0) {
                            $nombreEmpleado = $nombreEmpleadoRs->data[0]->TituloTrato . " " . $nombreEmpleadoRs->data[0]->Nombre . " " . $nombreEmpleadoRs->data[0]->Paterno . " " . $nombreEmpleadoRs->data[0]->Materno;
                            $registro[] = $nombreEmpleado;
                        } else {
                            
                        }
                    }
                    if ($key == "cveAdscripcion") {
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

    public function getAdscripcionNombre($ads = null) {
        $fileJson = "../../../archivos/juzgados" . date("Ymd") . ".json";
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

}

//$CuadroComparativoController = new CuadroComparativoController();
//var_dump($CuadroComparativoController->getMontoSolicitadoConIdSuficienciaPresupuestal(1));
