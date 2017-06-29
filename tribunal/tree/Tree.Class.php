<?php

include_once(dirname(__FILE__) . "/tree/TreeView.Class.php");
include_once(dirname(__FILE__) . "/treefolder/TreeFolder.Class.php");
include_once(dirname(__FILE__) . "/treedocument/TreeDocument.Class.php");

class Tree extends TreeView {

    private $objectElements = array();
    private $idTable;
    private static $instance = 0;

    public function __construct($instace=0) {
        self::$instance=$instace;
        self::$instance++;
        $this->idTable = self::$instance;
    }

    public function addDocument($name, $action="", $url="",$document="word",$target="") {
       $this->objectElements [] = new TreeDocument($name, $action,$url,$document,$target);
    }

    public function addFolder($name, $expanded=false,$default=false,$numFolder="") {
        $elements = count($this->objectElements);
        $this->objectElements[] = new TreeFolder($this->idTable . '.' . ($elements + 1), $name, $expanded,$default,$numFolder);
        return $elements;
    }

    public function getObjectFolder($object) {
        if ($this->objectElements[$object] instanceof TreeFolder) {
            return $this->objectElements[$object];
        } else {
            throw new Exception('objeto invalido');
        }
    }

    public function render($width = 0, $height = 0,$src="../../images/",$idPersonal="") {
        $html ="";
        $html.= '<ul id="objTree' . $this->idTable . '" class="treeview">'."\n";
        foreach ($this->objectElements as $objElement) {
            $html .= $objElement->render($src,$idPersonal);
        }
        $html .= "</ul>\n";
        return $html;
    }

}
?>