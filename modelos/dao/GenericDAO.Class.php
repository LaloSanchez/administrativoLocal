<?php

include_once(dirname(__FILE__) . "/../../tribunal/connect/Proveedor.Class.php");
include_once(dirname(__FILE__) . "/../../tribunal/logger/Logger.Class.php");
include_once(dirname(__FILE__) . "/../../tribunal/json/JsonEncod.Class.php");
include_once(dirname(__FILE__) . "/../../aplicacion/configuracion.php");
include_once(dirname(__FILE__) . "/../../controladores/bitacora/BitacoraController.Class.php");

class GenericDAO {

    private $proveedor;
    private $table;
    private $accionBitacora = '';

    public function __construct() {
        $logger = new Logger("/../../logs/", "GenericDAO");
    }

    public function select($param) {
        try {
            if (isset($param['tabla']) && ($param['tabla'] !== "")) {
                $tabla = $param['tabla'];
            } else {
                if (!isset($param['tmpSql']) || ($param['tmpSql'] === "")) {
                    throw new Exception("No ingresaste la tabla a la cual se realizaria la consulta", "10001");
                } else {
                    $tabla = "";
                }
            }

            if (isset($param['d']) && ($param['d'] !== "")) {
                $d = $param['d'];
            } else {
                if ((!isset($param['tmpSql']) || ($param['tmpSql'] === "")) && (!isset($param['tabla']) || ($param['tabla'] !== ""))) {
                    throw new Exception("No ingresaste las condiciones y los campos a mostrar", "10002");
                } else {
                    $d = "";
                }
            }

            if (isset($param['tmpSql']) && ($param['tmpSql'] !== "")) {
                $tmpSql = $param['tmpSql'];
            } else {
                if ((!isset($param['tabla']) || (@$param['tabla'] === "")) && (!isset($param['d']) || ($param['d'] === ""))) {
                    throw new Exception("No ingresaste un sql valido", "10003");
                } else {
                    $tmpSql = "";
                }
            }

            if (isset($param['proveedor']) && (($param['proveedor'] !== "") || ($param['proveedor'] !== null) )) {
                $this->proveedor = $param['proveedor'];
            } else {
                $this->proveedor = new Proveedor(DEFECTO_GESTOR, DEFECTO_BD);
                $this->proveedor->connect();
            }




            $this->table = $tabla;
            $tmp = "";

            if (((!isset($d["campos"])) || ((string) @$d["campos"] === "")) && ((is_array($tmpSql)) && (sizeof($tmpSql) <= 0) )) {

                $sql = "SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA ";
                $sql .= " = '" . DEFECTO_NAME_BD . "' AND TABLE_NAME = '" . $this->table . "' ";
                $sql .= " Order By ORDINAL_POSITION ASC";

                $logger = new Logger("/../../logs/", "GenericDAO");
                $logger->w_onError("**********COMIENZA DAO GENERICO $tabla**********");
                $logger->w_onError($sql);
                $this->proveedor->execute($sql);
                if (!$this->proveedor->error()) {
                    if ($this->proveedor->rows($this->proveedor->stmt) > 0) {
                        $campos = array();
                        while ($row = $this->proveedor->fetch_array($this->proveedor->stmt, 0)) {
                            $campos[] = array("COLUMN_NAME" => $row["COLUMN_NAME"],
                                "IS_NULLABLE" => $row["IS_NULLABLE"],
                                "DATA_TYPE" => $row["DATA_TYPE"],
                                "COLUMN_KEY" => $row["COLUMN_KEY"],
                                "EXTRA" => $row["EXTRA"],
                                "COLUMN_COMMENT" => $row["COLUMN_COMMENT"]);
                        }

                        $sql = "SELECT ";
                        for ($index = 0; $index < count($campos); $index++) {
                            $sql .= $campos[$index]["COLUMN_NAME"] . " ,";
                        }
                        $sql = substr($sql, 0, -1) . " FROM " . $this->table;
                        if (isset($d["where"])) {
                            $sql .= " WHERE ";
                            foreach ($d["where"] as $key => $value) {
                                $sql .= " " . $key . " ='" . $value . "' And";
                            }
                            $sql = substr($sql, 0, -3);
                        }
                    }
                } else {
                    $logger = new Logger("/../../logs/", "GenericDAO");
                    $logger->w_onError("Error: " . $this->proveedor->error());
                    throw new Exception($this->proveedor->error() . " " . $sql . "     " . json_encode($param), $this->proveedor->errorNo());
                }
            } else if (((isset($d["campos"])) && ((string) $d["campos"] !== "")) && ((is_array($tmpSql)) && (sizeof($tmpSql) <= 0) )) {

                $campos = explode(",", $d["campos"]);

                if (count($campos) > 0) {
                    $c = array();
                    for ($x = 0; $x < count($campos); $x++) {
                        $c[] = array("COLUMN_NAME" => $campos[$x]);
                    }
                    $campos = $c;
                }

                $sql = "SELECT ";
                $sql .= $d["campos"];
                $sql .= " FROM " . $this->table;
                if (isset($d["where"])) {
                    $sql .= " WHERE ";
                    foreach ($d["where"] as $key => $value) {
                        $sql .= " " . $key . " ='" . $value . "' And";
                    }
                    $sql = substr($sql, 0, -3);
                }
            } else if ((is_array($tmpSql)) && (sizeof($tmpSql) >= 0)) {

                $sql = "";
                if ((array_key_exists("campos", $tmpSql)) && ((String) $tmpSql["campos"] !== "")) {
                    $sql .= "SELECT " . $tmpSql["campos"] . " FROM ";
                } else {
                    $sql .= "SELECT * FROM ";
                }

                if ((array_key_exists("tablas", $tmpSql)) && ((String) $tmpSql["tablas"] !== "")) {
                    $sql .= " " . $tmpSql["tablas"] . " ";
                } else {
                    if ((String) $tabla !== "") {
                        $sql .= " " . $tabla . " ";
                    } else {
                        throw new Exception("No ingreso el nombre de la tabla a consultar", "10005");
                    }
                }

                if ((array_key_exists("where", $tmpSql)) && ((String) $tmpSql["where"] !== "")) {
                    $sql .= " WHERE " . $tmpSql["where"] . " ";
                }

                if ((array_key_exists("values", $tmpSql)) && ((String) $tmpSql["values"] !== "")) {
                    $sql .= "";
                    throw new Exception("Esta instruccion no es valida para la instruccion SQL", "10010");
                }

                if ((array_key_exists("groups", $tmpSql)) && ((String) $tmpSql["groups"] !== "")) {
                    $sql .= " GROUP BY " . $tmpSql["groups"] . " ";
                }
                if ((array_key_exists("orders", $tmpSql)) && ((String) $tmpSql["orders"] !== "")) {
                    $sql .= " ORDER BY " . $tmpSql["orders"] . " ";
                }
            } else {
                throw new Exception("No se ingresaron los parametros correctos", "10011");
            }

            if (isset($param["d"]['search']) && ($param["d"]['search'] !== "")) {
                if (isset($d["where"])) {
                    if (sizeof($d["where"]) > 0) {
                        $sql .= " And ";
                    } else {
                        $sql .= " where ";
                    }
                } else {
                    $sql .= " where ";
                }

                $sql .= " " . $param["d"]['search'] . " ";
            }

            if (isset($param["d"]['order']) && ($param["d"]['order'] !== "")) {
                $sql .= " ORDER BY " . $param["d"]['order'];
            }

            if (isset($param["d"]['limit']) && ($param["d"]['limit'] !== "")) {
                $sql .= " ";
                $inicial = 0;
                if ((int) $param["d"]['limit']["pag"] > 0) {
                    $inicial = (ceil($param["d"]['limit']["pag"] / (int) $param["d"]['limit']["max"])) * (int) $param["d"]['limit']["max"];
                } else {
                    $inicial = 0;
                }
                if (isset($param["d"]['limit']["normal"]) && ($param["d"]['limit']["normal"] !== "")) {
                    $sql .= " LIMIT " . $param["d"]['limit']["pag"] . "," . (int) $param["d"]['limit']["max"];
                } else {
                    $sql .= " LIMIT " . $inicial . "," . (int) $param["d"]['limit']["max"];
                }
            }
//            echo $sql."\n\n";
            $logger = new Logger("/../../logs/", "GenericDAO");
            $logger->w_onError($sql);
            if ((boolean) $this->valida($sql) === true) {
                $this->proveedor->execute($sql);
                if (!$this->proveedor->error()) {

                    if ($this->proveedor->rows($this->proveedor->stmt) > 0) {

                        $tmp = array();
                        $contador = 0;

                        $columns = ($this->proveedor->fetch_field($this->proveedor->stmt, 0));

                        while ($row = $this->proveedor->fetch_array($this->proveedor->stmt, 0)) {
                            if ($tmpSql == "") {
                                $record = array();
                                for ($i = 0; $i < count($campos); $i++) {
                                    for ($y = 0; $y < count($columns); $y++) {
                                        if ($campos[$i]["COLUMN_NAME"] == $columns[$y]->name) {
                                            $record[$campos[$i]["COLUMN_NAME"]] = $row[$campos[$i]["COLUMN_NAME"]];
                                            break;
                                        }
                                    }
                                }
                            } else {
                                for ($y = 0; $y < count($columns); $y++) {
                                    $record[$columns[$y]->name] = ($row[$columns[$y]->name]);
                                }
                            }

                            $tmp[$contador] = $record;
                            $contador++;
                        }
                        $tmp = array_merge(array("status" => "success", "totalCount" => sizeof($tmp)), array("data" => $tmp));
                    } else {
                        $tmp = array("status" => "success", "totalCount" => 0, "data" => array());
                    }
                } else {
                    if (isset($param['proveedor']) && (($param['proveedor'] !== "") || ($param['proveedor'] !== null) )) {
                        //Nada va aqui
                    } else {
//                        $this->proveedor->free_result($this->proveedor->stmt);
                        if ($this->proveedor != null)
                            $this->proveedor->close();
                    }

                    throw new Exception($this->proveedor->error() . " " . $sql, $this->proveedor->errorNo());
                }
            } else {
                throw new Exception("La sentencia sql contiene instrucciones no permitidas", "10004");
            }

            if (isset($param['proveedor']) && (($param['proveedor'] !== "") || ($param['proveedor'] !== null) )) {
                //Nada va aqui
            } else {
//                $this->proveedor->free_result($this->proveedor->stmt);
                if ($this->proveedor != null)
                    $this->proveedor->close();
            }
        } catch (Exception $e) {
            $tmp = array("status" => "error", "totalCount" => 0, "msg" => $e->getMessage());
            if (isset($param['proveedor']) && (($param['proveedor'] !== "") || ($param['proveedor'] !== null) )) {
                //Nada va aqui
            } else {
                //$this->$_GET($this->proveedor->stmt);
                if ($this->proveedor != null)
                    $this->proveedor->close();
            }
        }
        return $tmp;
    }

