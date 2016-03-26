/*
DESTAJO-MODULE

date: 2015.01.19
type: js module
path: js/plugins.js

DESTAJO-MODULE-END
*/
/*    -------------------------------------------------------------
    Layout
    Controla la visualizacion de los elementos de la UI
    -------------------------------------------------------------    */
function layout () {

    /*
     * Tabla de contenidos
     */
    var tablaContent = $('#table-fixed-content'),
        vistaPrevia = $('#word-template'),
        tablaFix = $('.table-fixed-header'),
        tablaEntrada = $('.table-fixed-header.table-entrada');

    // Ajustar la posicion del contenedor de la tabla
    tablaContent.css({
       "height": swinH - 89 - 32,
       "width": swinW
    });
    vistaPrevia.css({
       "min-height": swinH - 41
    });

    // Hacer la tabla fixed header
    if (tablaFix.length) tablaFix.fixedHeaderTable();
    if (tablaEntrada.length) 
      tablaEntrada.fixedHeaderTable({
        fixedColumns: 1
    });
}

/*    -------------------------------------------------------------
    Message machine
    Este objeto se encarga de mostrar los basicos de la aplicacion
    Cuando se esta cargando una pagina, entre otros
    -------------------------------------------------------------    */
var messageMachine = {

    timeID: null,
    el: null,

    // Bloquear elemento del DOM
    bloquear: function(el) {
        // Obtener un elemento de la pagina por defecto para bloquear
        // en caso que no se halla enviado ninguno
        if (el == null || el == undefined || el == "") el = $('#main-content');
        // Ocultar los alertifys
        this.hideLogs();
        // Bloquear el elemento del DOM
        el.block({ message: null });
        // Poner contador para dentro de 1.00 seg mostrar mensaje de cargando
        this.timeID = setTimeout(this.cargando, 1000);
    },

    // Mostrar mensaje de "Cargando..."
    cargando: function() {
        alertify.log("Cargando...", "", 0);
    },

    // Desbloquear la pagina
    desbloquear: function(el) {
        // Obtener un elemento de la pagina por defecto para bloquear
        // en caso que no se halla enviado ninguno
        if (el == null || el == undefined) el = $('#main-content');
        // Eliminar el contador
        clearTimeout(messageMachine.timeID);
        // Ocultar los alertifys
        this.hideLogs();
        // Desbloquear la pgina
        el.unblock();
    },

    // Ocular alertifys
    hideLogs: function() {
        $( ".alertify-logs" ).addClass("alertify-logs-hidden").empty();
    }

};

/*    -------------------------------------------------------------
    Main table
    Controlar el comportamiento de los objetos relacionados a la tabla
    -------------------------------------------------------------    */
var mainTable = {

    // Marcar un row
    checkRow: function(chk) {

        var tr = chk.parents( "tr" );
        if (chk.is( ":checked" )) {
            tr.addClass( "warning" );
        }
        else
            tr.removeClass("warning");

        // Analizar opciones de tool-bar
        this.shOptions(chk);
    },

    // Marcar todos los rows
    checkRows: function(chk, isChecked) {

        var chks = chk.parents( ".fht-table-wrapper" ).find( ".fht-tbody td input:checkbox" );
        if (isChecked)
            chks.iCheck('check');
        else
            chks.iCheck('uncheck');

        // Analizar opciones de tool-bar
        this.shOptions(chk);

    },

    // Mostrar y ocular opciones en toolbar
    shOptions: function() {

        var tableWrapper = $( ".fht-table-wrapper" ),
            chkTd = tableWrapper.find( ".fht-tbody td input:checked" ),
            mainToolbar = $('#main-toolbar'),
            comandoNuevo = mainToolbar.find('.comando-nuevo'),
            comandoEditar = mainToolbar.find('.comando-editar'),
            comandoEliminar = mainToolbar.find('.comando-eliminar'),
            comandoMas = mainToolbar.find('.comando-mas'),
            comandoEE = mainToolbar.find('.comando-editar-eliminar');

        // Ocultar todo
        if (chkTd.length == 0) {
            comandoEditar.addClass("hide");
            comandoEliminar.addClass("hide");
            comandoMas.addClass("hide");
            comandoEE.addClass('hide');
        }else
        // Mostrar todo, habilitar editar
        if (chkTd.length == 1) {
            comandoEditar.removeClass("hide disabled");
            comandoEliminar.removeClass("hide");
            comandoMas.removeClass("hide");
            comandoEE.removeClass('hide');
        }else
        // Mostrar todo, deshabilitar editar
        if (chkTd.length > 1) {
            comandoEditar.addClass("disabled");
            comandoEliminar.removeClass("hide");
            comandoMas.removeClass("hide");
            comandoEE.removeClass('hide');
        }
    }

};

/*    -------------------------------------------------------------
    Auxiliar table
    Controlar el comportamiento de los objetos relacionados
    a las tablas auxiliares
    -------------------------------------------------------------    */
var auxTable = {

    // CAPACIDAD DE BOMBEO
    // Activar o desactivar los input
    enableInput: function(chk) {

        var inputText = chk.parents('tr').find('input[type=text]');
        if (chk.is(':checked')) {
            inputText
                .removeAttr('disabled')
                .focus();
        }else{
            inputText
                .attr('disabled','')
                .val('');
        }
    }
};


/*    -------------------------------------------------------------
    makeiCheck
    Hacer los checkbox tipo Gmail
    -------------------------------------------------------------    */
function makeiCheck () {

    $( "input:checkbox" ).iCheck({
        checkboxClass: 'icheckbox_polaris',
        radioClass: 'iradio_polaris'
    });

    // Checkbox en tablas
    $( "table td input:checkbox" ).bind("is.Checked", function() {
        mainTable.checkRow($(this));
    });
    $( "table td input:checkbox" ).bind("is.Unchecked", function() {
        mainTable.checkRow($(this));
    });

    // Checkbox en tabla th
    $( "table th input:checkbox" ).bind("is.Checked", function() {
        mainTable.checkRows($(this), true);
    });
    $( "table th input:checkbox" ).bind("is.Unchecked", function() {
        mainTable.checkRows($(this), false);
    });
}

/*  -------------------------------------------------------------
    Crear ruta a la pagina X (cPathX)
    @param goto_page_number (Ir a la pagina #)
    @param url (URL del modulo) Ej: .../index.php/empresa/showContent
    -------------------------------------------------------------    */
function cPathX($go_to_page, $url){

    var $go_to_page = parseInt($go_to_page),
        $num_pages = parseInt( $.cookie("num_pages") ),
        $per_page = parseInt( $.cookie("per_page") ),
        $rows_to_show = parseInt( $.cookie("rows_to_show") );

    // Escriben un valor mayor que el total de paginas
    // Ajustamos, paginas = total de paginas
    if ($go_to_page > $num_pages) $go_to_page = $num_pages;

    // Escriben un valor <= 0
    if ($go_to_page <= 0) $go_to_page = "";

    // Calculamos la pagina a mostrar
    var x = 1;
    if ($rows_to_show == 0) x = 2;
    if ($go_to_page != "") $go_to_page = ($go_to_page - x) * $per_page;


    if ($go_to_page != ""){
        $url = $url + "" + $go_to_page;
    }

     return $url;
}


