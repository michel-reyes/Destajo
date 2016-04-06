/*
DESTAJO-MODULE

date: 2014.12.17
type: js module
path: js/main.js

DESTAJO-MODULE-END
*/
/*    -------------------------------------------------------------
    Arreglar conflictos entre Select2 y Jquery UI
    -------------------------------------------------------------    */
$.ui.dialog.prototype._allowInteraction = function(e) {
	return !!$(e.target).closest('.ui-dialog, .ui-datepicker, .select2-drop').length;
};

/*
 * Sobre escribir los titles de jquery ui para que acepten HTML
 */
$.widget("ui.dialog", $.extend({}, $.ui.dialog.prototype, {
    _title: function(title) {
        if (!this.options.title ) {
            title.html("&#160;");
        } else {
            title.html(this.options.title);
        }
    }
}));

/*    -------------------------------------------------------------
    Variables globales
    -------------------------------------------------------------    */
var swindow = $(window),
    swinH = swindow.outerHeight(true),
    swinW = swindow.outerWidth(true),
    base_url = "http://localhost/destajo/";


/*    -------------------------------------------------------------
    Auto-inicio
    Controla el inicio de algunos elementos de la UI
    -------------------------------------------------------------    */
layout();
initForm();

// Mostrar mensaje de error si el usuario no obtuvo el acceso a algun modulo
var noaccess = $( 'input[name=noaccess_redirect]' );
if (noaccess.length) {
    alertify.log(noaccess.val(), "", 10000);
    noaccess.remove();
}

// Cargar contenido principal
var autoLoad = $('#table-fixed-content');
if ( autoLoad.length ) {
    // Cargar contenido 
    loadContent( base_url + $.cookie("modulo") + "/show_content" );
}


// Ordenar tabla ascendente / descendente
$('body').on('click', '.fht-thead th a', function(e) {
    e.preventDefault();
    // Cargar contenido 
    loadContent($(this).attr("href"));       
});


// Evitar ir a un modulo cuando el link esta deshabilitado
$('body').on('click', '.dropdown-menu > li.disabled > a', function(e) {
    e.preventDefault();
});


/*    -------------------------------------------------------------
    Paginacion
    Gestionar los controles de paginacion
    -------------------------------------------------------------    */
   
/**
 * Ir a la pagina siguiente o a la anterior 
 */
$('body').on('click', '#paginacion-group a.paginacion-link', function(e){
    e.preventDefault();
    // Cargar contenido
    loadContent($(this).attr('href')); 
});

/**
 * Ir a una pagina especifica mediante el listado de paginas 
 */
$('body').on('click', '.paginacion-content > li > a', function(e){      
    e.preventDefault();
    // Cargar contenido
    loadContent(cPathX($(this).attr('data-paginacion'), $(this).attr('href'))); 
});


/*    -------------------------------------------------------------
    Nuevo, Editar (GUARDAR), Eliminar, AutoCalcular
    Gestionar los dialogos para ejecutar las accciones principales
    -------------------------------------------------------------    */
   
/**
 * NUEVO 
 */

// Inicializar dialog
$( "#dialog-nuevo" ).dialog({
    autoOpen: false,
    closeOnEscape: true,
    modal: true,
    position: { my: "top", at: "top+10", of: window },
    resizable: false,
    width: "auto",
    maxHeight: swinH-50,
    buttons: [ 
        { 
            text: "Guardar",
            addClass: "btn btn-primary",
            click: function() { 
                 mainForm.validate();
            }            
        },
        {
            text: "Cancelar",
            addClass: "btn",
            click: function() {
                $( this ).dialog( "close" ); 
            }            
        } 
    ],      
    open: function( event, ui ) {                   
         // Desbloquear la ventana
        messageMachine.desbloquear();
        initForm();     
    },
    close: function( event, ui ) {
        $(this).empty();
    }
});

