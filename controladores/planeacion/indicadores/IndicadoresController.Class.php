<?php

include_once(dirname(__FILE__) . "/../../../modelos/dao/GenericDAO.Class.php");
include_once(dirname(__FILE__) . "/../../../tribunal/json/JsonEncod.Class.php");
include_once(dirname(__FILE__) . "/../../../tribunal/json/JsonDecod.Class.php");
include_once(dirname(__FILE__) . "/../../../controladores/generic/GenericController.Class.php");
include_once(dirname(__FILE__) . "/../../../controladores/bitacora/BitacoraController.Class.php");
include_once(dirname(__FILE__) . "/../../../controladores/presupuestos/techosController.Class.php");

/**
 * Clase que permite consultar el anteproyectoPartidas
 *
 * @author PJ
 */
class IndicadoresController {

    public function datatableIndicadores($params, $cveAdscripcion, $cveOrganigrama) {
        $d = array("limit" => "");
        $limit = array("max" => $params["limit"]["max"], "pag" => $params["limit"]["pag"]);
        $d = array("limit" => $limit);
        $where = "";
        ///////////////////////// CODIGO DE HECTOR
        $genericDao = new GenericDAO();
         $sql = array("campos" => "cveAdscripcion,cveOrganigrama",
                        "tablas" => "tblproyectosadscripciones",
                        "where" => " idProyectoProgramatico = (SELECT idProyectoProgramatico from tblproyectosadscripciones where cveAdscripcion = ".$cveAdscripcion.")",
                        "groups" => "",
                    );

                    $param = array("tabla" => "", "d" => $d, "tmpSql" => $sql, "proveedor" => null);
                    $consultaIds = $genericDao->select($param);
                    if($consultaIds["totalCount"]>0){
                        for($a=0;$a<$consultaIds["totalCount"];$a++){
                            if($where ==""){
                            $where .= "AND A.cveAdscripcion ='" . $consultaIds["data"][$a]["cveAdscripcion"] . "'";
                            }else{
                               $where .= "OR A.cveAdscripcion ='" . $consultaIds["data"][$a]["cveAdscripcion"] . "'"; 
                            }
                        }
                    }
        
        
        
        //////////////////////////
        $sql = array("campos" => "A.cveIndicadorAsociado,A.desIndicadorAsociado,A.interpretacion,A.desMetaAnual,A.valFactorComparacion,B.cveDimension,C.cveFrecuenciaMedicion,A.desFactorComparacion,D.cveAmbitoGeografico,A.desCobertura,E.cveTipoOperacion,F.cveTendencia,G.cveSentido,A.lineaBase,A.formula,A.cveAdscripcion,A.cveOrganigrama,A.activo,A.fechaRegistro,A.fechaActualizacion ",
            "tablas" => "tblindicadoresasociados as A INNER JOIN tbldimensiones as B on (B.cveDimension = A.cveDimension ) INNER JOIN tblfrecuenciasmedicion as C on (C.cveFrecuenciaMedicion = A.cveFrecuenciaMedicion ) INNER JOIN tblambitosgeograficos as D on (D.cveAmbitoGeografico = A.cveAmbitoGeografico ) INNER JOIN tbltiposoperaciones as E on (E.cveTipoOperacion = A.cveTipoOperacion ) INNER JOIN tbltendencias as F on (F.cveTendencia = A.cveTendencia ) INNER JOIN tblsentidos as G on (G.cveSentido = A.cveSentido )",
            "orders" => $params["order"]["column"] . "  " . $params["order"]["dir"],
            "where" => "  A.activo ='S' " . $where. " AND A.cveTipoIndicador in (3,4) ");

        if ($params['search']['value'] != "") {
            $sql['where'] = " A.activo ='S' " . $where. " AND A.cveTipoIndicador in (3,4)"
                    . "AND (A.desIndicadorAsociado LIKE '%" . $params['search']['value'] . "%' "
                    . "OR A.desMetaAnual LIKE '%" . $params['search']['value'] . "%'  "
                    . "OR A.interpretacion LIKE '%" . $params['search']['value'] . "%' "
                    . "OR A.valFactorComparacion LIKE '%" . $params['search']['value'] . "%' "
                    . "OR B.cveDimension LIKE '%" . $params['search']['value'] . "%' "
                    . "OR C.cveFrecuenciaMedicion LIKE '%" . $params['search']['value'] . "%' "
                    . "OR A.desFactorComparacion LIKE '%" . $params['search']['value'] . "%' "
                    . "OR D.cveAmbitoGeografico LIKE '%" . $params['search']['value'] . "%' "
                    . "OR A.desCobertura LIKE '%" . $params['search']['value'] . "%' "
                    . "OR E.cveTipoOperacion LIKE '%" . $params['search']['value'] . "%' "
                    . "OR F.cveTendencia LIKE '%" . $params['search']['value'] . "%' "
                    . "OR G.cveSentido LIKE '%" . $params['search']['value'] . "%' "
                    . "OR A.lineaBase LIKE '%" . $params['search']['value'] . "%' "
                    . "OR A.formula LIKE '%" . $params['search']['value'] . "%' )" . $where;
        }
        $param = array("tabla" => "", "d" => $d, "tmpSql" => $sql, "proveedor" => null);
        return $this->datatableGenerico($params, $param, $limit, $nombreTabla = $sql['tablas'], $sql['where']);
    }

