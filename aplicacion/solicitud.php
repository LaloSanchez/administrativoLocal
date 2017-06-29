<?php

class Solicitud {

    private $_fachada;
    private $_metodo;
    private $_argumentos;
    private $_accion;
    private $_server;

    public function __construct($server = null) {
        $this->_server = $server;

        if (isset($_GET['url'])) {
            $url = filter_input(INPUT_GET, 'url', FILTER_SANITIZE_URL);

            $url = explode('/', $url);
            $url = array_filter($url);

            $this->_fachada = strtolower(array_shift($url));
            $this->_metodo = strtolower(array_shift($url));
            $this->_argumentos = $url;
        }

        if (!$this->_fachada) {
            $this->_fachada = "index";
        }

        if (!$this->_metodo) {
            $this->_metodo = "index";
        }

        if (!isset($this->_argumentos)) {
            $this->_argumentos = array();
        }
        
        try {
            $this->_accion = $this->_server["REQUEST_METHOD"];
            if ((string) $this->_accion == "")
                throw new Exception("No se localizo el metodo de envio de la info", "0001");
        } catch (Exception $ex) {
            //
        }
    }

    public function getFachada() {
        return $this->_fachada;
    }

    public function getMetodo() {
        return $this->_metodo;
    }

    public function getAccion() {
        return $this->_accion;
    }

    public function getArgumentos() {
        return $this->_argumentos;
    }

}

?>
