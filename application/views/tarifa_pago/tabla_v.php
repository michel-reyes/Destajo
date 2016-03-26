<?php if ($results->num_rows() > 0): ?>
	
	<table class="table-fixed-header fancyTable">
		
		<thead>
			<tr>				
				<th headers="checked"><input type="checkbox" />
					<?php echo anchor($modulo['nombre'] . "/show_content/?ofield=m_categoria_operario.categoria&otype=" . $otype, 'Categor&iacute;a del operario'); ?>				
				</th>
				<th>
				    <?php echo anchor($modulo['nombre'] . "/show_content/?ofield=tarifa_pago.tarifa_menor&otype=" . $otype, 'Tarifa menor'); ?>
				</th>
				<th>
                    <?php echo anchor($modulo['nombre'] . "/show_content/?ofield=tarifa_pago.tarifa_mayor&otype=" . $otype, 'Tarifa mayor'); ?>
                </th>
                <th>
                    <?php echo anchor($modulo['nombre'] . "/show_content/?ofield=tarifa_pago.tarifa_completa&otype=" . $otype, 'Tarifa completa'); ?>
                </th>
                <th>
                    <?php echo anchor($modulo['nombre'] . "/show_content/?ofield=tarifa_pago.tarifa_interrupcion&otype=" . $otype, 'Tarifa interrupci&oacute;n'); ?>
                </th>
			</tr>
		</thead>
		
		<tbody>
			<?php foreach($results->result() as $row): ?>
			<tr>
				<td headers="checked">
					<input type="checkbox" value="<?php echo $row->tarifa_pago_id; ?>" />
					<?php echo $row->categoria; ?>
				</td>
				<td><?php echo mysql_to_number($row->tarifa_menor,5); ?></td>
				<td><?php echo mysql_to_number($row->tarifa_mayor,5); ?></td>
				<td><?php echo mysql_to_number($row->tarifa_completa,5); ?></td>
				<td><?php echo mysql_to_number($row->tarifa_interrupcion,5); ?></td>
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