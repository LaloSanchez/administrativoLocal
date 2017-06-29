<?php

include_once(dirname(__FILE__) . "/../../../modelos/dao/GenericDAO.Class.php");
include_once(dirname(__FILE__) . "/../../../tribunal/json/JsonEncod.Class.php");
include_once(dirname(__FILE__) . "/../../../tribunal/json/JsonDecod.Class.php");
include_once(dirname(__FILE__) . "/../../../controladores/generic/GenericController.Class.php");
include_once(dirname(__FILE__) . "/../../../controladores/bitacora/BitacoraController.Class.php");

/**
 * Clase que permite consultar el anteproyectoPartidas
 *
 * @author PJ
 */
class AlineacionController {
  
    
    public function guardarMetasProyPresProgramaticos($proyectosProgramaticos = "",$cveMetaProyectoPresupuestal,$proyectosProgramaticosBorrar = "") {
        $proveedor = new Proveedor('mysql', 'ADMINISTRATIVO');
        $proveedor->connect();
        $proveedor->execute("BEGIN");
            $genericoDao = new GenericDAO();
            $d = array();
            $error = false;
            $valores = array();
            if ($proyectosProgramaticos != "") {
        foreach ($proyectosProgramaticos as $value) {
            $valores["cveMetaProyectoPresupuestal"] = $cveMetaProyectoPresupuestal;
            $valores["idProyectoProgramatico"] = $value["idProyectoProgramatico"];
            $valores["activo"] = "S";
            $valores["fechaRegistro"] = "now()";
            $valores["fechaActualizacion"] = "now()";
            $d["values"] = $valores;
            $param = array("tabla" => "tblmetasproypresprogramaticos", "d" => $d, "tmpSql" => "", "proveedor" => $proveedor);
             if($value["idMetaProyPresProgramatico"] == ""){
                $metaProy = $genericoDao->insert($param);
                if($metaProy["totalCount"] < 0){
                    $error = true;
                }
             }
        }
        }
        if ($proyectosProgramaticosBorrar != "") {
            foreach ($proyectosProgramaticosBorrar as $value) {
                $d = array();
                $error = false;
                $valores = array();
                $where = array();
                $where["idMetaProyPresProgramatico"] = $value["idMetaProyPresProgramatico"];
                $valores["activo"] = "N";
                $valores["fechaActualizacion"] = "now()";
                $d["values"] = $valores;
                $d["where"] = $where;
                $param = array("tabla" => "tblmetasproypresprogramaticos", "d" => $d, "tmpSql" => "", "proveedor" => $proveedor);

                if ($value["idMetaProyPresProgramatico"] != "") {
                    $metaProyBorrado = $genericoDao->update($param);
                    if ($metaProyBorrado["totalCount"] < 0) {
                        $error = true;
                    }
                }
            }
        }

        if(!$error){
            $proveedor->execute("COMMIT");
            $respuesta = array("type"=>"success"); 
        }else{
            $proveedor->execute("ROLLBACK");
            $respuesta = array("type"=>"error"); 
        }
        $proveedor->close();
        $jsonEncode = new Encode_JSON();
        return $jsonEncode->encode($respuesta);
    }
    public function consultarCapitulosProyectos($arrProyectosProgramaticos) {
        $arrProyectosProgramaticos = implode(",",$arrProyectosProgramaticos);
         $genericoDao = new GenericDAO();
        $d = array();
        $sql = array(
            "campos" => " c.cveCapitulo,c.capitulo,c.desCapitulo,sum(ap.montoTotal) as montoCapitulo ",
            "tablas" => " tblanteproyectospartidas ap INNER JOIN tblcog co on (ap.idCog = co.idCog) INNER JOIN tblcapitulo c on (co.cveCapitulo = c.cveCapitulo)",
            "where" => " ap.idProyectoProgramatico in  (" . $arrProyectosProgramaticos . ") ",
            "groups" => " co.cveCapitulo "
        );
        $sqlSelect = array("tabla" => "", "d" => $d, "tmpSql" => $sql, "proveedor" => null);
        $respuesta = $genericoDao->select($sqlSelect);
        $jsonEncode = new Encode_JSON();
        return $jsonEncode->encode($respuesta);
    }
    public function consultarNombresProyectosProgramaticos($arrProyectosProgramaticos) {
        $arrProyectosProgramaticos = implode(",",$arrProyectosProgramaticos);
         $genericoDao = new GenericDAO();
        $d = array();
        $sql = array(
            "campos" => " desProyectoProgramatico ",
            "tablas" => " tblproyectosprogramaticos ",
            "where" => " idProyectoProgramatico in  (" . $arrProyectosProgramaticos . ") "
        );
        $sqlSelect = array("tabla" => "", "d" => $d, "tmpSql" => $sql, "proveedor" => null);
        $respuesta = $genericoDao->select($sqlSelect);
        $jsonEncode = new Encode_JSON();
        return $jsonEncode->encode($respuesta);
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
    
    
}


