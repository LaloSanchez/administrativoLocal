posValue = function (event) {/*CAMBIO DE FOCO AL PRESIONAR EL ENTER*/
    //if (event.shiftKey)
    //    alert(event.preventDefault());
    if (event.keyCode == 46 || event.keyCode == 8 || event.keyCode == 9 || event.keyCode == 34) {
    } else if (event.keyCode == 13) {
        try {
            var id = "cmb" + this.id.substring(6, this.id.lentgh);
            var cmb = document.getElementById(id);
            cmb.value = this.value.toUpperCase();
            if (cmb.value == "0") {
                alert("La clave no se encontro");
                document.getElementById(this.id).focus();
                document.getElementById(this.id).select();
            } else {
                var tabIndex = parseInt($(this).attr('tabindex'));
                if ($(':input[tabindex=\'' + (tabIndex + 1) + '\']') != null) {
                    $(':input[tabindex=\'' + (tabIndex + 1) + '\']').focus();
                    $(':input[tabindex=\'' + (tabIndex + 1) + '\']').select();
                    return false;
                }
            }

        } catch (e) {
            var tabIndex = parseInt($(this).attr('tabindex'));
            if ($(':input[tabindex=\'' + (tabIndex + 1) + '\']') != null) {
                $(':input[tabindex=\'' + (tabIndex + 1) + '\']').focus();
                $(':input[tabindex=\'' + (tabIndex + 1) + '\']').select();
                return false;
            }
        }

    }
}

ajustar = function (object) {
    var doc = object.contentDocument ? object.contentDocument
            : object.contentWindow.document;

    var h = getDocHeight(doc);
    h = (h * .2) + h
    object.height = h;//getDocHeight(doc);//+ (getDocHeight(doc) * .2)
//    alert(object.height);
}

getDocHeight = function (doc) {
    doc = doc || document;
    var body = doc.body;
    var html = doc.documentElement;
//    alert("body.offsetHeight: " + body.offsetHeight + " html.clientHeight: " + html.clientHeight + " html.offsetHeight: " + html.offsetHeight + " body.scrollHeight:" +body.scrollHeight  + "html.scrollHeight:" + html.scrollHeight);
    if ((body.offsetHeight > 0) || (html.offsetHeight > 0)) {
        var height = (body.offsetHeight > 0) ? body.offsetHeight : html.offsetHeight;
    } else {
        var height = html.clientHeight;
    }
//    var height = Math.max( body.offsetHeight,
//            html.clientHeight, html.offsetHeight);//body.scrollHeight, html.scrollHeight
//            alert(height);
    return height;
}

jQuery.fn.resetForm = function () {
    $(this).each(function () {
        this.reset();
    });
};

jQuery.fn.Trim = function () {
    var strTexto = $(this).val().replace(/^\s*|\s*$/g, "");
    $(this).val(strTexto);
};

$.urlParam = function (name) {
    var results = new RegExp('[\\?&]' + name + '=([^&#]*)').exec(window.location.href);
    if (results == null) {
        return null;
    } else {
        return results[1] || 0;
    }
};


/**
 * Valida la entrada exclusiva para nUmeros
 * @param {object} event Es el objeto del evento en Keypress
 * @return {boolean}  
 */
function validateNumber(event) {
    var key = window.event ? event.keyCode : event.which;
    if (event.keyCode == 8 || event.keyCode == 46 || event.keyCode == 37 || event.keyCode == 39 || event.keyCode == 9) {
        return true;
    } else if (key < 48 || key > 57) {
        return false;
    } else
        return true;
}
;

function validarAcentos(event) { // 1
    var tecla = (document.all) ? event.keyCode : event.which; // 2
    if (event.keyCode == 8 || event.keyCode == 37 || event.keyCode == 39 || event.keyCode == 9) {
	return true;
    } else { // 3
	var patron = /^[A-Z\u00C0-\u00ff a-z\u00C0-\u00ff]/; // expresión regular para letras(máy o minus), acentuadas o no, y números // 4
    var te = String.fromCharCode(tecla); // 5
	var l = patron.test(te);
    return patron.test(te);

    }// 6
};