// Llamar al dialog
$('body').on('click', '.comando-nuevo', function(e) {
    e.preventDefault();
            
    // Bloquear la ventana
    messageMachine.bloquear();

    // Cargar el contenido de agregar en el dialog       
    $('#dialog-nuevo').load($.cookie('modulo') + '/agregar', function() { 
        // Abrir el dialog                      
        $('#dialog-nuevo').dialog( "open" );
        $('#dialog-nuevo').dialog('option', 'title', 'Agregar ' + $.cookie('modulo'));
        $('#dialog-nuevo').dialog("option", "position",{ my: "top", at: "top+10", of: window });     
    });     
});

// Llamar al dialog (entrada minorista)
$('body').on('click', '.comando-nuevo-minorista', function(e) {
	e.preventDefault();
	
	// Bloquear la ventana
	messageMachine.bloquear();
	
	// Cargar el contenido en el dialog
	$('#dialog-nuevo').load($.cookie('modulo') + '/agregar_minorista', function() {
		// Abrir el dialog
        $('#dialog-nuevo').dialog( "open" );
        $('#dialog-nuevo').dialog('option', 'title', 'Agregar entrada minorista');
        $('#dialog-nuevo').dialog("option", "position",{ my: "top", at: "top+10", of: window });
	});
});


/**
 * EDITAR 
 */

// Inicializar dialog
$( "#dialog-editar" ).dialog({
    autoOpen: false,
    closeOnEscape: true,
    modal: true,
    position: { my: "top", at: "top+10", of: window },
    resizable: false,
    width: "auto",
    maxHeight: swinH-50,
    buttons: [ 
        {
            text: "Guardar",
            addClass: "btn btn-primary",
            click: function() {
                 mainForm.validate();
            }
        },
        {
            text: "Cancelar",
            addClass: "btn",
            click: function() {
                $( this ).dialog( "close" ); 
            }
        } 
    ],      
    open: function( event, ui ) {
         // Desbloquear la ventana
        messageMachine.desbloquear();
        initForm(); 
        
        // En le formulario de carga - descarga formatear los numeros
        // en label y edit
        var labelTotal = $('.km-total'),
            campo = $('input[name=km_recorridos]');
        campo.autoNumeric('init', {aSep: '.', aDec: ','});
        campo.autoNumeric('set', campo.val());
        labelTotal.text(campo.val()); 

    },
    close: function( event, ui ) {
        $(this).empty();
    }
});

// Llamar al dialog
$('body').on('click', '.comando-editar:not(.disabled)', function(e) {
    e.preventDefault();
            
    // Bloquear la ventana
    messageMachine.bloquear();
    
    // Obtener el ID del item seleccionado para agergarlo la URI
    var itemId = mainForm.itemCount();

    // Cargar el contenido de agregar en el dialog       
    $('#dialog-editar').load($.cookie('modulo') + '/editar/' + itemId['items'], function() {                            
        // Abrir el dialog                      
        $('#dialog-editar').dialog( "open" ); 
        $('#dialog-editar').dialog("option", "position",{ my: "top", at: "top+10", of: window });                       
    });     
});


/**
 * ELIMINAR 
 */
$('body').on('click', '.comando-eliminar', function(e) {
    e.preventDefault();
    mainForm.eliminar($(this).attr('href'));        
});

/**
 * AUTO CALCULAR 
 */
$('body').on('click', '.comando-autocalc', function(e) {
    e.preventDefault();
    mainForm.autoCalcular($(this).attr('href')); 
});




/*    -------------------------------------------------------------
    Buscar, Agregar regla, Eliminar regla, Eliminar todas las reglas
    Gestionar los dialogos para ejecutar las accciones de busqueda
    -------------------------------------------------------------    */
   
/*
 * BUSCAR
 */
