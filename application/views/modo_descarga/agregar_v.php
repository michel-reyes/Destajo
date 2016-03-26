<?php echo form_open($modulo['nombre'] . '/agregar', array('class'=>'main-form form-validate')); ?>

	<!-- Necesario para guardar -->
    <input type="hidden" name="accion" value="agregar" />
    
    <div>
    	<label for="modo">Modo de descarga</label>
    	<input type="text" name="modo" id="modo"  />    	
    </div>
	
</form>