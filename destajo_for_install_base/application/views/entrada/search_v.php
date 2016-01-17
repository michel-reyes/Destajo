<?php echo form_open('entrada/show_content', array('id'=>'form-search')); 
    // Obtener las busquedas almacenadas en session
    $b_campo = $this->session->userdata('buscar_campo_entrada');
    $b_criterio = $this->session->userdata('buscar_criterio_entrada');
    $b_texto = $this->session->userdata('buscar_texto_entrada');
    $b_periodo = $this->session->userdata('search_periodo_entrada');
    $datepicker = NULL;
?>

	<input type="hidden" name="buscar_btn_avanzado" value="search" />
	
	<div class="row-fluid">
    	<table class="table-search">
    		<tbody>
    		    <!-- Buscar periodos de pago -->
    		    <tr class="tr-periodos">
    		        <td colspan="4">
    		            <label for="radio_set"><strong>Periodo a buscar</strong></label>
    		            <div id="radio_set" class="buttonset">
                            <input type="radio" id="radio1" name="b_periodo" <?php if ($b_periodo == 'present' OR $b_periodo === FALSE) echo 'checked="checked"'; ?>  value="present" /><label for="radio1">Este periodo</label>
                            <input type="radio" id="radio2" name="b_periodo" <?php if ($b_periodo == 'past') echo 'checked="checked"'; ?> value="past" /><label for="radio2">Periodos anteriores</label>
                            <input type="radio" id="radio3" name="b_periodo" <?php if ($b_periodo == 'both') echo 'checked="checked"'; ?> value="both" /><label for="radio3">Ambos</label>
                        </div>
    		        </td>
    		    </tr>
    			<?php
                // Solo mostrar las busquedas almacenadas si las hay
                if ($b_campo):
                    foreach ($b_campo as $key => $value):
                        $datepicker = FALSE; 
                ?>
    			<tr>
    				<!-- Campo -->
    				<td>
                        <select class="select2" name="buscar_campo[]" data-placeholder="Columna">
                            <option></option>
                            <option value="op.chapa" <?php if($value=='op.chapa'): ?> selected="selected" <?php endif; ?>>Chapa</option>
                            <option value="op.nombre" <?php if($value=='op.nombre'): ?> selected="selected" <?php endif; ?>>Nombre</option>
                            <option value="op.apellidos" <?php if($value=='op.apellidos'): ?> selected="selected" <?php endif; ?>>Apellidos</option>
                            <option value="entrada.hoja_de_ruta" <?php if($value=='entrada.hoja_de_ruta'): ?> selected="selected" <?php endif; ?>>Hoja de ruta</option>
                            <option value="entrada.fecha_incidencia" <?php if($value=='entrada.fecha_incidencia'): ?> selected="selected" <?php $datepicker = TRUE; endif; ?>>Fecha de incidencia</option>
                            <option value="m_equipo.numero_operacional" <?php if($value=='m_equipo.numero_operacional'): ?> selected="selected" <?php endif; ?>>Equipo</option>
                            <option value="m_cuna.numero_operacional" <?php if($value=='m_cuna.numero_operacional'): ?> selected="selected" <?php endif; ?>>Cu&ntilde;a</option>
                            <option value="cc.capacidad_carga" <?php if($value=='cc.capacidad_carga'): ?> selected="selected" <?php endif; ?>>Capacidad de carga</option>
                            <option value="p.producto" <?php if($value=='p.producto'): ?> selected="selected" <?php endif; ?>>Producto</option>
                            <option value="p.tipo" <?php if($value=='p.tipo'): ?> selected="selected" <?php endif; ?>>Tipo de producto</option>
                            <option value="lc.lugar_carga" <?php if($value=='lc.lugar_carga'): ?> selected="selected" <?php endif; ?>>Lugar de carga</option>
                            <option value="ld.lugar_descarga" <?php if($value=='ld.lugar_descarga'): ?> selected="selected" <?php endif; ?>>Lugar de descarga</option>
                            <option value="entrada.litros_entregados" <?php if($value=='entrada.litros_entregados'): ?> selected="selected" <?php endif; ?>>Litros entregados</option>
                            <option value="entrada.km_recorridos_carga" <?php if($value=='entrada.km_recorridos_carga'): ?> selected="selected" <?php endif; ?>>Kil&oacute;metros recorridos</option>                            
                            <option value="md.modo" <?php if($value=='md.modo'): ?> selected="selected" <?php endif; ?>>Modo de descarga</option>
                            
                            <option value="entrada.numero_de_viajes" <?php if($value=='entrada.numero_de_viajes'): ?> selected="selected" <?php endif; ?>>N&deg; de viajes</option>
                            <option value="entrada.numero_de_entregas" <?php if($value=='entrada.numero_de_entregas'): ?> selected="selected" <?php endif; ?>>N&deg; de entregas</option>                            
                            <option value="entrada.horas_de_viaje" <?php if($value=='entrada.horas_de_viaje'): ?> selected="selected" <?php endif; ?>>Horas de viaje</option>
                            <option value="entrada.horas_interrupto" <?php if($value=='entrada.horas_interrupto'): ?> selected="selected" <?php endif; ?>>Horas interrupto</option>
                            <option value="entrada.horas_no_vinculado" <?php if($value=='entrada.horas_no_vinculado'): ?> selected="selected" <?php endif; ?>>Horas no vinculado</option>
                            <option value="entrada.horas_nocturnidad_corta" <?php if($value=='entrada.horas_nocturnidad_corta'): ?> selected="selected" <?php endif; ?>>Horas nacturnidad corta</option>
                            <option value="entrada.horas_nocturnidad_larga" <?php if($value=='entrada.horas_nocturnidad_larga'): ?> selected="selected" <?php endif; ?>>Horas nocturnidad larga</option>
                            <option value="entrada.horas_capacitacion" <?php if($value=='entrada.horas_capacitacion'): ?> selected="selected" <?php endif; ?>>Horas de capacitaci&oacute;n</option>
                            <option value="entrada.horas_movilizacion" <?php if($value=='entrada.horas_movilizacion'): ?> selected="selected" <?php endif; ?>>Horas de movilizaci&oacute;n</option>
                            <option value="entrada.horas_feriado" <?php if($value=='entrada.horas_feriado'): ?> selected="selected" <?php endif; ?>>Horas feriado</option>
                            <option value="entrada.horas_ausencia" <?php if($value=='entrada.horas_ausencia'): ?> selected="selected" <?php endif; ?>>Horas de ausencia</option>
                            <option value="ca.causa" <?php if($value=='ca.causa'): ?> selected="selected" <?php endif; ?>>Causa de la ausencia</option>
                            <option value="entrada.pago_feriado" <?php if($value=='entrada.pago_feriado'): ?> selected="selected" <?php endif; ?>>Pago feriado</option>                                
                            <option value="entrada.importe_viaje" <?php if($value=='entrada.importe_viaje'): ?> selected="selected" <?php endif; ?>>Importe del viaje</option>
                            <option value="entrada.cumplimiento_norma" <?php if($value=='entrada.cumplimiento_norma'): ?> selected="selected" <?php endif; ?>>Cumplimiento de la norma</option>
                            <option value="entrada.fecha_captacion" <?php if($value=='entrada.fecha_captacion'): ?> selected="selected" <?php $datepicker = TRUE; endif; ?>>Fecha de captacion</option>
                            <option value="entrada.fecha_inicio_periodo_pago" <?php if($value=='entrada.fecha_inicio_periodo_pago'): ?> selected="selected" <?php $datepicker = TRUE; endif; ?>>Inicio del periodo de pago</option>
                            <option value="entrada.fecha_final_periodo_pago" <?php if($value=='entrada.fecha_final_periodo_pago'): ?> selected="selected" <?php $datepicker = TRUE; endif; ?>>Final del periodo de pago</option>                            
                        </select>
                    </td>
                    
                    <!-- Regla -->
                    <td>
	                    <select class="select2" name="buscar_criterio[]" data-placeholder="Criterio">
	                        <option></option>
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
                        <input type="text" value="<?php echo $b_texto[$key]; ?>" class="span2 <?php if ($datepicker) echo "datepicker"; ?>" name="buscar_texto[]" placeholder="Texto a buscar" />
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