/*    -------------------------------------------------------------
    Load content
    Cargar contenidos principal e inicializar plugins
    -------------------------------------------------------------    */
function loadContent ( url, params ) {

    var $data = {}; // El objeto data

    // Getionar parametros
    if (params != undefined){
        // Data para buscar
        if (params.search != undefined) $data = params.search;
    }

    /*
     * Llamar al contenido
     */
    var request = $.ajax({
        url: url,
        data: $data,
        type: "POST",
        cache: true,
        dataType: "json",
        timeout: 10000,
        beforeSend: function(xhr) {
            // Mandar a mostrar pagina cargando
            if (params && params.loading) messageMachine.bloquear(params.loading);
            else messageMachine.bloquear();
        }
     });

     /*
      * El contenido se cargo OK :)
      */
     request.done(function(response) {

         // Tabla principal
         var mainTableContent = $( "#table-fixed-content" );

         if ( mainTableContent.length ) {

             mainTableContent
                .empty()
                .addClass('animated fadeInLeft')
                .append(response.registros);

         }

         // Paginacion
         var paginacionGroup = $( "#paginacion-group" );
         if (paginacionGroup.length) {
             paginacionGroup
                .empty()
                .append(response.paginacion);
         }

        // Inicializar plugins
        $('.table-fixed-header').fixedHeaderTable();
        makeiCheck();


         // Almacenamos datos de paginacion a las cookies
         $.cookie("per_page", response.per_page, {path: '/destajo'});
         $.cookie("rows_to_show", response.rows_to_show, {path: '/destajo'});
         $.cookie("cur_page", response.cur_page, {path: '/destajo'});
         $.cookie("num_pages", response.num_pages, {path: '/destajo'});

         initForm();
         mainTable.shOptions();
     });

     /*
      * Siempre hacer esto:...
      */
     request.always(function(xhr, status, error) {
        // Desbloquear la ventana
         messageMachine.desbloquear();

        // Error: tiempo de espera
        if (status == "timeout") alertify.log("Tiempo de espera agotado.", "", 10000);

        // Mostrar un mensaje luego de cargar, utilizado principalmente despues de agregar o editar
        if (params != undefined && params.redirect_msg != undefined) alertify.log(params.redirect_msg, 4000);

        // Mostrar mensaje de error si el usuario no obtuvo el acceso a algun modulo
        var noaccess = $( 'input[name=noaccess_redirect]' );
        if (noaccess.length) {
            alertify.log(noaccess.val(), "", 10000);
            noaccess.remove();
        }
     });
}

/*    -------------------------------------------------------------
    Main form
    validar, Agregar, Editar, Eliminar
    -------------------------------------------------------------    */
