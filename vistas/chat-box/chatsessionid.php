<?php 
session_start();

$_SESSION['chatid'] = $_POST['chatident'];
$_SESSION['cveNumero'] = $_POST['cveNumero'];
$_SESSION['tipochat'] = $_POST['tipochat'];

print json_encode(array('message' => $_SESSION['chatid']));
die();

?>