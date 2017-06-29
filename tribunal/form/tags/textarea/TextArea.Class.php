<?php
include_once(dirname(__FILE__) . "/../../../import/Package.Class.php");
new Package(dirname(__FILE__)."/../../../form/combo/");
new Package(dirname(__FILE__)."/../../../form/tags/img/");
class TextArea {

    private $html;
    private $name;
    private $id;
    private $width = "100%";
    private $height="100%";
    private $class;
    private $cols;
    private $rows;
    private $value;
    private $title;
    private $events;
    private $toogle;
    private $src="";

    public function __construct($param) {
        $this->param($param);
        $this->generaTagHtml();
    }

    private function param($param) {
        foreach ($param as $tag => $value) {
            ( ($tag == "name") && ($value != "") ) ? $this->_name($value) : "";
            ( ($tag == "id") && ($value != "") ) ? $this->_id($value) : "";
            ( ($tag == "class") && ($value != "") ) ? $this->_class($value) : "";
            ( ($tag == "cols") && ($value != "") ) ? $this->_cols($value) : "";
            ( ($tag == "rows") && ($value != "") ) ? $this->_rows($value) : "";
            ( ($tag == "value") && ($value != "") ) ? $this->_value($value) : "";
            ( ($tag == "title") && ($value != "") ) ? $this->_title($value) : "";
            ( ($tag == "events") && ($value != "") ) ? $this->_events($value) : "";
            ( ($tag == "toogle") && ($value != "") ) ? $this->_toogle($value) : "";
            ( ($tag == "width") && ($value != "") ) ? $this->_width($value) : "";
            ( ($tag == "height") && ($value != "") ) ? $this->_height($value) : "";
            ( ($tag == "src") && ($value != "") ) ? $this->_src($value) : "";
        }
    }

    private function _height($height) {
        $this->height=$height;
    }

    private function _src($src) {
        $this->src=$src;
    }

    private function _width($width) {
        $this->width = $width;
    }

    private function _name($name) {
        $this->name = $name;
    }

    private function _id($id) {
        $this->id = $id;
    }

    private function _class($class) {
        $this->class = $class;
    }

    private function _cols($cols) {
        $this->cols = $cols;
    }

    private function _rows($rows) {
        $this->rows = $rows;
    }

    private function _value($value) {
        $this->value = $value;
    }

    private function _title($title) {
        $this->title = $title;
    }

    private function _events($events) {
        $this->events = $events;
    }

    private function _toogle($toogle) {
        $this->toogle = $toogle;
    }

    private function addEvents() {
        if (is_array($this->events)) {
            foreach ($this->events as $tag => $value) {
                $this->html.=" " . $tag . "=\"$value\"";
            }
        }
    }

