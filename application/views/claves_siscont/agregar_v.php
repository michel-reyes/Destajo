<?php
/*
DESTAJO-MODULE

date: 2014.12.17
type: php module
path: application/views/claves_siscont/agregar_v.php

DESTAJO-MODULE-END
*/
?>

<?php echo form_open($modulo['nombre'] . '/agregar', array('class'=>'main-form form-validate')); ?>

	<!-- Necesario para guardar -->
    <input type="hidden" name="accion" value="agregar" />
    
    <div class="row">        
        <div class="span3">
            <label for="normativa">Clave</label>
            <input type="text" name="clave" id="clave" />
        </div>
        
        <div class="span3">
            <label>&nbsp;</label>
            <input class="span1 uppercase" type="text" name="sigla" id="sigla" placeholder="Sigla" />
        </div>        
    </div>
    
    <div class="row">
        <div class="span3">
            <label for="valor">Valor</label>
            <input type="text" name="valor" id="valor" data-numeric-format="integer" />
        </div> 
        
        <div class="span3">
            <label for="unidad_medida">Unidad de medida</label>
            <input class="span2" type="text" name="unidad_medida" id="unidad_medida" />
        </div>       
    </div>
	
</form>