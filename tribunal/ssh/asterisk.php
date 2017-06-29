<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

class asterisk2 {

    public function __construct() {
        
    }

    public function llamar($host, $celular, $path, $name, $context = null) {
//$name ="tmp20160519150020.txt";
        if ($context == null) {
            $context = "outboundmsg1";
        }

        $tmp = fopen($path . $name, "x");
        $data = "Channel: SIP/" . $celular . "@10.22.165.2
MaxRetries: 5
RetryTime: 60
WaitTime: 45
Context: " . trim($context) . "
Extension: s
Priority: 1";
        fwrite($tmp, $data);
        fclose($tmp);
        $tmp = fopen($path . $name, "r");
        $conn_id = ftp_connect($host) or die("No se pudo conectar a 10.22.157.61");
//        if (ftp_login($conn_id, "peritos", "p3r1t0s2016_.")) {
        if (ftp_Login($conn_id, "peritos", "p3r1t0s2016_.")) {

	    ftp_pasv($conn_id, true);	 
            if (ftp_fput($conn_id, $name, $tmp,FTP_ASCII)) {	
                return "Archivo cargado de forma correcta al servidor(" . $host . ") con el nombre " . $name . " ";
            } else {
                return "Ocurrio un problema al cargar el archivo $name al servidor (" . $host . ")\n";
            }
            ftp_close($conn_id);
        } else {
            return "No se pudo autentificar en el servidor(" . $host . ")";
        }
        fclose($tmp);
    }

}
?>

