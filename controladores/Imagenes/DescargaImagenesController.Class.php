<?php
class DescargaImagenesController {

    public function descargaImagen($ruta = '', $nombreZip = '') {
        /**
         * @author e-israel
         * arma rutas relativas al PATH del server, quita el http[s]://PATH/PATH/
         */
        $ruta = substr($ruta, 0, -1);
        $rutas = array();
        $rutasTmp = explode(',', $ruta);
        $pathGeneral = '../../vistas/descargasZip/';
        $zipname = "./" . $nombreZip . '.zip';
        
        $pathEliminar = array('/^([http]|[https]:+\/+\/+)+([a-zA-Z0-9-_\.])*(\/)+([a-zA-Z0-9-_])*(\/)+/');
        foreach($rutasTmp as $r)
        {   
//            #$r = 'https://juan.pepe.com/sigejupev2/imagenes/abc/hola.pdf';
            $rutas[] = "../../../".preg_replace($pathEliminar, '', $r);
        }
        
        $zip = new ZipArchive;
        if ($zip->open($zipname, ZIPARCHIVE::CREATE) === TRUE) 
        {
            foreach ($rutas as $file) 
            {

                $rutaCompleta = explode('/', $file);
                $nombre = end($rutaCompleta);
                /*
                $direccionArchivo = "";
                foreach($rutaCompleta as $rutaComp){
                    $direccionArchivo .= $rutaComp."/";
                }
                 */
                if(file_exists($file))
                    $zip->addFile($file, " " . basename($nombre));
            }

            $zip->close();

            //Se descarga el ZIP
            header('Content-Type: application/zip');
            header('Content-disposition: attachment; filename=' . $nombreZip . '.zip');
            header('Content-Length: ' . filesize($zipname));
            readfile($zipname);

            //Se elimina el ZIP
            unlink($zipname);
        }
        else
        {
            exit("Error al crear/abrir el archivo <$zipname.zip>\n");
        }
    }

}

@$action = $_POST['action'];
$DescargaImagenesController = new DescargaImagenesController();
if ($action == "descargar") {
    @$ruta = $_POST['ruta'];
    @$nombre = $_POST['nombre'];
    $DescargaImagenesController->descargaImagen($ruta, $nombre);
}