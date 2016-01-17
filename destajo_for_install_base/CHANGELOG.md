1.5.0 (07/11/2014)
------------------
1- salida_salario_trabajador->layout_v
2- salida_salario_trabajador (controller)
3- Math
5- Se agrego el campo fondo horario en la tabla periodo de pago
6- Se modifico perido_de_pago_m
7- Se modifico periodo_de_pago->apertura_v
8- Se agregao todo un modulo para claves del siscont, incluyendo tabla en la base de datos, se agregaron permisos y modificciones en el perfil tecnico para dicho modulo
9-Se agrego el campo ci "carne de identidad" en la tabla m_operario: este dato es requierido por el modulo de siscont cuando se genera el XML
se creo en la bd un campo varchar de 11 no obligatorio porque si se crea obligatorio no permite guardarlo ya que hay datos en la bd en Codeigniter debe ponerse una validacion para obligatorio
10-Se modifico la funcion get_all_period de salida_salario_trabajador_m





1.4.0 (02/11/2014)
------------------

1. [Rafael:]La salida salario por trabajador impresa calcula mal la nocturnidad (la suma de la minorista sale doble). En el excel sale bien. [Se modifico: **salida_salario_trabajador->view->tabla_v**, **salida_salario_trabajador->view->imprimir_v**]
2. [Rafael:]La salida salario por trabajador en horas viaje, se suma mayorista y minorista, en v. minorista si sale solo la minorista. [Se modifico: **models->salida_salario_trabajador_m**]
3. [Rafael:]En la salida salario por equipos los viajes incrementados se suma el viaje más el incremento, debía ser solo el incremento, lo mismo que nos daba antes la salida salario por trabajador. [Se modifico: **tabla: salida_salario_equipo->query:ALTER TABLE `salida_salario_equipo` CHANGE COLUMN `importe_viaje` `importe_viaje` DECIMAL(6,2) UNSIGNED DEFAULT '0.00'**,**models->salida_salario_equipo_m**]


1.3.0 (23/09/2013)
------------------

 1. Se modificaros las funciones de convertir fechas a MySQL y a human para un mejor rendimiento
 2. Se creo el controllador **install** para crear la base de datos en caso de que no exista y para cargar el modulo de base de datos. Debido a esto urgen las siguientes modificaciones:
 3. En config/autoload/librarries se debe quitar *database* **(solo cuando se entrega una APP desde cero)**
 4.   En config/database de debe dejar en blanco los datos sobre la configuracion de la base de datos para que *install*  se encargue de ello
 5.   El nuevo link para inicializar la instalacion es: http://localhost/install donde aparececra el asistente de instalacion. Que se encargara de crear la base de datos y la configuracion necesaria en los ficheros de **config**
 6. El modulo periodo de pago fue modificado para comparar fechas en *sql* en vez de *strtotime* que era el modo en que se hacia, debido a que el modo *strtotime* tiene un metodo variable de encriptacion.
 
 7. Se inproviso un metodo basico en el modulo **entrada** para comprobar si se ha instalado destajo, comprobando si la libreria **database** esta cargada en autoload

1.2.1 (15/08/2013)
------------------

 *  Se cambio el concepto de la paginacion para mostrar mas informacion y mayor comprencion
 *  Se corrigio el numero total de entradas basandose en el periodo de pago
 * Se modifico la funcion para cnovertir fechas a sql y a date
 * Se modifico la periodo de **pago.php** la funcion para chequear si los datos de cierre de un periodo de pago son correctos, todos los datos se analizan en formato *sql* y no en formato *strtotime* ya que estos ultimos pueden tenen tener varias formas para una misma fecha

1.2.0 (12/07/2013)
------------------

*  Se actualizo la libreria "AutoNumeric" a la version: 1.9.12 
** "Normativa" en la tabla se cambio el valor decimal de 6,2 a 6,3 para aceptar hasta 3 valores decimales
*  "Normativa" los valores de entrada y los valores de la tabla se formatearon hasta 3 valores decimales 
*  "Entrada" se formatean los valores numericos antes de almacenarse en la base de datos
*  "Entrada" se formatean los valores numericos al mostrar en tabla
*  "Entrada" se agregaron los campos cuantia_horario_norctunidad_corta(larga) en la base de datos
** "Salida salario trabajador" se han agregado los campos cuantia_horario_norctunidad_corta(larga) en la base de datos
*  "Salida salario trabajador" se calcula la cuantia de la norcturnidad corta y larga y se muestra en la tabla de salida
*  En todos los modulos se cambio la forma de buscar para agilizar el proceso de busqueda
*  Se realizaron cmabios menores al contar el numero de busquedas realizadas


1.1.6 (3/07/2013)
-----------------

*  La "Entrada" y "Salidas" pueden buscarse por periodo presente, periodos anteriores y por ambos
*  Se realizaron cambios menores en cuanto a el accesso a modulos cuando esten los periodos de pago abiertos y cerrados
*  Cambios menores al mostrar datos en las tablas segun la fecha del periodo de pago (Acciones de editar, agregar) 
*  Cambios menores al mostrar tablas de contenido, se le agrega un efecto FadeInLeft
** "Entrada" y "Salidas" se agrega la funcionalidad de exportar a EXCEL


1.1.0 (28/06/2013)
------------------

*   Eliminar "entrada" fue actualizado
*   Arreglado el boton de autocalcular en tiempo de descarga
*   Editar "entrada"
*   Actualizado duplicar importe cuando se paga feriado
*   Buscar "entrada"
**  Cambios en todos los search_v.php para inicializar las busquedas con datepicker 
    en caso de ser un campo de fecha
**  Cambios en todos los modelos_m.php (funcion: create_search) para dar formato a la
    fecha segun el tipo de campo
*   "Entrada" la validacion de la pestaña vinculacion deja de ser obligatoria
*   "Entrada" se incropora la validacion de algunos datos si es que estos son viados en el formulario
*   "Capacidad de carga" no hacer obligatorias las capacidades de bombeo (porque puede que sea 
    turbina de cliente solamente)
*   "Capacidad de carga" al no hacer obligatorias las capacidades de bombeo se realizan cambios menores
    en "capacidad_carga_m.php" en las funciones agregar y editar
*   Arreglos menores en la vizualizacion de los menú
*   "Lugar de descarga" la validacion de capacidad de bombeo deja de ser obligatoria
*   El cambio anterior ocacionó errores en el calculo de tiempo de descarga que fueron corregidos
**  Se han agragado 3 nuevas normativas para el calculo del destajo progresivo
**  Se ha incorporado el calculo del importe del viaje basandose en el destajo progresivo
**  "Entrada" se facilita la insercion de datos corrigiendo posibles errores humanos
    Ejemplo: 
        1- Al introducir una capacidad de carga se analiza
            - tiempos de carga y descarga
            - se muestran solo los productos asociados al equipo
        2- Al introducir producto 
            - solo mostrar los recorridos asociados a el producto
            - No mostrar modos de descarga hasta no elegir un recorrido
        3- Todo el analisis anterior tambien se comprueba cuando se edita (NUEVO)
*  Se mejoro la visualizacion de la informacion de "No resultados"
*  Se mejoro la visualizacion de la ventana de inicio de session
** "Configuracion" se ha agregado una nueva funcionalidad para optimizar las tablas de la base de datos que
    tienen un uso frecuente en cuanto a las acciones de insertar, eliminar y actualizar
*  En todas las tablas de contenido principal se utiliza la cabaecera fija para saber siempre el nombre de la columna

