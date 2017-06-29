<?php

include_once(dirname(__FILE__) . "/../../modelos/dao/GenericDAO.Class.php");
include_once(dirname(__FILE__) . "/../../tribunal/json/JsonEncod.Class.php");
include_once(dirname(__FILE__) . "/../../tribunal/host/Host.Class.php");
include_once(dirname(__FILE__) . "/../../tribunal/connect/Proveedor.Class.php");
include_once(dirname(__FILE__) . "/../../controladores/bitacora/BitacoraController.Class.php");
include_once(dirname(__FILE__) . "/../../controladores/Imagenes/ImagenesController.Class.php");
include_once(dirname(__FILE__) . "/../../controladores/cuadrocomparativo/CuadroComparativoController.Class.php");
include_once(dirname(__FILE__) . "/../../webservice/cliente/personal/PersonalCliente.php");
include_once(dirname(__FILE__) . "/../../task/notificaciones/NotificacionesAdministrativo.Class.php");
set_time_limit(-1);
//session_start();

/**
 * Clase generica que permite consultar 
 * los clasificadores 
 * acciones
 * 

  7	AGREGAR EVIDENCIA DE UNA ACCION
  8	ELIMINAR EVIDENCIA DE UNA ACCION
  9	ADJUNTAR EVIDENCIA NUEVA DE UNA ACCION
  10	ELIMINAR ARCHIVO ADJUNTO A EVIDENCIA DE UNA ACCION
  11	MODIFICAR DATOS EVIDENCIA REGISTRADA
  12	FIRMAR EVIDENCIA
  13	AGREGAR CONOCIMIENTO DE PROYECTO PROGRAMATICO

 * @author PJ
 */
error_reporting(E_ALL ^ E_NOTICE);

class SeguimientoProyectosController {

    private $adscripcionPadreArray = array();