// Inicializar dialog
$( "#dialog-buscar" ).dialog({
    autoOpen: false,
    closeOnEscape: true,
    modal: true,
    position: { my: "top", at: "top+10", of: window },
    resizable: false,
    width: 625,
    maxHeight: swinH-50,
    buttons: [ 
        { 
            // Agregar regla de busqueda
            text: "",
            title: "Agregar regla",
            append :"<i class='icon-plus'></i>",
            addClass: "btn buscar-add-regla",
            click: function() { 
                $('.table-search').append(buscar_machine.search_template($.cookie('modulo')));
                initForm(); 
            }            
        },
        {
            // Eliminar todas las reglas de busqueda
            text: "",
            title: "Quitar todas las reglas",
            append: "<i class='icon-trash'></i>",
            addClass: "btn buscar-delete-all-regla",
            click: function() {
                 var _tr = $('table.table-search tr:not(.tr-periodos)');
                 if (_tr.length > 0) {
                     _tr.remove();
                     $('.btn-buscar').trigger('click', ['quitar_busqueda']);
                 }
            }            
        },        
        {
            text: "Buscar",
            addClass: "btn btn-primary btn-buscar",
            click: function(event, param1) {
                var trCount = $('.table-search tr:not(.tr-periodos)').length,
                     searchBtn = $( '.comando-buscar' ),
                     searchSpan = searchBtn.find('span');
                     
                 if (trCount > 0 || (param1 != undefined)) {
                     var _form = $('#form-search'),
                         _href = _form.attr('action'),
                         _frz = _form.serialize();         
                     
                     // Buscar y cargar el contenido
                     loadContent(_href, {search: _frz});
                     
                     // Agregar el numero de busquedas actuales para este modulo al boton de "Buscar"
                     if (trCount > 0) {
                        // No existe el span
                        if (searchSpan.length){
                            searchSpan.empty().append("(" + trCount + ")");
                        }
                        // Existe el span
                        else{
                            var searchSpan = $("<span />");
                            searchBtn.append(searchSpan.append("(" + trCount + ")"));
                        }
                     }
                     else { 
                        $( '.comando-buscar' ).find("span").empty();
                     }            
                 }
            }
        },
        {
            text: "Cancelar",
            addClass: "btn",
            click: function() {
                $( this ).dialog( "close" ); 
            }
        }
    ],      
    open: function( event, ui ) {                   
         // Desbloquear la ventana
        messageMachine.desbloquear();
        initForm();     
    },
    close: function( event, ui ) {
        $(this).empty();
    }
});

// Mostrar dialogo de busqueda
$("body").on("click", ".comando-buscar", function(e) {
    e.preventDefault();
         
    // Cargar el contenido de agregar en el dialog       
    $('#dialog-buscar').load($.cookie('modulo') + '/buscar', function() {                            
        // Abrir el dialog                      
        $('#dialog-buscar').dialog( "open" );                
    });
});

 
/*
 * Eliminar una regla de busqueda
 */
 $('body').on('click', '#dialog-buscar table.table-search td .close', function() {
     $(this).parents('tr').remove();
     $('.btn-buscar').trigger('click', ['quitar_busqueda']);   
 });
 
 
/*    -------------------------------------------------------------
    Configuracion
    -Salva
    -Restaura
    -Apertura   (periodo de pago)
    -Cierre     (periodo de pago)
    -Mantenimiento a tablas de la base de datos
    -------------------------------------------------------------    */
/**
 * ABRIR periodo de pago
 * Crear Dialog dinamico
 */ 
