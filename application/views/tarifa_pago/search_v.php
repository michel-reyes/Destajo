<?php echo form_open('tarifa_pago/show_content', array('id'=>'form-search')); 
    // Obtener las busquedas almacenadas en session
    $b_campo = $this->session->userdata('buscar_campo_tarifa_pago');
    $b_criterio = $this->session->userdata('buscar_criterio_tarifa_pago');
    $b_texto = $this->session->userdata('buscar_texto_tarifa_pago');
?>

	<input type="hidden" name="buscar_btn_avanzado" value="search" />
	
	<div class="row-fluid">
    	<table class="table-search">
    		<tbody>
    			<?php
                // Solo mostrar las busquedas almacenadas si las hay
                if ($b_campo):
                    foreach ($b_campo as $key => $value): 
                ?>
    			<tr>
    				<!-- Campo -->
    				<td>
                        <select class="select2" name="buscar_campo[]" data-placeholder="Columna">
                            <option value="categoria" <?php if($value=='categoria'): ?> selected="selected" <?php endif; ?>>Categor&iacute;a del operario</option>
                            <option value="tarifa_menor" <?php if($value=='tarifa_menor'): ?> selected="selected" <?php endif; ?>>Tarifa menor</option>
                            <option value="tarifa_mayor" <?php if($value=='tarifa_mayor'): ?> selected="selected" <?php endif; ?>>Tarifa mayor</option>
                            <option value="tarifa_completa" <?php if($value=='tarifa_completa'): ?> selected="selected" <?php endif; ?>>Tarifa completa</option>
                            <option value="tarifa_interrupcion" <?php if($value=='tarifa_interrupcion'): ?> selected="selected" <?php endif; ?>>Tarifa interrupci&oacute;n</option>
                        </select>
                    </td>
                    
                    <!-- Regla -->
                    <td>
	                    <select class="select2" name="buscar_criterio[]" data-placeholder="Criterio">
	                        <option value="like_both" <?php if ($b_criterio[$key] == 'like_both') echo 'selected="selected"'; ?>>Contiene</option>
	                        <option value="not_like_both" <?php if ($b_criterio[$key] == 'not_like_both') echo 'selected="selected"'; ?>>No contiene</option>
	                        <option value="like_none" <?php if ($b_criterio[$key] == 'like_none') echo 'selected="selected"'; ?>>Es igual a</option>
	                        <option value="not_like_none" <?php if ($b_criterio[$key] == 'not_like_none') echo 'selected="selected"'; ?>>No es igual a</option>               
	                        <option value="or_like_both" <?php if ($b_criterio[$key] == 'or_like_both') echo 'selected="selected"'; ?>>&oacute; Contiene</option>               
	                        <option value="or_not_like_both" <?php if ($b_criterio[$key] == 'or_not_like_both') echo 'selected="selected"'; ?>>&oacute; No contiene</option>
	                        <option value="gt" <?php if ($b_criterio[$key] == 'gt') echo 'selected="selected"'; ?>>Mayor que</option>
	                        <option value="lt" <?php if ($b_criterio[$key] == 'lt') echo 'selected="selected"'; ?>>Menor que</option>                                
	                    </select>
                    </td>
                    
                    <!-- Texto -->
                    <td>
                        <input type="text" value="<?php echo $b_texto[$key]; ?>" class="span2" name="buscar_texto[]" placeholder="Texto a buscar" />
                    </td>
                    
                    <!-- Close -->
                    <td><button class="close">&times;</button></td>
    				
    			</tr>
    			<?php
                    endforeach;
                endif;        
                ?>
    		</tbody>
    	</table>
	</div>	

</form>