    public function consultarProyectosProgramaticosAdscripcion($idProyectoProgramatico) {
        $d = array();
        $sql = array();
        $where = array();
        $JsonEncod = new Encode_JSON();
        $proyectosProgramaticos = "";
        $genericoDao = new GenericDAO();
        $d["values"] = "*";
//        $where["cveAdscripcion"] = $cveAdscripcion;
        $where["idProyectoProgramatico"] = $idProyectoProgramatico;
        $where["activo"] = 'S';
        $d["where"] = $where;
        $param = array("tabla" => "tblproyectosprogramaticos", "d" => $d, "tmpSql" => $sql, "proveedor" => null);
        $proyectosProgramaticos = $genericoDao->select($param);

        return $JsonEncod->encode($proyectosProgramaticos);
    }
    
    public function guardarIndicador($params) {
        $error = false;
        $proveedor = new Proveedor('mysql', 'ADMINISTRATIVO');
        $proveedor->connect();
        $proveedor->execute("BEGIN");
        $jsonEncode = new Encode_JSON();
        $genericDao = new GenericDAO();
        $techoP = new techosController();
        $campos = array();
        $msj  ="";
        if($params["cveIndicadorAsociado"] != ""){
            $where["cveIndicadorAsociado"] = $params["cveIndicadorAsociado"];
            $d["where"] = $where;
            $bitacora = 120;
        }else{
            $bitacora = 119;
        }
        if($params["activo"] != "N"){
            $campos["cveDimension"] = utf8_encode($params["cveDimension"]);
            $campos["cveFrecuenciaMedicion"] = utf8_encode($params["cveFrecuenciaMedicion"]);
            $campos["cveSentido"] = $params["cveSentido"];
            $campos["cveAmbitoGeografico"] = $params["cveAmbitoGeografico"];
            if($params["cveAdscripcion"] != "" ){
                $campos["cveAdscripcion"] = $params["cveAdscripcion"];
            }
            if($params["cveOrganigrama"] != ""){
                $campos["cveOrganigrama"] = $params["cveOrganigrama"];
            }
            $campos["cveTipoIndicador"] = $params["cveTipoIndicador"];
            $campos["cveTipoOperacion"] = $params["cveTipoOperacion"];
            $campos["cveTendencia"] = $params["cveTendencia"];
            $campos["desIndicadorAsociado"] = $params["desIndicadorAsociado"];
            $campos["interpretacion"] = $params["interpretacion"];
            $campos["valFactorComparacion"] = $params["valFactorComparacion"];
            $campos["desFactorComparacion"] = $params["desFactorComparacion"];
            $campos["desCobertura"] = $params["desCobertura"];
            $campos["lineaBase"] = $params["lineaBase"];
            $campos["formula"] = $params["formula"];
            $campos["desFormula"] = $params["desFormula"];
            $campos["mediosVerificacion"] = $params["mediosVerificacion"];
            $campos["supuestos"] = $params["supuestos"];
            $campos["desMetaAnual"] = $params["desMetaAnual"];
            $campos["fechaRegistro"] = "now()";
            $campos["fechaActualizacion"] = "now()";
        }
        $campos["activo"] = $params["activo"];
        $d["values"] = $campos;
        
        $param = array("tabla" => "tblindicadoresasociados","accionBitacora" => $bitacora, "d" => $d, "tmpSql" => "", "proveedor" => $proveedor);
        
        if($params["cveIndicadorAsociado"] !== ""){
            $indicadorAnterior = $techoP->consultaAntesUpdate($param);
           $indicadorRespOrig = $genericDao->update($param);
            $indicadorResp = $jsonEncode->encode($indicadorRespOrig);
             
             if($indicadorRespOrig["totalCount"] <= 0){
                $error = true;
                $msj = "Error al insertar indicador";
            }
        }else{
            $indicadorRespOrig = $genericDao->insert($param);
            if($indicadorRespOrig["totalCount"] <= 0){
                $error = true;
                $msj = "Error al insertar indicador";
            }else{
                $error = $this->agregarRelacionIndicador($params["cveTipoIndicador"],$indicadorRespOrig["data"][0]["cveIndicadorAsociado"],$params["indicadorAccionObj"],$proveedor);
                if(!$error){
                    $error = $this->agregarRelacionVariables($params["cveTipoIndicador"],$indicadorRespOrig["data"][0]["cveIndicadorAsociado"],$params["arrVariables"],$proveedor);
                }
            }
             $indicadorResp = $jsonEncode->encode($indicadorRespOrig);
        }
         if (!$error) {
            $proveedor->execute("COMMIT");
            $respuesta = $indicadorResp;
        } else {
            $proveedor->execute("ROLLBACK");
            $respuesta = $msj;
        }
        return $respuesta;
    }
    
