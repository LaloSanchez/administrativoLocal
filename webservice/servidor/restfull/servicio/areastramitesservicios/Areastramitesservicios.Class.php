<?php
include_once(dirname(__FILE__) . "/../../../../../aplicacion/configuracion.php");
include_once(dirname(__FILE__) . "/../../../../../tribunal/connect/Proveedor.Class.php");
include_once(dirname(__FILE__) . "/../../../../../tribunal/json/JsonEncod.Class.php");
include_once(dirname(__FILE__) . "/../../../../../modelos/dao/GenericDAO.Class.php");
class Areastramitesservicios {
    public function consultarTramitesServicios(){
        if(isset( $_POST["cveAdscripcion"])){
            $genericoDao = new GenericDAO();      
            $auxData=array();
            $d = array("limit" => "");
            $sql = array("campos" => "ts.cveTipoTramite,tt.desTipoTramite,ts.idAreaTramiteServicio,ts.tramite, ts.tituloAreaTramitesServicios,ts.desAreaTramitesServicios",
                "tablas" => "tblareatramitesservicios ts INNER JOIN tbltipotramites tt ON(ts.cveTipoTramite=tt.cveTipoTramite)",
                "where" => " ts.activo='S' AND ts.cveAdscripcion=".$_POST["cveAdscripcion"]." " );
            $param = array("tabla" => "", "d" => $d, "tmpSql" => $sql, "proveedor" => null);
            $data = $genericoDao->select($param);
            if($data["totalCount"]>0){
                for ($i=0; $i < $data["totalCount"] ; $i++) {
                    $auxData[$data["data"][$i]["cveTipoTramite"]]["cveTipoTramite"]=$data["data"][$i]["cveTipoTramite"];
                    $auxData[$data["data"][$i]["cveTipoTramite"]]["desTipoTramite"]=$data["data"][$i]["desTipoTramite"];                    
                    $auxData[$data["data"][$i]["cveTipoTramite"]]["tramServ"][$data["data"][$i]["idAreaTramiteServicio"]]["idAreaTramiteServicio"]=$data["data"][$i]["idAreaTramiteServicio"];
                    $auxData[$data["data"][$i]["cveTipoTramite"]]["tramServ"][$data["data"][$i]["idAreaTramiteServicio"]]["tramite"]=$data["data"][$i]["tramite"];
                    $auxData[$data["data"][$i]["cveTipoTramite"]]["tramServ"][$data["data"][$i]["idAreaTramiteServicio"]]["tituloAreaTramitesServicios"]=$data["data"][$i]["tituloAreaTramitesServicios"];
                    $auxData[$data["data"][$i]["cveTipoTramite"]]["tramServ"][$data["data"][$i]["idAreaTramiteServicio"]]["desAreaTramitesServicios"]=$data["data"][$i]["desAreaTramitesServicios"];                    
                }
                $jsonEncode = new Encode_JSON();
                return utf8_encode($jsonEncode->encode(array("totalCount"=>count($auxData),"error"=>"false", "data"=>$auxData)));            
            }
        }
        return '{"totalCount":"0","error":"true"}'; 
        
    }
    public function consultarDetalleTramServ(){
        //echo ">>".DEFECTO_GESTOR.'>>'.DEFECTO_BD;
        $conexion = new Proveedor('mysql', 'administrativo');
        $conexion->connect();
        $genericoDao = new GenericDAO();      
        $auxData=array();
        $auxData["formasPago"]=array();
        $auxData["lugarPago"]=array();
        $auxData["canalAtencion"]=array();
        $auxData["horarios"]=array();
        $auxData["requisitos"]=array();
        $auxData["pasos"]=array();
        $auxData["costo"]=array();
        $auxData["documentosObtener"]=array();
        $auxData["tiempoRespuesta"]=array();
        $auxData["preguntasFrecuentes"]=array();
        $d = array("limit" => "");
        $sql = array("campos" => "tp.desTipoPago","tablas" => "tblareadetalletramitesservicios d INNER JOIN tbltipospagos tp ON(d.referencia=tp.cveTipoPago)","where" => " d.claveCatalogo=1  AND d.activo='S' AND d.IdAreaTramiteServicio=".$_POST["cveTramServ"]." " );
        $param = array("tabla" => "", "d" => $d, "tmpSql" => $sql, "proveedor" => $conexion);
        $data = $genericoDao->select($param);
        if($data["totalCount"]>0){
            for ($i=0; $i < $data["totalCount"] ; $i++) {
                $auxData["formasPago"][]=$data["data"][$i]["desTipoPago"];                                
            }
        }
        $sql = array("campos" => "lp.desLugarPago","tablas" => " tblareadetalletramitesservicios d INNER JOIN tbllugarpagos lp ON(d.referencia=lp.cveLugarPago) ","where" => " d.claveCatalogo=2  AND d.activo='S' AND d.IdAreaTramiteServicio=".$_POST["cveTramServ"]." " );
        $param = array("tabla" => "", "d" => $d, "tmpSql" => $sql, "proveedor" => $conexion);
        $data = $genericoDao->select($param);
        if($data["totalCount"]>0){
            for ($i=0; $i < $data["totalCount"] ; $i++) {
                $auxData["lugarPago"][]=$data["data"][$i]["desLugarPago"];                                
            }
        }        
        $sql = array("campos" => "ca.desCanalAtencion","tablas" => " tblareadetalletramitesservicios d INNER JOIN tblcanalatencion ca ON(d.referencia=ca.cveCanalAtencion) ","where" => " d.claveCatalogo=3  AND d.activo='S' AND d.IdAreaTramiteServicio=".$_POST["cveTramServ"]." " );
        $param = array("tabla" => "", "d" => $d, "tmpSql" => $sql, "proveedor" => $conexion);
        $data = $genericoDao->select($param);
        if($data["totalCount"]>0){
            for ($i=0; $i < $data["totalCount"] ; $i++) {
                $auxData["canalAtencion"][]=$data["data"][$i]["desCanalAtencion"];                                
            }
        }
        $sql = array("campos" => "desAreaHorario","tablas" => " tblareahorarios ","where" => " idAreaTramiteServicio=".$_POST["cveTramServ"]." AND activo='S' " );
        $param = array("tabla" => "", "d" => $d, "tmpSql" => $sql, "proveedor" => $conexion);
        $data = $genericoDao->select($param);
        if($data["totalCount"]>0){
            for ($i=0; $i < $data["totalCount"] ; $i++) {
                $auxData["horarios"][]=$data["data"][$i]["desAreaHorario"];                                
            }
        }
        $sql = array("campos" => "desAreaRequisito","tablas" => " tblareasrequisitos ","where" => " idAreaTramiteServicio=".$_POST["cveTramServ"]." AND activo='S' " );
        $param = array("tabla" => "", "d" => $d, "tmpSql" => $sql, "proveedor" => $conexion);
        $data = $genericoDao->select($param);
        if($data["totalCount"]>0){
            for ($i=0; $i < $data["totalCount"] ; $i++) {
                $auxData["requisitos"][]=$data["data"][$i]["desAreaRequisito"];                                
            }
        }
        $sql = array("campos" => "desAreaPaso","tablas" => " tblareapasos ","where" => " idAreaTramiteServicio=".$_POST["cveTramServ"]." AND activo='S' " );
        $param = array("tabla" => "", "d" => $d, "tmpSql" => $sql, "proveedor" => $conexion);
        $data = $genericoDao->select($param);
        if($data["totalCount"]>0){
            for ($i=0; $i < $data["totalCount"] ; $i++) {
                $auxData["pasos"][]=$data["data"][$i]["desAreaPaso"];                                
            }
        }
        $sql = array("campos" => "idAreaCosto,costo,desAreaCosto,concepto,generaReferencia","tablas" => " tblareacostos ","where" => " idAreaTramiteServicio=".$_POST["cveTramServ"]." AND activo='S' " );
        $param = array("tabla" => "", "d" => $d, "tmpSql" => $sql, "proveedor" => $conexion);
        $data = $genericoDao->select($param);
        if($data["totalCount"]>0){
            for ($i=0; $i < $data["totalCount"] ; $i++) {
                $auxData["costo"][]=array('costo'=>$data["data"][$i]["costo"],'desAreaCosto'=>$data["data"][$i]["desAreaCosto"],'concepto'=>$data["data"][$i]["concepto"],'generaReferencia'=>$data["data"][$i]["generaReferencia"]);
            }
        }
        $sql = array("campos" => "desAreaDocumenObtener","tablas" => " tblareasdocumenobtener ","where" => " idAreaTramiteServicio=".$_POST["cveTramServ"]." AND activo='S' " );
        $param = array("tabla" => "", "d" => $d, "tmpSql" => $sql, "proveedor" => $conexion);
        $data = $genericoDao->select($param);
        if($data["totalCount"]>0){
            for ($i=0; $i < $data["totalCount"] ; $i++) {
                $auxData["documentosObtener"][]=$data["data"][$i]["desAreaDocumenObtener"];                              
            }
        }
        $sql = array("campos" => "desAreaTiempo","tablas" => " tblareatiempo ","where" => " idAreaTramiteServicio=".$_POST["cveTramServ"]." AND activo='S' " );
        $param = array("tabla" => "", "d" => $d, "tmpSql" => $sql, "proveedor" => $conexion);
        $data = $genericoDao->select($param);
        if($data["totalCount"]>0){
            for ($i=0; $i < $data["totalCount"] ; $i++) {
                $auxData["tiempoRespuesta"][]=$data["data"][$i]["desAreaTiempo"];                              
            }
        }        
        $sql = array("campos" => "pregunta,respuesta","tablas" => " tblareapreguntasfrec ","where" => " idAreaTramiteServicio=".$_POST["cveTramServ"]." AND activo='S' " );
        $param = array("tabla" => "", "d" => $d, "tmpSql" => $sql, "proveedor" => $conexion);
        $data = $genericoDao->select($param);
        if($data["totalCount"]>0){
            for ($i=0; $i < $data["totalCount"] ; $i++) {
                $auxData["preguntasFrecuentes"][]=array('pregunta' => $data["data"][$i]["pregunta"],'respuesta'=>$data["data"][$i]["respuesta"]);                              
            }
        }
        $jsonEncode = new Encode_JSON();
        return utf8_encode($jsonEncode->encode(array("totalCount"=>count($auxData),"error"=>"false", "data"=>$auxData)));                    
    }

}
try {        
    // $_POST["cveTramServ"]=1;
    // $_POST["accion"]='consultarDetalleTramServ';
    //$_POST["cveAdscripcion"]=885;
    @$accion = $_POST["accion"];
    $obj = new Areastramitesservicios();
    if (method_exists($obj, $accion)) {     
        echo (call_user_func_array(array($obj, $accion),array()));           
    }else{
        throw new Exception("Acción no definida", "09");
    }
    
} catch (Exception $e) {
    echo json_encode(array("status"=>"error","code"=>$e->getCode(),"msg"=>$e->getMessage()));
}