    private function addButtons() {
        $html = "";
        $param=array("src"=>"$this->src/textarea/negrita.gif",
                "alt"=>"Negrita",
                "title"=>"Negrita",
                "width"=>"20",
                "height"=>"20",
                "events"=>array("onMouseOver"=>"seleccionar_$this->name(this, '#B6BDD2');",
                        "onMouseOut"=>"desseleccionar_$this->name(this);",
                        "onMouseDown"=>"edita_$this->name('" . $this->name . "_editor','bold',false,null);"
        ));
        $img = new Img($param);
        $html.=$img->returnHtml()."&nbsp;\n";
        $param=array("src"=>"$this->src/textarea/cursiva.gif",
                "alt"=>"Cursiva",
                "title"=>"Cursiva",
                "width"=>"20",
                "height"=>"20",
                "events"=>array("onMouseOver"=>"seleccionar_$this->name(this, '#B6BDD2');",
                        "onMouseOut"=>"desseleccionar_$this->name(this);",
                        "onMouseDown"=>"edita_$this->name('" . $this->name . "_editor','italic',false,null);"
        ));
        $img = new Img($param);
        $html.=$img->returnHtml()."&nbsp;\n";
        $param=array("src"=>"$this->src/textarea/subrayar.gif",
                "alt"=>"Subrayar",
                "title"=>"Subrayar",
                "width"=>"20",
                "height"=>"20",
                "events"=>array("onMouseOver"=>"seleccionar_$this->name(this, '#B6BDD2');",
                        "onMouseOut"=>"desseleccionar_$this->name(this);",
                        "onMouseDown"=>"edita_$this->name('" . $this->name . "_editor','underline',false,null);"
        ));
        $img = new Img($param);
        $html.=$img->returnHtml()."&nbsp;\n";
        $param=array("src"=>"$this->src/textarea/izquierda.gif",
                "alt"=>"Izquierda",
                "title"=>"Izquierda",
                "width"=>"20",
                "height"=>"20",
                "events"=>array("onMouseOver"=>"seleccionar_$this->name(this, '#B6BDD2');",
                        "onMouseOut"=>"desseleccionar_$this->name(this);",
                        "onMouseDown"=>"edita_$this->name('" . $this->name . "_editor','justifyleft',false,null);"
        ));
        $img = new Img($param);
        $html.=$img->returnHtml()."&nbsp;\n";
        $param=array("src"=>"$this->src/textarea/centrar.gif",
                "alt"=>"Centrar",
                "title"=>"Centrar",
                "width"=>"20",
                "height"=>"20",
                "events"=>array("onMouseOver"=>"seleccionar_$this->name(this, '#B6BDD2');",
                        "onMouseOut"=>"desseleccionar_$this->name(this);",
                        "onMouseDown"=>"edita_$this->name('" . $this->name . "_editor','justifycenter',false,null);"
        ));
        $img = new Img($param);
        $html.=$img->returnHtml()."&nbsp;\n";
        $param=array("src"=>"$this->src/textarea/derecha.gif",
                "alt"=>"Derecha",
                "title"=>"Derecha",
                "width"=>"20",
                "height"=>"20",
                "events"=>array("onMouseOver"=>"seleccionar_$this->name(this, '#B6BDD2');",
                        "onMouseOut"=>"desseleccionar_$this->name(this);",
                        "onMouseDown"=>"edita_$this->name('" . $this->name . "_editor','justifyright',false,null);"
        ));
        $img = new Img($param);
        $html.=$img->returnHtml()."&nbsp;\n";

        $param=array("src"=>"$this->src/textarea/justificar.gif",
                "alt"=>"Justificar",
                "title"=>"Justificar",
                "width"=>"20",
                "height"=>"20",
                "events"=>array("onMouseOver"=>"seleccionar_$this->name(this, '#B6BDD2');",
                        "onMouseOut"=>"desseleccionar_$this->name(this);",
                        "onMouseDown"=>"edita_$this->name('" . $this->name . "_editor','justifyfull',false,null);"
        ));
        $img = new Img($param);
        $html.=$img->returnHtml()."&nbsp;\n";

        $param=array("src"=>"$this->src/textarea/bullist.gif",
                "alt"=>"Vinetas",
                "title"=>"Vinetas",
                "width"=>"20",
                "height"=>"20",
                "events"=>array("onMouseOver"=>"seleccionar_$this->name(this, '#B6BDD2');",
                        "onMouseOut"=>"desseleccionar_$this->name(this);",
                        "onMouseDown"=>"edita_$this->name('" . $this->name . "_editor','insertunorderedlist',false,null);"
        ));
        $img = new Img($param);
        $html.=$img->returnHtml()."&nbsp;\n";

        $param=array("src"=>"$this->src/textarea/numlist.gif",
                "alt"=>"Numeracion",
                "title"=>"Numeracion",
                "width"=>"20",
                "height"=>"20",
                "events"=>array("onMouseOver"=>"seleccionar_$this->name(this, '#B6BDD2');",
                        "onMouseOut"=>"desseleccionar_$this->name(this);",
                        "onMouseDown"=>"edita_$this->name('" . $this->name . "_editor','insertorderedlist',false,null);"
        ));
        $img = new Img($param);
        $html.=$img->returnHtml()."&nbsp;\n";

        $param=array("src"=>"$this->src/textarea/undo.gif",
                "alt"=>"Deshacer",
                "title"=>"Deshacer",
                "width"=>"20",
                "height"=>"20",
                "events"=>array("onMouseOver"=>"seleccionar_$this->name(this, '#B6BDD2');",
                        "onMouseOut"=>"desseleccionar_$this->name(this);",
                        "onMouseDown"=>"edita_$this->name('" . $this->name . "_editor','Undo',false,null);"
        ));
        $img = new Img($param);
        $html.=$img->returnHtml()."&nbsp;\n";

        $param=array("src"=>"$this->src/textarea/redo.gif",
                "alt"=>"Rehacer",
                "title"=>"Rehacer",
                "width"=>"20",
                "height"=>"20",
                "events"=>array("onMouseOver"=>"seleccionar_$this->name(this, '#B6BDD2');",
                        "onMouseOut"=>"desseleccionar_$this->name(this);",
                        "onMouseDown"=>"edita_$this->name('" . $this->name . "_editor','Redo',false,null);"
        ));
        $img = new Img($param);
        $html.=$img->returnHtml()."&nbsp;\n";

        $param = array("name" => "fonts", "events" => array("onChange" => "edita_$this->name('" . $this->name . "_editor','fontname',false,this.value)"), "sql" => array("Arial", "Arial Bold", "Tahoma", "Verdana", "Times New Roman"),"title"=>"Tipo de Letra");
        $selectFont = new Select($param);
        $html.=$selectFont->returnHtml() . "&nbsp;\n";

        $param = array("name" => "size", "events" => array("onChange" => "action_$this->name('" . $this->name . "_editor','FontSize',false,this.value)"), "sql" => array("1", "2", "3", "4", "5", "6", "7"),"title"=>"tama&ntilde;o de letra");
        $selectFont = new Select($param);
        $html.=$selectFont->returnHtml() . "&nbsp;\n";

        return $html;
    }