    public function agregarRelacionIndicador($cveTipoIndicador,$cveIndicadorAsociado,$indicadorAccionObj,$proveedor) {
        $genericDao = new GenericDAO();
        $techoP = new techosController();
        $campos = array();
        
        $indicadorAccionObj= json_decode($indicadorAccionObj);
        $campos["cveIndicadorAsociado"] = $cveIndicadorAsociado;
        $campos["cantidadAnual"] = $indicadorAccionObj->cantidadAnual;
        $campos["ponderacionTotal1"] = $indicadorAccionObj->ponderacionTotal1;
        $campos["ponderacionTotal2"] = $indicadorAccionObj->ponderacionTotal2;
        $campos["ponderacionTotal3"] = $indicadorAccionObj->ponderacionTotal3;
        $campos["ponderacionTotal4"] = $indicadorAccionObj->ponderacionTotal4;
        $campos["fechaRegistro"] = "now()";
        $campos["fechaActualizacion"] = "now()";
           
        $campos["activo"] = "S";
        $d["values"] = $campos;
        if($cveTipoIndicador == 3){
            $campos["idProyectoProgramatico"] = $indicadorAccionObj->idProyectoProgramatico;
            $d["values"] = $campos;
            $param = array("tabla" => "tblindicadoresproyectos", "d" => $d, "tmpSql" => "", "proveedor" => $proveedor);
            $relacionIndicador = $genericDao->insert($param);
        }else{
            $campos["idAccionProyecto"] = $indicadorAccionObj->idAccionProyecto;
            $d["values"] = $campos;
            $param = array("tabla" => "tblindicadoresacciones", "d" => $d, "tmpSql" => "", "proveedor" => $proveedor);
            $relacionIndicador = $genericDao->insert($param);
        }
        if($relacionIndicador["totalCount"] > 0){
            return false;
        }else{
            return true;
        }
        
    }
    public function agregarRelacionVariables($cveTipoIndicador,$cveIndicadorAsociado,$arrVariables,$proveedor) {
        $genericDao = new GenericDAO();
        $techoP = new techosController();
        $campos = array();
        $arrVariables= json_decode($arrVariables);
        foreach ($arrVariables as $value) {
            $campos["cveIndicadorAsociado"] = $cveIndicadorAsociado;
            $campos["metaAnual"] = $value->metaAnual;
            $campos["ponderacionTotal1"] = $value->ponderacionTotal1;
            $campos["ponderacionTotal2"] = $value->ponderacionTotal2;
            $campos["ponderacionTotal3"] = $value->ponderacionTotal3;
            $campos["ponderacionTotal4"] = $value->ponderacionTotal4;
            $campos["fechaRegistro"] = "now()";
            $campos["fechaActualizacion"] = "now()";
            $campos["idVariable"] = $value->idVariable;
            $campos["activo"] = "S";
            $d["values"] = $campos;
            $param = array("tabla" => "tblindicadoresvariables", "d" => $d, "tmpSql" => "", "proveedor" => $proveedor);
            $relacionVariables = $genericDao->insert($param);
        }
        if($relacionVariables["totalCount"] > 0){
            return false;
        }else{
            return true;
        }
    }
    
