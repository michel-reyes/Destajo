<?php if ($results->num_rows() > 0): ?>
	
	<table class="table-fixed-header fancyTable">
		
		<thead>
			<tr>				
				<th headers="checked"><input type="checkbox" />
					<?php echo anchor($modulo['nombre'] . "/show_content/?ofield=salida_salario_equipo.numero_operacional_equipo&otype=" . $otype, 'Equipo'); ?>				
				</th>
				<th>
				    <?php echo anchor($modulo['nombre'] . "/show_content/?ofield=salida_salario_equipo.numero_operacional_cuna&otype=" . $otype, 'Cu&ntilde;a'); ?>
				</th>
				<th>
                    <?php echo anchor($modulo['nombre'] . "/show_content/?ofield=salida_salario_equipo.importe_viaje&otype=" . $otype, 'Importe del viaje'); ?>
                </th>
                <th>
                    <?php echo anchor($modulo['nombre'] . "/show_content/?ofield=salida_salario_equipo.fecha_inicio_periodo_pago&otype=" . $otype, 'Inicio del periodo de pago'); ?>
                </th>
                <th>
                    <?php echo anchor($modulo['nombre'] . "/show_content/?ofield=salida_salario_equipo.fecha_final_periodo_pago&otype=" . $otype, 'Final del periodo de pago'); ?>
                </th>
			</tr>
		</thead>
		
		<tbody>
			<?php foreach($results->result() as $row): ?>
			<tr>
				<td headers="checked">
				    <!-- Si la entrada esta fuera del rango del periodo de pago no editar ni eliminar -->
                    <?php if($fipp == $row->fecha_inicio_periodo_pago): ?>
					   <input type="checkbox" value="<?php echo $row->salida_salario_equipo_id; ?>" />
					<?php endif; ?>
					
					<?php echo $row->numero_operacional_equipo; ?>
				</td>
				<td><?php echo $row->numero_operacional_cuna; ?></td>
				<td><?php echo mysql_to_number($row->importe_viaje); ?></td>
				<td><?php echo date("d/m/Y",$row->fecha_inicio_periodo_pago); ?></td>
				<td><?php echo date("d/m/Y",$row->fecha_final_periodo_pago); ?></td>
			</tr>
			<?php endforeach; ?>
		</tbody>
		
	</table>
	
<?php else: ?>
	
	<div class="no-search">  
       <div class="no-search-inner">
           <i class="icon-info-sign"></i>
           <p class="nosearch-title">No encontramos ning&uacute;n resultado</p>
           <p>
               Deber&iacute;as probar con otros criterios de b&uacute;squeda 
               <a href="#" class="btn btn-inverse comando-buscar"> Buscar  <i class="icon-search"></i> </a>
           </p>
       </div>          
    </div>
	
<?php endif; ?>