function validar(e) { // 1
    var tecla = (document.all) ? e.keyCode : e.which; // 2
    if (tecla == 8) {
    return true;
    } else { // 3
    var patron = /[a-zA-Z]/; // 4
    var te = String.fromCharCode(tecla); // 5

    return patron.test(te);

    }// 6
};

/**
 *FunciOn para la correcciOn del formato de fecha
 * @param {datetime} date Recibe la fecha y hora en formato YYYY-MM-DD HH:MM:SS
 * @param {datetime} dateTime Regresa la fecha y hora en formato DD/MM/YYY HH:MM:SS
 */
function dateFormat(date) {
    var dateTime = date.split(' ');
    var tmpDate = dateTime[0].split('-');
    return dateTime = tmpDate[2] + '/' + tmpDate[1] + '/' + tmpDate[0] + ' ' + dateTime[1];
}

(function (a) {
    a.fn.validaCampo = function (b) {
        a(this).on({keypress: function (a) {
                var c = a.which, d = a.keyCode, e = String.fromCharCode(c).toLowerCase(), f = b;
                (-1 != f.indexOf(e) || 9 == d || 37 != c && 37 == d || 39 == d && 39 != c || 8 == d || 46 == d && 46 != c) && 161 != c || a.preventDefault()
            }})
    }
})(jQuery);

jQuery.fn.mueveScroll = function () {
    $('html,body').animate({scrollTop: Obj.offset().top}, 1000);
};

jQuery.fn.Mayusculas = function () {
    $(this).bind("blur", null, function (e) {
        $(this).val($(this).val().toUpperCase());
    });
};

var getCateosOrdenesPendientes = function (type) {
    console.log("get ordenes Pendientes");
    var datosSend = {
        "accion": "getCateosPendientes"
    }
    $(".cateosPendientes").html("").css({"left": "-35%"}).stop().hide();
    $(".ordenesPendientes").html("").css({"bottom": "-35%"}).stop().hide();
    var urlToSend = "";
    if (type == 1)
        urlToSend = "../fachadas/sigejupe/cateos/CateosFacade.Class.php";
    else
        urlToSend = "../fachadas/sigejupe/ordenes/OrdenesFacade.Class.php";
    $.ajax({
        type: "POST",
        url: urlToSend,
        data: datosSend,
        async: true,
        global: false,
        success: function (datos) {
            try {
                var data = eval("(" + datos + ")");
                if (data.type = "OK") {
                    var msg = "";
                    var msgnotify = "";
                    var table = "<div class='table-responsive' ><table class='table' >";
                    if (type == 1)
                        table += "<thead><tr><th colspan='2' >CATEOS PENDIENTES</th></tr></thead><tbody>";
                    else
                        table += "<thead><tr><th colspan='2' >ORDENES DE APREHENSI&Oacute;N PENDIENTES</th></tr></thead><tbody>";
                    $.each(data.data, function (index, val) {
                        table += "<tr>";
                        table += "<td>" + val.total + "</td>";
                        table += "<td>" + val.desJuzgado + "</td>";
                        table += "</tr>";
                        msg += '<span class="badge">' + val.total + '</span>' + ' ' + val.desJuzgado + '<br>';
                        msgnotify += '' + val.total + '' + ' ' + val.desJuzgado + '\n';
                    });

//                    alert("Notify")
                    table += "</tbody></table></div>";
                    if (type == 1) {
                        notify({
                            type: "error", //alert | success | error | warning | info
                            title: "CATEOS PENDIENTES",
                            message: msg,
                            position: {
                                x: "right", //right | left | center
                                y: "top" //top | bottom | center
                            },
                            icon: '<img src="img/alert.png" />', //<i>
                            size: "normal", //normal | full | small
                            overlay: false, //true | false
                            closeBtn: true, //true | false
                            overflowHide: false, //true | false
                            spacing: 20, //number px
                            theme: "dark-theme", //default | dark-theme
                            autoHide: true, //true | false
                            delay: 25000, //number ms
                            onShow: null, //function
                            onClick: null, //function
                            onHide: null, //function
                            template: '<div class="notify"><div class="notify-text"></div></div>'
                        });
                        notifyMe("CATEOS PENDIENTES", msgnotify);

                    } else {
                        notify({
                            type: "info", //alert | success | error | warning | info
                            title: "ORDENES DE APREHENSI&Oacute;N PENDIENTES",
                            message: msg,
                            position: {
                                x: "right", //right | left | center
                                y: "top" //top | bottom | center
                            },
                            icon: '<img src="img/alert.png" />', //<i>
                            size: "normal", //normal | full | small
                            overlay: false, //true | false
                            closeBtn: true, //true | false
                            overflowHide: false, //true | false
                            spacing: 20, //number px
                            theme: "dark-theme", //default | dark-theme
                            autoHide: true, //true | false
                            delay: 25000, //number ms
                            onShow: null, //function
                            onClick: null, //function
                            onHide: null, //function
                            template: '<div class="notify"><div class="notify-text"></div></div>'
                        });
                        notifyMe("ORDENES DE APREHENSI\xD3N PENDIENTES", msgnotify);
//                        $(".ordenesPendientes").html(table).show();
//                        $(".ordenesPendientes").animate({bottom: "50px"}).delay(10000).animate({bottom: "-35%"});
                    }
                }
            } catch (e) {

            }
        }
    });
}