    public function consultaraccionUsada($arrAcciones) {
        $arrAcciones = implode(",",$arrAcciones);
        $d = array();
        $sql = array();
        $where = array();
        $JsonEncod = new Encode_JSON();
        $accionesProyecto = "";
        $genericoDao = new GenericDAO();
         $sql = array("campos" => "idAccionProyecto",
            "tablas" => " tblindicadoresacciones ",
            "where" => "  activo ='S' AND idAccionProyecto in (".$arrAcciones.")");
         
        $param = array("tabla" => "", "d" => $d, "tmpSql" => $sql, "proveedor" => null);
        $accionesProyecto = $genericoDao->select($param);

        return $JsonEncod->encode($accionesProyecto);
    }
    public function consultarVariables($cveTipoVariable,$cveAdscripcion,$cveOrganigrama){
        $where = "";
        ////////////////////////////// CODIGO DE HECTOR
         $genericDao = new GenericDAO();
         $JsonEncod = new Encode_JSON();
         $sql = array("campos" => "cveAdscripcion,cveOrganigrama",
                        "tablas" => "tblproyectosadscripciones",
                        "where" => " idProyectoProgramatico = (SELECT idProyectoProgramatico from tblproyectosadscripciones where cveAdscripcion = ".$cveAdscripcion.")",
                        "groups" => "",
                    );
                    $param = array("tabla" => "", "d" => $d, "tmpSql" => $sql, "proveedor" => null);
                    $consultaIds = $genericDao->select($param);
                    if($consultaIds["totalCount"]>0){
                        for($a=0;$a<$consultaIds["totalCount"];$a++){
                            if($where ==""){
                            $where .= "AND A.cveAdscripcion ='" . $consultaIds["data"][$a]["cveAdscripcion"] . "'";
                            }else{
                               $where .= "OR A.cveAdscripcion ='" . $consultaIds["data"][$a]["cveAdscripcion"] . "'"; 
                            }
                        }
                    }
        
        $sql = array("campos" => "A.idVariable, A.nombreVariable, A.desVariable, B.cveUnidadMedida , C.desAcumuladoAnual, A.cveAdscripcion ",
            "tablas" => "tblvariables as A INNER JOIN tblunidadesmedida as B on (B.cveUnidadMedida = A.cveUnidadMedida ) INNER JOIN tblacumuladosanuales as C on (C.cveAcumuladoAnual = A.cveAcumuladoAnual )",
            "where" => "  A.cveTipoVariable = 2 AND A.activo ='S' " . $where,
            "orders" => "A.desVariable asc");
        
        $param = array("tabla" => "", "d" => $d, "tmpSql" => $sql, "proveedor" => null);
        $accionesProyecto = $genericDao->select($param);

        return $JsonEncod->encode($accionesProyecto);
    }
    public function consultarVariablesUsadas($cveIndicadorAsociado) {
        $d = array();
        $sql = array();
        $where = array();
        $JsonEncod = new Encode_JSON();
        $VariablesUsadas = "";
        $genericoDao = new GenericDAO();
         $sql = array("campos" => " iv.*, v.nombreVariable,um.desUnidadMedida  ",
            "tablas" => " tblindicadoresvariables iv INNER JOIN tblvariables v ON (v.idVariable = iv.idVariable) INNER JOIN tblunidadesmedida um ON (um.cveUnidadMedida=v.cveUnidadMedida)  ",
            "where" => " um.activo='S' AND v.activo='S' AND iv.activo ='S' AND iv.cveIndicadorAsociado = ".$cveIndicadorAsociado);
         
        $param = array("tabla" => "", "d" => $d, "tmpSql" => $sql, "proveedor" => null);
        $VariablesUsadas = $genericoDao->select($param);

        return $JsonEncod->encode($VariablesUsadas);
    }

