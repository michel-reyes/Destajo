<?php if ($results->num_rows() > 0): ?>
	
	<table class="table-fixed-header fancyTable">
		
		<thead>
			<tr>				
				<th headers="checked"><input type="checkbox" />
					<?php echo anchor($modulo['nombre'] . "/show_content/?ofield=m_categoria_operario.categoria&otype=" . $otype, 'Categor&iacute;a'); ?>				
				</th>
				<th>
				    <?php echo anchor($modulo['nombre'] . "/show_content/?ofield=m_categoria_operario.nomenclador&otype=" . $otype, 'Nomenclador'); ?>
				</th>
				<th>
                    <?php echo anchor($modulo['nombre'] . "/show_content/?ofield=m_categoria_operario.min_capacidad_carga&otype=" . $otype, 'M&iacute;n. capacidad de carga'); ?>
                </th>
                <th>
                    <?php echo anchor($modulo['nombre'] . "/show_content/?ofield=m_categoria_operario.max_capacidad_carga&otype=" . $otype, 'M&aacute;x. capacidad de carga'); ?>
                </th>
			</tr>
		</thead>
		
		<tbody>
			<?php foreach($results->result() as $row): ?>
			<tr>
				<td headers="checked">
					<input type="checkbox" value="<?php echo $row->m_categoria_operario_id; ?>" />
					<?php echo $row->categoria; ?>
				</td>
				<td><strong><?php echo $row->nomenclador; ?></strong></td>
				<td><?php echo mysql_to_number($row->min_capacidad_carga); ?></td>
				<td><?php echo mysql_to_number($row->max_capacidad_carga); ?></td>
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