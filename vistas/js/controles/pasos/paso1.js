var arrComplete = "";

(function ($) {
    /////*validar*////
    $('.numerico').numeric();

    ///*autocomplete*/////
    $.widget("custom.combobox", {
        _create: function () {
            this.wrapper = $("<span>")
                    .addClass("custom-combobox")
                    .insertAfter(this.element);

            this.element.hide();
            this._createAutocomplete();
            this._createShowAllButton();
        },
        _createAutocomplete: function () {
            var selected = this.element.children(":selected"),
                    value = selected.val() ? selected.text() : "";

            this.input = $("<input>")
                    .appendTo(this.wrapper)
                    .val(value)
                    .attr("title", "")
                    .attr("style", "width:95%")
                    
//                    .addClass("custom-combobox-input ui-widget ui-widget-content ui-state-default ui-corner-left")
                    .autocomplete({
                        delay: 0,
                        minLength: 0,
                        source: $.proxy(this, "_source")
                    })
                    .tooltip({
                        tooltipClass: "ui-state-highlight"
                    });

            this._on(this.input, {
                autocompleteselect: function (event, ui) {
                    ui.item.option.selected = true;
                    this._trigger("select", event, {
                        item: ui.item.option
                    });
                },
                autocompletechange: "_removeIfInvalid"
            });
        },
        _createShowAllButton: function () {
            var input = this.input,
                    wasOpen = false;

            $("<a>")
                    .attr("tabIndex", -1)
                    .attr("title", "Mostrar todo")
                    .tooltip()
                    .appendTo(this.wrapper)
                    .button({
                        icons: {
                            primary: "ui-icon-triangle-1-s"
                        },
                        text: false
                    })
                    .removeClass("ui-corner-all")
                    .addClass("custom-combobox-toggle ui-corner-right")
                    .mousedown(function () {
                        wasOpen = input.autocomplete("widget").is(":visible");
                    })
                    .click(function () {
                        input.focus();

                        // Close if already visible
                        if (wasOpen) {
                            return;
                        }

                        // Pass empty string as value to search for, displaying all results
                        input.autocomplete("search", "");
                    });
        },
        _source: function (request, response) {
            var matcher = new RegExp($.ui.autocomplete.escapeRegex(request.term), "i");
            response(this.element.children("option").map(function () {
                var text = $(this).text();
                if (this.value && (!request.term || matcher.test(text)))
                    return {
                        label: text,
                        value: text,
                        option: this
                    };
            }));
        },
        _removeIfInvalid: function (event, ui) {

            // Selected an item, nothing to do
            if (ui.item) {
                return;
            }

            // Search for a match (case-insensitive)
            var value = this.input.val(),
                    valueLowerCase = value.toLowerCase(),
                    valid = false;
            this.element.children("option").each(function () {
                if ($(this).text().toLowerCase() === valueLowerCase) {
                    this.selected = valid = true;
                    return false;
                }
            });

            // Found a match, nothing to do
            if (valid) {
                return;
            }

            // Remove invalid value
            this.input
                    .val("")
                    .attr("title", " no se encontraron elementos que coincidan con: " + value + " ")
                    .tooltip("open");
            this.element.val("");
            this._delay(function () {
                this.input.tooltip("close").attr("title", "");
            }, 2500);
            this.input.autocomplete("instance").term = "";
        },
        _destroy: function () {
            this.wrapper.remove();
            this.element.show();
        }
    });
})(jQuery);

$(function () {
//    $('.numerico').keyup(function () {
//        this.value = (this.value + '').replace(/[^0-9]/g, '');
//    });
//    
 $('.numerico').numeric();
    llenarTipoAudiencia();
    $("#cmbAudiencia").combobox();
    llenarComboJuzgado();
    $("#cmbJuzgado").combobox();
    llenarcomboTipoCarpeta();
    $("#cmbTipocarpeta").combobox();
    llenarcomboConsignacion();
    $("#cmbConsignacion").combobox();

});