    public function consultarProyectosProgramaticosAcciones($idProyectoProgramatico) {
        $d = array();
        $sql = array();
        $where = array();
        $JsonEncod = new Encode_JSON();
        $proyectosProgramaticos = "";
        $genericoDao = new GenericDAO();
        $d["values"] = "*";
        $where["idProyectoProgramatico"] = $idProyectoProgramatico;
        $where["activo"] = "S";
        $d["where"] = $where;
        $param = array("tabla" => "tblaccionesproyecto", "d" => $d, "tmpSql" => $sql, "proveedor" => null);
        $proyectosProgramaticos = $genericoDao->select($param);

        return $JsonEncod->encode($proyectosProgramaticos);
    }

    public function consultarProyectosProgramaticoElegido($cveIndicadorAsociado,$idProyectoProgramatico) {
        $d = array();
        $sql = array();
        $where = array();
        $JsonEncod = new Encode_JSON();
        $proyectosProgramaticos = "";
        $genericoDao = new GenericDAO();
        $d["values"] = "*";
        $where["cveIndicadorAsociado"] = $cveIndicadorAsociado;
        $where["idProyectoProgramatico"] = $idProyectoProgramatico;
        $where["activo"] = "S";
        $d["where"] = $where;
        $param = array("tabla" => "tblindicadoresproyectos", "d" => $d, "tmpSql" => $sql, "proveedor" => null);
        $proyectosProgramaticos = $genericoDao->select($param);
        
        return $JsonEncod->encode($proyectosProgramaticos);
    }
    public function consultarProyectosProgramaticoUsados($idProyectoProgramatico) {
        $d = array();
        $sql = array();
        $where = array();
        $JsonEncod = new Encode_JSON();
        $proyectosProgramaticos = "";
        $genericoDao = new GenericDAO();
        $d["values"] = "idProyectoProgramatico";
        $where["idProyectoProgramatico"] = $idProyectoProgramatico;
        $where["activo"] = "S";
        $d["where"] = $where;
        $param = array("tabla" => "tblindicadoresproyectos", "d" => $d, "tmpSql" => $sql, "proveedor" => null);
        $proyectosProgramaticos = $genericoDao->select($param);

        return $JsonEncod->encode($proyectosProgramaticos);
    }

    public function consultaraccionElegida($cveIndicadorAsociado,$idProyectoProgramatico) {
        $genericoDao = new GenericDAO();
        $JsonEncod = new Encode_JSON();
        $d = array();
        $sql = array();
        $where = "";
        if($idProyectoProgramatico != ""){
            $where1 = " AND pp.idProyectoProgramatico =". $idProyectoProgramatico;
        }
        if($cveIndicadorAsociado != ""){
            $where = " AND ia.cveIndicadorAsociado=". $cveIndicadorAsociado;
        }
        $sql = array("campos" => " ia.* ",
            "tablas" => " tblproyectosProgramaticos pp inner join tblaccionesproyecto ap on (pp.idProyectoProgramatico = ap.idProyectoProgramatico) inner join tblindicadoresacciones ia on (ia.idAccionProyecto = ap.IdAccionProyecto) ",
            "where" => " ia.activo='S' AND ap.activo='S' AND pp.activo ='S' ".$where1 .$where);
        
        $param = array("tabla" => "", "d" => $d, "tmpSql" => $sql, "proveedor" => null);
        $proyectosProgramaticos = $genericoDao->select($param);

        return $JsonEncod->encode($proyectosProgramaticos);
    }

