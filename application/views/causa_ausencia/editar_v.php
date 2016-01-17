<?php echo form_open($modulo['nombre'] . '/editar', array('class'=>'main-form form-validate')); ?>

	<!-- Necesario para guardar -->
    <input type="hidden" name="accion" value="editar" />
    <input type="hidden" name="id" value="<?php echo $ibi->m_causa_ausencia_id; ?>" />
    
    <div>
        <label for="causa">Causa de la ausencia</label>
        <input type="text" name="causa" id="causa" value="<?php echo html_escape($ibi->causa); ?>" />
    </div>
	
</form>