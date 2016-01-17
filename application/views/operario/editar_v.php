<?php echo form_open($modulo['nombre'] . '/editar', array('class'=>'main-form form-validate')); ?>

	<!-- Necesario para guardar -->
    <input type="hidden" name="accion" value="editar" />
    <input type="hidden" name="id" value="<?php echo $ibi->m_operario_id; ?>" />
    
    <div class="row">
        
        <div class="span3">
            <label for="chapa">Chapa</label>
            <input type="text" name="chapa" id="chapa" value="<?php echo html_escape($ibi->chapa); ?>" /> 
        </div>

        <div class="span3">
            <label for="ci">Carn&eacute; de identidad</label>
            <input type="text" name="ci" id="ci" data-numeric-format="integer" value="<?php echo html_escape($ibi->ci); ?>" /> 
        </div>
                
    </div>
    
    <div class="row">
        
        <div class="span3">
            <label for="nombre">Nombre y apellidos</label>
            <input type="text" name="nombre" id="nombre" placeholder="Nombre" value="<?php echo html_escape($ibi->nombre); ?>" /> 
        </div>
        <div class="span3">
            <label for="apellidos">&nbsp;</label>
            <input type="text" name="apellidos" id="apellidos" placeholder="Apellidos" value="<?php echo html_escape($ibi->apellidos); ?>" /> 
        </div>
                
    </div>
    
    <div>
       
        <label for="fk_categoria_operario_id">Categor&iacute;a del operario</label>
        <select class="select2" name="fk_categoria_operario_id" id="fk_categoria_operario_id">
           <?php if($lista_categoria_operario->num_rows() > 0): ?>
                <?php foreach($lista_categoria_operario->result() as $co): ?>
                    <option <?php if ($co->m_categoria_operario_id == $ibi->fk_categoria_operario_id): ?> selected="selected" <?php endif; ?> 
                        value="<?php echo $co->m_categoria_operario_id; ?>"><?php echo $co->categoria; ?></option>
                <?php endforeach; ?>
            <?php endif; ?>
        </select>
                
    </div>
	
</form>