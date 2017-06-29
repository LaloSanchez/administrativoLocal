<?php

include_once(dirname(__FILE__) . "../../../../../modelos/dao/GenericDAO.Class.php");
include_once(dirname(__FILE__) . "../../../../../tribunal/json/JsonEncod.Class.php");
include_once(dirname(__FILE__) . "../../../../../tribunal/json/JsonDecod.Class.php");
include_once(dirname(__FILE__) . "../../../../../tribunal/host/Host.Class.php");
include_once(dirname(__FILE__) . "../../../../../tribunal/connect/Proveedor.Class.php");
include_once(dirname(__FILE__) . "../../../../../webservice/cliente/personal/PersonalCliente.php");
include_once(dirname(__FILE__) . "../../../../../controladores/bitacora/BitacoraController.Class.php");
include_once(dirname(__FILE__) . "../../../../../controladores/contadores/ContadoresController.Class.php");

/**
 * Clase generica que permite consultar 
 * los clasificadores 
 *
 * @author Augusto Fonseca
 */
class ProyectosEstrategicosController {
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

    public function getProyectosEstrategicosDataTable($params)
    {
        $limit = array("max" => $params["limit"]["max"],
            "pag" => $params["limit"]["pag"]);
        $d = array("limit" => $limit);

        $sql = array(
            "campos" => 
                "PE.idProyectoEstrategico, PE.nomenclatura, PE.objetivo, PE.desProyectoEstrategico, PE.numEmpleadoResponsable",
            "tablas" => 
                "tblproyectosestrategicos as PE",
            "orders" => $params["order"]["column"] . " " . $params["order"]["dir"],
            "where" => 
                ' PE.activo = "S" '
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
        return $this->datatableGenerico($params, $param, $limit, $nombreTabla = $sql['tablas'], $sql['where']);
    }

    public function getProyectosEstrategicos($params = '')
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
            "tabla" => "tblproyectosestrategicos",
            "d" => array(
                    'campos' => '*',
                    'where' => array(
                        'activo' => 'S'
                    ),
                    'order' => ' nomenclatura ASC '
            ),
            "tmpSql" => array()
        );
        if (isset($params['extras']) && $params['extras'] != '') {
            foreach ($params['extras'] as $key => $value) {
                $param['d']['where'][$key] = $value;
            }
        }

        // $pE = proyectos estrategicos 
        $pE = $genenericDAO->select($param);
        include_once(dirname(__FILE__) . "../../../../../webservice/cliente/usuarios/UsuarioCliente.php");
        if ($pE['status'] == 'success' && $pE['totalCount'] > 0) {
            // colocar el nombre del usuario
            $usuarioCliente = new UsuarioCliente();
            foreach ($pE['data'] as $key => $proyecto) {
                foreach ($proyecto as $key2 => $value) {
                    if ($key2 == 'numEmpleadoResponsable') {
                        $usuario = json_decode($usuarioCliente->selectUsuario_NumEmpleado($value));
                        if ($usuario != '' && $usuario->totalCount > 0) {
                            $nombre = $usuario->data[0]->paterno.' '.$usuario->data[0]->materno.' '.$usuario->data[0]->nombre;
                            $pE['data'][$key]['nombreCompleto'] = $nombre;
                        }else{
                            $pE['data'][$key]['nombreCompleto'] = '';
                        }
                    }
                }
            }
        }

