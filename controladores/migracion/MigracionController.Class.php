<?php

include_once(dirname(__FILE__) . "/../../modelos/dao/GenericDAO.Class.php");
include_once(dirname(__FILE__) . "/../../tribunal/json/JsonEncod.Class.php");
include_once(dirname(__FILE__) . "/../../tribunal/json/JsonDecod.Class.php");
include_once(dirname(__FILE__) . "/../../tribunal/connect/conexionRemota.php");
include_once(dirname(__FILE__) . "/../../controladores/migracion/ObtenerDatosController.php");
include_once(dirname(__FILE__) . "/../../webservice/cliente/migracionAdministrativo/MigracionAdministrativo.php");

set_time_limit(-1);
ini_set('memory_limit', -1);
/**
 * Clase que permite consultar el anteproyectoPartidas
 *
 * @author PJ
 */
class MigracionController {
    
    private $proveedor;
    private $adscripcionPadreArray = array();
    private $logger;

    public function __construct() {
        $this->logger = new Logger("/../../logs/", "Resguardo");
        $this->proveedor = new Proveedor('mysql', 'ADMINISTRATIVO');
    }
    
    public function migrarDatosDescarga($params) {
        $this->guardarLogger("COMIENZA MIGRACION", "*******************************");
        $error = false;
        $genericoDao = new GenericDAO();
        $jsonDecode = new Decode_JSON();
        $ObtenerDatosController=new ObtenerDatosController();
        $datosMigracion=$ObtenerDatosController->getInfo();
//        $this->guardarLogger("respuesta", $datosMigracion);
//        $this->guardarLogger("Arreglo devuelto", $datosMigracion);
        $this->proveedor->connect();
        $this->proveedor->execute("BEGIN");
        $this->proveedor->execute("CALL limpiarbase()");
            //tblunidadesMedida
                $this->guardarLogger("tabla tblunidadesMedida", $datosMigracion["data"]["tblunidadesmedida"]["data"]);
                $val="insert into tblunidadesmedida (cveUnidadMedida,desUnidadMedida,descripcionUnidadMedida,activo,fechaRegistro,fechaActualizacion)values";
                foreach($datosMigracion["data"]["tblunidadesmedida"]["data"] as $keyUM => $valueUM){
                    $val .="('".$valueUM["cveUnidadMedida"]."','".$valueUM["desUnidadMedida"]."','".$valueUM["descripcionUnidadMedida"]."','".$valueUM["activo"]."','".$valueUM["fechaRegistro"]."','".$valueUM["fechaActualizacion"]."'),";
                }
                $val= trim($val,",");
                $this->proveedor->execute($val);
                if($this->proveedor->error()){
                    print_r("tblunidadesMedida");
                    $error=true;
                }
            //tblcogbienes
                $this->guardarLogger("tabla tblcogbienes", $datosMigracion["data"]["tblcogbienes"]["data"]);
                $val="insert into tblcogbienes (idCogBien,idCog,cveUnidadMedida,consecutivo,descripcion,activo,fechaRegistro,fechaActualizacion)values";
                foreach ($datosMigracion["data"]["tblcogbienes"]["data"] as $keyCB => $valueCB) {
                    $val .="('".$valueCB["idCogBien"]."',";
                    $val .="'".$valueCB["idCog"]."',";
                    $val .="'".$valueCB["cveUnidadMedida"]."',";
                    $val .="'".$valueCB["consecutivo"]."',";
                    $val .="'".$valueCB["descripcion"]."',";
                    $val .="'".$valueCB["activo"]."',";
                    $val .="'".$valueCB["fechaRegistro"]."',";
                    $val .="'".$valueCB["fechaActualizacion"]."'),";
                }
                $val= trim($val,",");
                $this->proveedor->execute($val);
                if($this->proveedor->error()){
                    print_r("tblcogbienes");
                    $error=true;
                }
            //tblaah
                $this->guardarLogger("tabla tblaah", $datosMigracion["data"]["tblaah"]["data"]);
                $val="insert into tblaah (idAah,idCogBien,idClasificadorTipoBien,denominacion,activo,fechaRegistro,fechaActualizacion)values";
                foreach ($datosMigracion["data"]["tblaah"]["data"] as $keyAah => $valueAah) {
                    $val .="(";
                    $val .="'".$valueAah["idAah"]."',";
                    $val .="'".$valueAah["idCogBien"]."',";
                    $val .="'".$valueAah["idClasificadorTipoBien"]."',";
                    $val .="'".$valueAah["denominacion"]."',";
                    $val .="'".$valueAah["activo"]."',";
                    $val .="'".$valueAah["fechaRegistro"]."',";
                    $val .="'".$valueAah["fechaActualizacion"]."'),";
                }
                $val= trim($val,",");
                $this->proveedor->execute($val);
                if($this->proveedor->error()){
                    print_r("tblaah");
                    $error=true;
                }
            //tblcbi
                $this->guardarLogger("tabla tblcbi", $datosMigracion["data"]["tblcbi"]["data"]);
                $val="insert into tblcbi (idCbi,idCogBien,idEdificio,cveTipoPropiedad,cveSituacion,cveCatastral,superficie,desEscritura,cveGrupoInmueble,subGrupoInmueble,claseInmueble,subClaseInmueble,denominacion,activo,fechaRegistro,fechaActualizacion)values";
                foreach ($datosMigracion["data"]["tblcbi"]["data"] as $keyCbi => $valueCbi) {
                    $val .="(";
                    $val .="'".$valueCbi["idCbi"]."',";
                    $val .="'".$valueCbi["idCogBien"]."',";
                    $val .="'".$valueCbi["idEdificio"]."',";
                    $val .="'".$valueCbi["cveTipoPropiedad"]."',";
                    $val .="'".$valueCbi["cveSituacion"]."',";
                    $val .="'".$valueCbi["cveCatastral"]."',";
                    $val .="'".$valueCbi["superficie"]."',";
                    $val .="'".$valueCbi["desEscritura"]."',";
                    $val .="'".$valueCbi["cveGrupoInmueble"]."',";
                    $val .="'".$valueCbi["subGrupoInmueble"]."',";
                    $val .="'".$valueCbi["claseInmueble"]."',";
                    $val .="'".$valueCbi["subClaseInmueble"]."',";
                    $val .="'".$valueCbi["denominacion"]."',";
                    $val .="'".$valueCbi["activo"]."',";
                    $val .="'".$valueCbi["fechaRegistro"]."',";
                    $val .="'".$valueCbi["fechaActualizacion"]."'),";
                }
                $val= trim($val,",");
                $this->proveedor->execute($val);
                if($this->proveedor->error()){
                    print_r("tblcbi");
                    $error=true;
                }
            //tblcbm
                $this->guardarLogger("tabla tblcbm", $datosMigracion["data"]["tblcbm"]["data"]);
                $val="insert into tblcbm (idCbm,cveGrupo,subGrupoMueble,claseMueble,subClase,cveColor,cveMaterial,cvefrecuenciaUso,cveUnidadMedida,idGrupoAlmacen,denominacion,marca,modelo,inventariable,requiereReorden,porcentajeReorden,IdArticuloAnt,clvGrupoContPat,clvSubgrupoContPat,activo,fechaRegistro,fechaActualizacion)values";
                foreach ($datosMigracion["data"]["tblcbm"]["data"] as $keyCbm => $valueCbm) {
                    $val .="(";
                    $val .="'".$valueCbm["idCbm"]."',";
                    $val .="'".$valueCbm["cveGrupo"]."',";
                    $val .="'".$valueCbm["subGrupoMueble"]."',";
                    $val .="'".$valueCbm["claseMueble"]."',";
                    $val .="'".$valueCbm["subClase"]."',";
                    $val .="'".$valueCbm["cveColor"]."',";
                    $val .="'".$valueCbm["cveMaterial"]."',";
                    $val .="'".$valueCbm["cvefrecuenciaUso"]."',";
                    $val .="'".$valueCbm["cveUnidadMedida"]."',";
                    $val .="'".$valueCbm["idGrupoAlmacen"]."',";
                    $val .="'".$valueCbm["denominacion"]."',";
                    $val .="'".$valueCbm["marca"]."',";
                    $val .="'".$valueCbm["modelo"]."',";
                    $val .="'".$valueCbm["inventariable"]."',";
                    $val .="'".$valueCbm["requiereReorden"]."',";
                    $val .="'".$valueCbm["porcentajeReorden"]."',";
                    $val .="'".$valueCbm["IdArticuloAnt"]."',";
                    $val .="'".$valueCbm["clvGrupoContPat"]."',";
                    $val .="'".$valueCbm["clvSubgrupoContPat"]."',";
                    $val .="'".$valueCbm["activo"]."',";
                    $val .="'".$valueCbm["fechaRegistro"]."',";
                    $val .="'".$valueCbm["fechaActualizacion"]."'),";
                }
                $val= trim($val,",");
                $this->proveedor->execute($val);
                if($this->proveedor->error()){
                    print_r("tblcbm");
                    $error=true;
                }
            //tblclasificadoresbienes
                $this->guardarLogger("tabla tblclasificadoresbienes", $datosMigracion["data"]["tblclasificadoresbienes"]["data"]);
                $val="insert into tblclasificadoresbienes (cveClasificadorBien,desClasificadorBien,activo,fechaRegistro,fechaActualizacion)values";
                foreach ($datosMigracion["data"]["tblclasificadoresbienes"]["data"] as $keyClb => $valueClb) {
                    $val .="(";
                    $val .="'".$valueClb["cveClasificadorBien"]."',";
                    $val .="'".$valueClb["desClasificadorBien"]."',";
                    $val .="'".$valueClb["activo"]."',";
                    $val .="'".$valueClb["fechaRegistro"]."',";
                    $val .="'".$valueClb["fechaActualizacion"]."'),";
                }
                $val= trim($val,",");
                $this->proveedor->execute($val);
                if($this->proveedor->error()){
                    print_r("tblclasificadoresbienes");
                    $error=true;
                }
            //tbltiposbienes
                $this->guardarLogger("tabla tbltiposbienes", $datosMigracion["data"]["tbltiposbienes"]["data"]);
                $val="insert into tbltiposbienes (cveTipoBien,desTipoBien,activo,fechaRegistro,fechaActualizacion)values";
                foreach ($datosMigracion["data"]["tbltiposbienes"]["data"] as $keyTb => $valueTb) {
                    $val .="(";
                    $val .="'".$valueTb["cveTipoBien"]."',";
                    $val .="'".$valueTb["desTipoBien"]."',";
                    $val .="'".$valueTb["activo"]."',";
                    $val .="'".$valueTb["fechaRegistro"]."',";
                    $val .="'".$valueTb["fechaActualizacion"]."'),";
                }
                $val= trim($val,",");
                $this->proveedor->execute($val);
                if($this->proveedor->error()){
                    print_r("tbltiposbienes");
                    $error=true;
                }
            //tblestatus
                $this->guardarLogger("tabla tblestatus", $datosMigracion["data"]["tblestatus"]["data"]);
                $val="insert into tblestatus (cveEstatus,desEstatus,cveTipoEstatus,activo,fechaRegistro,fechaActualizacion)values";
                foreach ($datosMigracion["data"]["tblestatus"]["data"] as $key2 => $valueEs) {
                    $val .="(";
                    $val .="'".$valueEs["cveEstatus"]."',";
                    $val .="'".$valueEs["desEstatus"]."',";
                    $val .="'".$valueEs["cveTipoEstatus"]."',";
                    $val .="'".$valueEs["activo"]."',";
                    $val .="'".$valueEs["fechaRegistro"]."',";
                    $val .="'".$valueEs["fechaActualizacion"]."'),";
                }
                $val= trim($val,",");
                $this->proveedor->execute($val);
                if($this->proveedor->error()){
                    print_r("tblestatus");
                    $error=true;
                }
            //tblestadosbienes
                $this->guardarLogger("tabla tblestadosbienes", $datosMigracion["data"]["tblestadosbienes"]["data"]);
                $val="insert into tblestadosbienes (cveEstadoBien,desEstadoBien,activo,fechaRegistro,fechaActualizacion)values";
                foreach ($datosMigracion["data"]["tblestadosbienes"]["data"] as $keyEsb => $valueEsb) {
                    $val .="(";
                    $val .="'".$valueEsb["cveEstadoBien"]."',";
                    $val .="'".$valueEsb["desEstadoBien"]."',";
                    $val .="'".$valueEsb["activo"]."',";
                    $val .="'".$valueEsb["fechaRegistro"]."',";
                    $val .="'".$valueEsb["fechaActualizacion"]."'),";       
                }
                $val= trim($val,",");
                $this->proveedor->execute($val);
                if($this->proveedor->error()){
                    print_r("tblestadosbienes");
                    $error=true;
                }
            //tblinventarios
                $this->guardarLogger("tabla tblinventarios", $datosMigracion["data"]["tblinventarios"]["data"]);
                $val="insert into tblinventarios (idInventario,idClasificadorFuenteFinanciamiento,idProyectoProgramatico,idReferencia,cveClasificadorBien,idFactura,"
                        . "cveEstadoBien,cveMotivoBaja,numeroSerie,consecutivo,codigoPropio,codigoAnterior,precioCompra,precioActual,valorDesecho,aniosVidaUtil,fechaCompra,"
                        . "garantia,fechaInicioGarantia,fechaFinGarantia,latitud,longitud,idInventarioAnterior,inventariado,asegurado,activo,fechaRegistro,fechaActualizacion)values";
                foreach ($datosMigracion["data"]["tblinventarios"]["data"] as $keyInv => $valueInv) {
                    $val .="(";
                    $val .="'".$valueInv["idInventario"]."',";
                    $val .="'".$valueInv["idClasificadorFuenteFinanciamiento"]."',";
                    $val .="'".$valueInv["idProyectoProgramatico"]."',";
                    $val .="'".$valueInv["idReferencia"]."',";
                    $val .="'".$valueInv["cveClasificadorBien"]."',";
                    $val .="'".$valueInv["idFactura"]."',";
                    $val .="'".$valueInv["cveEstadoBien"]."',";
                    $val .="'".$valueInv["cveMotivoBaja"]."',";
                    $val .="'".$valueInv["numeroSerie"]."',";
                    $val .="'".$valueInv["consecutivo"]."',";
                    $val .="'".$valueInv["codigoPropio"]."',";
                    $val .="'".$valueInv["codigoAnterior"]."',";
                    $val .="'".$valueInv["precioCompra"]."',";
                    $val .="'".$valueInv["precioActual"]."',";
                    $val .="'".$valueInv["valorDesecho"]."',";
                    $val .="'".$valueInv["aniosVidaUtil"]."',";
                    $val .="'".$valueInv["fechaCompra"]."',";
                    $val .="'".$valueInv["garantia"]."',";
                    $val .="'".$valueInv["fechaInicioGarantia"]."',";
                    $val .="'".$valueInv["fechaFinGarantia"]."',";
                    $val .="'".$valueInv["latitud"]."',";
                    $val .="'".$valueInv["longitud"]."',";
                    $val .="'".$valueInv["idInventarioAnterior"]."',";
                    $val .="'".$valueInv["inventariado"]."',";
                    $val .="'".$valueInv["asegurado"]."',";
                    $val .="'".$valueInv["activo"]."',";
                    $val .="'".$valueInv["fechaRegistro"]."',";
                    $val .="'".$valueInv["fechaActualizacion"]."'),";
                }
                $val= trim($val,",");
                
                $this->proveedor->execute($val);
                if($this->proveedor->error()){
                    print_r("tblinventarios");
                    $error=true;
                }
            //tblresguardos
                $this->guardarLogger("tabla tblresguardos", $datosMigracion["data"]["tblresguardos"]["data"]);
                $val="insert into tblresguardos (idResguardo,idInventario,cveRegion,cveAdscripcion,cveOrganigrama,precioActual,numEmpleadoResguardo,fechaAsigancion,activo,fechaRegistro,fechaActualizacion)values";
                foreach ($datosMigracion["data"]["tblresguardos"]["data"] as $keyRes => $valueRes) {
                    $val .="(";
                    $val .="'".$valueRes["idResguardo"]."',";
                    $val .="'".$valueRes["idInventario"]."',";
                    $val .="'".$valueRes["cveRegion"]."',";
                    $val .="'".$valueRes["cveAdscripcion"]."',";
                    $val .="'".$valueRes["cveOrganigrama"]."',";
                    $val .="'".$valueRes["precioActual"]."',";
                    $val .="'".$valueRes["numEmpleadoResguardo"]."',";
                    $val .="'".$valueRes["fechaAsigancion"]."',";
                    $val .="'".$valueRes["activo"]."',";
                    $val .="'".$valueRes["fechaRegistro"]."',";
                    $val .="'".$valueRes["fechaActualizacion"]."'),";
                }
                $val= trim($val,",");
                $this->proveedor->execute($val);
                if($this->proveedor->error()){
                    print_r("tblresguardos");
                    $error=true;
                }
                if(!$error){
                    $error=$this->infoEmpleados($params["extrasPost"]["listaJuzagdos"]);
                    $this->guardarLogger("Archivo JSON", $error);
                }
                
        if(!$error){
            $this->proveedor->execute("COMMIT");
            $resultado = array("status"=>"success", "msj"=>"Migracion Exitosa");
        }else{
            $this->proveedor->execute("ROLLBACK");
            $resultado = array("status"=>"error", "msj"=>"Error al migrar datos");
        }
        $this->proveedor->close();
        $json=new Encode_JSON();
        return $json->encode($resultado);
    }
    
