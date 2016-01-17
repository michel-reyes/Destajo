<?php echo form_open($modulo['nombre'] . '/editar', array('class'=>'main-form form-validate')); ?>

	<!-- Necesario para guardar -->
    <input type="hidden" name="accion" value="editar" />
    <input type="hidden" name="id" value="<?php echo $ibi->m_producto_id; ?>" />
    
    <div>
        <label for="producto">Nombre del producto</label>
        <input type="text" name="producto" id="producto" value="<?php echo html_escape($ibi->producto); ?>" /> 
        
        <label for="tipo">Tipo de producto</label>
        <select name="tipo" id="tipo" class="select2">
            <option <?php if (html_escape($ibi->tipo) == "Blanco"): ?> selected="selected" <?php endif; ?> value="Blanco">Blanco</option>
            <option <?php if (html_escape($ibi->tipo) == "GLP"): ?> selected="selected" <?php endif; ?> value="GLP">GLP</option>
            <option <?php if (html_escape($ibi->tipo) == "Negro"): ?> selected="selected" <?php endif; ?> value="Negro">Negro</option>
        </select>
    </div>
	
</form>