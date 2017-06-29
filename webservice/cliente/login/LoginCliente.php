<?php

include_once(dirname(__FILE__) . "/../../../tribunal/host/Host.Class.php");
include_once(dirname(__FILE__) . "/../../../tribunal/json/JsonDecod.Class.php");

class LoginCliente {

    private $host = null;

    public function __construct() {
        $this->host = new Host("../../../tribunal/host/config.xml", "GESTION");
        $this->host = $this->host->getConnect();
    }

    public function validar($user, $password) {
        ini_set("default_socket_timeout", 200);
        ini_set("soap.wsdl_cache_enabled", "0");
        $login = new SoapClient($this->host . "controller/login/LoginServer.php?wsdl");
        $login = $login->getLogin(utf8_encode($user), sha1($password), $_SESSION['cveSistema'], '3a332bac303f6e9536b36731090f66800abee04c', 'cf3387f0417a09352af09b2926e3e38522bef9f5');
        return $login;
    }

    public function loginFull($user, $password) {
//        echo $user;
//        echo "<br/>";
//        echo $txt = utf8_encode($user);
//        echo "<br/>";
//        echo utf8_decode($txt);
        ini_set("default_socket_timeout", 200);
        ini_set("soap.wsdl_cache_enabled", "0");
        $login = new SoapClient($this->host . "controller/login/LoginServer.php?wsdl");
        $login = $login->getFullLogin(utf8_encode($user), sha1($password), $_SESSION['cveSistema'], '3a332bac303f6e9536b36731090f66800abee04c', 'cf3387f0417a09352af09b2926e3e38522bef9f5');
//        echo utf8_decode($login);
        return $login;
    }

    public function perfiles($json) {
        ini_set("default_socket_timeout", 200);
        ini_set("soap.wsdl_cache_enabled", "0");
        $perfil = new SoapClient($this->host . "controller/login/LoginServer.php?wsdl");
        $perfil = $perfil->getPerfil($json, $_SESSION['cveSistema'], '3a332bac303f6e9536b36731090f66800abee04c', 'cf3387f0417a09352af09b2926e3e38522bef9f5');
        return $perfil;
    }

}

//$loginCliente = new LoginCliente();
//$loginCliente->validar("jeeclo", "01012012");
?>
