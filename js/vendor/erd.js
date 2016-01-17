var Union = {
    
    sbox: null,
    ebox: null,
    point: [],
    
    unir: function(start, end) {
        
               
    },
    
    /**
     * Obtener el centro de un objeto
     * Las flechas deben de ir de un centro hacia otro
     */     
    center: function(shape) {
        
        var shBox = shape.getBBox()
            centerX = shBox.x + shBox.width/2,
            centerY = shBox.y + shBox.height/2;
        this.point['x'] = centerX;
        this.point['y'] = centerY;                
        
        return this.point;
    },
    
    /**
     * Dibujar una flecha lineal entre objetos
     * Como los objetos son circulos siempre habra igual distancia
     * desde el centro a cualquiera de los bordes
     */
    linearArrow: function(start, end) {
        
        this.center(start);
        var xc0 = this.point['x'],
            yc0 = this.point['y'];       
            
        this.center(end);
        var xc1 = this.point['x'],
            yc1 = this.point['y'];
       
        var radio = start.getBBox().width / 2;
        var angulo1 = Raphael.angle(xc1, yc1, xc0, yc0);
        var anguloRad1 = Raphael.rad(angulo1);
        
        var angulo2 = Raphael.angle(xc0, yc0, xc1, yc1),
            anguloRad2 = Raphael.rad(angulo2);
                
        var py0 = radio * Math.sin(anguloRad1) + yc0,
            px0 = radio * Math.cos(anguloRad1) + xc0;
        
        
        var py1 = radio * Math.sin(anguloRad2) + yc1,
            px1 = radio * Math.cos(anguloRad2) + xc1;
                                
        var arrow = paper.path("M"+px0+","+py0+"L"+px1+","+py1);
        arrow.attr({
            "arrow-end": "classic-wide-long",
            stroke: "#3079ed",
            "stroke-dasharray": "-"    
        });
        
    },
    
    /**
     * Dibujar una flecha con puntos de inflexion entre objetos
     */   
    
    vertexArrow: function(start, end) {
        
        this.center(start);
        var x0 = this.point['x'],
            y0 = this.point['y'];
            
        this.center(end);
        var x1 = this.point['x'],
            y1 = this.point['y'];
        
        var disCentral = Math.abs(x0-x1);
        
        var arrow = paper.path(
            "M" + x0 + "," + (y0 + 40) +
            "L" + x0 + "," + (y0 + 70) +
            "L" + x1 + "," + (y1 + 70) + 
            "L" + x1 + "," + (y1 + 40)
        );
        arrow.attr({
            "arrow-end": "classic-wide-long",
            stroke: "#3079ed",
            "stroke-dasharray": "-"    
        });
    }
}

var paper = new Raphael(document.getElementById("raphael"), 1200, 500);

// Crear los modulos
var empresa = paper.image(base_url+'css/relacion-img/empresa.png', 884,20,84,84);
empresa.attr({title: "Empresa", href: base_url+"empresa"});

var usuarios = paper.image(base_url+'css/relacion-img/usuarios.png',1028,20,84,84);
usuarios.attr({title: "Usuarios", href: base_url+"usuario"});

var perfiles = paper.image(base_url+'css/relacion-img/perfiles.png',1028,164,84,84);
perfiles.attr({title: "Perfiles", href: base_url+"perfile"});

var permisos = paper.image(base_url+'css/relacion-img/permisos.png',884,164,84,84);
permisos.attr({title: "Permisos", href: base_url+"permiso"});

var lugarCarga = paper.image(base_url+'css/relacion-img/lugar-carga.png',20,20,84,84);
lugarCarga.attr({title: "Lugar de carga", href: base_url+"lugar_carga"});

var tiempoCarga = paper.image(base_url+'css/relacion-img/tiempo-carga.png',164,20,84,84);
tiempoCarga.attr({title: "Tiempo de carga", href: base_url+"tiempo_carga"});

var cargaDescarga = paper.image(base_url+'css/relacion-img/carga-descarga.png',20,164,84,84);
cargaDescarga.attr({title: "Carga y descarga", href: base_url+"carga_descarga"});