    public function __construct() {
        if (substr($_SESSION["cveOrganigrama"], -3) != "000") {
            $adscripcionPadre = $this->getAdscripcionPadre($_SESSION["cveAdscripcion"]);
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

    public function datatableConsultaHistorial($params) {
//        var_dump($params);
        $limit = array("max" => $params["limit"]["max"],
            "pag" => $params["limit"]["pag"]);
        $d = array("limit" => $limit);

        $sql = array(
            "campos" => " 
            tblproyectosprogbitacoras.idProyectoProgBitacora,
            tblacciones.desAccion,
            tblproyectosprogbitacoras.nombreEmpleado,
            tblproyectosprogbitacoras.fechaRegistro,
            tblproyectosprogbitacoras.idProyectoProgramatico,
            tblacciones.cveAccion 
            ",
            "tablas" => "
            tblproyectosprogbitacoras tblproyectosprogbitacoras 
		INNER JOIN tblacciones tblacciones 
		ON tblproyectosprogbitacoras.cveAccion = tblacciones.cveAccion
            ",
            "orders" => $params["order"]["column"] . " " . $params["order"]["dir"],
            "where" => " ( tblproyectosprogbitacoras.idProyectoProgramatico = " . $params["extrasPost"]["idProyectoProyectoProgramatico"] . "  ) "
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
        return $this->datatableGenerico($params, $param, $limit, $nombreTabla = $sql['tablas'], $sql['where'], "", "", true);
    }

    public function datatableConsultaObservaciones($params) {
//        var_dump($params);
        $limit = array("max" => $params["limit"]["max"],
            "pag" => $params["limit"]["pag"]);
        $d = array("limit" => $limit);

        $sql = array(
            "campos" => " 
            tblobservacionesevidencias.idObservacionEvidencia,
            tblobservacionesevidencias.comentarios,
            tblevidenciaacciones.desEvidenciaAccion,
            tblaccionesproyecto.desAccionProyecto,
            tblobservacionesevidencias.fechaRegistro,
            tblevidenciaacciones.cveEvidenciaAccion,
            tblaccionesproyecto.idAccionProyecto
            ",
            "tablas" => "
            tblobservacionesevidencias tblobservacionesevidencias 
            INNER JOIN tblevidenciaacciones tblevidenciaacciones 
            ON tblobservacionesevidencias.cveEvidenciaAccion = tblevidenciaacciones.cveEvidenciaAccion 
            INNER JOIN tblaccionesproyecto tblaccionesproyecto 
            ON tblevidenciaacciones.idAccionProyecto = tblaccionesproyecto .idAccionProyecto
            ",
            "orders" => $params["order"]["column"] . " " . $params["order"]["dir"],
            "where" => " (tblaccionesproyecto.idProyectoProgramatico = " . $params["extrasPost"]["idProyectoProyectoProgramatico"] . "  ) "
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
        return $this->datatableGenerico($params, $param, $limit, $nombreTabla = $sql['tablas'], $sql['where'], "", "", true);
    }

    public function datatableConsultaObservacionesIndicador($params) {
//        var_dump($params);
        $limit = array("max" => $params["limit"]["max"],
            "pag" => $params["limit"]["pag"]);
        $d = array("limit" => $limit);

        $sql = array(
            "campos" => " 
            tblobservacionesevidenciasip.idObservacionEvidenciaIP,
            tblobservacionesevidenciasip.comentarios,
            tblevidenciasindicadoresproyectos.desEvidencia,
            tblobservacionesevidenciasip.fechaRegistro,
            tblevidenciasindicadoresproyectos.idEvidenciaIndicadorProyectos
            ",
            "tablas" => "
            tblobservacionesevidenciasip tblobservacionesevidenciasip 
            INNER JOIN tblevidenciasindicadoresproyectos tblevidenciasindicadoresproyectos 
            ON tblobservacionesevidenciasip.idEvidenciaIndicadorProyectos = tblevidenciasindicadoresproyectos.idEvidenciaIndicadorProyectos 
            INNER JOIN tblindicadoresproyectos tblindicadoresproyectos 
            ON tblevidenciasindicadoresproyectos.idIndicadorProyecto = tblindicadoresproyectos .idIndicadorProyecto
            ",
            "orders" => $params["order"]["column"] . " " . $params["order"]["dir"],
            "where" => " (tblindicadoresproyectos.idProyectoProgramatico = " . $params["extrasPost"]["idProyectoProyectoProgramatico"] . "  ) "
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
        return $this->datatableGenerico($params, $param, $limit, $nombreTabla = $sql['tablas'], $sql['where'], "", "", true);
    }

    public function consultarSeguimientoProyectos($params) {
        $limit = array("max" => $params["limit"]["max"],
            "pag" => $params["limit"]["pag"]);
        $d = array("limit" => $limit);

        $sql = array(
            "campos" => " 
                tblproyectosprogramaticos.idProyectoProgramatico,
                tblproyectosprogramaticos.desProyectoProgramatico,
                tblpdeideales.reto,
                tblpdeideales.desPdeIdeal,
                tblpdeestrategias.desPdeEstrategia,
                tblpdelineasaccion.desPdeLineaAccion,
                tblestadosproyecto.desEstadoProyecto,
                tblproyectosprogramaticos.fechaInicio,
                tblproyectosprogramaticos.fechaTermino,
                tblproyectosprogramaticos.numEmpleadoResponsable,
                tblpdeideales.cvePdeIdeal,
                tblpdeestrategias.cvePdeEstrategia,
                tblpdelineasaccion.cvePdeLineaAccion,
                tblestadosproyecto.cveEstadoProyecto,
                tblproyectosprogramaticos.estrategiaProyecto,
                tblproyectosprogramaticos.metaProyecto 
            ",
            "tablas" => "
                tblproyectosprogramaticos tblproyectosprogramaticos 
                INNER JOIN tblestadosproyecto tblestadosproyecto 
                ON tblproyectosprogramaticos.cveEstadoProyecto = tblestadosproyecto.cveEstadoProyecto 
                INNER JOIN tblpdeideales tblpdeideales 
                ON tblproyectosprogramaticos.cvePdeIdeal = tblpdeideales.cvePdeIdeal 
                INNER JOIN tblpdeestrategias tblpdeestrategias 
                ON tblproyectosprogramaticos.cvePdeEstrategia = tblpdeestrategias.cvePdeEstrategia 
                INNER JOIN tblpdelineasaccion tblpdelineasaccion 
                ON tblproyectosprogramaticos.cvePdeLineaAccion = tblpdelineasaccion.cvePdeLineaAccion 
                INNER JOIN  tblproyectosadscripciones tblproyectosadscripciones 
                ON tblproyectosprogramaticos.idProyectoProgramatico = tblproyectosadscripciones.idProyectoProgramatico 
            ",
            "orders" => $params["order"]["column"] . " " . $params["order"]["dir"],
            "where" => "
                (tblproyectosprogramaticos.activo = 'S') AND
                (tblestadosproyecto.activo = 'S') AND
                (tblpdeideales.activo = 'S') AND
                (tblpdeestrategias.activo = 'S') AND
                (tblpdelineasaccion.activo = 'S') AND
                (tblproyectosprogramaticos.cveEstatusFinanzas = 29) AND
                (tblproyectosprogramaticos.cveEstatusPlaneacion = 23) AND
                (tblproyectosadscripciones.cveAdscripcion = " . $this->adscripcionPadreArray["cveAdscripcion"] . ")
            "
        );

        if (!is_null($params["extras"])) {
            foreach ($params["extras"] as $key => $value) {
                if ($key == "idProyectoEstrategico" && ($value != null && $value != "" && $value != "null")) {
                    $sql["where"] .= " AND tblproyectosestrategicos." . $key . "=" . $value . " ";
                } else {
                    if ($value != "" && $value != null) {
                        $sql["where"] .= " AND tblproyectosestrategicos." . $key . "=" . $value . " ";
                    }
                }
            }
        }
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
        return $this->datatableGenerico($params, $param, $limit, $nombreTabla = $sql['tablas'], $sql['where']);
    }

    function consultarSeguimientoProyectosAdministracion($params) {
        $limit = array("max" => $params["limit"]["max"],
            "pag" => $params["limit"]["pag"]);
        $d = array("limit" => $limit);

        $sql = array(
            "campos" => " 
                tblproyectosprogramaticos.idProyectoProgramatico,
                tblproyectosprogramaticos.desProyectoProgramatico,
                tblpdeideales.reto,
                tblpdeideales.desPdeIdeal,
                tblpdeestrategias.desPdeEstrategia,
                tblpdelineasaccion.desPdeLineaAccion,
                tblestadosproyecto.desEstadoProyecto,
                tblproyectosprogramaticos.fechaInicio,
                tblproyectosprogramaticos.fechaTermino,
                tblproyectosprogramaticos.numEmpleadoResponsable,
                tblproyectosprogramaticos.cvePdeIdeal,
                tblproyectosprogramaticos.cvePdeEstrategia,
                tblproyectosprogramaticos.cvePdeLineaAccion,
                tblproyectosprogramaticos.cveEstadoProyecto,
                tblproyectosprogramaticos.estrategiaProyecto,
                tblproyectosprogramaticos.metaProyecto 
            ",
            "tablas" => "
                tblproyectosprogramaticos tblproyectosprogramaticos 
                INNER JOIN tblestadosproyecto tblestadosproyecto 
                ON tblproyectosprogramaticos.cveEstadoProyecto = tblestadosproyecto.cveEstadoProyecto 
                INNER JOIN tblpdeestrategias tblpdeestrategias 
                ON tblproyectosprogramaticos.cvePdeEstrategia = tblpdeestrategias.cvePdeEstrategia 
                INNER JOIN tblpdeideales tblpdeideales 
                ON tblproyectosprogramaticos.cvePdeIdeal = tblpdeideales.cvePdeIdeal 
                INNER JOIN tblpdelineasaccion tblpdelineasaccion 
                ON tblproyectosprogramaticos.cvePdeLineaAccion = tblpdelineasaccion.cvePdeLineaAccion 
            ",
            "orders" => $params["order"]["column"] . " " . $params["order"]["dir"],
            "where" => "
                (tblproyectosprogramaticos.activo = 'S') AND
                (tblestadosproyecto.activo = 'S') AND
                (tblpdeideales.activo = 'S') AND
                (tblpdeestrategias.activo = 'S') AND
                (tblpdelineasaccion.activo = 'S') AND
                (tblproyectosprogramaticos.cveEstatusFinanzas = 29) AND
                (tblproyectosprogramaticos.cveEstatusPlaneacion = 23)
            "
        );
        if (array_key_exists("extras", $params)) {
            if ($params["extras"] != NULL && array_key_exists("cveAdscripcion", $params["extras"])) {
                $sql["where"] .= " AND (tblunidadesinvolucradas.cveAdscripcion = " . $params["extras"]["cveAdscripcion"] . ")";
            }
        }

        if (!is_null($params["extras"])) {
            foreach ($params["extras"] as $key => $value) {
                if ($key == "idProyectoEstrategico" && ($value != null && $value != "" && $value != "null")) {
                    $sql["where"] .= " AND tblproyectosestrategicos." . $key . "=" . $value . " ";
                } else {
                    if (array_key_exists("extras", $params)) {
                        if ($params["extras"] != NULL && array_key_exists("cveAdscripcion", $params["extras"])) {
                            
                        } else {
                            if ($value != "" && $value != null) {
                                $sql["where"] .= " AND tblproyectosestrategicos." . $key . "=" . $value . " ";
                            }
                        }
                    }
                }
            }
        }
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
        return $this->datatableGenerico($params, $param, $limit, $nombreTabla = $sql['tablas'], $sql['where']);
    }

    public function agregarNuevaObservacionEvidencia($params) {
        $genenericDAO = new GenericDAO();
        $controllerImagenes = new ImagenesController();
        $idProyectoProgramaticoGeneral = "";
        $proveedor = new Proveedor('mysql', 'ADMINISTRATIVO');
        $proveedor->connect();
        $proveedor->execute("BEGIN");
        $error = false;
        $respuesta = "";

        $updateEvidenciaAvance = array(
            "tabla" => "tblevidenciaacciones", "d" => array(
                "values" => array(
                    "avance" => $params["valorporcentajeavanceaccion"],
                    "fechaActualizacion" => "now()"
                ), "where" => array(
                    "cveEvidenciaAccion" => $params["cveEvidenciaAccion"]
                )
            ), "proveedor" => $proveedor
        );
        $updatEvidenciaAvanceRs = $genenericDAO->update($updateEvidenciaAvance);

        if ($updatEvidenciaAvanceRs["totalCount"] > 0) {
            $updateAccionProyecto = array(
                "tabla" => "tblaccionesproyecto", "d" => array(
                    "values" => array(
                        "AvanceAcumulado" => $this->actualizarAcumuladoAccionesProyecto($params["idAccionProyecto"], $proveedor),
                        "fechaActualizacion" => "now()"
                    ), "where" => array(
                        "idAccionProyecto" => $params["idAccionProyecto"]
                    )), "proveedor" => $proveedor, "accionBitacora" => 14
            );
            $selectAccionProyectoRs = $this->consultaAntesUpdate($updateAccionProyecto);
            $updatAccionProyectoRs = $genenericDAO->update($updateAccionProyecto);


            if ($updatAccionProyectoRs["totalCount"] > 0) {
//            $this->guardarBitacora(14, $updatAccionProyectoRs, $selectAccionProyectoRs, $proveedor);
                $this->guardarProyectoProgBitacora($idProyectoProgramaticoGeneral, 14, $proveedor);
                $idProyectoProgramaticoGeneral = $updatAccionProyectoRs["data"][0]["idProyectoProgramatico"];
//            var_dump($params);
                if (array_key_exists("idObservacionEvidencia", $params) && $params["idObservacionEvidencia"] != "" && $params["idObservacionEvidencia"] != null) {
                    //Modificar
//                var_dump("Modificar");
                    $updateObservacionEvidencia = array(
                        "tabla" => "tblobservacionesevidencias", "d" => array(
                            "values" => array(
                                "comentarios" => $params["comentarios"],
                                /*                                 * "observaciones" => str_ireplace("'", "\\'", ($params["observaciones"])),* */
                                "fechaActualizacion" => "now()"
                            ), "where" => array(
                                "idObservacionEvidencia" => $params["idObservacionEvidencia"]
                            )), "proveedor" => $proveedor
                    );
                    $selectObservacionEvidenciaRs = $this->consultaAntesUpdate($updateObservacionEvidencia);
                    $updatObservacionEvidenciaRs = $genenericDAO->update($updateObservacionEvidencia);
                    if ($updatObservacionEvidenciaRs["totalCount"] > 0) {
//                    var_dump($updatObservacionEvidenciaRs);
                        $updateDocumentosImg = array(
                            "tabla" => "tbldocumentosimg", "d" => array(
                                "values" => array(
                                    "descripcion" => str_ireplace("'", "\\'", ($params["observaciones"])),
                                    "numEmpleadoCarga" => $_SESSION["NumEmpleado"],
                                    "fechaActualizacion" => "now()"
                                ), "where" => array(
                                    "idReferencia" => $params["idObservacionEvidencia"],
                                    "cveTipoDocumento" => 8
                                )), "proveedor" => $proveedor, "accionBitacora" => 33
                        );
                        $updatDocumentosImgRs = $genenericDAO->update($updateDocumentosImg);
//                    var_dump($imagenes);
                        if ($updatDocumentosImgRs["totalCount"] > 0) {
//                        $this->guardarBitacora(33, $updatObservacionEvidenciaRs, $selectObservacionEvidenciaRs, $proveedor);
                            $this->guardarProyectoProgBitacora($idProyectoProgramaticoGeneral, 33, $proveedor);
                            $imagenesController = new ImagenesController();
                            $documentoImagenes = $imagenesController->consultarTiposDocumentos($updatDocumentosImgRs, $proveedor);
                            $error = $this->convertirEditorPDF($documentoImagenes["data"][0]["descripcion"], $documentoImagenes, true, $proveedor);
                            $respuesta = $updatObservacionEvidenciaRs;
                            $respuesta["acumulado"] = $this->getAcumuladoCantidad($params, $proveedor);
                        } else {
                            $respuesta = $updatDocumentosImgRs;
                            $error = true;
                        }
                    } else {
                        $error = true;
                        $respuesta = $updatObservacionEvidenciaRs;
                    }
                } else {
                    //Agregar 
//                var_dump("Agregar");
                    $guardarObservacionEvidencia = array(
                        "tabla" => "tblobservacionesevidencias", "d" => array(
                            "values" => array(
                                "comentarios" => $params["comentarios"],
                                "cveEvidenciaAccion" => $params["cveEvidenciaAccion"],
                                /*                                 * "observaciones" => str_ireplace("'", "\\'", ($params["observaciones"])),* */
                                "activo" => "S",
                                "fechaRegistro" => "now()",
                                "fechaActualizacion" => "now()",
                            )), "proveedor" => $proveedor, "accionBitacora" => 32
                    );
                    $guardarObservacionEvidenciaRs = $genenericDAO->insert($guardarObservacionEvidencia);

                    if ($guardarObservacionEvidenciaRs["totalCount"] > 0) {
                        $guardarDocumentosImg = array(
                            "tabla" => "tbldocumentosimg",
                            "d" => array(
                                "values" => array(
                                    "descripcion" => str_ireplace("'", "\\'", ($params["observaciones"])),
                                    "numEmpleadoCarga" => $_SESSION["NumEmpleado"],
                                    "cveTipoDocumento" => 8,
                                    "idReferencia" => $guardarObservacionEvidenciaRs["data"][0]["idObservacionEvidencia"],
                                    "activo" => "S",
                                    "fechaRegistro" => "now()",
                                    "fechaActualizacion" => "now()",
                                )
                            ),
                            "proveedor" => $proveedor
                        );
                        $imagenes = $controllerImagenes->crearDocumentoImg($guardarDocumentosImg);
//                    var_dump($imagenes);
                        if ($imagenes["totalCount"] > 0) {
//                        $this->guardarBitacora(32, $guardarObservacionEvidenciaRs, null, $proveedor);
                            $this->guardarProyectoProgBitacora($idProyectoProgramaticoGeneral, 32, $proveedor);
                            $error = $this->convertirEditorPDF($imagenes["data"][0]["descripcion"], $imagenes, false, $proveedor);
                            $respuesta = $guardarObservacionEvidenciaRs;
                            $respuesta["acumulado"] = $this->getAcumuladoCantidad($params, $proveedor);
                        } else {
                            $respuesta = $imagenes;
                            $error = true;
                        }
                    } else {
                        $error = true;
                        $respuesta = $guardarObservacionEvidenciaRs;
                    }
                }
            } else {
                $error = true;
                $respuesta = $updatAccionProyectoRs;
            }
        } else {
            $error = true;
            $respuesta = $updatEvidenciaAvanceRs;
        }

        if (!$error) {
            try {
                $ads = $this->getAdscripcionEvidenciaAccion($params["cveEvidenciaAccion"]);
//                var_dump($ads);
                $this->notificar(array(
                    "Origen" => $this->adscripcionPadreArray["cveAdscripcion"],
                    "Destino" => $ads,
                    "cveTipoNotificacion" => "1",
                    "tituloNotificacion" => utf8_decode("Agrego Observación a evidencia"),
                    "descripcionNotificacion" => $_SESSION["desAdscripcion"] . utf8_decode(" agrego una nueva observación a evidencia ..."),
                    "urlFormulario" => "vistas/planeacion/frmSeguimientoProyectosView.php",
                        ), $proveedor);
            } catch (Exception $e) {
//                echo $e;
            }
        }
        if (!$error) {
            $proveedor->execute("COMMIT");
        } else {
            $proveedor->execute("ROLLBACK");
        }
        $proveedor->close();
        $jsonEncode = new Encode_JSON();
        return $jsonEncode->encode($respuesta);
    }

    public function agregarNuevaObservacionEvidenciaIndicador($params) {
//        var_dump($params);
        $genenericDAO = new GenericDAO();
        $controllerImagenes = new ImagenesController();
        $idProyectoProgramaticoGeneral = "";
        $proveedor = new Proveedor('mysql', 'ADMINISTRATIVO');
        $proveedor->connect();
        $proveedor->execute("BEGIN");
        $error = false;
        $respuesta = "";
        $updateAccionProyecto = array(
            "tabla" => "tblindicadoresproyectos", "d" => array(
                "values" => array(
                    "ponderacionTrim" . $params["trimestre"] => $params["valorporcentajeavanceaccion"],
                    "fechaActualizacion" => "now()"
                ), "where" => array(
                    "idIndicadorProyecto" => $params["idIndicadorProyecto"]
                )), "proveedor" => $proveedor
        );
//        $selectAccionProyectoRs = $this->consultaAntesUpdate($updateAccionProyecto);
        $updatAccionProyectoRs = $genenericDAO->update($updateAccionProyecto);


        if ($updatAccionProyectoRs["totalCount"] > 0) {
//            $this->guardarBitacora(14, $updatAccionProyectoRs, $selectAccionProyectoRs, $proveedor);
//            $this->guardarProyectoProgBitacora($idProyectoProgramaticoGeneral, 14, $proveedor);
            $idProyectoProgramaticoGeneral = $updatAccionProyectoRs["data"][0]["idProyectoProgramatico"];
//            var_dump($params);
            if (array_key_exists("idObservacionEvidenciaIP", $params) && $params["idObservacionEvidenciaIP"] != "" && $params["idObservacionEvidenciaIP"] != null) {
                //Modificar
//                var_dump("Modificar");
                $updateObservacionEvidencia = array(
                    "tabla" => "tblobservacionesevidenciasip", "d" => array(
                        "values" => array(
                            "comentarios" => $params["comentarios"],
                            /*                             * "observaciones" => str_ireplace("'", "\\'", ($params["observaciones"])),* */
                            "fechaActualizacion" => "now()"
                        ), "where" => array(
                            "idObservacionEvidenciaIP" => $params["idObservacionEvidenciaIP"]
                        )), "proveedor" => $proveedor
                );
//                $selectObservacionEvidenciaRs = $this->consultaAntesUpdate($updateObservacionEvidencia);
                $updatObservacionEvidenciaRs = $genenericDAO->update($updateObservacionEvidencia);
//                var_dump($updatObservacionEvidenciaRs);
                if ($updatObservacionEvidenciaRs["totalCount"] > 0) {
//                    var_dump($updatObservacionEvidenciaRs);
                    $updateDocumentosImg = array(
                        "tabla" => "tbldocumentosimg", "d" => array(
                            "values" => array(
                                "descripcion" => str_ireplace("'", "\\'", ($params["observaciones"])),
                                "numEmpleadoCarga" => $_SESSION["NumEmpleado"],
                                "fechaActualizacion" => "now()"
                            ), "where" => array(
                                "idReferencia" => $params["idObservacionEvidenciaIP"],
                                "cveTipoDocumento" => 29
                            )), "proveedor" => $proveedor
                    );
                    $updatDocumentosImgRs = $genenericDAO->update($updateDocumentosImg);
//                    var_dump($imagenes);
                    if ($updatDocumentosImgRs["totalCount"] > 0) {
//                        $this->guardarBitacora(33, $updatObservacionEvidenciaRs, $selectObservacionEvidenciaRs, $proveedor);
//                        $this->guardarProyectoProgBitacora($idProyectoProgramaticoGeneral, 33, $proveedor);
                        $imagenesController = new ImagenesController();
                        $documentoImagenes = $imagenesController->consultarTiposDocumentos($updatDocumentosImgRs, $proveedor);
                        $error = $this->convertirEditorPDF($documentoImagenes["data"][0]["descripcion"], $documentoImagenes, true, $proveedor);
                        $respuesta = $updatObservacionEvidenciaRs;
//                        $respuesta["acumulado"] = $this->getAcumuladoCantidad($params, $proveedor);
                    } else {
                        $respuesta = $updatDocumentosImgRs;
                        $error = true;
                    }
                } else {
                    $error = true;
                    $respuesta = $updatObservacionEvidenciaRs;
                }
            } else {
                //Agregar 
//                var_dump("Agregar");
                $guardarObservacionEvidencia = array(
                    "tabla" => "tblobservacionesevidenciasip", "d" => array(
                        "values" => array(
                            "comentarios" => $params["comentarios"],
                            "idEvidenciaIndicadorProyectos" => $params["idEvidenciaIndicadorProyectos"],
                            /*                             * "observaciones" => str_ireplace("'", "\\'", ($params["observaciones"])),* */
                            "activo" => "S",
                            "fechaRegistro" => "now()",
                            "fechaActualizacion" => "now()",
                        )), "proveedor" => $proveedor
                );
                $guardarObservacionEvidenciaRs = $genenericDAO->insert($guardarObservacionEvidencia);

                if ($guardarObservacionEvidenciaRs["totalCount"] > 0) {
                    $guardarDocumentosImg = array(
                        "tabla" => "tbldocumentosimg",
                        "d" => array(
                            "values" => array(
                                "descripcion" => str_ireplace("'", "\\'", ($params["observaciones"])),
                                "numEmpleadoCarga" => $_SESSION["NumEmpleado"],
                                "cveTipoDocumento" => 29,
                                "idReferencia" => $guardarObservacionEvidenciaRs["data"][0]["idObservacionEvidenciaIP"],
                                "activo" => "S",
                                "fechaRegistro" => "now()",
                                "fechaActualizacion" => "now()",
                            )
                        ),
                        "proveedor" => $proveedor
                    );
                    $imagenes = $controllerImagenes->crearDocumentoImg($guardarDocumentosImg);
//                    var_dump($imagenes);
                    if ($imagenes["totalCount"] > 0) {
//                        $this->guardarBitacora(32, $guardarObservacionEvidenciaRs, null, $proveedor);
//                        $this->guardarProyectoProgBitacora($idProyectoProgramaticoGeneral, 32, $proveedor);
                        $error = $this->convertirEditorPDF($imagenes["data"][0]["descripcion"], $imagenes, false, $proveedor);
                        $respuesta = $guardarObservacionEvidenciaRs;
//                        $respuesta["acumulado"] = $this->getAcumuladoCantidad($params, $proveedor);
                    } else {
                        $respuesta = $imagenes;
                        $error = true;
                    }
                } else {
                    $error = true;
                    $respuesta = $guardarObservacionEvidenciaRs;
                }
            }
        } else {
            $error = true;
            $respuesta = $updatAccionProyectoRs;
        }
        if (!$error) {
            try {
//                $ads = $this->getAdscripcionEvidenciaAccion($params["cveEvidenciaAccion"]);
//                var_dump($ads);
                $this->notificar(array(
                    "Origen" => $this->adscripcionPadreArray["cveAdscripcion"],
                    "Destino" => 885,
                    "cveTipoNotificacion" => "1",
                    "tituloNotificacion" => utf8_decode("Agrego Observación a evidencia del indicador"),
                    "descripcionNotificacion" => $_SESSION["desAdscripcion"] . utf8_decode(" agrego una nueva observación a evidencia del indicador..."),
                    "urlFormulario" => "vistas/planeacion/frmSeguimientoProyectosView.php",
                        ), $proveedor);
            } catch (Exception $e) {
//                echo $e;
            }
        }
        if (!$error) {
            $proveedor->execute("COMMIT");
        } else {
            $proveedor->execute("ROLLBACK");
        }
        $proveedor->close();
        $jsonEncode = new Encode_JSON();
        return $jsonEncode->encode($respuesta);
    }

    public function getAcumuladoCantidad($param, $p = null) {
        $genericoDao = new GenericDAO();
        $d = array();
        $sql = array(
            "campos" => "
                tblaccionesproyecto.AvanceAcumulado,
                tblaccionprogramatica.cantidadAnual 
             ",
            "tablas" => " 
                tblaccionesproyecto tblaccionesproyecto 
                INNER JOIN tblaccionprogramatica tblaccionprogramatica 
                ON tblaccionesproyecto.idAccionProyecto = tblaccionprogramatica.idAccionProyecto 
                INNER JOIN tblevidenciaacciones tblevidenciaacciones 
                ON tblaccionesproyecto.idAccionProyecto = tblevidenciaacciones.idAccionProyecto 
                ",
            "where" => " 
                (tblevidenciaacciones.activo = 'S') AND
                (tblaccionesproyecto.activo = 'S') AND
                (tblaccionprogramatica.activo = 'S') AND
                (tblevidenciaacciones.cveEvidenciaAccion = " . $param["cveEvidenciaAccion"] . ") 
                "
        );
        $sqlSelect = array("tabla" => "", "d" => $d, "tmpSql" => $sql, "proveedor" => $p);
        $rs = $genericoDao->select($sqlSelect);
        if ($rs["totalCount"] > 0) {
            return $rs;
        } else {
            return 0;
        }
    }

    public function getAdscripcionEvidenciaAccion($cveEvidenciaAccion) {
        $genericoDao = new GenericDAO();
        $d = array();
        $sql = array(
            "campos" => "
                DISTINCT tblproyectosprogramaticos.idProyectoProgramatico,
                tblproyectosprogramaticos.cveAdscripcion 
             ",
            "tablas" => " 
                tblevidenciaacciones tblevidenciaacciones 
		INNER JOIN tblaccionesproyecto tblaccionesproyecto 
		ON tblevidenciaacciones.idAccionProyecto = tblaccionesproyecto.idAccionProyecto 
		INNER JOIN tblproyectosprogramaticos tblproyectosprogramaticos 
		ON tblaccionesproyecto.idProyectoProgramatico = tblproyectosprogramaticos.idProyectoProgramatico 
                ",
            "where" => " (tblevidenciaacciones.cveEvidenciaAccion = " . $cveEvidenciaAccion . ") "
        );
        $sqlSelect = array("tabla" => "", "d" => $d, "tmpSql" => $sql, "proveedor" => null);
        $rs = $genericoDao->select($sqlSelect);
        if ($rs["totalCount"] > 0) {
            return $rs["data"][0]["cveAdscripcion"];
        } else {
            return 0;
        }
//        $jsonEncode = new Encode_JSON();
//        return $jsonEncode->encode($rs);
    }

    public function consultarObservacionEvidencia($params) {
//        var_dump($params);
        $genericoDao = new GenericDAO();
        $d = array();
        $sql = array(
            "campos" => "
                tblobservacionesevidencias.*,
                tbldocumentosimg.descripcion                
             ",
            "tablas" => " 
                tblobservacionesevidencias tblobservacionesevidencias
                INNER JOIN tbldocumentosimg tbldocumentosimg
                ON tblobservacionesevidencias.idObservacionEvidencia = tbldocumentosimg.idReferencia
                ",
            "where" => " (tblobservacionesevidencias.idObservacionEvidencia = " . $params["idObservacionEvidencia"] . ") AND 
                tbldocumentosimg.cveTipoDocumento = 8
            "
        );
        $sqlSelect = array("tabla" => "", "d" => $d, "tmpSql" => $sql, "proveedor" => null);
        $rs = $genericoDao->select($sqlSelect);
        $jsonEncode = new Encode_JSON();
        return $jsonEncode->encode($rs);
    }

    public function consultarObservacionesAccion($params) {
        $genericoDao = new GenericDAO();
        $d = array();
        $sql = array(
            "campos" => "
                tblaccionesproyecto.idAccionProyecto,
                tblaccionesproyecto.desAccionProyecto,
                tblaccionesproyecto.AvanceAcumulado,
                tblevidenciaacciones.cveEvidenciaAccion,
                tblevidenciaacciones.desEvidenciaAccion,
                tbldocumentosimg.descripcion,
                tblobservacionesevidencias.idObservacionEvidencia,
                tblobservacionesevidencias.comentarios,
                tblaccionprogramatica.idAccionProgramatica,
                tblaccionprogramatica.cantidadAnual,
                tblunidadesmedida.cveUnidadMedida,
                tblunidadesmedida.desUnidadMedida 
             ",
            "tablas" => " 
                tblevidenciaacciones tblevidenciaacciones 
                LEFT JOIN tblobservacionesevidencias tblobservacionesevidencias 
                ON (tblevidenciaacciones.cveEvidenciaAccion = tblobservacionesevidencias.cveEvidenciaAccion )
                INNER JOIN tblaccionesproyecto tblaccionesproyecto 
                ON ( tblevidenciaacciones.idAccionProyecto = tblaccionesproyecto.idAccionProyecto )
                INNER JOIN tblaccionprogramatica tblaccionprogramatica 
                ON tblaccionprogramatica.idAccionProyecto = tblaccionesproyecto.idAccionProyecto
                INNER JOIN tblunidadesmedida tblunidadesmedida 
                ON tblunidadesmedida.cveUnidadMedida = tblaccionprogramatica.cveUnidadMedida
                LEFT JOIN tbldocumentosimg tbldocumentosimg 
                ON ( tblobservacionesevidencias.idObservacionEvidencia = tbldocumentosimg.idReferencia AND tbldocumentosimg.cveTipoDocumento = 8)
                
                ",
            "where" => " (tblevidenciaacciones.cveEvidenciaAccion = " . $params["cveEvidenciaAccion"] . ")"
        );
        $sqlSelect = array("tabla" => "", "d" => $d, "tmpSql" => $sql, "proveedor" => null);
        $rs = $genericoDao->select($sqlSelect);
        $jsonEncode = new Encode_JSON();
        return $jsonEncode->encode($rs);
    }

    public function consultarSeguimientoProyectosAcciones($params) {
        $limit = array("max" => $params["limit"]["max"],
            "pag" => $params["limit"]["pag"]);
        $d = array("limit" => $limit);
        $sql = array(
            "campos" => " 
            tblaccionesproyecto.idAccionProyecto,
            tblaccionesproyecto.nomenclatura,
            tblaccionesproyecto.desAccionProyecto,
            tblaccionesproyecto.numEmpleadoACargo,
            tblaccionesproyecto.fechaInicio,
            tblaccionesproyecto.fechaFin,
            tblaccionesproyecto.ponderacion,
            tblaccionesproyecto.AvanceAcumulado,
            tblaccionesproyecto.idProyectoProgramatico,
            tblaccionprogramatica.idAccionProgramatica,
            tblaccionprogramatica.cantidadAnual,
            tblunidadesmedida.cveUnidadMedida,
            tblunidadesmedida.desUnidadMedida
            ",
            "tablas" => " tblaccionesproyecto tblaccionesproyecto 
                INNER JOIN htsj_administrativo.tblaccionprogramatica tblaccionprogramatica 
                ON tblaccionesproyecto.idAccionProyecto = tblaccionprogramatica.idAccionProyecto 
                INNER JOIN htsj_administrativo.tblunidadesmedida tblunidadesmedida 
                ON tblaccionprogramatica.cveUnidadMedida = tblunidadesmedida.cveUnidadMedida  ",
            "orders" => $params["order"]["column"] . " " . $params["order"]["dir"],
            "where" => " (tblaccionesproyecto.activo = 'S') AND (tblaccionprogramatica.activo = 'S') AND (tblunidadesmedida.activo = 'S') AND tblaccionesproyecto.activo = 'S' AND tblaccionesproyecto.idProyectoProgramatico = " . $params["extrasPost"]["idProyectoProyectoProgramatico"] . "");

        if (!is_null($params["extras"])) {
            foreach ($params["extras"] as $key => $value) {
                $sql["where"] .= " AND tblproyectosprogramaticos." . $key . "=" . $value . " ";
            }
        }
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
        return $this->datatableGenerico($params, $param, $limit, $nombreTabla = $sql['tablas'], $sql['where']);
    }

    public function datatableConsultaEvidenciasIndicadores($params) {
        $limit = array("max" => $params["limit"]["max"],
            "pag" => $params["limit"]["pag"]);
        $d = array("limit" => $limit);
        $sql = array(
            "campos" => " 
                tblindicadoresproyectos.idIndicadorProyecto,
                tblindicadoresproyectos.idProyectoProgramatico,
                tblindicadoresproyectos.cveIndicadorAsociado,
                tblindicadoresproyectos.cantidadAnual,
                tblindicadoresproyectos.ponderacionTotal1,
                tblindicadoresproyectos.ponderacionTotal2,
                tblindicadoresproyectos.ponderacionTotal3,
                tblindicadoresproyectos.ponderacionTotal4,
                tblindicadoresproyectos.ponderacionTrim1,
                tblindicadoresproyectos.ponderacionTrim2,
                tblindicadoresproyectos.ponderacionTrim3,
                tblindicadoresproyectos.ponderacionTrim4,
                tblindicadoresproyectos.activo,
                tblindicadoresproyectos.fechaRegistro,
                tblindicadoresproyectos.fechaActualizacion,
                tblevidenciasindicadoresproyectos.idEvidenciaIndicadorProyectos,
                tblevidenciasindicadoresproyectos.desEvidencia,
                tblevidenciasindicadoresproyectos.trimestre
            ",
            "tablas" => "
                tblindicadoresproyectos tblindicadoresproyectos 
                INNER JOIN tblevidenciasindicadoresproyectos tblevidenciasindicadoresproyectos 
                ON tblindicadoresproyectos.idIndicadorProyecto = tblevidenciasindicadoresproyectos.idIndicadorProyecto 
            ",
            "orders" => $params["order"]["column"] . " " . $params["order"]["dir"],
            "where" => "
                (tblindicadoresproyectos.activo = 'S') AND
                (tblevidenciasindicadoresproyectos.activo = 'S') AND
                (tblindicadoresproyectos.idProyectoProgramatico = " . $params["extrasPost"]["idProyectoProgramatico"] . ")
            ");


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
        return $this->datatableGenerico($params, $param, $limit, $nombreTabla = $sql['tablas'], $sql['where']);
    }

    public function seguimientoProyectosProgramaticosAccionesEvidencias($param) {
        $genericoDao = new GenericDAO();
        $d = array();
        $sql = array(
            "campos" => " tblaccionesproyecto.*,
                          tblaccionprogramatica.idAccionProgramatica,
                          tblaccionprogramatica.cantidadAnual,
                          tblunidadesmedida.cveUnidadMedida,
                          tblunidadesmedida.desUnidadMedida  ",
            "tablas" => " tblaccionesproyecto tblaccionesproyecto 
                          INNER JOIN htsj_administrativo.tblaccionprogramatica tblaccionprogramatica 
                          ON tblaccionesproyecto.idAccionProyecto = tblaccionprogramatica.idAccionProyecto 
                          INNER JOIN htsj_administrativo.tblunidadesmedida tblunidadesmedida 
                          ON tblaccionprogramatica.cveUnidadMedida = tblunidadesmedida.cveUnidadMedida  ",
            "where" => " tblaccionesproyecto.activo = 'S' AND tblaccionesproyecto.idProyectoProgramatico = " . $param["idProyectoProgramatico"]
            . " AND (tblaccionprogramatica.activo = 'S') AND
                    (tblunidadesmedida.activo = 'S')"
        );
        $sqlSelect = array("tabla" => "", "d" => $d, "tmpSql" => $sql, "proveedor" => null);
        $rs = $genericoDao->select($sqlSelect);
        $arrayAccionesProyecto = array();
        $arrayAccionesProyectoReturn = array();
        if ($rs["status"] == "error" || $rs["totalCount"] == 0) {
            $arrayAccionesProyectoReturn = $rs;
        } else {
            $arrayAccionesProyectoReturn["status"] = $rs["status"];
            $arrayAccionesProyectoReturn["totalCount"] = $rs["totalCount"];
            foreach ($rs["data"] as $key => $value) {
                foreach ($value as $key2 => $value2) {
                    if ($key2 == "idAccionProyecto") {
                        $arrayAccionesProyecto[$key]["evidenciasacciones"] = $this->getEvidenciaPorAccionProyecto($value2);
                    }
                    if ($key2 == "numEmpleadoACargo") {
                        $nombreEmpleado = "";
                        $nombreEmpleadoRs = json_decode($this->getNombrePersonalCliente($value2));
                        $nombreEmpleado = $nombreEmpleadoRs->data[0]->TituloTrato . " " . $nombreEmpleadoRs->data[0]->Nombre . " " . $nombreEmpleadoRs->data[0]->Paterno . " " . $nombreEmpleadoRs->data[0]->Materno;
                        $arrayAccionesProyecto[$key]["nomEmpleadoACargo"] = $nombreEmpleado;
                    }
                    $arrayAccionesProyecto[$key][$key2] = $value2;
                }
            }
            $arrayAccionesProyectoReturn["data"] = $arrayAccionesProyecto;
        }
        $jsonEncode = new Encode_JSON();
        return $jsonEncode->encode($arrayAccionesProyectoReturn);
    }

    public function getDigestion($filePath) {
        $binDigest = sha1_file($filePath, true);
        $strDigest = base64_encode($binDigest);
        return $strDigest;
    }

    public function getEvidenciaPorAccionArchivos($cveEvidenciaAccion, $proveedor = null) {
        $genericoDao = new GenericDAO();
        $d = array();
        $sql = array(
            "campos" => " tblimagenes.*  ",
            "tablas" => " tblimagenes tblimagenes 
            INNER JOIN tbldocumentosimg tbldocumentosimg 
            ON tblimagenes.idDocumentoImg = tbldocumentosimg.idDocumentoImg  ",
            "where" => " (tblimagenes.activo = 'S') AND
            (tbldocumentosimg.activo = 'S') AND
            (tbldocumentosimg.cveTipoDocumento = 4) AND
            (tbldocumentosimg.idReferencia = " . $cveEvidenciaAccion . ") "
        );
        $sqlSelect = array("tabla" => "", "d" => $d, "tmpSql" => $sql, "proveedor" => $proveedor);
        $rs = $genericoDao->select($sqlSelect);
        return $rs;
    }

    public function getEvidenciaPorAccionArchivosIndicador($idEvidenciaIndicadorProyectos, $proveedor = null) {
        $genericoDao = new GenericDAO();
        $d = array();
        $sql = array(
            "campos" => " tblimagenes.*  ",
            "tablas" => " tblimagenes tblimagenes 
            INNER JOIN tbldocumentosimg tbldocumentosimg 
            ON tblimagenes.idDocumentoImg = tbldocumentosimg.idDocumentoImg  ",
            "where" => " (tblimagenes.activo = 'S') AND
            (tbldocumentosimg.activo = 'S') AND
            (tblimagenes.adjunto = 'S') AND
            (tbldocumentosimg.cveTipoDocumento = 25) AND
            (tbldocumentosimg.idReferencia = " . $idEvidenciaIndicadorProyectos . ") "
        );
        $sqlSelect = array("tabla" => "", "d" => $d, "tmpSql" => $sql, "proveedor" => $proveedor);
        $rs = $genericoDao->select($sqlSelect);
        return $rs;
    }

    public function eliminarEvidenciaIndicador($param) {
        $proveedor = new Proveedor('mysql', 'ADMINISTRATIVO');
        $genenericDAO = new GenericDAO();
        $proveedor->connect();
        $proveedor->execute("BEGIN");
        $error = false;
        $respuesta = "";
        if ($param["idEvidenciaIndicadorProyectos"] != null && $param["idEvidenciaIndicadorProyectos"] != "null" && $param["idEvidenciaIndicadorProyectos"] != "") {
            $updateEvidencia = array(
                "tabla" => "tblevidenciasindicadoresproyectos", "d" => array(
                    "values" => array(
                        "activo" => "N",
                        "fechaActualizacion" => "now()"
                    ), "where" => array(
                        "idEvidenciaIndicadorProyectos" => $param["idEvidenciaIndicadorProyectos"]
                    )
                ), "proveedor" => $proveedor
            );
//            $selectEvidenciaRs = $this->consultaAntesUpdate($updateEvidencia);
            $updatEvidenciaRs = $genenericDAO->update($updateEvidencia);
//            var_dump($updatEvidenciaRs);
            if ($updatEvidenciaRs["totalCount"] > 0) {
//                $this->guardarBitacora(8, $updatEvidenciaRs, $selectEvidenciaRs, $proveedor);
//                $this->guardarProyectoProgBitacora($this->getIdProyectoProgramaticoConEvidenciaAccion($param["cveEvidenciaAccion"], $proveedor), 8, $proveedor);
                $respuesta = $updatEvidenciaRs;
                $updateDocumentosEvidencia = array(
                    "tabla" => "tbldocumentosimg", "d" => array(
                        "values" => array(
                            "activo" => "N",
                            "fechaActualizacion" => "now()"
                        ), "where" => array(
                            "idReferencia" => $param["idEvidenciaIndicadorProyectos"],
                            "cveTipoDocumento" => 25,
                        )), "proveedor" => $proveedor
                );
//                $selectDocumentosEvidenciaRs = $this->consultaAntesUpdate($updateDocumentosEvidencia);
//                var_dump($selectDocumentosEvidenciaRs);
                $updatDocumentosEvidenciaRs = $genenericDAO->update($updateDocumentosEvidencia);
//                var_dump($updatDocumentosEvidenciaRs);
//                $this->guardarBitacora(10, $updatDocumentosEvidenciaRs, $selectDocumentosEvidenciaRs, $proveedor);
//                $this->guardarProyectoProgBitacora($this->getIdProyectoProgramaticoConEvidenciaAccion($param["cveEvidenciaAccion"], $proveedor), 10, $proveedor);
                if ($updatDocumentosEvidenciaRs["totalCount"] > 0) {
                    $updateImagenes = array(
                        "tabla" => "tblimagenes", "d" => array(
                            "values" => array(
                                "activo" => "N",
                                "fechaActualizacion" => "now()"
                            ), "where" => array(
                                "idDocumentoImg" => $updatDocumentosEvidenciaRs["data"][0]["idDocumentoImg"],
                            )), "proveedor" => $proveedor
                    );
                    $updatImagenesRs = $genenericDAO->update($updateImagenes);
                    $respuesta = $updatDocumentosEvidenciaRs;
                } else {
                    $error = true;
                    $respuesta = $updatDocumentosEvidenciaRs;
                }
            } else {
                $error = true;
                $respuesta = $updatEvidenciaRs;
            }
            if (!$error) {
                $proveedor->execute("COMMIT");
            } else {
                $proveedor->execute("ROLLBACK");
            }
            $proveedor->close();
            $jsonEncode = new Encode_JSON();
            return $jsonEncode->encode($respuesta);
        }
    }

    public function eliminarEvidencia($param) {
        $proveedor = new Proveedor('mysql', 'ADMINISTRATIVO');
        $genenericDAO = new GenericDAO();
        $proveedor->connect();
        $proveedor->execute("BEGIN");
        $error = false;
        $respuesta = "";
        if ($param["cveEvidenciaAccion"] != null && $param["cveEvidenciaAccion"] != "null" && $param["cveEvidenciaAccion"] != "") {
            $updateEvidencia = array(
                "tabla" => "tblevidenciaacciones", "d" => array(
                    "values" => array(
                        "activo" => "N",
                        "fechaActualizacion" => "now()"
                    ), "where" => array(
                        "cveEvidenciaAccion" => $param["cveEvidenciaAccion"]
                    )
                ), "proveedor" => $proveedor, "accionBitacora" => 8
            );
            $selectEvidenciaRs = $this->consultaAntesUpdate($updateEvidencia);
            $updatEvidenciaRs = $genenericDAO->update($updateEvidencia);
//            var_dump($updatEvidenciaRs);
            if ($updatEvidenciaRs["totalCount"] > 0) {
                $updateAvance = array(
                    "tabla" => "tblaccionesproyecto", "d" => array(
                        "values" => array(
                            "AvanceAcumulado" => $this->actualizarAcumuladoAccionesProyecto($updatEvidenciaRs["data"][0]["idAccionProyecto"], $proveedor),
                            "fechaActualizacion" => "now()"
                        ), "where" => array(
                            "idAccionProyecto" => $updatEvidenciaRs["data"][0]["idAccionProyecto"]
                        )
                    ), "proveedor" => $proveedor
                );
                $updatAvanceRs = $genenericDAO->update($updateAvance);
                if ($updatAvanceRs["totalCount"] > 0) {
                    //                $this->guardarBitacora(8, $updatEvidenciaRs, $selectEvidenciaRs, $proveedor);
                    $this->guardarProyectoProgBitacora($this->getIdProyectoProgramaticoConEvidenciaAccion($param["cveEvidenciaAccion"], $proveedor), 8, $proveedor);
                    $respuesta = $updatEvidenciaRs;
                    $updateDocumentosEvidencia = array(
                        "tabla" => "tbldocumentosimg", "d" => array(
                            "values" => array(
                                "activo" => "N",
                                "fechaActualizacion" => "now()"
                            ), "where" => array(
                                "idReferencia" => $param["cveEvidenciaAccion"],
                                "cveTipoDocumento" => 4,
                            )), "proveedor" => $proveedor, "accionBitacora" => 10
                    );
                    $selectDocumentosEvidenciaRs = $this->consultaAntesUpdate($updateDocumentosEvidencia);
//                var_dump($selectDocumentosEvidenciaRs);
                    $updatDocumentosEvidenciaRs = $genenericDAO->update($updateDocumentosEvidencia);
//                var_dump($updatDocumentosEvidenciaRs);
//                $this->guardarBitacora(10, $updatDocumentosEvidenciaRs, $selectDocumentosEvidenciaRs, $proveedor);
                    $this->guardarProyectoProgBitacora($this->getIdProyectoProgramaticoConEvidenciaAccion($param["cveEvidenciaAccion"], $proveedor), 10, $proveedor);
                    if ($updatDocumentosEvidenciaRs["totalCount"] > 0) {
                        $updateImagenes = array(
                            "tabla" => "tblimagenes", "d" => array(
                                "values" => array(
                                    "activo" => "N",
                                    "fechaActualizacion" => "now()"
                                ), "where" => array(
                                    "idDocumentoImg" => $updatDocumentosEvidenciaRs["data"][0]["idDocumentoImg"],
                                )), "proveedor" => $proveedor
                        );
                        $updatImagenesRs = $genenericDAO->update($updateImagenes);
                        $respuesta = $updatDocumentosEvidenciaRs;
                    } else {
                        $error = true;
                        $respuesta = $updatDocumentosEvidenciaRs;
                    }
                } else {
                    $error = true;
                    $respuesta = $updatAvanceRs;
                }
            } else {
                $error = true;
                $respuesta = $updatEvidenciaRs;
            }
            if (!$error) {
                $proveedor->execute("COMMIT");
            } else {
                $proveedor->execute("ROLLBACK");
            }
            $proveedor->close();
            $jsonEncode = new Encode_JSON();
            return $jsonEncode->encode($respuesta);
        }
    }

    public function guardarConocimiento($param) {
        $proveedor = new Proveedor('mysql', 'ADMINISTRATIVO');
        $proveedor->connect();
        $proveedor->execute("BEGIN");
        $error = false;
        $respuesta = "";
        $genenericDAO = new GenericDAO();
        $updateProyecto = array(
            "tabla" => "tblproyectosprogramaticos",
            "d" => array(
                "values" => array(
                    "conocimiento" => $param["conocimiento"],
                    "fechaActualizacion" => "now()"
                ),
                "where" => array(
                    "idProyectoProgramatico" => $param["idProyectoProgramatico"]
                )
            ),
            "proveedor" => $proveedor, "accionBitacora" => 13
        );
        $selectProyectoRs = $this->consultaAntesUpdate($updateProyecto);
        $updateProyectoRs = $genenericDAO->update($updateProyecto);
        if ($updateProyectoRs["totalCount"] > 0) {
//            $this->guardarBitacora(13, $updateProyectoRs, $selectProyectoRs, $proveedor);
            $this->guardarProyectoProgBitacora($param["idProyectoProgramatico"], 13, $proveedor);
            $respuesta = $updateProyectoRs;
        } else {
            $error = true;
            $respuesta = $updateProyectoRs;
        }
        if (!$error) {
            $proveedor->execute("COMMIT");
        } else {
            $proveedor->execute("ROLLBACK");
        }
        $proveedor->close();
        $jsonEncode = new Encode_JSON();
        return $jsonEncode->encode($respuesta);
    }

    public function consultarObservacionEvidenciaIndicador($param) {
        $d = array();
        $genericoDao = new GenericDAO();

        $sql = array(
            "campos" => "
                tblobservacionesevidenciasip.*,
                tbldocumentosimg.descripcion 
            ",
            "tablas" => "
                tblobservacionesevidenciasip tblobservacionesevidenciasip 
                INNER JOIN tbldocumentosimg tbldocumentosimg 
                ON tblobservacionesevidenciasip.idObservacionEvidenciaIP = tbldocumentosimg.idReferencia 
            ",
            "where" => "
                (tblobservacionesevidenciasip.activo = 'S') AND
                (tbldocumentosimg.activo = 'S') AND
                (tbldocumentosimg.activo = 'S') AND
                (tbldocumentosimg.cveTipoDocumento = 29) AND
                (tblobservacionesevidenciasip.idObservacionEvidenciaIP = " . $param["extrasPost"]["idObservacionEvidenciaIP"] . ")
            "
        );

        $sqlSelect = array("tabla" => "", "d" => $d, "tmpSql" => $sql, "proveedor" => null);
        $rs = $genericoDao->select($sqlSelect);

        $jsonEncode = new Encode_JSON();
        return $jsonEncode->encode($rs);
    }

    public function guardarVariablesProyecto($param) {
        $proveedor = new Proveedor('mysql', 'ADMINISTRATIVO');
        $proveedor->connect();
        $proveedor->execute("BEGIN");
        $error = false;
        $respuesta = "";
        $genenericDAO = new GenericDAO();
        $updateProyecto = array(
            "tabla" => "tblindicadoresvariables",
            "d" => array(
                "values" => array(
                    "ponderacionTrim1" => "",
                    "ponderacionTrim2" => "",
                    "ponderacionTrim3" => "",
                    "ponderacionTrim4" => "",
                    "fechaActualizacion" => "now()"
                ),
                "where" => array(
                )
            ),
            "proveedor" => $proveedor
        );
        foreach ($param["extrasPost"]["listadoVariablesPonderacion"] as $key => $value) {
            $updateProyecto["d"]["where"]["idIndicadorVariable"] = $value["idIndicadorVariable"];
            if ($value["trimestre"] == "1") {
                $updateProyecto["d"]["values"]["ponderacionTrim1"] = $value["ponderacion"];
            }
            if ($value["trimestre"] == "2") {
                $updateProyecto["d"]["values"]["ponderacionTrim2"] = $value["ponderacion"];
            }
            if ($value["trimestre"] == "3") {
                $updateProyecto["d"]["values"]["ponderacionTrim3"] = $value["ponderacion"];
            }
            if ($value["trimestre"] == "4") {

                $updateProyecto["d"]["values"]["ponderacionTrim4"] = $value["ponderacion"];

                $updateProyectoRs = $genenericDAO->update($updateProyecto);
                if ($updateProyectoRs["totalCount"] > 0) {
                    $respuesta = $updateProyectoRs;
                } else {
                    $error = true;
                    $respuesta = $updateProyectoRs;
                }
            }

//            $updateProyecto = array(
//                "tabla" => "tblindicadoresvariables",
//                "d" => array(
//                    "values" => array(
//                        "ponderacionTrim" . $value["trimestre"] => $value["ponderacion"],
//                        "fechaActualizacion" => "now()"
//                    ),
//                    "where" => array(
//                        "idIndicadorVariable" => $value["idIndicadorVariable"]
//                    )
//                ),
//                "proveedor" => $proveedor
//            );
//            $updateProyectoRs = $genenericDAO->update($updateProyecto);
////            var_dump($updateProyectoRs);
//            if ($updateProyectoRs["totalCount"] > 0) {
//                $respuesta = $updateProyectoRs;
//            } else {
//                $error = true;
//                $respuesta = $updateProyectoRs;
//            }
        }
        if (!$error) {
            $proveedor->execute("COMMIT");
        } else {
            $proveedor->execute("ROLLBACK");
        }
        $proveedor->close();
        $jsonEncode = new Encode_JSON();
        if (!$error) {
            return $jsonEncode->encode($respuesta);
        } else {
            return $jsonEncode->encode(array(
                        "status" => "error",
                        "totalCount" => 0,
                        "mnj" => "Error al guardar el comportamiento de las variables"
            ));
        }
    }

    public function modificarEvidencia($param) {
        $proveedor = new Proveedor('mysql', 'ADMINISTRATIVO');
        $proveedor->connect();
        $proveedor->execute("BEGIN");
        $error = false;
        $respuesta = "";
        $d = array();
        $genenericDAO = new GenericDAO();
        $updateEvidencia = array(
            "tabla" => "tblevidenciaacciones", "d" => array(
                "values" => array(
                    "fechaActualizacion" => "now()",
                    "desEvidenciaAccion" => $param["desEvidenciaAccion"],
                /*                 * "observaciones" => str_ireplace("'", "\\'", ($param["observaciones"]))* */
                ), "where" => array(
                    "cveEvidenciaAccion" => $param["cveEvidenciaAccion"]
                )), "proveedor" => $proveedor
        );
        $selectEvidenciaRs = $this->consultaAntesUpdate($updateEvidencia);
        $updateEvidenciaRs = $genenericDAO->update($updateEvidencia);
        if ($updateEvidenciaRs["totalCount"] > 0) {
            $updateDocumentosImg = array(
                "tabla" => "tbldocumentosimg", "d" => array(
                    "values" => array(
                        "descripcion" => str_ireplace("'", "\\'", ($param["observaciones"])),
                        "fechaActualizacion" => "now()",
                    ), "where" => array(
                        "idReferencia" => $param["cveEvidenciaAccion"],
                        "cveTipoDocumento" => 4
                    )), "proveedor" => $proveedor, "accionBitacora" => 11
            );
            $updateDocumentosImgRs = $genenericDAO->update($updateDocumentosImg);
            if ($updateDocumentosImgRs["totalCount"] > 0) {
                $imagenesController = new ImagenesController();
//                $this->guardarBitacora(11, $updateEvidenciaRs, $selectEvidenciaRs, $proveedor);
                $this->guardarProyectoProgBitacora($this->getIdProyectoProgramaticoConEvidenciaAccion($param["cveEvidenciaAccion"], $proveedor), 11, $proveedor);
                $documentoImagenes = $imagenesController->consultarTiposDocumentos($updateDocumentosImgRs, $proveedor);
                $error = $this->convertirEditorPDF($documentoImagenes["data"][0]["descripcion"], $documentoImagenes, true, $proveedor);
                $respuesta = $updateEvidenciaRs;
            } else {
                
            }
        } else {
            $error = true;
            $respuesta = $updateEvidenciaRs;
        }
        if (!$error) {
            $proveedor->execute("COMMIT");
        } else {
            $proveedor->execute("ROLLBACK");
        }
        $proveedor->close();
        $jsonEncode = new Encode_JSON();
        return $jsonEncode->encode($respuesta);
    }

    public function modificarEvidenciaIndicador($param) {
        $proveedor = new Proveedor('mysql', 'ADMINISTRATIVO');
        $proveedor->connect();
        $proveedor->execute("BEGIN");
        $error = false;
        $respuesta = "";
        $d = array();
        $genenericDAO = new GenericDAO();
        $updateEvidencia = array(
            "tabla" => "tblevidenciasindicadoresproyectos", "d" => array(
                "values" => array(
                    "fechaActualizacion" => "now()",
                    "desEvidencia" => $param["desEvidencia"],
                ), "where" => array(
                    "idEvidenciaIndicadorProyectos" => $param["idEvidenciaIndicadorProyectos"]
                )), "proveedor" => $proveedor
        );
//        $selectEvidenciaRs = $this->consultaAntesUpdate($updateEvidencia);
        $updateEvidenciaRs = $genenericDAO->update($updateEvidencia);
        if ($updateEvidenciaRs["totalCount"] > 0) {
            $updateDocumentosImg = array(
                "tabla" => "tbldocumentosimg", "d" => array(
                    "values" => array(
                        "descripcion" => str_ireplace("'", "\\'", ($param["observaciones"])),
                        "fechaActualizacion" => "now()",
                    ), "where" => array(
                        "idReferencia" => $param["idEvidenciaIndicadorProyectos"],
                        "cveTipoDocumento" => 25
                    )), "proveedor" => $proveedor
            );
            $updateDocumentosImgRs = $genenericDAO->update($updateDocumentosImg);
            if ($updateDocumentosImgRs["totalCount"] > 0) {
                $imagenesController = new ImagenesController();
//                $this->guardarBitacora(11, $updateEvidenciaRs, $selectEvidenciaRs, $proveedor);
//                $this->guardarProyectoProgBitacora($this->getIdProyectoProgramaticoConEvidenciaAccion($param["cveEvidenciaAccion"], $proveedor), 11, $proveedor);
                $documentoImagenes = $imagenesController->consultarTiposDocumentos($updateDocumentosImgRs, $proveedor);
                $error = $this->convertirEditorPDF($documentoImagenes["data"][0]["descripcion"], $documentoImagenes, true, $proveedor);
                $respuesta = $updateEvidenciaRs;
            } else {
                
            }
        } else {
            $error = true;
            $respuesta = $updateEvidenciaRs;
        }
        if (!$error) {
            $proveedor->execute("COMMIT");
        } else {
            $proveedor->execute("ROLLBACK");
        }
        $proveedor->close();
        $jsonEncode = new Encode_JSON();
        return $jsonEncode->encode($respuesta);
    }

    public function guardarArchivoEnDirecto($param, $paramFiles) {
        $controllerImagenes = new ImagenesController();
        $d = array();
        $genericoDao = new GenericDAO();
        $respuesta = null;
        $sql = array(
            "campos" => "
                tbldocumentosImg.*, tbltiposdocumentos.descTipoDocumento, tbltiposdocumentos.extension
            ", "tablas" => "
                tbldocumentosImg tbldocumentosImg INNER JOIN tbltiposdocumentos tbltiposdocumentos ON(tbltiposdocumentos.cveTipoDocumento = tbldocumentosImg.cveTipoDocumento)
            ", "where" => "
                (tbldocumentosImg.activo = 'S') AND
                (tbldocumentosImg.idReferencia = " . $param['cveEvidenciaAccion'] . ") AND 
                (tbldocumentosImg.cveTipoDocumento = " . $param['cveTipoDocumento'] . ")    
            "
        );
        $sqlSelect = array("tabla" => "", "d" => $d, "tmpSql" => $sql, "proveedor" => null);
        $consultaEvidenciaRs = $genericoDao->select($sqlSelect);
        if ($consultaEvidenciaRs["totalCount"] > 0) {
//            $archivoCreado = $this->guardarArchivoServidor($param, $paramFiles, $consultaEvidenciaRs, null);
//            $imagenes = $controllerImagenes->crearDocumentoImg($guardarDocumentosImg);

            if ($consultaEvidenciaRs["totalCount"] > 0) {
                $imagenesDoc = $controllerImagenes->crearImagenes($paramFiles, $consultaEvidenciaRs, null);
                if (!$imagenesDoc) {
                    $respuesta = $consultaEvidenciaRs;
                } else {
                    $error = true;
                }
            } else {
                $error = true;
            }
//            $respuesta = $archivoCreado;
        } else {
            $respuesta = $consultaEvidenciaRs;
        }
        $jsonEncode = new Encode_JSON();
        return $jsonEncode->encode($respuesta);
    }

    public function eliminarDocumentoEvidencia($param) {
        $proveedor = new Proveedor('mysql', 'ADMINISTRATIVO');
        $proveedor->connect();
        $proveedor->execute("BEGIN");
        $error = false;
        $respuesta = "";
        $d = array();
        $genenericDAO = new GenericDAO();
        $updateDocumentos = array(
            "tabla" => "tbldocumentacionevidencias", "d" => array(
                "values" => array(
                    "activo" => "N",
                    "fechaActualizacion" => "now()"
                ), "where" => array(
                    "idDocumentacionEvidencia" => $param["idDocumentacionEvidencia"]
                )), "proveedor" => $proveedor, "accionBitacora" => 10
        );
        $selectDocumentosRs = $this->consultaAntesUpdate($updateDocumentos);
        $updateDocumentosRs = $genenericDAO->update($updateDocumentos);
        if ($updateDocumentosRs["totalCount"] > 0) {
//            $this->guardarBitacora(10, $updateDocumentosRs, $selectDocumentosRs, $proveedor);
            $this->guardarProyectoProgBitacora($this->getIdProyectoProgramaticoConEvidenciaAccion($updateDocumentosRs["data"][0]["cveEvidenciaAccion"], $proveedor), 10, $proveedor);
        } else {
            $error = true;
            $respuesta = $updateProyectoRs;
        }
        if (!$error) {
            $proveedor->execute("COMMIT");
        } else {
            $proveedor->execute("ROLLBACK");
        }
        $proveedor->close();
        $jsonEncode = new Encode_JSON();
        return $jsonEncode->encode($respuesta);
    }

    public function consultarEvidenciaPorAccionArchivos($param, $regresar = false, $proveedor = null) {
        $d = array();
        $genericoDao = new GenericDAO();

        $sql = array(
            "campos" => "
                tblaccionesproyecto.idAccionProyecto,
                tblaccionesproyecto.AvanceAcumulado,
                tblevidenciaacciones.cveEvidenciaAccion,
                tblevidenciaacciones.desEvidenciaAccion,
                tblevidenciaacciones.fechaRegistro,
                tbldocumentosimg.descripcion,
                tblaccionprogramatica.idAccionProgramatica,
                tblaccionprogramatica.cantidadAnual,
                tblunidadesmedida.cveUnidadMedida,
                tblunidadesmedida.desUnidadMedida 
            ",
            "tablas" => "
                tblevidenciaacciones tblevidenciaacciones 
                INNER JOIN tblaccionesproyecto tblaccionesproyecto 
                ON tblevidenciaacciones.idAccionProyecto = tblaccionesproyecto.idAccionProyecto 
                LEFT JOIN tbldocumentosimg tbldocumentosimg 
                ON tblevidenciaacciones.cveEvidenciaAccion = tbldocumentosimg.idReferencia 
                INNER JOIN tblaccionprogramatica tblaccionprogramatica 
                ON tblaccionesproyecto.idAccionProyecto = tblaccionprogramatica.idAccionProyecto 
                INNER JOIN tblunidadesmedida tblunidadesmedida 
                ON tblaccionprogramatica.cveUnidadMedida = tblunidadesmedida.cveUnidadMedida 
            ",
            "where" => "
                (tblevidenciaacciones.activo = 'S') AND
                (tblevidenciaacciones.cveEvidenciaAccion = " . $param['cveEvidenciaAccion'] . ") AND
                (tblevidenciaacciones.activo = 'S') AND
                (tblaccionesproyecto.activo = 'S') AND
                (tbldocumentosimg.activo = 'S') AND
                (tblaccionprogramatica.activo = 'S') AND
                (tblunidadesmedida.activo = 'S')
            "
        );

        $sqlSelect = array("tabla" => "", "d" => $d, "tmpSql" => $sql, "proveedor" => $proveedor);
        $rs = $genericoDao->select($sqlSelect);
        $evidenciasArchivo = array();
        $evidenciasArchivoRs = array();
        if ($rs["totalCount"] > 0) {
            foreach ($rs["data"] as $key => $value) {
                foreach ($value as $key2 => $value2) {
                    $evidenciasArchivo[$key][$key2] = $value2;
                    if ($key2 == "cveEvidenciaAccion") {
                        $evidenciasArchivo[$key]["documentos"] = $this->getEvidenciaPorAccionArchivos($value2, $proveedor);
                    }
                }
            }
            $evidenciasArchivoRs["totalCount"] = $rs["totalCount"];
            $evidenciasArchivoRs["status"] = $rs["status"];
            $evidenciasArchivoRs["data"] = $evidenciasArchivo;
            $respuesta = $evidenciasArchivoRs;
        } else {
            $respuesta = $rs;
        }
        if ($regresar) {
            $jsonEncode = new Encode_JSON();
            return ($respuesta);
        } else {
            $jsonEncode = new Encode_JSON();
            return $jsonEncode->encode($respuesta);
        }
    }

    public function consultarEvidenciaPorAccionArchivosIndicador($param, $regresar = false, $proveedor = null) {
        $d = array();
        $genericoDao = new GenericDAO();

        $sql = array(
            "campos" => "
                tblindicadoresproyectos.idIndicadorProyecto,
                tblindicadoresproyectos.idProyectoProgramatico,
                tblindicadoresproyectos.cveIndicadorAsociado,
                tblindicadoresproyectos.cantidadAnual,
                tblindicadoresproyectos.ponderacionTotal1,
                tblindicadoresproyectos.ponderacionTotal2,
                tblindicadoresproyectos.ponderacionTotal3,
                tblindicadoresproyectos.ponderacionTotal4,
                tblindicadoresproyectos.ponderacionTrim1,
                tblindicadoresproyectos.ponderacionTrim2,
                tblindicadoresproyectos.ponderacionTrim3,
                tblindicadoresproyectos.ponderacionTrim4,
                tblindicadoresproyectos.activo,
                tblindicadoresproyectos.fechaRegistro,
                tblindicadoresproyectos.fechaActualizacion,
                tblevidenciasindicadoresproyectos.idEvidenciaIndicadorProyectos,
                tblevidenciasindicadoresproyectos.desEvidencia,
                tblevidenciasindicadoresproyectos.trimestre,
                tbldocumentosimg.idDocumentoImg,
                tbldocumentosimg.cveTipoDocumento,
                tbldocumentosimg.descripcion
            ",
            "tablas" => "
                tblindicadoresproyectos tblindicadoresproyectos 
                INNER JOIN tblevidenciasindicadoresproyectos tblevidenciasindicadoresproyectos 
                ON tblindicadoresproyectos.idIndicadorProyecto = tblevidenciasindicadoresproyectos.idIndicadorProyecto 
                LEFT JOIN tbldocumentosimg tbldocumentosimg 
                ON tblevidenciasindicadoresproyectos.idEvidenciaIndicadorProyectos = tbldocumentosimg.idReferencia 
                AND tblevidenciasindicadoresproyectos.activo = 'S' AND tbldocumentosimg.cveTipoDocumento = 25
            ",
            "where" => "
                (tblindicadoresproyectos.activo = 'S') AND
                (tblevidenciasindicadoresproyectos.activo = 'S') AND
                (tblevidenciasindicadoresproyectos.idEvidenciaIndicadorProyectos = " . $param["idEvidenciaIndicadorProyectos"] . ")
            "
        );

        $sqlSelect = array("tabla" => "", "d" => $d, "tmpSql" => $sql, "proveedor" => $proveedor);
        $rs = $genericoDao->select($sqlSelect);
        $evidenciasArchivo = array();
        $evidenciasArchivoRs = array();
        if ($rs["totalCount"] > 0) {
            foreach ($rs["data"] as $key => $value) {
                foreach ($value as $key2 => $value2) {
                    $evidenciasArchivo[$key][$key2] = $value2;
                    if ($key2 == "idEvidenciaIndicadorProyectos") {
                        $evidenciasArchivo[$key]["documentos"] = $this->getEvidenciaPorAccionArchivosIndicador($value2, $proveedor);
                    }
                }
            }
            $evidenciasArchivoRs["totalCount"] = $rs["totalCount"];
            $evidenciasArchivoRs["status"] = $rs["status"];
            $evidenciasArchivoRs["data"] = $evidenciasArchivo;
            $respuesta = $evidenciasArchivoRs;
        } else {
            $respuesta = $rs;
        }
        if ($regresar) {
            $jsonEncode = new Encode_JSON();
            return ($respuesta);
        } else {
            $jsonEncode = new Encode_JSON();
            return $jsonEncode->encode($respuesta);
        }
    }

    public function consultarConocimientoProyecto($param) {
        $d = array();
        $genericoDao = new GenericDAO();

        $sql = array(
            "campos" => "
                tblproyectosprogramaticos.conocimiento
            ",
            "tablas" => "
                tblproyectosprogramaticos tblproyectosprogramaticos
            ",
            "where" => "
                (tblproyectosprogramaticos.activo = 'S') AND                
                (tblproyectosprogramaticos.idProyectoProgramatico = " . $param['idProyectoProgramatico'] . ") 
            "
        );

        $sqlSelect = array("tabla" => "", "d" => $d, "tmpSql" => $sql, "proveedor" => null);
        $rs = $genericoDao->select($sqlSelect);

        $jsonEncode = new Encode_JSON();
        return $jsonEncode->encode($rs);
    }

    public function diagramaGantt($param) {
        $d = array();
        $genericoDao = new GenericDAO();
        $sql = array(
            "campos" => "
                tblproyectosprogramaticos.idProyectoProgramatico,
                tblproyectosprogramaticos.desProyectoProgramatico,
                tblproyectosprogramaticos.Objetivo,
                tblproyectosprogramaticos.estrategiaProyecto,
                tblproyectosprogramaticos.metaProyecto,
                tblproyectosprogramaticos.fechaInicio,
                tblproyectosprogramaticos.fechaTermino,
                tblaccionesproyecto.idAccionProyecto,
                tblaccionesproyecto.nomenclatura,
                tblaccionesproyecto.desAccionProyecto,
                tblaccionesproyecto.ponderacion,
                tblaccionesproyecto.fechaInicio,
                tblaccionesproyecto.fechaFin
            ",
            "tablas" => "
                tblproyectosprogramaticos tblproyectosprogramaticos 
                INNER JOIN tblaccionesproyecto tblaccionesproyecto 
                ON tblproyectosprogramaticos.idProyectoProgramatico = tblaccionesproyecto.idProyectoProgramatico  
            ",
            "where" => "
                (tblproyectosprogramaticos.activo = 'S') AND
                (tblaccionesproyecto.activo = 'S') AND
                (tblproyectosprogramaticos.idProyectoProgramatico = " . $param['idProyectoProgramatico'] . ") 
            "
        );

        $sqlSelect = array("tabla" => "", "d" => $d, "tmpSql" => $sql, "proveedor" => null);

        $rs = $genericoDao->select($sqlSelect);

        $arrayData = array();
        $arrayDataNorm = array();
        $arrayDataNormRs = array();
        $arrayDataRs = array();
        if ($rs["totalCount"] > 0) {
            $cont = 0;
            foreach ($rs["data"] as $key => $value) {
                foreach ($value as $key2 => $value2) {
                    if ($key2 == "desProyectoProgramatico") {
                        $arrayData[$key]["name"] = $value2;
                        if ($cont == 0) {
                            $arrayData[$key]["name"] = $value2;
                            $cont = 1;
                        } else {
                            $arrayData[$key]["name"] = "";
                        }
                    }
                    if ($key2 == "desAccionProyecto") {
                        $arrayData[$key]["desc"] = $value2;
                    }
                    if ($key2 == "fechaInicio") {
                        $fechaInico = date("Y/m/d", strtotime($value2));
                        $arrayData[$key]["values"][0]["from"] = "/Date(" . ((strtotime($fechaInico) - (3600 * 24)) * 1000) . ")/";
                    }
                    if ($key2 == "fechaFin") {
                        $fechaFin = date("Y/m/d", strtotime($value2));
                        $arrayData[$key]["values"][0]["to"] = "/Date(" . ((strtotime($fechaFin) - (3600 * 24) ) * 1000) . ")/";
                    }
                    if ($key2 == "ponderacion") {
                        $arrayData[$key]["values"][0]["label"] = $value2;
                    }
                }
            }

            $arrayDataNormRs["totalCount"] = $rs["totalCount"];
            $arrayDataNormRs["status"] = $rs["status"];
            foreach ($rs["data"] as $key => $value) {
                foreach ($value as $key2 => $value2) {
                    $arrayDataNorm[$key][$key2] = $value2;
                }
            }
            $arrayDataNormRs["data"] = $arrayDataNorm;
            $arrayDataNormRs["dataGantt"] = $arrayData;
        } else {
            $jsonEncode = new Encode_JSON();
            return $jsonEncode->encode($rs);
        }
        $jsonEncode = new Encode_JSON();
        return $jsonEncode->encode($arrayDataNormRs);
    }

    public function getProyectosEstrategicosAdscripcion($param) {
        $d = array();
        $genericoDao = new GenericDAO();

        $sql = array(
            "campos" => " tblproyectosestrategicos.* ",
            "tablas" => " 
                tblproyectosestrategicos tblproyectosestrategicos 
		INNER JOIN tblunidadesinvolucradas tblunidadesinvolucradas 
		ON tblproyectosestrategicos.idProyectoEstrategico = tblunidadesinvolucradas.idProyectoEstrategico
            ",
            "where" => "
                (tblunidadesinvolucradas.cveAdscripcion = " . $this->adscripcionPadreArray["cveAdscripcion"] . ") AND
                (tblunidadesinvolucradas.activo = 'S') AND
                (tblproyectosestrategicos.activo = 'S')
            "
        );

        if (array_key_exists("cvePdeIdeal", $param) && $param['cvePdeIdeal'] != "") {
            $sql["where"] .= " AND  (tblproyectosestrategicos.cvePdeIdeal = " . $param['cvePdeIdeal'] . ") ";
        }
        if (array_key_exists("cvePdeEstrategia", $param) && $param['cvePdeEstrategia']) {
            $sql["where"] .= " AND  (tblproyectosestrategicos.cvePdeEstrategia = " . $param['cvePdeEstrategia'] . ") ";
        }
        if (array_key_exists("cvePdeLineaAccion", $param) && $param['cvePdeLineaAccion']) {
            $sql["where"] .= " AND  (tblproyectosestrategicos.cvePdeLineaAccion = " . $param['cvePdeLineaAccion'] . ") ";
        }
        $sqlSelect = array("tabla" => "", "d" => $d, "tmpSql" => $sql, "proveedor" => null);
        $rs = $genericoDao->select($sqlSelect);
        $jsonEncode = new Encode_JSON();
        return $jsonEncode->encode($rs);
    }

    public function agregarNuevaEvidenciaIndicador($param, $paramFiles) {
        $proveedor = new Proveedor('mysql', 'ADMINISTRATIVO');
        $proveedor->connect();
        $proveedor->execute("BEGIN");
        $controllerImagenes = new ImagenesController();

        $error = false;
        $respuesta = "";
        $genenericDAO = new GenericDAO();
        $guardarEvidencia = array(
            "tabla" => "tblevidenciasindicadoresproyectos",
            "d" => array(
                "values" => array(
                    "desEvidencia" => $param["desEvidencia"],
                    "idIndicadorProyecto" => $param["idIndicadorProyecto"],
                    "trimestre" => $param["trimestre"],
                    "activo" => "S",
                    "fechaRegistro" => "now()",
                    "fechaActualizacion" => "now()",
                )
            ),
            "proveedor" => $proveedor
        );
        $guardarEvidenciaRs = $genenericDAO->insert($guardarEvidencia);
        if ($guardarEvidenciaRs["totalCount"] > 0) {
//            $this->guardarBitacora(7, $guardarEvidenciaRs, null, $proveedor); 
//            $this->guardarProyectoProgBitacora($this->getIdProyectoProgramaticoConAccionProyecto($param["idAccionProyecto"], $proveedor), 7, $proveedor);

            $updateAccionProyecto = array(
                "tabla" => "tblindicadoresproyectos", "d" => array(
                    "values" => array(
                        "ponderacionTrim" . $param["trimestre"] => $param["valorporcentajeavanceaccion"],
                        "fechaActualizacion" => "now()"
                    ), "where" => array(
                        "idIndicadorProyecto" => $param["idIndicadorProyecto"]
                    )), "proveedor" => $proveedor
            );
//            $selectAccionProyectoRs = $this->consultaAntesUpdate($updateAccionProyecto);
            $updateAccionProyectoRs = $genenericDAO->update($updateAccionProyecto);
//            $this->guardarBitacora(14, $updateAccionProyectoRs, $selectAccionProyectoRs, $proveedor);
//            $this->guardarProyectoProgBitacora($this->getIdProyectoProgramaticoConAccionProyecto($param["idAccionProyecto"], $proveedor), 14, $proveedor);

            if ($updateAccionProyectoRs["totalCount"] > 0) {
                $guardarDocumentosImg = array(
                    "tabla" => "tbldocumentosimg",
                    "d" => array(
                        "values" => array(
                            "descripcion" => str_ireplace("'", "\\'", ($param["observaciones"])),
                            "numEmpleadoCarga" => $_SESSION["NumEmpleado"],
                            "cveTipoDocumento" => 25,
                            "idReferencia" => $guardarEvidenciaRs["data"][0]["idEvidenciaIndicadorProyectos"],
                            "activo" => "S",
                            "fechaRegistro" => "now()",
                            "fechaActualizacion" => "now()",
                        )
                    ),
                    "proveedor" => $proveedor
                );
                $imagenes = $controllerImagenes->crearDocumentoImg($guardarDocumentosImg);
                if ($paramFiles != null) {
                    if ($imagenes["totalCount"] > 0) {
                        $imagenesDoc = $controllerImagenes->crearImagenes($paramFiles, $imagenes, $proveedor);
                        if (!$imagenesDoc) {
                            $respuesta = $guardarEvidenciaRs;
                        } else {
                            $error = true;
                        }
                    }
                } else {
                    $respuesta = $guardarEvidenciaRs;
                }
                if (!$error) {
                    $error = $this->convertirEditorPDF($imagenes["data"][0]["descripcion"], $imagenes, false, $proveedor);
                }
            } else {
                $respuesta = $updateAccionProyectoRs;
                $error = true;
            }
        } else {
            $respuesta = $guardarEvidenciaRs;
            $error = true;
        }
        //NOTIFICA
        if (!$error) {
            try {
                $this->notificar(array(
                    "Origen" => $this->getAdscripcionPadre($_SESSION["cveAdscripcion"])["cveAdscripcion"],
                    "Destino" => 853,
                    "cveTipoNotificacion" => "1",
                    "tituloNotificacion" => utf8_decode("Agrego Evidencia a Indicador"),
                    "descripcionNotificacion" => $_SESSION["desAdscripcion"] . utf8_decode(" ágrego una nueva evidencia al indicador del proyecto programatico ..."),
                    "urlFormulario" => "vistas/planeacion/frmAdministracionProyectosView.php",
                        ), $proveedor);
            } catch (Exception $e) {
                
            }
//            $respuesta = $this->consultarEvidenciaPorAccionArchivos(array("cveEvidenciaAccion" => $guardarEvidenciaRs["data"][0]["cveEvidenciaAccion"]), true, $proveedor);
        }
//        $error = true;
        if (!$error) {
            $proveedor->execute("COMMIT");
        } else {
            $proveedor->execute("ROLLBACK");
        }
        $proveedor->close();
        $jsonEncode = new Encode_JSON();
        return $jsonEncode->encode($respuesta);
    }

    public function actualizarAcumuladoAccionesProyecto($idAccionProyecto, $p = null) {
        $genenericDAO = new GenericDAO();
        $d = array();
        $sql = array(
            "campos" => "
                SUM(tblevidenciaacciones.avance) AS totalAvance 
            ",
            "tablas" => "
                tblevidenciaacciones tblevidenciaacciones 
            ",
            "where" => "
                (tblevidenciaacciones.activo = 'S') AND
                (tblevidenciaacciones.idAccionProyecto = " . $idAccionProyecto . ")
            "
        );
        $sqlSelect = array("tabla" => "", "d" => $d, "tmpSql" => $sql, "proveedor" => $p);
        $rs = $genenericDAO->select($sqlSelect);
        if ($rs["totalCount"] > 0) {
            return $rs["data"][0]["totalAvance"];
        } else {
            return 0;
        }
    }

    public function agregarEvidenciaAcciones($param, $paramFiles) {
//        var_dump($paramFiles);
        $proveedor = new Proveedor('mysql', 'ADMINISTRATIVO');
        $proveedor->connect();
        $proveedor->execute("BEGIN");
        $controllerImagenes = new ImagenesController();

        $error = false;
        $respuesta = "";
        $genenericDAO = new GenericDAO();
        $guardarEvidencia = array(
            "tabla" => "tblevidenciaacciones",
            "d" => array(
                "values" => array(
                    "desEvidenciaAccion" => $param["desEvidencia"],
                    "idAccionProyecto" => $param["idAccionProyecto"],
                    "avance" => $param["valorporcentajeavanceaccion"],
                    /*                     * "observaciones" => str_ireplace("'", "\\'", ($param["observaciones"])),* */
                    "activo" => "S",
                    "fechaRegistro" => "now()",
                    "fechaActualizacion" => "now()",
                )
            ),
            "proveedor" => $proveedor, "accionBitacora" => 7
        );
        $guardarEvidenciaRs = $genenericDAO->insert($guardarEvidencia);
//        var_dump($guardarEvidenciaRs);
        if ($guardarEvidenciaRs["totalCount"] > 0) {
//            $this->guardarBitacora(7, $guardarEvidenciaRs, null, $proveedor);
            $this->guardarProyectoProgBitacora($this->getIdProyectoProgramaticoConAccionProyecto($param["idAccionProyecto"], $proveedor), 7, $proveedor);

            $updateAccionProyecto = array(
                "tabla" => "tblaccionesproyecto", "d" => array(
                    "values" => array(
                        "AvanceAcumulado" => $this->actualizarAcumuladoAccionesProyecto($param["idAccionProyecto"], $proveedor),
                        "fechaActualizacion" => "now()"
                    ), "where" => array(
                        "idAccionProyecto" => $param["idAccionProyecto"]
                    )), "proveedor" => $proveedor, "accionBitacora" => 14
            );
            $selectAccionProyectoRs = $this->consultaAntesUpdate($updateAccionProyecto);
            $updateAccionProyectoRs = $genenericDAO->update($updateAccionProyecto);
//            $this->guardarBitacora(14, $updateAccionProyectoRs, $selectAccionProyectoRs, $proveedor);
            $this->guardarProyectoProgBitacora($this->getIdProyectoProgramaticoConAccionProyecto($param["idAccionProyecto"], $proveedor), 14, $proveedor);

            if ($updateAccionProyectoRs["totalCount"] > 0) {
                $guardarDocumentosImg = array(
                    "tabla" => "tbldocumentosimg",
                    "d" => array(
                        "values" => array(
                            "descripcion" => str_ireplace("'", "\\'", ($param["observaciones"])),
                            "numEmpleadoCarga" => $_SESSION["NumEmpleado"],
                            "cveTipoDocumento" => 4,
                            "idReferencia" => $guardarEvidenciaRs["data"][0]["cveEvidenciaAccion"],
                            "activo" => "S",
                            "fechaRegistro" => "now()",
                            "fechaActualizacion" => "now()",
                        )
                    ),
                    "proveedor" => $proveedor
                );
                $imagenes = $controllerImagenes->crearDocumentoImg($guardarDocumentosImg);
//                    var_dump("******");
//                    var_dump($imagenes);
//                    var_dump("HOLA");
//                    var_dump($imagenes["totalCount"]);
                if ($paramFiles != null) {
                    if ($imagenes["totalCount"] > 0) {
//                        var_dump($paramFiles);
                        $imagenesDoc = $controllerImagenes->crearImagenes($paramFiles, $imagenes, $proveedor);
//                        var_dump($imagenesDoc);
                        if (!$imagenesDoc) {
                            $respuesta = $guardarEvidenciaRs;
//                            $respuesta = $this->consultarEvidenciaPorAccionArchivos(array("cveEvidenciaAccion" => $guardarEvidenciaRs["data"][0]["cveEvidenciaAccion"]));
                        } else {
                            $error = true;
                        }
//                        var_dump($imagenesDoc); 
                    }
//                    $archivoCreado = $this->guardarArchivoServidor($param, $paramFiles, $guardarEvidenciaRs, $proveedor);
//                    if ($archivoCreado["totalCount"] > 0) {
//                        $respuesta = $archivoCreado;
//                    } else {
//                        $error = true;
//                    }
                } else {
                    $respuesta = $guardarEvidenciaRs;
                }
                if (!$error) {
                    $error = $this->convertirEditorPDF($imagenes["data"][0]["descripcion"], $imagenes, false, $proveedor);
                }
            } else {
                $respuesta = $updateAccionProyectoRs;
                $error = true;
            }
        } else {
            $respuesta = $guardarEvidenciaRs;
            $error = true;
        }
        //NOTIFICA
        if (!$error) {
            try {
                $this->notificar(array(
                    "Origen" => $this->getAdscripcionPadre($_SESSION["cveAdscripcion"])["cveAdscripcion"],
                    "Destino" => 853,
                    "cveTipoNotificacion" => "1",
                    "tituloNotificacion" => utf8_decode("Agrego Evidencia"),
                    "descripcionNotificacion" => $_SESSION["desAdscripcion"] . utf8_decode(" ágrego una nueva evidencia al proyecto programatico ..."),
                    "urlFormulario" => "vistas/planeacion/frmAdministracionProyectosView.php",
                        ), $proveedor);
            } catch (Exception $e) {
                
            }
            $respuesta = $this->consultarEvidenciaPorAccionArchivos(array("cveEvidenciaAccion" => $guardarEvidenciaRs["data"][0]["cveEvidenciaAccion"]), true, $proveedor);
        }

        if (!$error) {
            $proveedor->execute("COMMIT");
        } else {
            $proveedor->execute("ROLLBACK");
        }
        $proveedor->close();
        $jsonEncode = new Encode_JSON();
        return $jsonEncode->encode($respuesta);
    }

    public function convertirEditorPDF($html, $documentoImagenes, $u = false, $p = null) {
        $genenericDAO = new GenericDAO();
        $imagenesController = new ImagenesController();
        if ($u) {
            $update = array(
                "tabla" => "tblimagenes", "d" => array(
                    "values" => array(
                        "activo" => "N",
                        "fechaActualizacion" => "now()"
                    ), "where" => array(
                        "idDocumentoImg" => $documentoImagenes["data"][0]["idDocumentoImg"],
                        "adjunto" => "N"
                    )
                ), "proveedor" => $p
            );
            $updatRs = $genenericDAO->update($update);
            if ($updatRs["totalCount"] > 0) {
                
            } else {
                return true;
            }
        }
        return $imagenesController->crearImagenesByHTML(null, $html, $documentoImagenes, $p);
    }

    public function notificar($notificacion, $p) {
//        var_dump($notificacion);
        $genenericDAO = new GenericDAO();
        $updateNotificacion = array(
            "tabla" => "tblnotificacionesgenerales", "d" => array(
                "values" => array(
                    "Origen" => $notificacion["Origen"],
                    "Destino" => $notificacion["Destino"],
                    "cveTipoNotificacion" => $notificacion["cveTipoNotificacion"],
                    "descripcionNotificacion" => (($notificacion["descripcionNotificacion"])),
                    "tituloNotificacion" => (($notificacion["tituloNotificacion"])),
                    "urlFormulario" => $notificacion["urlFormulario"],
                    "activo" => "S",
                    "fechaRegistro" => "now()",
                    "fechaActualizacion" => "now()",
                )), "proveedor" => $p
        );
        $updateNotificacionRs = $genenericDAO->insert($updateNotificacion);
        //var_dump($updateNotificacionRs);
        if ($updateNotificacionRs["totalCount"] > 0) {
            $json = new Encode_JSON();
            $updateNotificacionRs["data"][0]["descripcionNotificacion"] = utf8_encode($updateNotificacionRs["data"][0]["descripcionNotificacion"]);
            $updateNotificacionRs["data"][0]["tituloNotificacion"] = utf8_encode($updateNotificacionRs["data"][0]["tituloNotificacion"]);
            $NotificacionesAdministrativo = new NotificacionesAdministrativo();
            $NotificacionesAdministrativo->setCanal("AdministrativoNotificaciones");
            $NotificacionesAdministrativo->setConfiguracionVista($updateNotificacionRs);
            if ($notificacion["cveTipoNotificacion"] == "1") {
                $arrayRemitente = array(
                    "remitente" => $notificacion["Destino"]
                    , "tipo" => "Adscripcion"
                );
            } elseif ($notificacion["cveTipoNotificacion"] == "2") {
                $arrayRemitente = array(
                    "remitente" => $notificacion["Destino"]
                    , "tipo" => "Grupo"
                );
            } elseif ($notificacion["cveTipoNotificacion"] == "3") {
                $arrayRemitente = array(
                    "remitente" => $notificacion["Destino"]
                    , "tipo" => "Perfil"
                );
            } elseif ($notificacion["cveTipoNotificacion"] == "4") {
                $arrayRemitente = array(
                    "remitente" => $notificacion["Destino"]
                    , "tipo" => "Sistema"
                );
            } elseif ($notificacion["cveTipoNotificacion"] == "5") {
                $arrayRemitente = array(
                    "remitente" => $notificacion["Destino"]
                    , "tipo" => "UsuarioSistema"
                );
            }


            $NotificacionesAdministrativo->setConfiguracionRemitente($arrayRemitente);
            $NotificacionesAdministrativo->emite();
        }
    }

    public function guardarArchivoServidor($param, $paramFiles, $e, $p) {

        $genenericDAO = new GenericDAO();
        $documentos = new Host(dirname(__FILE__) . '/../../tribunal/host/config.xml', 'DOCUMENTOS');
        $documentos = $documentos->getConnect();
        $path = dirname(__FILE__) . $documentos->DOCUMENTOSEVIDENCIAS;
        $errro = false;
        $respuesta = "";
        if (!file_exists($path)) {
            mkdir($path);
        }
        if (!file_exists($path . "/evidencias")) {
            mkdir($path . "/evidencias");
        }
        if (!file_exists($path . "/evidencias/AccionProyecto_" . $e["data"][0]["idAccionProyecto"])) {
            mkdir($path . "/evidencias/AccionProyecto_" . $e["data"][0]["idAccionProyecto"]);
        }


        $nombreCarpeta = $path . "/evidencias/AccionProyecto_" . $e["data"][0]["idAccionProyecto"] . "/";
        $nombreCarpetaG = "/evidencias/AccionProyecto_" . $e["data"][0]["idAccionProyecto"] . "/";
        $e["data"][0]["documentos"] = array();
        foreach ($paramFiles as $key => $file) {
            $nombreArchivo = pathinfo($file["name"], PATHINFO_FILENAME) . "_AccionProyecto_" . $e["data"][0]["idAccionProyecto"] . "_" . "EvidenciaAccion_" . $e["data"][0]["cveEvidenciaAccion"] . "_" . $key . "_" . "." . pathinfo($file["name"], PATHINFO_EXTENSION);
            if (move_uploaded_file($file["tmp_name"], $nombreCarpeta . basename($nombreArchivo))) {
                $updateEvidencia = array(
                    "tabla" => "tbldocumentacionevidencias", "d" => array(
                        "values" => array(
                            "rutaArchivo" => $nombreCarpetaG . $nombreArchivo,
                            "fechaActualizacion" => "now()",
                            "fechaRegistro" => "now()",
                            "activo" => "S",
                            "nombreArchivo" => $file["name"],
                            "cveEvidenciaAccion" => $e["data"][0]["cveEvidenciaAccion"]
                        )), "proveedor" => $p, "accionBitacora" => 9
                );
                $updateEvidenciaRs = $genenericDAO->insert($updateEvidencia);
                if ($updateEvidenciaRs["totalCount"] > 0) {
//                    $this->guardarBitacora(9, $updateEvidenciaRs, null, $p);
                    $this->guardarProyectoProgBitacora($this->getIdProyectoProgramaticoConEvidenciaAccion($updateEvidenciaRs["data"][0]["cveEvidenciaAccion"], $p), 9, $p);
                    array_push($e["data"][0]["documentos"], $updateEvidenciaRs["data"]);
                    $respuesta = $e;
                    $respuesta = $e;
                } else {
                    $respuesta = $updateEvidenciaRs;
                    $error = true;
                }
            } else {
                $errro = true;
            }
        }
        return $respuesta;
    }

    public function getEvidenciaPorAccionProyecto($idaccionproyecto) {

        $d = array();
        $genericoDao = new GenericDAO();

        $sql = array(
            "campos" => " * ",
            "tablas" => " 
                tblevidenciaacciones tblevidenciaacciones
            ",
            "where" => " 
                tblevidenciaacciones.activo = 'S' AND tblevidenciaacciones.idAccionProyecto = " . $idaccionproyecto
        );
        $sqlSelect = array("tabla" => "", "d" => $d, "tmpSql" => $sql, "proveedor" => null);

        $arrayEvidenciaPorAccionProyecto = array();
        $arrayEvidenciaPorAccionProyectoReturn = array();
        $rs = $genericoDao->select($sqlSelect);
        if ($rs["status"] == "error" || $rs["totalCount"] == 0) {
            $arrayEvidenciaPorAccionProyectoReturn = $rs;
        } else {
            $arrayEvidenciaPorAccionProyectoReturn["status"] = $rs["status"];
            $arrayEvidenciaPorAccionProyectoReturn["totalCount"] = $rs["totalCount"];
            foreach ($rs["data"] as $key => $value) {
                foreach ($value as $key2 => $value2) {
                    $arrayEvidenciaPorAccionProyecto[$key][$key2] = $value2;
                    if ($key2 == "cveEvidenciaAccion") {
                        $arrayEvidenciaPorAccionProyecto[$key]["rutasArchivos"] = $this->getEvidenciaPorAccionArchivos($value2);
                    }
                }
            }
            $arrayEvidenciaPorAccionProyectoReturn["data"] = $arrayEvidenciaPorAccionProyecto;
        }
        return $arrayEvidenciaPorAccionProyectoReturn;
    }

    public function consultaAntesUpdate($update) {
        $genenericDAO = new GenericDAO();
        $d = array();
        $selectAccionProyecto = array(
            "campos" => " * ",
            "tablas" => $update["tabla"],
            "where" => ""
        );
        $countArray = count($update["d"]["where"]);
        $cont = 0;
        foreach ($update["d"]["where"] as $key => $value) {
            if ($cont == 0) {
                $selectAccionProyecto["where"] .= $key . " = " . $value;
            } else {
                $selectAccionProyecto["where"] .= " AND " . $key . " = " . $value;
            }
            $cont++;
        }
        $sqlSelect = array("tabla" => "", "d" => $d, "tmpSql" => $selectAccionProyecto, "proveedor" => null);
        $rs = $genenericDAO->select($sqlSelect);

        return $rs;
    }

    function fechaNormal($fecha, $hora = false) {
        $arrFecha = explode(" ", $fecha);
        list($year, $mes, $dia) = explode("-", $arrFecha[0]);
        if ($hora)
            return $dia . "/" . $mes . "/" . $year . " " . $arrFecha[1];
        else
            return $dia . "/" . $mes . "/" . $year . " ";
    }

    function validateDate($date, $format = 'Y-m-d H:i:s') {
        $d = DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) == $date;
    }

    public function datatableGenerico($params, $param, $limit, $nombreTabla, $condiciones = "", $agrupacion = "", $orders = "", $extras = false) {
        $cuadro = new CuadroComparativoController();
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
                    if ($key == "numEmpleadoResponsable" || $key == "numEmpleadoACargo") {
                        $nombreEmpleadoRs = json_decode($this->getNombrePersonalCliente($value));
                        if (intval($nombreEmpleadoRs->totalCount) > 0) {
                            $nombreEmpleado = $nombreEmpleadoRs->data[0]->TituloTrato . " " . $nombreEmpleadoRs->data[0]->Nombre . " " . $nombreEmpleadoRs->data[0]->Paterno . " " . $nombreEmpleadoRs->data[0]->Materno;
                            $registro[] = $nombreEmpleado;
                        } else {
                            
                        }
                    }
                    if ($key == "cveAdscripcion") {
                        $registro[] = $cuadro->getAdscripcionNombre($value);
                    } else {
                        if ($this->validateDate($value) || $this->validateDate($value, 'Y-m-d')) {
                            if ($extras)
                                $registro[] = $this->fechaNormal($value, true);
                            else
                                $registro[] = $this->fechaNormal($value);
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

    public function guardarProyectoProgBitacora($idProyectoProgramatico, $cveAccion, $p = null) {
        $genericoDao = new GenericDAO();
        $proyectoProgBitacora = array(
            "tabla" => "tblproyectosprogbitacoras", "d" => array(
                "values" => array(
                    "activo" => "S",
                    "fechaRegistro" => "now()",
                    "fechaActualizacion" => "now()",
                    "idProyectoProgramatico" => $idProyectoProgramatico,
                    "cveAccion" => $cveAccion,
                    "nombreEmpleado" => @$_SESSION["Nombre"],
                )), "proveedor" => $p
        );
        $guardarProyectoProgBitacora = $genericoDao->insert($proyectoProgBitacora);
        return $guardarProyectoProgBitacora;
    }

    public function guardarBitacora($accion, $nuevo, $anterior = null, $proveedor = null) {
        $bitacoraController = new BitacoraController();
        $bitacora = array();
        $bitacora["cveAccion"] = $accion;
        if ($anterior != null) {
            $bitacora["observacionPrevia"] = array(
                "anterior" => $anterior,
                "nuevo" => $nuevo
            );
        } else {
            $bitacora["observacion"] = $nuevo;
        }
        $bitacora["proveedor"] = $proveedor;
        $bitaRs = $bitacoraController->bitacora($bitacora);
        return $bitaRs;
    }

    public function getNombrePersonalCliente($numEmpleado) {
        $personal = new PersonalCliente();
        $rsPersonal = $personal->getNumEmpleado($numEmpleado);
        return ($rsPersonal);
    }

    public function getIdProyectoProgramaticoConAccionProyecto($idAccionProyecto, $p) {
        $d = array();
        $genericoDao = new GenericDAO();

        $sql = array(
            "campos" => "
                tblaccionesproyecto.idProyectoProgramatico                
            ",
            "tablas" => "
                tblaccionesproyecto tblaccionesproyecto 		
            ",
            "where" => "
                (tblaccionesproyecto.idAccionProyecto = " . $idAccionProyecto . ") 
            "
        );
        $sqlSelect = array("tabla" => "", "d" => $d, "tmpSql" => $sql, "proveedor" => $p);
        $rs = $genericoDao->select($sqlSelect);
        return $rs["data"][0]["idProyectoProgramatico"];
    }

    public function getIdProyectoProgramaticoConEvidenciaAccion($cveEvidenciaAccion, $p) {
        $d = array();
        $genericoDao = new GenericDAO();
//        var_dump($cveEvidenciaAccion);
        $sql = array(
            "campos" => "
                *              
            ",
            "tablas" => "
                tblevidenciaacciones tblevidenciaacciones 
                INNER JOIN tblaccionesproyecto tblaccionesproyecto 
                ON tblevidenciaacciones.idAccionProyecto = tblaccionesproyecto.idAccionProyecto		
            ",
            "where" => "
                (tblevidenciaacciones.cveEvidenciaAccion = " . $cveEvidenciaAccion . ") 
            "
        );
        $sqlSelect = array("tabla" => "", "d" => $d, "tmpSql" => $sql, "proveedor" => $p);
//        var_dump($sqlSelect);
        $rs = $genericoDao->select($sqlSelect);
//        var_dump($rs);
        return $rs["data"][0]["idProyectoProgramatico"];
    }

    public function consultarDatosIndicadorVariablesPorProyectoProgramatico($param, $p = null) {
        $respuesta = "";
        $datosIndicador = $this->getIndicadorAsociadoIdProyectoProgramatico($param["idProyectoProgramatico"], $p);
        if ($datosIndicador["totalCount"] > 0) {
            $datosIndicador["indicadorProyecto"] = $this->getIndicadorProyectoIdProyectoProgramatico($param["idProyectoProgramatico"], $p);
            if ($datosIndicador["indicadorProyecto"]["totalCount"] > 0) {
                $datosIndicador["evidenciasIndicador"] = $this->getEvidenciasIndicadoresProyectos($datosIndicador["indicadorProyecto"]["data"][0]["idIndicadorProyecto"], $p);
                $datosIndicador["variablesIndicador"] = $this->getValiablesIndicadoresProyectoIdProyectoProgramatico($param["idProyectoProgramatico"], $p);
            }
        }
        $respuesta = $datosIndicador;
        $json = new Encode_JSON();
        return $json->encode($respuesta);
    }

    public function getIndicadorAsociadoIdProyectoProgramatico($idProyectoProgramatico, $p) {
        $d = array();
        $genericoDao = new GenericDAO();

        $sql = array(
            "campos" => "
                tblindicadoresasociados.*,
                tblambitosgeograficos.desAmbitoGeografico,
                tblsentidos.desSentido,
                tbltiposindicadores.desTipoIndicador,
                tblfrecuenciasmedicion.desFrecuenciaMedicion,
                tbltiposoperaciones.desTipoOperacion,
                tbltendencias.desTendencia,
                tbldimensiones.desDimension
            ",
            "tablas" => "
                tblindicadoresasociados tblindicadoresasociados 
                INNER JOIN tblindicadoresproyectos tblindicadoresproyectos 
                ON tblindicadoresasociados.cveIndicadorAsociado = tblindicadoresproyectos.cveIndicadorAsociado 
                INNER JOIN tblambitosgeograficos tblambitosgeograficos 
                ON tblindicadoresasociados.cveAmbitoGeografico = tblambitosgeograficos.cveAmbitoGeografico 
                INNER JOIN tblsentidos tblsentidos 
                ON tblindicadoresasociados.cveSentido = tblsentidos.cveSentido 
                INNER JOIN tbltiposindicadores tbltiposindicadores 
                ON tblindicadoresasociados.cveTipoIndicador = tbltiposindicadores.cveTipoIndicador 
                INNER JOIN tbltiposoperaciones tbltiposoperaciones 
                ON tblindicadoresasociados.cveTipoOperacion = tbltiposoperaciones.cveTipoOperacion 
                INNER JOIN tbltendencias tbltendencias 
                ON tblindicadoresasociados.cveTendencia = tbltendencias.cveTendencia 
                INNER JOIN  tbldimensiones tbldimensiones 
                ON tblindicadoresasociados.cveDimension = tbldimensiones.cveDimension 
                INNER JOIN  tblfrecuenciasmedicion tblfrecuenciasmedicion 
                ON tblindicadoresasociados.cveFrecuenciaMedicion = tblfrecuenciasmedicion.cveFrecuenciaMedicion                 
            ",
            "where" => " 
                (tblindicadoresasociados.activo = 'S') AND
                (tblindicadoresproyectos.activo = 'S') AND
                (tblindicadoresproyectos.idProyectoProgramatico = " . $idProyectoProgramatico . ")
            "
        );
        $sqlSelect = array("tabla" => "", "d" => $d, "tmpSql" => $sql, "proveedor" => $p);
        $rs = $genericoDao->select($sqlSelect);
        return $rs;
    }

    public function getIndicadorProyectoIdProyectoProgramatico($idProyectoProgramatico, $p = null) {
        $d = array();
        $genericoDao = new GenericDAO();

        $sql = array(
            "campos" => "
                * 
            ",
            "tablas" => "
                tblindicadoresproyectos tblindicadoresproyectos 
            ",
            "where" => " 
                (tblindicadoresproyectos.activo = 'S') AND
                (tblindicadoresproyectos.idProyectoProgramatico = " . $idProyectoProgramatico . ")
            "
        );
        $sqlSelect = array("tabla" => "", "d" => $d, "tmpSql" => $sql, "proveedor" => $p);
        $rs = $genericoDao->select($sqlSelect);
        return $rs;
    }

    public function getEvidenciasIndicadoresProyectos($idIndicadorProyecto, $p = null) {
        $d = array();
        $genericoDao = new GenericDAO();

        $sql = array(
            "campos" => "
                * 
            ",
            "tablas" => "
                tblevidenciasindicadoresproyectos tblevidenciasindicadoresproyectos 
            ",
            "where" => " 
                (tblevidenciasindicadoresproyectos.activo = 'S') AND
                (tblevidenciasindicadoresproyectos.idIndicadorProyecto = " . $idIndicadorProyecto . ")
            "
        );
        $sqlSelect = array("tabla" => "", "d" => $d, "tmpSql" => $sql, "proveedor" => $p);
        $rs = $genericoDao->select($sqlSelect);
        return $rs;
    }

    public function getValiablesIndicadoresProyectoIdProyectoProgramatico($idProyectoProgramatico, $p = null) {
        $d = array();
        $genericoDao = new GenericDAO();

        $sql = array(
            "campos" => "
                tblindicadoresvariables.*,
                tblvariables.nombreVariable,
                tblvariables.desVariable,
                tblunidadesmedida.desUnidadMedida,
                tblacumuladosanuales.desAcumuladoAnual
            ",
            "tablas" => "
                tblindicadoresvariables tblindicadoresvariables 
                INNER JOIN tblvariables tblvariables 
                ON tblindicadoresvariables.idVariable = tblvariables.idVariable 
                INNER JOIN tblindicadoresasociados tblindicadoresasociados 
                ON tblindicadoresasociados.cveIndicadorAsociado = tblindicadoresvariables.cveIndicadorAsociado 
                INNER JOIN tblindicadoresproyectos tblindicadoresproyectos 
                ON tblindicadoresasociados.cveIndicadorAsociado = tblindicadoresproyectos.cveIndicadorAsociado 
                INNER JOIN tblunidadesmedida tblunidadesmedida 
                ON tblvariables.cveUnidadMedida = tblunidadesmedida.cveUnidadMedida 
                INNER JOIN tblacumuladosanuales tblacumuladosanuales 
                ON tblvariables.cveAcumuladoAnual = tblacumuladosanuales.cveAcumuladoAnual 
            ",
            "where" => " 
                (tblindicadoresasociados.activo = 'S') AND
                (tblindicadoresproyectos.activo = 'S') AND
                (tblindicadoresvariables.activo = 'S') AND
                (tblvariables.activo = 'S') AND
                (tblunidadesmedida.activo = 'S') AND
                (tblacumuladosanuales.activo = 'S') AND
                (tblindicadoresproyectos.idProyectoProgramatico = " . $idProyectoProgramatico . ")
            "
        );
        $sqlSelect = array("tabla" => "", "d" => $d, "tmpSql" => $sql, "proveedor" => $p);
        $rs = $genericoDao->select($sqlSelect);
        return $rs;
    }

    public function getAdscripcionPadre($ads = null) {
        $fileJson = dirname(__FILE__) . "/../../archivos/juzgados" . date("Ymd") . ".json";
        if (file_exists($fileJson)) {
            $json = file_get_contents($fileJson);
            $json = json_decode($json, true);
            $buscarPadre = false;
            $cadenaBuscarPadre = "";
            if ($json["totalCount"] > 0) {
                foreach ($json["resultados"] as $key => $value) {
                    foreach ($value as $key2 => $value2) {
                        if ($key2 == "idJuzgado" && $value2 == $ads) {
                            if (substr($value["cveOrganigrama"], -3) != "000") {
                                $buscarPadre = true;
                                $cadenaBuscarPadre = (substr($value["cveOrganigrama"], 0, -3));
                            } else {
                                return array(
                                    "cveOrganigrama" => $value["cveOrganigrama"],
                                    "cveAdscripcion" => $value["idJuzgado"]
                                );
                            }
                        }
                    }
                }
                if ($buscarPadre) {
                    $cadenaBuscarPadre .= "000";
                    foreach ($json["resultados"] as $key => $value) {
                        foreach ($value as $key2 => $value2) {
                            if ($key2 == "cveOrganigrama" && $value2 == $cadenaBuscarPadre) {
                                return array(
                                    "cveOrganigrama" => $value["cveOrganigrama"],
                                    "cveAdscripcion" => $value["idJuzgado"]
                                );
                            }
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

    //--------------------
    public function consultarDatosIndicadorVariablesPorAccion($param, $p = null) {
        $respuesta = "";
        $datosIndicador = $this->getIndicadorAsociadoIdAccionProyecto($param["idAccionProyecto"], $p);
        if ($datosIndicador["totalCount"] > 0) {
            $datosIndicador["indicadorAccion"] = $this->getIndicadorAcciones($param["idAccionProyecto"], $p);
            if ($datosIndicador["indicadorAccion"]["totalCount"] > 0) {
                $datosIndicador["evidenciasIndicador"] = $this->getEvidenciasIndicadoresAcciones($datosIndicador["indicadorAccion"]["data"][0]["idIndicadorAccion"], $p);
                $datosIndicador["variablesIndicador"] = $this->getValiablesIndicadoresAcciones($param["idAccionProyecto"], $p);
            }
        }
        $respuesta = $datosIndicador;
        $json = new Encode_JSON();
        return $json->encode($respuesta);
    }

    public function getIndicadorAsociadoIdAccionProyecto($idAccionProyecto, $p) {
        $d = array();
        $genericoDao = new GenericDAO();

        $sql = array(
            "campos" => "
                tblindicadoresasociados.*,
                tblambitosgeograficos.desAmbitoGeografico,
                tblsentidos.desSentido,
                tbltiposindicadores.desTipoIndicador,
                tblfrecuenciasmedicion.desFrecuenciaMedicion,
                tbltiposoperaciones.desTipoOperacion,
                tbltendencias.desTendencia,
                tbldimensiones.desDimension
            ",
            "tablas" => "
                tblindicadoresasociados tblindicadoresasociados 
                INNER JOIN tblindicadoresacciones
                ON tblindicadoresasociados.cveIndicadorAsociado=tblindicadoresacciones.cveIndicadorAsociado
                INNER JOIN tblambitosgeograficos tblambitosgeograficos 
                ON tblindicadoresasociados.cveAmbitoGeografico = tblambitosgeograficos.cveAmbitoGeografico 
                INNER JOIN tblsentidos tblsentidos 
                ON tblindicadoresasociados.cveSentido = tblsentidos.cveSentido 
                INNER JOIN tbltiposindicadores tbltiposindicadores 
                ON tblindicadoresasociados.cveTipoIndicador = tbltiposindicadores.cveTipoIndicador 
                INNER JOIN tbltiposoperaciones tbltiposoperaciones 
                ON tblindicadoresasociados.cveTipoOperacion = tbltiposoperaciones.cveTipoOperacion 
                INNER JOIN tbltendencias tbltendencias 
                ON tblindicadoresasociados.cveTendencia = tbltendencias.cveTendencia 
                INNER JOIN  tbldimensiones tbldimensiones 
                ON tblindicadoresasociados.cveDimension = tbldimensiones.cveDimension 
                INNER JOIN  tblfrecuenciasmedicion tblfrecuenciasmedicion 
                ON tblindicadoresasociados.cveFrecuenciaMedicion = tblfrecuenciasmedicion.cveFrecuenciaMedicion                 
            ",
            "where" => " 
                (tblindicadoresasociados.activo = 'S') AND
                (tblindicadoresacciones.activo = 'S') AND
                (tblindicadoresacciones.idAccionProyecto = " . $idAccionProyecto . ")
            "
        );
        $sqlSelect = array("tabla" => "", "d" => $d, "tmpSql" => $sql, "proveedor" => $p);
        $rs = $genericoDao->select($sqlSelect);
        return $rs;
    }

    public function getIndicadorAcciones($idAccionProyecto, $p = null) {
        $d = array();
        $genericoDao = new GenericDAO();

        $sql = array(
            "campos" => "
                * 
            ",
            "tablas" => "
                tblindicadoresacciones 
            ",
            "where" => " 
                activo = 'S' AND idAccionProyecto = " . $idAccionProyecto . "
            "
        );
        $sqlSelect = array("tabla" => "", "d" => $d, "tmpSql" => $sql, "proveedor" => $p);
        $rs = $genericoDao->select($sqlSelect);
        return $rs;
    }

    public function getEvidenciasIndicadoresAcciones($idIndicadorAccion, $p = null) {
        $d = array();
        $genericoDao = new GenericDAO();

        $sql = array(
            "campos" => "
                * 
            ",
            "tablas" => "
                tblindicadoresacciones
            ",
            "where" => " 
                (activo = 'S') AND
                (idIndicadorAccion = " . $idIndicadorAccion . ")
            "
        );
        $sqlSelect = array("tabla" => "", "d" => $d, "tmpSql" => $sql, "proveedor" => $p);
        $rs = $genericoDao->select($sqlSelect);
        return $rs;
    }

    public function getValiablesIndicadoresAcciones($idAccionProyecto, $p = null) {
        $d = array();
        $genericoDao = new GenericDAO();

        $sql = array(
            "campos" => "
                tblindicadoresvariables.*,
                tblvariables.nombreVariable,
                tblvariables.desVariable,
                tblunidadesmedida.desUnidadMedida,
                tblacumuladosanuales.desAcumuladoAnual
            ",
            "tablas" => "
                tblindicadoresvariables tblindicadoresvariables 
                INNER JOIN tblvariables tblvariables 
                ON tblindicadoresvariables.idVariable = tblvariables.idVariable 
                INNER JOIN tblindicadoresasociados tblindicadoresasociados 
                ON tblindicadoresasociados.cveIndicadorAsociado = tblindicadoresvariables.cveIndicadorAsociado 
                INNER JOIN tblindicadoresacciones tblindicadoresacciones
                ON tblindicadoresasociados.cveIndicadorAsociado = tblindicadoresacciones.cveIndicadorAsociado 
                INNER JOIN tblunidadesmedida tblunidadesmedida 
                ON tblvariables.cveUnidadMedida = tblunidadesmedida.cveUnidadMedida 
                INNER JOIN tblacumuladosanuales tblacumuladosanuales 
                ON tblvariables.cveAcumuladoAnual = tblacumuladosanuales.cveAcumuladoAnual 
            ",
            "where" => " 
                (tblindicadoresasociados.activo = 'S') AND
                (tblindicadoresacciones.activo = 'S') AND
                (tblindicadoresvariables.activo = 'S') AND
                (tblvariables.activo = 'S') AND
                (tblunidadesmedida.activo = 'S') AND
                (tblacumuladosanuales.activo = 'S') AND
                (tblindicadoresacciones.idAccionProyecto = " . $idAccionProyecto . ")
            "
        );
        $sqlSelect = array("tabla" => "", "d" => $d, "tmpSql" => $sql, "proveedor" => $p);
        $rs = $genericoDao->select($sqlSelect);
        return $rs;
    }

    public function datatableConsultaEvidenciasIndicadoresAcciones($params) {
//        print_r($params);
        $limit = array("max" => $params["limit"]["max"],
            "pag" => $params["limit"]["pag"]);
        $d = array("limit" => $limit);
        $sql = array(
            "campos" => " 
                i.idIndicadorAccion,
                i.idAccionProyecto,
                i.cveIndicadorAsociado,
                i.cantidadAnual,
                i.ponderacionTotal1,
                i.ponderacionTotal2,
                i.ponderacionTotal3,
                i.ponderacionTotal4,
                i.ponderacionTrim1,
                i.ponderacionTrim2,
                i.ponderacionTrim3,
                i.ponderacionTrim4,
                i.activo,
                i.fechaRegistro,
                i.fechaActualizacion,
                ei.idEvidenciaIndicadorAccion,
                ei.idIndicadorAccion,
                ei.desEvidencia,
                ei.trimestre,
                ei.idEvidenciaIndicadorAccion
            ",
            "tablas" => "
                tblindicadoresacciones i
                INNER JOIN tblevidenciasindicadoresacciones ei
                ON i.idIndicadorAccion=ei.idIndicadorAccion 
            ",
            "orders" => $params["order"]["column"] . " " . $params["order"]["dir"],
            "where" => "
                (i.activo = 'S') AND
                (ei.activo = 'S') AND
                (ei.idIndicadorAccion = " . $params["extrasPost"]["idIndicadorAccion"] . ")
            ");


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
        return $this->datatableGenerico($params, $param, $limit, $nombreTabla = $sql['tablas'], $sql['where']);
    }

    public function agregarNuevaEvidenciaIndicadorAcciones($param, $paramFiles) {
//        var_dump($paramFiles);
        $proveedor = new Proveedor('mysql', 'ADMINISTRATIVO');
        $proveedor->connect();
        $proveedor->execute("BEGIN");
        $controllerImagenes = new ImagenesController();

        $error = false;
        $respuesta = "";
        $genenericDAO = new GenericDAO();

        $indicadoresAsociados = array(
            "tabla" => "tblindicadoresacciones",
            "accionBitacora" => "146",
            "d" => array(
                "values" => array(
                    "ponderacionTrim1" => "",
                    "ponderacionTrim2" => "",
                    "ponderacionTrim3" => "",
                    "ponderacionTrim4" => "",
                    "fechaActualizacion" => "now()"
                ),
                "where" => array("idIndicadorAccion" => $param["idIndicadorAccion"])
            ),
            "proveedor" => $proveedor
        );

        if ($param["trimestre"] == "1") {
            $indicadoresAsociados["d"]["values"]["ponderacionTrim1"] = $param["valorporcentajeavanceaccion"];
        }
        if ($param["trimestre"] == "2") {
            $indicadoresAsociados["d"]["values"]["ponderacionTrim2"] = $param["valorporcentajeavanceaccion"];
        }
        if ($param["trimestre"] == "3") {
            $indicadoresAsociados["d"]["values"]["ponderacionTrim3"] = $param["valorporcentajeavanceaccion"];
        }
        if ($param["trimestre"] == "4") {
            $indicadoresAsociados["d"]["values"]["ponderacionTrim3"] = $param["valorporcentajeavanceaccion"];
        }
        $updateIndicadorAcciones = $genenericDAO->update($indicadoresAsociados);

        if ($updateIndicadorAcciones["totalCount"] > 0) {
            $guardarEvidencia = array(
                "tabla" => "tblevidenciasindicadoresacciones",
                "accionBitacora" => "145",
                "d" => array(
                    "values" => array(
                        "desEvidencia" => $param["desEvidencia"],
                        "idIndicadorAccion" => $param["idIndicadorAccion"],
                        "trimestre" => $param["trimestre"],
                        "activo" => "S",
                        "fechaRegistro" => "now()",
                        "fechaActualizacion" => "now()",
                    )
                ),
                "proveedor" => $proveedor
            );
            $guardarEvidenciaRs = $genenericDAO->insert($guardarEvidencia);
            //        var_dump($guardarEvidenciaRs);

            if ($guardarEvidenciaRs["totalCount"] > 0) {
                $guardarDocumentosImg = array(
                    "tabla" => "tbldocumentosimg",
                    "accionBitacora" => "4",
                    "d" => array(
                        "values" => array(
                            "descripcion" => str_ireplace("'", "\\'", ($param["observaciones"])),
                            "numEmpleadoCarga" => $_SESSION["NumEmpleado"],
                            "cveTipoDocumento" => 30, //Evidencia Indicadores Acciones
                            "idReferencia" => $guardarEvidenciaRs["data"][0]["idEvidenciaIndicadorAccion"],
                            "activo" => "S",
                            "fechaRegistro" => "now()",
                            "fechaActualizacion" => "now()",
                        )
                    ),
                    "proveedor" => $proveedor
                );
                $imagenes = $controllerImagenes->crearDocumentoImg($guardarDocumentosImg);
//                    var_dump("******");
//                    var_dump($imagenes);
//                    var_dump("HOLA");
//                    var_dump($imagenes["totalCount"]);
                if ($paramFiles != null) {
                    if ($imagenes["totalCount"] > 0) {
//                        var_dump($paramFiles);
                        $imagenesDoc = $controllerImagenes->crearImagenes($paramFiles, $imagenes, $proveedor);
//                        var_dump($imagenesDoc);
                        if (!$imagenesDoc) {
                            $respuesta = $guardarEvidenciaRs;
//                            $respuesta = $this->consultarEvidenciaPorAccionArchivos(array("cveEvidenciaAccion" => $guardarEvidenciaRs["data"][0]["cveEvidenciaAccion"]));
                        } else {
                            $error = true;
                        }
//                        var_dump($imagenesDoc); 
                    }
//                    $archivoCreado = $this->guardarArchivoServidor($param, $paramFiles, $guardarEvidenciaRs, $proveedor);
//                    if ($archivoCreado["totalCount"] > 0) {
//                        $respuesta = $archivoCreado;
//                    } else {
//                        $error = true;
//                    }
                } else {
                    $respuesta = $guardarEvidenciaRs;
                }
                if (!$error) {
                    $error = $this->convertirEditorPDF($imagenes["data"][0]["descripcion"], $imagenes, false, $proveedor);
                }
            } else {
                $respuesta = $guardarEvidenciaRs;
                $error = true;
            }
        } else {
            $respuesta = $guardarEvidenciaRs;
            $error = true;
        }
        //NOTIFICA
        if (!$error) {
            try {
                $this->notificar(array(
                    "Origen" => $this->getAdscripcionPadre($_SESSION["cveAdscripcion"])["cveAdscripcion"],
                    "Destino" => 853,
                    "cveTipoNotificacion" => "1",
                    "tituloNotificacion" => utf8_decode("Agrego Evidencia"),
                    "descripcionNotificacion" => $_SESSION["desAdscripcion"] . utf8_decode(" agregó una nueva evidencia al proyecto programático ..."),
                    "urlFormulario" => "vistas/planeacion/frmAdministracionProyectosView.php",
                        ), $proveedor);
            } catch (Exception $e) {
                
            }
            $respuesta = $this->consultarEvidenciaPorAccionArchivosAcciones(array("idEvidenciaIndicadorAccion" => $guardarEvidenciaRs["data"][0]["idEvidenciaIndicadorAccion"]), true, $proveedor);
        }

        if (!$error) {
            $proveedor->execute("COMMIT");
        } else {
            $proveedor->execute("ROLLBACK");
        }
        $proveedor->close();
        $jsonEncode = new Encode_JSON();
        return $jsonEncode->encode($respuesta);
    }

    public function consultarEvidenciaPorAccionArchivosAcciones($param, $regresar = false, $proveedor = null) {
        $d = array();
        $genericoDao = new GenericDAO();

        $sql = array(
            "campos" => "
                ia.idIndicadorAccion,ia.ponderacionTrim1,ia.ponderacionTrim2,ia.ponderacionTrim3,ia.ponderacionTrim1,
                ia.ponderacionTotal1,ia.ponderacionTotal2,ia.ponderacionTotal3,ia.ponderacionTotal4,
                ei.idEvidenciaIndicadorAccion,ei.idIndicadorAccion,ei.desEvidencia,ei.trimestre,ei.fechaActualizacion,
                d.descripcion
            ",
            "tablas" => "
                tblevidenciasindicadoresacciones ei
                INNER JOIN tblindicadoresacciones ia
                ON ia.idIndicadorAccion = ei.idIndicadorAccion 
                INNER JOIN tbldocumentosimg d
                ON ei.idEvidenciaIndicadorAccion = d.idReferencia 
                INNER JOIN tblimagenes i ON d.idDocumentoImg=i.idDocumentoImg
            ",
            "where" => "
                ei.activo = 'S' AND d.activo='S' AND i.activo='S' AND ei.idEvidenciaIndicadorAccion=" . $param['idEvidenciaIndicadorAccion']
        );

        $sqlSelect = array("tabla" => "", "d" => $d, "tmpSql" => $sql, "proveedor" => $proveedor);
        $rs = $genericoDao->select($sqlSelect);
        $evidenciasArchivo = array();
        $evidenciasArchivoRs = array();
        if ($rs["totalCount"] > 0) {
            foreach ($rs["data"] as $key => $value) {
                foreach ($value as $key2 => $value2) {
                    $evidenciasArchivo[$key][$key2] = $value2;
                    if ($key2 == "idEvidenciaIndicadorAccion") {
                        $evidenciasArchivo[$key]["documentos"] = $this->getEvidenciaPorAccionArchivos($value2, $proveedor);
                    }
                }
            }
            $evidenciasArchivoRs["totalCount"] = $rs["totalCount"];
            $evidenciasArchivoRs["status"] = $rs["status"];
            $evidenciasArchivoRs["data"] = $evidenciasArchivo;
            $respuesta = $evidenciasArchivoRs;
        } else {
            $respuesta = $rs;
        }
        if ($regresar) {
            $jsonEncode = new Encode_JSON();
            return ($respuesta);
        } else {
            $jsonEncode = new Encode_JSON();
            return $jsonEncode->encode($respuesta);
        }
    }

    public function consultarEvidenciaIndicadorAccion($param, $regresar = false, $proveedor = null) {
        $d = array();
        $genericoDao = new GenericDAO();

        $sql = array(
            "campos" => "ia.*,ei.idEvidenciaIndicadorAccion,ei.desEvidencia,ei.trimestre,"
            . "d.idDocumentoImg,d.cveTipoDocumento,d.descripcion",
            "tablas" => "tblindicadoresacciones ia "
            . "INNER JOIN tblevidenciasindicadoresacciones ei ON ia.idIndicadorAccion=ei.idIndicadorAccion "
            . "INNER JOIN tbldocumentosimg d ON ei.idEvidenciaIndicadorAccion=d.idReferencia",
            "where" => "ei.activo='S' AND d.activo='S' AND d.cveTipoDocumento=30 "
            . "AND ei.idEvidenciaIndicadorAccion=" . $param["idEvidenciaIndicadorAccion"]
        );

        $sqlSelect = array("tabla" => "", "d" => $d, "tmpSql" => $sql, "proveedor" => $proveedor);
        $rs = $genericoDao->select($sqlSelect);
        $evidenciasArchivo = array();
        $evidenciasArchivoRs = array();
        if ($rs["totalCount"] > 0) {
            foreach ($rs["data"] as $key => $value) {
                foreach ($value as $key2 => $value2) {
                    $evidenciasArchivo[$key][$key2] = $value2;
                    if ($key2 == "idEvidenciaIndicadorAccion") {
                        $evidenciasArchivo[$key]["documentos"] = $this->getEvidenciaPorAccionArchivosIndicadorAccion($value2, $proveedor);
                    }
                }
            }
            $evidenciasArchivoRs["totalCount"] = $rs["totalCount"];
            $evidenciasArchivoRs["status"] = $rs["status"];
            $evidenciasArchivoRs["data"] = $evidenciasArchivo;
            $respuesta = $evidenciasArchivoRs;
        } else {
            $respuesta = $rs;
        }
        if ($regresar) {
            $jsonEncode = new Encode_JSON();
            return ($respuesta);
        } else {
            $jsonEncode = new Encode_JSON();
            return $jsonEncode->encode($respuesta);
        }
    }

    public function modificarEvidenciaIndicadorAccion($param) {
        $proveedor = new Proveedor('mysql', 'ADMINISTRATIVO');
        $proveedor->connect();
        $proveedor->execute("BEGIN");
        $error = false;
        $respuesta = "";
        $d = array();
        $genenericDAO = new GenericDAO();
        $updateEvidencia = array(
            "tabla" => "tblevidenciasindicadoresacciones",
            "accionBitacora" => "148",
            "d" => array(
                "values" => array(
                    "fechaActualizacion" => "now()",
                    "desEvidencia" => $param["desEvidencia"],
                ), "where" => array(
                    "idEvidenciaIndicadorAccion" => $param["idEvidenciaIndicadorAccion"]
                )), "proveedor" => $proveedor
        );
//        $selectEvidenciaRs = $this->consultaAntesUpdate($updateEvidencia);
        $updateEvidenciaRs = $genenericDAO->update($updateEvidencia);
        if ($updateEvidenciaRs["totalCount"] > 0) {
            $updateDocumentosImg = array(
                "tabla" => "tbldocumentosimg", "d" => array(
                    "values" => array(
                        "descripcion" => str_ireplace("'", "\\'", ($param["observaciones"])),
                        "fechaActualizacion" => "now()",
                    ), "where" => array(
                        "idReferencia" => $param["idEvidenciaIndicadorAccion"],
                        "cveTipoDocumento" => 30
                    )), "proveedor" => $proveedor
            );
            $updateDocumentosImgRs = $genenericDAO->update($updateDocumentosImg);
            if ($updateDocumentosImgRs["totalCount"] > 0) {
                $imagenesController = new ImagenesController();
                $documentoImagenes = $imagenesController->consultarTiposDocumentos($updateDocumentosImgRs, $proveedor);
                $error = $this->convertirEditorPDF($documentoImagenes["data"][0]["descripcion"], $documentoImagenes, true, $proveedor);
                $respuesta = $updateEvidenciaRs;
            } else {
                
            }
        } else {
            $error = true;
            $respuesta = $updateEvidenciaRs;
        }
        if (!$error) {
            $proveedor->execute("COMMIT");
        } else {
            $proveedor->execute("ROLLBACK");
        }
        $proveedor->close();
        $jsonEncode = new Encode_JSON();
        return $jsonEncode->encode($respuesta);
    }

    public function getEvidenciaPorAccionArchivosIndicadorAccion($idEvidenciaIndicadorAccion, $proveedor = null) {
        $genericoDao = new GenericDAO();
        $d = array();
        $sql = array(
            "campos" => " tblimagenes.*  ",
            "tablas" => " tblimagenes tblimagenes 
            INNER JOIN tbldocumentosimg tbldocumentosimg 
            ON tblimagenes.idDocumentoImg = tbldocumentosimg.idDocumentoImg  ",
            "where" => " (tblimagenes.activo = 'S') AND
            (tbldocumentosimg.activo = 'S') AND
            (tbldocumentosimg.cveTipoDocumento = 30) AND
            (tbldocumentosimg.idReferencia = " . $idEvidenciaIndicadorAccion . ") "
        );
        $sqlSelect = array("tabla" => "", "d" => $d, "tmpSql" => $sql, "proveedor" => $proveedor);
        $rs = $genericoDao->select($sqlSelect);
        return $rs;
    }

    public function modificarEvidenciaAccion($param) {
        $proveedor = new Proveedor('mysql', 'ADMINISTRATIVO');
        $proveedor->connect();
        $proveedor->execute("BEGIN");
        $error = false;
        $respuesta = "";
        $d = array();
        $genenericDAO = new GenericDAO();
        $updateEvidencia = array(
            "tabla" => "tblevidenciasindicadoresacciones", "d" => array(
                "values" => array(
                    "fechaActualizacion" => "now()",
                    "desEvidencia" => $param["desEvidencia"],
                ), "where" => array(
                    "idEvidenciaIndicadorAccion" => $param["idEvidenciaIndicadorAccion"]
                )), "proveedor" => $proveedor
        );
        $selectEvidenciaRs = $this->consultaAntesUpdate($updateEvidencia);
        $updateEvidenciaRs = $genenericDAO->update($updateEvidencia);
        if ($updateEvidenciaRs["totalCount"] > 0) {
            $updateDocumentosImg = array(
                "tabla" => "tbldocumentosimg", "d" => array(
                    "values" => array(
                        "descripcion" => str_ireplace("'", "\\'", ($param["observaciones"])),
                        "fechaActualizacion" => "now()",
                    ), "where" => array(
                        "idReferencia" => $param["idEvidenciaIndicadorAccion"],
                        "cveTipoDocumento" => 30
                    )), "proveedor" => $proveedor
            );
            $updateDocumentosImgRs = $genenericDAO->update($updateDocumentosImg);
            if ($updateDocumentosImgRs["totalCount"] > 0) {
                $imagenesController = new ImagenesController();
                $documentoImagenes = $imagenesController->consultarTiposDocumentos($updateDocumentosImgRs, $proveedor);
                $error = $this->convertirEditorPDF($documentoImagenes["data"][0]["descripcion"], $documentoImagenes, true, $proveedor);
                $respuesta = $updateEvidenciaRs;
            } else {
                
            }
        } else {
            $error = true;
            $respuesta = $updateEvidenciaRs;
        }
        if (!$error) {
            $proveedor->execute("COMMIT");
        } else {
            $proveedor->execute("ROLLBACK");
        }
        $proveedor->close();
        $jsonEncode = new Encode_JSON();
        return $jsonEncode->encode($respuesta);
    }

    public function eliminarEvidenciaIndicadorAccion($param) {
        $proveedor = new Proveedor('mysql', 'ADMINISTRATIVO');
        $genenericDAO = new GenericDAO();
        $proveedor->connect();
        $proveedor->execute("BEGIN");
        $error = false;
        $respuesta = "";
        if ($param["idEvidenciaIndicadorAccion"] != null && $param["idEvidenciaIndicadorAccion"] != "null" && $param["idEvidenciaIndicadorAccion"] != "") {
            $updateEvidencia = array(
                "tabla" => "tblevidenciasindicadoresacciones", "d" => array(
                    "values" => array(
                        "activo" => "N",
                        "fechaActualizacion" => "now()"
                    ), "where" => array(
                        "idEvidenciaIndicadorAccion" => $param["idEvidenciaIndicadorAccion"]
                    )
                ), "proveedor" => $proveedor
            );
            $updatEvidenciaRs = $genenericDAO->update($updateEvidencia);
            if ($updatEvidenciaRs["totalCount"] > 0) {
                $respuesta = $updatEvidenciaRs;
                $updateDocumentosEvidencia = array(
                    "tabla" => "tbldocumentosimg", "d" => array(
                        "values" => array(
                            "activo" => "N",
                            "fechaActualizacion" => "now()"
                        ), "where" => array(
                            "idReferencia" => $param["idEvidenciaIndicadorAccion"],
                            "cveTipoDocumento" => 30,
                        )), "proveedor" => $proveedor
                );
                $updatDocumentosEvidenciaRs = $genenericDAO->update($updateDocumentosEvidencia);
                if ($updatDocumentosEvidenciaRs["totalCount"] > 0) {
                    $updateImagenes = array(
                        "tabla" => "tblimagenes", "d" => array(
                            "values" => array(
                                "activo" => "N",
                                "fechaActualizacion" => "now()"
                            ), "where" => array(
                                "idDocumentoImg" => $updatDocumentosEvidenciaRs["data"][0]["idDocumentoImg"],
                            )), "proveedor" => $proveedor
                    );
                    $updatImagenesRs = $genenericDAO->update($updateImagenes);
                    $respuesta = $updatDocumentosEvidenciaRs;
                } else {
                    $error = true;
                    $respuesta = $updatDocumentosEvidenciaRs;
                }
            } else {
                $error = true;
                $respuesta = $updatEvidenciaRs;
            }
            if (!$error) {
                $proveedor->execute("COMMIT");
            } else {
                $proveedor->execute("ROLLBACK");
            }
            $proveedor->close();
            $jsonEncode = new Encode_JSON();
            return $jsonEncode->encode($respuesta);
        }
    }

    public function datatableConsultaObservacionesEvidenciasIa($params) {
//        var_dump($params);
        $limit = array("max" => $params["limit"]["max"],
            "pag" => $params["limit"]["pag"]);
        $d = array("limit" => $limit);

        if ($params["extrasPost"]["idEvidenciaIndicadorAccion"] != "0") {
            $where = "  ap.activo='S' AND ia.activo='S' AND eia.activo='S' AND oe.activo='S' AND ap.idProyectoProgramatico=" . $params["extrasPost"]["idProyectoProgramatico"] . " AND oe.idEvidenciaIndicadorAccion= " . $params["extrasPost"]["idEvidenciaIndicadorAccion"];
        } else {
            $where = " ap.activo='S' AND ia.activo='S' AND eia.activo='S' AND oe.activo='S' AND ap.idProyectoProgramatico=" . $params["extrasPost"]["idProyectoProgramatico"];
        }
        $sql = array(
            "campos" => "oe.idObsaervacionesEvidenciasIA,oe.idEvidenciaIndicadorAccion,CONCAT (ap.nomenclatura,' ',ap.desAccionProyecto) accion,eia.desEvidencia,oe.comentarios,oe.fechaRegistro,oe.fechaActualizacion
            ",
            "tablas" => "tblaccionesproyecto ap 
INNER JOIN tblindicadoresacciones ia ON ap.idAccionProyecto=ia.idAccionProyecto
INNER JOIN tblevidenciasindicadoresacciones eia ON ia.idIndicadorAccion=eia.idIndicadorAccion
INNER JOIN tblobservacionesevidenciasia oe ON eia.idEvidenciaIndicadorAccion=oe.idEvidenciaIndicadorAccion
            ",
            "orders" => $params["order"]["column"] . " " . $params["order"]["dir"],
            "where" => $where
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
        return $this->datatableGenerico($params, $param, $limit, $nombreTabla = $sql['tablas'], $sql['where'], "", "", true);
    }

    //--------------------

    public function consultarObservacionEvidenciaIndicadorAccion($param) {
        $d = array();
        $genericoDao = new GenericDAO();

        $sql = array(
            "campos" => "o.*,d.descripcion ",
            "tablas" => "tblobservacionesevidenciasia o INNER JOIN tbldocumentosimg d ON o.idObsaervacionesEvidenciasIA = d.idReferencia ",
            "where" => "o.activo='S' AND d.activo='S' AND d.cveTipoDocumento=31 AND o.idEvidenciaIndicadorAccion=" . $param["extrasPost"]["idEvidenciaIndicadorAccion"]
        );

        $sqlSelect = array("tabla" => "", "d" => $d, "tmpSql" => $sql, "proveedor" => null);
        $rs = $genericoDao->select($sqlSelect);

        $jsonEncode = new Encode_JSON();
        return $jsonEncode->encode($rs);
    }

    public function agregarNuevaObservacionEvidenciaIndicadorAccion($params) {
//        var_dump($params);
        $genenericDAO = new GenericDAO();
        $controllerImagenes = new ImagenesController();
        $idProyectoProgramaticoGeneral = "";
        $proveedor = new Proveedor('mysql', 'ADMINISTRATIVO');
        $proveedor->connect();
        $proveedor->execute("BEGIN");
        $error = false;
        $respuesta = "";
        $updateAccionProyecto = array(
            "tabla" => "tblindicadoresacciones",
            "accionBitacora" => "146",
            "d" => array(
                "values" => array(
                    "ponderacionTrim" . $params["trimestre"] => $params["valorporcentajeavanceaccion"],
                    "fechaActualizacion" => "now()"
                ), "where" => array(
                    "idIndicadorAccion" => $params["idIndicadorAccion"]
                )), "proveedor" => $proveedor
        );
        $updatAccionProyectoRs = $genenericDAO->update($updateAccionProyecto);
//        print_r($updatAccionProyectoRs);

        if ($updatAccionProyectoRs["totalCount"] > 0) {
            $idIndicadorAccion = $updatAccionProyectoRs["data"][0]["idIndicadorAccion"];
            if (array_key_exists("idObsaervacionesEvidenciasIA", $params) && $params["idObsaervacionesEvidenciasIA"] != "" && $params["idObsaervacionesEvidenciasIA"] != null) {
                //Modificar
//                var_dump("Modificar");
                $updateObservacionEvidencia = array(
                    "tabla" => "tblobservacionesevidenciasia", "d" => array(
                        "values" => array(
                            "comentarios" => $params["comentarios"],
                            "fechaActualizacion" => "now()"
                        ), "where" => array(
                            "idObsaervacionesEvidenciasIA" => $params["idObsaervacionesEvidenciasIA"]
                        )), "proveedor" => $proveedor
                );
                $updatObservacionEvidenciaRs = $genenericDAO->update($updateObservacionEvidencia);
//                var_dump($updatObservacionEvidenciaRs);
                if ($updatObservacionEvidenciaRs["totalCount"] > 0) {
//                    var_dump($updatObservacionEvidenciaRs);
                    $updateDocumentosImg = array(
                        "tabla" => "tbldocumentosimg", "d" => array(
                            "values" => array(
                                "descripcion" => str_ireplace("'", "\\'", ($params["observaciones"])),
                                "numEmpleadoCarga" => $_SESSION["NumEmpleado"],
                                "fechaActualizacion" => "now()"
                            ), "where" => array(
                                "idReferencia" => $params["idObsaervacionesEvidenciasIA"],
                                "cveTipoDocumento" => 31// Evidencia Observaciones Indicadores Acciones
                            )), "proveedor" => $proveedor
                    );
                    $updatDocumentosImgRs = $genenericDAO->update($updateDocumentosImg);
//                    var_dump($imagenes);
                    if ($updatDocumentosImgRs["totalCount"] > 0) {
                        $imagenesController = new ImagenesController();
                        $documentoImagenes = $imagenesController->consultarTiposDocumentos($updatDocumentosImgRs, $proveedor);
                        $error = $this->convertirEditorPDF($documentoImagenes["data"][0]["descripcion"], $documentoImagenes, true, $proveedor);
                        $respuesta = $updatObservacionEvidenciaRs;
//                        $respuesta["acumulado"] = $this->getAcumuladoCantidad($params, $proveedor);
                    } else {
                        $respuesta = $updatDocumentosImgRs;
                        $error = true;
                    }
                } else {
                    $error = true;
                    $respuesta = $updatObservacionEvidenciaRs;
                }
            } else {
                //Agregar 
//                var_dump("Agregar");
                $guardarObservacionEvidencia = array(
                    "tabla" => "tblobservacionesevidenciasia",
                    "accionBitacora" => "149",
                    "d" => array(
                        "values" => array(
                            "comentarios" => $params["comentarios"],
                            "idEvidenciaIndicadorAccion" => $params["idEvidenciaIndicadorAccion"],
                            "activo" => "S",
                            "fechaRegistro" => "now()",
                            "fechaActualizacion" => "now()",
                        )), "proveedor" => $proveedor
                );
                $guardarObservacionEvidenciaRs = $genenericDAO->insert($guardarObservacionEvidencia);

                if ($guardarObservacionEvidenciaRs["totalCount"] > 0) {
                    $guardarDocumentosImg = array(
                        "tabla" => "tbldocumentosimg",
                        "d" => array(
                            "values" => array(
                                "descripcion" => str_ireplace("'", "\\'", ($params["observaciones"])),
                                "numEmpleadoCarga" => $_SESSION["NumEmpleado"],
                                "cveTipoDocumento" => 31,
                                "idReferencia" => $guardarObservacionEvidenciaRs["data"][0]["idObsaervacionesEvidenciasIA"],
                                "activo" => "S",
                                "fechaRegistro" => "now()",
                                "fechaActualizacion" => "now()",
                            )
                        ),
                        "proveedor" => $proveedor
                    );
                    $imagenes = $controllerImagenes->crearDocumentoImg($guardarDocumentosImg);
//                    var_dump($imagenes);
                    if ($imagenes["totalCount"] > 0) {
                        $error = $this->convertirEditorPDF($imagenes["data"][0]["descripcion"], $imagenes, false, $proveedor);
                        $respuesta = $guardarObservacionEvidenciaRs;
                    } else {
                        $respuesta = $imagenes;
                        $error = true;
                    }
                } else {
                    $error = true;
                    $respuesta = $guardarObservacionEvidenciaRs;
                }
            }
        } else {
            $error = true;
            $respuesta = $updatAccionProyectoRs;
        }
        if (!$error) {
            try {
//                $ads = $this->getAdscripcionEvidenciaAccion($params["cveEvidenciaAccion"]);
//                var_dump($ads);
                $this->notificar(array(
                    "Origen" => $this->adscripcionPadreArray["cveAdscripcion"],
                    "Destino" => 885,
                    "cveTipoNotificacion" => "1",
                    "tituloNotificacion" => utf8_decode("Agrego Observación a evidencia del indicador"),
                    "descripcionNotificacion" => $_SESSION["desAdscripcion"] . utf8_decode(" ha agregado una nueva observación a evidencia del indicador..."),
                    "urlFormulario" => "vistas/planeacion/frmSeguimientoProyectosView.php",
                        ), $proveedor);
            } catch (Exception $e) {
//                echo $e;
            }
        }
        if (!$error) {
            $proveedor->execute("COMMIT");
        } else {
            $proveedor->execute("ROLLBACK");
        }
        $proveedor->close();
        $jsonEncode = new Encode_JSON();
        return $jsonEncode->encode($respuesta);
    }

    //--------------------
}

//$SeguimientoProyectosController = new SeguimientoProyectosController();
//var_dump($SeguimientoProyectosController->getAdscripcionPadre(10169));
//var_dump($SeguimientoProyectosController->getAdscripcionPadre(10169));
