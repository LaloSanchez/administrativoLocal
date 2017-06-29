<?php

class Img {

    private $src;
    private $events;
    private $alt;
    private $class;
    private $id;
    private $name;
    private $title;
    private $width;
    private $height;

    private $html;

    public function  __construct($param) {
        $this->param($param);
        $this->generaHtml();
    }
    private function param($param) {
        foreach ($param as $tag => $value) {
            if (($tag == "class") && ($value != ""))
                $this->_class($value);
            if (($tag == "title") && ($value != ""))
                $this->_title($value);
            if (($tag == "id") && ($value != ""))
                $this->_id($value);
            if (($tag == "events") && ($value != ""))
                $this->_events($value);
            if (($tag == "src") && ($value != ""))
                $this->_src($value);
            if (($tag == "alt") && ($value != ""))
                $this->_alt($value);
            if (($tag == "name") && ($value != ""))
                $this->_name($value);
            if (($tag == "height") && ($value != ""))
                $this->_height($value);
            if (($tag == "width") && ($value != ""))
                $this->_width($value);
        }
    }
    private function _height($height) {
        $this->height = $height;
    }

    private function _width($width) {
        $this->width = $width;
    }

    private function _name($name) {
        $this->name = $name;
    }

    private function _alt($alt) {
        $this->alt = $alt;
    }

    private function _src($src) {
        $this->src = $src;
    }

    private function _class($class) {
        $this->class = $class;
    }

    private function _id($id) {
        $this->id = $id;
    }

    private function _title($title) {
        $this->title = $title;
    }

    private function _events($events) {
        $this->events = $events;
    }

    private function addEvents() {
        if (is_array($this->events)) {
            foreach ($this->events as $tag => $value) {
                $this->html.=" " . $tag . "=\"$value\"";
            }
        }
    }

    private function generaHtml(){
       $this->html="<img ";
       $this->html.=($this->src != "") ?  "src=\"$this->src\" " : "";
       $this->html.=($this->alt != "") ?  "alt=\"$this->alt\" " : "";
       $this->html.=($this->id != "") ?  "id=\"$this->id\" " : "";
       $this->html.=($this->name != "") ?  "name=\"$this->name\" " : "";
       $this->html.=($this->title != "") ?  "title=\"$this->title\" " : "";
       $this->html.=($this->class != "") ?  "class=\"$this->class\" " : "";
       $this->html.=($this->width != "") ?  "width=\"$this->width\" " : "";
       $this->html.=($this->height != "") ?  "height=\"$this->height\" " : "";
       $this->addEvents();
       $this->html.=" />";
    }

    public function imprime(){
        echo $this->html;
    }
    public function returnHtml(){
        return $this->html;
    }
}
?>
