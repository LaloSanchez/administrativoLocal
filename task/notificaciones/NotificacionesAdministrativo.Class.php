<?php

require_once dirname(__FILE__) . '/vendor/autoload.php';
include_once(dirname(__FILE__) . "/../../tribunal/json/JsonEncod.Class.php");

class NotificacionesAdministrativo {

    private $canal = "";
    private $configuracionVista = "";
    private $configuracionRemitente = "";
    private $redis = null;

    function __construct() {
        Predis\Autoloader::register();
        try {
            $this->redis = new Predis\Client([
                'scheme' => 'tcp',
                'host' => '10.22.157.133',
                'port' => 6379,
                'password' => '6e1b44c3334ce1cb78db450e84b70f99df5c36be946565854b79f20be9d0b67e'
            ]);
        } catch (Exception $e) {
            echo 'Conexion no se puede establecer';
            echo $e->getMessage();
        }
    }

    function getCanal() {
        return $this->canal;
    }

    function getConfiguracionVista() {
        return $this->configuracionVista;
    }

    function getConfiguracionRemitente() {
        return $this->configuracionRemitente;
    }

    /**
     * Canal en el cual se va transmitir el mensaje
     * @param type $canal
     */
    function setCanal($canal) {
        $this->canal = $canal;
    }

    /**
     * Array para la configuracion de la vista 
     * array(
     *  type => "error", //alert | success | error | warning | info
     *  title => "CATEOS PENDIENTES EJEMPLO",
     *  message => msg,
     *  position => array(
     *      x => "right", //right | left | center
     *      y => "top" //top | bottom | center
     * ),
     * icon => '<img src="img/alert.png" />', //<i>
     * size => "normal", //normal | full | small
     * overlay => false, //true | false
     * closeBtn => true, //true | false
     * overflowHide => false, //true | false
     * spacing => 20, //number px
     * theme => "dark-theme", //default | dark-theme
     * autoHide => true, //true | false
     * delay => 25000, //number ms
     * onShow => null, //function
     * onClick => null, //function
     * onHide => null, //function
     * template => '<div class="notify"><div class="notify-text"></div></div>'
     * )
     * @return type
     */
    function setConfiguracionVista($configuracionVista) {
        $this->configuracionVista = $configuracionVista;
    }

    /**
     * Array configuracion remitente
     * 
     * @return type
     */
    function setConfiguracionRemitente($configuracionRemitente) {
        $this->configuracionRemitente = $configuracionRemitente;
    }

    function strReplace($str2) {
        var_dump($str2);
        $str2 = str_replace("á", "\u00e1", $str2);
        $str2 = str_replace("é", "\u00e9", $str2);
        $str2 = str_replace("í", "\u00ed", $str2);
        $str2 = str_replace("ó", "\u00f3", $str2);
        $str2 = str_replace("ú", "\u00fa", $str2);
        $str2 = str_replace("Á", "\u00c1", $str2);
        $str2 = str_replace("É", "\u00c9", $str2);
        $str2 = str_replace("Í", "\u00cd", $str2);
        $str2 = str_replace("Ó", "\u00d3", $str2);
        $str2 = str_replace("Ú", "\u00da", $str2);
        $str2 = str_replace("ñ", "\u00f1", $str2);
        $str2 = str_replace("Ñ", "\u00d1", $str2);

        return $str2;
    }

    /**
     * Metodo para enviar
     */
    function emite() {
//        var_dump($this->redis);
//        var_dump($this->canal);.
//        var_dump("json");
//        var_dump(encodeURIComponent($this->obtenerJSON()));
//        var_dump($this->strReplace($this->obtenerJSON()));
//        var_dump("cadena");
//        var_dump($this->strReplace('{"vista":{"status":"success","totalCount":1,"data":[{"cveNotificacionesGenerales":"210","cveTipoNotificacion":"1","Origen":"885","Destino":"853","descripcionNotificacion":"DIRECCIÓN DE TECNOLOGÍAS DE INFORMACIÓN agrego una nueva evidencia al proyecto programatico ...","tituloNotificacion":"Agrego Evidencia","urlFormulario":"vistas\/planeacion\/frmAdministracionProyectosView.php","visto":null,"activo":"S","fechaRegistro":"2017-03-08 13:11:38","fechaActualizacion":"2017-03-08 13:11:38"}]},"remitente":{"remitente":853,"tipo":"Adscripcion"}}'));
        $this->redis->publish($this->canal, ($this->obtenerJSON()));
    }

    function obtenerJSON() {
        $jsonEncode = new Encode_JSON();
        return ($jsonEncode->encode(array(
                    "vista" => $this->configuracionVista,
                    "remitente" => $this->configuracionRemitente
        )));
    }

}
