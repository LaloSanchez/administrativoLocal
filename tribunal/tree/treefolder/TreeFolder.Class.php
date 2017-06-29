<?php

class TreeFolder {

    private $idPrefix;
    private $name;
    private $expanded;
    private $objectElements = array();
    private $default=false;
    private $numFolder=0;

    public function __construct($idPrefix, $name, $expanded,$default=false,$numFolder) {
        $this->idPrefix = $idPrefix;
        $this->name = $name;
        $this->expanded = $expanded;
        $this->default=$default;
        $this->numFolder=$numFolder;
    }

    public function addDocument($name, $action="", $url="",$document="word",$target="") {
        $this->objectElements [] = new TreeDocument($name, $action,$url,$document,$target);
    }

    public function addFolder($name, $expanded=false,$default=false,$numFolder="") {
        $elements = count($this->objectElements);
        $this->objectElements [] = new TreeFolder($this->idPrefix . '.' . ($elements + 1), $name, $expanded,$default,$numFolder);
        return $elements;
    }

    public function getObjectFolder($object) {
        if ($this->objectElements[$object] instanceof TreeFolder) {
            return $this->objectElements[$object];
        } else {
            throw new Exception('objeto invalido');
        }
    }

    public function render($src="../../images/",$idPersonal="") {
        $html="";
        $html.="<img id=\"objTreeCollapser". $this->idPrefix."\" src=\"".$src."".($this->expanded ? 'collapser' : 'expander') . ".gif\" style=\"visibility:".(((count($this->objectElements)  > 0) || ($this->default)) ? 'show' : 'hidden') . ";\"  onclick=\"treeviewExpand('" . $this->idPrefix ."','".$src."',".$idPersonal.",".$this->numFolder.")\">\n";
        $html.="<img src='".$src."folder".($this->expanded ? 'open' : '').".gif' id='folder".$this->idPrefix."' ondblclick=\"treeviewExpand('".$this->idPrefix ."','".$src."',".$idPersonal.",".$this->numFolder.")\">\n";
        $html.= "<span ondblclick=\"treeviewExpand('". $this->idPrefix ."','".$src."',".$idPersonal.",".$this->numFolder.")\" onselectstart='return false;' title='". $this->name ."' >" . $this->name . "</span>\n";
        $html.="<div id='div".$this->idPrefix."' class='treeviewFolderUl' style='" . ($this->expanded ? 'display: block' : 'display: none') . ";'>\n";
        foreach ($this->objectElements as $objectElement) {
            $html .= $objectElement->render($src);
        }
        $html .= "</div>\n<br>";       
        return $html;
    }

}

?>