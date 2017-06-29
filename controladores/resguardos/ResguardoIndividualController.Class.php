<?php

include_once(dirname(__FILE__) . "/../../modelos/dao/GenericDAO.Class.php");
include_once(dirname(__FILE__) . "/../../tribunal/json/JsonEncod.Class.php");
include_once(dirname(__FILE__) . "/../../tribunal/host/Host.Class.php");
include_once(dirname(__FILE__) . "/../../tribunal/connect/Proveedor.Class.php");
include_once(dirname(__FILE__) . "/../../webservice/cliente/personal/PersonalCliente.php");
include_once(dirname(__FILE__) . "/../../task/notificaciones/NotificacionesAdministrativo.Class.php");
include_once(dirname(__FILE__) . "/../../tribunal/logger/Logger.Class.php");

//error_reporting(E_ALL ^ E_NOTICE);

class ResguardoIndividualController {

    private $proveedor;
    private $adscripcionPadreArray = array();
    private $logger;

    public function __construct() {
        $this->logger = new Logger("/../../logs/", "Resguardo");
        $this->proveedor = new Proveedor('mysql', 'ADMINISTRATIVO');
    }
    
    public function cargarDatosEmpleado($params){
        $this->proveedor->connect();
        $numEmpleado=$params["extrasPost"]["numEmpleado"];
        $genericoDao = new GenericDAO();
        $d = array();
        $sql = array(
            "campos" => "a.`idResguardoIndividual`,a.`cveAdscripcion`,a.`cveOrganigrama`,a.numEmpleado,b.`inventariado`,b.`numeroSerie`,b.`codigoPropio`,b.`precioActual`,b.`cveClasificadorBien`,b.`idReferencia`,c.desEstatus,c.cveEstatus,a.idInventario",
            "tablas" => "tblresguardosindividuales a INNER JOIN tblinventarios b ON a.`idInventario`=b.`idInventario` INNER JOIN tblestatus c on a.cveEstatus=c.cveEstatus",
            "where" => "a.activo='S' AND b.`activo`='S' AND a.numEmpleado=".$numEmpleado
        );
        $sqlSelect = array("tabla" => "", "d" => $d, "tmpSql" => $sql, "proveedor" => $this->proveedor);
        $rs = $genericoDao->select($sqlSelect);
        if($rs["totalCount"] > 0){
            foreach($rs["data"] as $key => $value){
                if($value["cveClasificadorBien"] == 2){
                    $d = array();
                    $sql = array(
                        "campos" => "denominacion",
                        "tablas" => "tblcbi",
                        "where" => "activo='S' AND idCbi=".$value["idReferencia"]
                    );
                    $param = array("tabla" => "", "d" => $d, "tmpSql" => $sql, "proveedor" => $this->proveedor);
                    $rs2 = $genericoDao->select($param);
                }else if($value["cveClasificadorBien"] == 7){
                    $d = array();
                    $sql = array(
                        "campos" => "denominacion",
                        "tablas" => "tblaah",
                        "where" => "activo='S' AND idAah=".$value["idReferencia"]
                    );
                    $param = array("tabla" => "", "d" => $d, "tmpSql" => $sql, "proveedor" => $this->proveedor);
                    $rs2 = $genericoDao->select($param);
                }else{
                    $d = array();
                    $sql = array(
                        "campos" => "denominacion",
                        "tablas" => "tblcbm",
                        "where" => "activo='S' AND idCbm=".$value["idReferencia"]
                    );
                    $param = array("tabla" => "", "d" => $d, "tmpSql" => $sql, "proveedor" => $this->proveedor);
                    $rs2 = $genericoDao->select($param);
                }
                $rs["data"][$key]["denominacion"]=$rs2["data"][0]["denominacion"];
            }
        }
        $this->proveedor->close();
        $encode = new Encode_JSON();
        return $encode->encode($rs);
    }
    