llenarTipoAudiencia = function () {
    $.ajax({
        type: "POST",
        url: "../../../../fachadas/sigejupe/autoresaudiencias/AutoresaudienciasFacade.Class.php",
        async: false,
        dataType: "json",
        data: {accion: "consultar", obligaPermiso: "false"},
        beforeSend: function (objeto) {
        },
        success: function (datos) {
            if (datos !== "") {
                arrComplete = "<select style='width:100%;' id='cmbAudiencia'>";
                arrComplete += "<option value=''>---SELECCIONE---";
                arrComplete += "</option>";
                for (var i = 0; i < datos.totalCount; i++) {
                    arrComplete += "<option value='" + datos.data[i].idAutorAudiencia + "'>";
                    arrComplete += datos.data[i].fechaRegistro;
                    arrComplete += "</option>";
                }
                arrComplete += "</select>";
            }
            $("#s2id_iId_Audiencia").html(arrComplete);
        },
        error: function (objeto, quepaso, otroobj) {
            alert("Error en la peticion de llenar Tipo Audiencia:\n\n" + otroobj);
        }
    });
}
llenarComboJuzgado = function () {
    $.ajax({
        type: "POST",
        url: "../../../../fachadas/sigejupe/juzgados/JuzgadosFacade.Class.php",
        async: false,
        dataType: "json",
        data: {accion: "consultar", obligaPermiso: "false"},
        beforeSend: function (objeto) {
        },
        success: function (datos) {
            if (datos !== "") {
                arrComplete = "<select  style='width:100%;' id='cmbJuzgado'>";
                arrComplete += "<option value=''>---SELECCIONE---";
                arrComplete += "</option>";
                for (var i = 0; i < datos.totalCount; i++) {
                    arrComplete += "<option value='" + datos.data[i].cveJuzgado + "'>";
                    arrComplete += datos.data[i].desJuzgado;
                    arrComplete += "</option>";
                }
                arrComplete += "</select>";
            }
            $("#s2id_iId_Juzgado").html(arrComplete);
        },
        error: function (objeto, quepaso, otroobj) {
            alert("Error en la peticion de llenar Tipo Audiencia:\n\n" + otroobj);
        }
    });
}

llenarcomboTipoCarpeta = function () {
    $.ajax({
        type: "POST",
        url: "../../../../fachadas/sigejupe/tiposcarpetas/TiposcarpetasFacade.Class.php",
        async: false,
        dataType: "json",
        data: {accion: "consultar", obligaPermiso: "false"},
        beforeSend: function (objeto) {
        },
        success: function (datos) {
            if (datos !== "") {
                arrComplete = "<select  style='width:100%;' id='cmbTipocarpeta'>";
                arrComplete += "<option value=''>---SELECCIONE---";
                arrComplete += "</option>";
                for (var i = 0; i < datos.totalCount; i++) {
                    arrComplete += "<option value='" + datos.data[i].cveTipoCarpeta + "'>";
                    arrComplete += datos.data[i].desTipoCarpeta;
                    arrComplete += "</option>";
                }
                arrComplete += "</select>";
            }
            $("#s2id_iId_TipoCarpeta").html(arrComplete);
        },
        error: function (objeto, quepaso, otroobj) {
            alert("Error en la peticion de llenar Tipo Audiencia:\n\n" + otroobj);
        }
    });
}
llenarcomboConsignacion = function () {
    $.ajax({
        type: "POST",
        url: "../../../../fachadas/sigejupe/consignaciones/ConsignacionesFacade.Class.php",
        async: false,
        dataType: "json",
        data: {accion: "consultar", obligaPermiso: "false"},
        beforeSend: function (objeto) {
        },
        success: function (datos) {
            if (datos !== "") {
                arrComplete = "<select  style='width:100%;' id='cmbConsignacion'>";
                arrComplete += "<option value=''>---SELECCIONE---";
                arrComplete += "</option>";
                for (var i = 0; i < datos.totalCount; i++) {
                    arrComplete += "<option value='" + datos.data[i].cveConsignacion + "'>";
                    arrComplete += datos.data[i].desConsignacion;
                    arrComplete += "</option>";
                }
                arrComplete += "</select>";
            }
            $("#s2id_iId_Consignacion").html(arrComplete);
        },
        error: function (objeto, quepaso, otroobj) {
            alert("Error en la peticion de llenar Tipo Audiencia:\n\n" + otroobj);
        }
    });
}


GuardarPaso1 = function () {
    var cveCatAudiencia = $("#cmbAudiencia").val();
    var cveJuzgado = $("#cmbJuzgado").val();
    var cveTipoCarpeta = $("#cmbTipocarpeta").val();
    var numero = $("#txtnumero").val();
    var anio = $("#txtAnio").val();
    var carpetaInv = $("#txtCarpetaInv").val();
    var nuc = $("#txtNuc").val();
    var cveConsignacion = $("#cmbConsignacion").val();
    var numImputados = $("#txtNoImputados").val();
    var numOfendidos = $("#txtNoOfendidos").val();
    var numDelitos = $("#txtNoDelitos").val();
    var observaciones = $("#txtObservaciones").val();

    $.ajax({
        type: "POST",
        url: "../../../../fachadas/sigejupe/solicitudesaudiencias/SolicitudesaudienciasFacade.Class.php",
        async: false,
        dataType: "html",
        data: {accion: "guardar", cveCatAudiencia: cveCatAudiencia, cveJuzgadoDesahoga: cveJuzgado, cveJuzgado: cveJuzgado,
            cveTipoCarpeta: cveTipoCarpeta, numero: numero, anio: anio, carpetaInv: carpetaInv, nuc: nuc,
            cveConsignacion: cveConsignacion, numImputados: numImputados, numOfendidos: numOfendidos,
            numDelitos: numDelitos, observaciones: observaciones},
        beforeSend: function (objeto) {
        },
        success: function (datos) {

            if (datos !== "") {
                alert(datos);

            }
        },
        error: function (objeto, quepaso, otroobj) {
            alert("Error en la peticion al guardar solicitud de audiencia:\n\n" + otroobj);
        }
    });
}
