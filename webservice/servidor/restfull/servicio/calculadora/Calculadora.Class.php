<?php

class Calculadora {

    public function sumar($a, $b) {
        return $a + $b;
    }
    public function restar($a, $b) {
        return $a - $b;
    }
    public function multiplicar($a, $b) {
        return $a * $b;
    }
    public function dividir($a, $b) {
        if($b<=0) return "El divisor no puede ser 0";
        return $a/$b;
    }

}

try {
    @$operacion = $_POST["operacion"];
    $calculadora = new Calculadora();
    if (method_exists($calculadora, $operacion)) {
        $a=$_POST['a'];
        $b=$_POST['b'];
        $c = (call_user_func_array(array($calculadora, $operacion),array($a,$b)));
        echo json_encode(array("status"=>"success","result"=>$c));

    }else{
        throw new Exception("esta operacion no esta definida", "09");
    }
    
} catch (Exception $e) {
    echo json_encode(array("status"=>"error","code"=>$e->getCode(),"msg"=>$e->getMessage()));
}
?>
