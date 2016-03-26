<?php echo form_open($modulo['nombre'] . '/editar', array('class'=>'main-form form-validate')); ?>

	<!-- Necesario para guardar -->
    <input type="hidden" name="accion" value="editar" />
    <input type="hidden" name="id" value="<?php echo $ibi->carga_descarga_id; ?>" />
    
    <div class="row-fluid">
        
        <div class="span6">
            
            <label for="codigo">C&oacute;digo</label>
            <input type="text" name="codigo" id="codigo" data-numeric-format="integer" value="<?php echo $ibi->codigo; ?>" />
            
            <label for="fk_lugar_carga_id">Lugar de carga</label>
            <select name="fk_lugar_carga_id" id="fk_lugar_carga_id" class="select2">
                <?php if ($lista_lugares_carga->num_rows() > 0): ?>
                    <?php foreach ($lista_lugares_carga->result() as $lc): ?>
                        <option <?php if ($lc->m_lugar_carga_id == $ibi->fk_lugar_carga_id): ?> selected="selected" <?php endif; ?> value="<?php echo $lc->m_lugar_carga_id; ?>"><?php echo $lc->lugar_carga; ?></option>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select> 
            
            <label for="fk_lugar_descarga_id">Lugar de descarga</label>
            <select name="fk_lugar_descarga_id" id="fk_lugar_descarga_id" class="select2">
                <?php if ($lista_lugares_descarga->num_rows() > 0): ?>
                    <?php foreach ($lista_lugares_descarga->result() as $lc): ?>
                        <option <?php if ($lc->m_lugar_descarga_id == $ibi->fk_lugar_descarga_id): ?> selected="selected" <?php endif; ?> value="<?php echo $lc->m_lugar_descarga_id; ?>"><?php echo $lc->lugar_descarga; ?></option>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>
            
        </div>  
        
        <div class="span6">
            
            <p><strong>Kil&oacute;metros recorridos por cada v&iacute;a</strong><span class="muted"> (Kms)</span></p>
            
            <table class="table-form-auxiliar">
                <tbody>
                    <tr>
                        <td>Per&iacute;metro urbano</td>
                        <td><input value="<?php if ($ibi->PU != "" && $ibi->PU != 0.00) echo $ibi->PU; ?>" class="span8 km-calc" type="text" name="PU" data-numeric-format="decimal" /></td>
                    </tr>
                    <tr>
                        <td>Carretera</td>
                        <td><input value="<?php if ($ibi->C != "" && $ibi->C != 0.00) echo $ibi->C; ?>" class="span8 km-calc" type="text" name="C" data-numeric-format="decimal" /></td>
                    </tr>
                    <tr>
                        <td>Autopista</td>
                        <td><input value="<?php if ($ibi->A != "" && $ibi->A != 0.00) echo $ibi->A; ?>" class="span8 km-calc" type="text" name="A" data-numeric-format="decimal" /></td>
                    </tr>
                    <tr>
                        <td>Terrapl&eacute;n</td>
                        <td><input value="<?php if ($ibi->T != "" && $ibi->T != 0.00) echo $ibi->T; ?>" class="span8 km-calc" type="text" name="T" data-numeric-format="decimal" /></td>
                    </tr>
                    <tr>
                        <td>Camino de tierra</td>
                        <td><input value="<?php if ($ibi->CT != "" && $ibi->CT != 0.00) echo $ibi->CT; ?>" class="span8 km-calc" type="text" name="CT" data-numeric-format="decimal" /></td>
                    </tr>
                    <tr>
                        <td>Carretera de monta&ntilde;a</td>
                        <td><input value="<?php if ($ibi->CM != "" && $ibi->CM != 0.00) echo $ibi->CM; ?>" class="span8 km-calc" type="text" name="CM" data-numeric-format="decimal" /></td>
                    </tr>
                    <tr>
                        <td>Terrapl&eacute;n de monta&ntilde;a</td>
                        <td><input value="<?php if ($ibi->TM != "" && $ibi->TM != 0.00) echo $ibi->TM; ?>" class="span8 km-calc" type="text" name="TM" data-numeric-format="decimal" /></td>
                    </tr>
                    <tr>
                        <td>Camino vecinal</td>
                        <td><input value="<?php if ($ibi->CV != "" && $ibi->CV != 0.00) echo $ibi->CV; ?>" class="span8 km-calc" type="text" name="CV" data-numeric-format="decimal" /></td>
                    </tr>
                    <tr>
                        <td>Total:</td>
                        <td><strong class="km-total"><?php echo $ibi->km_recorridos; ?></strong></td>
                        <input type="hidden" name="km_recorridos" value="<?php echo $ibi->km_recorridos; ?>" />
                    </tr>
                </tbody>
            </table>
            
        </div>
                    
    </div>
	
</form>