<?php 
/*
DESTAJO-MODULE

date: 2014.03.10
type: php module
path: application/views/lugar_descarga/editar_v.php

DESTAJO-MODULE-END
*/
echo form_open($modulo['nombre'] . '/editar', array('class'=>'main-form form-validate')); ?>

	<!-- Necesario para guardar -->
    <input type="hidden" name="accion" value="editar" />
    <input type="hidden" name="id" value="<?php echo $ibi->m_lugar_descarga_id; ?>" />
    <?php
    // Algunas variables complicadas
    $cb = $ibi->capacidad_bombeo_turbina_cliente;
	$vm = $ibi->velocidad_media_a_k;
	$vmd = $ibi->velocidad_media_d;
    ?>
    
    <div class="row-fluid">
        <div class="span6">            
            <label for="lugar_descarga">Lugar de descarga</label>
            <input type="text" name="lugar_descarga" id="lugar_descarga" value="<?php echo html_escape($ibi->lugar_descarga); ?>" /> 
        </div>              
    </div>
    
    <!-- Capacidades de bombeo -->
    <div class="row-fluid">
    	
        <p><strong>Capacidad de bombeo</strong></p>
        <div class="span12">
            <table class="table-form-auxiliar">
                <tr>
                    <td>
                        <label class="label-i-check" for="tc1">Turbina del cliente</label>
                        <input <?php if ($cb != "" && $cb != 0.00): ?> checked="checked" <?php endif; ?> type="checkbox" id="tc1" />
                    </td>
                    <td>
                        <input <?php if ($cb == "" || $cb == 0.00): ?> disabled="" <?php endif; ?> class="span6" 
                        type="text" name="capacidad_bombeo_turbina_cliente" data-numeric-format="decimal"
                        value="<?php if ($cb != "" && $cb != 0.00) echo $ibi->capacidad_bombeo_turbina_cliente; ?>" />
                    </td>
                </tr>
            </table>
        </div>
        
    </div>
    
    <!-- Velocidades media (minorista) -->
    <div class="row-fluid">
    	
    	<p><strong>Velocidad media (Minorista)</strong></p>
    	<div class="span12">
    		<table class="table-form-auxiliar">
    			<tr>
    				<td>
    					<label class="label-i-check" for="vm2">Alcohol y Kerosina</label>
    					<input <?php if ($vm != "" && $vm != 0.00): ?> checked="checked" <?php endif; ?> type="checkbox" id="vm2" />
    				</td>
    				<td>
    					<input <?php if ($vm == "" || $vm == 0.00): ?> disabled="" <?php endif; ?> class="span6" 
    					type="text" name="velocidad_media_a_k" data-numeric-format="decimal" 
    					value="<?php if ($vm != "" && $vm != 0.00) echo $ibi->velocidad_media_a_k; ?>" />
    				</td>
    			</tr>
    			<tr>
    				<td>
    					<label class="label-i-check" for="vm3">Diesel</label>
    					<input <?php if ($vmd != "" && $vmd != 0.00): ?> checked="checked" <?php endif; ?> type="checkbox" id="vm3" />
    				</td>
    				<td>
    					<input <?php if ($vmd == "" || $vmd == 0.00): ?> disabled="" <?php endif; ?> class="span6" 
    					type="text" name="velocidad_media_d" data-numeric-format="decimal" 
    					value="<?php if ($vmd != "" && $vmd != 0.00) echo $ibi->velocidad_media_d; ?>" />
    				</td>
    			</tr>
    		</table>
    	</div>
    	
    </div>
    
    <!-- Productos -->
    <?php
    // Crear un array con los productos descargables en este lugar de descarga
    $lugar_descarga_producto = array();
    foreach ($lista_productos_descargables->result() as $ldp) {
        $lugar_descarga_producto[$ldp->fk_producto_id][] = $ldp->fk_producto_id;
    }
     
    if ($lista_productos->num_rows() > 0): 
        $productos = array();
        foreach ($lista_productos->result() as $lpro){
            $productos['productos'][] = $lpro->producto;
            $productos['id'][] = $lpro->m_producto_id;
        }
        ?>
        <div class="row-fluid">        
            <p><strong>Seleccion los productos que son descargados en este lugar</strong></p>   
                 
            <div class="span6">
                <table class="table-form-auxiliar">
                <?php for ($i=0; $i<6; $i++): ?>
                   <tr>
                       <td>                           
                           <label for="<?php echo $productos['id'][$i]; ?>" class="label-i-check"><?php echo $productos['productos'][$i]; ?></label>
                           <input <?php if(array_key_exists($productos['id'][$i], $lugar_descarga_producto)): ?> checked="checked" <?php endif; ?> 
                           type="checkbox" id="<?php echo $productos['id'][$i]; ?>" name="productos[]" value="<?php echo $productos['id'][$i]; ?>" />
                       </td>
                   </tr>
                <?php endfor; ?>
                </table>
            </div>
                
            <div class="span5">
                <table class="table-form-auxiliar">
                <?php for ($i=6, $j=count($productos['productos'])-1; $i<=$j; $i++): ?>
                   <tr>
                       <td>                           
                           <label for="<?php echo $productos['id'][$i]; ?>" class="label-i-check"><?php echo $productos['productos'][$i]; ?></label>
                           <input <?php if(array_key_exists($productos['id'][$i], $lugar_descarga_producto)): ?> checked="checked" <?php endif; ?> 
                           type="checkbox" id="<?php echo $productos['id'][$i]; ?>" name="productos[]" value="<?php echo $productos['id'][$i]; ?>" />
                       </td>
                   </tr>
                <?php endfor; ?>
                </table>
            </div>
                    
        </div>
    <?php else: ?>
        <div class="alert">
            <strong>&iexcl;Advertencia!</strong> No hay productos para mostrar
        </div>
    <?php endif; ?>
	
</form>