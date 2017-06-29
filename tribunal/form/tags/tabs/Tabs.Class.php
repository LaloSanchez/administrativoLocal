<?php

class Tabs {

    private $html = "";
    private $id = "tabs";
    private $class = "tabs";
    private $title;
    private $tap;

    public function __construct($param) {
        $this->param($param);
        $this->generaTagHtml();
    }

    private function param($param) {
        foreach ($param as $tag => $value) {
            (($tag == "id") && ($value != "")) ? $this->_id($value) : "";
            (($tag == "class") && ($value != "")) ? $this->_class($value) : "";
            (($tag == "title") && ($value != "")) ? $this->_title($value) : "";
            (($tag == "tap") && ($value != "")) ? $this->_tap($value) : "";
        }
    }

    private function _tap($tap) {
        $this->tap = $tap;
    }

    private function _id($id) {
        $this->id = $id;
    }

    private function _class($class) {
        $this->class = $class;
    }

    private function _title($title) {
        $this->title = $title;
    }

    private function addJavascript() {

        $this->html.="<script type=\"text/javascript\" language=\"javascript\">\n";
        $this->html.="active_$this->id = function(object){\n";
        $this->html.="var ul=object.parentNode;\n";
        $this->html.="for(var index=0; index < ul.children.length; index ++){\n";
        $this->html.="ul.children[index].id='';\n";
        $this->html.="}\n";
        $this->html.="object.id='current';\n";
        $this->html.="}\n";

        $this->html.="carga_$this->id = function(){\n";
        $this->html.="try{\n";
        $this->html.="var tabs = document.getElementById('" . $this->id . "');\n";
        $this->html.="for(var index=0; index < tabs.children.length; index ++){\n";
        $this->html.="tabs.children[index].id='';\n";
        $this->html.="}\n";
        $this->html.="tabs.children[0].id='current';\n";
        //$this->html.="var iframe=document.getElementsByName('".$this->id."_iframe')[0];\n";
        //$this->html.=$this->id."_iframe.src='".$this->tap[0]['url']."';\n";
        $this->html.=$this->id."_iframe.location='".$this->tap[0]['url']."';\n";
        $this->html.="}catch(e){\n";
        $this->html.="var texto='Ocurrio un error en la pagina' +\n";
        $this->html.="' verifique con informatica descripcion: ' + ";
        $this->html.="e.message + \n' click en OK para continuar';\n";
        $this->html.="alert(texto);\n";
        $this->html.="}\n";
        $this->html.="}\n";

        $this->html.="ajustar_$this->id =function(){\n";
        $this->html.="var object=document.getElementsByName('".$this->id."_iframe')[0];\n";
        $this->html.="var isIE = (navigator.appName.indexOf('Explorer')!=-1) ? true : false;\n";
        $this->html.="if(isIE){\n";
        $this->html.="var ejey = object.document.body.offsetHeight + object.document.body.scrollHeight;\n";
        $this->html.="}else{\n";
        $this->html.="var ejey = object.offsetHeight + (object.scrollHeight * 2.6);\n";
        $this->html.="}\n";
        $this->html.="object.height=ejey;\n";
        $this->html.="}\n";

        $this->html.="</script>\n";
    }

    private function generaTagHtml() {
        $this->addJavascript();
        $this->html.="<ul ";
        $this->html.= ( $this->class != "") ? "class=\"$this->class\"" : "";
        $this->html.= ( $this->id != "") ? "id=\"$this->id\"" : "";
        $this->html.= ( $this->title != "") ? "title=\"$this->title\"" : "";
        $this->html.=">\n";

        for ($index = 0; $index < count($this->tap); $index++) {
            $this->html.="<li ";
            $this->html.=" onClick='active_$this->id(this)'";
            $this->html.="><a href=\"" . $this->tap[$index]['url'] . "\" target='" . $this->id . "_iframe'>";
            $this->html.=strtoupper($this->tap[$index]['text']) . "";
            $this->html.="</a></li>\n";
        }

        $this->html.="</ul>\n";

        $this->html.="<style>\n";
        //$this->html.=".".$this->id."_iframe{\n";
        $this->html.="border-bottom: 2px solid #006633;";
        //$this->html.="border-right: 2px solid #006633;";
        //$this->html.="border-top: 1px solid #A4C2A3;";
        //$this->html.="border-left: 2px solid #A4C2A3;";
        //$this->html.="}\n";
        $this->html.="</style>\n";
        // onresize='ajustar_$this->id()' height='100%' name='" . $this->id . "_iframe' onload="ajustar()"
        $this->html.="<iframe onload=\"ajustar_$this->id()\" src='' name=\"". $this->id ."_iframe\"  id=\"".$this->id."_iframe\" width='99%' scrolling='no' allowtransparency style='padding: 0 0 0 0;' frameborder='0' marginheight='0' marginwidth='0' >";
        $this->html.="Si puedes ver este texto, tu navegador necesita ser actualizado";
        $this->html.="</iframe>";
        $this->html.="<script type=\"text/javascript\" language=\"javascript\">\n";
        $this->html.="carga_$this->id();\n";
        //$this->html.="ajustar_$this->id();\n";
        $this->html.="</script>\n";
    }

    public function imprime() {
        echo $this->html;
    }

    public function returnHtml() {
        return $this->html;
    }

}
?>
