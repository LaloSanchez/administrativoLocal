/*
 * @autor Fernanda Ortega
 */
(function (){
//   var baseURL = '../../../js/DataTables-1.10.13/';
    var baseURL = '../../../js/DataTables-1.10.13/';
    var styles  = [
            'media/css/dataTables.bootstrap.min.css',
            'extensions/Responsive/css/responsive.bootstrap.min.css',
            'extensions/Buttons/css/buttons.dataTables.min.css'
        ];
    var scripts  = [
            'media/js/jquery.dataTables.min.js',
            'media/js/dataTables.bootstrap.min.js',
            'extensions/Responsive/js/dataTables.responsive.min.js',
            //Nuevas
            'media/js/pdfmake.min.js',
            'media/js/vfs_fonts.js',
            'extensions/Buttons/js/dataTables.buttons.min.js',
            'extensions/Buttons/js/buttons.flash.min.js',
            'extensions/Buttons/js/buttons.html5.min.js',
            'extensions/Buttons/js/buttons.print.min.js'
        ];
    for (var i=0,style;style =styles[i++];) {
        document.write('<link type="text/css" rel="stylesheet" href="'+ baseURL + style +'"/>');
    }
    for (var i=0,script;script = scripts[i++];) {
        document.write('<script type="text/javascript" src="'+ baseURL + script +'"></script>');
    }
})();

