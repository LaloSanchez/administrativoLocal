<?php

include_once(dirname(__FILE__) . "/../../../../../aplicacion/configuracion.php");
include_once(dirname(__FILE__) . "/../../../../../tribunal/connect/Proveedor.Class.php");
include_once(dirname(__FILE__) . "/../../../../../controladores/generic/GenericController.Class.php");

if (isset($_POST["frm"])) {
    $tabla = $_POST["frm"];
} else {
    if (isset($_SERVER["HTTP_SOAPACTION"])) {
//    $tabla = $_SERVERT["HTTP_SOAPACTION"];
        $url = explode('/', trim($_SERVER["HTTP_SOAPACTION"]));
        $url = array_filter($url);

        strtolower(array_shift($url));
        strtolower(array_shift($url));
        strtolower(array_shift($url));
        strtolower(array_shift($url));
        strtolower(array_shift($url));
        strtolower(array_shift($url));
        strtolower(array_shift($url));
        $tabla = strtolower(array_shift($url));
        $file = array_shift($url);
    } else {
        $tabla = "";
    }
}
$prefijo = strtoupper(substr($_POST["frm"], 0, strlen(DEFECTO_PREFIJO)));
if ($prefijo == DEFECTO_PREFIJO) {
    $tabla = $tabla;
} else {
    $tabla = DEFECTO_PREFIJO . $tabla;
}

//if ((sizeof($campos) > 0) && ($tabla != "") && ($database != "")) {


class GenericFachada {

    public $c;
    public $t;
    public $metodo;
    public $pm;
    public $usuario;
    public $ap = array("A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T", "U", "V", "W", "X", "Y", "Z", "AA", "AB");

