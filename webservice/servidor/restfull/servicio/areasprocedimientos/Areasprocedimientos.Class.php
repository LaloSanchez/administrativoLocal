<?php
include_once(dirname(__FILE__) . "/../../../../../aplicacion/configuracion.php");
include_once(dirname(__FILE__) . "/../../../../../tribunal/connect/Proveedor.Class.php");
include_once(dirname(__FILE__) . "/../../../../../tribunal/json/JsonEncod.Class.php");
include_once(dirname(__FILE__) . "/../../../../../modelos/dao/GenericDAO.Class.php");
include_once(dirname(__FILE__) . "/../../../../../controladores/Imagenes/ImagenesController.Class.php");
class Areasprocedimientos {
    
    public function consultarProcesoProcedimientos($cveAdscripcion){
        $genericoDao = new GenericDAO();      
        $arrayProcedimientos=array();
        $d = array("limit" => "");        
        $sql = array("campos" => "a.numeroProceso, a.desProceso,b.idAreaProcedimiento,b.idAreaProceso,b.numeroProcedimiento,b.nombreProcedimiento,b.numeroPaginas,b.version, b.fechaEmision,b.fechaVigencia",
            "tablas" => "tblareaprocesos a LEFT JOIN tblareaprocedimiento b ON(a.idAreaProceso=b.idAreaProceso AND b.activo='S') ",
            "orders"=>" a.numeroProceso,b.numeroProcedimiento ",
            "where" => " a.cveAdscripcion=".$cveAdscripcion." AND a.activo='S' " );
        $param = array("tabla" => "", "d" => $d, "tmpSql" => $sql, "proveedor" => null);
        $data = $genericoDao->select($param);
        if($data["totalCount"]>0){
            $auxProcedimientos=array();
            for ($i=0; $i < $data["totalCount"] ; $i++) {
                $auxProcedimientos[]=$data["data"][$i]["idAreaProcedimiento"];
            }            
            implode(',', $auxProcedimientos);
            $d = array("limit" => "");        
            $sql = array("campos" => "d.idReferencia idAreaProcedimiento, i.idDocumentoImg,i.idImagen,i.ruta,d.descripcion titulo,i.descripcion",
                "tablas" => "tbldocumentosimg d,tblimagenes i",                
                "where" => "d.idDocumentoImg = i.idDocumentoImg AND d.cveTipoDocumento=44  AND d.activo='S' AND i.activo='S' AND d.idReferencia IN(".implode(',', $auxProcedimientos).")" );
            $param = array("tabla" => "", "d" => $d, "tmpSql" => $sql, "proveedor" => null);
            $documentos = $genericoDao->select($param);            
            $auxProcedimientos=array();
            for ($i=0; $i < $documentos["totalCount"] ; $i++) {
                $auxProcedimientos[$documentos["data"][$i]["idAreaProcedimiento"]][]=array(
                                                                                  "titulo"=>  $documentos["data"][$i]["titulo"],
                                                                                  "descripcion"=>  $documentos["data"][$i]["descripcion"],
                                                                                  "ruta"=>  $documentos["data"][$i]["ruta"]
                                                                                );
            }   
            for ($i=0; $i < $data["totalCount"] ; $i++) {
                $arrayProcedimientos[$data["data"][$i]["numeroProceso"]]["numeroProceso"]=$data["data"][$i]["numeroProceso"];
                $arrayProcedimientos[$data["data"][$i]["numeroProceso"]]["desProceso"]=$data["data"][$i]["desProceso"];
                $auxDocumentos=array();
                if(array_key_exists($data["data"][$i]["idAreaProcedimiento"], $auxProcedimientos)){
                    $auxDocumentos= $auxProcedimientos[$data["data"][$i]["idAreaProcedimiento"]];
                }
                $arrayProcedimientos[$data["data"][$i]["numeroProceso"]]["procedimientos"][$data["data"][$i]["idAreaProcedimiento"]]=$arrayName = array(
                                                                                                'idAreaProcedimiento' =>$data["data"][$i]["idAreaProcedimiento"],
                                                                                                'nombreProcedimiento' =>$data["data"][$i]["nombreProcedimiento"], 
                                                                                                'numeroProcedimiento' =>$data["data"][$i]["numeroProcedimiento"],
                                                                                                'numeroPaginas' =>$data["data"][$i]["numeroPaginas"],
                                                                                                'version' =>$data["data"][$i]["version"],
                                                                                                'fechaEmision' =>$data["data"][$i]["fechaEmision"],
                                                                                                'fechaVigencia' =>$data["data"][$i]["fechaVigencia"],
                                                                                                'documentos'=>$auxDocumentos
                                                                                                );
            }
            $jsonEncode = new Encode_JSON();
            return utf8_encode($jsonEncode->encode(array("totalCount"=>count($arrayProcedimientos),"error"=>"false", "data"=>$arrayProcedimientos)));
        }
        return '{"totalCount":"0","error":"true"}';  
    }

}
try {        
    @$method = $_POST["accion"];
    @$cveAdscripcion = $_POST["cveAdscripcion"];    
    $method="consultarProcesoProcedimientos";    
    $obj = new Areasprocedimientos();
    if (method_exists($obj, $method)) {        
        echo (call_user_func_array(array($obj, $method),array($cveAdscripcion)));           
    }else{
        throw new Exception("Acción no definida", "09");
    }
    
} catch (Exception $e) {
    echo json_encode(array("status"=>"error","code"=>$e->getCode(),"msg"=>$e->getMessage()));
}