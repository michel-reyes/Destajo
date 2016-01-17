<?php
/*
DESTAJO-MODULE

date: 2014.03.10
type: php module
path: application/views/template/t_main_menu.php

DESTAJO-MODULE-END
*/
?>

<div class="navbar navbar-googlebar">
    <div class="navbar-inner navbar-fixed-top">

        <!-- Titulo -->
        <a class="brand" href="#">
            <span class="text-error">Destajo</span>
            <span class="muted">(<?php echo $modulo['display']; ?>)</span>
        </a>
        
        <!-- Menu de modulos -->
        <ul class="nav">
            
            <!-- Vista relaciones -->
            <li>
                <a href="<?php echo base_url('erd'); ?>">Vista global</a>
            </li>
            
            <!-- Configuracion -->
            <?php if ($this->users->can('Configurar.Ver')): ?>
            <li class="dropdown">
                <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                    Configuraci&oacute;n
                    <b class="caret"></b>
                </a>
                <ul class="dropdown-menu">
                    <li class="nav-header">Periodos de pago</li>                    
                    <li <?php if (! $this->users->can('PeriodoPago.Abrir')) echo "class='disabled'"; ?>>
                        <a class="comando-periodo-apertura" href="<?php echo base_url('periodo_pago/apertura'); ?>">Apertura</a>
                    </li>
                    <li <?php if (! $this->users->can('PeriodoPago.Cerrar')) echo "class='disabled'"; ?>>
                        <a class="comando-periodo-cierre" href="<?php echo base_url('periodo_pago/cierre'); ?>">Cierre</a>
                    </li>
                    <li><a class="comando-periodo-estado" href="<?php echo base_url('periodo_pago/estado'); ?>">Estado</a></li>
                                        
                    <li class="divider"></li>
                    
                    <li class="nav-header">Salva y restaura</li>
                    <li><a href="#" data-url="<?php echo base_url('salva_restaura/salva'); ?>" class="comando-exportar-db">Salva</a></li>
                    <li><a href="#" data-url="<?php echo base_url('salva_restaura/restaura_form'); ?>" class="comando-importar-db">Restaura</a></li>
                    
                    
                    <?php if ($this->users->can('Configurar.Mantenimiento')): ?>
                        
                        <li class="divider"></li>                   
                    
                        <li class="dropdown-submenu">
                            <a tabindex="-1" href="#">Mantenimiento</a>
                            <ul class="dropdown-menu">
                                <li>
                                    <a class="comando-mantenimiento" href="<?php echo base_url('mantenimiento/optimmizar_tablas'); ?>">Optimizar base de datos</a>
                                </li>
                            </ul>
                        </li>
                    <?php endif; ?>
                    
                    <li class="divider"></li>
                    
                    <li class="dropdown-submenu">
                        <a tabindex="-1" href="#">Privilegios</a>
                        <ul class="dropdown-menu">
                            <li class="nav-header">Estructura</li>
                            <li <?php if (! $this->users->can('Permiso.Ver')) echo "class='disabled'"; ?>>
                                <a href="<?php echo base_url('permiso'); ?>">Permisos</a>
                            </li>
                            <li <?php if (! $this->users->can('Perfil.Ver')) echo "class='disabled'"; ?>>
                                <a href="<?php echo base_url('perfil'); ?>">Perfiles</a>
                            </li>
                            <li <?php if (! $this->users->can('Usuario.Ver')) echo "class='disabled'"; ?>>
                                <a href="<?php echo base_url('usuario'); ?>">Usuarios</a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </li>
            <?php endif; ?>
            
            <!-- Entradas y salidas -->            
            <li class="dropdown <?php if (isset($modulo['menu'])&&$modulo['menu'] == 2) echo 'active'; ?>">
                <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                    Entradas / Salidas
                    <b class="caret"></b>
                </a>
                <ul class="dropdown-menu">
                    <li <?php if($modulo['nombre'] == "entrada"): ?> class="active" <?php endif; ?>><a href="<?php echo base_url('entrada'); ?>">Entrada</a></li>
                    <li class="divider"></li>
                    <li class="nav-header">Salidas</li>
                    <li <?php if (! $this->users->can('SalidaSalarioEquipo.Ver')) echo "class='disabled'"; ?>>
                        <a href="<?php echo base_url('salida_salario_equipo'); ?>">Salario por equipo</a>
                    </li>
                    <li <?php if (! $this->users->can('SalidaSalarioTrabajador.Ver')) echo "class='disabled'"; ?>>
                        <a href="<?php echo base_url('salida_salario_trabajador'); ?>">Salario por trabajador</a>
                    </li>
                    <li <?php if (! $this->users->can('SalidaCumplimientoNorma.Ver')) echo "class='disabled'"; ?>>
                        <a href="<?php echo base_url('salida_cumplimiento_norma'); ?>">Cumplimiento de la norma</a>
                    </li>
                </ul>
            </li>
            
            <!-- Maestros -->
            <li class="dropdown <?php if (isset($modulo['menu'])&&$modulo['menu'] == '3') echo " active"; ?>">
                <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                    Maestros
                    <b class="caret"></b>
                </a>
                <ul class="dropdown-menu">
                    <li <?php if (! $this->users->can('Empresa.Ver')) echo "class='disabled'"; ?>>
                        <a href="<?php echo base_url('empresa'); ?>">Empresa</a>
                    </li>
                    <li class="<?php if (! $this->users->can('Equipo.Ver')) echo 'disabled'; if ($modulo['nombre'] == 'equipo') echo ' active'; ?>">
                        <a href="<?php echo base_url('equipo'); ?>">Equipos</a>
                    </li>
                    <li <?php if (! $this->users->can('Cuna.Ver')) echo "class='disabled'"; ?>>
                        <a href="<?php echo base_url('cuna'); ?>">Cu&ntilde;a</a>
                    </li>
                    <li <?php if (! $this->users->can('Producto.Ver')) echo "class='disabled'"; ?>>
                        <a href="<?php echo base_url('producto'); ?>">Productos</a>
                    </li>
                    <li <?php if (! $this->users->can('CausaAusencia.Ver')) echo "class='disabled'"; ?>>
                        <a href="<?php echo base_url('causa_ausencia'); ?>">Causas de ausencia</a>
                    </li>
                    <li class="<?php if (! $this->users->can('Normativa.Ver')) echo 'disabled'; if ($modulo['nombre'] == 'normativa') echo ' active'; ?>">
                        <a href="<?php echo base_url('normativa'); ?>">Normativas</a>
                    </li>
                    <li <?php if (! $this->users->can('CategoriaOperario.Ver')) echo "class='disabled'"; ?>>
                        <a href="<?php echo base_url('categoria_operario'); ?>">Categor&iacute;a de los operarios</a>
                    </li>
                    <li <?php if (! $this->users->can('Operario.Ver')) echo "class='disabled'"; ?>>
                        <a href="<?php echo base_url('operario'); ?>">Operarios</a>
                    </li>
                    <li <?php if (! $this->users->can('ModoDescarga.Ver')) echo "class='disabled'"; ?>>
                        <a href="<?php echo base_url('modo_descarga'); ?>">Modos de descarga</a>
                    </li>
                    <li class="<?php if (! $this->users->can('LugarCarga.Ver')) echo 'disabled'; if ($modulo['nombre'] == 'lugar_carga') echo ' active'; ?>">
                        <a href="<?php echo base_url('lugar_carga'); ?>">Lugares de carga</a>
                    </li>
                    <li <?php if (! $this->users->can('LugarDescarga.Ver')) echo "class='disabled'"; ?>>
                        <a href="<?php echo base_url('lugar_descarga'); ?>">Lugares de descarga</a>
                    </li>
                    <li class="<?php if (! $this->users->can('CapacidadCarga.Ver')) echo 'disabled'; if ($modulo['nombre'] == 'capacidad_carga') echo ' active'; ?>">
                        <a href="<?php echo base_url('capacidad_carga'); ?>">Capacidades de carga</a>
                    </li>
                    <li <?php if (! $this->users->can('TarifaPago.Ver')) echo "class='disabled'"; ?>>
                        <a href="<?php echo base_url('tarifa_pago'); ?>">Tarifas de pago</a>
                    </li>
                    <li class="divider"></li>
                    <li <?php if (! $this->users->can('ClavesSiscont.Ver')) echo "class='disabled'"; ?>>
                        <a href="<?php echo base_url('claves_siscont'); ?>">Claves SISCONT</a>
                    </li>
                </ul>
            </li>
            
            <!-- Carga y descarga -->
            <li class="dropdown <?php if (isset($modulo['menu'])&&$modulo['menu'] == 4) echo 'active'; ?>">
                <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                    Carga / Descarga
                    <b class="caret"></b>
                </a>
                <ul class="dropdown-menu">
                    <li <?php if (! $this->users->can('CargaDescarga.Ver')) echo "class='disabled'"; ?>>
                        <a href="<?php echo base_url('carga_descarga'); ?>">Carga y descarga</a>
                    </li>
                    <li class="divider"></li>
                    <li class="<?php if (! $this->users->can('TiempoCarga.Ver')) echo 'disabled'; if ($modulo['nombre'] == 'tiempo_carga') echo ' active'; ?>">
                        <a href="<?php echo base_url('tiempo_carga'); ?>">Tiempo de carga</a>
                    </li>
                    <li <?php if (! $this->users->can('TiempoDescarga.Ver')) echo "class='disabled'"; ?>>
                        <a href="<?php echo base_url('tiempo_descarga'); ?>">Tiempo de descarga</a>
                    </li>
                </ul>
            </li>
        </ul>
        
        <!-- 
            Menu de usuario 
            El usuario esta logueado
        -->
        <?php if ($this->session->userdata('nombre_login')): ?>
            
            <ul class="nav pull-right">
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                        <span class="muted">Hola, </span><strong><?php echo $this->session->userdata('nombre'); ?></strong>
                        <b class="caret"></b>
                    </a>
                    <ul class="dropdown-menu">
                        <li><li><?php echo anchor('auth/logOut', 'Cerrar sesi&oacute;n'); ?></li></li>
                    </ul>
                </li>
            </ul>
            
        <?php else: ?>
            
            <!-- El usuario no esta logueado -->
            <form class="navbar-form pull-right">
                <?php echo anchor('auth', 'Iniciar sesi&oacute;n', array('class'=>'btn')); ?>
            </form>
            
        <?php endif; ?>

    </div>
</div>