$('body').on('click', ".comando-periodo-apertura", function(e) {     
    e.preventDefault();
    var ppAperturaDialog = $("<div id='periodo-pago-apertura'>Cargando...</div>");
    ppAperturaDialog.dialog({
        autoOpen: false,
        closeOnEscape: true,
        modal: true,
        position: { my: "top", at: "top+10", of: window },
        resizable: false,
        width: "auto",
        maxHeight: swinH-50,
        title: 'Periodo de pago (apertura)',
        buttons: [ 
            { 
                text: "Abrir",
                addClass: "btn btn-success",
                click: function() { 
                     mainForm.validate();
                }            
            },
            {
                text: "Cancelar",
                addClass: "btn",
                click: function() {
                    $( this ).dialog( "close" );
                }            
            } 
        ]
    })
    .bind('dialogclose', function() {
        ppAperturaDialog.dialog('destroy');
    })
    .load($(this).attr('href'), function(response, status, xhr) {
        
        // Manejar errores
        if (response == "periodo_pago_abierto") {
            alertify.log("<strong>&iexcl;Informaci&oacute;n!</strong> El periodo de pago a&uacute;n est&aacute; en curso.<br/>Debe cerrar el periodo de pago actual para poder abrir uno nuevo.", "", 20000);
            $('#periodo-pago-apertura').dialog("destroy"); 
        }
        else if (status == "error") {
            alertify.log("<strong>Error: </strong> La p&aacute;gina no se ha podido cargar.</br>" + xhr.statusText + " (" + xhr.status + ")","",20000);
            $('#periodo-pago-apertura').dialog("destroy");
        }
        // Mostrar dialogo
        else {
            // Inicializar elementos del formulario
            initForm();        
            // Posicionar el dialog
            ppAperturaDialog.dialog("option", "position", { my: "top", at: "top+10", of: window });
            ppAperturaDialog.dialog("open");
        }            
        
    });
});

/**
 * CERRAR periodo de pago
 * Crear Dialog dinamico
 */ 
$(".comando-periodo-cierre").click(function(e) {    
    e.preventDefault();
    var ppCierreDialog = $("<div id='periodo-pago-cierre'>Cargando...</div>");
    ppCierreDialog.dialog({
        autoOpen: false,
        closeOnEscape: true,
        modal: true,
        position: { my: "top", at: "top+10", of: window },
        resizable: false,
        width: "auto",
        maxHeight: swinH-50,
        title: 'Periodo de pago (cierre)',
        buttons: [ 
            { 
                text: "Cerrar",
                addClass: "btn btn-primary",
                click: function() { 
                     mainForm.validate();
                }            
            },
            {
                text: "Cancelar",
                addClass: "btn",
                click: function() {
                    $( this ).dialog( "close" );
                }            
            } 
        ]
    })
    .bind('dialogclose', function() {
        ppCierreDialog.dialog('destroy');
    })
    .load($(this).attr('href'), function(response, status, xhr) {
        
        // Manejar errores
        if (response == "periodo_pago_cerrado") {
            alertify.log("<strong>&iexcl;Informaci&oacute;n!</strong> No se ha iniciado un periodo de pago.<br/>Debe abrir un nuevo periodo de pago.", "", 20000);
            $('#periodo-pago-cierre').dialog("destroy");
        }
        else if (status == "error") {
            alertify.log("<strong>Error: </strong> La p&aacute;gina no se ha podido cargar.</br>" + xhr.statusText + " (" + xhr.status + ")","",20000);
            $('#periodo-pago-cierre').dialog("destroy");
        }
        // Mostrar dialogo
        else {
            // Inicializar elementos del formulario
            initForm();        
            // Posicionar el dialog
            ppCierreDialog.dialog("option", "position", { my: "top", at: "top+10", of: window });
            ppCierreDialog.dialog("open");
        }            
        
    }); 

});


/**
 * ESTADO periodo de pago
 * Crear Dialog dinamico
 */ 
