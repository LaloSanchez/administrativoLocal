<?php

include_once(dirname(__FILE__) . "/../../modelos/dao/GenericDAO.Class.php");
include_once(dirname(__FILE__) . "/../../tribunal/json/JsonEncod.Class.php");
include_once(dirname(__FILE__) . "/../../tribunal/json/JsonDecod.Class.php");
include_once(dirname(__FILE__) . "/../../tribunal/pdf/html2pdf.class.php");
include_once(dirname(__FILE__) . "/../../webservice/cliente/usuarios/UsuarioCliente.php");
include_once(dirname(__FILE__) . "/../../controladores/Imagenes/ImagenesController.Class.php");
include_once(dirname(__FILE__) . "/../../controladores/planeacion/SeguimientoProyectosController.Class.php");

/**
 * Clase para el Anteroyecto Programatico 
 *
 * @author PJ
 */
class AnteproyectoProgramaticoController {
    /* Función para consultar los Proyectos:
     * @param array $params array que contiene los datos de la paginación
     * @return json datos para construir el datatable del cri
     */
    private $proveedor;
    public function __construct() {
        $this->proveedor = new Proveedor('mysql', 'ADMINISTRATIVO');
    }
    public function consultaProyectosEstrategicos($params) {
        $limit = array("max" => $params["limit"]["max"], "pag" => $params["limit"]["pag"]);
        $d = array("limit" => $limit);
        $sql = array("campos" => "a.idProyectoEstrategico,a.desProyectoEstrategico, a.objetivo,c.desPdeEstrategia,d.desPdeIdeal,e.desPdeLineaAccion",
            "tablas" => "tblproyectosestrategicos a INNER JOIN tblunidadesinvolucradas b ON a.idProyectoEstrategico=b.idProyectoEstrategico inner join tblpdeestrategias c on a.cvePdeEstrategia=c.cvePdeEstrategia inner join tblpdeideales d on a.cvePdeIdeal=d.cvePdeIdeal inner join tblpdelineasaccion e on a.cvePdeLineaAccion=e.cvePdeLineaAccion",
            "orders" => $params["order"]["column"] . " " . $params["order"]["dir"],
            "where" => "a.activo='S' AND b.cveAdscripcion=" . $_SESSION["cveAdscripcion"] . "");
        if ($params['search']['value'] != "") {
            $sql['where'] = "a.activo='S' AND b.cveAdscripcion=" . $_SESSION["cveAdscripcion"] . " AND (a.idProyectoEstrategico LIKE '%" . $params['search']['value'] . "%' OR a.desProyectoEstrategico LIKE '%" . $params['search']['value'] . "%'  OR a.objetivo LIKE '%" . $params['search']['value'] . "%' )";
        }
        $param = array("tabla" => "", "d" => $d, "tmpSql" => $sql, "proveedor" => null);
        return $this->datatableGenerico($params, $param, $limit, $nombreTabla = $sql['tablas'], $sql['where']);
    }

    public function consultaProyectos($params) {
        $limit = array("max" => $params["limit"]["max"], "pag" => $params["limit"]["pag"]);
        $d = array("limit" => $limit);
        $sql = array("campos" => "a.idProyectoProgramatico,a.desProyectoProgramatico,a.Objetivo,a.estrategiaProyecto,b.desEstadoProyecto,a.cveEstatusPlaneacion,a.cveEstatusFinanzas",
            "tablas" => "tblproyectosprogramaticos a inner join tblestadosproyecto b on a.cveEstadoProyecto=b.cveEstadoProyecto",
            "orders" => "",
            "groups" => "a.idProyectoProgramatico",
            "where" => "a.activo='S'");
        if ($params['search']['value'] != "") {
            $sql['where'] = "a.activo='S' AND (a.idProyectoProgramatico LIKE '%" . $params['search']['value'] . "%' OR a.desProyectoProgramatico LIKE '%" . $params['search']['value'] . "%'  OR a.Objetivo LIKE '%" . $params['search']['value'] . "%'  OR a.estrategiaProyecto LIKE '%" . $params['search']['value'] . "%'   OR b.desEstadoProyecto LIKE '%" . $params['search']['value'] . "%')";
        }
        $param = array("tabla" => "", "d" => $d, "tmpSql" => $sql, "proveedor" => null);
        return $this->datatableGenerico($params, $param, $limit, $nombreTabla = $sql['tablas'], $sql['where']);
    }