    public function cargarDatosAdscripcion($params){
        $this->proveedor->connect();
        $genericoDao = new GenericDAO();
        $d = array();
        $sql = array(
            "campos" => "a.`idResguardoIndividual`,a.`cveAdscripcion`,a.`cveOrganigrama`,a.numEmpleado,b.`inventariado`,b.`numeroSerie`,b.`codigoPropio`,b.`precioActual`,b.`cveClasificadorBien`,b.`idReferencia`,c.desEstatus,c.cveEstatus,a.idInventario",
            "tablas" => "tblresguardosindividuales a INNER JOIN tblinventarios b ON a.`idInventario`=b.`idInventario` INNER JOIN tblestatus c on a.cveEstatus=c.cveEstatus",
            "where" => "a.activo='S' AND b.`activo`='S' AND a.cveAdscripcion=".$params["extrasPost"]["cveAdscripcion"]
        );
        $sqlSelect = array("tabla" => "", "d" => $d, "tmpSql" => $sql, "proveedor" => $this->proveedor);
        $rs = $genericoDao->select($sqlSelect);
        if($rs["totalCount"] > 0){
            foreach($rs["data"] as $key => $value){
                if($value["cveClasificadorBien"] == 2){
                    $d = array();
                    $sql = array(
                        "campos" => "denominacion",
                        "tablas" => "tblcbi",
                        "where" => "activo='S' AND idCbi=".$value["idReferencia"]
                    );
                    $param = array("tabla" => "", "d" => $d, "tmpSql" => $sql, "proveedor" => $this->proveedor);
                    $rs2 = $genericoDao->select($param);
                }else if($value["cveClasificadorBien"] == 7){
                    $d = array();
                    $sql = array(
                        "campos" => "denominacion",
                        "tablas" => "tblaah",
                        "where" => "activo='S' AND idAah=".$value["idReferencia"]
                    );
                    $param = array("tabla" => "", "d" => $d, "tmpSql" => $sql, "proveedor" => $this->proveedor);
                    $rs2 = $genericoDao->select($param);
                }else{
                    $d = array();
                    $sql = array(
                        "campos" => "denominacion",
                        "tablas" => "tblcbm",
                        "where" => "activo='S' AND idCbm=".$value["idReferencia"]
                    );
                    $param = array("tabla" => "", "d" => $d, "tmpSql" => $sql, "proveedor" => $this->proveedor);
                    $rs2 = $genericoDao->select($param);
                }
                $rs["data"][$key]["denominacion"]=$rs2["data"][0]["denominacion"];
                $rs["data"][$key]["desAdscripcion"]= $this->getAdscripcionNombre($value["cveAdscripcion"]);
                $empleado=$this->getdatosEmpleado($value["numEmpleado"]);
                $rs["data"][$key]["nombreEmpleado"]= $empleado["Nombre"]." ".$empleado["Paterno"]." ".$empleado["Materno"];
            }
        }
        $this->proveedor->close();
        $encode = new Encode_JSON();
        return $encode->encode($rs);
    }
    
    public function cargarFaltantes($params){
        $this->proveedor->connect();
        $numEmpleado=$params["extrasPost"]["numEmpleado"];
        $genericoDao = new GenericDAO();
        $d = array();
        $sql = array(
            "campos" => "a.`idResguardo`,a.`cveAdscripcion`,a.`cveOrganigrama`,a.numEmpleadoResguardo,b.`inventariado`,b.`numeroSerie`,b.`codigoPropio`,b.`precioActual`,b.`cveClasificadorBien`,b.`idReferencia`,a.idInventario ",
            "tablas" => "tblResguardos a INNER JOIN tblinventarios b ON a.`idInventario`=b.idInventario",
            "where" => "a.activo='S' AND b.`activo`='S' AND a.numEmpleadoResguardo=".$numEmpleado
        );
        $sqlSelect = array("tabla" => "", "d" => $d, "tmpSql" => $sql, "proveedor" => $this->proveedor);
        $rs = $genericoDao->select($sqlSelect);
        $rs3=array();
        $rs3["status"]="success";
        $rs3["data"]=array();
        if($rs["totalCount"] > 0){
            $cont=0;
            foreach($rs["data"] as $key => $value){
                if($value["cveClasificadorBien"] == 2){
                    $d = array();
                    $sql = array(
                        "campos" => "denominacion",
                        "tablas" => "tblcbi",
                        "where" => "activo='S' AND idCbi=".$value["idReferencia"]
                    );
                    $param = array("tabla" => "", "d" => $d, "tmpSql" => $sql, "proveedor" => $this->proveedor);
                    $rs2 = $genericoDao->select($param);
                }else if($value["cveClasificadorBien"] == 7){
                    $d = array();
                    $sql = array(
                        "campos" => "denominacion",
                        "tablas" => "tblaah",
                        "where" => "activo='S' AND idAah=".$value["idReferencia"]
                    );
                    $param = array("tabla" => "", "d" => $d, "tmpSql" => $sql, "proveedor" => $this->proveedor);
                    $rs2 = $genericoDao->select($param);
                }else{
                    $d = array();
                    $sql = array(
                        "campos" => "denominacion",
                        "tablas" => "tblcbm",
                        "where" => "activo='S' AND idCbm=".$value["idReferencia"]
                    );
                    $param = array("tabla" => "", "d" => $d, "tmpSql" => $sql, "proveedor" => $this->proveedor);
                    $rs2 = $genericoDao->select($param);
                }
                $d = array();
                $sql = array(
                    "campos" => "idResguardoIndividual",
                    "tablas" => "tblresguardosindividuales a INNER JOIN tblinventarios b ON a.`idInventario`=b.`idInventario`",
                    "where" => "a.activo='S' AND b.`activo`='S' AND a.cveEstatus=122 AND a.idInventario=".$value["idInventario"]." AND a.numEmpleado=".$numEmpleado,
                );
                $sqlSelect = array("tabla" => "", "d" => $d, "tmpSql" => $sql, "proveedor" => $this->proveedor);
                $getValidar = $genericoDao->select($sqlSelect);
                if($getValidar["totalCount"] == 0){
                    $rs3["data"][$cont]=$value;
                    $rs3["data"][$cont]["denominacion"]=$rs2["data"][0]["denominacion"];
                    $cont++;
                }
            }
            $rs3["totalCount"]=$cont;
            $emp=$this->getdatosEmpleado($numEmpleado);
            $rs3["empleado"]=$emp["Nombre"]." ".$emp["Paterno"]." ".$emp["Materno"];
        }
        $this->proveedor->close();
        $encode = new Encode_JSON();
        return $encode->encode($rs3);
    }
    
