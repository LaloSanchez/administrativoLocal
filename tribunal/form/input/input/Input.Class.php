<?php
interface Input{

 public function param($param);
 
 public function _value($value);
 public function _type($type);
 public function _name($name);
 public function _class($class);
 public function _enamble($enamble);
 public function _id($id);
 public function _title($title);
 
 public function _events($events);
 public function generaTagHtml();
 public function addEvents();
}
?>