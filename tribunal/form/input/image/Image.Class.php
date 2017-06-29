<?php
include_once(dirname(__FILE__) . "/../input/Input.Class.php");

class Image implements Input {

    private $html;
    private $value;
    private $name;
    private $class;
    private $events;
    private $id;
    private $enamble;
    private $src;
    private $title;
    private $alt;
    private $type;

    public function __construct($param) {
        $this->param($param);
        $this->generaTagHtml();
    }

    public function param($param) {
        foreach ($param as $tag => $value) {
            if (($tag == "value") && ($value != ""))
                $this->_value($value);
            if (($tag == "type") && ($value != ""))
                $this->_type($value);
            if (($tag == "name") && ($value != ""))
                $this->_name($value);
            if (($tag == "class") && ($value != ""))
                $this->_class($value);
            
            if (($tag == "id") && ($value != ""))
                $this->_id($value);
            if (($tag == "title") && ($value != ""))
                $this->_title($value);
            if (($tag == "src") && ($value != ""))
                $this->_src($value);
            if (($tag == "events") && ($value != ""))
                $this->_events($value);
        }
    }

    public function addEvents() {
      if(is_array($this->events)){
          foreach ($this->events as $tag => $value){
             $this->html.=" ".$tag."= \"$value\"";
          }
      }

    }

    public function generaTagHtml() {
        $this->html.="<input ";
        $this->html.= ( $this->type != "") ? "type='" . $this->type . "' " : "type='image'";
        $this->html.= ( $this->value != "") ? "value='" . $this->value . "' " : "";
        $this->html.= ( $this->name != "") ? "name='" . $this->name . "' " : "name='' ";
        $this->html.= ( $this->class != "") ? "class=" . $this->class."' ": "";
        $this->html.= ( $this->id != "") ? "id='" . $this->id . "' " : "";
        $this->html.= ( $this->src != "") ? "src='" . $this->src . "' " : "";
        $this->html.= ( $this->title != "") ? "title='" . $this->title . "' " : "";
        
        
        $this->addEvents();
        $this->html.="/>";
    }

    private function _src($src){
        $this->src=$src;
    }

    public function _type($type) {
        $this->type = "image";
    }

    public function _name($name) {
        $this->name = $name;
    }

    public function _class($class) {
        $this->class = $class;
    }

    public function _value($value) {
        $this->value = $value;
    }

    public function _enamble($enamble) {
        $this->enamble = $enamble;
    }

    public function _id($id) {
        $this->id = $id;
    }

    public function _title($title) {
        $this->title = $title;
    }

    public function _size($size) {
        $this->size = $size;
    }

    public function _maxlength($maxlength) {
        $this->maxlength = $maxlength;
    }

    public function _events($events) {
        $this->events = $events;
    }

    public function imprime(){
        echo $this->html;
    }

    public function returnHtml(){
        return $this->html;
    }


}
?>