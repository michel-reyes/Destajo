<?php echo form_open('tiempo_descarga/show_content', array('id'=>'form-search')); 
    // Obtener las busquedas almacenadas en session
    $b_campo = $this->session->userdata('buscar_campo_tiempo_descarga');
    $b_criterio = $this->session->userdata('buscar_criterio_tiempo_descarga');
    $b_texto = $this->session->userdata('buscar_texto_tiempo_descarga');
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
                            <option value="m_equipo.numero_operacional" <?php if($value=='m_equipo.numero_operacional'): ?> selected="selected" <?php endif; ?>>Equipo</option>
                            <option value="m_cuna.numero_operacional" <?php if($value=='m_cuna.numero_operacional'): ?> selected="selected" <?php endif; ?>>Cu&ntilde;a</option>
                            <option value="tiempo_descarga" <?php if($value=='tiempo_descarga'): ?> selected="selected" <?php endif; ?>>Tiempo de descarga</option>
                            <option value="producto" <?php if($value=='producto'): ?> selected="selected" <?php endif; ?>>Producto</option>
                            <option value="modo" <?php if($value=='modo'): ?> selected="selected" <?php endif; ?>>Modo</option>
                            <option value="lugar_descarga" <?php if($value=='lugar_descarga'): ?> selected="selected" <?php endif; ?>>Lugar de descarga</option>
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