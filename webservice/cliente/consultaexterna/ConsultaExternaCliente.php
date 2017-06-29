<?php

include_once(dirname(__FILE__) . "/../../../fachadas/sigejupe/consultaexterna/ConsultaexternaFacade.Class.php");
include_once(dirname(__FILE__) . "/../../../tribunal/dtotojson/DtoToJson.Class.php");
include_once(dirname(__FILE__) . "/../../../tribunal/host/Host.Class.php");

if (!defined("ruta")) {
    define("ruta", dirname(__FILE__));
}

class ConsultaExternaCliente {

    public function __construct() {
        ini_set("default_socket_timeout", 200);
        ini_set("soap.wsdl_cache_enabled", 0);
    }

    /**
     * busca si tiene carpetas la persona logueada
     * @param int $IdPersonaautorizada id de la sesión
     * @return json => si hay carpetas -> carpetas judiciales de la persona; otro caso => error
     */
    public function getCarpetasJudicialesPorPersona($IdPersonaautorizada)
    {
        
        /**
         * @todo verificar URL
         */
        try {
            $cliente = new SoapClient('http://localhost/electronico/desarrollo/webservice/servidor/consultaexterna/ConsultaExternaServidor.php?wsdl' );
            return $cliente->getCarpetasJudicialesPorPersona($IdPersonaautorizada);
        } 
        catch (SoapFault $e) 
        {
            print_r($e);
            exit();
        }
    }# cierra getCarpetasJudicialesPorPersona
    
    /**
     * busca por carpeta, valida que tenga relación la carpeta con la persona logueada
     * @param type $IdCarpetajudicial
     * @param type $IdPersonaautorizada
     * 
     * @return JSON => si hay carpeta -> carpeta judicial solicitada; otro caso => error
     */
    public function getCarpetaJudicial($IdCarpetajudicial, $IdPersonaautorizada)
    {
        /**
         * @todo verificar URL
         */
        try {
            $cliente = new SoapClient('http://localhost/electronico/desarrollo/webservice/servidor/consultaexterna/ConsultaExternaServidor.php?wsdl' );
            return $cliente->getCarpetaJudicial($IdCarpetajudicial, $IdPersonaautorizada);
        } 
        catch (SoapFault $e) 
        {
            print_r($e);
            exit();
        }
    }#cierra getCarpetaJudicial
    
    /**
     * devuelve el detalle de la carpeta
     * @param string $tipo 1 = es carpeta judicial, 2 = es actuación
     * @param int $id de la carpeta a buscar
     * 
     * @return JSON con detalle de la carpeta
     */
    public function getDetalleCarpeta($tipo, $id)
    {
        /**
         * @todo verificar URL
         */
        try {
            $cliente = new SoapClient('http://localhost/electronico/desarrollo/webservice/servidor/consultaexterna/ConsultaExternaServidor.php?wsdl' );
            return $cliente->getDetalleCarpeta($tipo, $id);
        } 
        catch (SoapFault $e) 
        {
            print_r($e);
            exit();
        }
    }
    
    /**
     * 
     * @param int $IdDocumentoImg
     * 
     * @return json Detalle de las imagenes del documento
     * 
     * @todo se muestran solo las adjuntadas?
     */
    public function getImagenes($IdDocumentoImg)
    {
        /**
         * @todo verificar URL
         */
        try {
            $cliente = new SoapClient('http://localhost/electronico/desarrollo/webservice/servidor/consultaexterna/ConsultaExternaServidor.php?wsdl' );
            return $cliente->getDetalleCarpeta($IdDocumentoImg);
        } 
        catch (SoapFault $e) 
        {
            print_r($e);
            exit();
        }
    }

}

/**
 * Llamado de métodos desde el form vistas/sigejupe/scanner/formscanner.php
 * Si hay post o get
 */

if (isset($_GET)) 
{
    /**
     * http://localhost/electronico/desarrollo/webservice/cliente/consultaexterna/ConsultaExternaCliente.php?accion=consultarpersona&IdPersonaautorizada=1
     * http://localhost/electronico/desarrollo/webservice/cliente/consultaexterna/ConsultaExternaCliente.php?accion=consultarcarpetas&IdPersonaautorizada=1&IdCarpetajudicial=11
     * http://localhost/electronico/desarrollo/webservice/cliente/consultaexterna/ConsultaExternaCliente.php?accion=consultardetallecarpeta&tipo=1&idCj=11
     */
    $accion = isset($_GET['accion']) ? $_GET['accion'] : 'consultar';
    $IdPersonaautorizada = isset($_GET['IdPersonaautorizada']) ? $_GET['IdPersonaautorizada'] : NULL;
    $idCarpetajudicial = isset($_GET['IdCarpetajudicial']) ? $_GET['IdCarpetajudicial'] : NULL;
    $idCj = isset($_GET['idCj']) ? $_GET['idCj'] : 0;
    $idAct = isset($_GET['idAct']) ? $_GET['idAct'] : 0;
    $tipoCarpeta = $idAct > 0 ? 2 : 1;
    
    $cliente = new ConsultaExternaCliente();
    
    /*
     * consulta carpetas judiciales por persona
     */
    if ($accion == 'consultarpersona' && $IdPersonaautorizada != NULL) 
    {
        header('Content-Type: application/json; charset=utf-8');
        echo $cliente->getCarpetasJudicialesPorPersona($IdPersonaautorizada);
    }
    elseif ($accion == 'consultarcarpetas' && $IdPersonaautorizada != NULL && $idCarpetajudicial != NULL) 
    {
        header('Content-Type: application/json; charset=utf-8');
        echo $cliente->getCarpetaJudicial($idCarpetajudicial, $IdPersonaautorizada);
    }
    elseif ($accion == 'consultardetallecarpeta' && ($idCj > 0 || $idAct > 0)) 
    {
        $id = $idCj > 0 ? $idCj : $idAct;
        header('Content-Type: application/json; charset=utf-8');
        # muestra detalle de la carpeta
        echo $cliente->getDetalleCarpeta($tipoCarpeta, $id);
        # muestra detalle de imagenes
        $documento = ConsultaexternaFacade::getDocumentosImagenes($tipoCarpeta, $id);
        echo $cliente->getImagenes($documento->getIdDocumentoImg());
    }
    else
    {
        $jsonDto = new Encode_JSON();
        header('Content-Type: application/json; charset=utf-8');
        echo exit(utf8_encode($jsonDto->encode(
            array(
                'status' => 'FAIL',
                'totalCount' => 0, 
                'mnj' => 'Información incompleta.')
          )));
    }
} 
else 
{
    $jsonDto = new Encode_JSON();
    header('Content-Type: application/json; charset=utf-8');
    echo exit(utf8_encode($jsonDto->encode(
        array(
            'status' => 'FAIL',
            'totalCount' => 0, 
            'mnj' => 'No se recibió información para procesar.')
      )));
}

?>