    public function cargarDatos(){
        $genericDao = new GenericDAO();
        $jsonEncode = new Encode_JSON();
        $sql = array("campos" => "*",
            "tablas" => " tblresguardosindividuales "
        );
        // $d = array("limit" => $limit);
        $param = array("tabla" => "", "d" => "", "tmpSql" => $sql, "proveedor" => null);
        $array = $genericDao->select($param);
        if($array["totalCount"] > 0){
            $migracion = new MigracionAdministrativo();
            $json=$jsonEncode->encode($array);
            $respuesta=$migracion->migracionlocal($json);
            return $respuesta;
        }else{
            return '{"totalCount":0,"status":"nadaExportar"}';
        }
    }
    
    public function infoEmpleados($array){
        $array=json_encode($array);
        $MigracionAdministrativo=new MigracionAdministrativo();
        $datos=$MigracionAdministrativo->migracionAdministrativo($array);
        $this->guardarLogger("Respuesta de los datos", $datos);
        if($datos == '0'){
            return true;
        }else{
            $archivo= fopen("../../archivos/informacionEmpleados.json", "w")or die($php_errormsg);
            fwrite($archivo, $datos);
            fclose($archivo);
            return false;
        }
    }
    
    public function obtenerAdscripciones(){
        $migracion = new MigracionAdministrativo();
        $respuesta=$migracion->obtenerAdscripciones();
        $respuesta= utf8_decode($respuesta);
        return $respuesta;
    }

    function fechaNormal($fecha, $hora = false) {
        $arrFecha = explode(" ", $fecha);
        list($year, $mes, $dia) = explode("-", $arrFecha[0]);
        if ($hora)
            return $dia . "/" . $mes . "/" . $year . " " . $arrFecha[1];
        else
            return $dia . "/" . $mes . "/" . $year . " ";
    }
    
    function fechaNormalSql($fecha, $hora = false) {
        $arrFecha = explode(" ", $fecha);
        list($year, $mes, $dia) = explode("/", $arrFecha[0]);
        if ($hora)
            return $dia . "-" . $mes . "-" . $year . " " . $arrFecha[1];
        else
            return $dia . "-" . $mes . "-" . $year . " ";
    }

    function validateDate($date, $format = 'Y-m-d H:i:s') {
        $d = DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) == $date;
    }
    
    public function guardarLogger($msn = null, $variable = null) {
        $this->logger->w_onError("######### " . $msn . " ##########");
        $this->logger->w_onError($this->varDumpToString($variable));
    }
    public function varDumpToString($var) {
        ob_start();
        var_dump($var);
        $result = ob_get_clean();
        return $result;
    }
}