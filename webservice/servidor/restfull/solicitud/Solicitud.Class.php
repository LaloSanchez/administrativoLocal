<?php

class Solicitud {

    public $_tipo = "aplication/json";
    public $_peticion = array();
    public $_estado = 200;
    public $_msg = "";
    public $_accion = "";
    public $_us = "";
    public $_pws = "";

    public function __construct() {
        $this->entrada();
    }

    private function entrada() {
        try {

            if (isset($_SERVER['PHP_AUTH_USER'])) {
                $this->_us = $_SERVER['PHP_AUTH_USER'];
                $this->_pws = $_SERVER['PHP_AUTH_PW'];
            } elseif (isset($_SERVER['HTTP_AUTHORIZATION'])) {
                if (strpos(strtolower($_SERVER['HTTP_AUTHORIZATION']), 'basic') === 0)
                    list($this->_us, $this->_pws) = explode(':', base64_decode(substr($_SERVER['HTTP_AUTHORIZATION'], 6)));
            }

            if (($this->_us == "") || ($this->_pws == "")) {
                throw new Exception("La autenticacion es requerida para accesar al Servicio Web", "403");
            } else {
                if((Boolean)$this->validaUsuario()==false){
                   throw new Exception("Usted no tiene permitido el acceso a este recurso", "403"); 
                }
            }

            $metodo = $_SERVER["REQUEST_METHOD"];
            switch ($metodo) {
                case "GET":
                    $this->_accion = "consultar";
                    
                    json_decode(file_get_contents("php://input"));
                    if (json_last_error() == JSON_ERROR_SYNTAX) {
                        parse_str(file_get_contents("php://input"), $p);
                    } else {
                        $p = json_decode(file_get_contents("php://input"), true);
                    }
                    
                    if (sizeof($p) > 0) {
                        $this->_peticion = $this->limpiar($p);
                    } else {
                        $this->_peticion = $this->limpiar($_GET);
                    }
                    
                    $this->_estado = 200;
                    break;
                case "POST":
                    $this->_accion = "guardar";
                    json_decode(file_get_contents("php://input"));
                    if (json_last_error() == JSON_ERROR_SYNTAX) {
                        parse_str(file_get_contents("php://input"), $p);
                    } else {
                        $p = json_decode(file_get_contents("php://input"), true);
                    }

                    if (sizeof($p) > 0) {
                        $this->_peticion = $this->limpiar($p);
                        $this->_estado = 200;
                    } else {
                        throw new Exception("No se logro obtener ningun parametro de entrada", "204");
                    }
                    break;
                case "PUT":
                    $this->_accion = "guardar";
                    json_decode(file_get_contents("php://input"));
                    if (json_last_error() == JSON_ERROR_SYNTAX) {
                        parse_str(file_get_contents("php://input"), $p);
                    } else {
                        $p = json_decode(file_get_contents("php://input"), true);
                    }

                    if (sizeof($p) > 0) {
                        $this->_peticion = $this->limpiar($p);
                        $this->_estado = 200;
                    } else {
                        throw new Exception("No se logro obtener ningun parametro de entrada", "204");
                    }
                    break;
                case "DELETE":
                    $this->_accion = "baja";
                    json_decode(file_get_contents("php://input"));
                    if (json_last_error() == JSON_ERROR_SYNTAX) {
                        parse_str(file_get_contents("php://input"), $p);
                    } else {
                        $p = json_decode(file_get_contents("php://input"), true);
                    }

                    if (sizeof($p) > 0) {
                        $this->_peticion = $this->limpiar($p);
                        $this->_estado = 200;
                    } else {
                        throw new Exception("No se logro obtener ningun parametro de entrada", "204");
                    }
                    break;
                default :
                    throw new Exception("No se localizo el servicio", "404");
                    break;
            }
        } catch (Exception $e) {
            $this->_estado = $e->getCode();
            $this->_msg = $e->getMessage();
        }
    }

    private function validaUsuario() {

        $url = explode('/', trim($_GET['url']));
        $url = array_filter($url);
        $metodo = strtolower(array_shift($url));
        
        if (file_exists("../us/" . base64_encode($this->_us . ":" . $this->_pws))) {
            $fp = fopen("../us/" . base64_encode($this->_us . ":" . $this->_pws), "r");
            $acceso=false;
            while (!feof($fp)) {
                $linea = fgets($fp);
                if(strtolower(trim($linea))==$metodo){
                  $acceso=true;
                  break;
                }
            }
            fclose($fp);
            return $acceso;
        } else {
            return false;
        }
    }

    private function limpiar($datos) {
        $entrada = array();
        if (is_array($datos)) {
            foreach ($datos as $key => $value) {
                $entrada[$key] = $this->limpiar($value);
            }
        } else {
            if (get_magic_quotes_gpc()) {
                $datos = trim(stripslashes($datos));
            }

            $datos = strip_tags($datos);
            @$datos = htmlentities($datos);
            $entrada = trim($datos);
        }
        return $entrada;
    }

    private function setCabecera() {
        header("HTTP/1.1 " . $this->_estado . " " . $this->getCodEstado());
        header("Content-Type:" . $this->_tipo . ';charset=utf-8');
    }

    private function getCodEstado() {
        $estado = array(
            200 => 'OK',
            201 => 'Created',
            202 => 'Accepted',
            204 => 'No Content',
            301 => 'Moved Permanently',
            302 => 'Found',
            303 => 'See Other',
            304 => 'Not Modified',
            400 => 'Bad Request',
            401 => 'Unauthorized',
            403 => 'Forbidden',
            404 => 'Not Found',
            405 => 'Method Not Allowed',
            500 => 'Internal Server Error');
        $respuesta = ($estado[$this->_estado]) ? $estado[$this->_estado] : $estado[500];
        return $respuesta;
    }

    public function mostrar($estado) {
        $this->_estado = ($estado) ? $estado : 200; //si no se envía $estado por defecto será 200  
        $this->setCabecera();
//        echo $datos;
//        exit;
    }

}

?>
