<?php

include_once(dirname(__FILE__) . "/../../modelos/dao/GenericDAO.Class.php");
include_once(dirname(__FILE__) . "/../../tribunal/json/JsonEncod.Class.php");
include_once(dirname(__FILE__) . "/../../tribunal/connect/Proveedor.Class.php");
include_once(dirname(__FILE__) . "/../../controladores/planeacion/SeguimientoProyectosController.Class.php");

/**
 * Clase para el Anteroyecto Programatico 
 *
 * @author PJ
 */
class AdminAnteproyectoController {
    /* Función para consultar los Proyectos:
     * @param array $params array que contiene los datos de la paginación
     * @return json datos para construir el datatable del cri
     */
    private $proveedor;
    public function consultarSeguimientoProyectos($params) {
        
        $limit = array("max" => $params["limit"]["max"],
            "pag" => $params["limit"]["pag"]);
        $d = array("limit" => $limit);

        $sql = array(
            "campos" => "tblproyectosprogramaticos.idProyectoProgramatico,tblproyectosprogramaticos.desProyectoProgramatico,tblproyectosprogramaticos.Objetivo,tblproyectosprogramaticos.estrategiaProyecto,tblproyectosprogramaticos.`fechaInicio`,tblestadosproyecto.desEstadoProyecto,tblproyectosprogramaticos.cveEstatusPlaneacion,tblproyectosprogramaticos.cveEstatusFinanzas",
            "tablas" => "tblproyectosprogramaticos INNER JOIN tblestadosproyecto ON tblproyectosprogramaticos.cveEstadoProyecto = tblestadosproyecto.cveEstadoProyecto INNER JOIN tblestatus ON tblproyectosprogramaticos.`cveEstatusPlaneacion` = tblestatus.`cveEstatus` INNER JOIN tblproyectosadscripciones ON tblproyectosadscripciones.`idProyectoProgramatico`=tblproyectosprogramaticos.`idProyectoProgramatico`",
            "orders" => $params["order"]["column"] . " " . $params["order"]["dir"],
//            "where" => "tblproyectosprogramaticos.activo = 'S' AND tblproyectosprogramaticos.cveAdscripcion = " . $_SESSION["cveAdscripcion"] . ""
            "where" => "tblproyectosprogramaticos.activo = 'S' AND tblproyectosprogramaticos.cveEstatusPlaneacion IN (24, 23, 64)",
            "groups" => "tblproyectosprogramaticos.idProyectoProgramatico"
        );
        if (!is_null($params["extras"])) {
//            var_dump($params["extras"]);
            foreach ($params["extras"] as $key => $value) {
                if($key == 'cveAdscripcion'){
                    $sql["where"] .= " AND tblproyectosadscripciones." . $key . "=" . $value . " ";
                }else{
                    if ($value != "" && $value != null) {
                        $sql["where"] .= " AND tblproyectosprogramaticos." . $key . "=" . $value . " ";
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
                return "no se pudo obtener la Adscripción";
            }
        } else {
            return "no existe";
        }
    }
    public function rechazarSolicitud($id,$comentario,$observaciones){
        $this->proveedor = new Proveedor(DEFECTO_GESTOR, DEFECTO_BD);
        $this->proveedor->connect();
        $this->proveedor->execute("BEGIN");
        $array = array();
        $array["tabla"] = "tblproyectosprogramaticos";
        $array["d"]["values"]["cveEstatusPlaneacion"] = 16;
        $array["d"]["values"]["fechaActualizacion"] = "now()";
        $array["d"]["where"]["idProyectoProgramatico"] = $id;
        $array["accionBitacora"] = "63";
        $array["tmpSql"] = "";
        $array["proveedor"] = NULL;
        $genericDao = new GenericDAO();
        $genericArray = $genericDao->update($array);
        $sql["campos"] = "cveAdscripcion";
        $sql["tablas"] = "tblproyectosadscripciones";
        $sql["where"] = "activo='S' and idProyectoProgramatico=".$id;
        $param = array("tabla" => "", "d" => "", "tmpSql" => $sql, "proveedor" => null);
        $adscripciones = $genericDao->select($param);
        $noti=new SeguimientoProyectosController();
        foreach($adscripciones["data"] as $key=>$value){
            foreach ($value as $key2=>$value2) {
                if($key2 == "cveAdscripcion"){
                    $notificacion=array(
                        "Origen" => $this->getAdscripcionPadre($_SESSION["cveAdscripcion"])["cveAdscripcion"],
                        "Destino" => $value2,
                        "cveTipoNotificacion" => "1",
                        "tituloNotificacion" => utf8_decode("Rechazó anteproyecto"),
                        "descripcionNotificacion" => $_SESSION["desAdscripcion"] .utf8_decode(" rechazó una solicitud de anteproyecto programatíco ..."),
                        "urlFormulario" => "vistas/programatico/registroAnteproyecto/frmregistroAnteproyectoView.php",
                    );
                    $notif=$noti->notificar($notificacion, $proveedor);
                }
            }
        }
        unset($genericDao);
        unset($param);
        if($genericArray["status"]=="error"){
            $this->proveedor->execute("ROLLBACK");
            return '{"totalCount":0,"error":"No se logro rechazar la solicitud"}';
            exit;
        }else{
            if($genericArray["status"]=="error"){
                $this->proveedor->execute("ROLLBACK");
                return '{"totalCount":0,"error":"No se logro rechazar la solicitud"}';
                exit;
            }else{
                $array = array();
                $array["tabla"] = "tblobservacionesplaneacion";
                $array["d"]["values"]["idProyectoProgramatico"] = $id;
                $array["d"]["values"]["comentarios"] = $comentario;
                $array["d"]["values"]["observaciones"] = $observaciones;
                $array["d"]["values"]["cveUsuarioRechazo"] = $_SESSION["cveUsuarioSistema"];
                $array["d"]["values"]["activo"] = "S";
                $array["d"]["values"]["fechaRegistro"] = "now()";
                $array["d"]["values"]["fechaActualizacion"] = 'now()';
                $array["tmpSql"] = "";
                $array["proveedor"] = NULL;
                $genericDao = new GenericDAO();
                $genericArray = $genericDao->insert($array);
            }
            if($genericArray["status"]=="error"){
                $this->proveedor->execute("ROLLBACK");
                return '{"totalCount":0,"error":"No se logro rechazar la solicitud"}';
                exit;
            }else{
                $this->proveedor->execute("COMMIT");
                $json_encode = new Encode_JSON();
                $this->proveedor->close();
            return $genericArray2=$json_encode->encode($genericArray);
            }
        }
    }
    public function aceptarSolicitud($id){
        $proveedor = new Proveedor('mysql', 'ADMINISTRATIVO');
        $proveedor->connect();
        $proveedor->execute("BEGIN");
        $error=false;
        $numEmp=$this->getNumeroEmpleado($_SESSION["cveUsuarioSistema"]);
        $array = array();
        $array["tabla"] = "tblproyectosprogramaticos";
        $array["d"]["values"]["cveEstatusPlaneacion"] = 23;
        $array["d"]["values"]["numEmpAutorizoP"] = $numEmp;
        $array["d"]["values"]["cveEstadoProyecto"] = 6;
        $array["d"]["values"]["fechaActualizacion"] = "now()";
        $array["d"]["where"]["idProyectoProgramatico"] = $id;
        $array["accionBitacora"] = "64";
        $array["tmpSql"] = "";
        $array["proveedor"] = NULL;
        $genericDao = new GenericDAO();
        $genericArray = $genericDao->update($array);
        if($genericArray["totalCount"] == 0){
            $error=true;
        }
        $sql["campos"] = "cveAdscripcion";
        $sql["tablas"] = "tblproyectosadscripciones";
        $sql["where"] = "activo='S' and idProyectoProgramatico=".$id;
        $param = array("tabla" => "", "d" => "", "tmpSql" => $sql, "proveedor" => null);
        $adscripciones = $genericDao->select($param);
        $noti=new SeguimientoProyectosController();
        foreach($adscripciones["data"] as $key=>$value){
            foreach ($value as $key2=>$value2) {
                if($key2 == "cveAdscripcion"){
                    $notificacion=array(
                        "Origen" => $this->getAdscripcionPadre($_SESSION["cveAdscripcion"])["cveAdscripcion"],
                        "Destino" => $value2,
                        "cveTipoNotificacion" => "1",
                        "tituloNotificacion" => utf8_decode("Aceptó anteproyecto"),
                        "descripcionNotificacion" => $_SESSION["desAdscripcion"] .utf8_decode(" aceptó una solicitud de anteproyecto programatíco ..."),
                        "urlFormulario" => "vistas/programatico/registroAnteproyecto/frmregistroAnteproyectoView.php",
                    );
                    $notif=$noti->notificar($notificacion, $proveedor);
                }
            }
        }
        unset($genericDao);
        unset($param);
        if($error){
            $proveedor->execute("ROLLBACK");
            $respuesta='{"totalCount":0,"status":"error"}';
        }else{
            $proveedor->execute("COMMIT");
            $json_encode = new Encode_JSON();
            $respuesta=$json_encode->encode($genericArray);
        }
        $proveedor->close();
        return $respuesta;
        
    }
    public function revisarSolicitud($id){
        $proveedor = new Proveedor('mysql', 'ADMINISTRATIVO');
        $proveedor->connect();
        $proveedor->execute("BEGIN");
        $error=false;
        $numEmp=$this->getNumeroEmpleado($_SESSION["cveUsuarioSistema"]);
        $array = array();
        $array["tabla"] = "tblproyectosprogramaticos";
        $array["d"]["values"]["cveEstatusPlaneacion"] = 64;
        $array["d"]["values"]["numEmpRevisoP"] = $numEmp;
        $array["d"]["values"]["fechaActualizacion"] = "now()";
        $array["d"]["where"]["idProyectoProgramatico"] = $id;
        $array["accionBitacora"] = "143";
        $array["tmpSql"] = "";
        $array["proveedor"] = NULL;
        $genericDao = new GenericDAO();
        $genericArray = $genericDao->update($array);
        if($genericArray["totalCount"] == 0){
            $error=true;
        }
        $sql["campos"] = "cveAdscripcion";
        $sql["tablas"] = "tblproyectosadscripciones";
        $sql["where"] = "activo='S' and idProyectoProgramatico=".$id;
        $param = array("tabla" => "", "d" => "", "tmpSql" => $sql, "proveedor" => null);
        $adscripciones = $genericDao->select($param);
        $noti=new SeguimientoProyectosController();
        foreach($adscripciones["data"] as $key=>$value){
            foreach ($value as $key2=>$value2) {
                if($key2=="cveAdscripcion"){
                   $notificacion=array(
                        "Origen" => $this->getAdscripcionPadre($_SESSION["cveAdscripcion"])["cveAdscripcion"],
                        "Destino" => $value2,
                        "cveTipoNotificacion" => "1",
                        "tituloNotificacion" => utf8_decode("Revizó Anteproyecto"),
                        "descripcionNotificacion" => $_SESSION["desAdscripcion"] .utf8_decode(" revizó una solicitud de anteproyecto programatíco ..."),
                        "urlFormulario" => "vistas/programatico/registroAnteproyecto/frmregistroAnteproyectoView.php",
                    );
                    $notif=$noti->notificar($notificacion, $proveedor); 
                }
            }
        }
        unset($genericDao);
        unset($param);
        if($error){
            $proveedor->execute("ROLLBACK");
            $respuesta='{"totalCount":0,"status":"error"}';
        }else{
            $proveedor->execute("COMMIT");
            $json_encode = new Encode_JSON();
            $respuesta=$json_encode->encode($genericArray);
        }
        return $respuesta;
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
                            if($value == 24 && $key == "cveEstatusPlaneacion"){
                                $value="Solicitado";
                            }else if($value == 64 && $key == "cveEstatusPlaneacion"){
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
}
