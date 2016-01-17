<?php
/*
DESTAJO-MODULE

date: 2014.03.10
type: php module
path: application/views/entrada/layout_v.php

DESTAJO-MODULE-END
*/
?>

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
                    
                    <!-- Nuevo -->
                    <?php if ($this->users->can('Entrada.Agregar')): ?>
                    <div class="btn-group">
                        <a href="#" class="btn btn-danger comando-nuevo">NUEVO</a>
                        <button type="button" class="btn btn-danger dropdown-toggle" data-toggle="dropdown">
                        	<b class="caret"></b>
                        </button>
                        <ul class="dropdown-menu">
                        	<li><a href="#" class="comando-nuevo">Mayorista</a></li>
                        	<li><a href="#" class="comando-nuevo-minorista">Minorista</a></li>
                        </ul>
                    </div>
                    <?php endif; ?>
                    
                    <!-- Editar, Eliminar -->
                    <div class="btn-group hide comando-editar-eliminar">
                        
                        <?php if ($this->users->can('Entrada.Editar')): ?>
                        <a href="#" class="btn comando-editar hide">Editar</a>
                        <?php endif; ?>
                        
                        <?php if ($this->users->can('Entrada.Eliminar')): ?>
                        <a href="<?php echo base_url($modulo['nombre'] . '/eliminar'); ?>" 
                        class="btn comando-eliminar hide">Eliminar</a>
                        <?php endif; ?>
                        
                    </div>
                    
                    <!-- Buscar -->
                    <div class="btn-group">
                        <?php
                        // Agregar el numero de busquedas actuales para este modulo al boton de "Buscar"
                        $b_campo = $this->session->userdata('buscar_campo_' . $modulo['nombre']);
                        if ($b_campo !== FALSE) $b_campo_count = count($b_campo);                     
                        ?>
                        <a href="#" class="btn comando-buscar"> Buscar
                            <?php if (isset($b_campo_count)) echo "<span>($b_campo_count)</span>"; ?>
                        </a>
                    </div>
                    
                    <!-- Exportar -->
                    <div class="btn-group">
                        <a href="<?php echo base_url($modulo['nombre'] . '/exportar'); ?>" class="comando-exportar-excel btn">
                           Eportar a Excel
                        </a>                       
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