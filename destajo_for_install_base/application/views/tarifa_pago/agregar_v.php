<?php echo form_open($modulo['nombre'] . '/agregar', array('class'=>'main-form form-validate')); ?>

	<!-- Necesario para guardar -->
    <input type="hidden" name="accion" value="agregar" />
    
    <div>
        
        <label for="fk_categoria_operario_id">Categor&iacute;a del operario</label>
        <select class="select2" name="fk_categoria_operario_id" id="fk_categoria_operario_id">
            <?php if ($lista_categoria_operario->num_rows() > 0): ?>
                <?php foreach ($lista_categoria_operario->result() as $co): ?>
                    <option value="<?php echo $co->m_categoria_operario_id; ?>"><?php echo $co->categoria; ?></option>
                <?php endforeach; ?>
            <?php endif; ?>
        </select>
        
        <label for="tarifa_menor">Tarifa para viajes menores</label>
        <span class="help-block">
            Tarifa centavos minutos para viajes <strong>menores e iguales a 90 kil&oacute;metros</strong> con carga.
        </span>
        <input type="text" name="tarifa_menor" id="tarifa_menor" data-numeric-format="decimal-five" />
        
        <label for="tarifa_mayor">Tarifa para viajes mayores</label>
        <span class="help-block">
            Tarifa centavos minutos para viajes <strong>mayores de 90 kil&oacute;metros</strong> con carga.
        </span>
        <input type="text" name="tarifa_mayor" id="tarifa_mayor" data-numeric-format="decimal-five" />
        
        <label for="tarifa_completa">Tarifa completa</label>
        <span class="help-block">
            Salario que gana el operario por horas seg&uacute;n su categor&iacute;a,<br />
            con todos los incrementos que procedan al 100 %
        </span>
        <input type="text" name="tarifa_completa" id="tarifa_completa" data-numeric-format="decimal-five" />
        
        <label for="tarifa_interrupcion">Tarifa para interrupciones</label>
        <span class="help-block">
            Tarifa calculada para el pago de la interrupci&oacute;n
        </span>
        <input type="text" name="tarifa_interrupcion" id="tarifa_interrupcion" data-numeric-format="decimal-five" />
    	     	
    </div>
	
</form>