var mainForm = {

    /*    -------------------------------------------------------------
    Validate

    Funcion principal para validar
    -------------------------------------------------------------    */
    validate: function() {

        var _form = $('form.main-form'),
            _action = _form.attr('action'),
            _form_srz = _form.serialize(),
            _validar_url = _action.substr(0, _action.lastIndexOf('/') + 1) + 'validar';

        // Validar el formulario con Code Igniter
        this.validar_formulario(_form_srz, _validar_url, function(status) {

            // Si la validacion fue correcta, ejecutamos la accion de agregar o editar
            if (status == true) {
                // Hacer login
                if (_action.substr(_action.lastIndexOf("/") + 1, _action.length) == 'login') {
                    mainForm.login(_form_srz, _action);
                }
                // Agregar o editar
                else {
                    mainForm.guardar(_form_srz, _action);
                }
            }
        });
    },

    /*    -------------------------------------------------------------
    Validar formulario

    @param array _form_srz
    @param string{url} _validar_url
    @param bool callback
    -------------------------------------------------------------    */
    validar_formulario: function(_form_srz, _validar_url, callback) {

        var request = $.ajax({
           url: _validar_url,
           data: _form_srz,
           dataType: 'json',
           type: 'post',
           cache: true
        });

        request.done(function(response) {

            // Eliminar mensajes de error
            $( "ul.error-list" ).remove();
            $( "*.error" ).removeClass('error');

            // ERROR en la validacion
            if (response.status == "validation_error") {

                $.each(response['error'], function(key, value) {

                    var campo = $('form.form-validate *[name="' + response['campo'][key] + '"]'),
                        span = $( "<ul />" );

                    campo.addClass('error');
                    campo.after(span);
                    span.addClass("error-list")
                        .append("<li>" + value + "</li>");

                    // Ajustamos los mensajes de error a los select2
                    if (campo.is(".select2")) {
                        var id = campo.attr('id'),
                            s2 = $( '#s2id_' + id );
                        s2.addClass("error");
                    }

                });
                // Mostrar mensaje al usuario de que la validacion fallo
                alertify.log('<strong>Error: </strong> Faltan elementos por validar.', '', 9000);

                /*
                 * Validacion excepcional
                 * 1- Capacidad de carga (validar: capacidad de bombeo)
                 * 2- Productos descargables (lugar de descarga)
                 * 3- kilometros recorridos (carga_descarga)
                 */
                if (response.falta_capacidad_bombeo == true) {
                    alertify.log('<strong>Advertencia: </strong> Debe insertar al menos una capacidad de bombeo.','', 20000);
                }
                if (response.falta_producto == true) {
                    alertify.log('<strong>Advertencia: </strong> Debe seleccionar al menos un producto.','', 20000);
                }
                if (response.falta_kms_recorridos == true) {
                    alertify.log('<strong>Advertencia: </strong> Debe insertar al menos 1km en alguna v&iacute;a.','', 20000);
                }
            }

            // OK en la validacion
            else if (response.status == "validation_pass") {
                callback(true);
            }
        });

        request.fail(function(xhr, status, error) {
            alertify.log('&iexcl;Vaya!... No se ha podido validar');
        });
    },

    /*    -------------------------------------------------------------
    Guardar

    Ejecuta la accion del formulario segun sea el caso (Agregar, Editar)
    @param array form_srz
    @param string main_form_url
    -------------------------------------------------------------    */
    guardar: function(_form_srz, _action) {

       var accion = _action.substr(_action.lastIndexOf("/") + 1, _action.length),
           request = $.ajax({
               url: _action,
               data: _form_srz,
               dataType: 'json',
               type: 'post'
           });

       request.done(function(response) {

           // ERROR al guardar
           if (response.status == 'agregar_error' || response.status == 'editar_error') {
               alertify.log('No se ha podido guardar');
           }

           // OK al guardar
           else if (response.status == 'agregar_pass' || response.status == 'editar_pass') {

                // Cerrar dialogos
                if (response.status == 'agregar_pass') $('#dialog-nuevo').dialog('close');
                if (response.status == 'editar_pass') $('#dialog-editar').dialog('close');

               loadContent(base_url + cPathX($.cookie("cur_page"), $.cookie("modulo") + "/show_content/"),
               {redirect_msg: 'Se ha guardado el elemento.<br>'});
           }

           // OK al guardar perfil
           else if (response.status == 'perfil_pass') {
               // Cerrar dialogo
               $('#dialog-perfil').dialog('close');
               alertify.log('Se ha modificado el perfil.');
           }

           // OK al abrir periodo de pago
           else if (response.status == 'pp_apertura_pass') {
               // Destruir dialogo dinamico
               $('#periodo-pago-apertura').dialog('destroy');
               alertify.log('Se ha iniciado un nuevo periodo de pago.', '', 10000);
           }

           // OK al cerrar periodo de pago
           else if (response.status == 'pp_cierre_pass') {
               // Destruir dialogo eco
               $('#periodo-pago-cierre').dialog('destroy');
               // Redireccionar a entrada para que se activen opciones asociadas al cierre
               location.href=base_url+"erd";
               loadContent(base_url, {redirect_msg: 'Se ha cerrado el periodo de pago'});
           }
       });

       // La llamada AJAX fallo
       request.fail(function(xhr, status, error) {
           alertify.log('&iexcl;Vaya!... No se ha podido realizar la acci&oacute;n');
       });

       // Siempre hacer esto:...
       request.always(function() {
           mainTable.shOptions();
       });
   },

   /*    -------------------------------------------------------------
    Eliminar registro

    Eliminar un registro del formulario
    @param object
    -------------------------------------------------------------    */
   eliminar: function($href) {
        console.log($href)
        // Contar los elementos a eliminar para formar el mensaje
        var result = this.itemCount();
            countChk = result['itemsCount'],
            $uri = result['items'],
            mess = "este elemento";
        (countChk > 1) ? mess = "estos <strong>(" + countChk + ")</strong> elementos" : "este elemento";

        var dialog = $("<div id='dialog-eliminar'>Cargando...</div>");
        dialog.dialog({
            autoOpen: false,
            show: { effect: "fade", duration: 200 },
            hide: { effect: "fade", duration: 200 },
            closeOnEscape: true,
            modal: true,
            resizable: false,
            width: "auto",
            maxHeight: $(window).outerHeight(true) - 50,
            title: "Confirmaci&oacute;n para eliminar",
            buttons: [
                {
                    text: "Eliminar",
                    addClass: "btn btn-primary",
                    click: function() {

                        var request = $.ajax({
                            url: $href,
                            dataType: 'json',
                            type: 'post',
                            data: {eliminar: true, id: $uri}
                        });

                        // Se ha eliminado
                        request.done(function(response) {
                            // Eliminar paso
                            if (response.status == 'eliminar_error') {
                                alertify.log('No se ha podido eliminar el elemento');
                            }
                            else if (response.status == 'eliminar_pass') {

                                $.cookie('rows_to_show', $.cookie('rows_to_show') - 1, {path: '/destajo'});
                                loadContent(base_url + cPathX($.cookie("cur_page"), $.cookie("modulo") + "/show_content/"),
                                {redirect_msg: 'Se ha eliminado el elemento'});
                            }
                        });

                        // No se puede eliminar
                        request.fail(function(xhr, status, error) {
                            // La consulta respondio un error propio de MySQL
                            if (xhr.responseText.indexOf('Error Number: 1451') != -1) {

                                var msg = 'No se ha podido eliminar el elemento.<br/>'
                                    + 'Existen datos de otras tablas que est&aacute;n utilizando este valor '
                                    + 'y no permiten su eliminaci&oacute;n.';
                                alertify.log(msg, 9000);

                            }else {

                                alertify.log('&iexcl;Vaya!... No se ha podido eliminar');
                            }
                        });

                        // Siempre hacer esto:...
                       request.always(function() {
                           mainTable.shOptions();
                           dialog.dialog("close")
                       });
                    }
                },
                {
                    text: "Cancelar",
                    addClass: "btn",
                    click: function() {
                        $(this).dialog("close");
                    }
                }

            ]
        })
        .bind("dialogclose", function() {
            dialog.dialog("destroy");
        });
        dialog
            .empty()
            .append("&iquest;Quieres eliminar " + mess + " permanentemente?")
            .dialog("open")
            .dialog("option", "position", { my: "top", at: "top+10", of: window });
    },

    /*    -------------------------------------------------------------
    ItemCount

    Cuenta los checkboxes seleccionados en la tabla
    @return item list
    @return item count
    -------------------------------------------------------------    */
   itemCount: function() {

       var chk = $( ".table-fixed-header td :checkbox:checked" ),
           countChk = chk.length,
           $uri = [],
           $query = [];
       for ( var i = 0; i < countChk; i++ ) {
           $uri[i] = $(chk[i]).val();
       }

       $query['items'] = $uri;
       $query['itemsCount'] = countChk;

       return $query;
   },

   /*    -------------------------------------------------------------
    Login

    intenta loguear al usuario en el sistema
    -------------------------------------------------------------    */
   login: function(_form_srz, _action) {
       var accion = _action.substr(_action.lastIndexOf("/") + 1, _action.length),
           request = $.ajax({
               url: _action,
               data: _form_srz,
               dataType: 'json',
               type: 'post'
           });

       // Se logueo
       request.done(function(response) {
           // Los datso son incorrectos
           if (response.status == 'login_error') {
               alertify.log('Los datos de su cuenta son incorrectos');
           }
           // Los datos son correctos
           else if(response.status == 'login_pass'){
               location.href = base_url + 'entrada';
           }
       });

       // No se pudo loguear
       request.fail(function(xhr, status, error) {
           alertify.log('&iexcl;Vaya!... No se ha podido loguear');
       });

   },

   /*    -------------------------------------------------------------
    Calcular total de Kilometros recorridos por via
    -------------------------------------------------------------    */
   km_calc_total: function(campo) {
       var tabla = campo.parents('table'),
           labelTotal = tabla.find('.km-total'),
           campos = tabla.find('input.km-calc'),
           temp = "",
           km_recorridos = tabla.find('input[name=km_recorridos]'),
           calc = 0.00;

       // Recorrer campos y sumas sus valores
       $.each(campos, function(i, val) {
           temp = $(val).autoNumeric('get');

           if (temp != "" && temp != "0.00")
               calc = parseFloat(calc) + parseFloat(temp);

           // Redondear
           calc = Math.round(calc * Math.pow(10, 2)) / Math.pow(10, 2);

           // Output los valores formateados decimal
           km_recorridos.val(calc);
           km_recorridos.autoNumeric('init', {aSep: '.', aDec: ','});
           km_recorridos.autoNumeric('set', km_recorridos.val());
           labelTotal.text(km_recorridos.val());
       });
   },

   /*    -------------------------------------------------------------
    AutoCalcular

    Auto calcula valores segun el modulo
    -------------------------------------------------------------    */
   autoCalcular: function(href) {

        var request = $.ajax({
            url : href,
            type : "POST",
            cache : true,
            dataType : "json",
            beforeSend : function(xhr) {
                messageMachine.bloquear()
            }
        });

        request.done(function(response) {

            switch(response.status) {
                case "no_calc":
                    alertify.error("No se pudo realizar el c&aacute;lculo.");
                    break;
                case "calc":
                    // Desbloquear la ventana
                    messageMachine.desbloquear();
                    loadContent( base_url + $.cookie("modulo") + "/show_content",
                    {redirect_msg: 'Se ha terminado de calcular.<br>'} );
                    break;
            }
        });

        request.always(function(xhr, status, error) {
             // Desbloquear la ventana
             messageMachine.desbloquear();

            // Error: tiempo de espera
            if (status == "error")  {
                 alertify.log("No se ha podido calcular. :(", "", 10000);
            }
        });

   },

   /*    -------------------------------------------------------------
    Exportar DB

    Exportar la base de datos utilizando MALSUP submitForm
    -------------------------------------------------------------    */

   exportarDB: function() {

       var _form = $('form.main-form'),
           _action = _form.attr('action');

       _form.ajaxSubmit({
               url: _action,
               type: "POST",
               dataType: "json",
               beforeSubmit: function() {
                   messageMachine.bloquear();
                   alertify.log("<strong>Exportando los datos</strong>.<br/>Esto puede tardar unos minutos...");
               },
               success: function(responseText, statusText, xhr) {
                   messageMachine.desbloquear();
               },
               error: function(xhr, status, errorThrown ) {
                   messageMachine.desbloquear();
               }
           });

       _form.submit();

   },

   /*    -------------------------------------------------------------
    Importar DB

    Importar el fichero SQL previamente exportado
    -------------------------------------------------------------    */
   importarDB: function() {

       var fichero = $("input[name='userfile']"),
           form_upload = $(".form-upload");

       form_upload.ajaxForm({
            dataType: 'json',
            type: 'POST',
            cache: false,
            beforeSubmit: function() {
                messageMachine.bloquear();
                alertify.log("<strong>Importando los datos</strong>.<br/>Esto puede tardar unos minutos...", "", 0);
            },
            error: function(xhr, status){
                messageMachine.desbloquear();
                alertify.log('&iexcl;Vaya!... Ha ocurrido un error al intentar subir el fichero', 5000);
            },
            success: function(response){
                // Errores al subir :(
                if (response.errores){
                    messageMachine.desbloquear();
                    alertify.log(response.errores, 9000);
                    alertify.log('No se ha podido subir el fichero', 9000);
                }
                // Subio :)
                else{
                    messageMachine.desbloquear();
                    // El fichero fue subido al servidor
                    if (response.upload_success) {
                        if (response.database_restore) {
                            var msg = "<a href='" + base_url + "'>cargar la p&aacute;gina</a>";
                            alertify.log("Se han restaurado los datos. <strong>Se le recomienda:<br/>" + msg + "</strong>", "", 0);
                        }else{
                            alertify.log("No se han podido restaurar los datos.", "", 9000);
                        }
                    }
                    console.log(response);
                }
            }
        });

       // En viar el formulario
       if (fichero.length) {
            form_upload.submit();
       }else{
           alertify.log("Debe seleccionar un fichero", "", 6000);
       }
   },

   /*    -------------------------------------------------------------
    debeElegirEquipo, debeElegirProducto, debeElegirReccorido

    Evitar que los campos dependientes de X
    se desplieguen si aun no existe algun valor anterior
    -------------------------------------------------------------    */

   debeElegirEquipo: function(e) {
       // Prevenir que se depliegue la lista si no se ha seleccionado un equipo - cu?
       var ec = $('#fk_capacidad_carga_id'),
           valor = ec.select2("val");
       if (valor == "" || valor == null) {
           e.preventDefault();
           alertify.log('<strong>&iexcl;Informaci&oacute;n!</strong><br/>Debe elegir un Equipo (Cu&ntilde;a) antes de seleccionar estos valores.', '', 8000);
       }
   },
   debeElegirProducto: function(e) {
       // Prevenir que se depliegue la lista si no se ha seleccionado un producto
       var ec = $('#fk_producto_id'),
           valor = ec.select2("val");
       if (valor == "" || valor == null) {
           e.preventDefault();
           alertify.log('<strong>&iexcl;Informaci&oacute;n!</strong><br/>Debe elegir un Producto antes de seleccionar estos valores.', '', 8000);
       }
   },
   debeElegirRecorrido: function(e) {
       // Prevenir que se depliegue la lista si no se ha seleccionado un recorrido
       var ec = $('#fk_carga_descarga_id'),
           valor = ec.select2("val");
       if (valor == "" || valor == null) {
           e.preventDefault();
           alertify.log('<strong>&iexcl;Informaci&oacute;n!</strong><br/>Debe elegir un Recorrido <span class="muted">(Carga/Descarga)</span> antes de seleccionar estos valores.', '', 8000);
       }
   },

   debeElegirMunicipio: function(e) {
       // Prevenir que se despliegue la lista si no se ha seleccionado un municipio
       var ec = $('#fk_municipio_id'),
           valor = ec.select2("val");
       if (valor == "" || valor == null) {
           e.preventDefault();
           alertify.log('<strong>&iexcl;Informaci&oacute;n!</strong><br/>Debe elegir un Municipio antes de seleccionar estos valores.', '', 8000);
       }
   },

   debeElegirLugarCarga: function(e) {
       // Prevenir que se despliegue la lista se no se ha seleccionado un lugar de carga
       var ec = $('#fk_lugar_carga_id'),
           valor = ec.select2("val");
       if (valor == "" || valor == null) {
           e.preventDefault();
           alertify.log('<strong>&iexcl;Informaci&oacute;n!</strong><br/>Debe elegir un Lugar de descarga antes de seleccionar estos valores.', '', 8000);
       }
   },

   /*    -------------------------------------------------------------
    fillProductos

    Rellenar productos: esta funcion permite rellenar los productos dependiendo
    del equipo cu? que se seleccione
    Permite comprobar que el equipo cu? tenga tiempo de carga y tiempo de descarga
    -------------------------------------------------------------    */

   fillProductos: function(e) {

       var capacidad_carga_id = $(e.target).select2("val"),
           request = null;
       if (capacidad_carga_id == "") return false;

       request = $.ajax({
           url: base_url + 'entrada/getProducto',
           data: {capacidad_carga_id: capacidad_carga_id},
           type: "POST",
           cache: false,
           dataType: "json",
           timeout: 10000,
           beforeSend: function(xhr) {
               // Mandar a mostrar pagina cargando
               messageMachine.bloquear();
           }
       });

       request.done(function(response) {
           messageMachine.desbloquear()

           // No existen tiempo de carga y/o de descarga
           if (response.no_time != undefined) {
               var message = "No existen ";
               if (response.no_time.tc != undefined) message += " <strong>tiempos de carga</strong>";
               if (response.no_time.td != undefined) message += " ni <strong>tiempos de descarga</strong>";
               message += " para este Equipo (Cu&ntilde;a) por lo que no se han obtenido <strong>Productos</strong>";
               alertify.log(message,"",10000);
               // Limpiar selects dependientes
               $('select#fk_producto_id').select2("val", "").empty().append("<option></option>");
               $('select#fk_carga_descarga_id').select2("val", "").empty().append("<option></option>");
               $('select#fk_modo_descarga_id').select2("val", "").empty().append("<option></option>");
           }
           // Repopular productos
           else {
               var options = "<option></option>",
                   p = response.lista_productos,
                   selectProducto = $('select#fk_producto_id');
               for (var i=0; i < p.length; i++) {
                   options += "<option data-foo='"+p[i].tipo+"' value='"+p[i].m_producto_id+"'>"+p[i].producto+"</option>";
               };
               selectProducto.empty().select2("val", "").append(options);
           }
       });

       request.always(function(xhr, status, error) {
           // Desbloquear la ventana
           if (status != "success") messageMachine.desbloquear();

           // Error: tiempo de espera
           if (status == "timeout") alertify.log("Tiempo de espera agotado.", "", 10000);
       });
   },

   /*    -------------------------------------------------------------
    fillRecorridos

    Rellenar recorridos: esta funcion permite rellenar los recorridos deependiendo
    de los productos que se seleccionen
    -------------------------------------------------------------    */
   fillRecorridos: function(e) {

       var producto_id = $(e.target).select2("val"),
           capacidad_carga_id = $('select#fk_capacidad_carga_id').select2("val"),
           request = null;
       if (producto_id == "") return false;

       request = $.ajax({
           url: base_url + 'entrada/getRecorridos',
           data: {producto_id: producto_id, capacidad_carga_id: capacidad_carga_id},
           type: "POST",
           cache: false,
           dataType: "json",
           timeout: 10000,
           beforeSend: function(xhr) {
               // Mandar a mostrar pagina cargando
               messageMachine.bloquear();
           }
       });

       request.done(function(response) {
           messageMachine.desbloquear();

           // No existen recorridos
           if (response.cd != undefined) {
               var message = "No existen Recorridos (Carga/Descarga) disponibles para el equipo y el producto seleccionados";
               alertify.log(message,"",10000);
               // Limpiar selects dependientes
               $('select#fk_carga_descarga_id').select2("val", "").empty().append("<option></option>");
               $('select#fk_modo_descarga_id').select2("val", "").empty().append("<option></option>");
           }
           else {
               // Repopular recorridos
               var options = "<option></option>",
                   r = response.lista_recorridos,
                   selectRecorridos = $('select#fk_carga_descarga_id');
               for (var i=0; i < r.length; i++) {
                   options += "<option data-foo='"+r[i].lugar_carga+" &rarr; "+r[i].lugar_descarga+"' value='"+r[i].carga_descarga_id+"'>"+r[i].codigo+"</option>";
               };
               selectRecorridos.select2("val", "").empty().append(options);

               // Repopular modos de descarga
               var options = "<option></option>",
                   md = response.lista_modos_descarga,
                   selectModoDescarga = $('select#fk_modo_descarga_id');
               for (var i=0; i < md.length; i++) {
                   options += "<option value='"+md[i]['m_modo_descarga_id']+"'>"+md[i]['modo']+"</option>";
               };
               selectModoDescarga.select2("val", "").empty().append(options);
           }
       });

       request.always(function(xhr, status, error) {
           // Desbloquear la ventana
           if (status != "success") messageMachine.desbloquear();

           // Error: tiempo de espera
           if (status == "timeout") alertify.log("Tiempo de espera agotado.", "", 10000);
       });
   },

   /*--------------------------------------------------------------------------
    * fillMunicipios (minorista)
    *
    * Rellenar municipios: esta funcion permite rellenar los municipios dependiendo
    * de los productos y de los equipos(cu?) que se seleccionen
    *
    * @Esta funcion es desencadenada por el select de productos en su metodo onchange
    --------------------------------------------------------------------------*/
   fillMunicipios: function(e)
   {
       var producto_id = $(e.target).select2("val"),
           capacidad_carga_id = $('select#fk_capacidad_carga_id').select2("val"),
           r = undefined;

       if (producto_id == "") return false;

      r = $.ajax({
          url: base_url+'entrada/getMunicipios',
          data: {producto_id: producto_id, capacidad_carga_id: capacidad_carga_id},
          type: 'post',
          cache: false,
          dataType: "json",
          beforeSend: function(xhr) {
            messageMachine.bloquear();
          }
      });

      // Done
      r.done(function(response) {
          messageMachine.desbloquear();

          // No existen municipios
          if (response.ld_status == "0")
          {
              var message = "No existen Municipios disponibles para el equipo y producto seleccionados";
              alertify.log(message,"",10000);
              // Limpiar selects dependientes
              $('select#fk_municipio_id').select2("val", "").empty().append("<option></option>");
              $('select#fk_modo_descarga_id').select2("val", "").empty().append("<option></option>");
          }
          else if(response.ld_status == "1")
          {
              // Repopular municipios
              var options = "<option></option>",
                  re = response.ld_list,
                  selectMunicipios = $('select#fk_municipio_id');
              for (var i=0; i < re.length; i++) {
                   options += "<option value='"+re[i].m_lugar_descarga_id+"'>"+re[i].lugar_descarga+"</option>";
              };
              selectMunicipios.select2("val", "").empty().append(options);

              // Repopular modos de descarga
               var options = "<option></option>",
                   md = response.md_list,
                   selectModoDescarga = $('select#fk_modo_descarga_id');
               for (var i=0; i < md.length; i++) {
                   options += "<option value='"+md[i]['m_modo_descarga_id']+"'>"+md[i]['modo']+"</option>";
               };
               selectModoDescarga.select2("val", "").empty().append(options);
          }
      });

      // Fail
      r.fail(function(xhr, status, error) {
          alert("error");
      });

      // always
      r.always(function(xhr,status, error) {
        setTimeout(function() {messageMachine.desbloquear();}, 1000);
      });


   },

   /*--------------------------------------------------------------------------
    * fillLugarCarga (minorista)
    *
    * Rellenar lugares d ecarga: esta funcion permite rellenar los lugares d ecarga dependiendo
    * de los productos y de los equipos(cu?) que se seleccionen
    *
    * @Esta funcion es desencadenada por el select de productos en su metodo onchange
    --------------------------------------------------------------------------*/
   fillLugarCarga: function(e)
   {
       var producto_id = $(e.target).select2("val"),
           capacidad_carga_id = $('select#fk_capacidad_carga_id').select2("val"),
           r = undefined;

       if (producto_id == "") return false;

      r = $.ajax({
          url: base_url+'entrada/getLugarCarga',
          data: {producto_id: producto_id, capacidad_carga_id: capacidad_carga_id},
          type: 'post',
          cache: false,
          dataType: "json",
          beforeSend: function(xhr) {
            messageMachine.bloquear();
          }
      });

      // Done
      r.done(function(response) {
          messageMachine.desbloquear();

          // No existen lugares de carga
          if (response.lc_status == "0")
          {
              var message = "No existen Lugares de carga disponibles para el equipo y producto seleccionados";
              alertify.log(message,"",10000);
              // Limpiar selects dependientes
              $('select#fk_lugar_carga_id').select2("val", "").empty().append("<option></option>");
              $('select#fk_modo_descarga_id').select2("val", "").empty().append("<option></option>");
          }
          else if(response.lc_status == "1")
          {
              // Repopular lugares de carga
              var options = "<option></option>",
                  re = response.lc_list,
                  selectLC = $('select#fk_lugar_carga_id');
              for (var i=0; i < re.length; i++) {
                   options += "<option value='"+re[i].m_lugar_carga_id+"'>"+re[i].lugar_carga+"</option>";
              };
              selectLC.select2("val", "").empty().append(options);

          }
      });

      // Fail
      r.fail(function(xhr, status, error) {

          alert("error");
      });

      // always
      r.always(function(xhr,status, error) {
        setTimeout(function() {messageMachine.desbloquear();}, 1000);

      });
   },

   //------------------------------------------------------------------------
   mantenimiento: function() {
       var _form = $('form.main-form'),
           _action = _form.attr('action'),
           _form_srz = _form.serialize();

       request = $.ajax({
           url: _action,
           data: _form_srz,
           type: "POST",
           cache: false,
           dataType: "json",
           timeout: 10000,
           beforeSend: function(xhr) {
               // Mandar a mostrar pagina cargando
               messageMachine.bloquear();
           }
       });

       request.done(function(response) {
           messageMachine.desbloquear();

           if (response.status == "optimizado") {
               alertify.log("Se han optimizados las tablas seleccionadas!", "", 9000);
           }
           if (response.status == "no_optimizado") {
              alertify.log("Algunas tablas no se han podido optimizar: <br/>" + response.tablas );
           }
       });

       request.always(function(xhr, status, error) {
           // Desbloquear la ventana
           if (status != "success") messageMachine.desbloquear();

           // Error: tiempo de espera
           if (status == "timeout") alertify.log("Tiempo de espera agotado.", "", 10000);
       });
   }
};


