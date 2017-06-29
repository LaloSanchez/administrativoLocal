<?php

include_once(dirname(__FILE__) . "/../../../tribunal/host/Host.Class.php");
include_once(dirname(__FILE__) . "/../../../tribunal/json/JsonDecod.Class.php");

if (!defined("ruta")) {
    define("ruta", dirname(__FILE__));
}

class UsuarioCliente {

    private $host = null;

    public function __construct() {
        $this->host = new Host(ruta . "/../../../tribunal/host/config.xml", "GESTION");
        $this->host = $this->host->getConnect();
    }

    public function getUsuarios($cveUsuario) {
        ini_set("default_socket_timeout", 200);
        ini_set("soap.wsdl_cache_enabled", "0");
        $usuario = new SoapClient($this->host . "controller/usuarios/UsuariosServer.php?wsdl");
        $usuario = $usuario->selectUsuariosIn($cveUsuario, '3a332bac303f6e9536b36731090f66800abee04c', 'cf3387f0417a09352af09b2926e3e38522bef9f5');
        return $usuario;
    }

    public function selectUsuario_NumEmpleado($NumEmpleado) {
        ini_set("default_socket_timeout", 200);
        ini_set("soap.wsdl_cache_enabled", "0");
        $usuario = new SoapClient($this->host . "controller/usuarios/UsuariosServer.php?wsdl");
        $usuario = $usuario->selectUsuario_NumEmpleado($NumEmpleado, $_SESSION["cveSistema"], '3a332bac303f6e9536b36731090f66800abee04c', 'cf3387f0417a09352af09b2926e3e38522bef9f5');
        return $usuario;
    }

    public function selectUsuario_Nombre($Nombre) {
        ini_set("default_socket_timeout", 200);
        ini_set("soap.wsdl_cache_enabled", "0");
        $usuario = new SoapClient($this->host . "controller/usuarios/UsuariosServer.php?wsdl");
        $usuario = $usuario->selectUsuario_Nombre($Nombre, '3a332bac303f6e9536b36731090f66800abee04c', 'cf3387f0417a09352af09b2926e3e38522bef9f5');
        return $usuario;
    }

    public function selectPersonal_Nombre($numero, $nombre, $cveJuzgado) {
        ini_set("default_socket_timeout", 200);
        ini_set("soap.wsdl_cache_enabled", "0");
        $usuario = new SoapClient($this->host . "controller/personal/PersonalServer.php?wsdl");
        $usuario = $usuario->getNombre($numero, $nombre, $cveJuzgado, '3a332bac303f6e9536b36731090f66800abee04c', 'cf3387f0417a09352af09b2926e3e38522bef9f5');
        return $usuario;
    }

    public function selectUsuarios_In($CvesUsuario) {
        ini_set("default_socket_timeout", 200);
        ini_set("soap.wsdl_cache_enabled", "0");
        $usuario = new SoapClient($this->host . "controller/usuarios/UsuariosServer.php?wsdl");
        $usuario = $usuario->selectUsuariosIn($CvesUsuario, '3a332bac303f6e9536b36731090f66800abee04c', 'cf3387f0417a09352af09b2926e3e38522bef9f5');
        return utf8_encode($usuario);
    }

    public function selectUsuariosGrupoSistema($cveGrupo, $cveSistema, $cveAdscripcion) {
        ini_set("default_socket_timeout", 200);
        ini_set("soap.wsdl_cache_enabled", "0");
        $usuario = new SoapClient($this->host . "controller/usuariosGrupos/UsuariosGruposServer.php?wsdl");
        $usuario = $usuario->selectUsuariosGrupoSistema($cveGrupo, $cveSistema, $cveAdscripcion);
        return utf8_encode($usuario);
    }
    public function cambiarPwd($cveUsuario, $pwd) {
        ini_set("default_socket_timeout", 200);
        ini_set("soap.wsdl_cache_enabled", "0");
        $usuario = new SoapClient($this->host . "../gestion3/webservice/ActualizarPwdUsuario/ActualizarPwdUsuarioServer.php?wsdl");
        $usuario = $usuario->actualizaUsuario($cveUsuario, $pwd);
        return $usuario;
    }

}

?>