    public function consultar($id, $tipo) {
        $genericoDao = new GenericDAO();
        $d = array("limit" => "");

        $sql = array("campos" => "a.idProyectoProgramatico,  a.desProyectoProgramatico,a.estrategiaProyecto,a.anioProyectoProgramatico,a.objetivo,a.metaProyecto,a.cveEstatusPlaneacion,a.numEmpElaboroP,a.numEmpRevisoP,a.numEmpAutorizoP,f.cveAdscripcion,b.`desPdeIdeal`,c.`desPdeEstrategia`,d.`desPdeLineaAccion`",
            "tablas" => "tblproyectosprogramaticos a INNER JOIN tblproyectosadscripciones f ON a.`idProyectoProgramatico`=f.idProyectoProgramatico INNER JOIN tblpdeideales b ON a.`cvePdeIdeal`=b.`cvePdeIdeal` INNER JOIN tblpdeestrategias c ON a.`cvePdeEstrategia`=c.`cvePdeEstrategia` INNER JOIN tblpdelineasaccion d ON a.`cvePdeLineaAccion`=d.`cvePdeLineaAccion`",
            "orders" => "",
            "where" => "a.`activo` = 'S' AND f.activo = 'S' AND A.`idProyectoProgramatico` =" . $id,
            "groups" => "A.idProyectoProgramatico");
        $param = array("tabla" => "", "d" => $d, "tmpSql" => $sql, "proveedor" => null);
        $row = $genericoDao->select($param);
        if ($row["totalCount"] != "") {
            foreach ($row["data"] as $key => $value) {
                foreach ($value as $key2 => $value2) {
                    if ($key2 == "numEmpElaboroP") {
                        if ($value != "") {
                            $numEmp = $this->getNombreEmpleado($value2);
                            $row["data"][$key]["nombreElabora"] = $numEmp;
                        }
                    }
                    if ($key2 == "numEmpRevisoP") {
                        if ($value != "") {
                            $numEmp = $this->getNombreEmpleado($value2);
                            $row["data"][$key]["nombreRevisa"] = $numEmp;
                        }
                    }
                    if ($key2 == "numEmpAutorizoP") {
                        if ($value != "") {
                            $numEmp = $this->getNombreEmpleado($value2);
                            $row["data"][$key]["nombreAutoriza"] = $numEmp;
                        }
                    }
                }
            }
        }
        $sql = array("campos" => "a.cveAdscripcion,b.idProyectoPresupuestal,b.desProyectoPresupuestal,b.claveProyecto",
            "tablas" => "htsj_administrativo.tblunidadesejecutoras a inner join tblproyectospresupuestales b on a.idProyectoPresupuestal=b.idProyectoPresupuestal",
            "orders" => "",
            "where" => "a.cveAdscripcion=" . $row["data"][0]["cveAdscripcion"] . " and a.activo='S'");

        $param = array("tabla" => "", "d" => $d, "tmpSql" => $sql, "proveedor" => null);
        $row8 = $genericoDao->select($param);
        if ($row8 != "") {
            $row["data"][0]["idProyectoPresupuestal"] = $row8["data"][0]["idProyectoPresupuestal"];
            $row["data"][0]["desProyectoPresupuestal"] = $row8["data"][0]["desProyectoPresupuestal"];
            $row["data"][0]["codigo"] = $row8["data"][0]["claveProyecto"];
        }

        ##Detalle de Actividades
        $row["data"][0]["acciones"] = array();
        $sql = array("campos" => "a.`idAccionProyecto`,a.`desAccionProyecto`,a.nomenclatura",
            "tablas" => "tblaccionesproyecto a",
            "orders" => "",
            "where" => "a.activo='S' and a.idProyectoProgramatico=" . $id . "");

        $param = array("tabla" => "", "d" => $d, "tmpSql" => $sql, "proveedor" => null);

        $row2 = $genericoDao->select($param);
        if ($row2 != "") {
            for ($x = 0; $x < $row2["totalCount"]; $x++) {
                $row["data"][0]["acciones"][$x]["idAccionProyecto"] = $row2["data"][$x]["idAccionProyecto"];
                $row["data"][0]["acciones"][$x]["desAccionProyecto"] = $row2["data"][$x]["desAccionProyecto"];
                $row["data"][0]["acciones"][$x]["nomenclatura"] = $row2["data"][$x]["nomenclatura"];

                $row["data"][0]["acciones"][$x]["detalle"] = array();
                $sql = array("campos" => "b.cantidadAnual,c.`desUnidadMedida`, b.`ponderacionTrim1`,b.`ponderacionTrim2`,b.`ponderacionTrim3`,b.`ponderacionTrim4`,b.`poblacionABeneficiar`,b.`poblacionProgramada` ",
                    "tablas" => " tblaccionprogramatica b INNER JOIN tblunidadesmedida c ON b.`cveUnidadMedida`=c.cveUnidadMedida",
                    "orders" => "",
                    "where" => "b.activo='S' and b.idAccionProyecto=" . $row2["data"][$x]["idAccionProyecto"] . "");
                $param = array("tabla" => "", "d" => $d, "tmpSql" => $sql, "proveedor" => null);
                $genericoDao = new GenericDAO();
                $row3 = $genericoDao->select($param);
                if ($row3["totalCount"] != 0) {
                    $row["data"][0]["acciones"][$x]["detalle"]["desUnidadMedida"] = $row3["data"][0]["desUnidadMedida"];
                    $row["data"][0]["acciones"][$x]["detalle"]["cantidadAnual"] = $row3["data"][0]["cantidadAnual"];
                    $row["data"][0]["acciones"][$x]["detalle"]["ponderacionTrim1"] = $row3["data"][0]["ponderacionTrim1"];
                    $row["data"][0]["acciones"][$x]["detalle"]["ponderacionTrim2"] = $row3["data"][0]["ponderacionTrim2"];
                    $row["data"][0]["acciones"][$x]["detalle"]["ponderacionTrim3"] = $row3["data"][0]["ponderacionTrim3"];
                    $row["data"][0]["acciones"][$x]["detalle"]["ponderacionTrim4"] = $row3["data"][0]["ponderacionTrim4"];
                    $row["data"][0]["acciones"][$x]["detalle"]["poblacionProgramada"] = $row3["data"][0]["poblacionProgramada"];
                    $row["data"][0]["acciones"][$x]["detalle"]["poblacionABeneficiar"] = $row3["data"][0]["poblacionABeneficiar"];
                }
            }
        }
        $row["data"][0]["totalAcciones"] = $row2["totalCount"];

        ##Documentos
        $row["data"][0]["arbol"] = array();
        $row["data"][0]["diagnostico"] = array();
        $row["data"][0]["foda"] = array();
        $row["data"][0]["arbolObjetivos"] = array();
        $row["data"][0]["analisis"] = array();
        $row["data"][0]["estructura"] = array();
        $row["data"][0]["mir"] = array();

        $sql = array("campos" => "*",
            "tablas" => "tbldocumentosimg",
            "orders" => "",
            "where" => "activo='S' and idReferencia=" . $id . "");

        $param = array("tabla" => "", "d" => $d, "tmpSql" => $sql, "proveedor" => null);
        $rowDoc = $genericoDao->select($param);
        $contDiag = 0;
        $contArbol = 0;
        $contMir = 0;
        $contFoda = 0;
        $contarbolObjetivos = 0;
        $contanalisis = 0;
        $contestructura = 0;
        if ($rowDoc["totalCount"] != 0) {
            for ($docu = 0; $docu < $rowDoc["totalCount"]; $docu++) {
                if ($rowDoc["data"][$docu]["cveTipoDocumento"] == 1) {
                    $row["data"][0]["diagnostico"]["descripcion"] = $rowDoc["data"][$docu]["descripcion"];
                    $sql = array("campos" => "idDocumentoImg,ruta,descripcion",
                        "tablas" => "tblimagenes",
                        "orders" => "",
                        "where" => "activo='S' and idDocumentoImg=" . $rowDoc["data"][$docu]["idDocumentoImg"] . "");

                    $param = array("tabla" => "", "d" => "", "tmpSql" => $sql, "proveedor" => null);
                    $rowDoc2 = $genericoDao->select($param);
                    foreach ($rowDoc2["data"] as $docum) {
                        $row["data"][0]["diagnostico"][$contDiag]["idDocumentoImg"] = $docum["idDocumentoImg"];
                        $row["data"][0]["diagnostico"][$contDiag]["idReferencia"] = $id;
                        $row["data"][0]["diagnostico"][$contDiag]["rutaArchivo"] = $docum["ruta"];
                        $row["data"][0]["diagnostico"][$contDiag]["descripcion"] = $docum["descripcion"];
                        $contDiag++;
                    }
                } else if ($rowDoc["data"][$docu]["cveTipoDocumento"] == 2) {
                    $sql = array("campos" => "idDocumentoImg,ruta,descripcion",
                        "tablas" => "tblimagenes",
                        "orders" => "",
                        "where" => "activo='S' and idDocumentoImg=" . $rowDoc["data"][$docu]["idDocumentoImg"] . "");

                    $param = array("tabla" => "", "d" => "", "tmpSql" => $sql, "proveedor" => null);
                    $rowDoc3 = $genericoDao->select($param);
                    foreach ($rowDoc3["data"] as $docum3) {
                        $row["data"][0]["arbol"][$contArbol]["idDocumentoImg"] = $docum3["idDocumentoImg"];
                        $row["data"][0]["arbol"][$contArbol]["idReferencia"] = $id;
                        $row["data"][0]["arbol"][$contArbol]["rutaArchivo"] = $docum3["ruta"];
                        $row["data"][0]["arbol"][$contArbol]["descripcion"] = $docum3["descripcion"];
                        $contArbol++;
                    }
                } else if ($rowDoc["data"][$docu]["cveTipoDocumento"] == 3) {
                    $sql = array("campos" => "idDocumentoImg,ruta,descripcion",
                        "tablas" => "tblimagenes",
                        "orders" => "",
                        "where" => "activo='S' and idDocumentoImg=" . $rowDoc["data"][$docu]["idDocumentoImg"] . "");

                    $param = array("tabla" => "", "d" => "", "tmpSql" => $sql, "proveedor" => null);
                    $rowDoc4 = $genericoDao->select($param);
                    foreach ($rowDoc4["data"] as $docum4) {
                        $row["data"][0]["mir"][$contMir]["idDocumentoImg"] = $docum4["idDocumentoImg"];
                        $row["data"][0]["mir"][$contMir]["idReferencia"] = $id;
                        $row["data"][0]["mir"][$contMir]["rutaArchivo"] = $docum4["ruta"];
                        $row["data"][0]["mir"][$contMir]["descripcion"] = $docum4["descripcion"];
                        $contMir++;
                    }
                }else if($rowDoc["data"][$docu]["cveTipoDocumento"] == 38){
                    $sql = array("campos" => "idDocumentoImg,ruta,descripcion",
                        "tablas" => "tblimagenes",
                        "orders" => "",
                        "where" => "activo='S' and idDocumentoImg=" . $rowDoc["data"][$docu]["idDocumentoImg"] . "");

                    $param = array("tabla" => "", "d" => "", "tmpSql" => $sql, "proveedor" => null);
                    $rowDoc4 = $genericoDao->select($param);
                    foreach ($rowDoc4["data"] as $docum4) {
                        $row["data"][0]["foda"][$contFoda]["idDocumentoImg"] = $docum4["idDocumentoImg"];
                        $row["data"][0]["foda"][$contFoda]["idReferencia"] = $id;
                        $row["data"][0]["foda"][$contFoda]["rutaArchivo"] = $docum4["ruta"];
                        $row["data"][0]["foda"][$contFoda]["descripcion"] = $docum4["descripcion"];
                        $contFoda++;
                    }
                }else if($rowDoc["data"][$docu]["cveTipoDocumento"] == 39){
                    $sql = array("campos" => "idDocumentoImg,ruta,descripcion",
                        "tablas" => "tblimagenes",
                        "orders" => "",
                        "where" => "activo='S' and idDocumentoImg=" . $rowDoc["data"][$docu]["idDocumentoImg"] . "");

                    $param = array("tabla" => "", "d" => "", "tmpSql" => $sql, "proveedor" => null);
                    $rowDoc4 = $genericoDao->select($param);
                    foreach ($rowDoc4["data"] as $docum4) {
                        $row["data"][0]["arbolObjetivos"][$contarbolObjetivos]["idDocumentoImg"] = $docum4["idDocumentoImg"];
                        $row["data"][0]["arbolObjetivos"][$contarbolObjetivos]["idReferencia"] = $id;
                        $row["data"][0]["arbolObjetivos"][$contarbolObjetivos]["rutaArchivo"] = $docum4["ruta"];
                        $row["data"][0]["arbolObjetivos"][$contarbolObjetivos]["descripcion"] = $docum4["descripcion"];
                        $contarbolObjetivos++;
                    }
                }else if($rowDoc["data"][$docu]["cveTipoDocumento"] == 40){
                    $sql = array("campos" => "idDocumentoImg,ruta,descripcion",
                        "tablas" => "tblimagenes",
                        "orders" => "",
                        "where" => "activo='S' and idDocumentoImg=" . $rowDoc["data"][$docu]["idDocumentoImg"] . "");

                    $param = array("tabla" => "", "d" => "", "tmpSql" => $sql, "proveedor" => null);
                    $rowDoc4 = $genericoDao->select($param);
                    foreach ($rowDoc4["data"] as $docum4) {
                        $row["data"][0]["analisis"][$contanalisis]["idDocumentoImg"] = $docum4["idDocumentoImg"];
                        $row["data"][0]["analisis"][$contanalisis]["idReferencia"] = $id;
                        $row["data"][0]["analisis"][$contanalisis]["rutaArchivo"] = $docum4["ruta"];
                        $row["data"][0]["analisis"][$contanalisis]["descripcion"] = $docum4["descripcion"];
                        $contanalisis++;
                    }
                }else if($rowDoc["data"][$docu]["cveTipoDocumento"] == 41){
                    $sql = array("campos" => "idDocumentoImg,ruta,descripcion",
                        "tablas" => "tblimagenes",
                        "orders" => "",
                        "where" => "activo='S' and idDocumentoImg=" . $rowDoc["data"][$docu]["idDocumentoImg"] . "");

                    $param = array("tabla" => "", "d" => "", "tmpSql" => $sql, "proveedor" => null);
                    $rowDoc4 = $genericoDao->select($param);
                    foreach ($rowDoc4["data"] as $docum4) {
                        $row["data"][0]["estructura"][$contestructura]["idDocumentoImg"] = $docum4["idDocumentoImg"];
                        $row["data"][0]["estructura"][$contestructura]["idReferencia"] = $id;
                        $row["data"][0]["estructura"][$contestructura]["rutaArchivo"] = $docum4["ruta"];
                        $row["data"][0]["estructura"][$contestructura]["descripcion"] = $docum4["descripcion"];
                        $contestructura++;
                    }
                }
            }
        }
        $row["data"][0]["totalDiagnosticos"] = $contDiag;
        ##MontoTotal
        $sql = array("campos" => "SUM(montoTotal) as montoTotal",
            "tablas" => "tblanteproyectospartidas",
            "orders" => "",
            "where" => "activo='S' and idProyectoProgramatico=" . $id . "");

        $param = array("tabla" => "", "d" => $d, "tmpSql" => $sql, "proveedor" => null);
        $row6 = $genericoDao->select($param);
        $row["data"][0]["montoTotal"] = "";
        if ($row6 != "") {
            $row["data"][0]["montoTotal"] = $row6["data"][0]["montoTotal"];
        }

        ##Obserevaciones
        $row["data"][0]["historial"] = array();
        $sql = array("campos" => "*",
            "tablas" => "tblobservacionesplaneacion",
            "orders" => "idObservacionPlaneacion DESC",
            "where" => "idProyectoProgramatico=" . $id . " and activo='S'");

        $param = array("tabla" => "", "d" => $d, "tmpSql" => $sql, "proveedor" => null);
        $row9 = $genericoDao->select($param);
        if ($row9["totalCount"] != 0) {
            $row["data"][0]["historial"]["comentario"] = $row9["data"][0]["comentarios"];
            $row["data"][0]["historial"]["observaciones"] = $row9["data"][0]["observaciones"];

            $usuarioCliente = new UsuarioCliente();
            $usu = $usuarioCliente->getUsuarios($row9["data"][0]["cveUsuarioRechazo"]);
            $usu = json_decode($usu, true);
            $usuario = $usu["data"][0]["nombre"] . " " . $usu["data"][0]["paterno"] . " " . $usu["data"][0]["materno"];

            $row["data"][0]["historial"]["cveUsuario"] = $row9["data"][0]["cveUsuarioRechazo"];
            $row["data"][0]["historial"]["usuario"] = $usuario;
        }

        ##MIR
        $d = array("limit" => "");

        $sql = array("campos" => "a.fin,b.desIndicadorAsociado,b.desFormula,b.cveFrecuenciaMedicion,b.mediosVerificacion,b.supuestos,c.desFrecuenciaMedicion",
            "tablas" => "tblproyectospresupuestales a INNER JOIN tblindicadoresasociados b ON a.cveIndicadorAsociado=b.cveIndicadorAsociado INNER JOIN tblfrecuenciasmedicion c ON b.cveFrecuenciaMedicion=c.cveFrecuenciaMedicion",
            "orders" => "",
            "where" => "a.activo='S' AND b.activo='S' AND a.idProyectoPresupuestal=" . $row["data"][0]["idProyectoPresupuestal"] . "");

        $param = array("tabla" => "", "d" => $d, "tmpSql" => $sql, "proveedor" => null);
        $rowMir = $genericoDao->select($param);
        $row["data"][0]["fin"] = array();
        if ($rowMir["totalCount"] > 0) {
            if ($rowMir["totalCount"] > 0) {
                foreach ($rowMir["data"] as $key => $value) {
                    foreach ($value as $key2 => $value2) {
                        $row["data"][0]["fin"][$key][$key2] = $value2;
                    }
                }
            }
        }
        $d = "";
        $sql = array("campos" => "b.cveEpProposito, b.desEpProposito,c.desIndicadorAsociado,c.desFormula,c.cveFrecuenciaMedicion,c.mediosVerificacion,c.supuestos,d.desFrecuenciaMedicion",
            "tablas" => "tbleppropositos b INNER JOIN tblindicadoresasociados c ON b.cveIndicadorAsociado=c.cveIndicadorAsociado INNER JOIN tblfrecuenciasmedicion d ON c.cveFrecuenciaMedicion=d.cveFrecuenciaMedicion",
            "orders" => "",
            "where" => "b.`activo`='S' AND b.idProyectoPresupuestal=" . $row["data"][0]["idProyectoPresupuestal"] . "");

        $param = array("tabla" => "", "d" => $d, "tmpSql" => $sql, "proveedor" => null);
        $rowProp = $genericoDao->select($param);
        $row["data"][0]["proposito"] = array();
        if ($rowProp["totalCount"] > 0) {
            foreach ($rowProp["data"] as $key3 => $value3) {
                foreach ($value3 as $key4 => $value4) {
                    $row["data"][0]["proposito"][$key3][$key4] = $value4;
                }
            }
        }

        $d = "";
        $sql = array("campos" => "b.`desIndicadorAsociado`,b.`desFormula`,c.`desFrecuenciaMedicion`,b.`mediosVerificacion`,b.`supuestos`",
            "tablas" => "tblindicadoresproyectos a INNER JOIN tblindicadoresasociados b ON a.`cveIndicadorAsociado`=b.`cveIndicadorAsociado` INNER JOIN tblfrecuenciasmedicion c ON b.`cveFrecuenciaMedicion`=c.`cveFrecuenciaMedicion` ",
            "orders" => "",
            "where" => "a.`activo`='S' AND b.`activo`='S' AND a.`idProyectoProgramatico`=" . $id . "");

        $param = array("tabla" => "", "d" => $d, "tmpSql" => $sql, "proveedor" => null);
        $rowComp = $genericoDao->select($param);
        $rowComp["data"][0]["componentes"] = array();
        if ($rowComp["totalCount"] > 0) {
            foreach ($rowComp["data"] as $key5 => $value5) {
                foreach ($value5 as $key6 => $value6) {
                    $row["data"][0]["componentes"][$key5][$key6] = $value6;
                }
            }
        }

        $d = "";
        $sql = array("campos" => "a.idAccionProyecto,a.`desAccionProyecto`,c.`desIndicadorAsociado`,c.`desFormula`,c.`mediosVerificacion`,c.`supuestos`,d.`desFrecuenciaMedicion`",
            "tablas" => "tblaccionesproyecto a INNER JOIN tblindicadoresacciones b ON a.`idAccionProyecto`=b.`idAccionProyecto` INNER JOIN tblindicadoresasociados c ON b.`cveIndicadorAsociado`=c.`cveIndicadorAsociado` INNER JOIN tblfrecuenciasmedicion d ON c.`cveFrecuenciaMedicion`=d.`cveFrecuenciaMedicion` ",
            "orders" => "",
            "where" => "a.`activo`='S' AND b.`activo`='S' AND a.`idProyectoProgramatico`=" . $id . "");

        $param = array("tabla" => "", "d" => $d, "tmpSql" => $sql, "proveedor" => null);
        $rowInd = $genericoDao->select($param);
        $rowInd["data"][0]["indicadoresAcciones"] = array(); 
        if ($rowInd["totalCount"] > 0) {
            foreach ($rowInd["data"] as $key7 => $value7) {
                foreach ($value7 as $key8 => $value8) {
                    $row["data"][0]["indicadoresAcciones"][$key7][$key8] = $value8;
                }
            }
        }
        $sql["campos"] = "cveAdscripcion";
        $sql["tablas"] = "tblproyectosadscripciones";
        $sql["where"] = "activo='S' and idProyectoProgramatico=" . $id;
        $param = array("tabla" => "", "d" => "", "tmpSql" => $sql, "proveedor" => null);
        $adscripciones = $genericoDao->select($param);
        $row["data"][0]["adscripciones"] = array();
        foreach ($adscripciones["data"] as $key9 => $value9) {
            foreach ($value9 as $key10 => $value10) {
                if ($key10 == "cveAdscripcion") {
                    $row["data"][0]["adscripciones"][$key9] = $this->getAdscripcionNombre($value10);
                }
            }
        }
        if ($tipo == 1) {
            $json_encode = new Encode_JSON();
            $row = $json_encode->encode($row);
        }
        return $row;
    }

