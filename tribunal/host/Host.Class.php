<?php

include_once(dirname(__FILE__) . "/ExceptionHost.Class.php");
include_once(dirname(__FILE__) . "/../logger/Logger.Class.php");

class Host {

    private $file = null;
    private $tag = null;
    private $text = null;
    private $xml = null;

    public function __construct($file = "", $tag = "") {
        ob_start();
        try {
            if ($file != "")
                $this->file = $file;
            else
                throw new ExceptionHost("La ruta del archivo de configuracion no valida", 1);
        } catch (ExceptionHost $e) {
            $log = new Logger();
            $text = "Codigo->" . $e->getCode() . " Error-> " . $e->getMessage() . " trace-> " . $e->getTraceAsString() . "\n";
            $log->w_onError($text);
        }

        try {
            if ($tag != "")
                $this->tag = $tag;
            else
                throw new ExceptionHost("El tag que se desea buscar no es valido", 2);
        } catch (ExceptionHost $e) {
            $log = new Logger();
            $text = "Codigo->" . $e->getCode() . " Error -> " . $e->getMessage() . " trace-> " . $e->getTraceAsString() . "\n";
            $log->w_onError($text);
        }
    }

    public function getConnect() {
        try {
            $this->openXml();
            $this->xml = simplexml_load_string($this->text);
            
            if (!is_object($this->xml)) {
                throw new ExceptionHost("Error en la lectura del XML de configuracion", 3);
            } else {
                return $this->getHost($this->xml);
            }
        } catch (ExceptionHost $e) {
            $log = new Logger();
            $text = "Codigo->" . $e->getCode() . " Error -> " . $e->getMessage() . " trace-> " . $e->getTraceAsString() . "\n";
            $log->w_onError($text);
        }
    }

    public function getHost($xml) {
        if (count($xml->children()) > 0) {
            foreach ($xml->children() as $Tag => $value) {
                if (strtoupper(trim($Tag)) == strtoupper(trim($this->tag))) {
                    return $value->children();
                } else {
                    $host = "";
                    $host = $this->getHost($value);
                    if ($host != "") {
                        return $host;
                    }
                }
            }
        }
    }

    private function openXml() {
        try {
            if (file_exists($this->file)) {
                $this->text = file_get_contents($this->file);
            } else {
                throw new ExceptionHost("El archivo de configuracion no existe", 4);
            }
        } catch (ExceptionHost $e) {
            $log = new Logger();
            $text = "Codigo->" . $e->getCode() . " Error -> " . $e->getMessage() . " trace-> " . $e->getTraceAsString() . "\n";
            $log->w_onError($text);
        }
    }

}

?>