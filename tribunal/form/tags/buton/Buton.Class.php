<?php
class Buton {

    private $html;
    private $class="button";
    private $id;
    private $events;
    private $title;
    private $value;

    public function  __construct($param) {
        $this->param($param);
        $this->generaTagHtml();
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
            if (($tag == "value") && ($value != ""))
                $this->_value($value);
        }
    }
    private function _class($class) {
        $this->class=$class;
    }
    private function _title($title) {
        $this->title=$title;
    }
    private function _id($id) {
        $this->id=$id;
    }
    private function _value($value) {
        $this->value=$value;
    }
    private function _events($events) {
        $this->events=$events;
    }

    private function addEvents() {
        if (is_array($this->events)) {
            foreach ($this->events as $tag => $value) {
                $this->html.=" " . $tag . "=\"$value\"";
            }
        }
    }

    private function generaTagHtml() {
        $this->html = "<a href='#noir' ";
        $this->html.= ( $this->class != "") ? "class=\"$this->class\" " : "";
        $this->html.= ( $this->id != "") ? "id='" . $this->id . "' " : "";
        $this->html.= ( $this->title != "") ? "title='" . $this->title . "' " : "";
        $this->addEvents();
        $this->html.=" onMouseUp='this.blur();'>";
        $this->html.="<span>";
        $this->html.=$this->value;
        $this->html.="</span>";
        $this->html.="</a>";
    }
    public function imprime() {
        echo $this->html;
    }
    public function returnHtml() {
        return $this->html;
    }
}
?>
