<?php

class ConnectWS {
    #public $base_WS = "http://207..lexsys.net";

    public $base_WS = "http://10.22.165.107";
    public $app_Secret = "9fec9e2869f72d9c69963e2d4775398a35e53ad72bd23e8daf1573f0eaeb3065";
    public $app_Key = "b8e91998-c725-4cd1-a779-098daaada77c";
    public $secure_key = "";
    public $user_WS = "TSJ";
    public $password = "lorem";
    public $method = "POST";

    public function __construct($user = "") {
        if ($user != "")
            $this->user_WS = $user;
        $this->secure_key = $this->loginWS();
    }

    /**
     * Inicia sesion para obtener el Sesion Key
     */
    private function loginWS() {
        $timestamp = $this->fechaUTC();
        $ruta = "/sessions";
        $parametros = '{"sessionKey":null,"expiration":null,"user":{},"position":{},"username":"' . $this->user_WS . '","authMethod":"P","positions":[],"password":"' . $this->password . '","session_key":null,"auth_method":"P"}';
        $digest = $this->method . "|" . $ruta . "|" . $timestamp . "|" . $this->app_Secret . "|" . $parametros;
        $digest = hash('sha256', $digest);
        $authorization = "WRT " . $this->app_Key . ":" . $digest;
        $url = $this->base_WS . $ruta;

        $resultadoLogin = $this->sendRequest($url, $parametros, $timestamp, $authorization);
        if ($resultadoLogin["meta"]["status"] == "ERROR") {
            return "";
        } else {
            return $resultadoLogin["response"]["session_key"];
        }
    }

    /**
     * Convierte una fecha en formato UTC
     * 
     * @param string $fecha Fecha en formato Y-m-d
     * @param string $hora Hora en formato H:i:s
     * @return string Fecha en formato UTC
     */
    public function fechaUTC($fecha = "", $hora = "") {
        date_default_timezone_set("UTC");

        if (empty($fecha))
            $fecha = date("Y-m-d");
        if (empty($hora))
            $hora = date("H:i:s");

        return $fecha . "T" . $hora . ".001Z";
    }

    /**
     * Inicia Session en el WS
     * 
     * @param type $url URL a la que se realizara la peticion
     * @param type $parametros Parametros que seran enviados a la peticion en formato json
     * @param type $timestamp Tiempo en el que se realiza la peticion
     * @param type $authorization Llave para el inicio de sesion
     * @return array 
     */
    public function sendRequest($url, $parametros, $timestamp, $authorization) {
        ob_start();
        $resultRequest = array();
        try {
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_CUSTOMREQUEST => $this->method,
                CURLOPT_POSTFIELDS => $parametros,
                CURLOPT_HTTPHEADER => array(
                    "Timestamp: " . $timestamp,
                    "Authorization:" . $authorization,
                    "content-type: application/json"
                ),
            ));

            $response = curl_exec($curl);
            curl_close($curl);
            $resultRequest = json_decode($response, true);
        } catch (Exception $e) {
            ob_end_clean();
            $resultRequest["meta"]["status"] = "Error";
            $resultRequest["meta"]["error_message"] = "Ocurrio un Error en el consumo del WS";
        }
        return $resultRequest;
    }

}

?>