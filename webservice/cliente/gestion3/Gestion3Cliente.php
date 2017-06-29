<?php
/*

Funciones para consumo de webservices en gestion 3

*/
include_once(dirname(__FILE__) . "/../../../tribunal/host/Host.Class.php");
include_once(dirname(__FILE__) . "/../../../tribunal/json/JsonDecod.Class.php");

@define("ruta", dirname(__FILE__));

class Gestion3Cliente {

    private $host = null;

    public function __construct() {
        $this->host = new Host(ruta . "/../../../tribunal/host/config.xml", "GESTION3");
        $this->host = $this->host->getConnect();
    }

    public function validarUsuario($usuario) {
        if ($usuario == '') {
            $result = array('Estatus' => 'Fail',
                        'mnj' => 'No se recibio la variable usuario'
                    );
            echo json_encode($result);
            exit();
        }
        try {
            ini_set("default_socket_timeout", 200);
            ini_set("soap.wsdl_cache_enabled", "0");
            $ws = new SoapClient($this->host . "CrearUsuarios/WSCrearUsuariosServer.php?wsdl");
            $result = $ws->usuariosGestion('{ "tipo" : "validarUsuario", "usuario" : "'.$usuario.'" }');
            return $result;
        } 
        catch (SoapFault $e) {
            if($e->getMessage() != '') {
                $mnj = $e->getMessage();
            }else{
                $mnj = $e;
            }
            $result = array('Estatus' => 'Error',
                        'mnj' => $mnj
                    );
            return json_encode($result);
            exit();
        }
    }

    public function guardaUsuario($data,$id_zimbra) {
        if ($data == '') {
            $result = array('Estatus' => 'Fail',
                        'mnj' => 'No se recibieron todos los parametros, intente nuevamente'
                    );
            echo json_encode($result);
            exit();
        }
        // si $id_zimbra viene vacio es debido a que el usuario se va a actualizar, si viene con algo entonces se va a guardar un registro nuevo
        if ($id_zimbra != '') {
            $data['idUsuarioZimbra'] = $id_zimbra;
        }
        try {
            ini_set("default_socket_timeout", 200);
            ini_set("soap.wsdl_cache_enabled", "0");
            $ws = new SoapClient($this->host . "CrearUsuarios/WSCrearUsuariosServer.php?wsdl");
            $complete_data = json_encode($data);
            $result = $ws->usuariosGestion($complete_data);
            return $result;
        } 
        catch (SoapFault $e) {
            if($e->getMessage() != '') {
                $mnj = $e->getMessage();
            }else{
                $mnj = $e;
            }
            $result = array('Estatus' => 'Error',
                        'mnj' => $mnj
                    );
            return json_encode($result);
            exit();
        }
    }

    public function consultaGrupo($grupo_activo) {
        if ($grupo_activo == '') {
            $result = array('Estatus' => 'Fail',
                        'mnj' => 'No se recibieron todos los parametros, intente nuevamente'
                    );
            echo json_encode($result);
            exit();
        }
        try {
            ini_set("default_socket_timeout", 200);
            ini_set("soap.wsdl_cache_enabled", "0");
            $ws = new SoapClient($this->host . "CrearUsuarios/WSCrearUsuariosServer.php?wsdl");
            $result = $ws->usuariosGestion('{ "tipo" : "consulta-grupo", "grupoActivo" : "'.$grupo_activo.'" }');
            return $result;
        } 
        catch (SoapFault $e) {
            if($e->getMessage() != '') {
                $mnj = $e->getMessage();
            }else{
                $mnj = $e;
            }
            $result = array('Estatus' => 'Error',
                        'mnj' => $mnj
                    );
            return json_encode($result);
            exit();
        }
    }

    public function consultaAdscripcion($grupo,$grupo_activo) {
        if ($grupo == '' || $grupo_activo == '') {
            $result = array('Estatus' => 'Fail',
                        'mnj' => 'No se recibieron todos los parametros, intente nuevamente'
                    );
            echo json_encode($result);
            exit();
        }
        try {
            ini_set("default_socket_timeout", 200);
            ini_set("soap.wsdl_cache_enabled", "0");
            $ws = new SoapClient($this->host . "CrearUsuarios/WSCrearUsuariosServer.php?wsdl");
            $result = $ws->usuariosGestion('{ "tipo" : "consultar_adscripcion", "grupo" : "'.$grupo.'", "grupoActivo" : "'.$grupo_activo.'" }');
            return $result;
        } 
        catch (SoapFault $e) {
            if($e->getMessage() != '') {
                $mnj = $e->getMessage();
            }else{
                $mnj = $e;
            }
            $result = array('Estatus' => 'Error',
                        'mnj' => $mnj
                    );
            return json_encode($result);
            exit();
        }
    }

    public function cargaUsuario($cve_usuario)
    {
        if ($cve_usuario == '') {
            $result = array('Estatus' => 'Fail',
                        'mnj' => 'No se recibieron todos los parametros, intente nuevamente'
                    );
            echo json_encode($result);
            exit();
        }
        try {
            ini_set("default_socket_timeout", 200);
            ini_set("soap.wsdl_cache_enabled", "0");
            $ws = new SoapClient($this->host . "CrearUsuarios/WSCrearUsuariosServer.php?wsdl");
            $result = $ws->usuariosGestion('{ "tipo" : "usuario_consultar", "cveUsuario" : "'.$cve_usuario.'" }');
            return $result;
        } 
        catch (SoapFault $e) {
            if($e->getMessage() != '') {
                $mnj = $e->getMessage();
            }else{
                $mnj = $e;
            }
            $result = array('Estatus' => 'Error',
                        'mnj' => $mnj
                    );
            return json_encode($result);
            exit();
        }
    }

    public function consultaUsuarios($data)
    {
        if ($data == '') {
            $result = array('Estatus' => 'Fail',
                        'mnj' => 'No se recibieron todos los parametros, intente nuevamente'
                    );
            echo json_encode($result);
            exit();
        }
        try {
            ini_set("default_socket_timeout", 200);
            ini_set("soap.wsdl_cache_enabled", "0");
            $ws = new SoapClient($this->host . "CrearUsuarios/WSCrearUsuariosServer.php?wsdl");
            
            $result = $ws->usuariosGestion(json_encode($data));
            return $result;
        } 
        catch (SoapFault $e) {
            if($e->getMessage() != '') {
                $mnj = $e->getMessage();
            }else{
                $mnj = $e;
            }
            $result = array('Estatus' => 'Error',
                        'mnj' => $mnj
                    );
            return json_encode($result);
            exit();
        }
    }

}

?>
