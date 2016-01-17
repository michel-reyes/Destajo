<?php echo form_open('periodo_pago/cierre', array('class'=>'main-form form-validate')); ?>

	<!-- Necesario para guardar -->
    <input type="hidden" name="accion" value="cierre" />
    
    <div class="row-fluid">
    	
    	<div class="span12"> 	    
    	    <label for="fecha_final_periodo_pago">Fecha de cierre del periodo de pago</label>
            <input class="datepicker" type="text" value="<?php echo date("d/m/Y",$ffpp); ?>" name="fecha_final_periodo_pago" id="fecha_final_periodo_pago"  />
    	</div>
    	     	
    </div>
    
    <!-- Alerta -->
    <div style="width: 400px;">  
                  
        <div class="accordion accordion-alert">
            <h3><strong>&iexcl;Advertencia!</strong> Est&aacute;s a punto de realizar un cierre...</h3>
            <div>
                <p>Si aceptas esta operaci&oacute;n las <strong>entradas</strong> y <strong>salidas</strong>
                    que tengan fecha menor o igual que la fecha de cierre actual se ajustar&aacute;n a las
                    siguientes medidas:
                    <ul>
                        <li>Quedar&aacute; deshabilitada la opci&oacute;n de editar entradas.</li>
                        <li>Quedar&aacute; deshabilitada la opci&oacute;n de eliminar entradas.</li>
                        <li>Quedar&aacute; deshabilitada la opci&oacute;n de calcular salidas (todas).</li>
                        <li>No aparecer&aacute;n en la lista de entradas y salidas.</li>
                    </ul>
                </p>
            </div>
        </div>
          
    </div>
    

</form>