$(".comando-periodo-estado").click(function(e) {    
    e.preventDefault();
    var ppCierreDialog = $("<div id='periodo-pago-estado'>Cargando...</div>");
    ppCierreDialog.dialog({
        autoOpen: false,
        closeOnEscape: true,
        modal: true,
        position: { my: "top", at: "top+10", of: window },
        resizable: false,
        width: "auto",
        maxHeight: swinH-50,
        title: 'Periodo de pago (estado)',
        buttons: [
            {
                text: "Aceptar",
                addClass: "btn",
                click: function() {
                    $( this ).dialog( "close" );
                }            
            } 
        ]
    })
    .bind('dialogclose', function() {
        ppCierreDialog.dialog('destroy');
    })
    .load($(this).attr('href'), function(response, status, xhr) {
        
        // Manejar errores
        if (status == "error") {
            alertify.log("<strong>Error: </strong> La p&aacute;gina no se ha podido cargar.</br>" + xhr.statusText + " (" + xhr.status + ")","",20000);
            $('#periodo-pago-cierre').dialog("destroy");
        }
        // Mostrar dialogo
        else {
            // Inicializar elementos del formulario
            initForm();        
            // Posicionar el dialog
            ppCierreDialog.dialog("option", "position", { my: "top", at: "top+10", of: window });
            ppCierreDialog.dialog("open");
        }            
        
    }); 

});


/*    -------------------------------------------------------------
    Salva y restaura (Importar Exportar)
    
    - Formularios para importar y exportar bases de datos
    -------------------------------------------------------------    */
   
/**
 * Exportar 
 */
$(".comando-exportar-db").click(function(e) {    
    e.preventDefault();
    var exportarDialog = $("<div id='exportar-dialog'>Cargando...</div>");
    exportarDialog.dialog({
        autoOpen: false,
        closeOnEscape: true,
        modal: true,
        position: { my: "top", at: "top+10", of: window },
        resizable: false,
        width: "auto",
        maxHeight: swinH-50,
        title: 'Salvar datos (Exportar)',
        buttons: [ 
            { 
                text: "SALVAR",
                addClass: "btn btn-success",
                click: function() { 
                     mainForm.exportarDB();
                }            
            },
            {
                text: "Cancelar",
                addClass: "btn",
                click: function() {
                    $( this ).dialog( "close" );
                }            
            } 
        ]
    })
    .bind('dialogclose', function() {
        exportarDialog.dialog('destroy');
    })
    .load($(this).attr('data-url'), function(response, status, xhr) {
        
        // Manejar errores
        if (status == "error") {
            alertify.log("<strong>Error: </strong> La p&aacute;gina no se ha podido cargar.</br>" + xhr.statusText + " (" + xhr.status + ")","",20000);
            exportarDialog.dialog("destroy");
        }
        // Mostrar dialogo
        else {
            // Inicializar elementos del formulario
            initForm();        
            // Posicionar el dialog
            exportarDialog.dialog("option", "position", { my: "top", at: "top+10", of: window });
            exportarDialog.dialog("open");
        }            
        
    });

});


/**
 *Importar 
 */

$(".comando-importar-db").click(function(e) {    
    e.preventDefault();
    var importarDialog = $("<div id='importar-dialog'>Cargando...</div>");
    importarDialog.dialog({
        autoOpen: false,
        closeOnEscape: true,
        modal: true,
        position: { my: "top", at: "top+10", of: window },
        resizable: false,
        width: "auto",
        maxHeight: swinH-50,
        title: 'Restaurar datos (Importar)',
        buttons: [ 
            { 
                text: "RESTAURAR",
                addClass: "btn btn-primary",
                click: function() { 
                     mainForm.importarDB();
                }            
            },
            {
                text: "Cancelar",
                addClass: "btn",
                click: function() {
                    $( this ).dialog( "close" );
                }            
            } 
        ]
    })
    .bind('dialogclose', function() {
        importarDialog.dialog('destroy');
    })
    .load($(this).attr('data-url'), function(response, status, xhr) {
        
        // Manejar errores
        if (status == "error") {
            alertify.log("<strong>Error: </strong> La p&aacute;gina no se ha podido cargar.</br>" + xhr.statusText + " (" + xhr.status + ")","",20000);
            importarDialog.dialog("destroy");
        }
        // Mostrar dialogo
        else {
            // Inicializar elementos del formulario
            initForm();        
            // Posicionar el dialog
            importarDialog.dialog("option", "position", { my: "top", at: "top+10", of: window });
            importarDialog.dialog("open");
        }            
        
    });

});