var getApelacionPendientes = function (type) {
    var datosSend = {
        "accion": "getApelacionesPendientes"
    }
    var urlToSend = "";
    urlToSend = "../fachadas/sigejupe/apelacioncateos/ApelacioncateosFacade.Class.php";
    $.ajax({
        type: "POST",
        url: urlToSend,
        data: datosSend,
        async: true,
        global: false,
        success: function (datos) {
            try {
                var data = eval("(" + datos + ")");
                if (data.type = "OK") {
                    var msg = "";
                    var msgnotify = "";
                    var table = "<div class='table-responsive' ><table class='table' >";
                    table += "<thead><tr><th colspan='2' >APELACI&Oacute;N CATEOS PENDIENTES</th></tr></thead><tbody>";
                    $.each(data.data, function (index, val) {
                        table += "<tr>";
                        table += "<td>" + val.total + "</td>";
                        table += "<td>" + val.desJuzgado + "</td>";
                        table += "</tr>";
                        msg += '<span class="badge">' + val.total + '</span>' + ' ' + val.desJuzgado + '<br>';
                        msgnotify += '' + val.total + '' + ' ' + val.desJuzgado + '\n';
                    });

//                    alert("Notify")
                    table += "</tbody></table></div>";

                    notify({
                        type: "info", //alert | success | error | warning | info
                        title: "APELACI&Oacute;N CATEOS PENDIENTES",
                        message: msg,
                        position: {
                            x: "right", //right | left | center
                            y: "top" //top | bottom | center
                        },
                        icon: '<img src="img/alert.png" />', //<i>
                        size: "normal", //normal | full | small
                        overlay: false, //true | false
                        closeBtn: true, //true | false
                        overflowHide: false, //true | false
                        spacing: 20, //number px
                        theme: "dark-theme", //default | dark-theme
                        autoHide: true, //true | false
                        delay: 25000, //number ms
                        onShow: null, //function
                        onClick: null, //function
                        onHide: null, //function
                        template: '<div class="notify"><div class="notify-text"></div></div>'
                    });
                    notifyMe("APELACION\xD3N CATEOS PENDIENTES", msgnotify);
                }
            } catch (e) {

            }
        }
    });
}

