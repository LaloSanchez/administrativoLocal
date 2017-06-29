<?php
include_once(dirname(__FILE__) . "/appConfig.php");
error_reporting(E_ALL);
error_reporting(-1);
$user = $_SERVER['PHP_AUTH_USER'];
$password = $_SERVER['PHP_AUTH_PW'];
$base = $_POST["dataBase"];

if (isset($_POST["option"])) {
    $option = $_POST["option"];
} else {
    $option = "Estructura";
}

@$mysqli = new mysqli(host, $user, $password, $base, port);
if ($mysqli->connect_error) {
    die("Error en la conexion : " . $mysqli->connect_errno .
            "-" . $mysqli->connect_error);
    exit(0);
}

$sql = "show tables";
$sentencia = $mysqli->query($sql);

if ($sentencia->num_rows > 0) {
    $tables = array();
    while ($row = $sentencia->fetch_assoc()) {
//        $tables[] = ;
        $tables[] = $row["Tables_in_" . $base];
    }
}
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
        seleccionaTable = function (div, tbl, db) {
            $.post("describe.php", {dataBase: db, table: tbl}, function (htmlexterno) {
                $("#" + div).html(htmlexterno);
            });
        }
        addTable = function (div, db) {
            $.post("create.php", {dataBase: db}, function (htmlexterno) {
                $("#" + div).html(htmlexterno);
            });
        }
        seleccionaDB = function (div, db) {
            $.post("admin.php", {dataBase: db}, function (htmlexterno) {
                $("#" + div).html(htmlexterno);
            });
        }
        sqlDB = function (div, db, object) {
//            object.className = "active";
            $.post("sql.php", {dataBase: db}, function (htmlexterno) {
                $("#" + div).html(htmlexterno);
            });
        }
    </script>

    <body>
        <div class="col-lg-12">
            <h1 class="page-header">Administrador</h1>
        </div>
        <ul id="myTabs" class="nav nav-tabs" role="tablist">
            <li class="<?php echo ($option == "Estructura") ? "active" : "" ?>" onclick="seleccionaDB('divContent', '<?php echo $base; ?>')"><a href="#">Estructura</a></li>
            <li class="<?php echo ($option == "Examinar") ? "active" : "" ?>" onclick="sqlDB('divContentAdmin', '<?php echo $base; ?>', this)"><a href="#">Examinar</a></li>
            <li><a href="#">Exportar</a></li>
            <li><a href="#">Importar</a></li>
        </ul>
        <div id="wrapper">
            <div class="panel-body" id="divContentAdmin">
                <a href="#noir" onclick="addTable('divContentAdmin', '<?php echo $base; ?>')">Crear Tabla</a>
                <div class="dataTable_wrapper">
                    <table class="table table-striped table-bordered table-hover" id="dataTables-dataBase">
                        <thead>
                            <tr>
                                <th>Table</th>
                                <th>Engine</th>
                                <th>Cotejamiento</th>
                                <th>Tama&ntilde;o</th>
                                <th>Residuo a depurar</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            @$mysqli = new mysqli(host, $user, $password, "information_schema", port);
                            if ($mysqli->connect_error) {
                                die("Error en la conexion : " . $mysqli->connect_errno .
                                        "-" . $mysqli->connect_error);
                                exit(0);
                            }

                            for ($index = 0; $index < count($tables); $index++) {
                                $sql = "select *,round(((data_length + index_length) / 1024 / 1024), 2) as size   FROM TABLES Where TABLE_SCHEMA='" . $base . "' And TABLE_NAME='" . $tables[$index] . "'";
                                $sentencia = $mysqli->query($sql);
                                ?>
                                <tr class="odd gradeX" onclick="seleccionaTable('divContentAdmin', '<?php echo $tables[$index]; ?>', '<?php echo $base; ?>')">
                                    <td><?php echo substr($tables[$index], strlen(prefijo), strlen($tables[$index])); ?></td>
                                    <?php
                                    if ($sentencia->num_rows > 0) {
                                        while ($row = $sentencia->fetch_assoc()) {
                                            echo "<td>" . $row['ENGINE'] . "</td>";
                                            echo "<td>" . $row['TABLE_COLLATION'] . "</td>";
                                            echo "<td class=\"center\">" . $row['size'] . "</td>";
                                            echo "<td class=\"center\">-</td>";
                                        }
                                    } else {
                                        echo "<td>-</td>";
                                        echo "<td>-</td>";
                                        echo "<td class=\"center\">-</td>";
                                        echo "<td class=\"center\">-</td>";
                                    }
                                    ?>
                                </tr>
                                <?php
                            }
                            $mysqli->close();
                            ?>
                        </tbody>
                    </table>

                </div>
            </div>

        </div>
        <!-- /#wrapper -->

        <!-- jQuery -->
        <script src="appGen/components/jquery/dist/jquery.min.js"></script>

        <!-- Bootstrap Core JavaScript -->
        <script src="appGen/components/bootstrap/dist/js/bootstrap.min.js"></script>       

        <!-- Morris Charts JavaScript -->
        <!--<script src="appGen/components/raphael/raphael-min.js"></script>-->
        <!--<script src="appGen/components/morrisjs/morris.min.js"></script>-->
        <!--<script src="appGen/js/morris-data.js"></script>-->

        <!-- Custom Theme JavaScript -->
        <!--<script src="appGen/dist/js/sb-admin-2.js"></script>-->
        <script>
//                        $(document).ready(function () {
//                            $('#dataTables').DataTable({
//                                "order": [[3, "desc"]]
//                            });
//                        });
        </script>
    </body>

</html>