    public function detalleMeta($id) {
        $d = array("limit" => "");
        $row["data"][0]["acciones"] = array();
        $sql = array("campos" => "a.`idAccionProyecto`,a.`desAccionProyecto`,a.idProyectoProgramatico,a.fechaInicio,a.fechaFin",
            "tablas" => "tblaccionesproyecto a",
            "orders" => "",
            "where" => "a.activo='S' and a.idAccionProyecto=" . $id . "");
        $param = array("tabla" => "", "d" => $d, "tmpSql" => $sql, "proveedor" => null);
        $genericoDao = new GenericDAO();
        $row = $genericoDao->select($param);
        if ($row != "") {
            for ($x = 0; $x < $row["totalCount"]; $x++) {
                $row["data"][0]["detalle"] = array();
                $sql = array("campos" => "b.idAccionProgramatica,c.cveUnidadMedida,c.`desUnidadMedida`, b.`ponderacionTrim1`,b.`ponderacionTrim2`,b.`ponderacionTrim3`,b.`ponderacionTrim4`,b.`poblacionABeneficiar`,b.`poblacionProgramada`,b.cantidadAnual ",
                    "tablas" => " tblaccionprogramatica b INNER JOIN tblunidadesmedida c ON b.`cveUnidadMedida`=c.cveUnidadMedida",
                    "orders" => "",
                    "where" => "b.activo='S' and b.idAccionProyecto=" . $row["data"][0]["idAccionProyecto"] . "");
                $param = array("tabla" => "", "d" => $d, "tmpSql" => $sql, "proveedor" => null);
                $genericoDao = new GenericDAO();
                $row3 = $genericoDao->select($param);
                if ($row3["totalCount"] != 0) {
                    $row["data"][0]["detalle"]["idAccionProgramatica"] = $row3["data"][0]["idAccionProgramatica"];
                    $row["data"][0]["detalle"]["desUnidadMedida"] = $row3["data"][0]["desUnidadMedida"];
                    $row["data"][0]["detalle"]["cveUnidadMedida"] = $row3["data"][0]["cveUnidadMedida"];
                    $row["data"][0]["detalle"]["ponderacionTrim1"] = $row3["data"][0]["ponderacionTrim1"];
                    $row["data"][0]["detalle"]["ponderacionTrim2"] = $row3["data"][0]["ponderacionTrim2"];
                    $row["data"][0]["detalle"]["ponderacionTrim3"] = $row3["data"][0]["ponderacionTrim3"];
                    $row["data"][0]["detalle"]["ponderacionTrim4"] = $row3["data"][0]["ponderacionTrim4"];
                    $row["data"][0]["detalle"]["poblacionProgramada"] = $row3["data"][0]["poblacionProgramada"];
                    $row["data"][0]["detalle"]["poblacionABeneficiar"] = $row3["data"][0]["poblacionABeneficiar"];
                    $row["data"][0]["detalle"]["cantidadAnual"] = $row3["data"][0]["cantidadAnual"];
                }
            }
        }
        $json_encode = new Encode_JSON();
        $row = $json_encode->encode($row);
        return $row;
    }

