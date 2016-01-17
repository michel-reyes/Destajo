<?php echo form_open($modulo['nombre'] . '/agregar', array('class'=>'main-form form-validate')); ?>

	<!-- Necesario para guardar -->
    <input type="hidden" name="accion" value="agregar" />
    
    <div class="tabs" id="tabs">
        
        <ul>
            <li><a href="#fragment1"><span>Perfil</span></a></li>
            <li><a href="#fragment2"><span>Persmisos del perfil</span></a></li>
        </ul>
        
        <!-- Perfil -->
        <div id="fragment1">
            
            <label for="perfil">Nombre del perfil</label>
            <input type="text" name="perfil" id="perfil"  />
            
            <label for="descripcion">Descripci&oacute;n del perfil</label>
            <textarea name="descripcion" id="descripcion"></textarea> 
            
            <label for="no_eliminar">No eliminar</label>
            <span class="help-block">Marque la opci&oacute;n <strong>No eliminar</strong> para evitar que este perfil sea eliminado</span>
            <div id="no_eliminar" class="buttonset">
                <input type="radio" value="0" id="item1" name="no_eliminar" checked="checked" />
                <label for="item1">Eliminar</label>
                <input type="radio" value="1" id="item2" name="no_eliminar" />
                <label for="item2">No eliminar</label>
            </div>         
                   
        </div>
        
        <!-- Permisos -->
        <div id="fragment2">
            
            <div class="row-fluid">
                <div class="span12">
                    <div>
                        <input type="checkbox" name="marcar-todo-permisos" id="marcar-todo-permisos" />
                        <label for="marcar-todo-permisos" class="label-i-check">Marcar/Desmarcar todo</label>
                    </div>
                </div>
            </div>
            
            <!-- Lista de permisos -->
            <?php
            $i = 0; 
            $modulo_temp = "";
            $row_open = FALSE;
            $span_open = FALSE;
            ?>
            <?php if ($lista_permisos->num_rows() > 0): ?>
                <div class="container-fluid">
                <?php foreach ($lista_permisos->result() as $permiso): ?>
                    
                    <?php
                    // Obtener el modulo y accion del permiso
                    $ma = explode(".", $permiso->nombre);
                    $modulo = $ma[0];
                    $accion = $ma[1];   
                    
                    if ($modulo == $modulo_temp) {
                        ?>
                        <div>
                            <input type="checkbox" id="<?php echo "item-" . $permiso->permiso_id; ?>" name="permisos[]" 
                                   value="<?php echo $permiso->permiso_id; ?>">
                            <label class="label-i-check" for="<?php echo "item-" . $permiso->permiso_id; ?>"><?php echo $accion; ?></label>
                        </div>
                        <?php 
                    }else 
                    if ($modulo != $modulo_temp) {                        
                        if ($row_open == TRUE && $i>=3) {
                            echo "</div></div><hr/>";
                            $span_open = FALSE;
                            $row_open = FALSE; 
                            $i=0;   
                        }
                        if ($row_open == FALSE) {
                            echo "<div class='row-fluid'>";
                            $row_open = TRUE;    
                        }
                        
                        if ($span_open == TRUE) {
                            echo "</div>";
                            $span_open = FALSE;
                        }                        
                        if ($span_open == FALSE) {
                            $modulo_temp = $modulo;
                            echo "<div class='span4'>";
                            echo "<h5 class='muted permiso-header'>$modulo</h5>";
                            ?>
                            <div>
                                <input type="checkbox" id="<?php echo "item-" . $permiso->permiso_id; ?>" name="permisos[]" 
                                       value="<?php echo $permiso->permiso_id; ?>">
                                <label class="label-i-check" for="<?php echo "item-" . $permiso->permiso_id; ?>"><?php echo $accion; ?></label>
                            </div>
                            <?php                                                        
                            $span_open = TRUE;
                            $i++;                            
                        }
                    }                    
                    ?>
                    
                               
                <?php endforeach; ?>
                </div>
            <?php else: ?>
                
                <div class="alert">
                    <strong>&iexcl;Advertencia!</strong> No hay permisos para mostrar.
                </div>
                
            <?php endif; ?>
            
        </div>
        
    </div>
	
</form>