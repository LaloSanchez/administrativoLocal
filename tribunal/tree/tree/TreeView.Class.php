<?php

abstract class TreeView {
    private $objectElements = array();
    private $idTable;
    private static $instance = 0;

    abstract public function addDocument($name, $action='',$url="");
    abstract public function addFolder($name, $expanded = false);
    abstract public function getObjectFolder($object);
    abstract public function render($width = 0, $height = 0);
    
}

?>