    public function __construct() {
        include(dirname(__FILE__) . "/../../../../../aplicacion/configuracion.php");
        if (isset($_SERVER["HTTP_SOAPACTION"])) {
//    $tabla = $_SERVERT["HTTP_SOAPACTION"];
            $url = explode('/', trim($_SERVER["HTTP_SOAPACTION"]));
            $url = array_filter($url);

            strtolower(array_shift($url));
            strtolower(array_shift($url));
            strtolower(array_shift($url));
            strtolower(array_shift($url));
            strtolower(array_shift($url));
            strtolower(array_shift($url));
            strtolower(array_shift($url));
            $tabla = strtolower(array_shift($url));
            $file = array_shift($url);
        } else {
            $tabla = "";
        }

        $database = DEFECTO_NAME_BD;

        $proveedor = new Proveedor(DEFECTO_GESTOR, DEFECTO_BD);
        $proveedor->connect();
        $campos = array();
        $contador = 0;
        $proveedor->execute("select
c.COLUMN_NAME,c.ORDINAL_POSITION,c.IS_NULLABLE,c.DATA_TYPE,c.CHARACTER_MAXIMUM_LENGTH,c.NUMERIC_PRECISION,c.DATETIME_PRECISION,c.COLUMN_TYPE,c.COLUMN_KEY,c.COLUMN_COMMENT,ca.REFERENCED_TABLE_NAME,ca.REFERENCED_COLUMN_NAME
from
information_schema.COLUMNS c LEFT JOIN information_schema.key_column_usage ca on (ca.COLUMN_NAME=c.COLUMN_NAME And ca.table_schema = '" . $database . "' And ca.referenced_table_name is not null And referenced_table_name<>'" . $tabla . "')
where 
c.table_schema = '" . $database . "'
and c.table_name = '" . $tabla . "'  order by c.ORDINAL_POSITION");
        if (!$proveedor->error()) {
            if ($proveedor->rows($proveedor->stmt) > 0) {
                while ($row = $proveedor->fetch_rows($proveedor->stmt, 0)) {
                    $campos[$contador] = array("field" => $row[0], "position" => $row[1], "nullable" => $row[2], "data_type" => $row[3], "character_max" => $row[4], "numeric_max" => $row[5], "date_max" => $row[6], "column_type" => $row[7], "column_key" => $row[8], "column_comment" => $row[9], "referenced_table_name" => $row[10], "referenced_column_name" => $row[11]);
                    $contador++;
                }
            } else {
                $error = true;
            }
        } else {
            $error = true;
        }

        $this->c = $campos;
        $this->t = "tbl" . $tabla;
        $this->metodo = $tabla;
        $this->pm = $primary;
        $this->usuario = base64_encode($_SERVER["PHP_AUTH_USER"] . ":" . $_SERVER["PHP_AUTH_PW"]);
    }

    public function insertGeneric($array) {

        if ($this->validaUsuario()) {

            $d = array();
            $campos = "";
            foreach ($array as $key => $value) {
                if ((strtoupper($key) == "FECHAREGISTRO") || (strtoupper($key) == "FECHAACTUALIZACION")) {
                    $campos.=$key . ",";
                    $d["values"][$key] = "now()";
                } else if ((strtoupper($key) == "ACTIVO")) {
                    $campos.=$key . ",";
                    $d["values"][$key] = "S";
                } else {
                    if (($value != "") && ($value != null)) {
                        $campos.=$key . ",";
                        $d["values"][$key] = $this->esFecha($value);
                    } else {
                        for ($index = 0; $index < sizeof($this->c); $index++) {
                            if ($this->c[$index]["field"] == $key) {
                                if ($this->c[$index]["column_key"] != "PRI") {
                                    $campos.=$key . ",";
                                    $d["values"][$key] = $this->esFecha($value);
                                }
                                break;
                            }
                        }
                    }
                }
            }
            $sql = array();
            $param = array("tabla" => "" . $this->t . "", "d" => $d, "tmpSql" => $sql, "proveedor" => null);

            $genericController = new GenericController();
            $array = $genericController->insertGeneric($param);
            if ($array != "") {
                return json_encode($array);
            }
            return json_encode(array("status" => "error", "totalCount" => 0, "msg" => "Ocurrio un error al guardar el registro"));
        } else {
            return json_encode(array("status" => "error", "totalCount" => 0, "msg" => "No tiene permiso para utilizar este recurso"));
        }
    }

    public function updateGeneric($array) {
        if ($this->validaUsuario()) {
            $d = array();
            foreach ($array as $key => $value) {
                if ((strtoupper($key) == "FECHAACTUALIZACION")) {
                    $d["values"][$key] = "now()";
                } else if (strtoupper($key) != "FECHAREGISTRO") {
//                    if (($value != "") && ($value != null)) {
//                        $d["values"][$key] = $value;
//                    } else {
                    for ($index = 0; $index < sizeof($this->c); $index++) {
                        if ($this->c[$index]["field"] == $key) {
                            if ($this->c[$index]["column_key"] != "PRI") {
                                if (($value != "") && ($value != null)) {
                                    $d["values"][$key] = $this->esFecha($value);
                                }
                            }
                            break;
                        }
                    }
//                    }
                }
            }
            $d = array_merge($d, array("where" => array($this->pm => $array[$this->pm])));
            $sql = array();

            $param = array("tabla" => "" . $this->t . "", "d" => $d, "tmpSql" => $sql, "proveedor" => null);

            $genericController = new GenericController();
            $array = $genericController->updateGeneric($param);
            if ($array != "") {
                return json_encode($array);
            }
            return json_encode(array("status" => "error", "totalCount" => 0, "msg" => "Ocurrio un error al actualizar el registro"));
        } else {
            return json_encode(array("status" => "error", "totalCount" => 0, "msg" => "No tiene permiso para utilizar este recurso"));
        }
    }

    public function selectGeneric($array, $limit = null, $draw = null, $o = null, $s = null) {//
        if ($this->validaUsuario()) {
            $campos = "";
            $like = "";
            $where = array();
            $contador = 1;
            $join = "";
            $orderBy = "";
            $search = "";
            $array = json_decode($array, true);
            if (is_array($array)) {
                foreach ($array as $key => $value) {

                    if (($value != "") && ($value != null)) {
                        $where["where"][$key] = $value;
                    }

                    for ($index = 0; $index < sizeof($this->c); $index++) {
                        if ($o != null) {
                            if ($o["column"] == $index) {
                                $orderBy = $this->ap[0] . "." . $this->c[$index]["field"] . " " . $o["dir"];
                            }
                        }

                        if ($this->c[$index]["field"] == $key) {
                            if (($this->c[$index]["referenced_table_name"] != "") && ($o != null) && ($draw != null) && ($limit != null) && ($s != null)) {
                                $join .= " INNER JOIN " . $this->c[$index]["referenced_table_name"] . " as " . $this->ap[$contador] . " on (";
                                $join .= $this->ap[$contador] . "." . $this->c[$index]["referenced_column_name"] . " = " . $this->ap[0] . "." . $key . " ) ";

                                $param = explode("|", $this->c[$index]["column_comment"]);

                                if (isset($param[2])) {
                                    $concat = "concat_ws(' ',";
                                    $camposShow = explode(",", $param[2]);
                                    for ($x = 0; $x < sizeof($camposShow); $x++) {
                                        $concat.=$this->ap[$contador] . "." . $camposShow[$x] . ",";
                                        $like.=$this->ap[$contador] . "." . $camposShow[$x] . ",";
                                    }
                                    $concat = substr($concat, 0, -1) . ")";
                                    $campos.=$concat . " as " . $key . ",";
                                } else {
                                    $campos.=$this->ap[$contador] . "." . $key . ",";
                                }
                                $contador++;
                            } else {
                                $campos.=$this->ap[0] . "." . $key . ",";
                            }
                            break;
                        }
                    }
                    $like.=$this->ap[0] . "." . $key . ",";
                }
                $campos = substr($campos, 0, -1);
                $like = substr($like, 0, -1);

                if ($s != null) {
                    if ($s["value"] != "") {
                        $search = " concat_ws(' '," . $like . ") like '%" . $s["value"] . "%' ";
                    }
                }

                $d = array("campos" => $campos, "limit" => $limit, "order" => $orderBy, "search" => $search);

                $d = array_merge($d, $where);
                $sql = array();

                $param = array("tabla" => "" . $this->t . ( ($join != "") ? " as " . $this->ap[0] . " " . $join : ""), "d" => $d, "tmpSql" => $sql, "proveedor" => null);

                $genericController = new GenericController();
                $array = $genericController->selectGeneric($param);

                if (($array != "") && (sizeof($array) > 0)) {

                    if (($s != null) && ($o != null) && ($limit != null) && ($draw != null)) {
                        $d = array("campos" => "");
                        $d = array_merge($d, array());
                        $sql = array("campos" => "count(*) as Total", "values" => "", "tablas" => $this->t, "where" => "", "groups" => "", "orders" => "");

                        $param = array("tabla" => "" . $this->t . "", "d" => $d, "tmpSql" => $sql, "proveedor" => null);
                        $arrayTot = $genericController->selectGeneric($param);

                        $data = array();
                        for ($index = 0; $index < sizeof(@$array["data"]); $index++) {
                            $registro = array();
                            foreach ($array["data"][$index] as $key => $value) {
                                $registro[] = $value;
                            }
                            $data[] = $registro;
                        }

                        $output = array(
                            "draw" => $draw,
                            "recordsTotal" => (int) $array["totalCount"],
                            "recordsFiltered" => (int) $arrayTot["data"][0]["Total"], //,
                            "start" => $limit["pag"],
                            "length" => $limit["max"],
                            "data" => $data);

                        return json_encode($output);
                    } else {
                        return json_encode($array);
                    }
                }
            } else {
                return json_encode(array("status" => "error", "totalCount" => 0, "msg" => "La estructura del json no es correcta json " . json_encode($array) . " json " . $array["cveEstado"]));
            }

            return json_encode(array("status" => "error", "totalCount" => 0, "msg" => "sin informacion a mostrar"));
        } else {
            return json_encode(array("status" => "error", "totalCount" => 0, "msg" => "No tiene permiso para utilizar este recurso ".$this->t ));
        }
    }

    public function deleteGeneric($array) {
        if ($this->validaUsuario()) {
            $d = array();

            foreach ($array as $key => $value) {
                if ((strtoupper($key) == "FECHAACTUALIZACION")) {
                    $d["values"][$key] = "now()";
                } else if (strtoupper($key) == "ACTIVO") {
                    $d["values"][$key] = "N";
                }
            }

            $d = array_merge($d, array("where" => array($this->pm => $array[$this->pm])));
            $sql = array();

            $param = array("tabla" => "" . $this->t . "", "d" => $d, "tmpSql" => $sql, "proveedor" => null);
            $genericController = new GenericController();
            if (isset($d["values"]) && (@sizeof($d["values"]) > 0)) {
                $array = $genericController->deleteGeneric($param);
            } else {
                $array = $genericController->deleteGenericFisico($param);
            }
            if ($array != "") {
                return json_encode($array);
            }
            return json_encode(array("status" => "error", "totalCount" => 0, "msg" => "El registro no se logro dar de baja"));
        } else {
            return json_encode(array("status" => "error", "totalCount" => 0, "msg" => "No tiene permiso para utilizar este recurso"));
        }
    }

    public function date($value) {
        $patron = "/^(0[1-9]|1[0-9]|2[0-9]|3[0-1])\/(0[1-9]|1[0-2])\/\d{4}$/";

        return preg_match($patron, (string) $value);
    }

    public function dateTime($value) {
        $patron = "/^(0[1-9]|1[0-9]|2[0-9]|3[0-1])\/(0[1-9]|1[0-2])\/\d{4}\ (0[0-9]|1[0-9]|2[0-4])\:(0[0-9]|1[0-9]|2[0-9]|3[0-9]|4[0-9]|5[0-9])\:(0[0-9]|1[0-9]|2[0-9]|3[0-9]|4[0-9]|5[0-9])$/";

        return preg_match($patron, (string) $value);
    }

    public function esFecha($text) {
        if ($this->date($text)) {
            $fecha = explode("/", $text);

            return $fecha[2] . "-" . $fecha[1] . "-" . $fecha[0];
        } else if ($this->dateTime($text)) {
            $fechaHora = explode(" ", $text);
            $fecha = explode("/", $fechaHora[0]);

            return $fecha[2] . "-" . $fecha[1] . "-" . $fecha[0] . " " . $fechaHora[1];
        }
        return $text;
    }

    private function validaUsuario() {

//        $url = explode('/', trim($_GET['url']));
//        $url = array_filter($url);
//        $metodo = strtolower(array_shift($url));

        if (file_exists(dirname(__FILE__)."/../../../us/" . $this->usuario)) {
            $fp = fopen(dirname(__FILE__)."/../../../us/" . $this->usuario, "r");
            $acceso = false;
            while (!feof($fp)) {
                $linea = fgets($fp);
                if (strtolower(trim($linea)) == $this->metodo) {
                    $acceso = true;
                    break;
                }
            }
            fclose($fp);
            return $acceso;
        } else {
            return false;
        }
    }

    public function generaWsdl($metodo) {
        $ruta = ucwords($metodo) . "ServerScramble.wsdl";
        $wsdl = "<?xml version ='1.0' encoding ='utf-8' ?>
              <definitions name='" . ucwords($metodo) . "Server'
              targetNamespace='http://localhost/codebase/webservice/servidor/soap/servicio/$metodo/'
              SOAP-ENV:encodingStyle='http://schemas.xmlsoap.org/soap/encoding/'
              xmlns:SOAP-ENC='http://schemas.xmlsoap.org/soap/encoding/'
              xmlns:SOAP-ENV='http://schemas.xmlsoap.org/soap/envelope/'
              xmlns:tns='http://localhost/codebase/webservice/servidor/soap/servicio/$metodo/'
              xmlns:soap='http://schemas.xmlsoap.org/wsdl/soap/'
              xmlns='http://schemas.xmlsoap.org/wsdl/'>\n

              <SOAP-ENV:Header>\n
              <h:BasicAuth xmlns:h='http://soap-authentication.org/basic/2001/10/'
              SOAP-ENV:mustUnderstand='1'>
              <part name='usuario' type='xsd:string'/>
              <part name='password' type='xsd:string'/>
              </h:BasicAuth>
              </SOAP-ENV:Header>\n";



        $wsdl.="<message name='selectGenericRequest'>
               <part name='array' type='xsd:Array'/> 
              </message>";

        $wsdl.="<message name='selectGenericResponse'>
              <part name='Resultado' type='xsd:string'/>
              </message>";
        $wsdl.="<message name='insertGenericRequest'>
                <part name='array' type='xsd:Array'/> 
              </message>";

        $wsdl.="<message name='insertGenericResponse'>
              <part name='Resultado' type='xsd:string'/>
              </message>";
        $wsdl.="<message name='updateGenericRequest'>
                <part name='array' type='xsd:Array'/> 
              </message>";

        $wsdl.="<message name='updateGenericResponse'>
              <part name='Resultado' type='xsd:string'/>
              </message>";
        $wsdl.="<message name='deleteGenericRequest'>
                <part name='array' type='xsd:Array'/> 
              </message>";

        $wsdl.="<message name='deleteGenericResponse'>
              <part name='Resultado' type='xsd:string'/>
              </message>";

        $wsdl.="<portType name='ScramblePortType'>
              <operation name='selectGeneric'>
              <input message='tns:selectGenericRequest'/>
              <output message='tns:selectGenericResponse'/>
              </operation>
              <operation name='updateGeneric'>
              <input message='tns:updateGenericRequest'/>
              <output message='tns:updateGenericResponse'/>
              </operation>
              <operation name='deleteGeneric'>
              <input message='tns:deleteGenericRequest'/>
              <output message='tns:deleteGenericResponse'/>
              </operation>
              <operation name='insertGeneric'>
              <input message='tns:insertGenericRequest'/>
              <output message='tns:insertGenericResponse'/>
              </operation>
              </portType>";

        $wsdl.="<binding name='ScrambleBinding' type='tns:ScramblePortType'>
              <soap:binding style='rpc'
              transport='http://schemas.xmlsoap.org/soap/http'/>";
        /*
         * Aqui van todas las operaciones
         */

        $wsdl.="<operation name='selectGeneric'>
              <soap:operation soapAction='urn:localhost-scramble#selectGeneric'/>
              <input>
              <soap:body use='encoded' namespace='urn:localhost-scramble'
              encodingStyle='http://schemas.xmlsoap.org/soap/encoding/'/>
              </input>
              <output>
              <soap:body use='encoded' namespace='urn:localhost-scramble'
              encodingStyle='http://schemas.xmlsoap.org/soap/encoding/'/>
              </output>
              </operation>
              <operation name='deleteGeneric'>
              <soap:operation soapAction='urn:localhost-scramble#deleteGeneric'/>
              <input>
              <soap:body use='encoded' namespace='urn:localhost-scramble'
              encodingStyle='http://schemas.xmlsoap.org/soap/encoding/'/>
              </input>
              <output>
              <soap:body use='encoded' namespace='urn:localhost-scramble'
              encodingStyle='http://schemas.xmlsoap.org/soap/encoding/'/>
              </output>
              </operation>
              <operation name='updateGeneric'>
              <soap:operation soapAction='urn:localhost-scramble#updateGeneric'/>
              <input>
              <soap:body use='encoded' namespace='urn:localhost-scramble'
              encodingStyle='http://schemas.xmlsoap.org/soap/encoding/'/>
              </input>
              <output>
              <soap:body use='encoded' namespace='urn:localhost-scramble'
              encodingStyle='http://schemas.xmlsoap.org/soap/encoding/'/>
              </output>
              </operation>
              <operation name='insertGeneric'>
              <soap:operation soapAction='urn:localhost-scramble#insertGeneric'/>
              <input>
              <soap:body use='encoded' namespace='urn:localhost-scramble'
              encodingStyle='http://schemas.xmlsoap.org/soap/encoding/'/>
              </input>
              <output>
              <soap:body use='encoded' namespace='urn:localhost-scramble'
              encodingStyle='http://schemas.xmlsoap.org/soap/encoding/'/>
              </output>
              </operation>
              ";

        $wsdl.="</binding>";

        $wsdl.="<service name='ScrambleService'> 
        <port name='ScramblePort' binding='ScrambleBinding'> 
            <soap:address location='http://localhost/codebase/webservice/servidor/soap/servicio/$metodo/" . ucwords($metodo) . "Server.php'/> 
        </port> 
    </service>
</definitions>";




        $file = fopen(dirname(__FILE__) . "/../../servicio/wsdl/" . $ruta, "w");
        fwrite($file, $wsdl);
        fclose($file);
        return dirname(__FILE__) . "/../../servicio/wsdl/" . $ruta;
    }

    public function generaServicio($metodo, $wsdlScamble) {
        $file = fopen(dirname(__FILE__) . "/../" . $metodo . "/" . ucwords($metodo) . "Server.php", "w");

        $wsdl = "<?php\n\n";
        $wsdl.="\n";
        $wsdl.="include_once(dirname(__FILE__) . \"/../generic/GenericFachada.Class.php\");\n";
        $wsdl.="ini_set(\"soap.wsdl_cache_enabled\", \"0\");\n";
        $wsdl.="\$server = new SoapServer(\"" . $wsdlScamble . "\");\n";
        $wsdl.="\$server->setClass(\"GenericFachada\");\n";
        $wsdl.="\$server->handle();\n";
        $wsdl.="\n";
        $wsdl.="?>";

        fwrite($file, $wsdl);
        fclose($file);
    }

    public function creaDirectorio($NomDirectorio) {
        $VectorDirectorio = preg_split("[" . DS . "]", $NomDirectorio);

        $ruta = "";
        foreach ($VectorDirectorio as $Carpeta) {
            if ($Carpeta != "." && trim($Carpeta) != "" && $Carpeta != "..") {
                if (DEFECTO_SO == "windows") {
                    if ($ruta == "") {
                        $ruta = $Carpeta;
                    } else {
                        $ruta = $ruta . "" . DS . "" . $Carpeta;
                    }
                } else {
                    $ruta = $ruta . "/" . $Carpeta;
                }

                if (!self::existeDirectorio($ruta)) {
                    mkdir($ruta, 0777);
                }
            }
        }
    }

    private function existeDirectorio($NomDirectorio) {
        if (is_dir($NomDirectorio) == true)
            return true;

        return false;
    }

    private function funcionExiste($function) {
        if (function_exists($function))
            return true;
        else
            return false;
    }

}

//} else {
//    echo json_encode(array("status" => "error", "totalCount" => 0, "msg" => "Upps algo salio malll "));
//}
?>