    public function cargarFaltantesAdscripcion($params){
        $this->proveedor->connect();
        $genericoDao = new GenericDAO();
        $d = array();
        $sql = array(
            "campos" => "a.`idResguardo`,a.`cveAdscripcion`,a.`cveOrganigrama`,a.numEmpleadoResguardo,b.`inventariado`,b.`numeroSerie`,b.`codigoPropio`,b.`precioActual`,b.`cveClasificadorBien`,b.`idReferencia`,a.idInventario ",
            "tablas" => "tblResguardos a INNER JOIN tblinventarios b ON a.`idInventario`=b.idInventario",
            "where" => "a.activo='S' AND b.`activo`='S' AND a.cveAdscripcion=".$params["extrasPost"]["cveAdscripcion"]
        );
        $sqlSelect = array("tabla" => "", "d" => $d, "tmpSql" => $sql, "proveedor" => $this->proveedor);
        $rs = $genericoDao->select($sqlSelect);
        $rs3=array();
        $rs3["status"]="success";
        $rs3["data"]=array();
        if($rs["totalCount"] > 0){
            $cont=0;
            foreach($rs["data"] as $key => $value){
                if($value["cveClasificadorBien"] == 2){
                    $d = array();
                    $sql = array(
                        "campos" => "denominacion",
                        "tablas" => "tblcbi",
                        "where" => "activo='S' AND idCbi=".$value["idReferencia"]
                    );
                    $param = array("tabla" => "", "d" => $d, "tmpSql" => $sql, "proveedor" => $this->proveedor);
                    $rs2 = $genericoDao->select($param);
                }else if($value["cveClasificadorBien"] == 7){
                    $d = array();
                    $sql = array(
                        "campos" => "denominacion",
                        "tablas" => "tblaah",
                        "where" => "activo='S' AND idAah=".$value["idReferencia"]
                    );
                    $param = array("tabla" => "", "d" => $d, "tmpSql" => $sql, "proveedor" => $this->proveedor);
                    $rs2 = $genericoDao->select($param);
                }else{
                    $d = array();
                    $sql = array(
                        "campos" => "denominacion",
                        "tablas" => "tblcbm",
                        "where" => "activo='S' AND idCbm=".$value["idReferencia"]
                    );
                    $param = array("tabla" => "", "d" => $d, "tmpSql" => $sql, "proveedor" => $this->proveedor);
                    $rs2 = $genericoDao->select($param);
                }
                $d = array();
                $sql = array(
                    "campos" => "idResguardoIndividual",
                    "tablas" => "tblresguardosindividuales a INNER JOIN tblinventarios b ON a.`idInventario`=b.`idInventario`",
                    "where" => "a.activo='S' AND b.`activo`='S' AND a.cveEstatus=122 AND a.idInventario=".$value["idInventario"]." AND a.cveAdscripcion=".$params["extrasPost"]["cveAdscripcion"]
                );
                $sqlSelect = array("tabla" => "", "d" => $d, "tmpSql" => $sql, "proveedor" => $this->proveedor);
                $getValidar = $genericoDao->select($sqlSelect);
                if($getValidar["totalCount"] == 0){
                    $rs3["data"][$cont]=$value;
                    $rs3["data"][$cont]["denominacion"]=$rs2["data"][0]["denominacion"];
                    $empleado=$this->getdatosEmpleado($value["numEmpleadoResguardo"]);
                    $rs3["data"][$cont]["nombreEmpleado"]= $empleado["Nombre"]." ".$empleado["Paterno"]." ".$empleado["Materno"];
                    $cont++;
                }
            }
            $rs3["totalCount"]=$cont;
            $ads=$this->getdatosAdscripcion($params["extrasPost"]["cveAdscripcion"]);
            $rs3["adscripcion"]= $ads["desJuz"];
        }
        $this->proveedor->close();
        $encode = new Encode_JSON();
        return $encode->encode($rs3);
    }
    
