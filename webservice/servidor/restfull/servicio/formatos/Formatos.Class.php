<?php
include_once(dirname(__FILE__) . "/../../../../../aplicacion/configuracion.php");
include_once(dirname(__FILE__) . "/../../../../../tribunal/connect/Proveedor.Class.php");
include_once(dirname(__FILE__) . "/../../../../../tribunal/json/JsonEncod.Class.php");
include_once(dirname(__FILE__) . "/../../../../../modelos/dao/GenericDAO.Class.php");
include_once(dirname(__FILE__) . "/../../../../../controladores/Imagenes/ImagenesController.Class.php");
class Formatos {
    
    public function consultarFormatosGenerales(){
        $genericoDao = new GenericDAO();      
        $formatos=array();
        $d = array("limit" => "");
        $sql = array("campos" => "i.idDocumentoImg,i.idImagen,i.ruta,d.descripcion titulo,i.descripcion",
            "tablas" => "tbldocumentosimg d,tblimagenes i",
            "where" => " d.idDocumentoImg = i.idDocumentoImg AND d.cveTipoDocumento=17 AND d.activo='S' AND i.activo='S' " );
        $param = array("tabla" => "", "d" => $d, "tmpSql" => $sql, "proveedor" => null);
        $data = $genericoDao->select($param);
        if($data["totalCount"]>0){
            for ($i=0; $i < $data["totalCount"] ; $i++) { 
                $formatos[$data["data"][$i]["idDocumentoImg"]]["idDocumentoImg"]=$data["data"][$i]["idDocumentoImg"];
                $formatos[$data["data"][$i]["idDocumentoImg"]]["titulo"]=$data["data"][$i]["titulo"];
                $formatos[$data["data"][$i]["idDocumentoImg"]]["descripcion"]=$data["data"][$i]["descripcion"];
                $formatos[$data["data"][$i]["idDocumentoImg"]]["archivos"][]=$data["data"][$i];                    
            }
            $jsonEncode = new Encode_JSON();
            return utf8_encode($jsonEncode->encode(array("totalCount"=>count($formatos),"error"=>"false", "data"=>$formatos)));            
        }
        return '{"totalCount":"0","error":"true"}';  
    }

}

try {
    @$accion = $_POST["accion"];
    $formatos = new Formatos();
    if (method_exists($formatos, $accion)) {     
        echo (call_user_func_array(array($formatos, $accion),array()));           
    }else{
        throw new Exception("Acción no definida", "09");
    }
    
} catch (Exception $e) {
    echo json_encode(array("status"=>"error","code"=>$e->getCode(),"msg"=>$e->getMessage()));
}
?>
