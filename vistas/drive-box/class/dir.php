	<?php

         include(dirname(__FILE__) ."/config.php");
         include(dirname(__FILE__) ."/json.php");



       //***********************************************************************************************************************************


       //echo "icon=".getIconApp("exe");

       $filelist=array();

        $n=0;
        $espacio=0;
	// Abre un directorio conocido, y procede a leer el contenido
		if (is_dir(DIR)) {
    			if ($dh = opendir(DIR)) {
        		while (($file = readdir($dh)) !== false) {
            			
            			if (strpos($file,TOKEN)==0 && filetype(DIR.$file)=="file" && (getChat($file)==TOKEN))
            			{
            			   $n=$n+1;
            			   
            			   $item['id']=$n;
            			   $item['filename']=getFilename($file);
            			   $item['filenamereal']=SUBDIR.$file;
            			   
            			   list($fileicon, $modalclass) = split(":",getIconApp(getExt($file)));
            			   $item['fileicon']=$fileicon;
            			   $item['modalclass']=$modalclass;
            			   $item['bytes']=filesize(DIR.$file);
            			   $espacio=$espacio+filesize(DIR.$file);
            			   
            			   $fsize=filesize(DIR.$file);
            			   
            			   if ($fsize>1048576) $item['kbytes']=number_format((filesize(DIR.$file)/1024/1024),1)."M";
            			   else if ($fsize>1024) $item['kbytes']=number_format((filesize(DIR.$file)/1024),1)."K";
            			       else $item['kbytes']= filesize(DIR.$file)."b";
            			   
            			   $item['utime']=filemtime(DIR.$file);
            			   $item['dtime']=date ("Y-m-d", filemtime(DIR.$file));
            			   $item['owner']=getSender($file);
            			   array_push($filelist,$item);            			   
            			}   
        		}
        	closedir($dh);
    		}
	}
	
           $nofiles=$n;	   
        ?>
