<?php 
/*
DESTAJO-MODULE

date: 2014.03.10
type: php module
path: application/views/cuna/agregar_v.php

DESTAJO-MODULE-END
*/
echo form_open($modulo['nombre'] . '/agregar', array('class'=>'main-form form-validate')); ?>

	<!-- Necesario para guardar -->
    <input type="hidden" name="accion" value="agregar" />
    
    <div>
    	<label for="numero_operacional">N&uacute;mero de la cu&ntilde;a</label>
    	<input type="text" name="numero_operacional" id="numero_operacional"  />
    </div>
	
</form>