<?php echo form_open($modulo['nombre'] . '/agregar', array('class'=>'main-form form-validate')); ?>

	<!-- Necesario para guardar -->
    <input type="hidden" name="accion" value="agregar" />
    
    <div>
    	<label for="empresa">Nombre de la empresa</label>
    	<input type="text" name="empresa" id="empresa"  />     	
    </div>
	
</form>