<?php

include_once(dirname(__FILE__) . "/../connect/Proveedor.Class.php");

class Xml {

    private $sql = "";
    private $mensaje = false;
    private $query = true;
    private $tipo = "confirma";
    private $text = "";
    private $encoding = array("es" => "iso-8859-1", "utf" => "UTF8");
    private $proveedor;
    private $stmt;
    private $language = "";
    private $html = "";
    private $name = "";
    private $db;

    public function __construct($sql, $query = false, $mensaje = false, $tipo = "confirma", $text = "", $encoding = "es", $name, $db) {
        $this->sql = $sql;
        $this->mensaje = $mensaje;
        $this->tipo = $tipo;
        $this->text = $text;
        $this->language = $encoding;
        $this->db = $db;
        $this->name = $name;
        $this->query = $query;
    }

    public function createXml() {
        if ($this->query == true) {
            $this->proveedor = new Proveedor($this->name, $this->db);
            $this->proveedor->connect();
            $this->proveedor->execute($this->sql);
            if (!$this->proveedor->error()) {
                if ($this->proveedor->rows($this->proveedor->stmt) > 0) {
                    $this->header();
                    $this->html.="<Resultados>\n";
                    while ($rows = $this->proveedor->fetch_array($this->proveedor->stmt, 0)) {
                        $this->html.="<Resultado>\n";
                        for ($index = 0; $index < (count($rows) / 2); $index++) {
                            $this->html.="<" . mysql_field_name($this->proveedor->stmt, $index) . ">";
                            if (strlen($rows[$index]) > 0) {
                                $this->html.='<![CDATA[';
                                if ($this->isFecha($rows[$index])) {
                                    $this->html.=$this->fechaNormal($rows[$index]);
                                } else {
                                    $this->html.=$rows[$index];
                                }
                                $this->html.=']]>';
                            } else {
                                $this->html.="<![CDATA[]]>";
                            }
                            $this->html.="</" . mysql_field_name($this->proveedor->stmt, $index) . ">\n";
                        }
                        $this->html.="</Resultado> \n";
                    }
                    if ($this->mensaje) {
                        $this->html.="<mensaje>\n";
                        $this->html.="<tipo>" . $this->tipo . "</tipo>\n";
                        $this->html.="<text>" . $this->text . "</text>\n";
                        $this->html.="</mensaje>\n";
                    }
                    $this->html.="</Resultados> \n";
                }
            }
            $this->proveedor->stmt = $this->proveedor->free_result($this->proveedor->stmt);
            $this->proveedor->close();
        } else {
            $this->header();
            $this->html.="<Resultado>\n";
            $this->html.="<mensaje>\n";
            $this->html.="<tipo>" . $this->tipo . "</tipo>\n";
            $this->html.="<text>" . $this->text . "</text>\n";
            $this->html.="</mensaje>\n";
            $this->html.="</Resultado> \n";
        }
    }

    private function header() {
        header('Content-type: text/xml;  charset=' . $this->encoding[$this->language] . ' standalone = "yes"');
        $this->html = "<?xml version=\"1.0\" encoding=\"" . $this->encoding[$this->language] . "\" standalone = \"yes\" ?>\n";
    }

    private function isFecha($fecha) {
        if (@$fecha[1] != "-" && @$fecha[4] == "-" && @$fecha[7] == "-" && @$fecha[10] != "-") {
            return true;
        } 
    }

    private function fechaNormal($fecha) {
        ereg("([0-9]{2,4})-([0-9]{1,2})-([0-9]{1,2})", $fecha, $mifecha);
        $lafecha = $mifecha[3] . "/" . $mifecha[2] . "/" . $mifecha[1];
        return $lafecha;
    }

    public function imprime() {
        echo $this->html;
    }

    public function returnHTML() {
        return $this->html;
    }

}

?>