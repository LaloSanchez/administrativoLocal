<?php
class Label {
    private $class;
    private $title;
    private $id;
    private $events;
    private $html;
    private $text;

    public function __construct($param) {
        $this->param($param);
        $this->generaLabel();
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
            if (($tag == "text") && ($value != ""))
                $this->_text($value);
        }
    }

    private function _text($text) {
        $this->text = $text;
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

    private function generaLabel() {
        $this->html = "<label ";
        $this->html.= ( $this->class != "") ? "class='" . $this->class . "' " : "";
        $this->html.= ( $this->id != "") ? "id='" . $this->id . "' " : "";
        $this->html.= ( $this->title != "") ? "title='" . $this->class . "' " : "";
        $this->addEvents();
        $this->html.=">";
        $this->html.=$this->text;
        $this->html.="</label>";
    }

    private function imprime() {
        echo $this->html;
    }

    public function returnHtml(){
      return $this->html;
    }
}
?>