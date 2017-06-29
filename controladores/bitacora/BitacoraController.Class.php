<?php
if (!isset($_SESSION)) {
    session_start();
}
include_once(dirname(__FILE__) . "/../../modelos/dao/GenericDAO.Class.php");
include_once(dirname(__FILE__) . "/../../tribunal/json/JsonEncod.Class.php");

class BitacoraController{
    
    /*
     * Metodo para registrar las acciones realizadas por el usuario
     * @params cveAccion Debe corresponder al ID de la acciOn de la tabla -tblacciones-
     * @params observacion Debe contener un arreglo asociativo con la informacionn del registro insertado o modificado
     * @params observacionPrevia Recibe un arreglo asociativo con los datos del registro previos a modificarse
     * @params proveedor recibe el proveedor de coneccion a la base de datoss
     */
    public function bitacora($params){
        $genericDao = new GenericDAO();
        $cveUsuario = $_SESSION['cveUsuarioSistema'];
	$cvePerfil = $_SESSION['cvePerfil'];
	$cveAdscripcion = $_SESSION['cveAdscripcion'];
        
        $d = array();
        
        $d['values']['cveAccion'] = (int)$params['cveAccion'];
        $d['values']['fechaMovimiento'] = 'now()';
        if ( array_key_exists('observacionPrevia', $params) && (is_array($params['observacionPrevia']) && $params['observacionPrevia'] != '' ) ) {
            $observaciones = json_encode($params['observacionPrevia']);
        } else {
            $observaciones = json_encode($params['observacion']);
        }
        if (array_key_exists('proveedor', $params) ) {
            $proveedor = $params['proveedor'];
        } else {
            $proveedor = null;
        }
        $d['values']['observaciones'] = $observaciones;
        $d['values']['cveUsuario'] = $cveUsuario;
        $d['values']['cvePerfil'] = $cvePerfil;
        $d['values']['cveAdscripcion'] = $cveAdscripcion;
        $sql = array();
        $tabla = 'tblbitacoramovimientos';
        $param = array("tabla" => "" . $tabla . "", "d" => $d, "tmpSql" => $sql, "proveedor" => $proveedor);
        $array = $genericDao->insert($param);
        if ($array != "") {
            return $array;
        }
        return array("status" => "error", "totalCount" => 0, "msg" => "Ocurrio un error al guardar la accion en bitacora");
    }

    public function guardarBitacora($params)
    {
        $genericDAO = new GenericDAO();
        $cveUsuario = $_SESSION['cveUsuarioSistema'];
        $cvePerfil = $_SESSION['cvePerfil'];
        $cveAdscripcion = $_SESSION['cveAdscripcion'];
        $saveBitacora = array(
            "tabla" => "tblbitacoramovimientos",
            "d" => array(
                "values" => array(
                    "cveAccion" => $params['cveAccion'],
                    "fechaMovimiento" => 'now()',
                    "observaciones" => $params['observaciones'],
                    "cveUsuario" => $cveUsuario,
                    "cvePerfil" => $cvePerfil,
                    "cveAdscripcion" => $cveAdscripcion
                )
            ),
            "proveedor" => null
        );
        $saveBitacoraResult = $genericDAO->insert($saveBitacora);

        if ($saveBitacoraResult['status'] == 'success' && $saveBitacoraResult['totalCount'] > 0) {
            return true;
        }else{
            return false;
        }
    }
}


/*
 * Ejem
 * INSERT
 * $array = $genericController->insertGeneric($param);
 * if ($array != "") {
        if ( $cveAccion != '' ) {
            $bitacoraController = new BitacoraController();
            $params = array('cveAccion' => $cveAccion,
                            'observacion' => $array);
            $result = $bitacoraController->bitacora($params);
        }
        return $array;
    }
 * 
 * UPDATE
 * $sqlPrevio = array('campos' => '*', 'tablas' => $this->t, 'where' => '' . $this->pm . '=' . $array[$this->pm]);
    $dPrevio = array("campos" => "");
    $paramPrevio = array("tabla" => "", "d" => $dPrevio, "tmpSql" => $sqlPrevio, "proveedor" => null);
    $registroPrevio = $genericController->selectGeneric($paramPrevio);

    $array = $genericController->updateGeneric($param);
    if ($array != "") {
        if ( $cveAccion != '' ) {
            $bitacoraController = new BitacoraController();
            $params = array('cveAccion' => $cveAccion,
                            'observacionPrevia' => array('anterior' => $registroPrevio,
                                                         'nuevo' => $array)
                            );
            $result = $bitacoraController->bitacora($params);
        }
        return $array;
    }
 */