/*    -------------------------------------------------------------
    InitForm
    Inicializar los plugins de algunos objetos del formulario
    Ej: select2, formatear numeros, inicializar checkboxes, etc
    -------------------------------------------------------------    */
function initForm () {

     // Checkbox
     makeiCheck();

     // Datepicker
     $( 'input.datepicker' ).datepicker();

     // Tooltip
     $(document).tooltip({ position: { my: "center top", at: "center bottom+10" } });
     $('[data-title=top]').tooltip({ position: { my: "center bottom", at: "center top-5" } });
     $('#raphael').tooltip({position: {my: "left top", at: "left top-30"}});

     // Fileupload
     $('.fileupload').fileupload({
            name: "userfile"
     });

     // Tabs
     $('.tabs').tabs();

     // Accordion
     $('.accordion').accordion({ active:1, collapsible: true, heightStyle: "content" });

     // Buttonset
     $('.buttonset').buttonset();


     // Formatear numeros en inputs
     $( "[data-numeric-format=decimal]" ).autoNumeric({
         aSep: '.',
         aDec: ','
     });

     $( "[data-numeric-format=decimal-five]" ).autoNumeric({
         aSep: '.',
         aDec: ',',
         mDec: '5'
     });

     $( "[data-numeric-format=decimal-three]" ).autoNumeric({
         aSep: '.',
         aDec: ',',
         mDec: '3'
     });

     $( "[data-numeric-format=integer]" ).autoNumeric({
         aSep: '',
         vMin: '0',
         vMax: '99999999999',
         mDec: '0'
     });

     // Select2 generales
     $("select.select2").select2({
        minimumResultsForSearch: 10,
        allowClear: true,
        width: '230px',
        dropdownAutoWidth: true
     });


     /**
     * BUSCAR
     */

    // Select para buscar
    $("#dialog-buscar select.select2").select2({
        minimumResultsForSearch: 10,
        allowClear: false,
        width: '150px',
        dropdownAutoWidth: true
     });

     // Select para busquedas de fechas interactivas
     $("#dialog-buscar select[name*='buscar_campo']").on("change", function(e) {
         var valor = e.val,
             tr = $(this).parents('tr'),
             input_buscar_texto = tr.find($("input[name*=buscar_texto]"));

         if (valor.indexOf("fecha") != -1) {
             input_buscar_texto.datepicker();
         }else{
             input_buscar_texto.datepicker('destroy');
         }
     });


     /*    -------------------------------------------------------------
     Modificaciones en select para facilitar la UI
     -------------------------------------------------------------    */

     // Funcion para formatear el modo en que se ven los datos
     function formatFooOperario(state) {
         var opcion = state.element;
         return "<span><span class='text-short'><strong>"+state.text+"</strong></span><br><span class='muted sub-text-short'>"+$(opcion).data('foo')+"</span></span>";
     }
     function formatFooOperarioSelection(state) {
         var opcion = state.element;
         return "<p><em class='text-short'><strong>"+state.text+"</strong></em><em class='muted sub-text-short'>"+$(opcion).data('foo')+"</em></p>";
     }
     function formatFooProducto (state) {
         var opcion = state.element;
         return "<strong>"+state.text+"</strong><span class='muted sub-text-short'> ("+$(opcion).data('foo')+")</span>";
     }
     function formatFooRecorridosSelection(state) {
         var opcion = state.element;
         return "<strong>"+state.text+"</strong><br><span class='muted'>"+$(opcion).data('foo')+"</span>";
     }

     /**
      * Operarios
      * - Mostrar Chapa y Nombre Apellidos
      * - Permitir buscar por chapa y por Nombre Apellidos
      */
     $('select#fk_operario_id').select2({
         formatSelection:   formatFooRecorridosSelection,
         formatResult:      formatFooOperario,
         escapeMarkup:      function(markup) {
             return markup;
         },
         dropdownAutoWidth: true,
         matcher:           function(term, text, opt) {
             return text.toUpperCase().indexOf(term.toUpperCase()) > 0 || opt.attr('data-foo').toUpperCase().indexOf(term.toUpperCase()) >= 0;
         },
         width: '230px',
         containerCssClass: 'autoFixed'
     });
     // select para ayudante
     $('select#fk_ayudante_id').select2({
         formatSelection:   formatFooRecorridosSelection,
         formatResult:      formatFooOperario,
         escapeMarkup:      function(markup) {
             return markup;
         },
         allowClear: true,
         placeholder: "Seleccione un ayudante",
         dropdownAutoWidth: true,
         matcher:           function(term, text, opt) {
             return text.toUpperCase().indexOf(term.toUpperCase()) > 0 || opt.attr('data-foo').toUpperCase().indexOf(term.toUpperCase()) >= 0;
         },
         width: '230px',
         containerCssClass: 'autoFixed'
     });

     /**
      *Productos
      * - Mostrar Producto y tipo
      * - Permitir buscar por Producto y tipo
      * - Prevenir mostrar valores si no hay un equipo seleccionado
      */
     $('select#fk_producto_id').select2({
         formatResult:      formatFooProducto,
         dropdownAutoWidth: true,
         matcher:           function(term, text, opt) {
             return text.toUpperCase().indexOf(term.toUpperCase()) > 0 || opt.attr('data-foo').toUpperCase().indexOf(term.toUpperCase()) >= 0;
         },
         width: '230px',
         allowClear: true
     }).on('select2-opening', function(e) {
         mainForm.debeElegirEquipo(e);
     }).on('change', function(e) {

        // Mayorista
        if ($('#fk_carga_descarga_id').length)
        {
            mainForm.fillRecorridos(e);
        }
        // Minorista
        else if ($('#fk_municipio_id').length)
        {
            mainForm.fillMunicipios(e);
            mainForm.fillLugarCarga(e);
        }

     }).on('select2-removed', function(e) {
          $('select#fk_carga_descarga_id').select2("val", "");
          $('select#fk_municipio_id').select2("val", "");
          $('select#fk_modo_descarga_id').select2("val", "");
      });


     /**
      * Recorridos
      * - Mostrar codigo y lugar de carga / lugar de descarga
      * - Buscar por codigo y lugar de carga / lugar de descarga
      * - Prevenir mostrar valores si no hay un equipo seleccionado
      */
     $('select#fk_carga_descarga_id').select2({
         formatSelection:   formatFooRecorridosSelection,
         formatResult:      formatFooOperario,
         escapeMarkup:      function(markup) {
             return markup;
         },
         allowClear:        true,
         dropdownAutoWidth: true,
         matcher:           function(term, text, opt) {
             return text.toUpperCase().indexOf(term.toUpperCase()) > 0 || opt.attr('data-foo').toUpperCase().indexOf(term.toUpperCase()) >= 0;
         },
         width: '300px',
         containerCssClass: 'autoFixed'
     }).on('select2-opening', function(e) {
         mainForm.debeElegirProducto(e);
     }).on('select2-removed', function(e) {
          $('select#fk_modo_descarga_id').select2("val", "");
      });

     /**
      * Municipio (minosrista)
      * - Mostrar municipio
      * - Prevenir mostrar valores si no hay un producto seleccionado
      */
     $('select#fk_municipio_id').select({
         allowClear: true,
         dropdownAutoWidth: true,
         width: '300px'
     }).on('select2-opening', function(e) {
        mainForm.debeElegirProducto(e);
     }).on('select2-removed', function(e) {
         $('select#fk_modo_descarga_id').select2("val", "");
     });

     /**
      * Lugar de carga (minosrista)
      * -Mostrar lugar de carga
      * -Prevenir mostrar valores si no hay un producto seleccionado
      */
     $('select#fk_lugar_carga_id').select({
         allowClear: true,
         dropdownAutoWidth: true,
         width: '300px'
     }).on('select2-opening', function(e) {
        mainForm.debeElegirProducto(e);
     }).on('select2-removed', function(e) {
         $('select#fk_modo_descarga_id').select2("val", "");
     });

     /**
      * Modo de descarga
      * - Prevenir mostrar valores si no hay un equipo seleccionado
      * - Prevenir mostrar valores si no hay un municipio seleccionado
      */
      $('#fk_modo_descarga_id').on('select2-opening', function(e) {
        // Mayorista
        if ($('#fk_carga_descarga_id').length)
        {
            mainForm.debeElegirRecorrido(e);
        }
        // Minorista
        else if ($('#fk_municipio_id').length)
        {
            mainForm.debeElegirMunicipio(e);
            mainForm.debeElegirLugarCarga(e);
        }

      });

      /**
       * Capcidad de carga
       * - Rellenar los productos dependiendo de equipo cu?
       */
      $('#fk_capacidad_carga_id').on('change', function(e) {
          mainForm.fillProductos(e);
      }).on('select2-removed', function(e) {
          $('select#fk_producto_id').select2("val", "");
          $('select#fk_carga_descarga_id').select2("val", "");
          $('select#fk_modo_descarga_id').select2("val", "");
      });


      /**
       * Modo de descarga para amyoristas
       * - Detectar cuando el modo de descarga es turbina del equipo
       *   se debe de multiplicar el importe doble, pero antes se notifica al usuario
       */
      $('.fk_modo_descarga_mayorista_id').on('change', function(e) {
        var modo_descarga = $(this).val();
        if (modo_descarga == 5)
        {
            $(this).after('<p class="help-block text-info">Usted ha seleccionado <strong>Turbina del equipo,</strong><br/>el importe del viaje se multiplicara pr el coeficiente CDTMA.</p>');
        }
        else
        {
            $(this).next('p.help-block.text-info').remove();
        }
      });
}


