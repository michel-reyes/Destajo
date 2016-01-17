<?php echo form_open($modulo['nombre'] . '/editar', array('class'=>'main-form form-validate')); ?>

	<!-- Necesario para guardar -->
    <input type="hidden" name="accion" value="editar" />
    <input type="hidden" name="id" value="<?php echo $ibi->m_modo_descarga_id; ?>" />
    
    <div>
    	<label for="modo">Modo de descarga</label>
        <input type="text" name="modo" id="modo" value="<?php echo html_escape($ibi->modo); ?>" />    	  	
    </div>
	
</form>