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
class IntegracionBienesController {
    /* Función para consultar los Proyectos:
     * @param array $params array que contiene los datos de la paginación
     * @return json datos para construir el datatable del cri
     */
    private $proveedor;
   public function datatableIntegracionBienes($params) {
        $d = array("limit" => "");
        $limit = array("max" => $params["limit"]["max"], "pag" => $params["limit"]["pag"]);
        $d = array("limit" => $limit);
        $where = "";

        $cveAdscripcion = $_SESSION["cveAdscripcion"];
        $cveOrganigrama = $_SESSION["cveOrganigrama"];
        $genericoDao = new GenericDAO();
        $sql = array("campos" => "ii.idIntegracionInventario, ii.idInventarioPadre, ii.desBienIntregrado",
            "tablas" => " tblintegracioninventarios ii",
            "where" => "ii.activo='S'",
            "orders" => $params["order"]["column"] . " " . $params["order"]["dir"]
        );
        if ($params['search']['value'] != "") {
            $sql['where'] = "ii.activo='S AND (ii.desBienIntregrado LIKE '%" . $params['search']['value'] . "%')" . $where;
        }
        $param = array("tabla" => "", "d" => $d, "tmpSql" => $sql, "proveedor" => null);
        $seguimiento = new SeguimientoProyectosController();
        return $seguimiento->datatableGenerico($params, $param, $limit, $nombreTabla = $sql['tablas'], $sql['where']);
    }

    public function comboClasificadorBien() {
        $d = array();
        $sql = array();
        $where = array();
        $sql = array("campos" => " * ",
            "tablas" => " tblclasificadoresbienes cb",
            "where" => " cb.activo = 'S' AND (cb.cveClasificadorBien<3 OR cb.cveClasificadorBien =7)"
            );
        $param = array("tabla" => "", "d" => $d, "tmpSql" => $sql, "proveedor" => null);
        $genericoDao = new GenericDAO();
        $recepciones = $genericoDao->select($param);
        $jsonEncode = new Encode_JSON();
        return $jsonEncode->encode($recepciones);
    }
    public function consultarBienPadre($idIntegracionInventario) {
        $d = array();
        $sql = array();
        $where = array();
        $genericoDao = new GenericDAO();

        $sql = array("campos" => " i.* ",
            "tablas" => " tblintegracioninventarios ii INNER JOIN tblinventarios i ON (ii.idInventarioPadre=i.idInventario) ",
            "where" => " ii.idIntegracionInventario=".$idIntegracionInventario
            );
        $param = array("tabla" => "", "d" => $d, "tmpSql" => $sql, "proveedor" => null);
        $integracioninventarios = $genericoDao->select($param);
        if($integracioninventarios["totalCount"] > 0){
            if($integracioninventarios["data"][0]["cveClasificadorBien"] == 1){
                $inner = "INNER JOIN tblcbm a ON (i.idReferencia = a.idCbm)";
            }else if($integracioninventarios["data"][0]["cveClasificadorBien"] == 2){
                $inner = "INNER JOIN tblcbi a ON (i.idReferencia = a.idCbi)";
            }else if($integracioninventarios["data"][0]["cveClasificadorBien"] == 7){
                $inner = "INNER JOIN tblaah a ON (i.idReferencia = a.idAah)";

            }

            $sql2 = array("campos" => " * ,i.codigoPropio as propio ".$campo,
                "tablas" => " tblintegracioninventarios ii INNER JOIN tblinventarios i ON (ii.idInventarioPadre=i.idInventario) ". $inner,
                "where" => " ii.activo='S' AND i.activo='S' AND a.activo='S' AND  ii.idIntegracionInventario=".$idIntegracionInventario
                );
            $param2 = array("tabla" => "", "d" => $d, "tmpSql" => $sql2, "proveedor" => null);
            $recepciones = $genericoDao->select($param2);
        }
        $jsonEncode = new Encode_JSON();
        return $jsonEncode->encode($recepciones);
    }
    public function consultarBienesUsados() {
        $d = array();
        $sql = array();
        $where = array();
        $arrayInventarios = array();
        $genericoDao = new GenericDAO();

        $sql = array("campos" => " ii.idInventarioPadre ",
            "tablas" => " tblintegracioninventarios ii ",
            "where" => " ii.activo='S' "
            );
        $param = array("tabla" => "", "d" => $d, "tmpSql" => $sql, "proveedor" => null);
        $integracioninventarios = $genericoDao->select($param);
        foreach ($integracioninventarios["data"] as $value) {
            array_push($arrayInventarios, $value["idInventarioPadre"]);
        }
          $sql2 = array("campos" => " bi.idInventario ",
            "tablas" => " tblbienesintegrados bi",
            "where" => " bi.activo='S' "
            );
        $param2 = array("tabla" => "", "d" => $d, "tmpSql" => $sql2, "proveedor" => null);
        $bienesinventarios = $genericoDao->select($param2);
        foreach ($bienesinventarios["data"] as $value) {
            array_push($arrayInventarios, $value["idInventario"]);
        }
        $jsonEncode = new Encode_JSON();
         $result = array(
                "status" => "success",
                "data" => $arrayInventarios
            );
        return $jsonEncode->encode($result);
    }
    
public function consultarBienHijo($idIntegracionInventario) {
        $d = array();
        $sql = array();
        $where = array();
        $genericoDao = new GenericDAO();

        $sql = array("campos" => " * ",
            "tablas" => " tblbienesintegrados bi INNER JOIN tblinventarios i ON (bi.idInventario=i.idInventario) ",
            "where" => " bi.activo='S' AND i.activo='S' AND  bi.idIntegracionInventario=".$idIntegracionInventario
            );
        $param = array("tabla" => "", "d" => $d, "tmpSql" => $sql, "proveedor" => null);
        $integracioninventarios = $genericoDao->select($param);

        if($integracioninventarios["totalCount"] > 0){
            foreach ($integracioninventarios["data"] as $value) {

                if($value["cveClasificadorBien"] == 1){
                    $inner = "INNER JOIN tblcbm a ON (i.idReferencia = a.idCbm)";
                }else if($value["cveClasificadorBien"] == 2){
                    $inner = "INNER JOIN tblcbi a ON (i.idReferencia = a.idCbi)";
                }else if($value["cveClasificadorBien"] == 7){
                    $inner = "INNER JOIN tblaah a ON (i.idReferencia = a.idAah)";
                }

                $sql2 = array("campos" => " * ,i.codigoPropio as propio",
                    "tablas" => " tblbienesintegrados bi INNER JOIN tblinventarios i ON (bi.idInventario=i.idInventario) ". $inner,
                    "where" => " bi.activo='S' AND i.activo='S' AND a.activo='S' AND  bi.idBienIntegrado=".$value["idBienIntegrado"]
                    );
                $param2 = array("tabla" => "", "d" => $d, "tmpSql" => $sql2, "proveedor" => null);
                $recepcion = $genericoDao->select($param2);
                if($recepcion["totalCount"] > 0){
                    $recepciones[] = $recepcion["data"][0];
                }
            }
            $result = array(
                "status" => "success",
                "totalCount" => count($recepciones),
                "msj" => " Consulta correcta ",
                "data" => $recepciones
            );
        }
        $jsonEncode = new Encode_JSON();
        return $jsonEncode->encode($result);
    }

