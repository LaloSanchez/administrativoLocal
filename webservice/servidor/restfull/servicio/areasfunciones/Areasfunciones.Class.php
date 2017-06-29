<?php
include_once(dirname(__FILE__) . "/../../../../../aplicacion/configuracion.php");
include_once(dirname(__FILE__) . "/../../../../../tribunal/connect/Proveedor.Class.php");
include_once(dirname(__FILE__) . "/../../../../../tribunal/json/JsonEncod.Class.php");
include_once(dirname(__FILE__) . "/../../../../../modelos/dao/GenericDAO.Class.php");
include_once(dirname(__FILE__) . "/../../../../../controladores/Imagenes/ImagenesController.Class.php");
class Areasfunciones {
    
    public function consultarFunciones($cveAdscripcion){
        $genericoDao = new GenericDAO();      
        $arrayObjetivos=array();
        $d = array("limit" => "");        
        $sql = array("campos" => "desAreaFuncion",
            "tablas" => "tblareafunciones",
            "where" => " cveAdscripcion=".$cveAdscripcion." AND activo='S' " );
        $param = array("tabla" => "", "d" => $d, "tmpSql" => $sql, "proveedor" => null);
        $data = $genericoDao->select($param);
        if($data["totalCount"]>0){
            for ($i=0; $i < $data["totalCount"] ; $i++) {
                $arrayObjetivos[]=$data["data"][$i]["desAreaFuncion"];
            }
            $jsonEncode = new Encode_JSON();
            return utf8_encode($jsonEncode->encode(array("totalCount"=>count($arrayObjetivos),"error"=>"false", "data"=>$arrayObjetivos)));
        }
        return '{"totalCount":"0","error":"true"}';  
    }

}
try {    
    @$method = $_POST["accion"];
    @$cveAdscripcion = $_POST["cveAdscripcion"];    
    $obj = new Areasfunciones();
    if (method_exists($obj, $method)) {        
        echo (call_user_func_array(array($obj, $method),array($cveAdscripcion)));           
    }else{
        throw new Exception("Acción no definida", "09");
    }
    
} catch (Exception $e) {
    echo json_encode(array("status"=>"error","code"=>$e->getCode(),"msg"=>$e->getMessage()));
}