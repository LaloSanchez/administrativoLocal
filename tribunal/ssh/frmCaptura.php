<?php
include "asterisk.php";
print_r($_POST);

echo "llama perito";

if (isset($_POST["txtCelular"])) {
   // echo $celular = "" .substr( $_POST["txtCelular"],-7);
    $celular = "044" .$_POST["txtCelular"];
    $context = $_POST["context"];
    $asterisk2 = new asterisk2();
    $e = $asterisk2->llamar("10.22.157.61", $celular, "../../llamadas/", "tmp" . date("YmdHis") . ".txt", $context);
    print_r($e);
}
?>


<html>
    <head>
        <title>Fromulario de captura</title>
    </head>

    <form name="frmCaptura" action="frmCaptura.php" method="POST">
        <table>
            <tr>
                <td>
                    Celular
                </td>
                <td>
                    <input type="text" name="txtCelular" id="txtCelular" value="" maxlength="15" size="15">                    
                </td>
                <td>
                    audio
                </td>
                <td>                    
                    <input type="text" name="context" id="context" value=""  size="15">
                </td>
            </tr>
        </table>
        <input type="submit" value="Enviar">
    </form>
</html>