/**
 *  MANTENIMIENTO
 *  a las tablas de la base de datos
 */  
$('body').on('click', ".comando-mantenimiento", function(e) {     
    e.preventDefault();
    var mantenimientoDialog = $("<div id='mantenimiento-dialog'>Cargando...</div>");
    mantenimientoDialog.dialog({
        autoOpen: false,
        closeOnEscape: true,
        modal: true,
        position: { my: "top", at: "top+10", of: window },
        resizable: false,
        width: "auto",
        maxHeight: swinH-50,
        title: 'Mantenimiento',
        buttons: [ 
            { 
                text: "Optimizar base de datos",
                addClass: "btn btn-success",
                click: function() { 
                     mainForm.mantenimiento();
                }            
            },
            {
                text: "Cancelar",
                addClass: "btn",
                click: function() {
                    $( this ).dialog( "close" );
                }            
            } 
        ]
    })
    .bind('dialogclose', function() {
        mantenimientoDialog.dialog('destroy');
    })
    .load($(this).attr('href'), function(response, status, xhr) {
        
        // Manejar errores
        if (status == "error") {
            alertify.log("<strong>Error: </strong> La p&aacute;gina no se ha podido cargar.</br>" + xhr.statusText + " (" + xhr.status + ")","",20000);
            $('#mantenimiento-dialog').dialog("destroy");
        }
        // Mostrar dialogo
        else {
            // Inicializar elementos del formulario
            initForm();        
            // Posicionar el dialog
            mantenimientoDialog.dialog("option", "position", { my: "top", at: "top+10", of: window });
            mantenimientoDialog.dialog("open");
        }            
        
    });
});
 

/*    -------------------------------------------------------------
    Login
    -------------------------------------------------------------    */
$('body').on('click', '.comando-login', function(e) {
    e.preventDefault();
    mainForm.validate();
}); 
 
 
/*    -------------------------------------------------------------
    Funcionalidades Auxiliares Generales
    
    - Capacidad bombeo checkboxes
    - Kilometros recorridos por via
    - Privilegios
    -------------------------------------------------------------    */

/*
 * Capacidad bombeo checkboxes
 */
$("body").on('change', ".table-form-auxiliar td input:checkbox", function(e){
    auxTable.enableInput($(this));
});

/*
 * Kilometros recorridos por via
 */
$("body").on('keyup', 'table input.km-calc', function() {
    mainForm.km_calc_total($(this));
});

/*
 * Privilegios marcar / desmarcar todo
 */
$("body").on('click', '#marcar-todo-permisos', function(e) {
    var chks = $("input[name*=permisos]");
    
    if ($(this).is(":checked")){
        chks.iCheck('check');
    }else{
        chks.iCheck('uncheck');
    }
});


/*    -------------------------------------------------------------
    Instalacion, cargar bases de datos
    -------------------------------------------------------------    */
var load_table_script = $('input[name=load_table_script]');
if (load_table_script.length) 
{
	console.log('econtrado script');
	var request = $.ajax({
		url: base_url + 'install/restore',
		dataType: 'json',
		type: 'post',
		beforeSend:function(){
			var m = 'Cargando datos iniciales de destajo. <p>Por favor espere hasta culminar el proceso...</p>'
				 + '<div class="progress progress-striped active">'
  				 + '<div class="bar" style="width: 100%;"></div>'
				 + '</div>'; 
			alertify.log(m,'',0);
		}		
	});
	
	request.done(function(response) {
		if (response.status != undefined)
		{
			setTimeout(function() {
				var m = "";
				if (response.status == "done") m = "Se han cargado los datos, ya puede finalizar!";
				if (response.status == "error") m = "No se han podido cargar los datos iniciales, intentelo mas tarde o contacte con el administrador del software";  
				alertify.log(m, '',50000);
			}, 1000);
		}
	});
	
	request.always(function(xhr,status,error) {
		messageMachine.desbloquear();
	});
}
