<?php echo form_open($modulo['nombre'] . '/editar', array('class'=>'main-form form-validate')); ?>

	<!-- Necesario para guardar -->
    <input type="hidden" name="accion" value="editar" />
    <input type="hidden" name="id" value="<?php echo $ibi->m_lugar_carga_id; ?>" />
    
    <div class="row-fluid">
        <div class="span6">
            <label for="lugar_carga">Lugar de carga</label>
            <input type="text" name="lugar_carga" id="lugar_carga" value="<?php echo html_escape($ibi->lugar_carga); ?>" />
        </div>              
    </div>
    
    <!-- Capacidades de bombeo para lugar de carga -->
    <?php
    // Crear un array con las capacidades de bombeo
    $capacidad_bombeo = array();
    foreach ($capacidades_bombeo->result() as $cb) {
        $capacidad_bombeo[$cb->fk_producto_id][] = $cb->fk_producto_id;
        $capacidad_bombeo['capacidad'][$cb->fk_producto_id] = $cb->capacidad_bombeo;
    }
    
    // Crear un array con los lugares de carga    
    if ($lista_productos->num_rows() > 0):
        $productos = array();
        foreach ($lista_productos->result() as $lpro){
            $productos['productos'][] = $lpro->producto;
            $productos['id'][] = $lpro->m_producto_id;
        }
        // Crear UI
        ?>
        <br />
        <strong>Capacidades de bombeo por productos</strong>
        <br />
        <br />
        <div class="row-fluid">
            
            <div class="span6">
                <table class="table-form-auxiliar">
                    <?php for ($i=0; $i<6; $i++): $data=FALSE; ?>                
                        <tr>
                            <td> 
                               <label class="label-i-check" for="<?php echo $i; ?>"><?php echo $productos['productos'][$i]; ?></label>                      
                               <input <?php if(array_key_exists($productos['id'][$i], $capacidad_bombeo)): $data=TRUE; ?> checked="checked" <?php endif; ?> type="checkbox" id="<?php echo $i; ?>" />                                                      
                            </td>
                            <td>
                                <input <?php if ($data==FALSE): ?> disabled="" <?php endif; ?> 
                                class="span6" type="text" name="productos[<?php echo $productos['id'][$i]; ?>]" 
                                data-numeric-format="decimal" value="<?php if ($data==TRUE) echo $capacidad_bombeo['capacidad'][$productos['id'][$i]]; ?>" />
                            </td>
                        </tr> 
                    <?php endfor; ?>
                </table> 
            </div> 
            
            <div class="span6">
                <table class="table-form-auxiliar">
                    <?php for ($i=6, $j=count($productos['productos'])-1; $i<=$j; $i++): $data=FALSE; ?>                
                        <tr>
                            <td> 
                               <label class="label-i-check" for="<?php echo $i; ?>"><?php echo $productos['productos'][$i]; ?></label>                      
                               <input <?php if(array_key_exists($productos['id'][$i], $capacidad_bombeo)): $data=TRUE; ?> checked="checked" <?php endif; ?> type="checkbox" id="<?php echo $i; ?>" />                                                      
                            </td>
                            <td>
                                <input <?php if ($data==FALSE): ?> disabled="" <?php endif; ?> 
                                class="span6" type="text" name="productos[<?php echo $productos['id'][$i]; ?>]" 
                                data-numeric-format="decimal" value="<?php if ($data==TRUE) echo $capacidad_bombeo['capacidad'][$productos['id'][$i]]; ?>" />
                            </td>
                        </tr> 
                    <?php endfor; ?>
                </table> 
            </div> 
                      
        </div>
        <?php 
        
    ?>
    <?php else: ?>
        <div class="alert">
            <strong>&iexcl;Advertencia!</strong> No hay productos para mostrar.
        </div>
    <?php endif; ?>
	
</form>