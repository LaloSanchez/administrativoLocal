<?php

class Google_Maps{

	var $code='';  
	var $zoom=14; 
	var $center_lat='19.292481483265664'; 
	var $center_lng='-99.65799951642452'; 
	var $divID='map'; 
	var $marker=array();  
	var $instance=1;
	
	function __construct() {
		echo '<script type="text/javascript" src="http://maps.google.com/maps/api/js?key=AIzaSyDia0WQeXw249IMPzDLd66Jt_VAiit5qVw&sensor=true"></script>';
	}
	
	private function start(){
		
		$this->code='
		<script type="text/javascript">
      (function() {
        window.onload = function(){
          var latlng = new google.maps.LatLng('.$this->center_lat.', '.$this->center_lng.');    
          var options = {  
          	zoom: '.$this->zoom.',    
          	center: latlng,
          	mapTypeId: google.maps.MapTypeId.ROADMAP
          };  
            
          var map = new google.maps.Map(document.getElementById("'.$this->divID.'"), options); ';
		   
          
		  for($i=0;$i<count($this->marker);$i++){
		  
			 $this->code.=' var marker'.$i.' = new google.maps.Marker({
				position: new google.maps.LatLng('.$this->marker[$i]['lat'].', '.$this->marker[$i]['lng'].'), 
				map: '.$this->marker[$i]['map'].',
				title: "'.$this->marker[$i]['title'].'",
				clickable: '.$this->marker[$i]['click'].',
				icon: "'.$this->marker[$i]['icon'].'",
                                draggable: '.$this->marker[$i]['draggable'].'    
			  });';
		  
		  
			if($this->marker[$i]['info']!=''){
				$this->code.=' var infowindow'.$i.' = new google.maps.InfoWindow({content: "'.$this->marker[$i]['info'].'"}); ';
	   			$this->code.=" google.maps.event.addListener(marker".$i.", 'click', function() { infowindow".$i.".open(map, marker".$i."); });"; 
			}
	}
    
	
	$this->code.='	}
      })();
		</script>';
		
	}

	
	
	public function addMarker($lat='19.292481483265664',$lng='-99.65799951642452',$click='false',$title='Ubicacion diligencia',$info='Punto Inicial',$icon='',$map='map',$draggable='false'){
		$count=count($this->marker);	
		$this->marker[$count]['lat']=$lat;
		$this->marker[$count]['lng']=$lng;
		$this->marker[$count]['map']=$map;
		$this->marker[$count]['title']=$title;
		$this->marker[$count]['click']=$click;
		$this->marker[$count]['icon']=$icon;
		$this->marker[$count]['info']=$info;
                $this->marker[$count]['draggable']=$draggable;
	}
	
	
	public function showmap(){
		$this->start();
		$this->instance++;
		return $this->code;
	}
	
}


?>
