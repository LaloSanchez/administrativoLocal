<?php

include_once(dirname(__FILE__) . "/../../connect/Proveedor.Class.php");
include_once(dirname(__FILE__) . "/../tags/buton/Buton.Class.php");

class Multiple {

    private $provider;
    private $html;
    private $class;
    private $id;
    private $name;
    private $events;
    private $gestor = "mysql";
    private $db = "";
    private $sql;
    private $title;
    private $array;
    private $enamble;
    private $size;
    private $actual;
    private $stmt;
    private $index;
    private $value;
    private $descripcion;

    public function __construct($param) {
        $this->param($param);
        $this->generaSelect();
    }

    private function param($param) {
        foreach ($param as $tag => $value) {
            if (($tag == "class") && ($value != ""))
                $this->_class($value);
            if (($tag == "sql") && ($value != ""))
                $this->_sql($value);
            if (($tag == "id") && ($value != ""))
                $this->_id($value);
            if (($tag == "name") && ($value != ""))
                $this->_name($value);
            if (($tag == "enamble") && ($value != ""))
                $this->_enamble($value);
            if (($tag == "actual") && ($value != ""))
                $this->_actual($value);
            if (($tag == "events") && ($value != ""))
                $this->_events($value);
            if (($tag == "value") && ($value != ""))
                $this->_value($value);
            if (($tag == "descripcion") && ($value != ""))
                $this->_descripcion($value);
            if (($tag == "title") && ($value != ""))
                $this->_title($value);
            if (($tag == "gestor") && ($value != ""))
                $this->_gestor($value);
            if (($tag == "db") && ($value != ""))
                $this->_db($value);
            if (($tag == "size") && ($value != ""))
                $this->_size($value);
        }
    }

    private function _size($size) {
        $this->size = $size;
    }

    private function _gestor($gestor) {
        $this->gestor = $gestor;
    }

    private function _db($db) {
        $this->db = $db;
    }

    private function _title($title) {
        $this->title = $title;
    }

    private function _descripcion($descripcion) {
        $this->descripcion = $descripcion;
    }

    private function _value($value) {
        $this->value = $value;
    }

    private function _events($events) {
        $this->events = $events;
    }

    private function _actual($actual="") {
        $this->actual = $actual;
    }

    private function _enamble($enamble=false) {
        $this->enamble = $enamble;
    }

    private function _class($class="") {
        $this->class = $class;
    }

    private function _id($id="") {
        $this->id = $id;
    }

    private function _name($name="") {
        $this->name = $name;
    }

    private function _sql($sql="") {
        if (!(is_array($sql))) {
            $this->sql = $sql;
            $this->getDatos();
        } else {
            $this->array = $sql;
        }
    }

    private function addEvents() {
        if (is_array($this->events)) {
            foreach ($this->events as $tag => $value) {
                $this->html.=" " . $tag . "=\"$value\"";
            }
        }
    }

    private function getDatos() {
        $this->provider = new Proveedor($this->gestor, $this->db);
        $this->provider->connect();
        $this->provider->execute($this->sql);
        $this->stmt = $this->provider->stmt;
        $this->provider->close();
    }

    private function addJavascript() {
        $this->html.="<script language=\"javascript\">";
        $this->html.="add = function(){\n";
        $this->html.="var destino = document.getElementById('" . $this->id . "_Destino')\n";
        $this->html.="var origen = document.getElementById('" . $this->id . "_Origen')\n";
        $this->html.="var leng = destino.length\n";

        $this->html.="for(var index=0; index < origen.length; index++){\n";

        $this->html.="if( (origen.options[index]!=null) && (origen.options[index].selected==true) ){\n";
        $this->html.="var fount = false;\n";
        $this->html.="for(var count=0; count < leng; count++){\n";
        $this->html.=" if(destino.options[count]!=null){\n";
        $this->html.="  if(origen.options[index].value == destino.options[count].value) {\n";
        $this->html.="    fount=true;\n";
        $this->html.="    break;\n";
        $this->html.="  }\n"; //if(origen.options[i].text == destino.options[count].text)
        $this->html.=" }\n"; //if(destino.options[count]!=null)
        $this->html.="}\n"; //for(var count=0; count < leng; count++)

        $this->html.="if(fount!=true){\n";
        $this->html.="destino.options[leng] = new Option(origen.options[index].text);\n";
        $this->html.="destino.options[leng].value=origen.options[index].value;\n";
        $this->html.="destino.options[leng].ondblclick=function(){\n";
        $this->html.="    remove();\n";
        $this->html.="}\n";
        $this->html.="leng++;\n";
        $this->html.="}"; // if(fount!=true)

        $this->html.="}\n"; //if( (origen.options[index]!=null) && (origen.options[index].selected==true) )

        $this->html.="}\n"; //for(var index=0; index < origen.length; index++)

        $this->html.="}\n"; // function

        $this->html.="addall = function(){\n";

        $this->html.="var destino = document.getElementById('" . $this->id . "_Destino')\n";
        $this->html.="var origen = document.getElementById('" . $this->id . "_Origen')\n";
        $this->html.="var leng = destino.length\n";

        $this->html.="for(var index=0; index < origen.length; index++){\n";

        $this->html.="if( (origen.options[index]!=null)){\n";
        $this->html.="var fount = false;\n";
        $this->html.="for(var count=0; count < leng; count++){\n";
        $this->html.=" if(destino.options[count]!=null){\n";
        $this->html.="  if(origen.options[index].value == destino.options[count].value) {\n";
        $this->html.="    fount=true;\n";
        $this->html.="    break;\n";
        $this->html.="  }\n"; //if(origen.options[i].text == destino.options[count].text)
        $this->html.=" }\n"; //if(destino.options[count]!=null)
        $this->html.="}\n"; //for(var count=0; count < leng; count++)

        $this->html.="if(fount!=true){\n";
        $this->html.="destino.options[leng] = new Option(origen.options[index].text);\n";
        $this->html.="destino.options[leng].value=origen.options[index].value;\n";
        $this->html.="leng++;\n";
        $this->html.="}"; // if(fount!=true)

        $this->html.="}\n"; //if( (origen.options[index]!=null) && (origen.options[index].selected==true) )

        $this->html.="}\n"; //for(var index=0; index < origen.length; index++)

        $this->html.="}\n"; //function

        $this->html.="remove = function(){\n";
        $this->html.="var destino = document.getElementById('" . $this->id . "_Destino')\n";
        $this->html.="var leng = destino.length\n";
        $this->html.="for(var index = (leng-1); index >= 0; index--) {\n";
        $this->html.=" if((destino.options[index] != null) && (destino.options[index].selected == true)) {\n";
        $this->html.="destino.options[index] = null;";
        $this->html.=" }"; //if((destino.options[index] != null) && (destino.options[index].selected == true))
        $this->html.="}"; //for(var i = (len-1); i >= 0; i--)
        $this->html.="}\n"; //function

        $this->html.="removeall = function(){\n";
        $this->html.="var destino = document.getElementById('" . $this->id . "_Destino')\n";
        $this->html.="var leng = destino.length\n";
        $this->html.="for(var index = (leng-1); index >= 0; index--) {\n";
        $this->html.=" if((destino.options[index] != null)) {\n";
        $this->html.="destino.options[index] = null;";
        $this->html.=" }"; //if((destino.options[index] != null) && (destino.options[index].selected == true))
        $this->html.="}"; //for(var i = (len-1); i >= 0; i--)
        $this->html.="}\n"; //function

        $this->html.="</script>";
    }

