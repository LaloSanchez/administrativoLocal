<?php
namespace tribunal\fechas\Fechas;
class Fechas {

    private $errorLog;
    protected $dias = array("DOMINGO", "LUNES", "MARTES", "MIERCOLES", "JUEVES", "VIERNES", "SABADO");

    public function __construct($error) {
        $this->errorLog = $error;
    }

    public function avanzaDiaXHora($fecha, $horas, $habiles = false, $festivos = "", $especial = "N", $horasSuma = 0) {
        $this->errorLog->w_onError("**********AVANZA POR HORA LA FECHA**********");
        $this->errorLog->w_onError("**********ESPECIAL: " . $especial);
        $this->errorLog->w_onError("**********HABILES: " . $habiles);
        $this->errorLog->w_onError("**********HORAS: " . $horas);
        $this->errorLog->w_onError("**********FECHA: " . $fecha);
        $this->errorLog->w_onError("**********SUMA HORAS: " . $horasSuma);

        //$fecha = substr($fecha, 6, 4) . "-" . substr($fecha, 3, 2) . "-" . substr($fecha, 0, 2);
        $fecha = explode("-", $fecha);
        $fecha[2] = explode(" ", $fecha[2]);
        $fecha = $fecha[0]."-".$fecha[1]."-".$fecha[2][0];
        $dias = 0;
        $horasRestantes = $horas + 24;
        $diaFestivo = false;

        while ($horasRestantes > 0) {
            $fechaTmp = explode("-", $fecha);
            $diaSemana = date("w", mktime(0, 0, 0, $fechaTmp[1], $fechaTmp[2], $fechaTmp[0]));
            $diaFestivo = false;
            if ($habiles) {
                $this->errorLog->w_onError("**********SE IDENTIFICA QUE SOLO SERAN DIAS HABILES");
                if (($diaSemana >= 1) && ($diaSemana <= 5)) {
                    $this->errorLog->w_onError("**********NO ES SABADO NI DOMIMGO");
                    if ($especial == "N") {
                        $this->errorLog->w_onError("**********NO ES DE PROCEDIMIENTO ESPECIAL");
                        $this->errorLog->w_onError("**********BUSCAMOS SI ES UN DIA FESTIVO ".$fecha);
                        for ($index = 0; $index < count($festivos); $index++) {
                            $diaAuxiliar1 = strtotime('+' . 0 . ' day', strtotime($festivos[$index]["fecha"]));
                            $diaAuxiliar2 = strtotime('+' . 0 . ' day', strtotime($fecha));
                            if ($diaAuxiliar1 == $diaAuxiliar2) {
                                $diaFestivo = true;
                                $this->errorLog->w_onError("**********ES UN DIA FESTIVO " .$fecha);
                                break;
                            }
                        }

                        if (!$diaFestivo) {
                            $this->errorLog->w_onError("**********NO ES UN FIA FESTIVO SE SUMAN 24 HORAS");
                            $horasRestantes = $horasRestantes - 24;
                        }
                    } else {
                        $this->errorLog->w_onError("**********ES DE PROCEDIMIENTO ESPECIAL");
                        for ($index = 0; $index < count($festivos); $index++) {

                            $diaAuxiliar1 = strtotime('+' . 0 . ' day', strtotime($festivos[$index]["fecha"]));
                            $diaAuxiliar2 = strtotime('+' . 0 . ' day', strtotime($fecha));
                            if ($diaAuxiliar1 == $diaAuxiliar2) {
                                if ($festivos[$index]["Tipo"] == 'S') {
                                    $this->errorLog->w_onError("**********ES UN FIA FESTIVO ".$fecha);
                                    $diaFestivo = true;
                                    break;
                                }
                            }
                        }
                        if (!$diaFestivo) {
                            $this->errorLog->w_onError("**********NO ES UN FIA FESTIVO SE SUMAN 24 HORAS");
                            $horasRestantes = $horasRestantes - 24;
                        }
                    }
                } else {
                    $this->errorLog->w_onError("**********ES SABADO Y DOMINGO");
                }
            } else {
                $horasRestantes = $horasRestantes - 24;
            }
            $dias+=1;

            $fechaAuxiliar = strtotime('+' . 1 . 'day', strtotime($fecha));
            $fecha = date('Y-m-d', $fechaAuxiliar);
        }
        $horaParaSumar = (($dias - 1) * 24) + $horasSuma;
        $this->errorLog->w_onError("**********TERMINA AVANCE DE FECHAS POR HORA************");
        return $horaParaSumar;
    }

