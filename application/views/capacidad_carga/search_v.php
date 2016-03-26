<?php 
/*
DESTAJO-MODULE

date: 2014.03.10
type: php module
path: application/views/capacidad_carga/search_v.php

DESTAJO-MODULE-END
*/

echo form_open('capacidad_carga/show_content', array('id'=>'form-search')); 
    // Obtener las busquedas almacenadas en session
    $b_campo = $this->session->userdata('buscar_campo_capacidad_carga');
    $b_criterio = $this->session->userdata('buscar_criterio_capacidad_carga');
    $b_texto = $this->session->userdata('buscar_texto_capacidad_carga');
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
                            <option value="viajes_promedio" <?php if($value=='viajes_promedio'): ?> selected="selected" <?php endif; ?>>Viajes promedio</option>
                            <option value="entregas_promedio" <?php if($value=='entregas_promedio'): ?> selected="selected" <?php endif; ?>>Entregas promedio</option>
                            <option value="capacidad_carga" <?php if($value=='capacidad_carga'): ?> selected="selected" <?php endif; ?>>Capacidad de carga</option>
                            <option value="tipo_de_producto" <?php if($value=='tipo_de_producto'): ?> selected="selected" <?php endif; ?>>Tipo de producto</option>
                            <option value="capacidad_bombeo_turbina_equipo" <?php if($value=='capacidad_bombeo_turbina_equipo'): ?> selected="selected" <?php endif; ?>>Turbina del equipo</option>
                            <option value="capacidad_bombeo_gravedad2" <?php if($value=='capacidad_bombeo_gravedad2'): ?> selected="selected" <?php endif; ?>>Gravedad 2"</option>
                            <option value="capacidad_bombeo_gravedad3" <?php if($value=='capacidad_bombeo_gravedad3'): ?> selected="selected" <?php endif; ?>>Gravedad 3"</option>
                            <option value="capacidad_bombeo_gravedad4 <?php if($value=='capacidad_bombeo_gravedad4'): ?> selected="selected" <?php endif; ?>>Gravedad 4"</option>
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