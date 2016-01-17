<?php echo form_open($modulo['nombre'] . '/agregar', array('class'=>'main-form form-validate')); ?>

	<!-- Necesario para guardar -->
    <input type="hidden" name="accion" value="agregar" />
    
    <div>
    	<label for="nombre">Nombre del permiso</label>
    	<span class="help-block">
    	    Debe seguir el siguiente patrón: Modulo.Permiso<br />
            Ejemplo: Usuario.Agregar (léase: puede <strong>Agregar</strong> en el modulo <strong>Usuario</strong>)
    	</span>
    	<input type="text" name="nombre" id="nombre"  />
    	
    	<label for="descripcion">Descripci&oacute;n</label>
    	<textarea name="descripcion" id="descripcion"></textarea>    	
    </div>
	
</form>