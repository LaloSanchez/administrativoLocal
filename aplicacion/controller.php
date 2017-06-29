<?php

error_reporting(E_ALL);
error_reporting(-1);
include_once(ROOT . DS . "tribunal" . DS . "connect" . DS . "Proveedor.Class.php");
include_once(ROOT . DS . "tribunal" . DS . "logger" . DS . "Logger.Class.php");

class Controller {

    public function __construct() {
        
    }

    public static function run() {
        $tablas = "";
        $contador = 0;
        $error = false;

        $proveedor = new Proveedor(DEFECTO_GESTOR, DEFECTO_BD);
        $proveedor->connect();

        $proveedor->execute("show tables");
        if (!$proveedor->error()) {
            if ($proveedor->rows($proveedor->stmt) > 0) {
                while ($row = $proveedor->fetch_rows($proveedor->stmt, 0)) {
                    $tablas[$contador] = $row[0];
                    $contador++;
                }
            } else {
                $error = true;
                throw new Exception("No se loalizaron tablas de la bd");
            }
        } else {
            $error = true;
            throw new Exception("Ocurrio un error al obtener el listado de tablas de la base");
        }

        if ((!$error) && (count($tablas) > 0)) {
            $logger = new Logger("/../../logs/", "Controllers");
            $logger->w_onError("**********COMIENZA EL PROGRAMA CON LA CREACION DEL CONTROLLER**********");
            for ($i = 0; $i < count($tablas); $i++) {
                $campos = "";
                $contador = 0;
                $proveedor->execute("desc " . $tablas[$i]);
                if (!$proveedor->error()) {
                    if ($proveedor->rows($proveedor->stmt) > 0) {
                        while ($row = $proveedor->fetch_rows($proveedor->stmt, 0)) {
                            $campos[$contador] = array("field" => $row[0], "primary" => $row[3]);
                            $contador++;
                        }
                    } else {
                        $error = true;
                    }
                } else {
                    $error = true;
                }

                if (count($campos) > 0) {
                    $pref = substr($tablas[$i], 0, 3);
                    $name = "";
                    if ($pref == DEFECTO_PREFIJO) {
                        $name = substr($tablas[$i], 3);
                    } else {
                        $name = $tablas[$i];
                    }

                    $controller = new Controller();
                    $controller->creaDirectorio(ROOT . "controladores" . DS . DEFECTO_BD . DS . $name);
                    $logger->w_onError("CREAMOS EL DIRECTORIO: " . ROOT . "controladores" . DS . $name);
                    if ($controller->existeDirectorio(ROOT . "controladores" . DS . DEFECTO_BD . DS . $name)) {
                        if (!$controller->existeArchivo(ROOT . "controladores" . DS . DEFECTO_BD . DS . $name . DS . ucwords($name) . "Controller.Class.php")) {
                            $dto = fopen(ROOT . "controladores" . DS . DEFECTO_BD . DS . $name . DS . ucwords($name) . "Controller.Class.php", "w");
                            $logger->w_onError("CREAMOS EL ARCHIVO: " . ROOT . "controladores" . DS . DEFECTO_BD . DS . $name . DS . ucwords($name) . "Controller.Class.php");
                            $cuerpo = "<?php\n";
                            $cuerpo.="/*\n";
                            $cuerpo.="*************************************************\n";
                            $cuerpo.="*FRAMEWORK V2.1.0 (http://www.pjedomex.gob.mx)\n";
                            $cuerpo.="*Copyright 2009-2016 CONTROLLER\n";
                            $cuerpo.="* Licensed under the MIT license \n";
                            $cuerpo.="* Autor: *\n";
                            $cuerpo.="* Departamento de Desarrollo de Software\n";
                            $cuerpo.="* Subdireccion de Ingenieria de Software\n";
                            $cuerpo.="* Direccion de Teclogias de Informacion\n";
                            $cuerpo.="* Poder Judicial del Estado de Mexico\n";
                            $cuerpo.="*************************************************\n";
                            $cuerpo.="*/\n\n";
//                            $cuerpo.=" include_once(dirname(__FILE__).\"" . DS . ".." . DS . ".." . DS . ".." . DS . "modelos" . DS . DEFECTO_BD . DS . "dto" . DS . $name . DS . ucwords($name) . "DTO.Class.php\");\n";
                            $cuerpo.= "include_once(dirname(__FILE__).\"" . DS . ".." . DS . ".." . DS . ".." . DS . "modelos" . DS . "dao" . DS . "GenericDAO.Class.php\");\n";
                            $cuerpo.= "include_once(dirname(__FILE__).\"" . DS . ".." . DS . ".." . DS . ".." . DS . "tribunal" . DS . "connect" . DS . "Proveedor.Class.php\");\n";
                            $cuerpo.= "class " . ucwords($name) . "Controller {\n";
                            $cuerpo.= "private \$proveedor;\n";
                            $cuerpo.="public function __construct() {\n";
                            $cuerpo.="}\n";
                            
                            $cuerpo.="public function select" . ucwords($name) . "(\$param){\n";

                            $cuerpo.="$" . $name . "Dao = new GenericDAO();\n";
                            $cuerpo.="$" . $name . "Array = $" . $name . "Dao->select(\$param);\n";

                            $cuerpo.="unset($" . $name . "Dao);\n";
                            $cuerpo.="unset(\$param);\n";

                            $cuerpo.="return $" . $name . "Array;\n";
                            $cuerpo.="}\n";
                            $cuerpo.="public function insert" . ucwords($name) . "(\$param){\n";

                            $cuerpo.="$" . $name . "Dao = new GenericDAO();\n";
                            $cuerpo.="$" . $name . "Array = $" . $name . "Dao->insert(\$param);\n";
                            $cuerpo.="unset($" . $name . "Dao);\n";
                            $cuerpo.="unset(\$param);\n";
                            $cuerpo.="return $" . $name . "Array;\n";
                            $cuerpo.="}\n";
                            $cuerpo.="public function update" . ucwords($name) . "(\$param){\n";
                            $cuerpo.="$" . $name . "Dao = new GenericDAO();\n";
                            $cuerpo.="$" . $name . "Array = $" . $name . "Dao->update(\$param);\n";
                            $cuerpo.="unset($" . $name . "Dao);\n";
                            $cuerpo.="unset(\$param);\n";
                            $cuerpo.="return $" . $name . "Array;\n";
                            $cuerpo.="}\n";
                            $cuerpo.="public function delete" . ucwords($name) . "(\$param){\n";
                            $cuerpo.="$" . $name . "Dao = new GenericDAO();\n";
                            $cuerpo.="$" . $name . "Array = $" . $name . "Dao->update(\$param);\n";
                            $cuerpo.="unset($" . $name . "Dao);\n";
                            $cuerpo.="unset(\$param);\n";
                            $cuerpo.="return $" . $name . "Array;\n";
                            $cuerpo.="}\n";
                            $cuerpo.= "}\n?>";
                            fwrite($dto, $cuerpo);
                        } else {
                            $logger->w_onError("EL ARCHIVO YA EXISTE NO SE PUEDE REESCRIBIR " . ROOT . "controladores" . DS . $name . DS . ucwords($name) . "Controller.Class.php");
                        }
                    } else {
                        $logger->w_onError("EL DIRECTORIO NO SE LOGRO CREAR");
                    }
                }

//                break;
            }
        }
        $logger->w_onError("**********TERMINA EL PROGRAMA CON LA CREACION DEL CONTROLLER**********");
        $proveedor->free_result($proveedor->stmt);
        $proveedor->close();
    }

    private function existeArchivo($NomArchivo) {
        if (file_exists($NomArchivo)) {
            return true;
        } else {
            return false;
        }
    }

    private function creaDirectorio($NomDirectorio) {
        $VectorDirectorio = preg_split("[" . DS . "]", $NomDirectorio);
        $ruta = "";
        foreach ($VectorDirectorio as $Carpeta) {
            if ($Carpeta != "." && trim($Carpeta) != "") {
                if (DEFECTO_SO == "windows") {
                    if ($ruta == "") {
                        $ruta = $Carpeta;
                    } else {
                        $ruta = $ruta . "" . DS . "" . $Carpeta;
                    }
                } else {
                    $ruta = $ruta . "/" . $Carpeta;
                }

                if (!$this->existeDirectorio($ruta)) {
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

?>