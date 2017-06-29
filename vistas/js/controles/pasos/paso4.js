//script Paso 4
var InitiateStep4 = function () {
    return {
        delitos:[],
        init: function () {
            var self = this;
            //Inicia todas las funciones del Paso
            var paso = '#divPaso4';
            $(paso).on("select2-selecting","#iId_Delito", function(e){
                console.log(e);
                var p4_delitoId = e.choice.id;
                var p4_delitoDesc = e.choice.text;
                self.ActulalizaDelitos(p4_delitoId,p4_delitoDesc);                
            });
            $("#cuerpoDelitos").on('click', '.removeitem', function()	{
                var id = $(this).data('item');
                self.delitos.splice(id, 1);
                self.ActulalizaDelitos();
            });
        },
        SetConfigByInput: function(obj, input)	{
        	return input;
        },
        SetExtraInput: function(obj)	{
        	var extraInput = '';
        	return extraInput;
        },
               SetExtraDiv: function(obj)  {
            var div_extra = "";
            //lista de delitos
            if(obj.nombre == "iId_Delito"){
                div_extra = '<div class="form-group" id=""><div class="col-sm-12"><table class="table table-hover"><thead><th widht="10%">#</th><th widht="80%">DELITO</th><th widht="10%"><a class="btn btn-default btn-xs shiny icon-only danger" href="javascript:void(0);"><i class="fa fa-times "></i></a></th></thead><tbody id="cuerpoDelitos"></tbody></table></div></div>';
            }
            return div_extra;
        },
        ActulalizaDelitos: function(delitoId,delitoDesc){
            var listaDelitos = $('#inputListaDelitos');
            var delitoId = delitoId || 0;
            var delitoDesc = delitoDesc || '';

            if(delitoId != 0){
                var item_id = this.find_in_object(this.delitos, 'iId', delitoId);
                if(item_id > -1)	{
                        this.delitos.splice(item_id, 1);
                }
                this.delitos.push({iId:delitoId, text:delitoDesc});
            }
            $("#cuerpoDelitos").html("");
                $.each(this.delitos, function(k,v){
                var td_id = $("<td/>", {
                                html: (k+1)
                        });
                var td_delito = $("<td/>", {
                        html: v.text
                        });
                var td_eliminar = $("<td/>",	{
                                html: '<a class="btn btn-default btn-xs shiny icon-only danger removeitem" data-item="' + k + '" href="javascript:void(0);"><i class="fa fa-times "></i></a>'
                        });
                var tr = $("<tr/>").append(td_id).append(td_delito).append(td_eliminar);
                $("#cuerpoDelitos").append(tr);
                });
        },
        find_in_object: function(array, property, value) {
            var index = array.map(function(d) { return d[property]; }).indexOf(value);
            return index;
        }
    };

}();

