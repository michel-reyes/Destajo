<?php echo form_open($modulo['nombre'] . '/agregar', array('class'=>'main-form form-validate')); ?>

	<!-- Necesario para guardar -->
    <input type="hidden" name="accion" value="agregar" />
    
    <div>
    	<label for="producto">Nombre del producto</label>
    	<input type="text" name="producto" id="producto"  /> 
    	
    	<label for="tipo">Tipo de producto</label>
    	<select name="tipo" id="tipo" class="select2">
    	    <option value="Blanco">Blanco</option>
    	    <option value="GLP">GLP</option>
    	    <option value="Negro">Negro</option>
    	</select>
    </div>
	
</form>