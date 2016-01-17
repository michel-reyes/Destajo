<?php echo form_open($modulo['nombre'] . '/editar', array('class'=>'main-form form-validate')); ?>

	<!-- Necesario para guardar -->
    <input type="hidden" name="accion" value="editar" />
    <input type="hidden" name="id" value="<?php echo $ibi->m_normativa_id; ?>" />
    
    <div class="row">        
        <div class="span3">
            <label for="normativa">Normativa</label>
            <input type="text" name="normativa" id="normativa" value="<?php echo html_escape($ibi->normativa); ?>" />
        </div>
        
        <div class="span3">
            <label>&nbsp;</label>
            <input class="span1 uppercase" type="text" name="sigla" id="sigla" placeholder="Sigla" value="<?php echo html_escape($ibi->sigla); ?>" />
        </div>        
    </div>
    
    <div class="row">
        <div class="span3">
            <label for="valor">Valor</label>
            <input type="text" name="valor" id="valor" data-numeric-format="decimal-three" value="<?php echo $ibi->valor; ?>" />
        </div> 
        
        <div class="span3">
            <label for="unidad_medida">Unidad de medida</label>
            <input class="span2" type="text" name="unidad_medida" id="unidad_medida" value="<?php echo html_escape($ibi->unidad_medida); ?>" />
        </div>       
    </div>
	
</form>