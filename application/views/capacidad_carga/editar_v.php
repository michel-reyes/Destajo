<?php 
/*
DESTAJO-MODULE

date: 2014.03.10
type: php module
path: application/views/capacidad_carga/editar_v.php

DESTAJO-MODULE-END
*/
echo form_open($modulo['nombre'] . '/editar', array('class'=>'main-form form-validate')); ?>

	<!-- Necesario para guardar -->
    <input type="hidden" name="accion" value="editar" />
    <input type="hidden" name="id" value="<?php echo $ibi->m_capacidad_carga_id; ?>" />
    
    <div class="row-fluid">
        
        <div class="span5">
            
            <label for="fk_equipo_id">N&uacute;mero operacional del equipo</label>
            <select name="fk_equipo_id" id="fk_equipo_id" class="select2">
                <?php if ($lista_equipos->num_rows() > 0): ?>
                    <?php foreach ($lista_equipos->result() as $equipo): ?>
                        <option <?php if($equipo->m_equipo_id == $ibi->fk_equipo_id): ?> selected="selected" <?php endif; ?> value="<?php echo $equipo->m_equipo_id; ?>"><?php echo $equipo->numero_operacional; ?></option>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>
            
            <label for="fk_cuna_id">N&uacute;mero operacional de la cu&ntilde;a</label>
            <select name="fk_cuna_id" id="fk_cuna_id" class="select2" data-placeholder="Cu&ntilde;as">
                <option></option>
                <?php if ($lista_cunas->num_rows() > 0): ?>
                    <?php foreach ($lista_cunas->result() as $cuna): ?>
                        <option <?php if($cuna->m_cuna_id == $ibi->fk_cuna_id): ?> selected="selected" <?php endif; ?> value="<?php echo $cuna->m_cuna_id; ?>"><?php echo $cuna->numero_operacional; ?></option>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>
            
            <label for="capacidad_carga">Capacidad de carga</label>
            <input value="<?php echo $ibi->capacidad_carga; ?>" type="text" name="capacidad_carga" id="capacidad_carga" data-numeric-format="integer" />
            
            <label for="viajes_promedio">Viajes promedio</label>
            <input value="<?php echo $ibi->viajes_promedio; ?>" type="text" name="viajes_promedio" id="viajes_promedio" data-numeric-format="integer" />
           	
           	<label for="entregas_promedio">Entregas promedio (Minorista)</label>
            <input value="<?php echo $ibi->entregas_promedio; ?>" type="text" name="entregas_promedio" id="entregas_promedio" data-numeric-format="integer" />
            
            <label for="tipo_de_producto">Tipo de producto</label>
            <select name="tipo_de_producto" id="tipo_de_producto" class="select2">
                <option <?php if ($ibi->tipo_de_producto == "Blanco"): ?> selected="selected" <?php endif; ?> value="Blanco">Blanco</option>
                <option <?php if ($ibi->tipo_de_producto == "GLP"): ?> selected="selected" <?php endif; ?> value="GLP">GLP</option>
                <option <?php if ($ibi->tipo_de_producto == "Negro"): ?> selected="selected" <?php endif; ?> value="Negro">Negro</option>
            </select>         
            
        </div>
        
        <!-- Capacidades de bombeo -->
        <div class="span6 offset1">
            
            <p><strong>Capacidades de bombeo </strong> <span class="muted">(descarga)</span></p>
            
            <?php 
            if ($lista_modo_descarga->num_rows() > 0):
             
            // Crear un array con las capacidades de bombeo
            $capacidad_bombeo = array();
            foreach ($capacidades_bombeo->result() as $cb) {
                $capacidad_bombeo[$cb->fk_modo_descarga_id][] = $cb->fk_modo_descarga_id;
                $capacidad_bombeo['capacidad'][$cb->fk_modo_descarga_id] = $cb->capacidad_bombeo;
            }
            ?>
            <table class="table-form-auxiliar">
                <tbody>
                    <?php foreach ($lista_modo_descarga->result() as $md): $data=FALSE; ?>
                        <?php if ($md->modo == "Turbina del cliente") continue; ?>
                        <tr>
                            <td>
                                <label for="<?php echo $md->m_modo_descarga_id; ?>" class="label-i-check"><?php echo $md->modo; ?></label>
                                <input <?php if (array_key_exists($md->m_modo_descarga_id, $capacidad_bombeo)): $data=TRUE; ?> checked="checked" <?php endif; ?> type="checkbox" id="<?php echo $md->m_modo_descarga_id; ?>" />
                            </td>
                            <td>
                                <input <?php if ($data==FALSE): ?> disabled="" <?php endif; ?> class="span10" type="text" data-numeric-format="decimal" 
                                name="capacidad_bombeo[<?php echo $md->m_modo_descarga_id; ?>]"
                                value="<?php if ($data==TRUE) echo $capacidad_bombeo['capacidad'][$md->m_modo_descarga_id]; ?>" />
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>    
            </table>        
            <?php else: ?>
                <div class="alert">
                    <strong>&iexcl;Advertencia!</strong> No hay modos de descarga
                </div>
            <?php endif; ?>
            
        </div>       
                
    </div>
	
</form>