    public function cargarSobrantes($params){
        $this->proveedor->connect();
        $numEmpleado=$params["extrasPost"]["numEmpleado"];
        $genericoDao = new GenericDAO();
        $d = array();
        $sql = array(
            "campos" => "a.`idResguardoIndividual`,a.`cveAdscripcion`,a.`cveOrganigrama`,a.numEmpleado,b.`inventariado`,b.`numeroSerie`,b.`codigoPropio`,b.`precioActual`,b.`cveClasificadorBien`,b.`idReferencia`,a.idInventario",
            "tablas" => "tblresguardosindividuales a INNER JOIN tblinventarios b ON a.`idInventario`=b.`idInventario`",
            "where" => "a.activo='S' AND b.`activo`='S' AND a.cveEstatus=122 AND a.numEmpleado=".$numEmpleado
        );
        $sqlSelect = array("tabla" => "", "d" => $d, "tmpSql" => $sql, "proveedor" => $this->proveedor);
        $rs = $genericoDao->select($sqlSelect);
        $rs3=array();
        $rs3["status"]="success";
        $rs3["data"]=array();
        if($rs["totalCount"] > 0){
            $cont=0;
            foreach($rs["data"] as $key => $value){
                if($value["cveClasificadorBien"] == 2){
                    $d = array();
                    $sql = array(
                        "campos" => "denominacion",
                        "tablas" => "tblcbi",
                        "where" => "activo='S' AND idCbi=".$value["idReferencia"]
                    );
                    $param = array("tabla" => "", "d" => $d, "tmpSql" => $sql, "proveedor" => $this->proveedor);
                    $rs2 = $genericoDao->select($param);
                }else if($value["cveClasificadorBien"] == 7){
                    $d = array();
                    $sql = array(
                        "campos" => "denominacion",
                        "tablas" => "tblaah",
                        "where" => "activo='S' AND idAah=".$value["idReferencia"]
                    );
                    $param = array("tabla" => "", "d" => $d, "tmpSql" => $sql, "proveedor" => $this->proveedor);
                    $rs2 = $genericoDao->select($param);
                }else{
                    $d = array();
                    $sql = array(
                        "campos" => "denominacion",
                        "tablas" => "tblcbm",
                        "where" => "activo='S' AND idCbm=".$value["idReferencia"]
                    );
                    $param = array("tabla" => "", "d" => $d, "tmpSql" => $sql, "proveedor" => $this->proveedor);
                    $rs2 = $genericoDao->select($param);
                }
                $d = array();
                $sql = array(
                    "campos" => "idResguardo",
                    "tablas" => "tblresguardos a INNER JOIN tblinventarios b ON a.`idInventario`=b.`idInventario`",
                    "where" => "a.activo='S' AND b.`activo`='S' AND a.idInventario=".$value["idInventario"]." AND a.numEmpleadoResguardo=".$numEmpleado
                );
                $sqlSelect = array("tabla" => "", "d" => $d, "tmpSql" => $sql, "proveedor" => $this->proveedor);
                $getValidar = $genericoDao->select($sqlSelect);
                if($getValidar["totalCount"] == 0){
                    $rs3["data"][$cont]=$value;
                    $rs3["data"][$cont]["denominacion"]=$rs2["data"][0]["denominacion"];
                    $cont++;
                }
            }
            $rs3["totalCount"]=$cont;
            $emp=$this->getdatosEmpleado($numEmpleado);
            $rs3["empleado"]=$emp["Nombre"]." ".$emp["Paterno"]." ".$emp["Materno"];
        }else{
            $rs3["totalCount"]=0;
            $rs3["status"]="success";
            $emp=$this->getdatosEmpleado($numEmpleado);
            $rs3["empleado"]=$emp["Nombre"]." ".$emp["Paterno"]." ".$emp["Materno"];
        }
        $this->proveedor->close();
        $encode = new Encode_JSON();
        return $encode->encode($rs3);
    }

    public function cargarSobrantesAdscripcion($params){
        $this->proveedor->connect();
        $genericoDao = new GenericDAO();
        $d = array();
        $sql = array(
            "campos" => "a.`idResguardoIndividual`,a.`cveAdscripcion`,a.`cveOrganigrama`,a.numEmpleado,b.`inventariado`,b.`numeroSerie`,b.`codigoPropio`,b.`precioActual`,b.`cveClasificadorBien`,b.`idReferencia`,a.idInventario",
            "tablas" => "tblresguardosindividuales a INNER JOIN tblinventarios b ON a.`idInventario`=b.`idInventario`",
            "where" => "a.activo='S' AND b.`activo`='S' AND a.cveEstatus=122 AND a.cveAdscripcion=".$params["extrasPost"]["cveAdscripcion"]
        );
        $sqlSelect = array("tabla" => "", "d" => $d, "tmpSql" => $sql, "proveedor" => $this->proveedor);
        $rs = $genericoDao->select($sqlSelect);
        $rs3=array();
        $rs3["status"]="success";
        $rs3["data"]=array();
        if($rs["totalCount"] > 0){
            $cont=0;
            foreach($rs["data"] as $key => $value){
                if($value["cveClasificadorBien"] == 2){
                    $d = array();
                    $sql = array(
                        "campos" => "denominacion",
                        "tablas" => "tblcbi",
                        "where" => "activo='S' AND idCbi=".$value["idReferencia"]
                    );
                    $param = array("tabla" => "", "d" => $d, "tmpSql" => $sql, "proveedor" => $this->proveedor);
                    $rs2 = $genericoDao->select($param);
                }else if($value["cveClasificadorBien"] == 7){
                    $d = array();
                    $sql = array(
                        "campos" => "denominacion",
                        "tablas" => "tblaah",
                        "where" => "activo='S' AND idAah=".$value["idReferencia"]
                    );
                    $param = array("tabla" => "", "d" => $d, "tmpSql" => $sql, "proveedor" => $this->proveedor);
                    $rs2 = $genericoDao->select($param);
                }else{
                    $d = array();
                    $sql = array(
                        "campos" => "denominacion",
                        "tablas" => "tblcbm",
                        "where" => "activo='S' AND idCbm=".$value["idReferencia"]
                    );
                    $param = array("tabla" => "", "d" => $d, "tmpSql" => $sql, "proveedor" => $this->proveedor);
                    $rs2 = $genericoDao->select($param);
                }
                $d = array();
                $sql = array(
                    "campos" => "idResguardo",
                    "tablas" => "tblresguardos a INNER JOIN tblinventarios b ON a.`idInventario`=b.`idInventario`",
                    "where" => "a.activo='S' AND b.`activo`='S' AND a.idInventario=".$value["idInventario"]." AND a.cveAdscripcion=".$params["extrasPost"]["cveAdscripcion"]
                );
                $sqlSelect = array("tabla" => "", "d" => $d, "tmpSql" => $sql, "proveedor" => $this->proveedor);
                $getValidar = $genericoDao->select($sqlSelect);
                if($getValidar["totalCount"] == 0){
                    $rs3["data"][$cont]=$value;
                    $rs3["data"][$cont]["denominacion"]=$rs2["data"][0]["denominacion"];
                    $empleado=$this->getdatosEmpleado($value["numEmpleado"]);
                    $rs3["data"][$cont]["nombreEmpleado"]= $empleado["Nombre"]." ".$empleado["Paterno"]." ".$empleado["Materno"];
                    $cont++;
                }
            }
            $rs3["totalCount"]=$cont;
            $ads=$this->getdatosAdscripcion($params["extrasPost"]["cveAdscripcion"]);
            $rs3["adscripcion"]= $ads["desJuz"];
        }
        $this->proveedor->close();
        $encode = new Encode_JSON();
        return $encode->encode($rs3);
    }
    