/*    -------------------------------------------------------------
    Buscar machine
    Gestiona las busquedas
    -------------------------------------------------------------    */

var buscar_machine = {

    // Template para buscar
    search_template: function(this_modulo) {

        // Este objeto almacena los campos de la tabla
        var criterios_busqueda = {
            'empresa': {
                'empresa': 'Empresa'
            },
            'permiso': {
                'nombre': 'Nombre',
                'descripcion': 'Descripci&oacute;n'
            },
            'perfil': {
                'perfil': 'Perfil',
                'descripcion': 'Descripci&oacute;n',
                'no_eliminar': 'No eliminar'
            },
            'usuario': {
                'nombre': 'Nombre',
                'apellidos': 'Apellidos',
                'email': 'Email',
                'nombre_login': 'Nombre login',
                'fecha_alta': 'Alta',
                'empresa': 'Empresa',
                'perfil': 'Perfil'
            },
            'producto': {
                'producto': 'Producto',
                'tipo': 'Tipo'
            },
            'normativa': {
                'normativa': 'Normativa',
                'sigla': 'Sigla',
                'valor': 'Valor',
                'unidad_medida': 'Unidad_medida'
            },
            'modo_descarga': {
                'modo': 'Modo'
            },
            'equipo': {
                'numero_operacional': 'N&uacute;mero operacional'
            },
            'cuna': {
                'numero_operacional': 'N&uacute;mero operacional'
            },
            'causa_ausencia': {
                'causa': 'Causa de la ausencia'
            },
            'categoria_operario': {
                'categoria': 'Categor&iacute;a del operario',
                'nomenclador': 'Nomenclador',
                'min_capacidad_carga': 'M&iacute;n. capacidad de carga',
                'max_capacidad_carga': 'M&aacute;x. capacidad de carga'
            },
            'operario': {
                'chapa': 'Chapa',
                'nombre': 'Nombre',
                'apellidos': 'Apellidos',
                'categoria': 'Categor&iacute;a del operario',
                'ci': 'C. Identidad'
            },
            'tarifa_pago': {
                'categoria': 'Categor&iacute;a del operario',
                'tarifa_menor': 'Tarifa menor',
                'tarifa_mayor': 'Tarifa mayor',
                'tarifa_completa': 'Tarifa completa',
                'tarifa_interrupcion': 'Tarifa interrupci&oacute;n'
            },
            'capacidad_carga': {
                'm_equipo.numero_operacional': 'Equipo',
                'm_cuna.numero_operacional': 'Cu&ntilde;a',
                'viajes_promedio': 'Viajes promedio',
                'entregas_promedio': 'Entregas promedio',
                'capacidad_carga': 'Capacidad de carga',
                'tipo_de_producto': 'Tipo de producto',
                'capacidad_bombeo_turbina_equipo': 'Turbina del equipo',
                'capacidad_bombeo_gravedad2': 'Gravedad 2"',
                'capacidad_bombeo_gravedad3': 'Gravedad 3"',
                'capacidad_bombeo_gravedad4': 'Gravedad 4"',
            },
            'lugar_carga': {
                'lugar_carga': 'Lugar de carga'
            },
            'lugar_descarga': {
                'lugar_descarga': 'Lugar de descarga',
                'capacidad_bombeo_turbina_cliente': 'Capacidad de bombeo'
            },
            'carga_descarga': {
                'codigo': 'C&oacute;digo',
                'lugar_carga': 'Lugar de carga',
                'lugar_descarga': 'Lugar de descarga',
                'km_recorridos': 'Km. recorridos',
                'PU': 'PU',
                'C': 'C',
                'A': 'A',
                'T': 'T',
                'CM': 'CM',
                'CT': 'CT',
                'TM': 'TM',
                'CV': 'CV'
            },
            'tiempo_carga': {
                'm_equipo.numero_operacional': 'Equipo',
                'm_cuna.numero_operacional': 'Cu&ntilde;a',
                'tiempo_carga': 'Tiempo de carga',
                'producto': 'Producto',
                'lugar_carga': 'Lugar de carga'
            },
            'tiempo_descarga': {
                'm_equipo.numero_operacional': 'Equipo',
                'm_cuna.numero_operacional': 'Cu&ntilde;a',
                'tiempo_descarga': 'Tiempo de descarga',
                'producto': 'Producto',
                'modo': 'Modo de descarga',
                'lugar_descarga': 'Lugar de descarga'
            },
            'salida_salario_equipo': {
                'numero_operacional_equipo': 'Equipo',
                'numero_operacional_cuna': 'Cu&ntilde;a',
                'importe_viaje': 'Importe del viaje',
                'fecha_inicio_periodo_pago': 'Inicio del periodo de pago',
                'fecha_final_periodo_pago': 'Final del periodo de pago'
            },
            'salida_salario_trabajador': {
                'chapa': 'Chapa',
                'nombre': 'Nombre',
                'apellidos': 'Apellidos',
                'importe_viaje': 'Importe del viaje',
                'cumplimiento_norma': 'Cumplimiento de la norma',
                'horas_viaje': 'Horas de viaje',
                'horas_interrupto': 'Horas interrupto',
                'horas_no_vinculado': 'Horas no vinculado',
                'horas_nocturnidad_corta': 'Horas nocturnidad corta',
                'horas_nocturnidad_larga': 'Horas nocturnidad larga',
                'horas_capacitacion': 'Horas capacitaci&oacute;n',
                'horas_movilizado': 'Horas movilizado',
                'horas_feriado': 'Horas feriado',
                'horas_ausencia': 'Horas ausencia',
                'fecha_inicio_periodo_pago': 'Inicio del periodo de pago',
                'fecha_final_periodo_pago': 'Final del periodo de pago'
            },
            'salida_cumplimiento_norma': {
                'producto': 'Producto',
                'cumplimiento_norma': 'Cumplimiento de la norma',
                'fecha_inicio_periodo_pago': 'Inicio del periodo de pago',
                'fecha_final_periodo_pago': 'Final del periodo de pago'
            },
            'entrada': {
                'op.chapa': 'Chapa',
                'op.nombre': 'Nombre',
                'op.apellidos': 'Apellidos',
                'entrada.hoja_de_ruta': 'Hoja de ruta',
                'entrada.fecha_incidencia': 'Fecha de incidencia',
                'm_equipo.numero_operacional': 'Equipo',
                'm_cuna.numero_operacional': 'Cu&ntilde;a',
                'cc.capacidad_carga': 'Capacidad de carga',
                'p.producto': 'Producto',
                'p.tipo': 'Tipo de producto',
                'lc.lugar_carga': 'Lugar de carga',
                'ld.lugar_descarga': 'Lugar de descarga',
                'entrada.litros_entregados': 'Litros entregados',
                'entrada.km_recorridos_carga': 'Kil&oacute;metros recorridos',
                'md.modo': 'Modo de descarga',
                'entrada.numero_de_viajes': 'N&deg; de viajes',
                'entrada.numero_de_entregas': 'N&deg; de entregas',
                'entrada.horas_de_viaje': 'Horas de viaje',
                'entrada.horas_interrupto': 'Horas interrupto',
                'entrada.horas_no_vinculado': 'Horas no vinculado',
                'entrada.horas_nocturnidad_corta': 'Horas nacturnidad corta',
                'entrada.horas_nocturnidad_larga': 'Horas nocturnidad larga',
                'entrada.horas_capacitacion': 'Horas de capacitaci&oacute;n',
                'entrada.horas_movilizacion': 'Horas de movilizaci&oacute;n',
                'entrada.horas_feriado': 'Horas feriado',
                'entrada.horas_ausencia': 'Horas de ausencia',
                'ca.causa': 'Causa de la ausencia',
                'entrada.pago_feriado': 'Pago feriado',
                'entrada.importe_viaje': 'Importe del viaje',
                'entrada.cumplimiento_norma': 'Cumplimiento de la norma',
                'entrada.fecha_captacion': 'Fecha de captaci&oacute;n',
                'entrada.fecha_inicio_periodo_pago': 'Inicio del periodo de pago',
                'entrada.fecha_final_periodo_pago': 'Final del periodo de pago'
            },
            'claves_siscont': {
                'clave': 'Clave',
                'sigla': 'Sigla',
                'valor': 'Valor',
                'unidad_medida': 'Unidad_medida'
            }
        }

        var _options = "";
        $.each(criterios_busqueda, function(key, val) {
           if (key == this_modulo) {
               $.each(val, function(k, v) {
                   _options = _options + '<option value="' + k + '">' + v + '</option>';
               });
           }
        });

        var _template
            = '<tr>'
            + '<td>'
            + '<select class="select2" name="buscar_campo[]" data-placeholder="Columna">'
            + _options
            + '</select>'
            + '</td>'
            + '<td>'
            + '<select class="select2" name="buscar_criterio[]" data-placeholder="Criterio">'
            + '<option value="like_both">Contiene</option>'
            + '<option value="not_like_both">No contiene</option>'
            + '<option value="like_none">Es igual a</option>'
            + '<option value="not_like_none">No es igual a</option>'
            + '<option value="or_like_both">&oacute; Contiene</option>'
            + '<option value="or_not_like_both">&oacute; No contiene</option>'
            + '<option value="gt">Mayor que</option>'
            + '<option value="lt">Menor que</option>'
            + '</select>'
            + '</td>'
            + '<td>'
            + '<input type="text" class="span2" name="buscar_texto[]" placeholder="Texto a buscar" />'
            + '</td>'
            + '<td>'
            + '<button class="close">&times;</button>'
            + '</td>'
            + ' </tr>';
        return _template;

    }
}