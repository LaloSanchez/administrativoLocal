<?php
       session_start();
       
       date_default_timezone_set("America/Mexico_City");

       $dir =  dirname($_SERVER['PHP_SELF']);
        $dirs = explode('/', $dir);
        $path = "/".$dirs[1]; // get first dir

       define('SUBDIR',    $path."/vistas/chat-box/uploads/");
       
       
       
       define('DIR',       $_SERVER['DOCUMENT_ROOT'].SUBDIR);
       
       define('TOKEN',$_SESSION['chatid']); 
       define('USERNAME',$_SESSION['idname']); 
       
       define('AUDIO_MODAL',       ':audio-modal');
       define('FLASH_MODAL',       ':flash-modal');
       define('IMAGE_MODAL',       ':image-modal');
       define('PDF_MODAL',         ':pdf-modal');
       define('QUICKTIME_MODAL',   ':quicktime-modal');
       define('SOURCE_MODAL-ALT',  ':source-modal-alt');
       define('SOURCE_MODAL',      ':source-modal');
       define('TEXT_MODAL-ALT',    ':text-modal-alt');
       define('VIDEO_MODAL',       ':video-modal');
       define('TEXT_MODAL',        ':text-modal');
       define('WEBSITE_MODAL',     ':website-modal');
       define('VIRTUAL_MODAL',     ':virtual-modal');
       define('DEFAULT_MODAL',     ':text-modal');
       define('JSON_DIR',  $_SERVER['DOCUMENT_ROOT'].$path."/vistas/drive-box/class/json/");
       
?>