    /*
     * Función que permite construir el json del datatableGenerico
     * @param arrray $params parametros de la paginación
     * @param arrray $param parametros para la consulta
     * @param array $limit limite de la consulta (pag y max)
     * @param string $nombreTabla nombre de la tabla
     * @param string $condiciones condiciones de la consulta
     * @param string $agrupacion campo por el que se agrupa la consulta
     * @param string $orders campos por los que se ordena la consulta
     * @return json regresa json con el resultado de la consulta
     */

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
                foreach ($row["data"][$index] as $key => $value) {
                    if ($key == "cveAdscripcion") {
                        $registro[] = $this->getAdscripcionNombre($value);
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

    public function date($value) {
        $patron = "/^(0[1-9]|1[0-9]|2[0-9]|3[0-1])\/(0[1-9]|1[0-2])\/\d{4}$/";
        return preg_match($patron, (string) $value);
    }

    public function dateTime($value) {
        $patron = "/^(0[1-9]|1[0-9]|2[0-9]|3[0-1])\/(0[1-9]|1[0-2])\/\d{4}\ (0[0-9]|1[0-9]|2[0-4])\:(0[0-9]|1[0-9]|2[0-9]|3[0-9]|4[0-9]|5[0-9])\:(0[0-9]|1[0-9]|2[0-9]|3[0-9]|4[0-9]|5[0-9])$/";

        return preg_match($patron, (string) $value);
    }

    public function esFecha($text) {
        if ($this->date($text)) {
            $fecha = explode("/", $text);

            return $fecha[2] . "-" . $fecha[1] . "-" . $fecha[0];
        } else if ($this->dateTime($text)) {
            $fechaHora = explode(" ", $text);
            $fecha = explode("/", $fechaHora[0]);

            return $fecha[2] . "-" . $fecha[1] . "-" . $fecha[0] . " " . $fechaHora[1];
        }
        return $text;
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

    public function consultarAcciones($idProyectoProgramatico) {
        $error = false;
        $d = array();
        $msg = array();
        $result = array();
        $sql = array("campos" => "AP.idAccionProyecto",
            "tablas" => "tblaccionesproyecto AP INNER JOIN tblindicadoresacciones IA ON(IA.idAccionProyecto = AP.idAccionProyecto)",
            "where" => " IA.activo='S' AND AP.activo='S' AND AP.idProyectoProgramatico=" . $idProyectoProgramatico,
            "groups" => "",
        );
        $param = array("tabla" => "", "d" => $d, "tmpSql" => $sql, "proveedor" => null);
        $genericDao = new GenericDAO();
        $consultaAnteproyectos = $genericDao->select($param);
//        $result = new Encode_JSON();
//        return $result->encode($consultaAnteproyectos);
        if ( (int)$consultaAnteproyectos['totalCount'] > 0 ) {
            $error = false;
        } else {
            $msg[] = 'No hay acciones para el proyecto o las acciones no tienen indicadores asociados';
            $error = true;
        }
        if ( !$error ) {
            $sqlP = array("campos" => " * ",
                "tablas" => "tblindicadoresproyectos",
                "where" => "activo = 'S'
                            AND idProyectoProgramatico=" . $idProyectoProgramatico,
                "groups" => ""
                
            );
            $paramsP = array("tabla" => "", "d" => $d, "tmpSql" => $sqlP, "proveedor" => null);
            $resultIndicadoresP = $genericDao->select($paramsP);
            //print_r($resultIndicadoresP);
            if ( (int)$resultIndicadoresP['totalCount'] > 0 ) {
                $error = false;
            } else {
                $error = true;
                $msg[] = 'El proyecto no tiene indicadores asociados, favor de verificar';
            }
        }
        if ( !$error ) {
            $sqlI = array("campos" => "ap.idAccionProyecto, ap.idProyectoProgramatico, ap.desAccionProyecto, 
                                       ia.idIndicadorAccion, ia.cveIndicadorAsociado",
                "tablas" => "tblaccionesproyecto ap
                             LEFT JOIN tblindicadoresacciones ia ON ia.idAccionProyecto = ap.idAccionProyecto AND ia.activo='S'",
                "where" => "ap.activo = 'S'
                            AND ap.idProyectoProgramatico=" . $idProyectoProgramatico,
                "groups" => ""
                
            );
            $params = array("tabla" => "", "d" => $d, "tmpSql" => $sqlI, "proveedor" => null);
            $resultIndicadores = $genericDao->select($params);
            //print_r($resultIndicadores);
            if ( (int)$resultIndicadores > 0 ) {
                for ( $n = 0; $n < (int)$resultIndicadores['totalCount']; $n++ ) {
                    if ($resultIndicadores['data'][$n]['cveIndicadorAsociado'] == '') {
                        $error = true;
                        $msg[] = 'Falta el indicador asociado a la accion: ' . utf8_encode($resultIndicadores['data'][$n]['desAccionProyecto']);
                    } else {
                        $error = false;
                    }
                    if ( $error ) {
                        break;
                    }
                }
            } else {
                $error = true;
                $msg[] = 'No hay acciones capturadas para el proyecto';
            }
        }
        if ( !$error ) {
            $result = array(
                'totalCount' => 1,
                'estatus' => 'ok'
            );
        } else {
            $result = array(
                'totalCount' => 0,
                'estatus' => 'error',
                'msj' => $msg
            );
        }
        return json_encode($result);
    }
//    public function cargarVariablesSeleccionadas() {
//        $d = array();
//        $sql = array("campos" => "idVariable",
//            "tablas" => "tblindicadoresvariables",
//            "where" => " activo = 'S' AND cveAdscripcion = " . $_SESSION['cveAdscripcion'],
//        );
//        $param = array("tabla" => "", "d" => $d, "tmpSql" => $sql, "proveedor" => null);
//        $genericDao = new GenericDAO();
//        $consultaVariables = $genericDao->select($param);
//        $variables = [];
//        $result = new Encode_JSON();
//        if($consultaVariables["totalCount"]){
//            foreach ($consultaVariables["data"] as $value) {
//                array_push($variables,$value["idVariable1"]);
//                array_push($variables,$value["idVariable2"]);
//            }
//        }
//        
//        return $result->encode($variables);
//    }

    public function validarPasoCinco($idProyectoProgramatico) {
        $d = array();
        $error = false;
        $msg = array();
        $sql = array("campos" => "COUNT(a.idAccionProyecto) AS accionesProyecto,
                                  COUNT(p.idAccionProgramatica) AS accionesprogramaticas",
            "tablas" => "tblaccionesproyecto a
                            LEFT JOIN tblaccionprogramatica p ON p.idAccionProyecto = a.idAccionProyecto AND p.activo='S'",
            "where" => " a.activo='S' AND a.idProyectoProgramatico=" . $idProyectoProgramatico,
            "groups" => "",
        );
        $param = array("tabla" => "", "d" => $d, "tmpSql" => $sql, "proveedor" => null);
        $genericDao = new GenericDAO();
        $consultaAnteproyectos = $genericDao->select($param);
        if ($consultaAnteproyectos["totalCount"] > 0 && ($consultaAnteproyectos['data'][0]['accionesProyecto'] == $consultaAnteproyectos['data'][0]['accionesprogramaticas']   )) {
            $sql = array("campos" => "idReferencia",
                "tablas" => "tbldocumentosimg A",
                "where" => " A.activo='S' AND A.cveTipoDocumento =1 AND A.idReferencia=" . $idProyectoProgramatico,
                "groups" => "",
            );
            $param = array("tabla" => "", "d" => $d, "tmpSql" => $sql, "proveedor" => null);
            $genericDao = new GenericDAO();
            $consultaDiagnosticos = $genericDao->select($param);
            
            if ($consultaDiagnosticos["totalCount"] > 0) {
                $sql = array("campos" => "idReferencia",
                    "tablas" => "tbldocumentosimg A",
                    "where" => " A.activo='S' AND A.cveTipoDocumento =2 AND A.idReferencia=" . $idProyectoProgramatico,
                    "groups" => "",
                );
                $param = array("tabla" => "", "d" => $d, "tmpSql" => $sql, "proveedor" => null);
                $genericDao = new GenericDAO();
                $consultaArboles = $genericDao->select($param);
                if ($consultaArboles["totalCount"] > 0) {
//                    $sql = array("campos" => "idReferencia",
//                        "tablas" => "tbldocumentosimg A",
//                        "where" => " A.activo='S' AND A.cveTipoDocumento =3 AND A.idReferencia=" . $idProyectoProgramatico,
//                        "groups" => "",
//                    );
//                    $param = array("tabla" => "", "d" => $d, "tmpSql" => $sql, "proveedor" => null);
//                    $genericDao = new GenericDAO();
//                    $consultaMir = $genericDao->select($param);
//                    if ($consultaMir["totalCount"] > 0) {
//                        $encode = new Encode_JSON();
//                        return $encode->encode($consultaMir);
//                    } else {
//                        $error = true;
//                        $msg[] = 'No se encontraron registros de MIR';
//                    }
                } else {
                    $error = true;
                    $msg[] = 'No se encontraron registros de diagramas';
                }
            } else {
                $error = true;
                $msg[] = 'No se encontraron registros de diagnosticos';
            }
        } else {
            $error = true;
            $msg[] = 'Hay registros de calendarizacion de acciones pendientes';
        }
        if (!$error) {
            $datosError = array(
                "totalCount" => 1,
                "estatus" => "ok" 
            );
        } else {
            $datosError = array(
                "totalCount" => 0,
                "msg" => $msg
            );
        }
        $encode = new Encode_JSON();
        return $encode->encode($datosError);
    }
    
    public function validarPasoUno(){
        $d = array();
        $error = false;
        $msg = array();
        $result = array();
        $idProyectoPresupuestal = 0;
//        echo "<pre>";
//        print_r($_SESSION);
//        echo "</pre>";
        $sql = array("campos" => " * ",
            "tablas" => " tblunidadesejecutoras ",
            "where" => " activo='S' AND cveAdscripcion=" . $_SESSION['cveAdscripcion'] . " AND idProyectoPresupuestal <> '' ",
            "groups" => "",
        );
        $param = array("tabla" => "", "d" => $d, "tmpSql" => $sql, "proveedor" => null);
        $genericDao = new GenericDAO();
        $consultaUnidadesEjecutoras = $genericDao->select($param);
        if ( (int)$consultaUnidadesEjecutoras['totalCount'] > 0 ) {
            $error = false;
            $idProyectoPresupuestal = $consultaUnidadesEjecutoras['data'][0]['idProyectoPresupuestal'];
            $sqlP = array("campos" => " * ",
                "tablas" => " tblproyectospresupuestales ",
                "where" => " activo='S' AND idProyectoPresupuestal=" . $idProyectoPresupuestal . " AND cveIndicadorAsociado <> '' ",
                "groups" => "",
            );
            $params = array("tabla" => "", "d" => $d, "tmpSql" => $sqlP, "proveedor" => null);
            $genericDao = new GenericDAO();
            $consultaProyectoPresupuestal = $genericDao->select($params);
            if ( (int)$consultaProyectoPresupuestal['totalCount'] > 0 ) {
                $error = false;
            } else {
                $error = true;
                $msg[] = 'No se encontró algún Proyecto Presupuestal para la Adscripción: ' . utf8_encode($_SESSION['desAdscripcion']) . ', o el proyecto no cuenta con algún indicador asociado';
            }
        } else {
            $error = true;
            $msg[] = 'No se encontraron registros de unidades ejecutoras para la Adscripción: ' . utf8_encode($_SESSION['desAdscripcion']);
        }
        if ( !$error ) {
            $anio = date('Y');
            $sqlT = array("campos" => " * ",
                "tablas" => " tbltechospresupuestales ",
                "where" => " activo='S' AND anioTecho = " . $anio . " AND enVigor='S' AND cveEstatus='3' AND (confirmado IS NULL OR confirmado <> 'S')",
                "groups" => "",
            );
            $paramT = array("tabla" => "", "d" => $d, "tmpSql" => $sqlT, "proveedor" => null);
            $genericDao = new GenericDAO();
            $consultaTechoPresupuestal = $genericDao->select($paramT);
            if ( (int)$consultaTechoPresupuestal['totalCount'] > 0 ) {
                $error = false;
            } else {
                $error = true;
                $msg[] = 'No se encontró algún registro de Techo Presupuestal En Vigor o Solicitado';
            }
        }
        if ( !$error ) {
            $result = array(
                'totalCount' => 1,
                'estatus' => 'ok'
            );
        } else {
            $result = array(
                'totalCount' => 0,
                'estatus' => 'error',
                'msj' => $msg
            );
        }
        //print_r($result);
        return json_encode($result);
    }
    
}