    private function generaSelect() {

        $this->html.="<style type=\"text/css\">\n";
        $this->html.="#" . $this->id . "_Origen{\n";
        $this->html.="width: 100%;\n";
        $this->html.="}\n";
        $this->html.="#" . $this->id . "_Destino{\n";
        $this->html.="width: 100%;\n";
        $this->html.="}\n";
        $this->html.="</style>";

        $this->addJavascript();

        $this->html.="<div id='div".$this->id."'>";
        $this->html.="<table align='center' cellpadding='2' cellspacing='0' border='0' width='83%'  class='Arial12'>\n";
        $this->html.="<tr>\n";
        $this->html.="<td width='40%'>\n";
        $this->html.="<Select class='" . $this->class . "' id='" . $this->id;
        $this->html.="_Origen' name='" . $this->name . "_Origen' title='" . $this->title . "'";

        if ($this->enamble == true)
            $this->html.= " disabled ";

        $this->addEvents();

        $this->html.="multiple size='" . $this->size . "' >";

        while ($this->array = $this->provider->fetch_array($this->stmt, 0)) {
            $this->html.="\n<option value='";

            $this->html.=$this->array[(($this->value == "") ? 0 : $this->value)] . "'";

            if ($this->actual == $this->array[(($this->value != "") ? $this->value : 0 )])
                $this->html.=" selected ";

            $this->html.=" title='" . $this->array[(($this->descripcion != "") ? $this->descripcion : 1 )] . "' onDblclick=\"add()\">";

            $this->html.=$this->array[(($this->descripcion != "") ? $this->descripcion : 1 )];

            $this->html.="</option>";
        }

        $this->html.="</Select>\n";
        $this->html.="</td>\n";
        $this->html.="<td width='10%'>\n";
        $this->html.="<table align='center' border='0'>\n";
        $this->html.="<tr>\n";
        $this->html.="<td align='center'>\n";
        $button = new Buton(array("id" => "bntadd", "value" => "&nbsp;&nbsp;>", "class" => "button", "events" => array("onClick" => "add()")));
        $this->html.=$button->returnHTML();
        $this->html.="</td>\n";
        $this->html.="</tr>\n";
        $this->html.="<tr>\n";
        $this->html.="<td align='center'>\n";
        $button = new Buton(array("id" => "bntaddall", "value" => ">>", "class" => "button", "events" => array("onClick" => "addall()")));
        $this->html.=$button->returnHTML();
        $this->html.="</td>\n";
        $this->html.="<tr>\n";
        $this->html.="<td align='center'>\n";
        $button = new Buton(array("id" => "bntremov", "value" => "<&nbsp;&nbsp;", "class" => "button", "events" => array("onClick" => "remove()")));
        $this->html.=$button->returnHTML();
        $this->html.="</td>\n";
        $this->html.="</tr>\n";
        $this->html.="<td align='center'>\n";
        $button = new Buton(array("id" => "bntremovall", "value" => "<<", "class" => "button", "events" => array("onClick" => "removeall()")));
        $this->html.=$button->returnHTML();
        $this->html.="</td>\n";
        $this->html.="</tr>\n";
        $this->html.="</table>\n";

        $this->html.="</td>\n";


        $this->html.="<td width='40%'>\n";
        $this->html.="<Select class='" . $this->class . "' id='" . $this->id;
        $this->html.="_Destino' name='" . $this->name . "_Destino' title='" . $this->title . "'";

        if ($this->enamble == true)
            $this->html.= " disabled ";

        $this->addEvents();

        $this->html.="multiple size='" . $this->size . "' >";
        $this->html.="</Select>";
        $this->html.="</td>\n";
        $this->html.="</tr>\n";
        $this->html.="</table>\n";
        $this->html.="</div>";
    }

    public function imprime() {
        echo $this->html;
    }

    public function returnHtml() {
        return $this->html;
    }

}
?>