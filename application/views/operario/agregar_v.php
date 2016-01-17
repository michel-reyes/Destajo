<?php echo form_open($modulo['nombre'] . '/agregar', array('class'=>'main-form form-validate')); ?>

	<!-- Necesario para guardar -->
    <input type="hidden" name="accion" value="agregar" />
    
    <div class="row">
        
        <div class="span3">
            <label for="chapa">Chapa</label>
            <input type="text" name="chapa" id="chapa" /> 
        </div>
    	    
        <div class="span3">
            <label for="ci">Carn&eacute; de identidad</label>
            <input type="text" name="ci" id="ci" data-numeric-format="integer" /> 
        </div>	
    </div>
    
    <div class="row">
        
        <div class="span3">
            <label for="nombre">Nombre y apellidos</label>
            <input type="text" name="nombre" id="nombre" placeholder="Nombre" /> 
        </div>
        <div class="span3">
            <label for="apellidos">&nbsp;</label>
            <input type="text" name="apellidos" id="apellidos" placeholder="Apellidos" /> 
        </div>
                
    </div>
    
    <div>
       
        <label for="fk_categoria_operario_id">Categor&iacute;a del operario</label>
        <select class="select2" name="fk_categoria_operario_id" id="fk_categoria_operario_id">
            <?php if($lista_categoria_operario->num_rows() > 0): ?>
                <?php foreach($lista_categoria_operario->result() as $co): ?>
                    <option value="<?php echo $co->m_categoria_operario_id; ?>"><?php echo $co->categoria; ?></option>
                <?php endforeach; ?>
            <?php endif; ?>
        </select>
                
    </div>
	
</form>