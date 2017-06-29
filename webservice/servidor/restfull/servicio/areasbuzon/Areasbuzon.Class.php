<?php
include_once(dirname(__FILE__) . "/../../../../../aplicacion/configuracion.php");
include_once(dirname(__FILE__) . "/../../../../../tribunal/connect/Proveedor.Class.php");
include_once(dirname(__FILE__) . "/../../../../../tribunal/json/JsonEncod.Class.php");
include_once(dirname(__FILE__) . "/../../../../../modelos/dao/GenericDAO.Class.php");
class Areasbuzon {
    public function nuevoMensaje(){
        if(isset( $_POST["queja"]) AND isset( $_POST["cveAdscripcionOrigen"]) AND isset( $_POST["cveOrganigramaOrigen"]) AND isset( $_POST["descripcionAreaBuzon"])
            AND isset( $_POST["numEmpleado"]) AND isset( $_POST["descripcionAreaBuzon"]) AND isset( $_POST["cveAdscripcionDestino"]) AND isset( $_POST["cveOrganigramaDestino"]) 
            ){
            $genericoDao = new GenericDAO();
            $jsonEncode = new Encode_JSON();
             $param = array(
                "tabla" => "tblareabuzon",
                "d" => array(
                    "values" => array(
                        "queja" => $_POST["queja"],
                        "cveAdscripcionOrigen" => $_POST["cveAdscripcionOrigen"],
                        "cveOrganigramaOrigen" => $_POST["cveOrganigramaOrigen"],
                        "numEmpleado"=>$_POST["numEmpleado"],
                        "descripcionAreaBuzon" => str_ireplace("'", "\\'", utf8_decode(urldecode($_POST["descripcionAreaBuzon"]))),
                        "cveAdscripcionDestino" => $_POST["cveAdscripcionDestino"],
                        "cveOrganigramaDestino" => $_POST["cveOrganigramaDestino"],
                        "activo" => "S",
                        "fechaRegistro" => "now()",
                        "fechaActualizacion" => "now()"
                    )
                ),
                "proveedor" => null
            );         
            return $jsonEncode->encode($genericoDao->insert($param));
        }
        return '{"totalCount":"0","error":"true"}'; 
        
    }

}
try {        
    @$accion = $_POST["accion"];
    $obj = new Areasbuzon();
    if (method_exists($obj, $accion)) {     
        echo (call_user_func_array(array($obj, $accion),array()));           
    }else{
        throw new Exception("Acción no definida", "09");
    }
    
} catch (Exception $e) {
    echo json_encode(array("status"=>"error","code"=>$e->getCode(),"msg"=>$e->getMessage()));
}