    public function getBien($params){
        $this->proveedor->connect();
        $genericoDao = new GenericDAO();
        $d = array();
        $sql = array(
            "campos" => "codigoPropio,idReferencia,cveClasificadorBien,idInventario",
            "tablas" => "tblinventarios",
            "where" => "activo='S' AND codigoPropio='".$params["extrasPost"]["codigoPropio"]."'"
        );
        $sqlSelect = array("tabla" => "", "d" => $d, "tmpSql" => $sql, "proveedor" => $this->proveedor);
        $rs = $genericoDao->select($sqlSelect);
        if($rs["totalCount"] > 0){
            foreach($rs["data"] as $key => $value){
                if($value["cveClasificadorBien"] == 2){
                    $d = array();
                    $sql = array(
                        "campos" => "denominacion",
                        "tablas" => "tblcbi",
                        "where" => "activo='S' AND idCbi=".$value["idReferencia"]
                    );
                    $param = array("tabla" => "", "d" => $d, "tmpSql" => $sql, "proveedor" => $this->proveedor);
                    $rs2 = $genericoDao->select($param);
                }else if($value["cveClasificadorBien"] == 7){
                    $d = array();
                    $sql = array(
                        "campos" => "denominacion",
                        "tablas" => "tblaah",
                        "where" => "activo='S' AND idAah=".$value["idReferencia"]
                    );
                    $param = array("tabla" => "", "d" => $d, "tmpSql" => $sql, "proveedor" => $this->proveedor);
                    $rs2 = $genericoDao->select($param);
                }else{
                    $d = array();
                    $sql = array(
                        "campos" => "denominacion",
                        "tablas" => "tblcbm",
                        "where" => "activo='S' AND idCbm=".$value["idReferencia"]
                    );
                    $param = array("tabla" => "", "d" => $d, "tmpSql" => $sql, "proveedor" => $this->proveedor);
                    $rs2 = $genericoDao->select($param);
                }
                $rs["data"][$key]["denominacion"]=$rs2["data"][0]["denominacion"];
            }
        }
        $this->proveedor->close();
        $encode = new Encode_JSON();
        return $encode->encode($rs);
    }
    
    public function validarGuardarNuevoBien($params){
        $genericoDao = new GenericDAO();
        $d = array();
        $sql = array(
            "campos" => "b.`numEmpleadoResguardo`",
            "tablas" => "tblinventarios a INNER JOIN tblresguardos b ON a.`idInventario`=b.`idInventario`",
            "where" => "a.activo='S' AND b.`activo`='S' AND a.codigoPropio='".$params["extrasPost"]["codigoPropio"]."'"
        );
        $sqlSelect = array("tabla" => "", "d" => $d, "tmpSql" => $sql, "proveedor" => null);
        $rs = $genericoDao->select($sqlSelect);
        if($rs["totalCount"] > 0){
            if($rs["data"][0]["numEmpleadoResguardo"] == $params["extrasPost"]["numeroEmpleado"]){
                return '{"totalCount":0,"status":"tuyo"}';
            }else{
                return '{"totalCount":0,"status":"alguien"}';
            }
        }else{
            return '{"totalCount":0,"status":"noAsignado"}';
        }
    }
    
    public function guardarNuevoBien($params){
        $this->proveedor->connect();
        $genericDao = new GenericDAO();
            $d = array();
            $sql = array(
                "campos" => "a.idInventario",
                "tablas" => "tblinventarios a INNER JOIN tblresguardosindividuales b",
                "where" => "a.activo='S' AND b.activo='S' AND b.idInventario=".$params["extrasPost"]["idInventario"]." AND b.numEmpleado=".$params["extrasPost"]["numeroEmpleado"]
            );
            $sqlSelect = array("tabla" => "", "d" => $d, "tmpSql" => $sql, "proveedor" => $this->proveedor);
            $rs2 = $genericDao->select($sqlSelect);
            if($rs2["totalCount"] == 0){
                $empleado=$this->getdatosEmpleado($params["extrasPost"]["numeroEmpleado"]);
                $adscripcion = $this->getdatosAdscripcion($empleado[0]["cveJuzgado"]);
                $array = array();
                $array["tabla"] = "tblresguardosindividuales";
                $array["d"]["values"]["cveEstatus"] = 121;
                $array["d"]["values"]["idInventario"] = $params["extrasPost"]["idInventario"];
                $array["d"]["values"]["cveRegion"] = $adscripcion["cveRegion"];//$monto;
                $array["d"]["values"]["cveAdscripcion"] = $adscripcion["idJuzgado"];//$monto;
                $array["d"]["values"]["cveOrganigrama"] = $adscripcion["cveOrganigrama"];
                $array["d"]["values"]["numEmpleado"] = $params["extrasPost"]["numeroEmpleado"];
                $array["d"]["values"]["activo"] = 'S';
                $array["d"]["values"]["fechaRegistro"] = 'now()';
                $array["d"]["values"]["fechaActualizacion"] = 'now()';
                $array["tmpSql"] = "";
                $array["proveedor"] = $this->proveedor;
                $insertar = $genericDao->insert($array);
                $this->proveedor->close();
                $encode =new Encode_JSON();
                return $encode->encode($insertar);
            }else{
                $this->proveedor->close();
                return '{"totalCount":0,"status":"ya"}';
            }
    }
    
