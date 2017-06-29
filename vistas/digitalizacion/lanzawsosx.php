<?php
//$urlxml="http://10.22.165.166/sigejupev2/vistas/digitalizacion/";
//$urlxml="http://".$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF'])."/";

$urlxml=$_GET['URLcodebase'];

if (!isset($_GET['idUsuario'])) {echo "No se puede llamar directamente"; exit; }

header('Content-type: application/x-java-jnlp-file');

$qstr="\n<argument>idCarpeta=".$_GET['idCarpeta']."</argument>";
$qstr=$qstr."\n    <argument>idActuacion=".$_GET['idActuacion']."</argument>";
$qstr=$qstr."\n    <argument>Descripcion=".$_GET['Descripcion']."</argument>";
$qstr=$qstr."\n    <argument>descJuzgado=".$_GET['descJuzgado']."</argument>";
$qstr=$qstr."\n    <argument>Numero=".$_GET['Numero']."</argument>";
$qstr=$qstr."\n    <argument>anio=".$_GET['anio']."</argument>";
$qstr=$qstr."\n    <argument>cveDocumento=".$_GET['cveDocumento']."</argument>";
$qstr=$qstr."\n    <argument>idUsuario=".$_GET['idUsuario']."</argument>";
$qstr=$qstr."\n    <argument>URLupload=".$_GET['URLupload']."</argument>\n";

//header('Content-Disposition: attachment; filename="myws.jnlp"');
?>
<?xml version="1.0" encoding="UTF-8" standalone="no"?>
<jnlp codebase="<?php echo $urlxml; ?>" href="" spec="1.0+">
    <information>
        <title>scanner</title>
        <vendor>bySFB</vendor>
        <homepage href=""/>
		<offline-allowed/>
        <description>scanner</description>
        <description kind="short">scanner</description>
    </information>
    <update check="always"/>
	<security>
      <all-permissions/>
  </security>
    <resources>
        <j2se version="1.8+"/>        
		<jar href="lib/commons-io-2.3.jar"/>
		<jar href="lib/commons-logging-1.1.3.jar"/>
        <jar href="lib/httpclient-4.3.5.jar"/>
	    <jar href="lib/httpcore-4.3.2.jar"/>
		<jar href="lib/httpmime-4.3.5.jar"/>
		<jar href="lib/itextpdf-5.5.3.jar"/>
		<jar href="lib/jai_imageio-1.1.jar"/>
		<jar href="lib/morena.jar"/>
		<jar href="lib/morena_license.jar"/>
		<jar href="lib/morena_osx.jar"/>
		<jar href="lib/twain.jar"/>
        <jar href="scanner.jar" main="true"/>
    </resources>
    <application-desc main-class="digitalizacion.Digitalizacion">
    <?php echo $qstr; ?>
	</application-desc>
</jnlp>