    public function insert($param,$migracion =false, $bandera=false) {
        try {
            if (isset($param['tabla']) && ($param['tabla'] !== "")) {
                $tabla = $param['tabla'];
            } else {
                if (!isset($param['tmpSql']) || ($param['tmpSql'] === "")) {
                    throw new Exception("No ingresaste la tabla a la cual se realizaria la accion", "10001");
                } else {
                    $tabla = "";
                }
            }
            if (isset($param['accionBitacora']) && ($param['accionBitacora'] !== "")) {
                $this->accionBitacora = $param['accionBitacora'];
            }

            if (isset($param['d']) && ($param['d'] !== "")) {
                $d = $param['d'];
            } else {
                if ((!isset($param['tmpSql']) || ($param['tmpSql'] === "")) && (!isset($param['tabla']) || ($param['tabla'] !== ""))) {
                    throw new Exception("No ingresaste las condiciones y los campos a mostrar", "10002");
                } else {
                    $d = "";
                }
            }

            if (isset($param['tmpSql']) && ($param['tmpSql'] !== "")) {
                $tmpSql = $param['tmpSql'];
            } else {
                if ((!isset($param['tabla']) || (@$param['tabla'] === "")) && (!isset($param['d']) || ($param['d'] === ""))) {
                    throw new Exception("No ingresaste un sql valido", "10003");
                } else {
                    $tmpSql = "";
                }
            }

            if (isset($param['proveedor']) && (($param['proveedor'] !== "") || ($param['proveedor'] !== null) )) {
                $this->proveedor = $param['proveedor'];
            } else {
                $this->proveedor = new Proveedor(DEFECTO_GESTOR, DEFECTO_BD);
                $this->proveedor->connect();
            }

            $this->table = $tabla;
            $tmp = "";

            if (isset($d["values"])) {
                $sql = "SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA ";
                $sql .= " = '" . DEFECTO_NAME_BD . "' AND TABLE_NAME = '" . $this->table . "' ";
                $sql .= " Order By ORDINAL_POSITION ASC";

                $logger = new Logger("/../../logs/", "GenericDAO");
                $logger->w_onError($sql);
               // echo ($sql);
                $this->proveedor->execute($sql);
                if (!$this->proveedor->error()) {
                    if ($this->proveedor->rows($this->proveedor->stmt) > 0) {
                        $campos = array();

                        while ($row = $this->proveedor->fetch_array($this->proveedor->stmt, 0)) {
                            $campos[] = array("COLUMN_NAME" => $row["COLUMN_NAME"],
                                "IS_NULLABLE" => $row["IS_NULLABLE"],
                                "COLUMN_KEY" => $row["COLUMN_KEY"],
                                "DATA_TYPE" => $row["DATA_TYPE"],
                                "EXTRA" => $row["EXTRA"],
                                "COLUMN_COMMENT" => $row["COLUMN_COMMENT"]);
                        }
//                        var_dump($campos);
                        $sql = "INSERT INTO " . $this->table . "(";
                        for ($index = 0; $index < count($campos); $index++) {
                            if($migracion){
                                foreach ($d["values"] as $key => $value) {
                                    if ($campos[$index]["COLUMN_NAME"] == $key) {
                                        if ($value !== "") {//No guarda cuando el valor el 0!!!!!!!!!!!!!!!!!!!
                                            $sql .= $campos[$index]["COLUMN_NAME"] . " ,";
                                        }
                                        break;
                                    }
                                }
                            }else{
                                if (strtoupper($campos[$index]["EXTRA"]) != "AUTO_INCREMENT") { //aqui meter validacion
                                    foreach ($d["values"] as $key => $value) {
                                        if ($campos[$index]["COLUMN_NAME"] == $key) {
                                            if ($value !== "") {//No guarda cuando el valor el 0!!!!!!!!!!!!!!!!!!!
                                                $sql .= $campos[$index]["COLUMN_NAME"] . " ,";
                                            }
                                            break;
                                        }
                                    }
                                }
                            }
                        }
                        $sql = substr($sql, 0, -1) . ") VALUES (";

                        if (isset($d["values"])) {
                            for ($index = 0; $index < count($campos); $index++) {
                                foreach ($d["values"] as $key => $value) {
                                    if ($campos[$index]["COLUMN_NAME"] == $key) {

                                        if ($value != "now()" && $value != "YEAR(now())" && $value != "null" && $value != "date(now())") {

                                            $sql .= "'" . addslashes($value) . "'" . ",";
                                        } else {
                                            $sql .= " " . $value . ",";
                                        }
                                        break;
                                    }
                                }
                            }
                            $sql = substr($sql, 0, -1);
                            $sql .= ")";
                        }
                    }
                } else {
                    $logger = new Logger("/../../logs/", "GenericDAO");
                    $logger->w_onError("Error: " . $this->proveedor->error());
                    throw new Exception($this->proveedor->error(), $this->proveedor->errorNo());
                }
            } else if ((is_array($tmpSql)) && (sizeof($tmpSql) > 0)) {

                $sql = "SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA ";
                $sql .= " = '" . DEFECTO_NAME_BD . "' AND TABLE_NAME = '" . $this->table . "' ";
                $sql .= " Order By ORDINAL_POSITION ASC";

//                echo $sql;
                $logger = new Logger("/../../logs/", "GenericDAO");
                $logger->w_onError($sql);
                $this->proveedor->execute($sql);

                if (!$this->proveedor->error()) {
                    if ($this->proveedor->rows($this->proveedor->stmt) > 0) {
                        $campos = array();
                        while ($row = $this->proveedor->fetch_array($this->proveedor->stmt, 0)) {
                            $campos[] = array("COLUMN_NAME" => $row["COLUMN_NAME"],
                                "IS_NULLABLE" => $row["IS_NULLABLE"],
                                "COLUMN_KEY" => $row["COLUMN_KEY"],
                                "DATA_TYPE" => $row["DATA_TYPE"],
                                "EXTRA" => $row["EXTRA"],
                                "COLUMN_COMMENT" => $row["COLUMN_COMMENT"]);
                        }
                    }
                }



                $sql = "";
                if ((array_key_exists("tablas", $tmpSql)) && ((String) $tmpSql["tablas"] !== "")) {
                    $sql .= " INSERT INTO " . $tmpSql["tablas"] . " ";
                } else {
                    if ((String) $tabla !== "") {
                        $sql .= " INSERT INTO " . $tabla . " ";
                    } else {
                        throw new Exception("No ingreso el nombre de la tabla a consultar para realizar la accion", "10005");
                    }
                }


                if ((array_key_exists("campos", $tmpSql)) && ((String) $tmpSql["campos"] !== "")) {
                    $sql .= " (" . $tmpSql["campos"] . " )";
                } else {
                    $sql .= " ";
                }

                if ((array_key_exists("where", $tmpSql)) && ((String) $tmpSql["where"] !== "")) {
                    $sql .= "";
                    throw new Exception("Esta instruccion no es valida para la instruccion de insert", "10010");
                }

                if ((array_key_exists("values", $tmpSql)) && ((String) $tmpSql["values"] !== "")) {
                    $sql .= " VALUES ( " . $tmpSql["values"] . " ) ";
                }

                if ((array_key_exists("groups", $tmpSql)) && ((String) $tmpSql["groups"] !== "")) {
                    $sql .= "";
                    throw new Exception("Esta instruccion no es valida para la instruccion SQL", "10010");
                }

                if ((array_key_exists("orders", $tmpSql)) && ((String) $tmpSql["orders"] !== "")) {
                    $sql .= "";
                    throw new Exception("Esta instruccion no es valida para la instruccion SQL", "10010");
                }
            } else {
                throw new Exception("No se ingresaron los parametros correctos", "10011");
            }
            if($bandera){
                var_dump($sql);
            }
            $logger = new Logger("/../../logs/", "GenericDAO");
            $logger->w_onError($sql);
            $this->proveedor->execute($sql);

            if (!$this->proveedor->error()) {

                if ($this->proveedor->_affected_rows() > 0) {
                    for ($index = 0; $index < count($campos); $index++) {
                        if (strtoupper($campos[$index]["COLUMN_KEY"]) == "PRI") {
                            $d = array("where" => array($campos[$index]["COLUMN_NAME"] => $this->proveedor->lastID()));
                            break;
                        }
                    }
                    $paramTmp = array("tabla" => $this->table, "d" => $d, "tmpSql" => array(), "proveedor" => $this->proveedor);

                    $tmp = $this->select($paramTmp); //$this->table, $d
                    // guardar bitacora del registro insertado
                    if ($this->accionBitacora!='') {
                        $bitacoraJsonEncode = new Encode_JSON();
                        $bitacoraNuevo = $bitacoraJsonEncode->encode($tmp);
                        $bitacoraJson = '{"anterior" : "No aplica para registros nuevos","nuevo":'.$bitacoraNuevo.'}';
                        
                        $bitacoraParams['cveAccion'] = $this->accionBitacora;
                        $bitacoraParams['observaciones'] = $bitacoraJson;
                        
                        $BitacoraController = new BitacoraController();
                        $guardarBitacora = $BitacoraController->guardarBitacora($bitacoraParams);
                        if (!$guardarBitacora) {
                            $logger->w_onError('Ocurrio un error al guardar en bitacora!!!!!');
                        }
                    }
                } else {
                    throw new Exception("Cero filas afectadas", "10012");
                }
            } else {

                throw new Exception($this->proveedor->error(), $this->proveedor->errorNo());
            }

            if (isset($param['proveedor']) && (($param['proveedor'] !== "") || ($param['proveedor'] !== null) )) {
                //Nada va aqui
            } else {
//                $this->proveedor->free_result($this->proveedor->stmt);
                if ($this->proveedor != null)
                    $this->proveedor->close();
            }
        } catch (Exception $e) {
            $tmp = array("status" => "error", "totalCount" => 0, "msg" => $e->getMessage());
            if (isset($param['proveedor']) && (($param['proveedor'] !== "") || ($param['proveedor'] !== null) )) {
                //Nada va aqui
            } else {

                if ($this->proveedor != null)
                    $this->proveedor->close();
            }
        }


        return $tmp;
    }