    public function finalizarRegistro($params){
        $this->proveedor->connect();
        $this->proveedor->execute("BEGIN");
        $error=false;
        $genericDao=new GenericDAO();
        foreach($params["extrasPost"]["listaResguardoDesmarcada"] as $key2 => $value2){
            $array = array();
            $array["tabla"] = "tblresguardosindividuales";
            $array["d"]["values"]["activo"] = 'N';
            $array["d"]["values"]["fechaActualizacion"] = 'now()';
            $array["d"]["where"]["idResguardoIndividual"] = $value2;
            $array["tmpSql"] = "";
            $array["proveedor"] = $this->proveedor;
            $insertar = $genericDao->update($array);
            if($insertar["totalCount"] == 0){
                $error =true;
            }else{
                $d = array();
                $sql = array(
                    "campos" => "idInventario",
                    "tablas" => "tblresguardos",
                    "where" => "activo='S' AND idInventario=".$insertar["data"][0]["idInventario"]." AND numEmpleado=".$params["extrasPost"]["numeroEmpleado"]
                );
                $sqlSelect = array("tabla" => "", "d" => $d, "tmpSql" => $sql, "proveedor" => $this->proveedor);
                $rs2 = $genericDao->select($sqlSelect);
                if($rs2["totalCount"] > 0){
                    $array = array();
                    $array["tabla"] = "tblresguardos";
                    $array["d"]["values"]["activo"] = 'N';
                    $array["d"]["values"]["fechaActualizacion"] = 'now()';
                    $array["d"]["where"]["idInventario"] = $insertar["data"][0]["idInventario"];
                    $array["tmpSql"] = "";
                    $array["proveedor"] = $this->proveedor;
                    $eliminarResguardo = $genericDao->update($array);
                    if($eliminarResguardo["totalCount"] == 0){
                        $error =true;
                    }
                }
            }
        }
        if(!$error){
            $this->proveedor->execute("COMMIT");
            $this->proveedor->close();
            return '{"totalCount":0,"status":"success"}';
        }else{
            $this->proveedor->execute("ROLLBACK");
            $this->proveedor->close();
            return '{"totalCount":0,"status":"error"}';
        }
    }
    
    public function getEmpleado($params){
        $empleado=$this->getdatosEmpleado($params["extrasPost"]["numEmpleado"]);
        $adscripcion=$this->getAdscripcionNombre($empleado[0]["cveJuzgado"]);
        $rs["totalCount"]=1;
        $rs["status"]="success";
        $rs["data"][0]["desAdscripcion"]= $adscripcion;
        $rs["data"][0]["empleado"]= $this->getNombreEmpleadoSuficiencia($params["extrasPost"]["numEmpleado"]);
        $rs["data"][0]["numeroEmpleado"]= $empleado[0]["numEmpleado"];
        $encode = new Encode_JSON();
        return $encode->encode($rs);
    }
    