    private function generaTagHtml() {
        //$this-> html.="alert(window.parent.$this->name.document.body.innerHTML)";

        $this->html.="<script language=\"javascript\" type=\"\">\n";
        $this->html.="function seleccionar_$this->name(img, fondo) {\n";
        $this->html.="    img.style.backgroundColor = fondo;\n";
        //$this->html.="    img.style.border = '1px solid #0A246A';\n";
        $this->html.="    img.style.cursor = 'pointer';\n";
        $this->html.="}\n";
        $this->html.="function desseleccionar_$this->name(img) {\n";
        $this->html.="    img.style.backgroundColor = '#FFFFFF';\n";
        //$this->html.="    img.style.border = '1px solid #FFFFFF';\n";
        $this->html.="}\n";

        $this->html.="function action_$this->name(o,comando,bool,valor){";
        $this->html.="var iframe = document . getElementById(o);\n var selText = '';\n";
        $this->html.="    iframe . contentWindow . focus();\n";
        $this->html.="document . getElementById(o) . contentWindow . document . execCommand(comando, bool, valor);\n";
        $this->html.="}";

        $this->html.="function edita_$this->name(o, comando, bool, valor){";
        $this->html.="var iframe = document . getElementById(o);\n";
        $this->html.="if (comando != 'createlink') {\n";
        $this->html.="    iframe . contentWindow . focus();\n";
        $this->html.="    document . getElementById(o) . contentWindow . document . execCommand(comando, bool, valor);\n";
        $this->html.="} else {\n";
        $this->html.="    _url = prompt('Introduce la URLs del enlace', '');\n";
        $this->html.="    if (_url == null || _url . match('^\\s*$')) {\n";
        $this->html.="        iframe . contentWindow . focus();\n";
        $this->html.="        return;\n";
        $this->html.="    }\n";
        $this->html.="    rango=document . getElementById(o) . contentWindow . document . selection . createRange();\n";
        $this->html.="    txt = rango . htmlText;\n";
        //$this->html.="    liga = \"<a href='' + _url + '' target='_parent'>' + txt + '</A>';";
        $this->html.="    if (txt . length == 0) {\n";
        $this->html.="        alert('Selecciona el texto que quieres enlazar');\n";
        $this->html.="    } else {\n";
        $this->html.="        rango . pasteHTML(liga);\n";
        $this->html.="    }\n";
        $this->html.="}\n";
        $this->html.="}\n";

        $this->html.="</script>\n";

        $this->html.="<style>\n";
        $this->html.=".toogle_$this->name{\n";
        $this->html.="border:0px solid black;\n";
        $this->html.="background:#eeeeee;\n";
        $this->html.="position: relative;\n";
        $this->html.="top: 0px;\n";
        $this->html.="padding: 0 0 0 0;\n";
        $this->html.="width: ".$this->width.";\n";
        $this->html.="}\n";
        $this->html.=".toogle_$this->name img{\n";
        $this->html.="background:#FFFFFF;\n";
        $this->html.="}\n";
        $this->html.="</style>\n";

        $this->html.="<div id='contenedor'>";

        $this->html.= ( $this->toogle == true) ? "<div id='toogle' class='toogle_$this->name'>" . $this->addButtons() . "</div>" : "";

        $this->html.="<iframe ";
        $this->html.= ( $this->class != "") ? "class='" . $this->class . "' " : "";
        $this->html.= ( $this->id != "") ? "id='" . $this->id . "_editor' " : "";
        $this->html.= ( $this->name != "") ? "name='" . $this->name . "_editor' " : "";
        $this->html.= ( $this->rows != "") ? "rows='" . $this->rows . "' " : "";
        $this->html.= ( $this->cols != "") ? "cols='" . $this->cols . "' " : "";
        $this->html.= ( $this->width != "") ? "width='" . $this->width . "' " : "";
        $this->html.= ( $this->title != "") ? "title='" . $this->title . "' " : "";
        $this->html.= ( $this->height != "") ? "height='" . $this->height . "' " : "";

        $this->addEvents();

        $this->html.="frameborder='1' marginheight='0' marginwidth='0' scrolling='auto' >";
        $this->html.="</iframe>\n";
        $this->html.="<textarea name='$this->name' id='".$this->id."' cols='30' style='display:none;'>".$this->value."</textarea>\n";
        $this->html.="<script language=\"javascript\" type=\"\">\n";



        $this->html.="var iframe = document.getElementById('" . $this->id . "_editor');\n";
        $this->html.="var doc=iframe.contentWindow . document;\n";
        $this->html.=$this->name . "_editor.document.open();\n";

        $this->html.="doc.write('<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">' + \n";
        $this->html.="'<html>' +\n";
        $this->html.="'<head>' +\n";
        $this->html.="'<title>' +\n";
        $this->html.="'Document blanck' +\n";
        $this->html.="'</title>' +\n";
        $this->html.="'</head>' +\n";
        $this->html.="'<body style=\"padding: 2px; margin: 0; spacing: 0; word-wrap: break-word;\">' +\n";
        $this->html.="document.getElementById('".$this->id."').value +\n";
        $this->html.="'<body>' +\n";
        $this->html.="'</body>' +\n";
        $this->html.="'</html>');\n";

        $this->html.=$this->name . "_editor.document.close();\n";
        $this->html.=$this->name . "_editor.document.designMode = 'On';\n";
        $this->html.="modificar=function(){\n";
        $this->html.="var txt = document.getElementById('".$this->id."');\n";
        $this->html.="txt.value=".$this->name."_editor.document.body.innerHTML;\n";
        $this->html.="};\n";
        $this->html.=$this->name."limpiar=function(){\n";
        $this->html.="alert('hola');\n";
        //$this->html.="var txt = document.getElementById('".$this->id."');\n";
        //$this->html.=$this->name."_editor.document.body.innerHTML='';\n";
        $this->html.="};\n";
        $this->html.="var editor = document.getElementsByName('".$this->name."_editor')[0];\n";
        $this->html.="if(editor.addEventListener){\n";
        $this->html.="editor.contentDocument.body.addEventListener('blur', modificar, false);\n";
        $this->html.="editor.contentDocument.body.addEventListener('reset',".$this->name."limpiar, false);\n";
        $this->html.="editor.contentDocument.addEventListener('blur', modificar, false);\n";
        $this->html.="editor.contentDocument.addEventListener('reset', ".$this->name."limpiar, false);\n";
        $this->html.="} else if(editor.attachEvent){\n";
        $this->html.="editor.attachEvent('onblur', modificar);\n";
        $this->html.="editor.attachEvent('onreset', ".$this->name."limpiar);\n";
        $this->html.="}\n";

        $this->html.="</script>\n";

        $this->html.="</div>\n";
    }

    public function imprime() {
        echo $this->html;
    }

    public function returnHtml() {
        return $this->html;
    }

}
?>