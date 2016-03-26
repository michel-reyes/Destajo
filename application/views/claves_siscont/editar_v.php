<?php
/*
DESTAJO-MODULE

date: 2014.12.17
type: php module
path: application/views/claves_siscont/editar_v.php

DESTAJO-MODULE-END
*/
?>

<?php echo form_open($modulo['nombre'] . '/editar', array('class'=>'main-form form-validate')); ?>

	<!-- Necesario para guardar -->
    <input type="hidden" name="accion" value="editar" />
    <input type="hidden" name="id" value="<?php echo $ibi->m_claves_siscont_id; ?>" />
    
    <div class="row">        
        <div class="span3">
            <label for="normativa">Clave</label>
            <input type="text" name="clave" id="clave" value="<?php echo html_escape($ibi->clave); ?>" />
        </div>
        
        <div class="span3">
            <label>&nbsp;</label>
            <input <?php if($this->users->can("ClavesSiscont.EditarSigla") == FALSE): ?> readonly="true" <?php endif; ?> class="span1 uppercase" type="text" name="sigla" id="sigla" placeholder="Sigla" value="<?php echo html_escape($ibi->sigla); ?>" />
        </div>        
    </div>
    
    <div class="row">
        <div class="span3">
            <label for="valor">Valor</label>
            <input type="text" name="valor" id="valor" data-numeric-format="integer" value="<?php echo $ibi->valor; ?>" />
        </div> 
        
        <div class="span3">
            <label for="unidad_medida">Unidad de medida</label>
            <input class="span2" type="text" name="unidad_medida" id="unidad_medida" value="<?php echo html_escape($ibi->unidad_medida); ?>" />
        </div>       
    </div>
	
</form>