        $jsonEncode = new Encode_JSON();
        return $jsonEncode->encode($pE);

    }

    public function saveProyectoEstrategico($formData)
    {
        $proveedor = new Proveedor('mysql', 'ADMINISTRATIVO');
        $proveedor->connect();
        $proveedor->execute("BEGIN");
        $status = 'success';
        $mensaje = '';

        // PROYECTO
        // obtener contador
        $contadores["cveAdscripcion"] = 853;
        $contadores["cveTipoDocContador"] = 13;
        $contadores["cveOrganigrama"] = '3013304000';
        $contadores["mes"] = "N";
        $contadores["cveMes"] = 0;
        $contadores["anio"] = 2017;

        $contadoresController = new ContadoresController();
        $contadores = $contadoresController->getContador($contadores,$proveedor);
        
        // guardar en tblproyectoestrategico
        $jsonDecode = new Decode_JSON();
        $formData = $jsonDecode->decode($formData);
        $genenericDAO = new GenericDAO();
        $saveProyecto = array(
            "tabla" => "tblproyectosestrategicos",
            "d" => array(
                "values" => array(
                    "desProyectoEstrategico" => ($formData->desProyectoEstrategico),
                    "nomenclatura" => $contadores['data'][0]['numero'].'-'.$contadores['data'][0]['anio'],
                    "cvePdeIdeal" => $formData->cvePdeIdeal,
                    "objetivo" => ($formData->objetivo),
                    "cvePdeEstrategia" => $formData->cvePdeEstrategia,
                    "cvePdeLineaAccion" => $formData->cvePdeLineaAccion,
                    "numEmpleadoResponsable" => $formData->numEmpleadoResponsable,
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
        }else{
            $mensaje .= 'Ocurrio un error al insertar en tblproyectosestrategicos. ';
            $status = 'error';
        }
        //var_dump($saveProyectoResult);
        $saveProyectoResultData = $saveProyectoResult['data'][0];

        // UNIDADES INVOLUCRADAS
        if ($status == 'success') {
            // guardar unidades involucradas en tblunidadesinvolucradas
            foreach ($formData->unidadesBeneficiadas as $key => $unidad) {
                $genenericDAO = new GenericDAO();
                $saveUnidad = array(
                    "tabla" => "tblunidadesinvolucradas",
                    "d" => array(
                        "values" => array(
                            "idProyectoEstrategico" => $saveProyectoResultData['idProyectoEstrategico'],
                            "cveOrganigrama" => $unidad->cveOrganigrama,
                            "cveAdscripcion" => $unidad->cveAdscripcion,
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
                    @$this->guardarBitacora(114,$saveUnidadResult);
                }else{
                    $mensaje .= 'Ocurrio un error al insertar en tblunidadesinvolucradas. ';
                    $status = 'error';
                }
            }
        }

        if ($status == 'success') {
            $proveedor->execute("COMMIT");

            $jsonEncode = new Encode_JSON();
            $saveProyectoResult = $jsonEncode->encode($saveProyectoResult);
            $json = '{"status" : "success","data":'.$saveProyectoResult.',"usuario":"'.$_SESSION["cveUsuarioSistema"].'"}';
            @$this->guardarBitacora(113,json_decode($json));
            echo $json;
        }else{
            $proveedor->execute("ROLLBACK");
            $json = '{"status" : "error","msg":"Ocurrio un error al guardar el registro en la base de datos: '.$mensaje.'"}';
            echo $json;
        }

        $proveedor->close();

    }

    public function updateProyectoEstrategico($idProyectoEstrategico,$extrasPost)
    {
        $proveedor = new Proveedor('mysql', 'ADMINISTRATIVO');
        $proveedor->connect();
        $proveedor->execute("BEGIN");
        $status = 'success';
        $mensaje = '';
        $warning = false;
        $warning_mensaje = '';
        // PROYECTO
        // guardar en tblproyectoestrategico
        $jsonDecode = new Decode_JSON();
        $formData = $jsonDecode->decode($extrasPost['formData']);
        $genenericDAO = new GenericDAO();
        $saveProyecto = array(
            "tabla" => "tblproyectosestrategicos",
            "d" => array(
                "values" => array(
                    "desProyectoEstrategico" => ($formData->desProyectoEstrategico),
                    "nomenclatura" => ($formData->nomenclatura),
                    "cvePdeIdeal" => $formData->cvePdeIdeal,
                    // El presente proyecto es para realizar las pruebas correspondientes de año en curso $%&#" ' *-/ jo?é
                    "objetivo" => ($formData->objetivo),
                    "cvePdeEstrategia" => $formData->cvePdeEstrategia,
                    "cvePdeLineaAccion" => $formData->cvePdeLineaAccion,
                    //"numEmpleadoResponsable" => $formData->numEmpleadoResponsable,
                    "activo" => 'S',
                    //"fechaRegistro" => "now()",
                    "fechaActualizacion" => "now()"
                ),
                "where" => array(
                    "idProyectoEstrategico" => $idProyectoEstrategico
                )
            ),
            "proveedor" => $proveedor
        );
        $saveProyectoResult = $genenericDAO->update($saveProyecto);
        //print_r($saveProyectoResult);
        if ($saveProyectoResult['status'] == 'success' && $saveProyectoResult['totalCount'] > 0) {
            // acciones? todo bien!
        }else{
            $mensaje .= 'Ocurrio un error al actualizar en tblproyectosestrategicos. ';
            $status = 'error';
        }

        // BORRAR UNIDADES SOLO SI NO TIENEN PROYECTOS PROGRAMATICOS LIGADOS
        if ($status == 'success'){
            $jsonDecode = new Decode_JSON();
            $borrarUnidadesList = $jsonDecode->decode($extrasPost['borrarUnidadesList']);
            foreach ($borrarUnidadesList as $unidadId) {
                $unidadHasProyectoProgramatico = json_decode($this->checkUnidadHasProyectoProgramatico($unidadId));
                if ($unidadHasProyectoProgramatico->status == 'success') {
                    if ($unidadHasProyectoProgramatico->resultConsulta == 'false') {
                        // no tiene proyectos, eliminar sin problema
                        $resultJson = $this->deleteUnidadById($unidadId,$proveedor);
                        $resultArr = json_decode($resultJson);
                        if ($resultArr->status == 'success') {
                            // acciones? todo bien!
                            @$this->guardarBitacora(115,$resultArr);
                        }else{
                            $mensaje .= ' Ocurrio un error al borrar en tblunidadesinvolucradas. ';
                            $status = 'error';
                        }
                    }else{
                        $warning = true;
                        $warning_mensaje .= ' La unidad beneficiada '.$unidadHasProyectoProgramatico->cveOrganigrama.' no pudo ser borrada pues ya tiene ligado un proyecto programatico';
                    }
                }else{
                    $status = 'error';
                    $mensaje .= $unidadHasProyectoProgramatico->msg;
                }
            }
        }

        // UNIDADES INVOLUCRADAS
        if ($status == 'success') {
            // guardar unidades involucradas en tblunidadesinvolucradas
            foreach ($formData->unidadesBeneficiadas as $key => $unidad) {
                $genenericDAO = new GenericDAO();
                if ($unidad->cveUnidadBeneficiada != '') {
                    // actualizar
                    $saveUnidad = array(
                        "tabla" => "tblunidadesinvolucradas",
                        "d" => array(
                            "values" => array(
                                "idProyectoEstrategico" => $idProyectoEstrategico,
                                "cveOrganigrama" => $unidad->cveOrganigrama,
                                "cveAdscripcion" => $unidad->cveAdscripcion,
                                "activo" => 'S',
                                //"fechaRegistro" => "now()",
                                "fechaActualizacion" => "now()"
                            ),
                            "where" => array(
                                "cveUnidadBeneficiada" => $unidad->cveUnidadBeneficiada
                            )
                        ),
                        "proveedor" => $proveedor
                    );
                    $saveUnidad = $genenericDAO->update($saveUnidad);
                    @$this->guardarBitacora(116,$saveUnidad);
                }else{
                    // insertar
                    $saveUnidad = array(
                        "tabla" => "tblunidadesinvolucradas",
                        "d" => array(
                            "values" => array(
                                "idProyectoEstrategico" => $idProyectoEstrategico,
                                "cveOrganigrama" => $unidad->cveOrganigrama,
                                "cveAdscripcion" => $unidad->cveAdscripcion,
                                "activo" => 'S',
                                "fechaRegistro" => "now()",
                                "fechaActualizacion" => "now()"
                            )
                        ),
                        "proveedor" => $proveedor
                    );
                    $saveUnidad = $genenericDAO->insert($saveUnidad);
                    @$this->guardarBitacora(114,$saveUnidad);
                }

                if ($saveUnidad['status'] == 'success' && $saveUnidad['totalCount'] > 0) {
                    // acciones? todo bien!
                }else{
                    $mensaje .= 'Ocurrio un error al insertar en tblunidadesinvolucradas. ';
                    $status = 'error';
                }
            }
        }

        if ($status == 'success') {
            $proveedor->execute("COMMIT");
            @$this->guardarBitacora(117,$saveProyectoResult);
            if ($warning) {
                $json = '{"status" : "success","warning_msg":"'.$warning_mensaje.'"}';
            }else{
                $json = '{"status" : "success"}';
            }
            echo $json;
        }else{
            $proveedor->execute("ROLLBACK");
            $json = '{"status" : "error","msg":"Ocurrio un error al guardar el registro en la base de datos: '.$mensaje.'"}';
            echo $json;
        }

        $proveedor->close();

    }

    public function loadProyectoEstrategico($idProyectoEstrategico)
    {
        $genericoDao = new GenericDAO();
        $d = array();
        $sql = array(
            "campos" => 
                "PE.idProyectoEstrategico, PE.nomenclatura, PE.objetivo, PE.desProyectoEstrategico, PE.numEmpleadoResponsable, IDE.cvePdeIdeal, ES.cvePdeEstrategia, LA.cvePdeLineaAccion",
            "tablas" => 
                "tblproyectosestrategicos AS PE, tblpdeideales AS IDE, tblpdeestrategias AS ES, tblpdelineasAccion AS LA",
            "where" => 
                ' PE.idProyectoEstrategico = '.$idProyectoEstrategico.' AND PE.cvePdeIdeal = IDE.cvePdeIdeal AND PE.cvePdeEstrategia = ES.cvePdeEstrategia AND PE.cvePdeLineaAccion = LA.cvePdeLineaAccion AND PE.activo = "S" AND IDE.activo = "S" AND ES.activo = "S" AND LA.activo = "S" '
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

            // obtener unidades involucradas
            $genericoDao = new GenericDAO();
            $d = array();
            $sql = array(
                "campos" => 
                    "UNI.cveUnidadBeneficiada, UNI.cveAdscripcion",
                "tablas" => 
                    "tblproyectosestrategicos AS PE, tblunidadesinvolucradas AS UNI",
                "where" => 
                    ' PE.idProyectoEstrategico = '.$idProyectoEstrategico.' AND PE.idProyectoEstrategico = UNI.idProyectoEstrategico AND UNI.activo = "S" '
            );
            $sqlSelect = array("tabla" => "", "d" => $d, "tmpSql" => $sql, "proveedor" => null);
            $rs = $genericoDao->select($sqlSelect);
            $unidadesDataArr = $rs;

            if ($unidadesDataArr['status'] == 'success' && $unidadesDataArr['totalCount'] > 0) {
                $unidades = $unidadesDataArr['data'];
                foreach ($unidades as $key => $unidad) {
                    // colocar las unidades beneficiadas en el arreglo principal
                    $proyectoDataArr['data'][0]['unidadesBeneficiadas'][$key] = $unidades[$key];
                }
            }

            // regresar arreglo principal
            $jsonEncode = new Encode_JSON();
            return $jsonEncode->encode($proyectoDataArr);
        }else{
            $json = '{"status" : "error","msg":"Ocurrio un error al consultar el proyecto solicitado."}';
            echo $json;
        }

    }

    public function deleteUnidadById($cveUnidadBeneficiada,$proveedor)
    {
        $genenericDAO = new GenericDAO();
        $mensaje = '';
        $deleteUnidad = array(
            "tabla" => "tblunidadesinvolucradas",
            "d" => array(
                "values" => array(
                    "activo" => 'N',
                    //"fechaRegistro" => "now()",
                    "fechaActualizacion" => "now()"
                ),
                "where" => array(
                    "cveUnidadBeneficiada" => $cveUnidadBeneficiada
                )
            ),
            "proveedor" => $proveedor
        );
        $deleteUnidadResult = $genenericDAO->update($deleteUnidad);
        if ($deleteUnidadResult['status'] == 'success' && $deleteUnidadResult['totalCount'] > 0) {
            // acciones? todo bien!
            $json = '{"status" : "success","msg":"Se elimino la unidad correctamente.","data":'.json_encode($deleteUnidadResult).',"usuario":"'.$_SESSION["cveUsuarioSistema"].'"}';
            return $json;
        }else{
            $mensaje .= 'Ocurrio un error al eliminar la unidad en tblunidadesinvolucradas. ';
            $json = '{"status" : "error","msg":"'.$mensaje.'"}';
            $status = 'error';
            return $json;
        }
    }

    public function checkUnidadHasProyectoProgramatico($cveUnidadBeneficiada)
    {
        $genericoDao = new GenericDAO();
        $d = array();
        $sql = array(
            "campos" => 
                "UI.cveOrganigrama,PP.objetivo",
            "tablas" => 
                "tblunidadesinvolucradas AS UI, tblproyectosprogramaticos AS PP",
            "where" => 
                ' UI.cveUnidadBeneficiada = '.$cveUnidadBeneficiada.' AND UI.idProyectoEstrategico = PP.idProyectoEstrategico AND UI.CveAdscripcion = PP.cveAdscripcion AND PP.activo = "S" AND UI.activo = "S" '
        );
        $sqlSelect = array("tabla" => "", "d" => $d, "tmpSql" => $sql, "proveedor" => null);
        $rs = $genericoDao->select($sqlSelect);
        $unidadHasProyectoProgramatico = $rs;

        if ($unidadHasProyectoProgramatico['status'] == 'success') {
            if ($unidadHasProyectoProgramatico['totalCount'] > 0) {
                // si tiene proyecto(s) programaticos esta unidad beneficiada, no se puede borrar
                $json = '{"status" : "success","resultConsulta" : "true","cveOrganigrama":"'.$unidadHasProyectoProgramatico['data'][0]['cveOrganigrama'].'"}';
            }else{
                $json = '{"status" : "success","resultConsulta" : "false"}';
            }
        }else{
            $json = '{"status" : "error","msg" : "Error al consultar si la unidad beneficiada tiene un proyecto programatico ligado"}';
        }
        return $json;
    }

    public function deleteIndicador($cveIndicadorAsociado)
    {
        $mensaje = '';
        $proveedor = new Proveedor('mysql', 'ADMINISTRATIVO');
        $proveedor->connect();
        $genenericDAO = new GenericDAO();
        $deleteIndicador = array(
            "tabla" => "tblindicadoresasociados",
            "d" => array(
                "values" => array(
                    "activo" => 'N',
                    //"fechaRegistro" => "now()",
                    "fechaActualizacion" => "now()"
                ),
                "where" => array(
                    "cveIndicadorAsociado" => $cveIndicadorAsociado
                )
            ),
            "proveedor" => $proveedor
        );
        $deleteIndicadorResult = $genenericDAO->update($deleteIndicador);
        if ($deleteIndicadorResult['status'] == 'success' && $deleteIndicadorResult['totalCount'] > 0) {
            // acciones? todo bien!
            $json = '{"status" : "success","msg":"Se elimino el indicador correctamente."}';
            echo $json;
        }else{
            $mensaje .= 'Ocurrio un error al eliminar el indicador en tblindicadoresasociados. ';
            $json = '{"status" : "error","msg":"'.$mensaje.'"}';
            $status = 'error';
            echo $json;
        }
        $proveedor->close();
    }

    public function eraseProyectoEstrategico($idProyectoEstrategico)
    {
        $mensaje = '';
        $proveedor = new Proveedor('mysql', 'ADMINISTRATIVO');
        $proveedor->connect();
        // verificar que no hay proyectos programaticos que contengan a este proyecto estrategico
        $genericoDao = new GenericDAO();
        $d = array();
        $sql = array(
            "campos" => 
                "*",
            "tablas" => 
                "tblproyectosprogramaticos",
            "where" => 
                ' idProyectoEstrategico = '.$idProyectoEstrategico.' AND activo = "S" '
        );
        $sqlSelect = array("tabla" => "", "d" => $d, "tmpSql" => $sql, "proveedor" => null);
        $rs = $genericoDao->select($sqlSelect);
        $proyectosProgramaticosArr = $rs;

        if ($proyectosProgramaticosArr['status'] == 'success' && $proyectosProgramaticosArr['totalCount'] > 0) {
            // si hay proyectos, no se pueden eliminar
            $mensaje .= 'No se puede eliminar este proyecto pues existe un proyecto programatico ligado a &eacute;ste';
            $json = '{"status" : "error","msg":"'.$mensaje.'"}';
            $status = 'error';
            $proveedor->close();
            echo $json;
            exit();
        }

        $genenericDAO = new GenericDAO();
        $deleteProyecto = array(
            "tabla" => "tblproyectosestrategicos",
            "d" => array(
                "values" => array(
                    "activo" => 'N',
                    //"fechaRegistro" => "now()",
                    "fechaActualizacion" => "now()"
                ),
                "where" => array(
                    "idProyectoEstrategico" => $idProyectoEstrategico
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
            @$this->guardarBitacora(118,json_decode($json));
            echo $json;
        }else{
            $mensaje .= 'Ocurrio un error al eliminar el proyecto en tblproyectosestrategicos. ';
            $json = '{"status" : "error","msg":"'.$mensaje.'"}';
            $status = 'error';
            echo $json;
        }
        $proveedor->close();
    }

    public function getProyectosEstrategicosByUnidadBeneficiada($params = '')
    {
        $genericoDao = new GenericDAO();
        $d = array();
        $sql = array(
            "campos" => 
                'PE.idProyectoEstrategico, PE.desProyectoEstrategico, PE.nomenclatura, PE.cvePdeIdeal, PE.objetivo, PE.cvePdeEstrategia, PE.cvePdeLineaAccion, PE.numEmpleadoResponsable, UI.cveUnidadBeneficiada, UI.cveAdscripcion, UI.cveOrganigrama',
            "tablas" => 
                'tblproyectosestrategicos AS PE, tblunidadesinvolucradas AS UI',
            "where" => 
                'PE.idProyectoEstrategico = UI.idProyectoEstrategico AND UI.cveAdscripcion = '.$params['extrasPost']['cveAdscripcion'].' AND PE.activo = "S" AND UI.activo = "S"'
        );
        $sqlSelect = array("tabla" => "", "d" => $d, "tmpSql" => $sql, "proveedor" => null);
        $pE = $genericoDao->select($sqlSelect);

        include_once(dirname(__FILE__) . "../../../../../webservice/cliente/usuarios/UsuarioCliente.php");
        if ($pE['status'] == 'success' && $pE['totalCount'] > 0) {
            // colocar el nombre del usuario
            $usuarioCliente = new UsuarioCliente();
            foreach ($pE['data'] as $key => $proyecto) {
                foreach ($proyecto as $key2 => $value) {
                    if ($key2 == 'numEmpleadoResponsable') {
                        $usuario = json_decode($usuarioCliente->selectUsuario_NumEmpleado($value));
                        if ($usuario != '' && $usuario->totalCount > 0) {
                            $nombre = $usuario->data[0]->paterno.' '.$usuario->data[0]->materno.' '.$usuario->data[0]->nombre;
                            $pE['data'][$key]['nombreCompleto'] = $nombre;
                        }else{
                            $pE['data'][$key]['nombreCompleto'] = '';
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
            }
        }

        $jsonEncode = new Encode_JSON();
        return $jsonEncode->encode($pE);

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

    public function getNombrePersonalCliente($numEmpleado) {
        $personal = new PersonalCliente();
        $rsPersonal = utf8_decode($personal->getNumEmpleado($numEmpleado));
        $tmp = json_decode($rsPersonal);
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
