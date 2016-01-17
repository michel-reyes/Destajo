<?php echo form_open($modulo['nombre'] . '/editar', array('class'=>'main-form form-validate')); ?>

	<!-- Necesario para guardar -->
    <input type="hidden" name="accion" value="editar" />
    <input type="hidden" name="id" value="<?php echo $ibi->entrada_id; ?>" />
    
    <div class="row">
       <div class="span8">
           
           <div id="tabs" class="tabs">
                <ul>
                    <li><a href="#fragment-0"><span>Datos requeridos</span></a></li>
                    <li><a href="#fragment-1"><span>Datos generales</span></a></li>
                    <li><a href="#fragment-2"><span>Vinculaci&oacute;n</span></a></li>
                    <li><a href="#fragment-3"><span>Sin vinculaci&oacute;n</span></a></li>
                </ul>
                
                <!-- DATOS REQUERIDOS -->
                <div id="fragment-0">
                    <div class="row">
                        <div class="span4">
                            
                            <!-- Operario (Chapa) -->
                            <label for="fk_operario_id">Operario <span class="muted">(Chapa)</span></label>  
                            <select class="select2" name="fk_operario_id" id="fk_operario_id">
                                <?php if ($lista_operarios->num_rows() > 0): ?>
                                    <?php foreach ($lista_operarios->result() as $operario): ?>
                                        <option <?php if ($operario->m_operario_id == $ibi->fk_operario_id): ?> selected="selected" <?php endif; ?> data-foo="<?php echo $operario->nombre.' '.$operario->apellidos; ?>" value="<?php echo $operario->m_operario_id; ?>" >
                                        <?php echo $operario->chapa; ?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                            
                            <!-- Fecha de incidencia -->
                            <label for="fecha_incidencia">Fecha de la incidencia</label>
                            <input value="<?php echo to_date($ibi->fecha_incidencia); ?>" type="text" class="datepicker" name="fecha_incidencia" id="fecha_incidencia" /> 
                            
                        </div>
                        
                        <div class="span4">
                            
                            <label for="fecha_captacion"><strong>Fecha de captaci&oacute;n</strong></label>
                            <input value="<?php echo to_date($ibi->fecha_captacion); ?>" type="text" name="fecha_captacion" id="fecha_captacion" class="datepicker" />
                            
                            <p><strong>Periodo de pago actual</strong></p>
                            <table class="table-form-auxiliar">
                                <tbody>
                                   <tr>
                                       <td><span class="muted">Fecha de inicio:</span></td>
                                       <td><?php echo date("d/m/Y", $periodo_pago->fecha_inicio_periodo_pago); ?></td>
                                   </tr>
                                   <tr>
                                       <td><span class="muted">Fecha de cierre:</span></td>
                                       <td><?php echo date("d/m/Y", $periodo_pago->fecha_final_periodo_pago); ?></td>
                                   </tr> 
                                </tbody>                                
                            </table>
                            
                        </div>
                        
                    </div>
                </div>
                
                <!-- DATOS GENERALES -->
                <div id="fragment-1">                    
                    <div class="row">                        
                        <div class="span4">
                            
                            <!-- Equipo cuña -->
                            <label for="fk_capacidad_carga_id">Equipo <span class="muted">(Cu&ntilde;a)</span></label>
                            <select class="select2" name="fk_capacidad_carga_id" id="fk_capacidad_carga_id" data-placeholder="Seleccione un equipo">
                                <option></option>
                                <?php if(! empty($capacidades_carga) AND $capacidades_carga->num_rows() > 0): ?>
                                    <?php foreach ($capacidades_carga->result() as $cc): ?>
                                        <option <?php if ($cc->m_capacidad_carga_id == $ibi->fk_capacidad_carga_id): ?> selected="selected" <?php endif; ?> value="<?php echo $cc->m_capacidad_carga_id; ?>">
                                            <?php echo $cc->equipo; if ($cc->cuna != "") echo " ($cc->cuna)"; ?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                            
                            <!-- Producto -->
                            <label for="fk_producto_id">Producto transportado</label>
                            <select class="select2" name="fk_producto_id" id="fk_producto_id" data-placeholder="Seleccione un Producto">
                                <option></option>
                                <?php if (! empty($lista_productos) AND $lista_productos->num_rows() > 0): ?>
                                    <?php foreach ($lista_productos->result() as $pro): ?>
                                        <option <?php if ($pro->m_producto_id == $ibi->fk_producto_id): ?> selected="selected" <?php endif; ?> data-foo="<?php echo $pro->tipo; ?>" value="<?php echo $pro->m_producto_id; ?>">
                                            <?php echo $pro->producto; ?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                            
                        </div>
                        
                        <div class="span4">
                            
                            <!-- Hoja de ruta -->
                            <label for="hoja_de_ruta">Hoja de ruta</label>
                            <input value="<?php echo $ibi->hoja_de_ruta; ?>" type="text" name="hoja_de_ruta" id="hoja_de_ruta" class="span2" />
                            
                        </div>
                                                
                    </div>                    
                </div>
                
                <!-- VINCULACION -->
                <div id="fragment-2">
                    <div class="row">
                        <div class="span4">
                            
                            <!-- Recorrido -->
                            <label for="fk_carga_descarga_id">Recorrido <span class="muted">(Carga/Descarga)</span></label>
                            <select class="select2" name="fk_carga_descarga_id" id="fk_carga_descarga_id" data-placeholder="Seleccione un recorrido">
                                <option></option>
                                <?php if (! empty($recorridos) AND $recorridos->num_rows() > 0): ?>
                                    <?php foreach ($recorridos->result() as $r): ?>
                                        <option <?php if ($r->carga_descarga_id == $ibi->fk_carga_descarga_id): ?> selected="selected" <?php endif; ?> data-foo="<?php echo $r->lugar_carga.' &rarr; '.$r->lugar_descarga; ?>" 
                                            value="<?php echo $r->carga_descarga_id; ?>" >
                                            <?php echo $r->codigo; ?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                            
                            <!-- kilometros con carga -->
                            <label for="km_recorridos_carga">Km. recorridos con carga</label>
                            <input value="<?php echo $ibi->km_recorridos_carga; ?>" type="text" name="km_recorridos_carga" id="km_recorridos_carga" data-numeric-format="decimal" />
                            
                            <!-- Litros entregados -->
                            <label for="litros_entregados">Litros entregados</label>
                            <input value="<?php echo $ibi->litros_entregados; ?>" type="text" name="litros_entregados" id="litros_entregados" data-numeric-format="integer" />
                            
                            <!-- Modos de descarga -->
                            <label for="fk_modo_descarga_id">Modo de descarga</label>                            
                            <select name="fk_modo_descarga_id" id="fk_modo_descarga_id" class="select2 fk_modo_descarga_mayorista_id" data-placeholder="Seleccione un modo de descarga">
                                <option></option>
                                <?php if (! empty($modos_descarga) AND count($modos_descarga) > 0): ?>
                                    <?php foreach ($modos_descarga as $key => $value): ?>
                                        <option <?php if ($ibi->fk_modo_descarga_id == $value['m_modo_descarga_id']): ?> selected="selected" <?php endif; ?> value="<?php echo $value['m_modo_descarga_id']; ?>">
                                            <?php echo $value['modo']; ?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                            
                        </div>
                        <div class="span4">
                            
                            <!-- Horas de viaje -->
                            <label for="horas_de_viaje">Horas de viaje</label>
                            <input value="<?php echo $ibi->horas_de_viaje; ?>" type="text" name="horas_de_viaje" id="horas_de_viaje" class="span2" data-numeric-format="decimal" />
                            
                            <!-- Numero de viajes -->
                            <label for="numero_de_viajes">N&uacute;mero de viajes</label>
                            <input value="<?php echo $ibi->numero_de_viajes; ?>" type="text" name="numero_de_viajes" id="numero_de_viajes" class="span2" data-numeric-format="integer" />
                            
                            <!-- Numero de entregas -->
                            <label for="numero_de_entregas">N&uacute;mero de entregas</label>
                            <input value="<?php echo $ibi->numero_de_entregas; ?>" type="text" name="numero_de_entregas" id="numero_de_entregas" data-numeric-format="integer" class="span2" />
                            
                            <!-- Feriado -->
                            <div>
                                <input <?php if ($ibi->pago_feriado == 1): ?> checked="checked" <?php endif; ?> type="checkbox" name="pago_feriado" id="pago_feriado" />
                                <label for="pago_feriado" class="label-i-check">Pagar d&iacute;a feriado</label>
                                <span class="icon-question-sign tip" title="Al seleccionar esta opci&oacute;n el trabajador cobrar&aacute; doble el importe del viaje"></span>
                            </div>
                            
                        </div>
                    </div>
                </div>
                
                <!-- SIN VINCULACION -->
                <div id="fragment-3">
                    
                    <div class="row">
                        <div class="span4">
                            
                            <label for="horas_ausencia">Horas de ausencia</label>
                            <input value="<?php echo $ibi->horas_ausencia; ?>" type="text" name="horas_ausencia" id="horas_ausencia" date-numeric-format="decimal" class="span2" />
                            
                            <label for="fk_causa_ausencia_id">Causas de ausencia</label>
                            <select name="fk_causa_ausencia_id" id="fk_causa_ausencia_id" class="select2" data-placeholder="Seleccione la causa de la ausencia">
                                <option></option>
                                <?php if($causas_ausencia->num_rows() > 0): ?>
                                    <?php foreach ($causas_ausencia->result() as $ca): ?>
                                        <option <?php if ($ca->m_causa_ausencia_id == $ibi->fk_causa_ausencia_id): ?> selected="selected" <?php endif; ?> value="<?php echo $ca->m_causa_ausencia_id; ?>">
                                            <?php echo $ca->causa; ?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                            
                            <label for="observaciones">Observaciones</label>
                            <textarea name="observaciones" id="observaciones"><?php echo $ibi->observaciones; ?></textarea>
                            
                        </div>
                        
                        <div class="span4">
                            
                            <p class="text-center"><strong>Horas</strong></p>
                            
                            <div class="row">
                                <div class="span2">
                                    
                                    <label for="horas_interrupto">Interrupto</label>
                                    <input value="<?php echo $ibi->horas_interrupto; ?>" type="text" name="horas_interrupto" id="horas_interrupto" class="span2" data-numeric-format="decimal" />
                                    
                                    <label for="horas_no_vinculado">No vinculado</label>
                                    <input value="<?php echo $ibi->horas_no_vinculado; ?>" type="text" name="horas_no_vinculado" id="horas_no_vinculado" class="span2" data-numeric-format="decimal" />
                                    
                                    <label for="horas_nocturnidad_corta">Nocturnidad <span class="muted">(Corta)</span> </label>
                                    <input value="<?php echo $ibi->horas_nocturnidad_corta; ?>" type="text" name="horas_nocturnidad_corta" id="horas_nocturnidad_corta" class="span2" data-numeric-format="decimal" />
                                    
                                    <label for="horas_nocturnidad_larga">Nocturnidad <span class="muted">(Larga)</span> </label>
                                    <input value="<?php echo $ibi->horas_nocturnidad_larga; ?>" type="text" name="horas_nocturnidad_larga" id="horas_nocturnidad_larga" class="span2" data-numeric-format="decimal" />
                                                                                                         
                                </div>
                                
                                <div class="span2">
                                    
                                    <label for="horas_capacitacion">Capacitaci&oacute;n</label>
                                    <input value="<?php echo $ibi->horas_capacitacion; ?>" type="text" name="horas_capacitacion" id="horas_capacitacion" class="span2" data-numeric-format="decimal" />
                                    
                                    <label for="horas_movilizacion">Movilizaci&oacute;n</label>
                                    <input value="<?php echo $ibi->horas_movilizacion; ?>" type="text" name="horas_movilizacion" id="horas_movilizacion" class="span2" data-numeric-format="decimal" />
                                    
                                    <label for="horas_feriado">Feriado</label>
                                    <input value="<?php echo $ibi->horas_feriado; ?>" type="text" name="horas_feriado" id="horas_feriado" class="span2" data-numeric-format="decimal" />
                                    
                                </div>
                            </div>
                            
                        </div>
                        
                    </div>
                    
                </div>
            </div>
           
       </div>
   </div>
	
</form>