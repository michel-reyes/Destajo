<!-- Dialogs -->
<div id="dialog-editar" title="Editar <?php echo strtolower($modulo['display']); ?>"></div>
<div id="dialog-nuevo" title="Agregar <?php echo strtolower($modulo['display']); ?>"></div>
<div id="dialog-buscar" title="Buscar <?php echo strtolower($modulo['display']); ?>"></div>

<div id="main-content">
	
	<div class="mc-inner">
		
		<!-- Main toolbar -->
	    <div class="navbar navbar-googlenav" id="main-toolbar">
            <div class="navbar-inner">                
                <div class="btn-toolbar">
                    
                    <!-- Autocalcular -->
                    <?php if ($this->users->can('SalidaSalarioEquipo.Calcular')): ?>
                    <div class="btn-group">
                        <a href="<?php echo base_url($modulo['nombre'] . '/autoCalc'); ?>" class="btn btn-success comando-autocalc">CALCULAR</a>
                    </div>
                    <?php endif; ?>
                    
                    <!-- Editar, Eliminar -->
                    <div class="btn-group hide comando-editar-eliminar">
                        <a href="<?php echo base_url($modulo['nombre'] . '/eliminar'); ?>" 
                        class="btn comando-eliminar hide">Eliminar</a>
                    </div>
                    
                    <!-- Buscar -->
                    <div class="btn-group">
                        <?php
                        // Agregar el numero de busquedas actuales para este modulo al boton de "Buscar"
                        $b_campo = $this->session->userdata('buscar_campo_salida_salario_equipo');
                        if ($b_campo) $b_campo_count = count($b_campo);                     
                        ?>
                        <a href="#" class="btn comando-buscar">Buscar 
                            <?php if (isset($b_campo_count)): ?>
                                <span>(<?php echo $b_campo_count; ?>)</span>
                            <?php endif; ?>
                        </a>
                    </div>
                    
                    <!-- Exportar -->
                    <div class="btn-group">
                        <a class="btn dropdown-toggle" data-toggle="dropdown" href="#">
                            M&aacute;s
                            <span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu">
                            <li class="nav-header">Exportar</li>
                            <li>
                                <a href="<?php echo base_url($modulo['nombre'] . '/exportar'); ?>" class="comando-exportar-excel">
                                   <i class="icon-file-alt"></i> Excel
                                </a>   
                            </li>
                            
                            <li class="divider"></li>
                            <li>
                                <a href="<?php echo base_url($modulo['nombre'] . '/imprimir'); ?>">
                                    <i class="icon-print"></i> Imprimir <span class="muted">(Vista previa)</span>
                                </a>
                            </li>
                        </ul>                        
                    </div>
                    
                    <!-- Paginacion -->
                    <div class="btn-group pull-right" id="paginacion-group"></div>

                </div>                
            </div>
        </div><!-- /Main toolbar -->
        
        
        <!-- Table content -->
        <div id="table-fixed-content"> </div>
		
	</div>
	
</div>