    public function avanzaDiaDisponible($fecha, $fechaMax, $catAudienciasDto, $habiles = false, $festivos = "", $especial = "N") { //$catAudienciasDto
        /*
         * Verificamos si el dia esta disponible para programar audiencias
         */
        
//        $this->errorLog->w_onError("**********AVANZA POR HORA LA FECHA**********");
//        $this->errorLog->w_onError("**********ESPECIAL: " . $especial);
//        $this->errorLog->w_onError("**********HABILES: " . $habiles);
//        $this->errorLog->w_onError("**********HORAS: " . $horas);
//        $this->errorLog->w_onError("**********FECHA: " . $fecha);
//        $this->errorLog->w_onError("**********SUMA HORAS: " . $horasSuma);

        //$fecha = substr($fecha, 6, 4) . "-" . substr($fecha, 3, 2) . "-" . substr($fecha, 0, 2);
        $fecha = explode("-", $fecha);
        $fecha[2] = explode(" ", $fecha[2]);
        $fechaMinima = $fecha[0]."-".$fecha[1]."-".$fecha[2][0];
        
        $fecha = explode("-", $fechaMax);
        $fecha[2] = explode(" ", $fecha[2]);
        
        $fechaMaxima = $fecha[0]."-".$fecha[1]."-".$fecha[2][0];
        
        $dias = 0;
        //$horasRestantes = $horas + 24;
        $diaFestivo = false;

        //$fechaMinima = substr($fecha, 6, 4) . "-" . substr($fecha, 3, 2) . "-" . substr($fecha, 0, 2);
        //$fechaMaxima = substr($fechaMax, 6, 4) . "-" . substr($fechaMax, 3, 2) . "-" . substr($fechaMax, 0, 2);
        $dias = 0;
        $diaDisponible = false;

        if (($catAudienciasDto->getCveTipoAudiencia() == 1) || ($catAudienciasDto->getCveTipoAudiencia() == 3)) { //AUDIENCIA PROGRAMADA O MIXTA
            while ($diaDisponible == false) {
                $fechaTmp = explode("-", $fechaMinima);

                $diaSemana = date("w", mktime(0, 0, 0, $fechaTmp[1], $fechaTmp[2], $fechaTmp[0]));
                $diaFestivo = false;
                if ($habiles) {
                    if (($diaSemana >= 1) && ($diaSemana <= 5)) {
                        if ($especial == "N") {//No es un tipo de audiencia especial se le da un trato normal
                            for ($index = 0; $index < count($festivos); $index++) {
                                $diaAuxiliar1 = strtotime('+' . 0 . ' day', strtotime($festivos[$index]["fecha"]));
                                $diaAuxiliar2 = strtotime('+' . 0 . ' day', strtotime($fechaMinima));
                                if ($diaAuxiliar1 == $diaAuxiliar2) {
                                    $diaFestivo = true;
                                    break;
                                }
                            }

                            if ($diaFestivo) {//Avanzamos un dia porque ese dia no lo podemos contemplar 
                                $fechaAuxiliar = strtotime('+' . 1 . 'day', strtotime($fechaMinima));
                                $fechaMinima = date('Y-m-d', $fechaAuxiliar);
                                $dias+=1;
                            } else { //Ese dia es valido para comenzar con la programacion de las audiencias
                                $diaDisponible = true;
                            }
                        } else {// Es un tipo de audiencia especial que es programada pero se considera urgente
                            for ($index = 0; $index < count($festivos); $index++) {

                                $diaAuxiliar1 = strtotime('+' . 0 . ' day', strtotime($festivos[$index]["fecha"]));
                                $diaAuxiliar2 = strtotime('+' . 0 . ' day', strtotime($fechaMinima));
                                if ($diaAuxiliar1 == $diaAuxiliar2) {
                                    if ($festivos[$index]["Tipo"] == 'S') {
                                        $diaFestivo = true;
                                        break;
                                    }
                                }
                            }

                            if ($diaFestivo) {
                                $fechaAuxiliar = strtotime('+' . 1 . 'day', strtotime($fechaMinima));
                                $fechaMinima = date('Y-m-d', $fechaAuxiliar);
                                $dias+=1;
                            } else {
                                $diaDisponible = true;
                            }
                        }
                    } else {
                        $fechaAuxiliar = strtotime('+' . 1 . 'day', strtotime($fechaMinima));
                        $fechaMinima = date('Y-m-d', $fechaAuxiliar);
                        $dias+=1;
                    }
                }
            }

            return $dias * 24;
        } else if ($catAudienciasDto->getCveTipoAudiencia() == 2) { //AUDIENCIA URGENTE
            return 0;
        }
    }

    public function tiempoUtilizacion($imputados, $ofendidos, $audienciasDistritos) {
        $numImputadosDetenidos = 0;
        $total = 0;

        for ($index = 0; $index < count($imputados); $index++) { //Obtenemos el numero de imputados detenidos
            if ($imputados[$index]->getDetenido() == "S") {
                $numImputadosDetenidos+=1;
            }
        }

        if ($numImputadosDetenidos > 2) {
            $total = ((($numImputadosDetenidos / 2) * $audienciasDistritos->getMaxDuracion()) + $audienciasDistritos->getHolgura());

            if ($total > 360) {//Revizamos si el timepo no rebaza las 6 horas
                $total = 360; // Equivalente a 6 horas  
            }
        } else {
            $total = ($audienciasDistritos->getMaxDuracion() + $audienciasDistritos->getHolgura());
        }

        unset($numImputadosDetenidos);
        unset($catAudiencia);
        unset($imputados);
        unset($ofendidos);
        unset($index);

        return $total;
    }

}

?>