/*
TODO:
- Texto...
*/

CanvasRenderingContext2D.prototype.clearArc = function (x, y, radius, startAngle, endAngle, anticlockwise) {
    this.beginPath();
    this.arc(x, y, radius, startAngle, endAngle, anticlockwise);
    this.clear();
};


CanvasRenderingContext2D.prototype.clear = function () {
    this.save();
    this.globalCompositeOperation = 'destination-out';
    this.fillStyle = 'black';
    this.fill();
    this.restore();
};

; (function ($) {

    // here it goes!
    $.fn.canvasPaint = function (method) {
        
        /***************************
         * support multiple elements
         ***************************/
        if (this.length > 1){
            this.each(function() { $(this).myPlugin(options) });
            return this;
        }

        /*******************
         * PRIVATE VARIABLES
         *******************/

         var TOOLS = {
            "PEN"               : 0,
            "ERASER"            : 1,
            "LINE"              : 2,
            "EMPTY_RECTANGLE"   : 3,
            "RECTANGLE"         : 4,
            "EMPTY_CIRCLE"      : 5,
            "CIRCLE"            : 6,
            "TEXT"              : 7,
            "SPRAY"             : 8
        }

        var canvas = {
            background: {},
            current: {},
            common: {}, 
            urlBackground: "", 
            imgloaded: false
        }

        var state = {
            startDrawX: 0,
            startDrawY: 0,
            drawing: false,
            tool: TOOLS.PEN,
            lineThickness: 2,
            strokeColor: "red",
            fillColor: "red",
            sprayDensity: 5
        }

        var undoHistory = []
        var redoHistory = []

        var defaults = {
            maxUndoHistory: 10
        }

        var settings = {}

        /*******************
         * PRIVATE METHODS
         *******************/
        var drawStart = function (event) {
            canvas.current.context.beginPath();
                event.preventDefault();

                // Store the current x, y positions
                state.startDrawX = event.pageX - $(canvas.current.canvas).offset().left;
                state.startDrawY = event.pageY - $(canvas.current.canvas).offset().top;

                // Ponemos el color en el contexto
                canvas.current.context.lineWidth = state.lineThickness;

                switch(state.tool)
                {
                    case TOOLS.EMPTY_CIRCLE:                    
                    case TOOLS.EMPTY_RECTANGLE:
                    case TOOLS.RECTANGLE:
                    case TOOLS.CIRCLE:
                    case TOOLS.LINE:
                        canvas.current.context.fillStyle = state.fillColor;
                        canvas.current.context.strokeStyle = state.strokeColor;
                        break;
                    
                    
                   case TOOLS.PEN:
                   case TOOLS.SPRAY:
                        canvas.current.context.fillStyle = state.strokeColor;
                        canvas.current.context.strokeStyle = state.strokeColor;
                        break;

                    case TOOLS.ERASER:
                        break;
                }

                state.drawing = true;
            }
        
        var draw = function (event) {
            // Calculate the current mouse X, Y coordinates with canvas offset
            var x = event.pageX - $(canvas.current.canvas).offset().left;
            var y = event.pageY - $(canvas.current.canvas).offset().top;

            // If the mouse is down (drawning) then start drawing lines
            if (state.drawing) {
                switch(state.tool)
                {
                    /*
                        * CIRCULOS
                        */
                    case TOOLS.CIRCLE:
                    case TOOLS.EMPTY_CIRCLE:
                        // Limpio la pantalla
                        canvas.current.context.clearRect(0, 0, canvas.common.width, canvas.common.height);

                        // Comienzo el trazo
                        canvas.current.context.beginPath();

                        var radiusX = Math.max(x - state.startDrawX, state.startDrawX - x);
                        var radiusY = Math.max(y - state.startDrawY, state.startDrawY - y);
                        var centerX = state.startDrawX;
                        var centerY = state.startDrawY;

                        if (state.tool != TOOLS.EMPTY_CIRCLE) {
                            canvas.current.context.arc(centerX, centerY, Math.max(radiusX, radiusY), 2 * Math.PI, false);
                            canvas.current.context.fill();
                        }

                        canvas.current.context.arc(centerX, centerY, Math.max(radiusX, radiusY), 2 * Math.PI, false);
                        canvas.current.context.stroke();
                        break;

                    /*
                        * RECTANGULOS
                        */
                    case TOOLS.EMPTY_RECTANGLE:
                    case TOOLS.RECTANGLE:
                        // Limpio la pantalla
                        canvas.current.context.clearRect(0, 0, canvas.common.width, canvas.common.height);

                        var x1 = state.startDrawX;
                        var y1 = state.startDrawY;
                        var x2 = x - state.startDrawX;
                        var y2 = y - state.startDrawY;

                        canvas.current.context.strokeRect(x1, y1, x2, y2);
                        if (state.tool != TOOLS.EMPTY_RECTANGLE)
                            canvas.current.context.fillRect(x1, y1, x2, y2);
                        break;

                    /*
                        * LINEA
                        */
                    case TOOLS.LINE:
                        // Limpio la pantalla
                        canvas.current.context.clearRect(0, 0, canvas.common.width, canvas.common.height);
                        // Comienzo el trazo
                        canvas.current.context.beginPath();

                        canvas.current.context.moveTo(state.startDrawX, state.startDrawY);
                        canvas.current.context.lineTo(x, y);
                        canvas.current.context.stroke();
                        break;

                    /*
                        * LAPIZ
                        */
                    case TOOLS.PEN:
                        // Comienzo el trazo

                        canvas.current.context.lineTo(x, y);
                        canvas.current.context.lineWidth=state.lineThickness*2;
                        canvas.current.context.stroke();
                        canvas.current.context.beginPath();
                        canvas.current.context.arc(x, y, state.lineThickness, 0, Math.PI*2 , false);

                        canvas.current.context.fill();
                        canvas.current.context.beginPath();
                        canvas.current.context.moveTo(x,y);

                        break;

                    case TOOLS.ERASER:
                        // Comienzo el trazo
                        canvas.background.context.beginPath();

                        canvas.background.context.arc(x, y, state.lineThickness, 0, 2 * Math.PI, false);
                        canvas.background.context.clear();
                        break;

                    case TOOLS.SPRAY:
                        var offset = state.lineThickness / 2;
                        var sourceX = x - offset;
                        var sourceY = y - offset;
                        for(var i = 0 ; i < state.sprayDensity ; i++) {
                            var randomX = sourceX + Math.floor((Math.random()*offset)+1);
                            var randomY = sourceY + Math.floor((Math.random()*offset)+1);
                            canvas.current.context.fillRect(randomX, randomY, 1, 1);
                        }
                        break;
                }                    

            } else {
                // No estoy pintando
                
            }
        }

        var drawEnd = function (event) {
            if (state.drawing) {
                state.drawing = false;

                // Guardo el estado en la pila de historia
                undoHistory.push(canvas.background.context.getImageData(0, 0, canvas.common.width, canvas.common.height));
                if (undoHistory.length > settings.maxUndoHistory)
                    undoHistory.shift(); // Saco el primero
                //undoHistory.push(canvas.background.canvas.toDataURL("image/png"));

                // Mezclo
                canvas.background.context.drawImage(canvas.current.canvas, 0, 0);

                // Limpio la pantalla
                canvas.current.context.clearRect(0, 0, canvas.common.width, canvas.common.height);
            }
        }

        // Necesito saber si tiene soporte nativo
        var nativeSupport = function () {
            var el = document.createElement('canvas');
            return el.getContext != undefined;
        }


        /**********************
         * PUBLIC METHODS *****
         **********************/

        // this the constructor method that gets called when  the object is created
        this.initialize = function (options) {

            // the plugin's final properties are the merged default
            // and user-provided properties (if any)
            // this has the advantage of not polluting the defaults,
            // making the same instace re-usable with
            // new options; thanks to Steven Black for suggesting this
            settings = $.extend({}, defaults, options)

            // Primero ponerlo como posición relativa
            $(this).css("position", "relative");

            // He de crear 2 canvas. Uno es el fondo con el dibujo resultante y
            // otro está encima con lo que se está pintando en el momento
            canvas.common.width = $(this).width();
            canvas.common.height = $(this).height();
            canvas.common.container = this;

            canvas.background.canvas = document.createElement('canvas');
            $(canvas.background.canvas).attr("width", canvas.common.width);
            $(canvas.background.canvas).attr("height", canvas.common.height);
            $(canvas.background.canvas).css("position", "absolute");
            $(canvas.background.canvas).css("top", "0px");
            $(canvas.background.canvas).css("left", "0px");
            $(canvas.background.canvas).css("zIndex", 0);

            canvas.current.canvas = document.createElement('canvas');
            $(canvas.current.canvas).attr("width", canvas.common.width);
            $(canvas.current.canvas).attr("height", canvas.common.height);
            $(canvas.current.canvas).css("position", "absolute");
            $(canvas.current.canvas).css("top", "0px");
            $(canvas.current.canvas).css("left", "0px");
            $(canvas.current.canvas).css("zIndex", 1);

            $(this).append(canvas.background.canvas);
            $(this).append(canvas.current.canvas);

            /* Usando FlashCanvas para compatibilidad con IE8 e IE7 */
            if (typeof FlashCanvas != "undefined") {
                FlashCanvas.initElement(canvas.background.canvas);
                FlashCanvas.initElement(canvas.current.canvas);
            }

            // Initaliase a 2-dimensional drawing context
            canvas.background.context = canvas.background.canvas.getContext('2d');
            canvas.current.context = canvas.current.canvas.getContext('2d');

            // Mouse based interface
            $(canvas.current.canvas).bind('mousedown', drawStart);
            $(canvas.current.canvas).bind('mousemove', draw);
            $(canvas.current.canvas).bind('mouseup', drawEnd);
            $(canvas.current.canvas).bind('mouseleave', function () {
                    //ASPNETPaint.context.putImageData(ASPNETPaint.state.oldState, 0, 0);
            });
            $('body').bind('mouseup', drawEnd);
            
            return this;
        }

        this.activatePen = function () { state.tool = TOOLS.PEN;}
        this.activateEraser = function () { state.tool = TOOLS.ERASER; }
        this.activateEmptyCircle = function () { state.tool = TOOLS.EMPTY_CIRCLE; }
        this.activateCircle = function () { state.tool = TOOLS.CIRCLE; }
        this.activateEmptyRectangle = function () { state.tool = TOOLS.EMPTY_RECTANGLE; }
        this.activateRectangle = function () { state.tool = TOOLS.RECTANGLE; }
        this.activateLine = function () { state.tool = TOOLS.LINE; }
        this.activateSpray = function () { state.tool = TOOLS.SPRAY; }

        this.loadBackgroundImageEdited = function(imgUrl) {
            $(canvas.common.container).css("backgroundImage", "url('" + imgUrl + "')");
        }

        this.loadBackgroundImage = function (imgUrl) {
            canvas.urlBackground = imgUrl;

            // var outlineImage = new Image();
            // outlineImage.src = imgUrl;

            // var ctx = canvas.current.canvas.getContext('2d');

            // // canvas.background.context = canvas.background.canvas.getContext('2d');
            // // canvas.current.context = canvas.current.canvas.getContext('2d');
            // outlineImage.onload = function()    {
            //     canvas.background.context.drawImage(outlineImage, 0, 0, canvas.background.canvas.width, canvas.background.canvas.height);
            // }

            // var img = document.createElement('img');
            // var ctx = canvas.background.canvas.getContext ? canvas.background.canvas.getContext('2d') : null;
            
            // img.onload = function () {
            //     ctx.drawImage(img, 0, 0, canvas.background.canvas.width, canvas.background.canvas.height);
            // };

            // img.src = canvas.urlBackground;
            
            $(canvas.common.container).css("backgroundImage", "url('" + canvas.urlBackground + "')");
        }

        this.undo = function () {
            //If we have some restore points
            if (undoHistory.length > 0) {
                redoHistory.unshift(canvas.background.context.getImageData(0, 0, canvas.common.width, canvas.common.height));
                canvas.background.context.putImageData(undoHistory.pop(), 0, 0);                
                if (redoHistory.length > settings.maxUndoHistory)
                    redoHistory.pop();
                
            }
        }

        this.redo = function () {
            if (redoHistory.length > 0) {
                undoHistory.push(canvas.background.context.getImageData(0, 0, canvas.common.width, canvas.common.height));
                canvas.background.context.putImageData(redoHistory.shift(), 0, 0);                
                if (undoHistory.length > settings.maxUndoHistory)
                    undoHistory.unshift();
            }
        }

        this.changeFillColor = function (color) {
            state.fillColor = color;
        }

        this.changeStrokeColor = function (color) {
            state.strokeColor = color;
        }

        this.changeStrokeSize = function (size) {
            state.lineThickness = size
        }

        this.changeSprayDensity = function (density) {
            state.sprayDensity = density
        }

/********/
        this.preview = function ()  {
            var image = canvas.background.canvas.toDataURL("image/png");

            var WindowObject = window.open('', "PrintPaintBrush", "toolbars=no,scrollbars=yes,status=no,resizable=no");
            WindowObject.document.open();
            WindowObject.document.writeln('<img src="' + image + '"/>');
            WindowObject.document.close();
            WindowObject.focus();
            return;
        }

        this.setBackgroundTrue = function() {
            var imgB = new Image();
            var imgC = new Image();

            var dibujos = this.save();

            var ctx = canvas.background.canvas.getContext ? canvas.background.canvas.getContext('2d') : null;
            
            imgB.onload = function () {
                ctx.drawImage(imgB, 0, 0, canvas.background.canvas.width, canvas.background.canvas.height);
                imgC.src = "data:image/gif;base64," + dibujos;
            };

            imgC.onload = function () {
                ctx.drawImage(imgC, 0, 0, canvas.background.canvas.width, canvas.background.canvas.height);
            };

            imgB.src = canvas.urlBackground;
        }

        this.save = function () {
            image = canvas.background.canvas.toDataURL("image/png");
            image = image.replace('data:image/png;base64,', '');
            return image;
        }

/********/
        this.text = function () {
            alert("Not implemented !");
        }

        this.clearAll = function () {
            canvas.background.context.clearRect ( 0 , 0 , canvas.common.width , canvas.common.height );
            canvas.current.context.clearRect ( 0 , 0 , canvas.common.width , canvas.common.height );
            $(canvas.common.container).css("backgroundImage", "none");
        }

        this.changeOpacity = function (opacity) {
            canvas.current.context.globalAlpha = opacity / 100;
        }        
        
        return this.initialize();
    }

})(jQuery);