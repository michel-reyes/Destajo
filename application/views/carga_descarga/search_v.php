<?php echo form_open('carga_descarga/show_content', array('id'=>'form-search')); 
    // Obtener las busquedas almacenadas en session
    $b_campo = $this->session->userdata('buscar_campo_carga_descarga');
    $b_criterio = $this->session->userdata('buscar_criterio_carga_descarga');
    $b_texto = $this->session->userdata('buscar_texto_carga_descarga');
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
                            <option value="codigo" <?php if($value=='codigo'): ?> selected="selected" <?php endif; ?>>C&oacute;digo</option>
                            <option value="lugar_carga" <?php if($value=='lugar_carga'): ?> selected="selected" <?php endif; ?>>Lugar de carga</option>
                            <option value="lugar_descarga" <?php if($value=='lugar_descarga'): ?> selected="selected" <?php endif; ?>>Lugar de descarga</option>
                            <option value="km_recorridos" <?php if($value=='km_recorridos'): ?> selected="selected" <?php endif; ?>>Km. recorridos</option>
                            <option value="PU" <?php if($value=='PU'): ?> selected="selected" <?php endif; ?>>PU</option>
                            <option value="C" <?php if($value=='C'): ?> selected="selected" <?php endif; ?>>C</option>
                            <option value="A" <?php if($value=='A'): ?> selected="selected" <?php endif; ?>>A</option>
                            <option value="T" <?php if($value=='T'): ?> selected="selected" <?php endif; ?>>T</option>
                            <option value="CM" <?php if($value=='CM'): ?> selected="selected" <?php endif; ?>>CM</option>
                            <option value="CT" <?php if($value=='CT'): ?> selected="selected" <?php endif; ?>>CT</option>
                            <option value="TM" <?php if($value=='TM'): ?> selected="selected" <?php endif; ?>>TM</option>
                            <option value="CV" <?php if($value=='CV'): ?> selected="selected" <?php endif; ?>>CV</option>
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