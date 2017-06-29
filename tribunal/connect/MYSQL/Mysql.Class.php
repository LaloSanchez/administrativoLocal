<?php

include_once(dirname(__FILE__) . "/../Configuracion.Class.php");
include_once(dirname(__FILE__) . "/../Exception/ExceptionBD.Class.php");
include_once(dirname(__FILE__) . "/../../logger/Logger.Class.php");

class Mysql {

    private $servidor;
    private $usuario;
    private $password;
    private $base_datos;
    private $port;
    private $domain;
    private $link;
    private $stmt;
    private $array;
    private $menssage = "";
    static $_instance;

    public function __construct($name) {
        $this->setConexion($name);
        $this->conectar();
    }

    private function setConexion($name) {
        $conf = new Configuracion(dirname(__FILE__) . "/../Configuracion.xml", "mysql", $name);
        $this->servidor = $conf->getHostDb();
        $this->base_datos = $conf->getDB();
        $this->usuario = $conf->getUserDb();
        $this->password = $conf->getPassDb();
        $this->port = $conf->getPortDb();
        $this->domain = $conf->getDomainDb();
    }

    private function __clone() {
        
    }

    private function conectar() {
        try {
            $this->link = @mysqli_connect(trim($this->servidor), trim($this->usuario), trim($this->password), trim($this->base_datos), trim($this->port));
            if (!mysqli_connect_error($this->link)) {
//                mysqli_select_db($this->link, $this->base_datos);
//                mysqli_query($this->link, "SET NAMES 'utf8'");
            } else {
                throw new ExceptionBD("Conexion Fallida : " . mysqli_connect_error($this->link), mysqli_connect_errno($this->link));
            }
        } catch (ExceptionBD $e) {
            $log = new Logger();
            $text = "\nAnterior en: " . $e->getPrevious() . "\n\n<-(donde ocurrió file:" . $e->getFile() . " line:'" . $e->getLine() . "' )->" . "\n-->Codigo->" . $e->getCode() . " Error -> " . $e->getMessage() . " trace-> " . $e->getTraceAsString() . "\n";
            $log->w_onError($text);
        }
    }

    public function fetch_field($stmt, $fila = 0) {
        if ($fila == 0) {
            $limit = mysqli_num_fields($stmt);
            $count = 0;
            for ($index = 0; $index < $limit; $index++) {
                $this->array[$count] = mysqli_fetch_field_direct($stmt, $index);
                $count++;
            }
        } else {
//            @mysqli_data_seek($stmt, $fila);
            @$this->array = mysqli_fetch_field_direct($stmt, $fila);
        }
        return $this->array;
    }

    public function fetch_chatobject($stmt) {
        return @mysqli_fetch_object($stmt);
    }

    public function fetch_object($stmt, $fila = 0) {
        if ($fila == 0) {
            @$limit = mysqli_num_rows($stmt);
            $count = 0;
            for ($index = 0; $index < $limit; $index++) {
                $this->array[$count] = mysqli_fetch_assoc($stmt);
                $count++;
            }
        } else {
//            @mysqli_data_seek($stmt, $fila);
            @$this->
                    array = mysqli_fetch_field_direct($stmt, $fila);
        }
        return $this->array;
    }

    public function execute($sql) {

        @$this->stmt = mysqli_query($this->link, $sql);

        return $this->stmt;
    }

    public function fetch_array($stmt, $fila) {
        if ($fila == 0) {
            @$this->array = mysqli_fetch_array($stmt);
        } else {
            @mysqli_data_seek($stmt, $fila);

            @$this->array = mysqli_fetch_array($stmt);
        }

        return $this->array;
    }

    public function fetch_rows($stmt, $fila) {
        if ($fila == 0) {
            @$this->array = mysqli_fetch_row($stmt);
        } else {
            @mysqli_data_seek($stmt, $fila);

            @$this->array = mysqli_fetch_row($stmt);
        }

        return $this->
                array;
    }

    public function _error() {
        return @mysqli_error($this->link)

        ;
    }
    public function _affected_rows() {
        return @mysqli_affected_rows($this->link);
    }

    public function _errorNo() {
        return @mysqli_errno($this->link);
    }

    public function _free_result($stmt) {
        return @mysqli_free_result($stmt
        );
    }

    public function _store_result($stmt) {
        return @mysqli_stmt_store_result($this->stmt);
    }	

    public function _rows($stmt) {
        return @

                mysqli_num_rows($stmt);
    }

    public function _close() {
        @mysqli_close($this->link);
    }

    public function lastID() {
        return @mysqli_insert_id($this
                        ->link);
    }

    public function _autocommit($sql) {
        @mysqli_query($sql);
    }

    public function isConnected($connnect) {
        return !is_null($connnect);
    }

    public function beginTransaction() {

        return mysqli_begin_transaction($this->link, MYSQLI_TRANS_START_READ_WRITE);
    }

    public function commit() {
        return mysqli_commit($this->link);
    }

    public function rollback() {
        return mysqli_rollback($this->link);
    }

//    public function _bitacora($CveAccion, $Observaciones) {
//        $error = false;
//        $Fecha = $this->_fecha();
//
//        if (is_null($Fecha))
//            $error = true;
//
//        if ($error == false) {
//            $sql = "Insert into tblBitacora(CveUsuario, CveAccion, Observaciones, Fecha, Hora) 
//	    values(" . $_SESSION["CveUsuarioSistema"] . ", $CveAccion, '$Observaciones', '$Fecha', '$Fecha')";
//            $stmt = $this->execute($sql);
//            if ($this->_error()) {
//                $error = true;
//            }
//        }
//
//        return $error;
//    }
//
//    public function _fecha() {
//        $error = false;
//        $sql = "SELECT NOW() AS FECHA";
//        $stmt = $this->execute($sql);
//
//        if (!$this->_error()) {
//            if ($this->_rows($stmt) > 0)
//                $result = $this->fetch_array($stmt, 0);
//        }
//        else
//            $error = true;
//        return $result["FECHA"];
//    }
}