var getMuestrasPendientes = function (type) {
    var datosSend = {
        "accion": "getMuestrasPendientes"
    }
    var urlToSend = "";
    urlToSend = "../fachadas/sigejupe/respuestamuestra/RespuestamuestraFacade.Class.php";
    $.ajax({
        type: "POST",
        url: urlToSend,
        data: datosSend,
        async: true,
        global: false,
        success: function (datos) {
            try {
                var data = eval("(" + datos + ")");
                if (data.type = "OK") {
                    var msg = "";
                    var msgnotify = "";
                    var table = "<div class='table-responsive' ><table class='table' >";
                    table += "<thead><tr><th colspan='2' >TOMA DE MUESTRAS PENDIENTES</th></tr></thead><tbody>";
                    $.each(data.data, function (index, val) {
                        table += "<tr>";
                        table += "<td>" + val.total + "</td>";
                        table += "<td>" + val.desJuzgado + "</td>";
                        table += "</tr>";
                        msg += '<span class="badge">' + val.total + '</span>' + ' ' + val.desJuzgado + '<br>';
                        msgnotify += '' + val.total + '' + ' ' + val.desJuzgado + '\n';
                    });

//                    alert("Notify")
                    table += "</tbody></table></div>";

                    notify({
                        type: "info", //alert | success | error | warning | info
                        title: "TOMA DE MUESTRAS PENDIENTES",
                        message: msg,
                        position: {
                            x: "right", //right | left | center
                            y: "top" //top | bottom | center
                        },
                        icon: '<img src="img/alert.png" />', //<i>
                        size: "normal", //normal | full | small
                        overlay: false, //true | false
                        closeBtn: true, //true | false
                        overflowHide: false, //true | false
                        spacing: 20, //number px
                        theme: "dark-theme", //default | dark-theme
                        autoHide: true, //true | false
                        delay: 25000, //number ms
                        onShow: null, //function
                        onClick: null, //function
                        onHide: null, //function
                        template: '<div class="notify"><div class="notify-text"></div></div>'
                    });
                    notifyMe("TOMA DE MUESTRAS PENDIENTES", msgnotify);
                }
            } catch (e) {

            }
        }
    });
}

/**
 * Muestra mensajes personalizados en el div destinado para ello
 * @param {string} message Es el mensaje que se mostrarA
 * @param {string} type Es el tipo de mensaje. 1:success, 2:warning, 3:information, 4:error
 * @param {string} divAux Es el postfijo para identificar un DIV alterno de notificaciOn
 * @param {string} extra DIV alterno de notificaciOn
 */
function muestraMensaje(message, type, divAux, extra) {
    var message = message || '';
    var div_message = '';
    var divAux = divAux || '';
    var extra = extra || '';
    var state = 0, icon = '', color = '';
    switch (type) {
        case 'success':
            div_message = 'divCorrecto';
            icon = 'glyphicon glyphicon-ok';
            color = 'green';
            break;
        case 'warning':
            div_message = 'divAdvertencia';
            icon = 'glyphicon glyphicon-remove';
            color = 'red';
            state = 1;
            break;
        case 'information':
            div_message = 'divInformacion';
            icon = 'glyphicon glyphicon-remove';
            color = 'red';
            break;
        case 'error':
            div_message = 'divError';
            icon = 'glyphicon glyphicon-remove';
            color = 'red';
            break;
    }
    if (divAux != '') {
        div_message += divAux;
        if (type == 'success') {
            $("#divInformacion" + divAux).hide();
            //$("#" + div_message).hide();
        }
        if (type == 'information') {
            $("#divCorrecto" + divAux).hide();
            $('#' + div_message).html(message);
            $('#' + div_message).hide().show("slide");
        }
    } else if (extra != '') {
        $('#' + extra).hide().empty().show("slide").append('<span style="color:' + color + ';" class="' + icon + '" aria-hidden="true"></span> ' + message);
        div_message = extra;
    } else {
        $('#' + div_message).html(message);
        $('#' + div_message).hide().show("slide");
        var aux = document.createElement("A");
        aux.setAttribute("href", "#"+div_message);
      
    }

    /*
     * crear ancla a alerta
     * var x = document.createElement("A");
     * $('html, body').animate({
		        scrollTop: $($(x).attr('href')).offset().top
		    }, 2000);
     */
    setTimeout(function () {
        $("#" + div_message).hide("slide");
    }, 5000);
    return;
}

/**
 * FunciOn para la obtenciOn de informacion de tablas y llenado de combos
 * @param {array} selectIds Ids de los combos donde se mostraran los datos
 * @param {string} facade Es la ruta de la fachada que se invoca, solo se define la carpeta y el nombre del archivo sin la extensiOn Class en adelante. Ej. tiposcarpetas/TiposcarpetasFacade
 * @param {string} value Es el identificador del campo llave
 * @param {string} descripction Es el identificador del campo de descripciOn
 */
