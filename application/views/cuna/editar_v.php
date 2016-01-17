<?php echo 
/*
DESTAJO-MODULE

date: 2014.03.10
type: php module
path: application/views/cuna/editar_v.php

DESTAJO-MODULE-END
*/
form_open($modulo['nombre'] . '/editar', array('class'=>'main-form form-validate')); ?>

	<!-- Necesario para guardar -->
    <input type="hidden" name="accion" value="editar" />
    <input type="hidden" name="id" value="<?php echo $ibi->m_cuna_id; ?>" />
    
    <div>
    	<label for="numero_operacional">N&uacute;mero operacional del equipo</label>
        <input type="text" name="numero_operacional" id="numero_operacional" value="<?php echo html_escape($ibi->numero_operacional); ?>" />    	  	
    </div>
	
</form>