    public function registrarBien($params){
        $this->proveedor->connect();
        $this->proveedor->execute("BEGIN");
        $error=false;
        $genericDao = new GenericDAO();
        $d = array();
        $sql = array(
            "campos" => "a.idResguardoIndividual,a.idInventario",
            "tablas" => "tblresguardosindividuales a INNER JOIN tblinventarios b ON a.idInventario=b.idInventario ",
            "where" => "a.activo='S' AND b.activo='S' AND b.codigoPropio='".$params["extrasPost"]["codigoPropio"]."' AND a.numEmpleado=".$params["extrasPost"]["numEmpleado"]
        );
        $sqlSelect = array("tabla" => "", "d" => $d, "tmpSql" => $sql, "proveedor" => $this->proveedor);
        $rs = $genericDao->select($sqlSelect);
        if($rs["totalCount"] == 0){
            $d = array();
            $sql = array(
                "campos" => "*",
                "tablas" => "tblinventarios",
                "where" => "activo='S' AND codigoPropio='".$params["extrasPost"]["codigoPropio"]."'"
            );
            $sqlSelect = array("tabla" => "", "d" => $d, "tmpSql" => $sql, "proveedor" => $this->proveedor);
            $rs2 = $genericDao->select($sqlSelect);
            if($rs2["totalCount"] > 0){
                $array = array();
                $array["tabla"] = "tblresguardosindividuales";
                $array["d"]["values"]["activo"] = 'N';
                $array["d"]["values"]["fechaActualizacion"] = 'now()';
                $array["d"]["where"]["idInventario"] = $rs2["data"][0]["idInventario"];
                $array["tmpSql"] = "";
                $array["proveedor"] = $this->proveedor;
                $bajaResguardosindividuales = $genericDao->update($array);
                $datosAds=$this->getdatosAdscripcionByIdNumEmpleado($params["extrasPost"]["numEmpleado"]);
                $array = array();
                $array["tabla"] = "tblresguardosindividuales";
                $array["d"]["values"]["cveEstatus"] = 122;
                $array["d"]["values"]["idInventario"] = $rs2["data"][0]["idInventario"];
                $array["d"]["values"]["cveRegion"] = $datosAds["cveRegion"];//$monto;
                $array["d"]["values"]["cveAdscripcion"] = $datosAds["idJuzgado"];//$monto;
                $array["d"]["values"]["cveOrganigrama"] = $datosAds["cveOrganigrama"];
                $array["d"]["values"]["numEmpleado"] = $params["extrasPost"]["numEmpleado"];
                $array["d"]["values"]["activo"] = 'S';
                $array["d"]["values"]["fechaRegistro"] = 'now()';
                $array["d"]["values"]["fechaActualizacion"] = 'now()';
                $array["tmpSql"] = "";
                $array["proveedor"] = $this->proveedor;
                $insertar = $genericDao->insert($array);
//                if($insertar["totalCount"] > 0){
//                    $d = array();
//                    $sql = array(
//                        "campos" => "*",
//                        "tablas" => "tblresguardos",
//                        "where" => "activo='S' AND idInventario=".$rs2["data"][0]["idInventario"]." AND numEmpleadoResguardo=".$params["extrasPost"]["numEmpleado"]
//                    );
//                    $sqlSelect = array("tabla" => "", "d" => $d, "tmpSql" => $sql, "proveedor" => $this->proveedor);
//                    $rs3 = $genericDao->select($sqlSelect);
//                    if($rs3["totalCount"] == 0){
//                        $array = array();
//                        $array["tabla"] = "tblresguardos";
//                        $array["d"]["values"]["activo"] = 'N';
//                        $array["d"]["values"]["fechaActualizacion"] = 'now()';
//                        $array["d"]["where"]["idInventario"] = $rs2["data"][0]["idInventario"];
//                        $array["tmpSql"] = "";
//                        $array["proveedor"] = $this->proveedor;
//                        $bajaResguardos = $genericDao->update($array);
//                        
//                        $array = array();
//                        $array["tabla"] = "tblresguardos";
//                        $array["d"]["values"]["idInventario"] = $rs2["data"][0]["idInventario"];
//                        $array["d"]["values"]["cveRegion"] = 0;//$monto;
//                        $array["d"]["values"]["cveAdscripcion"] = 0;//$monto;
//                        $array["d"]["values"]["cveOrganigrama"] = 0;
//                        $array["d"]["values"]["numEmpleadoResguardo"] = $params["extrasPost"]["numEmpleado"];
//                        $array["d"]["values"]["activo"] = 'S';
//                        $array["d"]["values"]["fechaRegistro"] = 'now()';
//                        $array["d"]["values"]["fechaActualizacion"] = 'now()';
//                        $array["tmpSql"] = "";
//                        $array["proveedor"] = $this->proveedor;
//                        $insertarNuevo = $genericDao->insert($array);
//                        if($insertarNuevo["totalCount"] == 0){
//                            $error=true;
//                        }
//                    }
//                }else{
//                    $error=true;
//                }
            }else{
                $error=true;
            }
        }else{
            $array = array();
            $array["tabla"] = "tblresguardosindividuales";
            $array["d"]["values"]["cveEstatus"] = 122;
            $array["d"]["values"]["fechaActualizacion"] = 'now()';
            $array["d"]["where"]["idResguardoIndividual"] = $rs["data"][0]["idResguardoIndividual"];
            $array["tmpSql"] = "";
            $array["proveedor"] = $this->proveedor;
            $estatusResguardo = $genericDao->update($array);
            if($estatusResguardo["totalCount"] > 0){
//                $d = array();
//                $sql = array(
//                    "campos" => "*",
//                    "tablas" => "tblresguardos",
//                    "where" => "activo='S' AND idInventario=".$rs["data"][0]["idInventario"]." AND numEmpleadoResguardo=".$params["extrasPost"]["numEmpleado"]
//                );
//                $sqlSelect = array("tabla" => "", "d" => $d, "tmpSql" => $sql, "proveedor" => $this->proveedor);
//                $rs3 = $genericDao->select($sqlSelect);
//                if($rs3["totalCount"] == 0){
//                    $array = array();
//                    $array["tabla"] = "tblresguardos";
//                    $array["d"]["values"]["activo"] = 'N';
//                    $array["d"]["values"]["fechaActualizacion"] = 'now()';
//                    $array["d"]["where"]["idInventario"] = $rs["data"][0]["idInventario"];
//                    $array["tmpSql"] = "";
//                    $array["proveedor"] = $this->proveedor;
//                    $bajaResguardos = $genericDao->update($array);
//
//                    $array = array();
//                    $array["tabla"] = "tblresguardos";
//                    $array["d"]["values"]["idInventario"] = $rs["data"][0]["idInventario"];
//                    $array["d"]["values"]["cveRegion"] = 0;//$monto;
//                    $array["d"]["values"]["cveAdscripcion"] = 0;//$monto;
//                    $array["d"]["values"]["cveOrganigrama"] = 0;
//                    $array["d"]["values"]["numEmpleadoResguardo"] = $params["extrasPost"]["numEmpleado"];
//                    $array["d"]["values"]["activo"] = 'S';
//                    $array["d"]["values"]["fechaRegistro"] = 'now()';
//                    $array["d"]["values"]["fechaActualizacion"] = 'now()';
//                    $array["tmpSql"] = "";
//                    $array["proveedor"] = $this->proveedor;
//                    $insertarNuevo = $genericDao->insert($array);
//                    if($insertarNuevo["totalCount"] == 0){
//                        $error=true;
//                    }
//                }
            }else{
                $error=true;
            }
        }
        if(!$error){
            $this->proveedor->execute("COMMIT");
            $this->proveedor->close();
            return '{"totalCount":0,"status":"success"}';
        }else{
            $this->proveedor->execute("ROLLBACK");
            $this->proveedor->close();
            return '{"totalCount":0,"status":"error"}';
        }
    }
    
