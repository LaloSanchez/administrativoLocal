<?php

class TreeDocument {

    private $name;
    private $action;
    private $url;
    private $document;
    private $target="iframeContenido";

    public function __construct($name,$action="",$url="",$document="word",$target="") {
       $this->action=$action;
       $this->name=$name;
       $this->url=$url;
       $this->document=strtoupper($document);
       $this->target=$target;
    }

    public function render($src="../../images/",$idPersonal="") {
        $html = "";
        $html.="<li class='treeviewFolderLi'>";
        $html.="<img src='".$src."empty.gif' title='" . $this->name . "'>";
        $html.="<img src='".$src."".$this->getDocument($this->document)."' title='" . $this->name . "'>";
        $html.= ( $this->action == '' ? '<a href="'.( ($this->url=="") ? "#noir": $this->url ).'" target="'.$this->target.'" id="node_114" class="lnktxt" onclick="' . $this->action . '" title="' . $this->name . '">' . $this->name . '</a>' :  $this->name );
        $html.='</li>';
        return $html;
    }

    private function getDocument($document="word"){

       switch($document){
         case "WORD":
           $html="WORD.png";
          break;
         case "PDF":
           $html="PDF.png";
          break;
         case "EXCEL":
           $html="EXCEL.png";
          break;
         case "FLV":
              $html="FLV.png";
          break;
         case "GIF":
              $html="GIF.png";
             break;
         case "JPG":
              $html="JPG.png";
             break;
         case "MP3":
              $html="MP3.png";
             break;
         case "WMV":
              $html="WMV.png";
             break;
         case "AVI":
              $html="AVI.png";
             break;

         default:
           $html="Hoja.png";
          break;
       }
       return $html;
    }

}

?>