    public function update($param) {
        try {
            if (isset($param['tabla']) && ($param['tabla'] !== "")) {
                $tabla = $param['tabla'];
            } else {
                throw new Exception("No ingresaste la tabla a la cual se realizaria la accion", "10001");
            }
            if (isset($param['accionBitacora']) && ($param['accionBitacora'] !== "")) {
                $this->accionBitacora = $param['accionBitacora'];
            }

            if (isset($param['d']) && ($param['d'] !== "")) {
                $d = $param['d'];
            } else {
                if ((!isset($param['tabla']) || ($param['tabla'] !== ""))) {
                    throw new Exception("No ingresaste las condiciones y los campos a mostrar", "10002");
                } else {
                    $d = "";
                }
            }

            if (isset($param['proveedor']) && (($param['proveedor'] !== "") || ($param['proveedor'] !== null) )) {
                $this->proveedor = $param['proveedor'];
            } else {
                $this->proveedor = new Proveedor(DEFECTO_GESTOR, DEFECTO_BD);
                $this->proveedor->connect();
            }

            $this->table = $tabla;
            $tmp = "";
//        $this->proveedor->connect();

            if (isset($d["values"])) {
                $sql = "SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA ";
                $sql .= " = '" . DEFECTO_NAME_BD . "' AND TABLE_NAME = '" . $this->table . "' ";
                $sql .= " Order By ORDINAL_POSITION ASC";

                $logger = new Logger("/../../logs/", "GenericDAO");
                $logger->w_onError($sql);
                $this->proveedor->execute($sql);

                if (!$this->proveedor->error()) {
                    if ($this->proveedor->rows($this->proveedor->stmt) > 0) {
                        $campos = array();
                        while ($row = $this->proveedor->fetch_array($this->proveedor->stmt, 0)) {
                            $campos[] = array("COLUMN_NAME" => $row["COLUMN_NAME"],
                                "IS_NULLABLE" => $row["IS_NULLABLE"],
                                "COLUMN_KEY" => $row["COLUMN_KEY"],
                                "DATA_TYPE" => $row["DATA_TYPE"],
                                "EXTRA" => $row["EXTRA"],
                                "COLUMN_COMMENT" => $row["COLUMN_COMMENT"]);
                        }

                        $sql = "UPDATE " . $this->table . " set ";
                        if (isset($d["values"])) {
                            for ($index = 0; $index < count($campos); $index++) {
                                $encontrado = false;
                                foreach ($d["values"] as $key => $value) {
                                    if ($campos[$index]["COLUMN_NAME"] == $key) {
                                        if ($value != "now()" && $value != "YEAR(now())" && $value != "null") {
                                            $sql .= " " . $key . "='" . addslashes($value) . "',";
                                        } else {
                                            $sql .= " " . $key . "=" . $value . ",";
                                        }

                                        $encontrado = true;
                                        break;
                                    }
                                }

//                                if (((Boolean) $encontrado === false) && (strtoupper($campos[$index]["COLUMN_KEY"]) != "PRI")) {
//                                    throw new Exception("No coincide uno de los campos con ninguno de la tabla ", "10015");
//                                }
                            }
                        }
                        $sql = substr($sql, 0, -1) . "";

                        if (isset($d["where"])) {
                            $sql .= " WHERE ";
                            foreach ($d["where"] as $key => $value) {
                                $sql .= " " . $key . " ='" . $value . "' And";
                            }
                            $sql = substr($sql, 0, -3);
                        }
                    }
                } else {
                    $logger = new Logger("/../../logs/", "GenericDAO");
                    $logger->w_onError("Error: " . $this->proveedor->error());
                    throw new Exception($this->proveedor->error(), $this->proveedor->errorNo());
                }
            } else {
                throw new Exception("No se ingresaron los parametros correctos", "10011");
            }
            $logger = new Logger("/../../logs/", "GenericDAO");
            $logger->w_onError("**********COMIENZA DAO GENERICO $tabla**********");
            $logger->w_onError($sql);

            // guardar bitacora del registro anterior
            if ($this->accionBitacora!='') {
                $paramTmpAnterior = array("tabla" => $this->table, "d" => $d, "tmpSql" => array(), "proveedor" => $this->proveedor);
                $tmpBitacora = $this->select($paramTmpAnterior);
                $bitacoraJsonEncode = new Encode_JSON();
                $bitacoraAnterior = $bitacoraJsonEncode->encode($tmpBitacora);
            }
            $this->proveedor->execute($sql);
            if (!$this->proveedor->error()) {
                if ($this->proveedor->_affected_rows() > 0) {
                    $paramTmp = array("tabla" => $this->table, "d" => $d, "tmpSql" => array(), "proveedor" => $this->proveedor);
                    $tmp = $this->select($paramTmp);
                    // guardar bitacora del registro nuevo
                    if ($this->accionBitacora!='') {
                        $bitacoraJsonEncode = new Encode_JSON();
                        $bitacoraNuevo = $bitacoraJsonEncode->encode($tmp);
                        $bitacoraJson = '{"anterior" : '.$bitacoraAnterior.',"nuevo":'.$bitacoraNuevo.'}';
                        
                        $bitacoraParams['cveAccion'] = $this->accionBitacora;
                        $bitacoraParams['observaciones'] = $bitacoraJson;
                        
                        $BitacoraController = new BitacoraController();
                        $guardarBitacora = $BitacoraController->guardarBitacora($bitacoraParams);
                        if (!$guardarBitacora) {
                            $logger->w_onError('Ocurrio un error al guardar en bitacora!!!!!');
                        }
                    }
                } else {
                    throw new Exception('No cumple con los parametros para poder eliminar el registro');
                }
            } else {
                throw new Exception($this->proveedor->error(), $this->proveedor->errorNo());
            }

            if (isset($param['proveedor']) && (($param['proveedor'] !== "") || ($param['proveedor'] !== null) )) {
                //Nada va aqui
            } else {
//                $this->proveedor->free_result($this->proveedor->stmt);
                if ($this->proveedor != null)
                    $this->proveedor->close();
            }
        } catch (Exception $e) {
            $tmp = array("status" => "error", "totalCount" => 0, "msg" => $e->getMessage());
            if (isset($param['proveedor']) && (($param['proveedor'] !== "") || ($param['proveedor'] !== null) )) {
                //Nada va aqui
            } else {
                if ($this->proveedor != null)
                    $this->proveedor->close();
            }
        }

        return $tmp;
    }

