<?php echo form_open($modulo['nombre'] . '/editar', array('class'=>'main-form form-validate')); ?>

	<!-- Necesario para guardar -->
    <input type="hidden" name="accion" value="editar" />
    <input type="hidden" name="id" value="<?php echo $ibi->usuario_id; ?>" />
    
    <div class="row">
        <div class="span3">
            <label for="nombre">Nombre</label>
            <input type="text" name="nombre" id="nombre" value="<?php echo html_escape($ibi->nombre); ?>" />
        </div>
        <div class="span3">
            <label for="apellidos">Apellidos</label>
            <input type="text" name="apellidos" id="apellidos" value="<?php echo html_escape($ibi->apellidos); ?>" />
        </div>      
    </div>
    
    <div class="row">
        
        <div class="span3">            
            <label for="fk_empresa_id">Empresa</label>
            <select name="fk_empresa_id" id="fk_empresa_id" class="select2">
                <?php if ($lista_empresas->num_rows() > 0): ?>
                    <?php foreach ($lista_empresas->result() as $empresa): ?>
                        <option <?php if($empresa->empresa_id == $ibi->fk_empresa_id): ?> selected="selected" <?php endif; ?> value="<?php echo $empresa->empresa_id; ?>"><?php echo $empresa->empresa; ?></option>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>          
        </div>
        
        <div class="span3">
            <label for="fk_perfil_id">Perfil</label>            
            <select name="fk_perfil_id" id="fk_perfil_id" class="select2">
                <?php if ($lista_perfiles->num_rows() > 0): ?>
                    <?php foreach ($lista_perfiles->result() as $perfil): ?>
                        <option <?php if($perfil->perfil_id == $ibi->fk_perfil_id): ?> selected="selected" <?php endif; ?> value="<?php echo $perfil->perfil_id; ?>"><?php echo $perfil->perfil; ?></option>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>
            
        </div>
        
    </div>
    <hr />    
    
    <div class="row">
        <div class="span3">
            <label for="nombre_login">Nombre de login</label>
            <input type="text" name="nombre_login" id="nombre_login" value="<?php echo html_escape($ibi->nombre_login); ?>" />
            
            <label for="email">Email</label>
            <input type="text" name="email" id="email" value="<?php echo html_escape($ibi->email); ?>" /> 
        </div>
        <div class="span3 well well-small">
            <span class="help-block">
                Si no va a cambiar la contrase&ntilde;a: deje estos campos en blanco.
            </span>
            <label for="password_login">Password</label>
            <input class="relative-width" type="password" name="password_login" id="password_login" />
            
            <label for="confirm_password">Confirmar password</label>
            <input class="relative-width" type="password" name="confirm_password" id="confirm_password" />            
        </div>
    </div>
	
</form>