    public function datatableBienPadre($params,$listaUsados) {
        switch ($params["extrasPost"]["cveClasificadorBien"]) {
            case "1":
                $tabla = "tblcbm a";
                $inner = "idcbm";
                $campos = "a.idcbm as idGeneral, a.denominacion";
            break;
            case "2":
                $tabla = "tblcbi a";
                $inner = "idCbi";
                $campos = "a.idCbi as idGeneral, a.denominacion";
            break;
            case "7":
                $tabla = "tblaah a";
                $inner = "idAah";
                $campos = "a.idAah as idGeneral, a.denominacion";
            break;
            default : 
                
            break;
        }

        $d = array("limit" => "");
        $limit = array("max" => $params["limit"]["max"], "pag" => $params["limit"]["pag"]);
        $d = array("limit" => $limit);
        $where = "";
        if(count(json_decode($listaUsados)) != 0){
            $listaUsados = implode(",", json_decode($listaUsados));
            $condicion = "AND i.idInventario NOT in (".$listaUsados.")";
        }else{
            $condicion = "";
        }
        
        $sql = array("campos" => $campos. " ,i.codigoPropio as propio,i.idInventario",
            "tablas" => $tabla." INNER JOIN tblinventarios i ON (a.".$inner." = i.idReferencia AND i.cveClasificadorBien = ".$params["extrasPost"]["cveClasificadorBien"].")",
            "where" => " a.activo = 'S' AND i.activo='S' ".$condicion
            );
         if ($params['search']['value'] != "") {
            $sql['where'] = "(a.denominacion LIKE '%" . $params['search']['value'] . "%')" . $where;
        }
        $param = array("tabla" => "", "d" => $d, "tmpSql" => $sql, "proveedor" => null);
        $seguimiento = new SeguimientoProyectosController();
        if($params["extrasPost"]["cveClasificadorBien"] == ""){
            $param = array();
        }
        return $seguimiento->datatableGenerico($params, $param, $limit, $nombreTabla = $sql['tablas'], $sql['where']);
    }

