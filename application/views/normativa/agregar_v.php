<?php echo form_open($modulo['nombre'] . '/agregar', array('class'=>'main-form form-validate')); ?>

	<!-- Necesario para guardar -->
    <input type="hidden" name="accion" value="agregar" />
    
    <div class="row">        
        <div class="span3">
            <label for="normativa">Normativa</label>
            <input type="text" name="normativa" id="normativa" />
        </div>
        
        <div class="span3">
            <label>&nbsp;</label>
            <input class="span1 uppercase" type="text" name="sigla" id="sigla" placeholder="Sigla" />
        </div>        
    </div>
    
    <div class="row">
        <div class="span3">
            <label for="valor">Valor</label>
            <input type="text" name="valor" id="valor" data-numeric-format="decimal-three" />
        </div> 
        
        <div class="span3">
            <label for="unidad_medida">Unidad de medida</label>
            <input class="span2" type="text" name="unidad_medida" id="unidad_medida" />
        </div>       
    </div>
	
</form>