<?php echo form_open($modulo['nombre'] . '/editar', array('class'=>'main-form form-validate')); ?>

	<!-- Necesario para guardar -->
    <input type="hidden" name="accion" value="editar" />
    <input type="hidden" name="id" value="<?php echo $ibi->m_categoria_operario_id; ?>" />
    
    <div class="row">
        
        <div class="span3">
            <label for="categoria">Categor&iacute;a</label>
            <input type="text" name="categoria" id="categoria" value="<?php echo html_escape($ibi->categoria); ?>" /> 
        </div>
        <div class="span3">
            <label for="nomenclador">&nbsp;</label>
            <input class="uppercase span2" type="text" name="nomenclador" id="nomenclador" placeholder="Nomenclador"
            value="<?php echo html_escape($ibi->nomenclador); ?>" /> 
        </div>        
            
    </div>
    
    <div class="row">
        
        <div class="span6">
            <label for="min_capacidad_carga">M&iacute;nimo de capacidad de carga</label>
            <span class="help-block">
                El m&iacute;nimo de capacidad de carga que debe tener el equipo<br />
                para que el operario entre en esta categor&iacute;a.
            </span>
            <input data-numeric-format="decimal" type="text" name="min_capacidad_carga" id="min_capacidad_carga" 
             value="<?php echo $ibi->min_capacidad_carga; ?>" />
        </div>
        
    </div>
    
    <div class="row">
        
        <div class="span6">
            <label for="max_capacidad_carga">M&aacute;ximo de capacidad de carga</label>
            <span class="help-block">
                El m&aacute;ximo de capacidad de carga que puede tener el equipo<br />
                para que el operario entre en esta categor&iacute;a.
            </span>
            <input data-numeric-format="decimal" type="text" name="max_capacidad_carga" id="max_capacidad_carga" 
                value="<?php echo $ibi->max_capacidad_carga; ?>" />
        </div>
        
    </div>
	
</form>