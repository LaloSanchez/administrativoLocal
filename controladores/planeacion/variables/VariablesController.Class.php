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
class VariablesController {
    
    /* Función para insertar en AnteproyectosPartidas
     * @param array $detalle
     * @return json 
     */
    public function guardarVariable($params){
        $error = false;
        $proveedor = new Proveedor('mysql', 'ADMINISTRATIVO');
        $proveedor->connect();
        $proveedor->execute("BEGIN");
        $jsonEncode = new Encode_JSON();
        $genericDao = new GenericDAO();
        $techoP = new techosController();
        $campos = array();
        $bitacora = array();
        if($params["idVariable"] != ""){
            $where["idVariable"] = $params["idVariable"];
            $d["where"] = $where;
            $bitacora = 119;
        }else{
            $nombreRepetido = $this->getVariablesNombreRepetido($params["nombreVariable"]);
            $bitacora = 120;
            $campos["cveAdscripcion"] = $params["cveAdscripcion"];
            $campos["cveOrganigrama"] = $params["cveOrganigrama"];
        }
        if($params["activo"] != "N"){
            $campos["nombreVariable"] = $params["nombreVariable"];
            $campos["desVariable"] = $params["desVariable"];
            $campos["cveTipoVariable"] = 2;
            $campos["cveUnidadMedida"] = $params["cveUnidadMedida"];
            $campos["cveAcumuladoAnual"] = $params["cveAcumuladoAnual"];
            $campos["fechaRegistro"] = "now()";
            $campos["fechaActualizacion"] = "now()";
        }
        $campos["activo"] = $params["activo"];
        $d["values"] = $campos;
        $param = array("tabla" => "tblvariables","accionBitacora" => $bitacora, "d" => $d, "tmpSql" => "", "proveedor" => $proveedor);
        
        if($params["idVariable"] !== ""){
            $variableAnterior = $techoP->consultaAntesUpdate($param);
           $variableRespOrig = $genericDao->update($param);
            $variableResp = $jsonEncode->encode($variableRespOrig);
//             $techoP->guardarBitacora(120, $variableRespOrig,$variableAnterior,$proveedor);
            if($variableRespOrig["totalCount"] <= 0){
                $error = true;
                $msj = "Error al actualizar variable";
            }
        }else{
            if(!$nombreRepetido){
                $variableRespOrig = $genericDao->insert($param);
                 $variableResp = $jsonEncode->encode($variableRespOrig);
    //            $techoP->guardarBitacora(119, $variableRespOrig,$proveedor);
                if($variableRespOrig["totalCount"] <= 0){
                    $error = true;
                    $msj = "Error al insertar variable";
                }
            }else{
                $error = true;
                $msj = "No se puede repetir variables";
            }
        }
         if (!$error) {
            $proveedor->execute("COMMIT");
            $respuesta = $variableResp;
        } else {
            $proveedor->execute("ROLLBACK");
            $resp = json_encode(array("status"=>"error",
                          "totalCount"=> 0 ,
                          "msj"=>$msj));
            $respuesta = $resp;
        }
        return $respuesta;
    }
    
    public function datatableVariables($params, $cveAdscripcion, $cveOrganigrama) {
        $d = array("limit" => "");
        $limit = array("max" => $params["limit"]["max"], "pag" => $params["limit"]["pag"]);
        $d = array("limit" => $limit);
        $where = "";
        
        ////////////////////////////// CODIGO DE HECTOR
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
                    /////////////////////////
        /////////////////////////////
                    
                   
      
            
        
        
        
        $sql = array("campos" => "A.idVariable, A.nombreVariable, A.desVariable, B.desUnidadMedida , C.desAcumuladoAnual, A.cveAdscripcion ",
            "tablas" => "tblvariables as A INNER JOIN tblunidadesmedida as B on (B.cveUnidadMedida = A.cveUnidadMedida ) INNER JOIN tblacumuladosanuales as C on (C.cveAcumuladoAnual = A.cveAcumuladoAnual )",
            "orders" => $params["order"]["column"] . "  " . $params["order"]["dir"],
            "where" => "  A.cveTipoVariable = 2 AND A.activo ='S' " . $where);


        if ($params['search']['value'] != "") {
            $sql['where'] = "A.cveTipoVariable = 2 AND A.activo ='S' " . $where
                    . "AND (A.nombreVariable LIKE '%" . $params['search']['value'] . "%'  "
                    . "OR A.desVariable LIKE '%" . $params['search']['value'] . "%' "
                    . "OR B.desUnidadMedida LIKE '%" . $params['search']['value'] . "%' "
                    . "OR C.desAcumuladoAnual LIKE '%" . $params['search']['value'] . "%' "
                    . "OR A.cveAdscripcion LIKE '%" . $params['search']['value'] . "%') " ;
        }
        $param = array("tabla" => "", "d" => $d, "tmpSql" => $sql, "proveedor" => null);
        return $this->datatableGenerico($params, $param, $limit, $nombreTabla = $sql['tablas'], $sql['where']);
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

    public function getVariablesNombreRepetido($nombreVariable) {
        $genericDao = new GenericDAO();
        $sql = array("campos" => "nombreVariable",
                        "tablas" => "tblvariables",
                        "where" => " activo = 'S' AND nombreVariable = '".$nombreVariable."'",
                    );
        $param = array("tabla" => "", "d" => $d, "tmpSql" => $sql, "proveedor" => null);
        $variableRepetida = $genericDao->select($param);
        if($variableRepetida["totalCount"] > 0){
            return true;
        }else{
            return false;
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



