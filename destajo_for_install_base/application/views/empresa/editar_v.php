<?php echo form_open($modulo['nombre'] . '/editar', array('class'=>'main-form form-validate')); ?>

	<!-- Necesario para guardar -->
    <input type="hidden" name="accion" value="editar" />
    <input type="hidden" name="id" value="<?php echo $ibi->empresa_id; ?>" />
    
    <div>
    	<label for="empresa">Nombre de la empresa</label>
    	<input type="text" name="empresa" id="empresa" value="<?php echo html_escape($ibi->empresa); ?>" />    	  	
    </div>
	
</form>