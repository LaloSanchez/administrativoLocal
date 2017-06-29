<?php

class ReferenciasAlfa {

    private $usuarios = array("M", "0", "Z", "J", "L", "4", "H", "C", "V", "A");
    private $importes = array("0", "5", "R", "3", "8", "9", "S", "3", "2", "A", "1");
    private $transaccion = array("P", "U", "R", "W", "O", "N", "G", "F", "Z", "K");

    public function __construct() {
        
    }

    public function getReferencia($cveUsuario = "", $accion = 0) {
        $referencia = "";
        $tmp = $cveUsuario;
        if ($accion == 1)  $cveUsuario = str_pad(trim($cveUsuario), 5, '0', STR_PAD_RIGHT);
        else if ($accion == 2) $cveUsuario = str_pad(trim($cveUsuario), 13, '0', STR_PAD_RIGHT);
        else if ($accion == 3) {
            $longitud = strlen(trim($cveUsuario));
            switch ((int) $longitud) {
                case 1: $cveUsuario .= "03758126"; break;
                case 2: $cveUsuario .= "5832631";  break;
                case 3: $cveUsuario .= "143345";   break;
                case 4: $cveUsuario .= "85548";    break;
                case 5: $cveUsuario .= "5337";     break;
                case 6: $cveUsuario .= "801";      break;
                case 7: $cveUsuario .= "93";       break;
                case 8: $cveUsuario .= "5";        break;
            }
        }

        $longitud = strlen(trim($cveUsuario));
        $index = 0;
        while ($index < $longitud) {
            if (substr(trim($cveUsuario), $index, 1) != ".") 
                $valor = (int) substr(trim($cveUsuario), $index, 1);
            else 
                $valor = 10;
            
            if ($accion == 1) $referencia .= $this->usuarios[$valor];
            if ($accion == 2) $referencia .= $this->importes[$valor];
            if ($accion == 3) $referencia .= $this->transaccion[$valor];

            $index++;
        }
        
        if($accion==3) {
        	 $referencia= substr(trim($referencia), 0, 2). $tmp.substr(trim($referencia), 2, strlen($referencia));
        	}
        
        return $referencia;
    }

}
?>