llenaCombo = function (selectIds, facade, activo, value, description, selected, mensaje) {
    var selected = selected || '';
    var mensaje = mensaje || '**NO DEFINIDO**'
    $.each(selectIds, function (k, v) {
        $('#' + v).empty();
    });
    $.post('../fachadas/sigejupe/' + facade + '.Class.php',
            {activo: activo, accion: 'consultar'},
            function (response) {
                var object = eval("(" + response + ")");
                var options = '';
                var selectedOption = '';
                if (object.totalCount > 0) {
                    options = '<option value="0">--SELECCIONE--</option>';
                    $.each(object.data, function (k, v) {
                        selectedOption = (v[value] == selected) ? " selected" : "";
                        options += '<option value="' + v[value] + '" ' + selectedOption + '>' + v[description] + '</option>';
                    });
                } else {
                    options = '<option value="0">--SIN REGISTROS--</option>';
                    alert('No existen registros para: ' + mensaje);
                }
                $.each(selectIds, function (k, v) {
                    $('#' + v).append(options);
                });
            });
    return null;
}

/*
 * Permite crear los combos
 * de manera generica utilizando
 * chosen como buscador
 * 
 * @param combos 'Indica los nombres de selects
 *  en los cuáles se incluirán los resultados 
 *  @param clave Es la clave que se va a almacenar en el value
 *  @param valor Valor que va aparecer en el text
 *  @param condiciones Condiciones para la consulta
 */



function ComboGenericoConCampo(combos, clave, valor, condiciones,valorSeleccionado,campo){
 
     $.each(combos, function (k, v) {
        $('#' + v).empty();
    });
            $.ajax({
        url: "../fachadas/generic/GenericFachada.Class.php",
        data: condiciones,
        type: "POST",
        async: false,
        global: false,
        dataType: "json"
        , error: function () {
            $.each(combos, function (k, v) {
                $('#' + v).append('<option value="">Seleccione una opci&oacute;n</option>');
                $('#' + v).trigger("chosen:updated");
            });
            muestraMensaje("Error en la conexi&oacute;n","error");
        }
    }).done(function (json) {
        
        var options = '';
        var sel=0;
        try {
            $.each(json.data, function (k, v) {
                
                eval("options += '<option data-campo=' + v."+campo+" +' value=\"' + v." + clave + " + '\">'+ v."+ campo + "+ '   ' +   v." + valor + " + '</option>'");
                var x = eval("v."+campo+" ");
                if(valorSeleccionado== x){
                   
                    sel = eval("v."+clave+" ");
                   
                }
            });
            $.each(combos, function (k, v) {
                $('#' + v).append(options);
                $('#' + v).val(sel);
                $('#' + v).trigger("chosen:updated");
            });
        } catch (e) {
            $.each(combos, function (k, v) {
                $('#' + v).append(options);
                $('#' + v).trigger("chosen:updated");
            });
        }
    });
        }


/*
 * Permite crear los combos
 * de manera generica utilizando
 * chosen como buscador
 * 
 *  @param combos 'Indica los nombres de selects
 *  en los cuáles se incluirán los resultados 
 *  @param clave Es la clave que se va a almacenar en el value
 *  @param valor Valor que va aparecer en el text
 *  @param condiciones Condiciones para la consulta
 *  @valorSeleccionado el valor que aparecera como seleccionado
 *  @campo data-campo
 *  @extraData arreglo que permite colocar data-extra1="X" data-extra2="X" etc.
 */



