<?php

include_once(dirname(__FILE__) . "/../../modelos/dao/GenericDAO.Class.php");
include_once(dirname(__FILE__) . "/../../tribunal/json/JsonEncod.Class.php");
include_once(dirname(__FILE__) . "/../../tribunal/connect/Proveedor.Class.php");

/**
 * Clase para el Anteroyecto Programatico 
 *
 * @author PJ
 */
class ConsultaInventarioController {
    /* Función para consultar los Proyectos:
     * @param array $params array que contiene los datos de la paginación
     * @return json datos para construir el datatable del cri
     */
    private $proveedor;
    public function datatableConsultaInventario($params) {
        
        $limit = array("max" => $params["limit"]["max"],
            "pag" => $params["limit"]["pag"]);
        $d = array("limit" => $limit);

        $sql = array(
            "campos" => "a.idInventario,b.`idCbm`,b.`denominacion`,c.`desColor`,b.cbmPropio,b.modelo,b.marca,d.`desUnidadMedida`,a.numeroSerie",
            "tablas" => "tblinventarios a INNER JOIN tblcbm b ON a.`idCbm` = b.`idCbm` INNER JOIN tblcolores c ON b.`cveColor` = c.`cveColor` INNER JOIN tblunidadesmedida d ON b.`cveUnidadMedida` = d.`cveUnidadMedida`",
            "orders" => $params["order"]["column"] . " " . $params["order"]["dir"],
//            "where" => "tblproyectosprogramaticos.activo = 'S' AND tblproyectosprogramaticos.cveAdscripcion = " . $_SESSION["cveAdscripcion"] . ""
            "where" => "a.`activo` = 'S' AND b.`activo` = 'S' AND c.`activo` = 'S' AND d.`activo` = 'S'",
        );
        if (!is_null($params["extras"])) {
//            var_dump($params["extras"]);
            foreach ($params["extras"] as $key => $value) {
                if($key == 'cveAdscripcion'){
                    $sql["where"] .= " AND e.activo='S' AND e." . $key . "=" . $value . " ";
                    $sql["tablas"] .= " INNER JOIN tblhistorialinventarios e ON a.`idInventario` = e.`idInventario` ";
                }else{
                    if ($value != "" && $value != null) {
                        $sql["where"] .= " AND b." . $key . "=" . $value . " ";
                    }
                }
            }
        }
//        var_dump($params);
        if ($params['search']['value'] != "") {
            $arrayCampos = split(",", $sql["campos"]);
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
    
    public function consultaAsegurados($params){
       
         $where = "I.asegurado='S' AND I.inventariado='S' AND I.activo='S'";


        $limit = array("max" => $params["limit"]["max"], "pag" => $params["limit"]["pag"]);
        $d = array("limit" => $limit);
        $sql = array("campos" => "B.idBienAsegurado,C.desClasificadorBien,if(BI.idCbi is not null AND C.cveClasificadorBien=2, BI.denominacion,if(BM.idCbm is not null AND C.cveClasificadorBien=1,BM.denominacion,if(AH.idAah is not null AND C.cveClasificadorBien=7,AH.denominacion,'SIN INFO'))) as denominacion 
                                 ,codigoPropio,desAseguradora,numeroPoliza,fechaInicioCobertura,fechaFinCobertura,B.idBienAsegurado",
                                "tablas" => " tblbienesasegurados B 
                                    INNER JOIN tblinventarios I ON (B.idInventario = I.idInventario)
                                    INNER JOIN tblaseguradoras A ON (B.cveAseguradora = A.cveAseguradora)
                                    LEFT join tblcbi BI on (BI.idCbi = I.idReferencia)
                                    LEFT JOIN tblcbm BM  on (BM.idCbm = I.idReferencia) 
                                    LEFT JOIN tblaah AH  on (AH.idAah = I.idReferencia)
                                    INNER JOIN tblclasificadoresbienes C ON (I.cveClasificadorBien=C.cveClasificadorBien)",
            "orders" => $params["order"]["column"] . " " . $params["order"]["dir"],
            "where" => $where);

        if ($params['search']['value'] != "") {
             $sql['where'] = " I.activo='S' AND I.codigoPropio  LIKE '%" . $params['search']['value'] . "%' OR C.desClasificadorBien  LIKE '%" . $params['search']['value'] . "%' OR desAseguradora  LIKE '%" . $params['search']['value'] . "%' OR B.numeroPoliza  LIKE '%" . $params['search']['value'] . "%' OR I.precioCompra  LIKE '%" . $params['search']['value'] . "%' OR BM.denominacion  LIKE '%" . $params['search']['value'] . "%' OR BI.denominacion  LIKE '%" . $params['search']['value'] . "%' OR AH.denominacion  LIKE '%" . $params['search']['value'] . "%'";
       }

        $param = array("tabla" => "", "d" => $d, "tmpSql" => $sql, "proveedor" => null);
        return $this->datatableGenerico($params, $param, $limit, $nombreTabla = $sql['tablas'], $sql['where']);
           
       }
       
       
       public function consultaCoberturas($params,$extrasPOST) {

        $d = array();

        $sql = array("campos" => "idCoberturaBien,sumaAsegurada,deducible,desTipoCobertura,C.idBienAsegurado",
            "tablas" => "tblcoberturabienes C INNER JOIN tbltiposcoberturas T ON (C.cveTipoCobertura = T.cveTipoCobertura)",
            "where" => " C.idBienAsegurado=".$extrasPOST["idBienAsegurado"]." AND C.activo='S'"
           );
        $param = array("tabla" => "", "d" => $d, "tmpSql" => $sql, "proveedor" => null);
        $genericDao = new GenericDAO();
        $rs = $genericDao->select($param);
        $encode_Json = new Encode_JSON();
        return $encode_Json->encode($rs);
    }
    
    public function  consultaInventarios($params){
       
        $where = "I.activo='S' AND I.inventariado='S'";


        $limit = array("max" => $params["limit"]["max"], "pag" => $params["limit"]["pag"]);
        $d = array("limit" => $limit);
        $sql = array("campos" => "I.idInventario,C.desClasificadorBien,if(BI.idCbi is not null AND C.cveClasificadorBien=2, BI.denominacion,if(BM.idCbm is not null AND C.cveClasificadorBien=1,BM.denominacion,if(AH.idAah is not null AND C.cveClasificadorBien=7,AH.denominacion,'SIN INFO'))) as denominacion 
                                 
                                 ,codigoPropio,I.fechaCompra,I.precioCompra,I.precioActual",
                                "tablas" => "tblinventarios I
                                            LEFT join tblcbi BI on (BI.idCbi = I.idReferencia)
                                            LEFT JOIN tblcbm BM  on (BM.idCbm = I.idReferencia) 
                                            LEFT JOIN tblaah AH  on (AH.idAah = I.idReferencia)
                                            INNER JOIN tblclasificadoresbienes C ON (I.cveClasificadorBien=C.cveClasificadorBien)",
            "orders" => $params["order"]["column"] . " " . $params["order"]["dir"],
            "where" => $where);

        if ($params['search']['value'] != "") {
            $sql['where'] = " I.activo='S' AND I.codigoPropio  LIKE '%" . $params['search']['value'] . "%' OR C.desClasificadorBien  LIKE '%" . $params['search']['value'] . "%' OR I.precioCompra  LIKE '%" . $params['search']['value'] . "%' OR BM.denominacion  LIKE '%" . $params['search']['value'] . "%' OR BI.denominacion  LIKE '%" . $params['search']['value'] . "%' OR AH.denominacion  LIKE '%" . $params['search']['value'] . "%'";
        }

        $param = array("tabla" => "", "d" => $d, "tmpSql" => $sql, "proveedor" => null);
        return $this->datatableGenerico($params, $param, $limit, $nombreTabla = $sql['tablas'], $sql['where']);
           
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
                $ban=0;
                $ban2=0;
                foreach ($row["data"][$index] as $key => $value) {
                    if ($key == "cveAdscripcion") {
                        $registro[] = $this->getAdscripcionNombre($value);
                    }else if($key=="fechaInicio"){
                        $registro[] = $this->fechaNormal($value, true);
                    }else if($key=="cveEstatusPlaneacion" && $value==23){
                        $ban=1;
                    }else if($key=="cveEstatusFinanzas"){
                         //$registro[] = "Solicitado";
                        if($value==29){
                           $ban2=1; 
                        }
                        if($ban==1 && $ban2 == 1){
                            $registro[] = "Aceptado";
                        }else{
                            $registro[] = "Solicitado";
                        }
                    }
                    else {
                        if ($this->validateDate($value)) {
                            if (array_key_exists("fechaHora", $extras) && $extras["fechaHora"]) {
                                $registro[] = $this->fechaNormal($value, true);
                            } elseif (array_key_exists("fecha", $extras) && $extras["fecha"]) {
                                $registro[] = $this->fechaNormal($value);
                            }
                        } else {
                            if($value == 24){
                                $value="Solicitado";
                            }else if($value == 64){
                                $value="Solicitado";
                            }
                            $registro[] = $value;
                        }
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
    function fechaNormal($fecha) {
        $arrFecha = explode(" ", $fecha);
        list($year, $mes, $dia) = explode("-", $arrFecha[0]);
//        var_dump($dia);
//        var_dump($mes);
//        var_dump($year);
        return $dia . "/" . $mes . "/" . $year . " ";
    }
    function validateDate($date, $format = 'Y-m-d H:i:s') {
        $d = DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) == $date;
    }
}