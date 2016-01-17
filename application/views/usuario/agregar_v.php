<?php echo form_open($modulo['nombre'] . '/agregar', array('class'=>'main-form form-validate')); ?>

	<!-- Necesario para guardar -->
    <input type="hidden" name="accion" value="agregar" />
    
    <div class="row">
        <div class="span3">
            <label for="nombre">Nombre</label>
            <input type="text" name="nombre" id="nombre" />
        </div>
        <div class="span3">
            <label for="apellidos">Apellidos</label>
            <input type="text" name="apellidos" id="apellidos" />
        </div>    	
    </div>
    
    <div class="row">
        
        <div class="span3">            
            <label for="fk_empresa_id">Empresa</label>
            <select name="fk_empresa_id" id="fk_empresa_id" class="select2">
                <?php if ($lista_empresas->num_rows() > 0): ?>
                    <?php foreach ($lista_empresas->result() as $empresa): ?>
                        <option value="<?php echo $empresa->empresa_id; ?>"><?php echo $empresa->empresa; ?></option>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>          
        </div>
        
        <div class="span3">
            <label for="fk_perfil_id">Perfil</label>            
            <select name="fk_perfil_id" id="fk_perfil_id" class="select2">
                <?php if ($lista_perfiles->num_rows() > 0): ?>
                    <?php foreach ($lista_perfiles->result() as $perfil): ?>
                        <option value="<?php echo $perfil->perfil_id; ?>"><?php echo $perfil->perfil; ?></option>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>
            
        </div>
        
    </div>
    <hr />    
    
    <div class="row">
        <div class="span3">
            <label for="nombre_login">Nombre de login</label>
            <input type="text" name="nombre_login" id="nombre_login" />
        </div>
        <div class="span3">
            <label for="password_login">Password</label>
            <input type="password" name="password_login" id="password_login" />            
        </div>
    </div>
    
    <div class="row">
        
        <div class="span3">
            <label for="email">Email</label>
            <input type="text" name="email" id="email" /> 
        </div>
        
        <div class="span3">
            <label for="confirm_password">Confirmar password</label>
            <input type="password" name="confirm_password" id="confirm_password" />
        </div>
        
    </div>
	
</form>