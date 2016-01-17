<?php echo form_open($modulo['nombre'] . '/agregar', array('class'=>'main-form form-validate')); ?>

	<!-- Necesario para guardar -->
    <input type="hidden" name="accion" value="agregar" />
    
    <div>
    	<label for="causa">Causa de la ausencia</label>
    	<input type="text" name="causa" id="causa" />
    </div>
	
</form>