    public function datatableGenerico($params, $param, $limit, $nombreTabla, $condiciones = "", $agrupacion = "", $orders = "") {
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
                $ban = 0;
                $ban2 = 0;
                foreach ($row["data"][$index] as $key => $value) {
                    if ($key == "cveEstatusPlaneacion" && $value == 23) {
                        $ban = 1;
                    } else if ($key == "cveEstatusFinanzas") {
                        $registro[] = "Solicitado";
                        if ($value == 29) {
                            $ban2 = 1;
                        }
                        if ($ban == 1 && $ban2 == 1) {
                            $registro[] = "Aceptado";
                        } else {
                            $registro[] = "Solicitado";
                        }
                    } else {
                        $registro[] = $value;
                    }
                }
                $data[] = $registro;
            }
            $output = array(
                "draw" => $params["draw"],
                "recordsTotal" => (int) $row["totalCount"],
                "recordsFiltered" => (int) $arrayTot["data"][0]["Total"],
                "start" => $limit["pag"],
                "length" => $limit["max"],
                "data" => $data);
            $json = new Encode_JSON();

            return $json->encode($output);
        } else {
            return json_encode(array("status" => "error", "totalCount" => 0, "msg" => "sin informacion a mostrar"));
        }
    }
    
    public function subirMir($id, $archivos) {
        $genericoDao = new GenericDAO();
        $this->proveedor->connect();
        $this->proveedor->execute("BEGIN");
        $sql = array();
            $sql["campos"] = "b.ruta,b.idImagen,a.idDocumentoImg";
            $sql["tablas"] = "tbldocumentosimg a inner join tblimagenes b on a.idDocumentoImg=b.idDocumentoImg";
            $sql["where"] = "a.activo='S' AND b.activo='S' AND a.cveTipoDocumento=3 AND a.idReferencia=".$id;

            $param = array("tabla" => "", "d" => "", "tmpSql" => $sql, "proveedor" => $this->proveedor);
            $bit = $genericoDao->select($param);
            
        $sql = array();
        
        $sql["campos"] = "di.*, td.cveTipoDocumento, td.descTipoDocumento, td.extension";
        $sql["tablas"] = "tbldocumentosimg di INNER JOIN tbltiposdocumentos td ON (di.cveTipoDocumento = td.cveTipoDocumento)";
        $sql["where"] = "di.activo='S' AND td.activo='S' AND di.cveTipoDocumento=3 AND di.idReferencia=".$id;
        
        $param = array("tabla" => "", "d" => "", "tmpSql" => $sql, "proveedor" => $this->proveedor);
        $tiposDocumentos = $genericoDao->select($param);
        $imagenesController = new ImagenesController();
        $archivo = false;
        $error = false;
        $msj = "";
        if($tiposDocumentos["totalCount"] == 0){
            $valores["numEmpleadoCarga"] = $_SESSION["NumEmpleado"];
            $valores["fojas"] = 1;
            $valores["cveTipoDocumento"] = 3;
            $valores["idReferencia"] = $id;
            $valores["activo"] = "S";
            $valores["fechaRegistro"] = "now()";
            $valores["fechaActualizacion"] = "now()";
            $d["values"] = $valores;
            $params = array("tabla" => "tbldocumentosimg", "d" => $d, "tmpSql" => "", "proveedor" => $this->proveedor);
            $documentoImagenes = $imagenesController->crearDocumentoImg($params,$proveedor);
        }else{
            $documentoImagenes=$tiposDocumentos;
        }
        if($documentoImagenes != 0){
            $archivo = $imagenesController->crearImagenes($archivos, $documentoImagenes,$this->proveedor);
        }else{
            $error = true;
            $msj = "No se subio el archivo";
        }
        if (!$error) {
            $array = array();
            $array["tabla"] = "tblbitacoramovimientos";
            $array["d"]["values"]["cveAccion"] = 37;
            $array["d"]["values"]["fechaMovimiento"] = 'now()';
            $array["d"]["values"]["observaciones"] = json_encode($bit);
            $array["d"]["values"]["cveUsuario"] = $_SESSION["cveUsuarioSistema"];
            $array["d"]["values"]["cvePerfil"] = $_SESSION["cvePerfil"];
            $array["d"]["values"]["cveAdscripcion"] = $_SESSION["cveAdscripcion"];
            $array["tmpSql"] = "";
            $array["proveedor"] = NULL;
            $insertarBitacora = $genericoDao->insert($array);
            $this->proveedor->execute("COMMIT");
            
            $sql = array();
            $sql["campos"] = "b.ruta,b.idImagen,b.descripcion,a.idDocumentoImg";
            $sql["tablas"] = "tbldocumentosimg a inner join tblimagenes b on a.idDocumentoImg=b.idDocumentoImg";
            $sql["where"] = "a.activo='S' AND b.activo='S' AND a.cveTipoDocumento=3 AND a.idReferencia=".$id;

            $param = array("tabla" => "", "d" => "", "tmpSql" => $sql, "proveedor" => $this->proveedor);
            $tiposDocumentos2 = $genericoDao->select($param);
            $codif=new Encode_JSON();
            $respuesta = $codif->encode($tiposDocumentos2);
        } else {
            $this->proveedor->execute("ROLLBACK");
            $respuesta = $msj;
        }
        $this->proveedor->close();
        return $respuesta;
    }
    
    public function subirDiagnostico($id, $archivos, $dec) {
        $sql = array();
        $genericoDao = new GenericDAO();
        $sql["campos"] = "di.*, td.cveTipoDocumento, td.descTipoDocumento, td.extension";
        $sql["tablas"] = "tbldocumentosimg di INNER JOIN tbltiposdocumentos td ON (di.cveTipoDocumento = td.cveTipoDocumento)";
        $sql["where"] = "di.activo='S' AND td.activo='S' AND di.cveTipoDocumento=1 AND di.idReferencia=" . $id;

        $param = array("tabla" => "", "d" => "", "tmpSql" => $sql, "proveedor" => null);
        $tiposDocumentos = $genericoDao->select($param);
        $imagenesController = new ImagenesController();
        $proveedor = new Proveedor('mysql', 'ADMINISTRATIVO');
        $proveedor->connect();
        $proveedor->execute("BEGIN");
        $archivo = false;
        $error = false;
        $msj = "";
        if ($tiposDocumentos["totalCount"] == 0) {
            $valores["cveUsuario"] = $_SESSION["cveUsuarioSistema"];
            $valores["descripcion"] = $dec;
            $valores["fojas"] = 1;
            $valores["cveTipoDocumento"] = 1;
            $valores["idReferencia"] = $id;
            $valores["activo"] = "S";
            $valores["fechaRegistro"] = "now()";
            $valores["fechaActualizacion"] = "now()";
            $d["values"] = $valores;
            $params = array("tabla" => "tbldocumentosimg", "accionBitacora" => "20", "d" => $d, "tmpSql" => "", "proveedor" => $proveedor);
            $documentoImagenes = $imagenesController->crearDocumentoImg($params, $proveedor);
        } else {
            $values["descripcion"] = $dec;
            $values["numEmpleadoCarga"] = $_SESSION['NumEmpleado'];
            $values["fechaActualizacion"] = "now()";
            $d["values"] = $values;
            $d["where"]["idDocumentoImg"] = $tiposDocumentos["data"][0]["idDocumentoImg"];
            $param = array("tabla" => "tbldocumentosimg", "d" => $d, "tmpSql" => "", "proveedor" => null);
            $genericoDao = new GenericDAO();
            $imagenes = $genericoDao->update($param);
            $documentoImagenes = $tiposDocumentos;
        }
        if ($documentoImagenes != 0) {
            $archivo = $imagenesController->crearImagenes($archivos, $documentoImagenes, $proveedor);
        } else {
            $error = true;
            $msj = "No se subio el archivo";
        }
        if (!$error) {

            $proveedor->execute("COMMIT");

            $sql = array();
            $sql["campos"] = "b.ruta,b.idImagen,b.descripcion,a.idDocumentoImg";
            $sql["tablas"] = "tbldocumentosimg a inner join tblimagenes b on a.idDocumentoImg=b.idDocumentoImg";
            $sql["where"] = "a.activo='S' AND b.activo='S' AND a.cveTipoDocumento=1 AND a.idReferencia=" . $id;

            $param = array("tabla" => "", "d" => "", "tmpSql" => $sql, "proveedor" => null);
            $tiposDocumentos2 = $genericoDao->select($param);

            $codif = new Encode_JSON();
            $respuesta = $codif->encode($tiposDocumentos2);
        } else {
            $proveedor->execute("ROLLBACK");
            $respuesta = $msj;
        }
        $proveedor->close();
        return $respuesta;
    }

    public function subirDiagnosticosinArchivo($id, $dec) {
        $sql = array();
        $genericoDao = new GenericDAO();
        $sql["campos"] = "di.*, td.cveTipoDocumento, td.descTipoDocumento, td.extension";
        $sql["tablas"] = "tbldocumentosimg di INNER JOIN tbltiposdocumentos td ON (di.cveTipoDocumento = td.cveTipoDocumento)";
        $sql["where"] = "di.activo='S' AND td.activo='S' AND di.cveTipoDocumento=1 AND di.idReferencia=" . $id;

        $param = array("tabla" => "", "d" => "", "tmpSql" => $sql, "proveedor" => null);
        $tiposDocumentos = $genericoDao->select($param);

        if ($tiposDocumentos["totalCount"] == 0) {
            $array = array();
            $values["descripcion"] = $dec;
            $values["numEmpleadoCarga"] = $_SESSION['numEmpleado'];
            $values["fojas"] = 1;
            $values["cveTipoDocumento"] = 1;
            $values["idReferencia"] = $id;
            $values["activo"] = "S";
            $values["fechaRegistro"] = "now()";
            $values["fechaActualizacion"] = "now()";
            $d["values"] = $values;
            $param = array("tabla" => "tbldocumentosimg", "accionBitacora" => "20", "d" => $d, "tmpSql" => "", "proveedor" => null);
            $genericoDao = new GenericDAO();
            $imagenes = $genericoDao->insert($param);
        } else {
            $values["descripcion"] = $dec;
            $values["numEmpleadoCarga"] = $_SESSION['NumEmpleado'];
            $values["fechaActualizacion"] = "now()";
            $d["values"] = $values;
            $d["where"]["idDocumentoImg"] = $tiposDocumentos["data"][0]["idDocumentoImg"];
            $param = array("tabla" => "tbldocumentosimg", "accionBitacora" => "22", "d" => $d, "tmpSql" => "", "proveedor" => null);
            $genericoDao = new GenericDAO();
            $imagenes = $genericoDao->update($param);
        }
        $encode = new Encode_JSON();
        return $encode->encode($imagenes);
    }

    public function modificarDiagnostico($id, $archivos, $dec, $cve, $activo) {
        $extension = end(explode(".", $_FILES['archivoDiagnostico']['name']));
        //obtenemos el archivo a subir
        $file = $archivos['archivoDiagnostico']['name'];
        //comprobamos si existe un directorio para subir el archivo
        //si no es así, lo creamos
        if (!is_dir("../../imagenes/documentos/diagnostico"))
            mkdir("../../imagenes/documentos/diagnostico", 0777);

        //comprobamos si el archivo ha subido
        $nombre = $archivos['archivoDiagnostico']['name'];
        $valor = $_FILES['archivoDiagnostico']['name'] . "_" . date("d-m-Y") . "_" . $id . "." . $extension;
        $rutaArchivo = "../../imagenes/documentos/diagnostico/" . $valor;
        $rutaArchivoValor = "../imagenes/documentos/diagnostico/" . $valor;
        if (move_uploaded_file($archivos['archivoDiagnostico']['tmp_name'], $rutaArchivo)) {
            sleep(3); //retrasamos la petición 3 segundos
            $array = array();
            $array["idReferencia"] = $id;
            $array["cveTipoDocumento"] = 1;
            $array["nombreArchivo"] = $nombre;
            $array["rutaArchivo"] = $rutaArchivoValor;
            $array["extencion"] = $extension;
            $array["cveUsuario"] = $_SESSION["cveUsuarioSistema"];
            $array["descripcion"] = $dec;
            $array["activo"] = 'S';
            $array["fechaActualizacion"] = "now()";
            $d["values"] = $array;
            $d["where"]["idDocumentoImg"] = $cve;
            $param = array("tabla" => "tbldocumentosimg", "accionBitacora" => "22", "d" => $d, "tmpSql" => "", "proveedor" => null);
            $genericDao = new GenericDAO();
            $genericArray = $genericDao->update($param);
            $json_encode = new Encode_JSON();
            $genericArray2 = $json_encode->encode($genericArray);
            return $genericArray2;
        } else {
            return '{"status":"error","msj":"No se pudo cargar el archivo intentalo de nuevo"}';
        }
    }
    
    public function subirArbol1($id, $archivos, $params){
        $genericoDao = new GenericDAO();
        
        $imagenesController = new ImagenesController();
        $proveedor = new Proveedor('mysql', 'ADMINISTRATIVO');
        $proveedor->connect();
        $proveedor->execute("BEGIN");
        $archivo = false;
        $error = false;
        $msj = "";
        $valores["numEmpleadoCarga"] = $_SESSION["NumEmpleado"];
            $valores["fojas"] = 1;
            $valores["cveTipoDocumento"] = $params["extrasPost"]["tipoDocumento"];
            $valores["idReferencia"] = $id;
            $valores["activo"] = "S";
            $valores["fechaRegistro"] = "now()";
            $valores["fechaActualizacion"] = "now()";
            $d["values"] = $valores;
            
            $params = array("tabla" => "tbldocumentosimg", "accionBitacora" => "233", "d" => $d, "tmpSql" => "", "proveedor" => $proveedor);
            $documentoImagenes = $imagenesController->crearDocumentoImg($params, $proveedor);
         
             if ($documentoImagenes != 0) {
                     $archivo = $imagenesController->crearImagenes($archivos, $documentoImagenes, $proveedor);
            
              
             } else {
                 
                    $error = true;
                    $msj = "No se subio el archivo";
            }
            
            if (!$error) {
            $proveedor->execute("COMMIT");
            $sql = array();
            $sql["campos"] = "b.ruta,b.idImagen,b.descripcion,a.idDocumentoImg";
            $sql["tablas"] = "tbldocumentosimg a inner join tblimagenes b on a.idDocumentoImg=b.idDocumentoImg";
            $sql["where"] = "a.activo='S' AND b.activo='S' AND a.cveTipoDocumento=".$valores["cveTipoDocumento"]." AND a.idReferencia=" . $id;

            $param = array("tabla" => "", "d" => "", "tmpSql" => $sql, "proveedor" => null);
            $tiposDocumentos2 = $genericoDao->select($param);
            $codif = new Encode_JSON();
            $respuesta = $codif->encode($tiposDocumentos2);
        } else {
            $proveedor->execute("ROLLBACK");
            $respuesta = $msj;
        }
        $proveedor->close();
        return $respuesta;

    }
    
    public function subirArbol($id, $archivos, $params) {
        switch ($params["extrasPost"]["tipoDocumento"]) {
            case 1:
                $cveTipoDocumento = 2;
                $idProyectoProgramatico=$params["extrasPost"]["idProyectoProgramatico4"];
            break;
            case 2:
                $cveTipoDocumento = 38;
                $idProyectoProgramatico=$params["extrasPost"]["idProyectoProgramatico6"];
            break;
            case 3:
                $cveTipoDocumento = 39;
                $idProyectoProgramatico=$params["extrasPost"]["idProyectoProgramatico7"];
            break;
            case 4:
                $cveTipoDocumento = 40;
                $idProyectoProgramatico=$params["extrasPost"]["idProyectoProgramatico8"];
            break;
            case 5:
                $cveTipoDocumento = 41;
                $idProyectoProgramatico=$params["extrasPost"]["idProyectoProgramatico9"];
            break;
        }
        $genericoDao = new GenericDAO();
        $sql = array();
        $sql["campos"] = "di.*, td.cveTipoDocumento, td.descTipoDocumento, td.extension";
        $sql["tablas"] = "tbldocumentosimg di INNER JOIN tbltiposdocumentos td ON (di.cveTipoDocumento = td.cveTipoDocumento)";
        $sql["where"] = "di.activo='S' AND td.activo='S' AND di.cveTipoDocumento=".$cveTipoDocumento." AND di.idReferencia=" . $idProyectoProgramatico;

        $param = array("tabla" => "", "d" => "", "tmpSql" => $sql, "proveedor" => null);
        $tiposDocumentos = $genericoDao->select($param);
        $imagenesController = new ImagenesController();
        $proveedor = new Proveedor('mysql', 'ADMINISTRATIVO');
        $proveedor->connect();
        $proveedor->execute("BEGIN");
        $archivo = false;
        $error = false;
        $msj = "";
        if ($tiposDocumentos["totalCount"] == 0) {
            $valores["numEmpleadoCarga"] = $_SESSION["NumEmpleado"];
            $valores["fojas"] = 1;
            $valores["cveTipoDocumento"] = $cveTipoDocumento;
            $valores["idReferencia"] = $idProyectoProgramatico;
            $valores["activo"] = "S";
            $valores["fechaRegistro"] = "now()";
            $valores["fechaActualizacion"] = "now()";
            $d["values"] = $valores;
            $params = array("tabla" => "tbldocumentosimg", "accionBitacora" => "17", "d" => $d, "tmpSql" => "", "proveedor" => $proveedor);
            $documentoImagenes = $imagenesController->crearDocumentoImg($params, $proveedor);
        } else {
            $documentoImagenes = $tiposDocumentos;
        }
        if ($documentoImagenes != 0) {
            $archivo = $imagenesController->crearImagenes($archivos, $documentoImagenes, $proveedor);
        } else {
            $error = true;
            $msj = "No se subio el archivo";
        }
        if (!$error) {
            $proveedor->execute("COMMIT");
            $sql = array();
            $sql["campos"] = "b.ruta,b.idImagen,b.descripcion,a.idDocumentoImg";
            $sql["tablas"] = "tbldocumentosimg a inner join tblimagenes b on a.idDocumentoImg=b.idDocumentoImg";
            $sql["where"] = "a.activo='S' AND b.activo='S' AND a.cveTipoDocumento=".$cveTipoDocumento." AND a.idReferencia=" . $idProyectoProgramatico;

            $param = array("tabla" => "", "d" => "", "tmpSql" => $sql, "proveedor" => null);
            $tiposDocumentos2 = $genericoDao->select($param);
            $codif = new Encode_JSON();
            $respuesta = $codif->encode($tiposDocumentos2);
        } else {
            $proveedor->execute("ROLLBACK");
            $respuesta = $msj;
        }
        $proveedor->close();
        return $respuesta;
    }

    public function enviarSolicitud($id) {
        $proveedor = new Proveedor('mysql', 'ADMINISTRATIVO');
        $proveedor->connect();
        $proveedor->execute("BEGIN");
        $error = false;
        $array = array();
        $numEmp = $this->getNumeroEmpleado($_SESSION["cveUsuarioSistema"]);
        $array["tabla"] = "tblproyectosprogramaticos";
        $array["cveEstatusPlaneacion"] = 24;
        $array["numEmpElaboroP"] = $numEmp;
        $array["fechaActualizacion"] = "now()";
        $array["d"]["where"]["idProyectoProgramatico"] = $id;
        $array["tmpSql"] = "";
        $array["proveedor"] = NULL;
        $d["values"] = $array;
        $d["where"]["idProyectoProgramatico"] = $id;
        $param = array("tabla" => "tblproyectosprogramaticos", "accionBitacora" => "23", "d" => $d, "tmpSql" => "", "proveedor" => null);
        $genericDao = new GenericDAO();

        $genericArray = $genericDao->update($param);
        unset($genericDao);
        unset($param);
        $json_encode = new Encode_JSON();
        $genericArray2 = $json_encode->encode($genericArray);
        if ($genericArray["totalCount"] != 0) {
            $noti = new SeguimientoProyectosController();
            $notificacion = array(
                "Origen" => $this->getAdscripcionPadre($_SESSION["cveAdscripcion"])["cveAdscripcion"],
                "Destino" => 853,
                "cveTipoNotificacion" => "1",
                "tituloNotificacion" => utf8_decode("Solicitó anteproyecto"),
                "descripcionNotificacion" => $_SESSION["desAdscripcion"] . utf8_decode(" envió una solicitud de anteproyecto programatíco ..."),
                "urlFormulario" => "vistas/planeacion/frmAdminAnteproyectoView.php",
            );
            $notif = $noti->notificar($notificacion, $proveedor);
        } else {
            $error = true;
        }
        if (!$error) {
            $proveedor->execute("COMMIT");
        } else {
            $proveedor->execute("ROLLBACK");
        }
        $proveedor->close();
        return $genericArray2;
    }

    public function historial($id) {
        $d = "";
        $sql = array("campos" => "idObservacionPlaneacion,comentarios,observaciones,fechaRegistro,cveUsuarioRechazo",
            "tablas" => "tblobservacionesplaneacion",
            "orders" => "idObservacionPlaneacion DESC",
            "where" => "idProyectoProgramatico=" . $id);

        $param = array("tabla" => "", "d" => $d, "tmpSql" => $sql, "proveedor" => null);
        $genericoDao = new GenericDAO();
        $datos = $genericoDao->select($param);
        $usuarioCliente = new UsuarioCliente();
        for ($x = 0; $x < $datos["totalCount"]; $x++) {
            $datos["data"][$x]["usuario"] = array();
            $usu = $usuarioCliente->getUsuarios($datos["data"][$x]["cveUsuarioRechazo"]);
            $usu = json_decode($usu, true);
            $usuario = $usu["data"][0]["nombre"] . " " . $usu["data"][0]["paterno"] . " " . $usu["data"][0]["materno"];
            $datos["data"][$x]["usuario"]["cveUsuario"] = $usu["data"][0]["cveUsuario"];
            $datos["data"][$x]["usuario"]["nombre"] = $usuario;
        }
        $json_encode = new Encode_JSON();
        return $genericArray2 = $json_encode->encode($datos);
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

    public function consultarDatosMir($params) {
        $genericoDao = new GenericDAO();
        $d = array("limit" => "");

        $sql = array("campos" => "a.`claveProyecto`,a.`desProyectoPresupuestal`,a.`cveUnidadResponsable`,a.`fechaRegistro`,a.fin,b.desIndicadorAsociado,b.desFormula,b.cveFrecuenciaMedicion,b.mediosVerificacion,b.supuestos,c.desFrecuenciaMedicion",
            "tablas" => "tblproyectospresupuestales a INNER JOIN tblindicadoresasociados b ON a.cveIndicadorAsociado=b.cveIndicadorAsociado INNER JOIN tblfrecuenciasmedicion c ON b.cveFrecuenciaMedicion=c.cveFrecuenciaMedicion",
            "orders" => "",
            "where" => "a.activo='S' AND b.activo='S' AND a.idProyectoPresupuestal=" . $_GET["idProyectoPresupuestal"] . "");

        $param = array("tabla" => "", "d" => $d, "tmpSql" => $sql, "proveedor" => null);
        $row = $genericoDao->select($param);
        if ($row["totalCount"] > 0) {
            $row["data"][0]["proposito"] = array();
            $row["data"][0]["componentes"] = array();
            $d = "";

            $sql = array("campos" => "b.cveEpProposito, b.desEpProposito,c.desIndicadorAsociado,c.desFormula,c.cveFrecuenciaMedicion,c.mediosVerificacion,c.supuestos,d.desFrecuenciaMedicion",
                "tablas" => "tbleppropositos b INNER JOIN tblindicadoresasociados c ON b.cveIndicadorAsociado=c.cveIndicadorAsociado INNER JOIN tblfrecuenciasmedicion d ON c.cveFrecuenciaMedicion=d.cveFrecuenciaMedicion",
                "orders" => "",
                "where" => "b.`activo`='S' AND b.idProyectoPresupuestal=" . $_GET["idProyectoPresupuestal"] . "");

            $param = array("tabla" => "", "d" => $d, "tmpSql" => $sql, "proveedor" => null);
            $row2 = $genericoDao->select($param);
            if ($row2["totalCount"] > 0) {
                foreach ($row2["data"] as $key => $value) {
                    foreach ($value as $key2 => $value2) {
                        $row["data"][0]["proposito"][$key][$key2] = $value2;
                    }
                }
            }

            $d = "";

            $sql = array("campos" => "d.`desProyectoProgramatico`,b.`desIndicadorAsociado`,b.`desFormula`,c.`desFrecuenciaMedicion`,b.`mediosVerificacion`,b.`supuestos`",
                "tablas" => "tblproyectosprogramaticos  d INNER JOIN tblindicadoresproyectos a ON d.idProyectoProgramatico=a.idProyectoProgramatico INNER JOIN tblindicadoresasociados b ON a.`cveIndicadorAsociado`=b.`cveIndicadorAsociado`  INNER JOIN tblfrecuenciasmedicion c ON b.`cveFrecuenciaMedicion`=c.`cveFrecuenciaMedicion`",
                "orders" => "",
                "where" => "a.`activo`='S' AND b.`activo`='S' AND d.`activo`='S' AND a.`idProyectoProgramatico`=" . $_GET["idProyectoProgramatico"] . "");

            $param = array("tabla" => "", "d" => $d, "tmpSql" => $sql, "proveedor" => null);
            $row2 = $genericoDao->select($param);
            if ($row2["totalCount"] > 0) {
                foreach ($row2["data"] as $key => $value) {
                    foreach ($value as $key2 => $value2) {
                        $row["data"][0]["componentes"][$key][$key2] = $value2;
                    }
                }
            }
            $d = "";
            $sql = array("campos" => "a.`desAccionProyecto`,c.`desIndicadorAsociado`,c.`desFormula`,c.`mediosVerificacion`,c.`supuestos`,d.`desFrecuenciaMedicion`",
                "tablas" => "tblaccionesproyecto a INNER JOIN tblindicadoresacciones b ON a.`idAccionProyecto`=b.`idAccionProyecto` INNER JOIN tblindicadoresasociados c ON b.`cveIndicadorAsociado`=c.`cveIndicadorAsociado` INNER JOIN tblfrecuenciasmedicion d ON c.`cveFrecuenciaMedicion`=d.`cveFrecuenciaMedicion` ",
                "orders" => "",
                "where" => "a.`activo`='S' AND b.`activo`='S' AND a.`idProyectoProgramatico`=" . $_GET["idProyectoProgramatico"] . "");

            $param = array("tabla" => "", "d" => $d, "tmpSql" => $sql, "proveedor" => null);
            $rowInd = $genericoDao->select($param);
            $rowInd["data"][0]["indicadoresAcciones"] = array();
            if ($rowInd["totalCount"] > 0) {
                foreach ($rowInd["data"] as $key7 => $value7) {
                    foreach ($value7 as $key8 => $value8) {
                        $row["data"][0]["indicadoresAcciones"][$key7][$key8] = $value8;
                    }
                }
            }
        }
        return $row;
    }

    public function getNumeroEmpleado($usu = null) {
        $fileJson = "../../archivos/" . $usu . ".json";
        if (file_exists($fileJson)) {
            $json = file_get_contents($fileJson);
            $json = json_decode($json, true);
            $buscarPadre = false;
            $cadenaBuscarPadre = "";
            if ($json != "") {
                return $json["numEmpleado"];
            } else {
                return "no se pudo obtener la Adscripción";
            }
        } else {
            return "no existe";
        }
    }

    public function getNombreEmpleado($usu = null) {
        $usuarioCliente = new UsuarioCliente();
        $usu = $usuarioCliente->getUsuarios($usu);
        $usu = json_decode($usu, true);
        $usuario = $usu["data"][0]["nombre"] . " " . $usu["data"][0]["paterno"] . " " . $usu["data"][0]["materno"];
        return $usuario;
    }

    public function modalIndicador($params) {
        $genericoDao = new GenericDAO();
        //datos del proyecto programatico
        $d = array("limit" => "");

        $sql = array("campos" => "a.`desProyectoProgramatico`,b.`desPdeIdeal`,c.`desPdeEstrategia`,d.`desPdeLineaAccion`,b.`reto`",
            "tablas" => "tblproyectosprogramaticos a INNER JOIN tblpdeideales b ON a.cvePdeIdeal=b.`cvePdeIdeal` INNER JOIN tblpdeestrategias c ON a.cvePdeEstrategia=c.`cvePdeEstrategia` INNER JOIN tblpdelineasaccion d ON a.cvePdeLineaAccion=d.`cvePdeLineaAccion` ",
            "orders" => "",
            "where" => "a.`activo`='S' AND a.`idProyectoProgramatico`=" . $params["extrasPost"]["idProyectoProgramatico"]);

        $param = array("tabla" => "", "d" => $d, "tmpSql" => $sql, "proveedor" => null);
        $row = $genericoDao->select($param);

        //datos del proyecto presupuestal
        $d = array("limit" => "");

        $sql = array("campos" => "a.`desProyectoPresupuestal`,b.`desEpPrograma`",
            "tablas" => "tblproyectospresupuestales a INNER JOIN tblepprogramas b ON a.`cveEpPrograma`=b.`cveEpPrograma`",
            "orders" => "",
            "where" => "a.`idProyectoPresupuestal`=" . $params["extrasPost"]["idProyectoPresupuestal"]);

        $param = array("tabla" => "", "d" => $d, "tmpSql" => $sql, "proveedor" => null);
        $row2 = $genericoDao->select($param);
        foreach ($row2["data"] as $key => $value) {
            foreach ($value as $key2 => $value2) {
                $row["data"][0][$key2] = $value2;
            }
        }
        if ($params["extrasPost"]["tipo"] == 1) {
            //datos del indicador
            $d = array("limit" => "");

            $sql = array("campos" => "b.cveIndicadorAsociado,b.`desIndicadorAsociado`,b.`formula`,b.`interpretacion`,e.`desDimension`,d.`desFrecuenciaMedicion`,b.`valFactorComparacion`,c.`desAmbitoGeografico`,b.`desCobertura`,a.cantidadAnual,a.ponderacionTotal1,a.ponderacionTotal2,a.ponderacionTotal3,a.ponderacionTotal4,b.`desMetaAnual`",
                "tablas" => "tblindicadoresproyectos a INNER JOIN tblindicadoresasociados b ON a.`cveIndicadorAsociado`=b.`cveIndicadorAsociado` inner join tblambitosgeograficos c on b.`cveAmbitoGeografico`=c.`cveAmbitoGeografico` inner join tblfrecuenciasmedicion d on b.`cveFrecuenciaMedicion`=d.`cveFrecuenciaMedicion` inner join tbldimensiones e on b.`cveDimension`=e.`cveDimension`",
                "orders" => "",
                "where" => "a.activo='S' AND b.`activo`='S' AND c.`activo`='S' AND d.`activo`='S' AND e.`activo`='S' AND a.`idProyectoProgramatico`=" . $params["extrasPost"]["id"]);

            $param = array("tabla" => "", "d" => $d, "tmpSql" => $sql, "proveedor" => null);
            $row3 = $genericoDao->select($param);
            if ($row3["totalCount"] > 0) {
                foreach ($row3["data"] as $key3 => $value3) {
                    foreach ($value3 as $key4 => $value4) {
                        $row["data"][0][$key4] = $value4;
                        if ($key4 == "cveIndicadorAsociado") {
                            $d = array("limit" => "");
                            $sql = array("campos" => "b.`nombreVariable`,b.`desVariable`,c.`desUnidadMedida`,a.metaAnual,a.ponderacionTotal1,a.ponderacionTotal2,a.ponderacionTotal3,a.ponderacionTotal4",
                                "tablas" => "tblindicadoresvariables a INNER JOIN tblvariables	b ON a.idVariable=b.idVariable INNER JOIN tblunidadesmedida c ON b.cveUnidadMedida=c.`cveUnidadMedida`",
                                "orders" => "",
                                "where" => "a.activo='S' AND b.`activo`='S' AND c.`activo`='S' AND a.cveIndicadorAsociado=" . $value4);

                            $param = array("tabla" => "", "d" => $d, "tmpSql" => $sql, "proveedor" => null);
                            $row4 = $genericoDao->select($param);
                            if ($row4["totalCount"] > 0) {
                                foreach ($row4["data"] as $key5 => $value5) {
                                    foreach ($value5 as $key6 => $value6) {
                                        $row["data"][0]["variables"][$key5][$key6] = $value6;
                                    }
                                }
                            }
                        }
                    }
                }
            }
        } else {
            $d = array("limit" => "");

            $sql = array("campos" => "b.cveIndicadorAsociado,b.`desIndicadorAsociado`,b.`formula`,b.`interpretacion`,e.`desDimension`,d.`desFrecuenciaMedicion`,b.`valFactorComparacion`,c.`desAmbitoGeografico`,b.`desCobertura`,a.cantidadAnual,a.ponderacionTotal1,a.ponderacionTotal2,a.ponderacionTotal3,a.ponderacionTotal4,b.`desMetaAnual`",
                "tablas" => "tblindicadoresacciones a INNER JOIN tblindicadoresasociados b ON a.`cveIndicadorAsociado`=b.`cveIndicadorAsociado` INNER JOIN tblambitosgeograficos c ON b.`cveAmbitoGeografico`=c.`cveAmbitoGeografico` INNER JOIN tblfrecuenciasmedicion d ON b.`cveFrecuenciaMedicion`=d.`cveFrecuenciaMedicion` INNER JOIN tbldimensiones e ON b.`cveDimension`=e.`cveDimension`",
                "orders" => "",
                "where" => "a.activo='S' AND b.`activo`='S' AND c.`activo`='S' AND d.`activo`='S' AND e.`activo`='S' AND a.`idAccionProyecto`=" . $params["extrasPost"]["id"]);

            $param = array("tabla" => "", "d" => $d, "tmpSql" => $sql, "proveedor" => null);
            $row3 = $genericoDao->select($param);
            if ($row3["totalCount"] > 0) {
                foreach ($row3["data"] as $key3 => $value3) {
                    foreach ($value3 as $key4 => $value4) {
                        $row["data"][0][$key4] = $value4;
                        if ($key4 == "cveIndicadorAsociado") {
                            $d = array("limit" => "");
                            $sql = array("campos" => "b.`nombreVariable`,b.`desVariable`,c.`desUnidadMedida`,a.metaAnual,a.ponderacionTotal1,a.ponderacionTotal2,a.ponderacionTotal3,a.ponderacionTotal4",
                                "tablas" => "tblindicadoresvariables a INNER JOIN tblvariables	b ON a.idVariable=b.idVariable INNER JOIN tblunidadesmedida c ON b.cveUnidadMedida=c.`cveUnidadMedida`",
                                "orders" => "",
                                "where" => "a.activo='S' AND b.`activo`='S' AND c.`activo`='S' AND a.cveIndicadorAsociado=" . $value4);

                            $param = array("tabla" => "", "d" => $d, "tmpSql" => $sql, "proveedor" => null);
                            $row4 = $genericoDao->select($param);
                            if ($row4["totalCount"] > 0) {
                                foreach ($row4["data"] as $key5 => $value5) {
                                    foreach ($value5 as $key6 => $value6) {
                                        $row["data"][0]["variables"][$key5][$key6] = $value6;
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
        $encode = new Encode_JSON();
        return $encode->encode($row);
    }

    public function getAdscripcionNombre($ads = null) {
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
                            return array(
                                "cveOrganigrama" => utf8_decode($value["cveOrganigrama"]),
                                "desAdscripcion" => utf8_decode($value["desJuz"]),
                                "idAdscripcion" => utf8_decode($value["idJuzgado"])
                            );
                        }
                    }
                }
            } else {
                return "no se pudo obtener la Adscripción";
            }
        } else {
            return "no existe";
        }
    }

    public function modalIndicadorPDF($idProyectoProgramatico, $idProyectoPresupuestal, $tipo, $id) {
        $genericoDao = new GenericDAO();
        //datos del proyecto programatico
        $d = array("limit" => "");

        $sql = array("campos" => "a.`desProyectoProgramatico`,b.`desPdeIdeal`,c.`desPdeEstrategia`,d.`desPdeLineaAccion`,b.`reto`",
            "tablas" => "tblproyectosprogramaticos a INNER JOIN tblpdeideales b ON a.cvePdeIdeal=b.`cvePdeIdeal` INNER JOIN tblpdeestrategias c ON a.cvePdeEstrategia=c.`cvePdeEstrategia` INNER JOIN tblpdelineasaccion d ON a.cvePdeLineaAccion=d.`cvePdeLineaAccion` ",
            "orders" => "",
            "where" => "a.`activo`='S' AND a.`idProyectoProgramatico`=" . $idProyectoProgramatico);

        $param = array("tabla" => "", "d" => $d, "tmpSql" => $sql, "proveedor" => null);
        $row = $genericoDao->select($param);

        //datos del proyecto presupuestal
        $d = array("limit" => "");

        $sql = array("campos" => "a.`desProyectoPresupuestal`,b.`desEpPrograma`",
            "tablas" => "tblproyectospresupuestales a INNER JOIN tblepprogramas b ON a.`cveEpPrograma`=b.`cveEpPrograma`",
            "orders" => "",
            "where" => "a.`idProyectoPresupuestal`=" . $idProyectoPresupuestal);

        $param = array("tabla" => "", "d" => $d, "tmpSql" => $sql, "proveedor" => null);
        $row2 = $genericoDao->select($param);
        foreach ($row2["data"] as $key => $value) {
            foreach ($value as $key2 => $value2) {
                $row["data"][0][$key2] = $value2;
            }
        }
        if ($tipo == 1) {
            //datos del indicador
            $d = array("limit" => "");

            $sql = array("campos" => "b.cveIndicadorAsociado,b.`desIndicadorAsociado`,b.`formula`,b.`interpretacion`,e.`desDimension`,d.`desFrecuenciaMedicion`,b.`valFactorComparacion`,c.`desAmbitoGeografico`,b.`desCobertura`,a.cantidadAnual,a.ponderacionTotal1,a.ponderacionTotal2,a.ponderacionTotal3,a.ponderacionTotal4,b.`desMetaAnual`",
                "tablas" => "tblindicadoresproyectos a INNER JOIN tblindicadoresasociados b ON a.`cveIndicadorAsociado`=b.`cveIndicadorAsociado` inner join tblambitosgeograficos c on b.`cveAmbitoGeografico`=c.`cveAmbitoGeografico` inner join tblfrecuenciasmedicion d on b.`cveFrecuenciaMedicion`=d.`cveFrecuenciaMedicion` inner join tbldimensiones e on b.`cveDimension`=e.`cveDimension`",
                "orders" => "",
                "where" => "a.activo='S' AND b.`activo`='S' AND c.`activo`='S' AND d.`activo`='S' AND e.`activo`='S' AND a.`idProyectoProgramatico`=" . $id);

            $param = array("tabla" => "", "d" => $d, "tmpSql" => $sql, "proveedor" => null);
            $row3 = $genericoDao->select($param);
            if ($row3["totalCount"] > 0) {
                foreach ($row3["data"] as $key3 => $value3) {
                    foreach ($value3 as $key4 => $value4) {
                        $row["data"][0][$key4] = $value4;
                        if ($key4 == "cveIndicadorAsociado") {
                            $d = array("limit" => "");
                            $sql = array("campos" => "b.`nombreVariable`,b.`desVariable`,c.`desUnidadMedida`,a.metaAnual,a.ponderacionTotal1,a.ponderacionTotal2,a.ponderacionTotal3,a.ponderacionTotal4",
                                "tablas" => "tblindicadoresvariables a INNER JOIN tblvariables b ON a.idVariable=b.idVariable INNER JOIN tblunidadesmedida c ON b.cveUnidadMedida=c.`cveUnidadMedida`",
                                "orders" => "",
                                "where" => "a.activo='S' AND b.`activo`='S' AND c.`activo`='S' AND a.cveIndicadorAsociado=" . $value4);

                            $param = array("tabla" => "", "d" => $d, "tmpSql" => $sql, "proveedor" => null);
                            $row4 = $genericoDao->select($param);
                            if ($row4["totalCount"] > 0) {
                                foreach ($row4["data"] as $key5 => $value5) {
                                    foreach ($value5 as $key6 => $value6) {
                                        $row["data"][0]["variables"][$key5][$key6] = $value6;
                                    }
                                }
                            }
                        }
                    }
                }
            }
        } else {
            $d = array("limit" => "");

            $sql = array("campos" => "b.cveIndicadorAsociado,b.`desIndicadorAsociado`,b.`formula`,b.`interpretacion`,e.`desDimension`,d.`desFrecuenciaMedicion`,b.`valFactorComparacion`,c.`desAmbitoGeografico`,b.`desCobertura`,a.cantidadAnual,a.ponderacionTotal1,a.ponderacionTotal2,a.ponderacionTotal3,a.ponderacionTotal4,b.`desMetaAnual`",
                "tablas" => "tblindicadoresacciones a INNER JOIN tblindicadoresasociados b ON a.`cveIndicadorAsociado`=b.`cveIndicadorAsociado` INNER JOIN tblambitosgeograficos c ON b.`cveAmbitoGeografico`=c.`cveAmbitoGeografico` INNER JOIN tblfrecuenciasmedicion d ON b.`cveFrecuenciaMedicion`=d.`cveFrecuenciaMedicion` INNER JOIN tbldimensiones e ON b.`cveDimension`=e.`cveDimension`",
                "orders" => "",
                "where" => "a.activo='S' AND b.`activo`='S' AND c.`activo`='S' AND d.`activo`='S' AND e.`activo`='S' AND a.`idAccionProyecto`=" . $id);

            $param = array("tabla" => "", "d" => $d, "tmpSql" => $sql, "proveedor" => null);
            $row3 = $genericoDao->select($param);
            if ($row3["totalCount"] > 0) {
                foreach ($row3["data"] as $key3 => $value3) {
                    foreach ($value3 as $key4 => $value4) {
                        $row["data"][0][$key4] = $value4;
                        if ($key4 == "cveIndicadorAsociado") {
                            $d = array("limit" => "");
                            $sql = array("campos" => "b.`nombreVariable`,b.`desVariable`,c.`desUnidadMedida`,a.metaAnual,a.ponderacionTotal1,a.ponderacionTotal2,a.ponderacionTotal3,a.ponderacionTotal4",
                                "tablas" => "tblindicadoresvariables a INNER JOIN tblvariables b ON a.idVariable=b.idVariable INNER JOIN tblunidadesmedida c ON b.cveUnidadMedida=c.`cveUnidadMedida`",
                                "orders" => "",
                                "where" => "a.activo='S' AND b.`activo`='S' AND c.`activo`='S' AND a.cveIndicadorAsociado=" . $value4);

                            $param = array("tabla" => "", "d" => $d, "tmpSql" => $sql, "proveedor" => null);
                            $row4 = $genericoDao->select($param);
                            if ($row4["totalCount"] > 0) {
                                foreach ($row4["data"] as $key5 => $value5) {
                                    foreach ($value5 as $key6 => $value6) {
                                        $row["data"][0]["variables"][$key5][$key6] = $value6;
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
        return $row;
    }

}
