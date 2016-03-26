<?php echo form_open('mantenimiento/optimmizar_tablas', array('class'=>'main-form form-validate')); ?>

    <!-- Necesario para guardar -->
    <input type="hidden" name="optimizar" value="true" />
    
    <div class="row">
        <div class="span8">
            <div class="alert alert-info">
                Esta opci&oacute;n debe usarse si ha borrado una gran parte de la tabla o 
                si ha hecho varios cambios en una tabla con registros de longitud variable.
                Es recomendable ejecutar esta acci&oacute;n una vez al mes y s&oacute;lo en ciertas tablas.<br />
                Es necesario optmizar con frecuencia las tablas resaltadas.
            </div>
        </div>
    </div>
    
    <div class="row">
    
        <?php 
        // Obtener lista de tablas
        $tables = $this->db->list_tables();
        $hightlightTables = array("tiempo_carga", "tiempo_descarga", "salida_cumplimiento_norma","salida_salario_equipo",
            "salida_salario_trabajador","lugar_descarga_producto","capacidad_bombeo_lugar_carga");
        ?>    
        
        <div class="span3">
            <?php for ($i = 1, $j = count($tables)/2; $i <= $j; $i++): ?>
                <div <?php if (in_array($tables[$i], $hightlightTables)): ?> class="hightlight-row" <?php endif; ?>>
                    <input <?php if (in_array($tables[$i], $hightlightTables)): ?> checked="checked" <?php endif; ?> type="checkbox" name="tablas[]" id="<?php echo $tables[$i]; ?>" value="<?php echo $tables[$i]; ?>" />
                    <label class="label-i-check" for="<?php echo $tables[$i]; ?>"><?php echo $tables[$i]; ?></label>
                </div>
            <?php endfor; ?>
        </div>
        
        <div class="span3 offset1">
            <?php for ($i = count($tables)/2+1, $j = count($tables); $i < $j; $i++): ?>
                <div <?php if (in_array($tables[$i], $hightlightTables)): ?> class="hightlight-row" <?php endif; ?>>
                    <input <?php if (in_array($tables[$i], $hightlightTables)): ?> checked="checked" <?php endif; ?> type="checkbox" name="tablas[]" id="<?php echo $tables[$i]; ?>" value="<?php echo $tables[$i]; ?>" />
                    <label class="label-i-check" for="<?php echo $tables[$i]; ?>"><?php echo $tables[$i]; ?></label>
                </div>
            <?php endfor; ?>
        </div>    
    
    </div>
    
</form>