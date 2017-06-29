<?php

class Configuracion_server {

    private $url;
    private $webservice;
    private $host;
    private $xml;
    private $text = "";
    private $menssage = "";

    public function Configuracion_server($url, $webservice, $host) {
        $this->url = $url;
        $this->webservice = strtoupper($webservice);
        $this->host = strtoupper($host);
        $this->getXml();
    }

    public function getHost() {
        try {
            $var = $this->xml->{$this->webservice}->{$this->host}->host; //->->host
            return $var;
        } catch (Exception $e) {
            $this->menssage = $e->getMessage();
            $this->imprime();
        }
    }

    private function getXml() {
        try {
            $this->openXml();
            $this->xml = simplexml_load_string($this->text);
            if (!is_object($this->xml)) {
                $this->menssage = ('Error en la lectura del XML de configuracion');
                $this->imprime();
            }
        } catch (ErrorException $e) {
            $this->menssage = $e->getMessage();
            $this->imprime();
        }
    }

    private function openXml() {
        $this->text = file_get_contents($this->url);
    }

    private function imprime() {
        echo $this->menssage;
    }

}

?>