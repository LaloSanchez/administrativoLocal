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

$sql = "show tables";
$sentencia = $mysqli->query($sql);

if ($sentencia->num_rows > 0) {
    $tables = array();
    while ($row = $sentencia->fetch_assoc()) {
//        $tables[] = ;
        $tables[] = $row["Tables_in_" . $base];
    }
}

$error = "";
$result = "";

if (isset($_POST["script"])) {
    $error = "";
    $sql = $_POST["script"];
    $sentencia = $mysqli->query($sql);
    $error = $mysqli->error;
    if ($sentencia->num_rows > 0) {
        $result = array();
        while ($row = $sentencia->fetch_assoc()) {
            $result[] = $row;
        }
    } else {
        $result = "";
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
        sqlScript = function (div, db) {
            var sql = document.getElementById("sqlscript");
//            alert(sql.value);
            $.post("sql.php", {dataBase: db, script: sql.value}, function (htmlexterno) {
                $("#" + div).html(htmlexterno);
            });
        }
    </script>

    <body>
        <div id="wrapper">
            <?php
//            if ($result != "") {
            echo "<div class=\"alert-" . (($error != "") ? "danger" : "success" ) . "\">";
            echo "Sql: " . $sql . "<br>";
            if ($error != "") {
                echo "Error: " . $error;
            }
            echo "</div>";
//            }
            ?>
            <textarea class="form-control" name="sqlscript" id="sqlscript"><?php echo $sql; ?></textarea> 
            <button class="btn  btn-default" onclick="sqlScript('divContentAdmin', '<?php echo $base; ?>')">Execute</button>
            <div class="panel-body" id="divContentSql">
                <div class="dataTable_wrapper">
                    <?php
                    if ($result != "") {
                        ?>
                        <table class="table table-striped table-bordered table-hover" id="dataTables-dataBase">
                            <thead>
                                <tr>
                                    <?php
                                    foreach ($result[0] as $column => $value) {
                                        echo "<th>$column</th>";
                                    }
                                    ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                for ($index = 0; $index < count($result); $index++) {
                                    ?>
                                    <tr class="odd gradeX" >
                                        <?php
                                        foreach ($result[$index] as $registro) {
                                            echo "<td>$registro</td>";
                                        }
                                        ?>
                                    </tr>
                                    <?php
                                }
                                ?>
                            </tbody>
                        </table>
                        <?php
                    } else {
                        if ($error == "") {
                            echo "<div class=\"alert-success\">";
                            echo "Empty<br>";
                            echo "</div>";
                        }
                    }
                    ?>
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
//                    });
//                        });
        </script>
    </body>

</html>