function ComboGenericoConMultipleCampo(combos, clave, valor, condiciones,valorSeleccionado,campo,extraData){
 
    $.each(combos, function (k, v) {
        $('#' + v).empty();
    });
    $.ajax({
        url: "../fachadas/generic/GenericFachada.Class.php",
        data: condiciones,
        type: "POST",
        async: false,
        global: false,
        dataType: "json"
        , error: function () {
            $.each(combos, function (k, v) {
                $('#' + v).append('<option value="">Seleccione una opci&oacute;n</option>');
                $('#' + v).trigger("chosen:updated");
            });
            muestraMensaje("Error en la conexi&oacute;n","error");
        }
    }).done(function (json) {

        var options = '<option value="">Seleccione una opci&oacute;n</option>';
        var sel=0;
        try {
            $.each(json.data, function (k, v) {

                var extras = '';
                $.each(extraData,function (key, extra) {
                    var tmp = eval("v."+extra+" ");
                    extras += " data-"+extra+"=\""+tmp+"\" ";
                });


                eval("options += '<option "+extras+" data-campo=' + v."+campo+" +' value=\"' + v." + clave + " + '\">'+ v."+ campo + "+ '   ' +   v." + valor + " + '</option>'");
                var x = eval("v."+campo+" ");
                if(valorSeleccionado== x){

                    sel = eval("v."+clave+" ");

                }
            });
            $.each(combos, function (k, v) {
                $('#' + v).append(options);
                $('#' + v).val(sel);
                $('#' + v).trigger("chosen:updated");
            });
        } catch (e) {
            $.each(combos, function (k, v) {
                $('#' + v).append(options);
                $('#' + v).trigger("chosen:updated");
            });
        }
    });
}




/*
 * Permite crear los combos
 * de manera generica utilizando
 * chosen como buscador
 * 
 * @param combos 'Indica los nombres de selects
 *  en los cuáles se incluirán los resultados 
 *  @param clave Es la clave que se va a almacenar en el value
 *  @param valor Valor que va aparecer en el text
 *  @param condiciones Condiciones para la consulta
 */
function comboGenerico(combos, clave, valor, condiciones,valorSeleccionado) {
    $.each(combos, function (k, v) {
        $('#' + v).empty();
    });
    $.ajax({
        url: "../fachadas/generic/GenericFachada.Class.php",
        data: condiciones,
        type: "POST",
        async: false,
        dataType: "json"
        , error: function () {
            $.each(combos, function (k, v) {
                $('#' + v).append('<option value="">Seleccione una opci&oacute;n</option>');
                $('#' + v).trigger("chosen:updated");
            });
            muestraMensaje("Error en la conexi&oacute;n","error");
        }
    }).done(function (json) {
        var options = '<option value="">Seleccione una opci&oacute;n</option>';
        try {
            $.each(json.data, function (k, v) {
                eval("options += '<option value=\"' + v." + clave + " + '\">' + v." + valor + " + '</option>'");
            });
            $.each(combos, function (k, v) {
                $('#' + v).append(options);
                $('#' + v).val(valorSeleccionado);
                $('#' + v).trigger("chosen:updated");
            });
        } catch (e) {
            $.each(combos, function (k, v) {
                $('#' + v).append(options);
                $('#' + v).trigger("chosen:updated");
            });
        }
    });
    
}

/*
*Funci&oacute;n para guardar un registro
* @param string {datos} cadena compuesta por los valores del formulario
* @param string {nombreBoton} cadena compuesta el nombre del bot&oacute;n que estará cargandose al momento de dar clic en Guardar
*/
  function guardarGenerico(datos,nombreBoton){
      var resultado= "";
        $.ajax({
        url: "../fachadas/generic/GenericFachada.Class.php",
        data: datos,
            type: "POST",
            async: false,
            global: false,
            dataType: "json",
            beforeSend: function () {
                $('#'+ nombreBoton).button('loading');
            },
            error: function () {
               $('#' + nombreBoton).button('reset');       
               muestraMensaje('<i class="fa fa-times-circle" aria-hidden="true"></i>Error, no se logr&oacute; realizar la transacci&oacute;n, verifique e intente nuevamente.','error');
            }
        }).done(function (response) {
                if (response != "" && response.status=="success") {
                    muestraMensaje('<i class="fa fa-check-circle" aria-hidden="true"></i> &Eacute;xito al guardar la informaci&oacute;n','success');
                    Consultar();
                }else{
                    muestraMensaje('<i class="fa fa-times-circle" aria-hidden="true"></i>Error, no se logr&oacute; realizar la transacci&oacute;n, verifique e intente nuevamente.','error');
                }
                $('#' + nombreBoton).button('reset');
                resultado = response;
            });
            
            return resultado;
            };