    public function deleteTable($param) {
        try {
            if (isset($param['tabla']) && ($param['tabla'] !== "")) {
                $tabla = $param['tabla'];
            } else {
                if (!isset($param['tmpSql']) || ($param['tmpSql'] === "")) {
                    throw new Exception("No ingresaste la tabla a la cual se realizaria la consulta", "10001");
                } else {
                    $tabla = "";
                }
            }

            if (isset($param['d']) && ($param['d'] !== "")) {
                $d = $param['d'];
            } else {
                if ((!isset($param['tmpSql']) || ($param['tmpSql'] === "")) && (!isset($param['tabla']) || ($param['tabla'] !== ""))) {
                    throw new Exception("No ingresaste las condiciones y los campos a mostrar", "10002");
                } else {
                    $d = "";
                }
            }

            if (isset($param['tmpSql']) && ($param['tmpSql'] !== "")) {
                $tmpSql = $param['tmpSql'];
            } else {
                if ((!isset($param['tabla']) || (@$param['tabla'] === "")) && (!isset($param['d']) || ($param['d'] === ""))) {
                    throw new Exception("No ingresaste un sql valido", "10003");
                } else {
                    $tmpSql = "";
                }
            }

            if (isset($param['proveedor']) && (($param['proveedor'] !== "") || ($param['proveedor'] !== null) )) {
                $this->proveedor = $param['proveedor'];
            } else {
                $this->proveedor = new Proveedor(DEFECTO_GESTOR, DEFECTO_BD);
                $this->proveedor->connect();
            }

            $this->table = $tabla;
            $tmp = "";


            if (isset($d["where"])) {
                $sql = "SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA ";
                $sql .= " = '" . DEFECTO_NAME_BD . "' AND TABLE_NAME = '" . $this->table . "' ";
                $sql .= " Order By ORDINAL_POSITION ASC";

                $logger = new Logger("/../../logs/", "GenericDAO");
                $logger->w_onError($sql);
                $this->proveedor->execute($sql);

                if (!$this->proveedor->error()) {
                    if ($this->proveedor->rows($this->proveedor->stmt) > 0) {
                        $campos = array();
                        while ($row = $this->proveedor->fetch_array($this->proveedor->stmt, 0)) {
                            $campos[] = array("COLUMN_NAME" => $row["COLUMN_NAME"],
                                "IS_NULLABLE" => $row["IS_NULLABLE"],
                                "COLUMN_KEY" => $row["COLUMN_KEY"],
                                "DATA_TYPE" => $row["DATA_TYPE"],
                                "EXTRA" => $row["EXTRA"],
                                "COLUMN_COMMENT" => $row["COLUMN_COMMENT"]);
                        }

                        $sql = "DELETE FROM " . $this->table . " ";

                        if (isset($d["where"])) {
                            $sql .= " WHERE ";
                            foreach ($d["where"] as $key => $value) {
                                $sql .= " " . $key . " ='" . $value . "' And";
                            }
                            $sql = substr($sql, 0, -3);
                        }
                    }
                } else {
                    $logger = new Logger("/../../logs/", "GenericDAO");
                    $logger->w_onError("Error: " . $this->proveedor->error());
                }
            } else if ((is_array($tmpSql)) && (sizeof($tmpSql) > 0)) {
                $sql = "";

                if ((array_key_exists("tablas", $tmpSql)) && ((String) $tmpSql["tablas"] !== "")) {
                    $sql .= " DELETE FROM " . $tmpSql["tablas"] . " ";
                } else {
                    if ((String) $tabla !== "") {
                        $sql .= " DELETE FROM " . $tabla . " ";
                    } else {
                        throw new Exception("No ingreso el nombre de la tabla a consultar para realizar la accion", "10005");
                    }
                }

                if ((array_key_exists("campos", $tmpSql)) && ((String) $tmpSql["campos"] !== "")) {
                    $sql .= "";
                    throw new Exception("Esta instruccion no es valida para la instruccion de insert", "10010");
                }

                if ((array_key_exists("where", $tmpSql)) && ((String) $tmpSql["where"] !== "")) {
                    $sql .= " WHERE " . $tmpSql["where"] . " ";
                }

                if ((array_key_exists("values", $tmpSql)) && ((String) $tmpSql["values"] !== "")) {
                    $sql .= "";
                    throw new Exception("Esta instruccion no es valida para la instruccion de insert", "10010");
                }

                if ((array_key_exists("groups", $tmpSql)) && ((String) $tmpSql["groups"] !== "")) {
                    $sql .= "";
                    throw new Exception("Esta instruccion no es valida para la instruccion SQL", "10010");
                }

                if ((array_key_exists("orders", $tmpSql)) && ((String) $tmpSql["orders"] !== "")) {
                    $sql .= "";
                    throw new Exception("Esta instruccion no es valida para la instruccion SQL", "10010");
                }
            } else {
                throw new Exception("No se ingresaron los parametros correctos", "10011");
            }

//            echo $sql;
            $this->proveedor->execute($sql);
            if (!$this->proveedor->error()) {

                if ($this->proveedor->_affected_rows() > 0) {
                    $tmp = array_merge(array("status" => "success", "totalCount" => 0, "msg" => "El registro se elimino de forma correcta"), array("data" => ""));
                } else {
                    throw new Exception("No se logro eliminar el registro", "10050");
                }
            } else {
                throw new Exception($this->proveedor->error(), $this->proveedor->errorNo());
            }

            if (isset($param['proveedor']) && (($param['proveedor'] !== "") || ($param['proveedor'] !== null) )) {
                $this->proveedor = $param['proveedor'];
            } else {
                $this->proveedor = new Proveedor(DEFECTO_GESTOR, DEFECTO_BD);
                $this->proveedor->connect();
            }
        } catch (Exception $e) {
            $tmp = array("status" => "error", "totalCount" => 0, "msg" => $e->getMessage());
            if (isset($param['proveedor']) && (($param['proveedor'] !== "") || ($param['proveedor'] !== null) )) {
                $this->proveedor = $param['proveedor'];
            } else {
                $this->proveedor = new Proveedor(DEFECTO_GESTOR, DEFECTO_BD);
                $this->proveedor->connect();
            }
        }
        return $tmp;
    }

