<?php echo form_open($modulo['nombre'] . '/agregar', array('class'=>'main-form form-validate')); ?>

	<!-- Necesario para guardar -->
    <input type="hidden" name="accion" value="agregar" />
    
    <div class="row">
        
        <div class="span3">
            <label for="categoria">Categor&iacute;a</label>
            <input type="text" name="categoria" id="categoria"  /> 
        </div>
        <div class="span3">
            <label for="nomenclador">&nbsp;</label>
            <input class="uppercase span2" type="text" name="nomenclador" id="nomenclador" placeholder="Nomenclador" /> 
        </div>        
    	   	
    </div>
    
    <div class="row">
        
        <div class="span6">
            <label for="min_capacidad_carga">M&iacute;nimo de capacidad de carga</label>
            <span class="help-block">
                El m&iacute;nimo de capacidad de carga que debe tener el equipo<br />
                para que el operario entre en esta categor&iacute;a.
            </span>
            <input data-numeric-format="decimal" type="text" name="min_capacidad_carga" id="min_capacidad_carga"  />
        </div>
        
    </div>
    
    <div class="row">
        
        <div class="span6">
            <label for="max_capacidad_carga">M&aacute;ximo de capacidad de carga</label>
            <span class="help-block">
                El m&aacute;ximo de capacidad de carga que puede tener el equipo<br />
                para que el operario entre en esta categor&iacute;a.
            </span>
            <input data-numeric-format="decimal" type="text" name="max_capacidad_carga" id="max_capacidad_carga"  />
        </div>
        
    </div>
	
</form>