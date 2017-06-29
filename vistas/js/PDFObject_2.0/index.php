<!DOCTYPE HTML>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html;charset=utf-8"/>
        <link type="text/css" href="../../css/font-awesome.min.css" rel="stylesheet" />
        <script type="text/javascript" src="../../js/jquery-1.10.2.min.js"></script>
        <script type="text/javascript" src="../../js/bootstrap/js/bootstrap.js"></script>
        <script type="text/javascript" src="../../js/bootstrap/botmoster/jquery.bootpag.min.js"></script>
        <link type="text/css" rel="stylesheet" href="../../js/bootstrap/css/bootstrap.css">
        <link type="text/css" rel="stylesheet" href="../../js/bootstrap/css/bootstrap.min.css">
        <link type="text/css" rel="stylesheet" href="../../js/bootstrap/DataTables-1.10.11/media/css/dataTables.bootstrap.min.css">
        <link type="text/css" rel="stylesheet" href="../../js/bootstrap/css/responsive.bootstrap.min.css">
        <script src="pdfobject.js"></script>
        <script src="_visor.js"></script>
        <style>
            /*.pdfobject-container { height: 500px;}*/ 
            /*.pdfobject { border: 1px solid #666; }*/
        </style>
    </head>
    <body>
        <script>            
            var pdfs = {
                data: [
                    {cveDocumento: 1, type: 'Ejemplo', ruta: '../../../modelos/integral/dao/plazapersonal/PDF_Movimiento.php?id=1'},
                    {cveDocumento: 1, type: 'Ejemplo', ruta: 'PDF_2.pdf'},
                    {cveDocumento: 1, type: 'Ejemplo', ruta: 'PDF_2.pdf'},
                    {cveDocumento: 1, type: 'Ejemplo', ruta: 'PDF_2.pdf'}
                ],
                type: {
                    cveUsuario: 850
                }
            }; 
            showPDF(pdfs, 'Acuerdo del 02/15/2016', false,1);
        </script>
    </body>
</html>