    public function valida($sql) {
        $tagsMysqlSusses = array("SELECT", "FROM", "GROUP", "BY", "ORDER", "HAVING", "COUNT", "INSERT", "NOW", "EXPLAIN", "INT", "LIMIT", "WHERE", "CALL", "INNER", "JOIN", "LEFT", "RIGTH", "ON");
        $tagsMysqlError = array("DROP", "CREATE", "TRUNCATE", "DATE_FORMAT");

        $error = false;

        for ($index = 0; $index < sizeof($tagsMysqlError); $index++) {

            if (stripos($sql, $tagsMysqlError[$index]) !== false) {
                $error = true;
                break;
            }
        }

        if ((Boolean) $error === true) {
            return false;
        }
        return true;
    }

}

/*
 * Ejemplos de como utilizar esta cosa
 */
//$d = array("campos" => "", "where" => array("idCliente" => "1"), "limit" => array("max" => 20, "pag" => 0)); //  ," //,desAccion,activo
//$sql = array("campos" => "desGenero,activo,fechaRegistro,fechaActualizacion", "values" => "'sql demo','S',now(),now()", "tablas" => "generos", "where" => "", "groups" => "", "orders" => "");
//$sql = array("campos" => "", "values" => "", "tablas" => "generos", "where" => "cveGenero=8", "groups" => "", "orders" => "");
//$sql = array();
//$d = array("where" => array("idConvocarotia" => "1")); //,desAccion,activo
//$d = array("values" => array("desGenero" => "....", "activo" => "S", "fechaRegistro" => "now()", "fechaActualizacion" => "now()")); //,desAccion,activo
//$d = array("values" => array("desGenero" => "-----", "activo" => "S", "fechaActualizacion" => "now()"), "where" => array("cveGenero" => "3")); //,desAccion,activo
//$d = array("where" => array("cveGenero" => "6"));
//$d = array();
//$genericoDao = new GenericDAO();
//$d = array("values" => array("descTipoAspirante" => "INTERNOS", "activo" => "S", "fechaActualizacion" => "now()"),"where" => array("cveTipoAspirante" => "1")); //,desAccion,activo
//$r = $genericoDao->update("tbltiposaspirantes", $d, "");
//$d = array("values" => array("descTipoAspirante" => "EXTERNOS", "activo" => "S", "fechaActualizacion" => "now()"),"where" => array("cveTipoAspirante" => "2")); //,desAccion,activo
//$r = $genericoDao->update("tbltiposaspirantes", $d, "");
//$d = array("values" => array("descTipoAspirante" => "MIXTOS", "activo" => "S", "fechaActualizacion" => "now()"),"where" => array("cveTipoAspirante" => "3")); //,desAccion,activo
//$r = $genericoDao->deleteTable("tbltiposaspirantes", $d, "");
//$proveedor = new Proveedor(DEFECTO_GESTOR, DEFECTO_BD);
//$proveedor->connect();
////
//$sql = array("campos"=>"C.criPropio, F.desCff,  R.desRubro, TR.desTipoRubro,CE.desCe,C.anioCri,C.denominacion",
//    "tablas"=>"tblcri C INNER JOIN tblcff F on C.cveCff =F.cveCff
//                            INNER JOIN tblrubros R on R.cveRubro = C.cveRubro 
//                            INNER JOIN tbltiposrubros TR on TR.cveRubro = R.cveRubro and TR.tipo = C.tipo
//                            INNER JOIN tblreferenciace REF on REF.idReferenciaCe = C.idReferenciaCe
//                            INNER JOIN tblce CE on CE.cveCe = REF.cveCe",
//    "orders"=>"C.CriPropio ASC",
//    "where"=>"C.activo='S'");
//
//$param = array("tabla" => "", "d" => $d, "tmpSql" => $sql, "proveedor" => $proveedor);
//$row = $genericoDao->select($param);
//print_r($row);
////$row = $genericoDao->deleteTable($param);
////$row = $genericoDao->update($param);
////$row = $genericoDao->insert($param);
////echo json_encode($row);
//$proveedor->close();
?>
