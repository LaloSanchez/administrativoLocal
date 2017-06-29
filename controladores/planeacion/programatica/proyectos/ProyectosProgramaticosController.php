<?php

include_once(dirname(__FILE__) . "../../../../../modelos/dao/GenericDAO.Class.php");

include_once(dirname(__FILE__) . "../../../../../tribunal/json/JsonEncod.Class.php");
include_once(dirname(__FILE__) . "../../../../../tribunal/host/Host.Class.php");
include_once(dirname(__FILE__) . "../../../../../tribunal/connect/Proveedor.Class.php");
include_once(dirname(__FILE__) . "../../../../../webservice/cliente/personal/PersonalCliente.php");
include_once(dirname(__FILE__) . "../../../../../controladores/bitacora/BitacoraController.Class.php");
include_once(dirname(__FILE__) . "../../../../../controladores/planeacion/SeguimientoProyectosController.Class.php");

/**
 * Clase generica que permite consultar 
 * los clasificadores 
 *
 * @author Augusto Fonseca
 */
class ProyectosProgramaticosController {
    public function guardarBitacora($accion, $nuevo, $anterior = null, $proveedor = null)
    {
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

    // Selecciona todos los proyectos presupuestales
    public function getProyectosPresupuestales()
    {
        $genenericDAO = new GenericDAO();
        /*
        Ejemplo genericdao
        $param = array(
            "tabla" => "tblproyectospresupuestales",
            "d" => array(
                    'campos' => '*',
                    'where' => array(
                        'idProyectoPresupuestal' => 100
                    )
            ),
            "tmpSql" => array()
        );*/
        $param = array(
            "tabla" => "tblproyectospresupuestales",
            "d" => array(
                    'campos' => '*',
                    'where' => array(
                        'activo' => 'S'
                    ),
            ),
            "tmpSql" => array()
        );

        // $pP = proyectos presupuestales 
        $pP = $genenericDAO->select($param);

        return json_encode($pP);

    }
    public function getProyectosProgramaticosDataTable($params)
    {
        $limit = array("max" => $params["limit"]["max"],
            "pag" => $params["limit"]["pag"]);
        $d = array("limit" => $limit);

        $sql = array(
            "campos" => 
                "PP.idProyectoProgramatico,PP.anioProyectoProgramatico,PP.fechaInicio,PP.fechaTermino,PP.desProyectoProgramatico,PP.Objetivo,ESF.desEstatus AS desEstatusFinanzas,ESP.desEstatus AS desEstatusPlaneacion",
            "tablas" => 
                "tblproyectosprogramaticos as PP, tblestatus as ESP, tblestatus as ESF, tblproyectosadscripciones as PA",
            "orders" => $params["order"]["column"] . " " . $params["order"]["dir"],
            "where" => 
                ' PP.cveEstatusFinanzas = ESF.cveEstatus AND PP.cveEstatusPlaneacion = ESP.cveEstatus AND PP.activo = "S" AND PP.idProyectoProgramatico = PA.idProyectoProgramatico AND PA.cveAdscripcion = '.$_SESSION['cveAdscripcion']
        );

        if ($params['search']['value'] != "") {
            $arrayCampos = explode(",", $sql["campos"]);
            foreach ($arrayCampos as $key => $value) {
                // para incluir alias en la busqueda es importante que $sql['campos'] de arriba no tenga espacios entre campo y campo
                $value_tmp = explode(' ', $value);
                if ($key == 0)
                    $sql["where"] .= " AND ( " . $value_tmp[0] . " like '%" . $params['search']['value'] . "%' ";
                else
                    $sql["where"] .= " OR " . $value_tmp[0] . " like '%" . $params['search']['value'] . "%' ";
            }
            $sql["where"] .= " ) ";
        }
        $param = array("tabla" => "", "d" => $d, "tmpSql" => $sql, "proveedor" => null);
        return $this->datatableGenerico($params, $param, $limit, $nombreTabla = $sql['tablas'], $sql['where']);
    }

        public function saveProyectoProgramatico($formData)
    {
        $proveedor = new Proveedor('mysql', 'ADMINISTRATIVO');
        $proveedor->connect();
        $proveedor->execute("BEGIN");
        $status = 'success';
        $mensaje = '';

        // PROYECTO
        // guardar en tblproyectoestrategico
        $jsonDecode = new Decode_JSON();
        $formData = $jsonDecode->decode($formData);
        $genenericDAO = new GenericDAO();
        $saveProyecto = array(
            "tabla" => "tblproyectosprogramaticos",
            "accionBitacora" => "106",
            "d" => array(
                "values" => array(
                    "cvePdeIdeal" => $formData->cvePdeIdeal,
                    "cvePdeEstrategia" => $formData->cvePdeEstrategia,
                    "cvePdeLineaAccion" => $formData->cvePdeLineaAccion,
                    "desProyectoProgramatico" => $formData->desProyectoProgramatico,
                    "objetivo" => $formData->objetivo,
                    "estrategiaProyecto" => $formData->estrategiaProyecto,
                    "metaProyecto" => $formData->metaProyecto,
                    "fechaInicio" => date_format(date_create_from_format('d/m/Y',$formData->fechaInicio),'Y-m-d'),
                    "fechaTermino" => date_format(date_create_from_format('d/m/Y',$formData->fechaTermino),'Y-m-d'),
                    "numEmpleadoResponsable" => $formData->numEmpleadoResponsable,
                    "cveAdscripcionResp" => $formData->cveAdscripcionResp,
                    "cveOrganigramaResponsable" => $formData->cveOrganigramaResponsable,
                    "anioProyectoProgramatico" => $formData->anioProyectoProgramatico,
                    "cveEstatusFinanzas" => '25',
                    "cveEstatusPlaneacion" => '16',
                    "cveEstadoProyecto" => '1',
                    "activo" => 'S',
                    "fechaRegistro" => "now()",
                    "fechaActualizacion" => "now()"
                )
            ),
            "proveedor" => $proveedor
        );
        $saveProyectoResult = $genenericDAO->insert($saveProyecto);

        if ($saveProyectoResult['status'] == 'success' && $saveProyectoResult['totalCount'] > 0) {
            // acciones? todo bien!
            // insertar la adscripcion en tblproyectosadscripciones
            $genericoDao = new GenericDAO();
            $saveAdscripcionInvolucrada = array(
                "tabla" => "tblproyectosadscripciones",
                "accionBitacora" => "137",
                "d" => array(
                    "values" => array(
                        "idProyectoProgramatico" => $saveProyectoResult['data'][0]['idProyectoProgramatico'],
                        "cveOrganigrama" => $formData->cveOrganigramaResponsable,
                        "cveAdscripcion" => $formData->cveAdscripcionResp,
                        "vistoBueno" => 'N',
                        "activo" => 'S',
                        "fechaRegistro" => "now()",
                        "fechaActualizacion" => "now()"
                    )
                ),
                "proveedor" => $proveedor
            );
            $saveAdscripcionInvolucradaResult = $genenericDAO->insert($saveAdscripcionInvolucrada);
            if ($saveAdscripcionInvolucradaResult['status'] == 'success' && $saveAdscripcionInvolucradaResult['totalCount'] > 0) {

            }else{
                $mensaje .= 'Ocurrio un error al insertar en tblproyectosadscripciones. ';
                $status = 'error';
            }
        }else{
            $mensaje .= 'Ocurrio un error al insertar en tblproyectosprogramaticos. ';
            $status = 'error';
        }
        //var_dump($saveProyectoResult);
        $saveProyectoResultData = $saveProyectoResult['data'][0];

        /*// UNIDADES INVOLUCRADAS
        if ($status == 'success') {
            // guardar unidades involucradas en tblunidadesinvolucradas
            foreach ($formData->unidadesInvolucradas as $key => $unidad) {
                $genenericDAO = new GenericDAO();
                $saveUnidad = array(
                    "tabla" => "tblproyectosadscripciones",
                    "accionBitacora" => "137",
                    "d" => array(
                        "values" => array(
                            "idProyectoProgramatico" => $saveProyectoResultData['idProyectoProgramatico'],
                            "cveOrganigrama" => $unidad->cveOrganigrama,
                            "cveAdscripcion" => $unidad->cveAdscripcion,
                            "vistoBueno" => 'N',
                            "activo" => 'S',
                            "fechaRegistro" => "now()",
                            "fechaActualizacion" => "now()"
                        )
                    ),
                    "proveedor" => $proveedor
                );
                $saveUnidadResult = $genenericDAO->insert($saveUnidad);
                if ($saveUnidadResult['status'] == 'success' && $saveUnidadResult['totalCount'] > 0) {
                    // acciones? todo bien!
                    // si la unidad es diferente de la que esta en sesion, mandar notificacion
                    if ($_SESSION['cveAdscripcion'] != $unidad->cveAdscripcion) {
                        $Notificacion = new SeguimientoProyectosController();
                        $noti = array(
                            'Origen' => $_SESSION['cveAdscripcion'], 
                            'Destino' => $unidad->cveAdscripcion, 
                            'cveTipoNotificacion' => 1, 
                            'tituloNotificacion' => 'Se registr&oacute; un proyecto program&aacute;tico', 
                            'descripcionNotificacion' => 'La '.$_SESSION['desAdscripcion'].' agreg&oacute; un proyecto program&aacute;tico que involucra a tu adscripci&oacute;n', 
                            'urlFormulario' => '#noir', 
                        );
                        $Notificacion->notificar($noti,$proveedor);
                    }
                }else{
                    $mensaje .= 'Ocurrio un error al insertar en tblunidadesinvolucradas. ';
                    $status = 'error';
                }
            }
        }*/

        // ACCIONES
        if ($status == 'success') {
            // guardar acciones en tblaccionesproyecto
            foreach ($formData->acciones as $key => $accion) {
                $genenericDAO = new GenericDAO();
                $saveAccion = array(
                    "tabla" => "tblaccionesproyecto",
                    "accionBitacora" => "109",
                    "d" => array(
                        "values" => array(
                            "idProyectoProgramatico" => $saveProyectoResultData['idProyectoProgramatico'],
                            "desAccionProyecto" => $accion->desAccionProyecto,
                            "numEmpleadoACargo" => $accion->numEmpleadoACargo,
                            "cveAdscripcion" => $accion->cveAdscripcion,
                            "cveOrganigrama" => $accion->cveOrganigrama,
                            "fechaInicio" => date_format(date_create_from_format('d/m/Y',$accion->fechaInicio),'Y-m-d'),
                            "fechaFin" => date_format(date_create_from_format('d/m/Y',$accion->fechaFin),'Y-m-d'),
                            "ponderacion" => $accion->ponderacion,
                            "AvanceAcumulado" => 0,
                            "nomenclatura" => $saveProyectoResultData['idProyectoProgramatico'].'-'.($key+1),
                            "activo" => 'S',
                            "fechaRegistro" => "now()",
                            "fechaActualizacion" => "now()"
                        )
                    ),
                    "proveedor" => $proveedor
                );
                $saveAccionResult = $genenericDAO->insert($saveAccion);
                if ($saveAccionResult['status'] == 'success' && $saveAccionResult['totalCount'] > 0) {
                    // acciones? todo bien!
                    // guardar tambien en proyectosadscripciones
                    // verificar si no existe
                    $genericoDao = new GenericDAO();
                    $d = array();
                    $sql = array(
                        "campos" => 
                            "*",
                        "tablas" => 
                            "tblproyectosadscripciones",
                        "where" => 
                            ' idProyectoProgramatico = '.$saveProyectoResultData['idProyectoProgramatico'].' AND cveAdscripcion = '.$accion->cveAdscripcion.' AND activo = "S" '
                    );
                    $sqlSelect = array("tabla" => "", "d" => $d, "tmpSql" => $sql, "proveedor" => $proveedor);
                    $rs = $genericoDao->select($sqlSelect);
                    $existeAdscripcionRs = $rs;

                    if ($existeAdscripcionRs['status'] == 'success' && $existeAdscripcionRs['totalCount'] > 0) {
                        // ya existe, no es necesario volver a insertar
                    }else{
                        // insertar, no existe
                        $genericoDao = new GenericDAO();
                        $saveAdscripcionInvolucrada = array(
                            "tabla" => "tblproyectosadscripciones",
                            "accionBitacora" => "137",
                            "d" => array(
                                "values" => array(
                                    "idProyectoProgramatico" => $saveProyectoResultData['idProyectoProgramatico'],
                                    "cveOrganigrama" => $accion->cveOrganigrama,
                                    "cveAdscripcion" => $accion->cveAdscripcion,
                                    "vistoBueno" => 'N',
                                    "activo" => 'S',
                                    "fechaRegistro" => "now()",
                                    "fechaActualizacion" => "now()"
                                )
                            ),
                            "proveedor" => $proveedor
                        );
                        $saveAdscripcionInvolucradaResult = $genenericDAO->insert($saveAdscripcionInvolucrada);
                        if ($saveAdscripcionInvolucradaResult['status'] == 'success' && $saveAdscripcionInvolucradaResult['totalCount'] > 0) {
                            // acciones? todo bien!
                            if ($_SESSION['cveAdscripcion'] != $accion->cveAdscripcion) {
                                $Notificacion = new SeguimientoProyectosController();
                                $noti = array(
                                    'Origen' => $_SESSION['cveAdscripcion'], 
                                    'Destino' => $accion->cveAdscripcion, 
                                    'cveTipoNotificacion' => 1, 
                                    'tituloNotificacion' => 'Se registr&oacute; un proyecto program&aacute;tico', 
                                    'descripcionNotificacion' => 'La '.$_SESSION['desAdscripcion'].' agreg&oacute; un proyecto program&aacute;tico que involucra a tu adscripci&oacute;n', 
                                    'urlFormulario' => '#noir', 
                                );
                                $Notificacion->notificar($noti,$proveedor);
                            }
                        }else{
                            $mensaje .= 'Ocurrio un error al insertar en tblproyectosadscripciones. ';
                            $status = 'error';
                        }
                    }
                }else{
                    $mensaje .= 'Ocurrio un error al insertar en tblaccionesproyecto. ';
                    $status = 'error';
                }
            }
        }

        if ($saveProyectoResult['status'] == 'success' && $saveProyectoResult['totalCount'] > 0) {
            // acciones? todo bien!
        }else{
            $mensaje .= 'Ocurrio un error al insertar en tblproyectosprogramaticos. ';
            $status = 'error';
        }

        if ($status == 'success') {
            $proveedor->execute("COMMIT");
            $json = '{"status" : "success"}';
            echo $json;
        }else{
            $proveedor->execute("ROLLBACK");
            $json = '{"status" : "error","msg":"Ocurrio un error al guardar el registro en la base de datos: '.$mensaje.'"}';
            echo $json;
        }

        $proveedor->close();

    }

    public function loadProyectoProgramatico($idProyectoProgramatico)
    {
        $genericoDao = new GenericDAO();
        $d = array();
        $sql = array(
            "campos" => 
                "PP.idProyectoProgramatico, PP.cvePdeIdeal, PP.cvePdeEstrategia, PP.cvePdeLineaAccion, PP.anioProyectoProgramatico, PP.fechaInicio, PP.fechaTermino, PP.desProyectoProgramatico, PP.objetivo, PP.cveAdscripcionResp, PP.numEmpleadoResponsable, PP.cveOrganigramaResponsable, PP.estrategiaProyecto, PP.metaProyecto",
            "tablas" => 
                "tblproyectosprogramaticos AS PP",
            "where" => 
                ' PP.idProyectoProgramatico = '.$idProyectoProgramatico.' AND PP.activo = "S" '
        );
        $sqlSelect = array("tabla" => "", "d" => $d, "tmpSql" => $sql, "proveedor" => null);
        $rs = $genericoDao->select($sqlSelect);
        $proyectoDataArr = $rs;

        if ($proyectoDataArr['status'] == 'success' && $proyectoDataArr['totalCount'] > 0) {
            // obtener los datos del responsable
            include_once(dirname(__FILE__) . "../../../../../webservice/cliente/usuarios/UsuarioCliente.php");
            $usuarioCliente = new UsuarioCliente();
            $usuario = json_decode($usuarioCliente->selectUsuario_NumEmpleado($proyectoDataArr['data'][0]['numEmpleadoResponsable']));
            if ($usuario != '' && $usuario->totalCount > 0) {
                $nombre = $usuario->data[0]->paterno.' '.$usuario->data[0]->materno.' '.$usuario->data[0]->nombre;
                $proyectoDataArr['data'][0]['nombreResponsable'] = $nombre;
            }else{
                $proyectoDataArr['data'][0]['nombreResponsable'] = '';
            }

            // obtener acciones
            $genericoDao = new GenericDAO();
            $d = array();
            $sql = array(
                "campos" => 
                    "ACC.idAccionProyecto, ACC.desAccionProyecto, ACC.numEmpleadoACargo, ACC.cveAdscripcion, ACC.cveOrganigrama, ACC.fechaInicio, ACC.fechaFin, ACC.ponderacion, ACC.nomenclatura",
                "tablas" => 
                    "tblproyectosprogramaticos AS PP, tblaccionesproyecto AS ACC",
                "where" => 
                    ' PP.idProyectoProgramatico = '.$idProyectoProgramatico.' AND PP.idProyectoProgramatico = ACC.idProyectoProgramatico AND ACC.activo = "S" '
            );
            $sqlSelect = array("tabla" => "", "d" => $d, "tmpSql" => $sql, "proveedor" => null);
            $rs = $genericoDao->select($sqlSelect);
            $accionesDataArr = $rs;

            if ($accionesDataArr['status'] == 'success' && $accionesDataArr['totalCount'] > 0) {
                $acciones = $accionesDataArr['data'];
                foreach ($acciones as $key => $accion) {
                    // colocar las acciones en el arreglo principal
                    $proyectoDataArr['data'][0]['acciones'][$key] = $acciones[$key];
                }
            }

            /*
            // obtener unidades involucradas
            $genericoDao = new GenericDAO();
            $d = array();
            $sql = array(
                "campos" => 
                    "PA.idProyectoAdscripcion, PA.cveAdscripcion",
                "tablas" => 
                    "tblproyectosadscripciones AS PA",
                "where" => 
                    ' PA.idProyectoProgramatico = '.$idProyectoProgramatico.' AND PA.activo = "S" '
            );
            $sqlSelect = array("tabla" => "", "d" => $d, "tmpSql" => $sql, "proveedor" => null);
            $rs = $genericoDao->select($sqlSelect);
            $unidadesDataArr = $rs;

            if ($unidadesDataArr['status'] == 'success' && $unidadesDataArr['totalCount'] > 0) {
                $unidades = $unidadesDataArr['data'];
                foreach ($unidades as $key => $unidad) {
                    // colocar las unidades involucradas en el arreglo principal
                    $proyectoDataArr['data'][0]['unidadesInvolucradas'][$key] = $unidades[$key];
                }
            }*/

            // regresar arreglo principal
            $jsonEncode = new Encode_JSON();
            return $jsonEncode->encode($proyectoDataArr);
        }else{
            $json = '{"status" : "error","msg":"Ocurrio un error al consultar el proyecto solicitado."}';
            echo $json;
        }
    }

    public function updateProyectoProgramatico($idProyectoProgramatico,$extrasPost)
    {
        $proveedor = new Proveedor('mysql', 'ADMINISTRATIVO');
        $proveedor->connect();
        $proveedor->execute("BEGIN");
        $status = 'success';
        $mensaje = '';
        // PROYECTO
        // guardar en tblproyectoestrategico
        $jsonDecode = new Decode_JSON();
        $formData = $jsonDecode->decode($extrasPost['formData']);
        $genenericDAO = new GenericDAO();
        $saveProyecto = array(
            "tabla" => "tblproyectosprogramaticos",
            "accionBitacora" => "107",
            "d" => array(
                "values" => array(
                    "cvePdeIdeal" => $formData->cvePdeIdeal,
                    "cvePdeEstrategia" => $formData->cvePdeEstrategia,
                    "cvePdeLineaAccion" => $formData->cvePdeLineaAccion,
                    "desProyectoProgramatico" => $formData->desProyectoProgramatico,
                    "objetivo" => $formData->objetivo,
                    "estrategiaProyecto" => $formData->estrategiaProyecto,
                    "metaProyecto" => $formData->metaProyecto,
                    "fechaInicio" => date_format(date_create_from_format('d/m/Y',$formData->fechaInicio),'Y-m-d'),
                    "fechaTermino" => date_format(date_create_from_format('d/m/Y',$formData->fechaTermino),'Y-m-d'),
                    "numEmpleadoResponsable" => $formData->numEmpleadoResponsable,
                    "cveAdscripcionResp" => $formData->cveAdscripcionResp,
                    "cveOrganigramaResponsable" => $formData->cveOrganigramaResponsable,
                    "anioProyectoProgramatico" => $formData->anioProyectoProgramatico,
                    // "cveEstatusFinanzas" => '25',
                    // "cveEstatusPlaneacion" => '16',
                    // "cveEstadoProyecto" => '1',
                    // "activo" => 'S',
                    // "fechaRegistro" => "now()",
                    "fechaActualizacion" => "now()"
                ),
                "where" => array(
                    "idProyectoProgramatico" => $idProyectoProgramatico
                )
            ),
            "proveedor" => $proveedor
        );
        $saveProyectoResult = $genenericDAO->update($saveProyecto);
        //print_r($saveProyectoResult);
        if ($saveProyectoResult['status'] == 'success' && $saveProyectoResult['totalCount'] > 0) {
            // acciones? todo bien!
        }else{
            $mensaje .= 'Ocurrio un error al actualizar en tblproyectosprogramaticos. ';
            $status = 'error';
        }

        /*
        // BORRAR UNIDADES
        if ($status == 'success'){
            $jsonDecode = new Decode_JSON();
            $borrarUnidadesList = $jsonDecode->decode($extrasPost['borrarUnidadesList']);
            foreach ($borrarUnidadesList as $unidadId) {
                $resultJson = $this->deleteUnidadById($unidadId,$proveedor);
                $resultArr = json_decode($resultJson);
                if ($resultArr->status == 'success') {
                    // acciones? todo bien!
                    //@$this->guardarBitacora(115,$resultArr);
                }else{
                    $mensaje .= ' Ocurrio un error al borrar en tblproyectosadscripciones. ';
                    $status = 'error';
                }
            }
        }

        // UNIDADES BENEFICIADAS
        if ($status == 'success') {
            // guardar unidades beneficiadas en tblproyectosadscripciones
            foreach ($formData->unidadesInvolucradas as $key => $unidad) {
                $genenericDAO = new GenericDAO();
                if ($unidad->idProyectoAdscripcion != '') {
                    // actualizar
                    $tipo = 'actualiza';
                    $saveUnidad = array(
                        "tabla" => "tblproyectosadscripciones",
                        "accionBitacora" => "140",
                        "d" => array(
                            "values" => array(
                                "idProyectoProgramatico" => $idProyectoProgramatico,
                                "cveOrganigrama" => $unidad->cveOrganigrama,
                                "cveAdscripcion" => $unidad->cveAdscripcion,
                                //"vistoBueno" => 'N',
                                "activo" => 'S',
                                //"fechaRegistro" => "now()",
                                "fechaActualizacion" => "now()"
                            ),
                            "where" => array(
                                "idProyectoAdscripcion" => $unidad->idProyectoAdscripcion
                            )
                        ),
                        "proveedor" => $proveedor
                    );
                    $saveUnidad = $genenericDAO->update($saveUnidad);
                }else{
                    // insertar
                    $tipo = 'inserta';
                    $saveUnidad = array(
                        "tabla" => "tblproyectosadscripciones",
                        "accionBitacora" => "137",
                        "d" => array(
                            "values" => array(
                                "idProyectoProgramatico" => $idProyectoProgramatico,
                                "cveOrganigrama" => $unidad->cveOrganigrama,
                                "cveAdscripcion" => $unidad->cveAdscripcion,
                                "vistoBueno" => 'N',
                                "activo" => 'S',
                                "fechaRegistro" => "now()",
                                "fechaActualizacion" => "now()"
                            )
                        ),
                        "proveedor" => $proveedor
                    );
                    $saveUnidad = $genenericDAO->insert($saveUnidad);
                }

                if ($saveUnidad['status'] == 'success' && $saveUnidad['totalCount'] > 0) {
                    // acciones? todo bien!
                    // si la unidad es diferente de la que esta en sesion, mandar notificacion
                    if ($_SESSION['cveAdscripcion'] != $unidad->cveAdscripcion && $tipo == 'inserta') {
                        $Notificacion = new SeguimientoProyectosController();
                        $noti = array(
                            'Origen' => $_SESSION['cveAdscripcion'], 
                            'Destino' => $unidad->cveAdscripcion, 
                            'cveTipoNotificacion' => 1, 
                            'tituloNotificacion' => 'Se registr&oacute; un Proyecto Program&aacute;tico', 
                            'descripcionNotificacion' => 'La '.$_SESSION['desAdscripcion'].' agreg&oacute; un proyecto program&aacute;tico que involucra a tu adscripci&oacute;n', 
                            'urlFormulario' => '#noir', 
                        );
                        $Notificacion->notificar($noti,$proveedor);
                    }
                }else{
                    $mensaje .= 'Ocurrio un error al insertar en tblproyectosadscripciones. ';
                    $status = 'error';
                }
            }
        }
        */

        // BORRAR ACCIONES
        if ($status == 'success'){
            $jsonDecode = new Decode_JSON();
            $borrarAccionesList = $jsonDecode->decode($extrasPost['borrarAccionesList']);
            foreach ($borrarAccionesList as $accionId) {
                $resultJson = $this->deleteAccionById($accionId,$proveedor);
                $resultArr = json_decode($resultJson);
                if ($resultArr->status == 'success') {
                    // acciones? todo bien!
                    // @$this->guardarBitacora(110,$resultArr);
                }else{
                    $mensaje .= ' Ocurrio un error al borrar en tblaccionesproyecto. ';
                    $status = 'error';
                }
            }
        }

        // ACCIONES
        if ($status == 'success') {
            // este arreglo contiene la lista de adscripciones recibidas desde la vista
            $adscripcionesRecibidas = array();
            // guardar acciones en tblaccionesproyecto
            foreach ($formData->acciones as $key => $accion) {
                $genenericDAO = new GenericDAO();
                if ($accion->idAccionProyecto != '') {
                    // actualizar
                    $saveAccion = array(
                        "tabla" => "tblaccionesproyecto",
                        "accionBitacora" => "111",
                        "d" => array(
                            "values" => array(
                                "idProyectoProgramatico" => $idProyectoProgramatico,
                                "desAccionProyecto" => $accion->desAccionProyecto,
                                "numEmpleadoACargo" => $accion->numEmpleadoACargo,
                                "cveAdscripcion" => $accion->cveAdscripcion,
                                "cveOrganigrama" => $accion->cveOrganigrama,
                                "fechaInicio" => date_format(date_create_from_format('d/m/Y',$accion->fechaInicio),'Y-m-d'),
                                "fechaFin" => date_format(date_create_from_format('d/m/Y',$accion->fechaFin),'Y-m-d'),
                                "ponderacion" => $accion->ponderacion,
                                //"AvanceAcumulado" => 0,
                                "nomenclatura" => $accion->nomenclatura,
                                //"activo" => 'S',
                                //"fechaRegistro" => "now()",
                                "fechaActualizacion" => "now()"
                            ),
                            "where" => array(
                                "idAccionProyecto" => $accion->idAccionProyecto
                            )
                        ),
                        "proveedor" => $proveedor
                    );
                    $saveAccion = $genenericDAO->update($saveAccion);
                }else{
                    // insertar
                    $saveAccion = array(
                        "tabla" => "tblaccionesproyecto",
                        "accionBitacora" => "109",
                        "d" => array(
                            "values" => array(
                                "idProyectoProgramatico" => $idProyectoProgramatico,
                                "desAccionProyecto" => $accion->desAccionProyecto,
                                "numEmpleadoACargo" => $accion->numEmpleadoACargo,
                                "cveAdscripcion" => $accion->cveAdscripcion,
                                "cveOrganigrama" => $accion->cveOrganigrama,
                                "fechaInicio" => date_format(date_create_from_format('d/m/Y',$accion->fechaInicio),'Y-m-d'),
                                "fechaFin" => date_format(date_create_from_format('d/m/Y',$accion->fechaFin),'Y-m-d'),
                                "ponderacion" => $accion->ponderacion,
                                "AvanceAcumulado" => 0,
                                "nomenclatura" => $accion->nomenclatura,
                                "activo" => 'S',
                                "fechaRegistro" => "now()",
                                "fechaActualizacion" => "now()"
                            )
                        ),
                        "proveedor" => $proveedor
                    );
                    $saveAccion = $genenericDAO->insert($saveAccion);
                }

                if ($saveAccion['status'] == 'success' && $saveAccion['totalCount'] > 0) {
                    // acciones? todo bien!
                    $tmp['cveAdscripcion'] = $accion->cveAdscripcion;
                    $tmp['cveOrganigrama'] = $accion->cveOrganigrama;
                    array_push($adscripcionesRecibidas,$tmp);
                }else{
                    $mensaje .= 'Ocurrio un error al insertar en tblaccionesproyecto. ';
                    $status = 'error';
                }
            }
            // no repetir unidades
            $adscripcionesRecibidas = array_map("unserialize", array_unique(array_map("serialize", $adscripcionesRecibidas)));
        }

        // unidades involucradas
        if ($status == 'success') {
            $genericoDao = new GenericDAO();
            $d = array();
            $sql = array(
                "campos" => 
                    "*",
                "tablas" => 
                    "tblproyectosadscripciones",
                "where" => 
                    ' idProyectoProgramatico = '.$idProyectoProgramatico.' AND activo = "S" ',
                "orders" => " idProyectoAdscripcion ",
            );
            $sqlSelect = array("tabla" => "", "d" => $d, "tmpSql" => $sql, "proveedor" => null);
            $rs = $genericoDao->select($sqlSelect);
            // adscripciones activas que existen en la bd
            $adscripcionesRegistradas = $rs;

            if ($adscripcionesRegistradas['status'] == 'success' && $adscripcionesRegistradas['totalCount'] > 0) {
                $adscripcionesRegistradas = $adscripcionesRegistradas['data'];
                foreach ($adscripcionesRegistradas as $key => $a) {
                    $existeAdscripcion = false;
                    if ($key != 0) { // dejar intacta la adscripcion que registro el proyecto
                        foreach ($adscripcionesRecibidas as $key2 => $ar) {
                            if ($ar['cveAdscripcion'] == $a['cveAdscripcion']) {
                                // si esta, ignorar
                                $existeAdscripcion = true;
                            }
                        }
                        if (!$existeAdscripcion) {
                            // no esta, eliminar
                            $genenericDAO = new GenericDAO();
                            $deleteAdscripcion = array(
                                "tabla" => "tblproyectosadscripciones",
                                "accionBitacora" => "139",
                                "d" => array(
                                    "values" => array(
                                        "activo" => 'N',
                                        //"fechaRegistro" => "now()",
                                        "fechaActualizacion" => "now()"
                                    ),
                                    "where" => array(
                                        "idProyectoProgramatico" => $idProyectoProgramatico,
                                        "cveAdscripcion" => $a['cveAdscripcion']
                                    )
                                ),
                                "proveedor" => $proveedor
                            );
                            $deleteAdscripcionResult = $genenericDAO->update($deleteAdscripcion);
                            if ($deleteAdscripcionResult['status'] == 'success' && $deleteAdscripcionResult['totalCount'] > 0) {

                            }else{
                                $mensaje .= 'Ocurrio un error al eliminar unidad en tblproyectosadscripciones. ';
                                $status = 'error';
                            }
                        }
                    }
                }
                foreach ($adscripcionesRecibidas as $a) {
                    $existeAdscripcion = false;
                    foreach ($adscripcionesRegistradas as $key => $ar) {
                        if ($a['cveAdscripcion'] == $ar['cveAdscripcion']) {
                            // si esta, ignorar
                            $existeAdscripcion = true;
                        }
                    }
                    if (!$existeAdscripcion) {
                        // no esta, insertar
                        $genericoDao = new GenericDAO();
                        $saveAdscripcionInvolucrada = array(
                            "tabla" => "tblproyectosadscripciones",
                            "accionBitacora" => "137",
                            "d" => array(
                                "values" => array(
                                    "idProyectoProgramatico" => $idProyectoProgramatico,
                                    "cveOrganigrama" => $a['cveOrganigrama'],
                                    "cveAdscripcion" => $a['cveAdscripcion'],
                                    "vistoBueno" => 'N',
                                    "activo" => 'S',
                                    "fechaRegistro" => "now()",
                                    "fechaActualizacion" => "now()"
                                )
                            ),
                            "proveedor" => $proveedor
                        );
                        $saveAdscripcionInvolucradaResult = $genenericDAO->insert($saveAdscripcionInvolucrada);
                        if ($saveAdscripcionInvolucradaResult['status'] == 'success' && $saveAdscripcionInvolucradaResult['totalCount'] > 0) {

                        }else{
                            $mensaje .= 'Ocurrio un error al insertar en tblproyectosadscripciones. ';
                            $status = 'error';
                        }
                    }
                }
            }
        }

        $genericoDao = new GenericDAO();
        $d = array();
        $sql = array(
            "campos" => 
                "*",
            "tablas" => 
                "tblproyectosadscripciones",
            "where" => 
                ' idProyectoProgramatico = '.$idProyectoProgramatico
        );
        $sqlSelect = array("tabla" => "", "d" => $d, "tmpSql" => $sql, "proveedor" => $proveedor);
        $rs = $genericoDao->select($sqlSelect);
        $adscripcionesRegistradas = $rs;

        if ($status == 'success') {
            $proveedor->execute("COMMIT");
            $json = '{"status" : "success"}';
            echo $json;
        }else{
            $proveedor->execute("ROLLBACK");
            $json = '{"status" : "error","msg":"Ocurrio un error al guardar el registro en la base de datos: '.$mensaje.'"}';
            echo $json;
        }

        $proveedor->close();

    }

    public function eraseProyectoProgramatico($idProyectoProgramatico)
    {
        $mensaje = '';
        $proveedor = new Proveedor('mysql', 'ADMINISTRATIVO');
        $proveedor->connect();
        $genenericDAO = new GenericDAO();
        $deleteProyecto = array(
            "tabla" => "tblproyectosprogramaticos",
            "accionBitacora" => "108",
            "d" => array(
                "values" => array(
                    "activo" => 'N',
                    //"fechaRegistro" => "now()",
                    "fechaActualizacion" => "now()"
                ),
                "where" => array(
                    "idProyectoProgramatico" => $idProyectoProgramatico,
                    "cveEstatusFinanzas" => "25",
                    "cveEstatusPlaneacion" => "16",
                    "cveEstadoProyecto" => "1"
                )
            ),
            "proveedor" => $proveedor
        );
        $deleteProyectoResult = $genenericDAO->update($deleteProyecto);
        if ($deleteProyectoResult['status'] == 'success' && $deleteProyectoResult['totalCount'] > 0) {
            // acciones? todo bien!
            $jsonEncode = new Encode_JSON();
            $deleteProyectoResult = $jsonEncode->encode($deleteProyectoResult);
            $json = '{"status" : "success","msg":"Se elimino el proyecto correctamente.","data":'.$deleteProyectoResult.',"usuario":"'.$_SESSION["cveUsuarioSistema"].'"}';
            //@$this->guardarBitacora(108,json_decode($json));
            echo $json;
        }else{
            $mensaje .= 'Ocurrio un error al eliminar el proyecto, el proyecto no se puede eliminar con el estatus actual '.$deleteProyectoResult['msg'];
            $json = '{"status" : "error","msg":"'.$mensaje.'"}';
            $status = 'error';
            echo $json;
        }
        $proveedor->close();
    }

    public function deleteUnidadById($idProyectoAdscripcion,$proveedor)
    {
        $genenericDAO = new GenericDAO();
        $mensaje = '';
        $deleteUnidad = array(
            "tabla" => "tblproyectosadscripciones",
            "accionBitacora" => "139",
            "d" => array(
                "values" => array(
                    "activo" => 'N',
                    //"fechaRegistro" => "now()",
                    "fechaActualizacion" => "now()"
                ),
                "where" => array(
                    "idProyectoAdscripcion" => $idProyectoAdscripcion
                )
            ),
            "proveedor" => $proveedor
        );
        $deleteUnidadResult = $genenericDAO->update($deleteUnidad);
        if ($deleteUnidadResult['status'] == 'success' && $deleteUnidadResult['totalCount'] > 0) {
            // acciones? todo bien!
            $json = '{"status" : "success","msg":"Se elimino la unidad correctamente."}';
            return $json;
        }else{
            $mensaje .= 'Ocurrio un error al eliminar la unidad en tblunidadesinvolucradas. ';
            $json = '{"status" : "error","msg":"'.$mensaje.'"}';
            $status = 'error';
            return $json;
        }
    }

    public function deleteAccionById($idAccionProyecto,$proveedor)
    {
        $genenericDAO = new GenericDAO();
        $mensaje = '';
        $deleteAccion = array(
            "tabla" => "tblaccionesproyecto",
            "accionBitacora" => "110",
            "d" => array(
                "values" => array(
                    "activo" => 'N',
                    //"fechaRegistro" => "now()",
                    "fechaActualizacion" => "now()"
                ),
                "where" => array(
                    "idAccionProyecto" => $idAccionProyecto
                )
            ),
            "proveedor" => $proveedor
        );
        $deleteAccionResult = $genenericDAO->update($deleteAccion);
        if ($deleteAccionResult['status'] == 'success' && $deleteAccionResult['totalCount'] > 0) {
            // acciones? todo bien!
            $json = '{"status" : "success","msg":"Se elimino la unidad correctamente.","data":'.json_encode($deleteAccionResult).',"usuario":"'.$_SESSION["cveUsuarioSistema"].'"}';
            return $json;
        }else{
            $mensaje .= 'Ocurrio un error al eliminar la unidad en tblunidadesinvolucradas. ';
            $json = '{"status" : "error","msg":"'.$mensaje.'"}';
            return $json;
        }
    }



    public function datatableGenerico($params, $param, $limit, $nombreTabla, $condiciones = "", $agrupacion = "", $orders = "", $extras = false) {

        $genericoDao = new GenericDAO();
        $row = $genericoDao->select($param);
        //print_r($row);
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

                    if ($this->validateCompleteDate($value)) {
                            $registro[] = $this->fechaCompletaNormal($value);
                    } else if($this->validateSimpleDate($value)) {
                            $registro[] = $this->fechaSimpleNormal($value);
                    }else{
                        $registro[] = $value;
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

    /*
     * Obtiene unidades ejecutoras que tengan al menos un proyecto programatico dado un idproyectopresupuestal
     */
    public function getUnidadesEjecutorasHasProyectoProgramaticoByIdProyectoPresupuestal($idProyectoPresupuestal)
    {
        $genericoDao = new GenericDAO();
        $d = array();
        $sql = array(
            "campos" => 
                "PP.idProyectoProgramatico,PA.cveAdscripcion,PA.cveOrganigrama,PP.desProyectoProgramatico,PP.objetivo,PP.estrategiaProyecto,PP.metaProyecto",
            "tablas" => 
                "tblunidadesejecutoras AS UE, tblproyectosadscripciones AS PA, tblproyectosprogramaticos as PP",
            "where" => 
                ' UE.idProyectoPresupuestal = '.$idProyectoPresupuestal.' AND UE.cveAdscripcion = PA.cveAdscripcion AND PA.idProyectoProgramatico = PP.idProyectoProgramatico AND PP.cveEstatusFinanzas = 29 AND PP.cveEstatusPlaneacion = 23 AND UE.activo = "S" AND PP.activo = "S" AND PA.activo="S" '
        );
        $sqlSelect = array("tabla" => "", "d" => $d, "tmpSql" => $sql, "proveedor" => null);
        $rs = $genericoDao->select($sqlSelect);
        return $rs;
    }

    public function getProyectoYCapitulo($idProyectoProgramatico,$capitulo)
    {
        
    }

    public function getNombrePersonalCliente($numEmpleado) {
        $personal = new PersonalCliente();
        $rsPersonal = utf8_decode($personal->getNumEmpleado($numEmpleado));
        
        $json = New Decode_JSON();
        $tmp = $json->decode($rsPersonal);
        if (isset($tmp->totalCount) && $tmp->totalCount > 0) {
            if ($tmp->data[0]->CveStatus == 1) {
                return $rsPersonal;
            }else{
                return json_encode(array("status" => "error", "totalCount" => 0, "msg" => "No encontrado"));
            }
        }else{
            return json_encode(array("status" => "error", "totalCount" => 0, "msg" => "No encontrado"));
        }
    }

    public function obtenerIdealesDisponibles($cveAdscripcion)
    {
        $genericoDao = new GenericDAO();
        $d = array();
        $sql = array(
            "campos" => 
                "I.cvePdeIdeal",
            "tablas" => 
                "tblidealesproyectospresupuestales as I, tblunidadesejecutoras as U",
            "where" => 
                ' U.cveAdscripcion = '.$cveAdscripcion.' AND U.idProyectoPresupuestal = I.idProyectoPresupuestal AND U.activo ="S" AND I.activo = "S" '
        );
        $sqlSelect = array("tabla" => "", "d" => $d, "tmpSql" => $sql, "proveedor" => null);
        $rs = $genericoDao->select($sqlSelect);
        $jsonEncode = new Encode_JSON();
        return $jsonEncode->encode($rs);
    }

    function validateCompleteDate($date, $format = 'Y-m-d H:i:s') {
        $d = DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) == $date;
    }

    function validateSimpleDate($date, $format = 'Y-m-d') {
        $d = DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) == $date;
    }

    function fechaCompletaNormal($fecha, $hora = true) {
        $arrFecha = explode(" ", $fecha);
        list($year, $mes, $dia) = explode("-", $arrFecha[0]);
        if ($hora)
            return $dia . "/" . $mes . "/" . $year . " " . $arrFecha[1];
        else
            return $dia . "/" . $mes . "/" . $year . " ";
    }

    function fechaSimpleNormal($fecha, $hora = false) {
        $arrFecha = explode(" ", $fecha);
        list($year, $mes, $dia) = explode("-", $arrFecha[0]);
        if ($hora)
            return $dia . "/" . $mes . "/" . $year . " " . $arrFecha[1];
        else
            return $dia . "/" . $mes . "/" . $year . " ";
    }
}