    function guardarBienIntegrado($listaBienPadre,$listaBienHijo,$desBienIntegrado) {
        $genericDao = new GenericDAO();
        $proveedor = new Proveedor('mysql', 'ADMINISTRATIVO');
        $proveedor->connect();
        $error = false;
        $proveedor->execute("BEGIN");
        $jsonDecode = new Decode_JSON();
        $listaBienPadre = $jsonDecode->decode(utf8_decode($listaBienPadre));
        $listaBienHijo = $jsonDecode->decode(utf8_decode($listaBienHijo));

        $camposIntegracionInv["desBienIntregrado"] = ($desBienIntegrado);
        $camposIntegracionInv["idInventarioPadre"] = $listaBienPadre[0]->idInventario;
        $camposIntegracionInv["activo"] = "S";
        $camposIntegracionInv["fechaRegistro"] = "now()";
        $camposIntegracionInv["fechaActualizacion"] = "now()";
        $dIntegracionInv["values"] = $camposIntegracionInv;
        $paramIntegracionInv = array("tabla" => "tblintegracioninventarios", "accionBitacora" => 184, "d" => $dIntegracionInv, "proveedor" => $proveedor);
        $IntegracionInv = $genericDao->insert($paramIntegracionInv);
        if($IntegracionInv["totalCount"] > 0){
            foreach ($listaBienHijo as $value) {
                $camposBienesIntegrados["idIntegracionInventario"] = $IntegracionInv["data"][0]["idIntegracionInventario"];
                $camposBienesIntegrados["idInventario"] = $value->idInventario;
                $camposBienesIntegrados["activo"] = "S";
                $camposBienesIntegrados["fechaRegistro"] = "now()";
                $camposBienesIntegrados["fechaActualizacion"] = "now()";
                $dBienesIntegrados["values"] = $camposBienesIntegrados;
                $paramBienesIntegrados = array("tabla" => "tblbienesintegrados", "accionBitacora" => 184, "d" => $dBienesIntegrados, "proveedor" => $proveedor);
                $BienesIntegrados = $genericDao->insert($paramBienesIntegrados);
            }
        }
        if (!$error) {
            $proveedor->execute("COMMIT");
            $result = array(
                "status" => "success",
                "msj" => " Se recibieron las entradas ",
                "bien" => $BienesIntegrados
            );
        } else {
            $proveedor->execute("ROLLBACK");
            $result = json_encode(array(
                "status" => "Error",
                "msj" => "No se pudo ingresar "
            ));
        }
        $proveedor->close();
        $jsonEncode = new Encode_JSON();
        return $jsonEncode->encode($result);

    }
    function actualizarBienIntegrado($listaBienPadre,$listaBienHijo,$desBienIntegrado,$idIntegracionInventario) {
        $genericDao = new GenericDAO();
        $proveedor = new Proveedor('mysql', 'ADMINISTRATIVO');
        $proveedor->connect();
        $error = false;
        $proveedor->execute("BEGIN");
        $jsonDecode = new Decode_JSON();
        $listaBienPadre = $jsonDecode->decode(utf8_decode($listaBienPadre));
        $listaBienHijo = $jsonDecode->decode(utf8_decode($listaBienHijo));
        $idIntegracionInventario = $listaBienPadre[0]->idIntegracionInventario;
        $whereIntegracionInv["idIntegracionInventario"] = $idIntegracionInventario;
        $camposIntegracionInv["desBienIntregrado"] = ($desBienIntegrado);
        $camposIntegracionInv["idInventarioPadre"] = $listaBienPadre[0]->idInventario;
        $camposIntegracionInv["activo"] = "S";
        $camposIntegracionInv["fechaRegistro"] = "now()";
        $camposIntegracionInv["fechaActualizacion"] = "now()";
        $dIntegracionInv["values"] = $camposIntegracionInv;
        $dIntegracionInv["where"] = $whereIntegracionInv;
        $paramIntegracionInv = array("tabla" => "tblintegracioninventarios", "accionBitacora" => 184, "d" => $dIntegracionInv, "proveedor" => $proveedor);
        $IntegracionInv = $genericDao->update($paramIntegracionInv);
        if($IntegracionInv["totalCount"] > 0){
            foreach ($listaBienHijo as $value) {
                if($value->idBienIntegrado != ""){
                    $wherebienesi["idBienIntegrado"] = $value->idBienIntegrado;
                    $dBienesIntegrados["where"] = $wherebienesi;
                }else{
                $camposBienesIntegrados["fechaRegistro"] = "now()";
                }
                $camposBienesIntegrados["idIntegracionInventario"] = $idIntegracionInventario;
                $camposBienesIntegrados["idInventario"] = $value->idInventario;
                $camposBienesIntegrados["activo"] = $value->activo;
                $camposBienesIntegrados["fechaActualizacion"] = "now()";
                $dBienesIntegrados["values"] = $camposBienesIntegrados;
                $paramBienesIntegrados = array("tabla" => "tblbienesintegrados", "accionBitacora" => 184, "d" => $dBienesIntegrados, "proveedor" => $proveedor);
                if($value->idBienIntegrado != ""){
                    $BienesIntegrados = $genericDao->update($paramBienesIntegrados);
                }else{
                    $BienesIntegrados = $genericDao->insert($paramBienesIntegrados);

                }
            }
        }
        if (!$error) {
            $proveedor->execute("COMMIT");
            $result = array(
                "status" => "success",
                "msj" => " Se recibieron las entradas ",
                "bien" => $BienesIntegrados
            );
        } else {
            $proveedor->execute("ROLLBACK");
            $result = json_encode(array(
                "status" => "Error",
                "msj" => "No se pudo ingresar "
            ));
        }
        $proveedor->close();
        $jsonEncode = new Encode_JSON();
        return $jsonEncode->encode($result);

    }
    function borrarInventario($idIntegracionInventario) {
        $genericDao = new GenericDAO();
        $proveedor = new Proveedor('mysql', 'ADMINISTRATIVO');
        $proveedor->connect();
        $error = false;
        $proveedor->execute("BEGIN");
        $jsonDecode = new Decode_JSON();
        $camposIntegracionInv["activo"] = "N";
        $camposIntegracionInv["fechaActualizacion"] = "now()";
        $whereIntegracionInv["idIntegracionInventario"] = $idIntegracionInventario;
        $dIntegracionInv["values"] = $camposIntegracionInv;
        $paramIntegracionInv = array("tabla" => "tblintegracioninventarios", "accionBitacora" => 184, "d" => $dIntegracionInv, "proveedor" => $proveedor);
        $IntegracionInv = $genericDao->update($paramIntegracionInv);
        $sql = array("campos" => " bi.* ",
            "tablas" => " tblbienesintegrados bi  ",
            "where" => " bi.idIntegracionInventario=".$idIntegracionInventario
            );
        $param = array("tabla" => "", "tmpSql" => $sql, "proveedor" => null);
        $BienesIntegrados = $genericDao->select($param);
        if($BienesIntegrados["totalCount"] > 0){
            foreach ($BienesIntegrados["data"] as $value) {
                
            if($value["idBienIntegrado"] != ""){
                    $wherebienesi["idBienIntegrado"] = $value["idBienIntegrado"];
                    $dBienesIntegrados["where"] = $wherebienesi;
                }
                $camposBienesIntegrados["activo"] = "N";
                $camposBienesIntegrados["fechaActualizacion"] = "now()";
                $dBienesIntegrados["values"] = $camposBienesIntegrados;
                $paramBienesIntegrados = array("tabla" => "tblbienesintegrados", "accionBitacora" => 184, "d" => $dBienesIntegrados, "proveedor" => $proveedor);
                if($value["idBienIntegrado"] != ""){
                    $BienesIntegrados1 = $genericDao->update($paramBienesIntegrados);
                }
            }
        }
        if (!$error) {
            $proveedor->execute("COMMIT");
            $result = array(
                "status" => "success",
                "msj" => " Se recibieron las entradas ",
                "bien" => $BienesIntegrados
            );
        } else {
            $proveedor->execute("ROLLBACK");
            $result = json_encode(array(
                "status" => "Error",
                "msj" => "No se pudo ingresar "
            ));
        }
        $proveedor->close();
        $jsonEncode = new Encode_JSON();
        return $jsonEncode->encode($result);

    }

    function fechaNormal($fecha) {
        $arrFecha = explode(" ", $fecha);
        list($year, $mes, $dia) = explode("-", $arrFecha[0]);
        return $dia . "/" . $mes . "/" . $year . " ";
    }
    function validateDate($date, $format = 'Y-m-d H:i:s') {
        $d = DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) == $date;
    }
}