/*
*Funci&oacute;n para guardar un registro
* @param string {datos} cadena compuesta por los valores del formulario
* @param string {nombreBoton} cadena compuesta el nombre del bot&oacute;n que estará cargandose al momento de dar clic en Guardar
*/
  function consultarGenerico(datos){
      var resultado= "";
        $.ajax({
        url: "../fachadas/generic/GenericFachada.Class.php",
        data: datos,
            type: "POST",
            async: false,
            global:false,
            dataType: "json",
            beforeSend: function () {
                
            },
            error: function () {
                  
               muestraMensaje('<i class="fa fa-times-circle" aria-hidden="true"></i>Error, no se logr&oacute; realizar la transacci&oacute;n, verifique e intente nuevamente.','error');
            }
        }).done(function (response) {
                resultado = response;
            });
            
            return resultado;
            };
            
      moneda = function (value) {
    var number = value.toString();
    var pos = number.indexOf(".");
    var decimales = "";
    var signo="";
    if (value<0) {
        number = number.substr(1, number.length);
        signo="-";
    }
    if (pos > 0) {
        decimales = number.substr(pos, number.length);
        number = number.substr(0, pos);
    }
    var result = '';
    while (number.length > 3) {
        result = ',' + number.substr(number.length - 3) + result;
        number = number.substring(0, number.length - 3);
    }
    result = number + result;
            
    return signo+result + decimales;     
};


function remplazarCaracter(cadena, valor, remplazo) {
//    var cadena=cadena;
    while (cadena.indexOf(valor) != -1) {
        cadena = cadena.replace(valor, remplazo);
    }
    return cadena;
//    return cadena.replace(new RegExp(valor, 'g'), remplazo);
}
function formatoMoneda(obj, e) {
    var key = window.Event ? e.which : e.keyCode;
    var aux = obj.value;
    if (key == 9 || key == 0) {
        return true;
    } else if (key == 46) {
        if (aux.indexOf('.') == -1) {
            return true;
        }
    } else if (key == 8) {
        aux = aux.substr(0, aux.length - 1).toString();
        aux = remplazarCaracter(aux, ',', '');
//        aux = aux.replace(new RegExp(',', 'g'), '');
        obj.value = moneda(aux);
    } else if (key >= 48 && key <= 57) {
        aux += String.fromCharCode(key).toString();
        aux = remplazarCaracter(aux, ',', '');
        obj.value = moneda(aux);
    }
    return false;
}
function formatoMonedaEntero(obj, e) {
    var key = window.Event ? e.which : e.keyCode;
    var aux = obj.value;
    if (key == 9 || key == 0) {
        return true;
    } else if (key == 8) {
        aux = aux.substr(0, aux.length - 1).toString();
        aux = remplazarCaracter(aux, ',', '');
//        aux = aux.replace(new RegExp(',', 'g'), '');
        obj.value = moneda(aux);
    } else if (key >= 48 && key <= 57) {
        aux += String.fromCharCode(key).toString();
        aux = remplazarCaracter(aux, ',', '');
        obj.value = moneda(aux);
    }
    return false;
}

function convertirMes(num) {
            var mes = "";
            switch (parseInt(num)) {

                case 1:

                    mes = "Enero";
                    break;
                case 2:
                    mes = "Febrero";
                    break;
                case 3:
                    mes = "Marzo";
                    break;
                case 4:
                    mes = "Abril";
                    break;
                case 5:
                    mes = "Mayo";
                    break;
                case 6:
                    mes = "Junio";
                    break;
                case 7:
                    mes = "Julio";
                    break;
                case 8:
                    mes = "Agosto";
                    break;
                case 9:
                    mes = "Septiembre";
                    break;
                case 10:
                    mes = "Octubre";
                    break;
                case 11:
                    mes = "Noviembre";
                    break;
                case 12:
                    mes = "Diciembre";
                    break;

            }
            return mes;

        }

        function convertirFecha(date) {
            var newdate = date.split("");
            var nuevafecha = newdate[8] + newdate[9] + "/" + newdate[5] + newdate[6] + "/" + newdate[0] + newdate[1] + newdate[2] + newdate[3];
            return nuevafecha;
        }
        
        fechaVista = function (fecha) {
            var fechaVista = fecha.split(" ");
            return fechaVista[0].split("-")[2] + "/" + fechaVista[0].split("-")[1] + "/" + fechaVista[0].split("-")[0];
        }
            