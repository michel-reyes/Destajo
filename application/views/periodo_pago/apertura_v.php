<?php echo form_open('periodo_pago/apertura', array('class'=>'main-form form-validate')); 

/*
DESTAJO-MODULE

date: 2014.12.17
type: php module
path: application/views/periodo_pago/apertura_v.php

DESTAJO-MODULE-END
*/
?>
	<!-- Necesario para guardar -->
    <input type="hidden" name="accion" value="apertura" />
        
    <div class="row-fluid">
    	
    	<div class="span12">
    	    <label for="fecha_inicio_periodo_pago">Fecha de inicio del periodo de pago</label>
            <input class="datepicker" type="text" name="fecha_inicio_periodo_pago" id="fecha_inicio_periodo_pago"  />
    	</div>

        <div class="span12">
            <label for="fondo_horario">Fondo horario</label>
            <input type="text" name="fondo_horario" id="fondo_horario" data-numeric-format="decimal" />
        </div>
    	     	
    </div>
	
</form>