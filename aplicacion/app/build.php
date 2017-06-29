<?php
include_once(dirname(__FILE__) . "/appConfig.php");
error_reporting(E_ALL);
error_reporting(-1);
$user = $_SERVER['PHP_AUTH_USER'];
$password = $_SERVER['PHP_AUTH_PW'];
$base = $_POST["dataBase"];

@$mysqli = new mysqli(host, $user, $password, $base, port);
if ($mysqli->connect_error) {
    die("Error en la conexion : " . $mysqli->connect_errno .
            "-" . $mysqli->connect_error);
    exit(0);
}

$sql = "show databases";
$sentencia = $mysqli->query($sql);

if ($sentencia->num_rows > 0) {
    $databases = array();
    while ($row = $sentencia->fetch_assoc()) {
        $databases[] = $row["Database"];
    }
}

$sql = "show tables";
$sentencia = $mysqli->query($sql);

if ($sentencia->num_rows > 0) {
    $tables = array();
    while ($row = $sentencia->fetch_assoc()) {
        $tables[] = $row["Tables_in_" . $base];
    }
}

$json = json_encode($tables);
$mysqli->close();
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="author" content="">

        <title></title>

        <!-- Bootstrap Core CSS -->
        <link href="appGen/components/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">

        <!-- MetisMenu CSS -->
        <!--<link href="appGen/components/metisMenu/dist/metisMenu.min.css" rel="stylesheet">-->

        <!--         Timeline CSS 
                <link href="appGen/components/datatables/media/js/jquery.dataTables.min.js" rel="stylesheet">-->

        <!-- Custom CSS -->
        <link href="appGen/dist/css/sb-admin-2.css" rel="stylesheet">
        <link href="appGen/components/jquery/css/jquery/jquery-ui-1.9.2.custom.min.css" rel="stylesheet">

        <!-- Morris Charts CSS -->
        <link href="appGen/components/morrisjs/morris.css" rel="stylesheet">

        <!-- Custom Fonts -->
        <link href="appGen/components/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">

        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
            <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
            <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
        <![endif]-->

    </head>
    <script language="javascript">
        appGen = function (div, db) {
            $.post("build.php", {dataBase: db}, function (htmlexterno) {
                $("#" + div).html(htmlexterno);
            });
        }
    </script>

    <body>
        <!--        <ul class="nav nav-tabs">
                    <li class="active"><a href="#">Estructura</a></li>
                    <li><a href="#">Examinar</a></li>
                    <li><a href="#">Exportar</a></li>
                    <li><a href="#">Importar</a></li>
                </ul>-->
        <div id="wrapper">
            <div class="panel-body" id="divContentGen">


                <div class="panel panel-green">
                    <div class="panel-heading">
                        Contruir App
                    </div>
                    <div class="panel-body">
                        <div class="form-group col-lg-4" >
                            <div class="input-group">
                                Bases:  
                                <select class="form-control" name="cmbDataBases" id="cmbDataBases" onchange="appGen('divContent', this.value)">
                                    <option value="">Seleccione una opcion</option>
                                    <?php
                                    for ($index = 0; $index < count($databases); $index++) {
                                        echo "<option value=\"" . $databases[$index] . "\" " . (($base == $databases[$index]) ? "selected " : "") . ">" . $databases[$index] . "</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group col-lg-4" >
                            <div class="input-group">
                                Nombre 
                                <input id="my-input" class="form-control" type="text" placeholder="Ingresa el Nombre de la tabla">
                            </div>
                        </div>
                        <div class="form-group col-lg-4" >
                            <div class="btn-group">
                                <button class="btn bg-primary">Generar</button>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

        </div>
        <!-- /#wrapper -->

        <!-- jQuery -->
        <script src="appGen/components/jquery/dist/jquery.min.js"></script>
        <script src="appGen/components/jquery/dist/jquery-ui-1.9.2.custom.min.js"></script>

        <!-- Bootstrap Core JavaScript -->
        <script src="appGen/components/bootstrap/dist/js/bootstrap.min.js"></script>       

        <!-- Morris Charts JavaScript -->
        <!--<script src="appGen/components/raphael/raphael-min.js"></script>-->
        <!--<script src="appGen/components/morrisjs/morris.min.js"></script>-->
        <!--<script src="appGen/js/morris-data.js"></script>-->

        <!-- Custom Theme JavaScript -->
        <!--<script src="appGen/dist/js/sb-admin-2.js"></script>-->
        <script>
                                    $(function () {
                                        var availableTags = <?php echo $json; ?>;
                                        $("#my-input").autocomplete({
                                            source: availableTags
                                        });
                                    });
        </script>
    </body>

</html>
