<?php echo form_open($modulo['nombre'] . '/agregar', array('class'=>'main-form form-validate')); ?>

	<!-- Necesario para guardar -->
    <input type="hidden" name="accion" value="agregar" />
    
    <div>
    	<label for="numero_operacional">N&uacute;mero operacional del equipo</label>
    	<input type="text" name="numero_operacional" id="numero_operacional"  />
    </div>
	
</form>