    public function eliminarBien($params){
        $this->proveedor->connect();
        $this->proveedor->execute("BEGIN");
        $error=false;
        $genericDao=new GenericDAO();
        
        $array = array();
        $array["tabla"] = "tblresguardosindividuales";
        $array["d"]["values"]["activo"] = 'N';
        $array["d"]["values"]["fechaActualizacion"] = 'now()';
        $array["d"]["where"]["idResguardoIndividual"] = $params["extrasPost"]["idResguardoIndividual"];
        $array["tmpSql"] = "";
        $array["proveedor"] = $this->proveedor;
        $eliminarResguardo = $genericDao->update($array);
        if($eliminarResguardo["totalCount"] == 0){
            $error =true;
        }
        if(!$error){
            $this->proveedor->execute("COMMIT");
            $this->proveedor->close();
            return '{"totalCount":0,"status":"success"}';
        }else{
            $this->proveedor->execute("ROLLBACK");
            $this->proveedor->close();
            return '{"totalCount":0,"status":"error"}';
        }
    }
    
    public function encryptStringArray($stringArray, $key) {
        $s = strtr(base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, md5($key), serialize($stringArray), MCRYPT_MODE_CBC, md5(md5($key)))), '+/=', '-_,');
        return $s;
//        return trim(base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, "SALT", $stringArray, MCRYPT_MODE_ECB, mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND))));
    }

    public function decryptStringArray($stringArray, $key) {
        $s = unserialize(rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, md5($key), base64_decode(strtr($stringArray, '-_,', '+/=')), MCRYPT_MODE_CBC, md5(md5($key))), "\0"));
        return $s;
//        return trim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, "SALT", base64_decode($stringArray), MCRYPT_MODE_ECB, mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND)));
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
                            return utf8_decode($value["desJuz"]);
                        }
                    }
                }
            } else {
                return utf8_decode("no se pudo obtener la Adscripción");
            }
        } else {
            return "no existe";
        }
    }
    
    public function getdatosEmpleado($value4 = null){
        $fileJson = dirname(__FILE__) . "/../../archivos/informacionEmpleados.json";
        if (file_exists($fileJson)) {
            $json = file_get_contents($fileJson);
            $json = json_decode($json, true);
                foreach ($json["data"] as $key => $value) {
                    foreach ($value["personal"] as $key2 => $value2) {
                        foreach($value2 as $key3 => $value3){
                            if ($key3 == "NumEmpleado" && $value3 == $value4) {
                                return $value2;
                            }
                        }
                    }
                }
        } else {
            return "no existe";
        }
    }
    
    public function getdatosAdscripcion($ads=null){
        $fileJson = dirname(__FILE__) . "/../../archivos/informacionEmpleados.json";
        if (file_exists($fileJson)) {
            $json = file_get_contents($fileJson);
            $json = json_decode($json, true);
                foreach ($json["data"] as $key => $value) {
                    foreach ($value as $key2 => $value2) {
                        if ($key2 == "idJuzgado" && $value2 == $ads) {
                            return $value;
                        }
                    }
                }
            
        } else {
            return "no existe";
        }
    }
    
    public function getdatosAdscripcionByIdNumEmpleado($numEmpleado){
        $fileJson = dirname(__FILE__) . "/../../archivos/informacionEmpleados.json";
        if (file_exists($fileJson)) {
            $json = file_get_contents($fileJson);
            $json = json_decode($json, true);
            
                foreach ($json["data"] as $key => $value) {
                    foreach ($value["personal"] as $key2 => $value2) {
                        foreach($value2 as $key3 => $value4){
                            if($key3 == "NumEmpleado" && $value4 == $numEmpleado){
                                unset($value['personal']);
                                return $value;
                            }
                        }
                    }
                }
            
        } else {
            return "no existe";
        }
    }
    
}
//$resguardo=new ResguardoIndividualController();
//var_dump($resguardo->encryptStringArray(3012, "secret"));
//echo "<br>";
//var_dump($resguardo->decryptStringArray("6NaWnW4UKW_czOjJ6KPx8MfoLJt-v7mKPtfnIi6NrO8,", "secret"));