var productos = paper.image(base_url+'css/relacion-img/productos.png',164,164,84,84);
productos.attr({title: "Productos", href: base_url+"producto"});

var lugarDescarga = paper.image(base_url+'css/relacion-img/lugar-descarga.png',20,308,84,84);
lugarDescarga.attr({title: "Lugares de descarga", href: base_url+"lugar_descarga"});

var tiempoDescarga = paper.image(base_url+'css/relacion-img/tiempo-descarga.png',164,308,84,84);
tiempoDescarga.attr({title: "Tiempo de descarga", href: base_url+"tiempo_descarga"});

var modoDescarga = paper.image(base_url+'css/relacion-img/modo-descarga.png',308,308,84,84);
modoDescarga.attr({title: "Modos de descarga", href: base_url+"modo_descarga"});

var equipo = paper.image(base_url+'css/relacion-img/equipo.png',452,308,84,84);
equipo.attr({title: "Equipo", href: base_url+"equipo"});

var capacidadCarga = paper.image(base_url+'css/relacion-img/capacidad-carga.png',596,308,84,84);
capacidadCarga.attr({title: "Capacidad de carga", href: base_url+"capacidad_carga"});

var cuna = paper.image(base_url+'css/relacion-img/cuna.png',740,308,84,84);
cuna.attr({title: "Cuña", href: base_url+"cuna"});

var operarios = paper.image(base_url+'css/relacion-img/operarios.png',308,20,84,84);
operarios.attr({title: "Operarios", href: base_url+"operario"});

var categoriaOperario = paper.image(base_url+'css/relacion-img/categoria-operario.png',452,20,84,84);
categoriaOperario.attr({title: "Categoría de los operarios", href: base_url+"categoria_operario"});

var tarifaPago = paper.image(base_url+'css/relacion-img/tarifa-pago.png',596,20,84,84);
tarifaPago.attr({title: "Tarifas de pago", href: base_url+"tarifa_pago"});

var entrada = paper.image(base_url+'css/relacion-img/entrada.png',308,164,84,84);
entrada.attr({title: "Entradas", href: base_url+"entrada"});

var scn = paper.image(base_url+'css/relacion-img/salida-cumplimiento-norma.png',452,164,84,84);
scn.attr({title: "Salida: cumplimineto de la norma", href: base_url+"salida_cumplimiento_norma"});

var sst = paper.image(base_url+'css/relacion-img/salida-salario-trabajador.png',596,164,84,84);
sst.attr({title: "Salida: salario por trabajador", href: base_url+"salida_salario_trabajador"});

var sse = paper.image(base_url+'css/relacion-img/salida-salario-equipo.png',740,164,84,84);
sse.attr({title: "Salida: salario por equipos", href: base_url+"salida_salario_equipo"});

var causaAusencia = paper.image(base_url+'css/relacion-img/causa-ausencia.png',740,20,84,84);
causaAusencia.attr({title: "Causas de ausencia", href: base_url+"causa_ausencia"});

var normativas = paper.image(base_url+'css/relacion-img/normativas.png',884,308,84,84);
normativas.attr({title: "Normativas", href: base_url+"normativa"});

Union.linearArrow(empresa, usuarios);
Union.linearArrow(perfiles, usuarios);
Union.linearArrow(permisos, perfiles);
Union.linearArrow(lugarCarga, tiempoCarga);
Union.linearArrow(lugarCarga, cargaDescarga);
Union.linearArrow(productos, tiempoCarga);
Union.linearArrow(lugarDescarga, cargaDescarga);
Union.linearArrow(productos, tiempoDescarga);
Union.linearArrow(lugarDescarga, tiempoDescarga);
Union.linearArrow(modoDescarga, tiempoDescarga);
Union.linearArrow(equipo, capacidadCarga);
Union.linearArrow(cuna, capacidadCarga);
Union.linearArrow(categoriaOperario, operarios);
Union.linearArrow(categoriaOperario, tarifaPago);
Union.vertexArrow(capacidadCarga, tiempoDescarga);
Union.vertexArrow(capacidadCarga, modoDescarga);
Union.linearArrow(productos, lugarDescarga);
Union.linearArrow(productos, lugarCarga);


