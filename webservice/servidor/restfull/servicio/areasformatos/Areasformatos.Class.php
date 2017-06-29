<?php
include_once(dirname(__FILE__) . "/../../../../../aplicacion/configuracion.php");
include_once(dirname(__FILE__) . "/../../../../../tribunal/connect/Proveedor.Class.php");
include_once(dirname(__FILE__) . "/../../../../../tribunal/json/JsonEncod.Class.php");
include_once(dirname(__FILE__) . "/../../../../../modelos/dao/GenericDAO.Class.php");
include_once(dirname(__FILE__) . "/../../../../../controladores/Imagenes/ImagenesController.Class.php");
class Areasformatos {
    
    public function consultarFormatos($cveAdscripcion){        


        $genericoDao = new GenericDAO();      
        $arrayFormatos=array();
        $d = array("limit" => "");        
        $sql = array("campos" => "f.idAreaFormatos,f.desAreaFormato, i.idDocumentoImg,i.idImagen,i.ruta,d.descripcion titulo,i.descripcion",
                "tablas" => "tblareaformatos f, tbldocumentosimg d,tblimagenes i",                
                "where" => "f.idAreaFormatos=d.idReferencia AND d.idDocumentoImg = i.idDocumentoImg AND d.cveTipoDocumento=45  AND d.activo='S' AND i.activo='S' AND f.cveAdscripcion IN(".$cveAdscripcion.")" );
        $param = array("tabla" => "", "d" => $d, "tmpSql" => $sql, "proveedor" => null);
        $data = $genericoDao->select($param);        
        if($data["totalCount"]>0){
            for ($i=0; $i < $data["totalCount"] ; $i++) {                            
                $arrayFormatos[$data["data"][$i]["idAreaFormatos"]]["idAreaFormatos"]=$data["data"][$i]["idAreaFormatos"];
                $arrayFormatos[$data["data"][$i]["idAreaFormatos"]]["desAreaFormato"]=$data["data"][$i]["desAreaFormato"];
                // $arrayFormatos[$data["data"][$i]["idAreaFormatos"]]["documentos"]["idDocumentoImg"]=$data["data"][$i]["idDocumentoImg"];
                // $arrayFormatos[$data["data"][$i]["idAreaFormatos"]]["documentos"]["titulo"]=$data["data"][$i]["titulo"];
                $arrayFormatos[$data["data"][$i]["idAreaFormatos"]]["documentos"][$data["data"][$i]["idImagen"]]=array(
                                                                                                                        "idImagen"=>$data["data"][$i]["idImagen"],
                                                                                                                        "ruta"=>$data["data"][$i]["ruta"],
                                                                                                                        "descripcion"=>$data["data"][$i]["descripcion"]
                                                                                                                        );
            }
            $jsonEncode = new Encode_JSON();
            return utf8_encode($jsonEncode->encode(array("totalCount"=>count($arrayFormatos),"error"=>"false", "data"=>$arrayFormatos)));
        }
        return '{"totalCount":"0","error":"true"}';  
    }

}
try {    
    @$method = $_POST["accion"];
    @$cveAdscripcion = $_POST["cveAdscripcion"];    
    $obj = new Areasformatos();
    if (method_exists($obj, $method)) {        
        echo (call_user_func_array(array($obj, $method),array($cveAdscripcion)));           
    }else{
        throw new Exception("Acción no definida", "09");
    }
    
} catch (Exception $e) {
    echo json_encode(array("status"=>"error","code"=>$e->getCode(),"msg"=>$e->getMessage()));
}