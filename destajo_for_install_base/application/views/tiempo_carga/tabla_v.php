<?php if ($results->num_rows() > 0): ?>
	
	<table class="table-fixed-header fancyTable">
		
		<thead>
			<tr>				
				<th headers="checked"><input type="checkbox" />
					<?php echo anchor($modulo['nombre'] . "/show_content/?ofield=m_equipo.numero_operacional&otype=" . $otype, 'Equipo'); ?>				
				</th>
				<th>
				    <?php echo anchor($modulo['nombre'] . "/show_content/?ofield=m_cuna.numero_operacional&otype=" . $otype, 'Cu&ntilde;a'); ?>
				</th>
				<th>
				    <?php echo anchor($modulo['nombre'] . "/show_content/?ofield=tiempo_carga.tiempo_carga&otype=" . $otype, 'Tiempo de carga <span class="muted">(minutos)</span>'); ?>
				</th>
				<th>
                    <?php echo anchor($modulo['nombre'] . "/show_content/?ofield=m_producto.producto&otype=" . $otype, 'Producto'); ?>
                </th>
                <th>
                    <?php echo anchor($modulo['nombre'] . "/show_content/?ofield=m_lugar_carga.lugar_carga&otype=" . $otype, 'Lugar de carga'); ?>
                </th>
			</tr>
		</thead>
		
		<tbody>
			<?php foreach($results->result() as $row): ?>
			<tr>
				<td headers="checked">
					<input type="checkbox" value="<?php echo $row->tiempo_carga_id; ?>" />
					<?php echo $row->no_equipo; ?>
				</td>
				<td><?php echo $row->no_cuna; ?></td>
				<td><span class="muted"><em class="km-arrow"> &larr;</em></span><strong><?php echo mysql_to_number($row->tiempo_carga); ?></strong><span class="muted"> &mdash;</span></td>
				<td><?php echo $row->producto; ?></td>
				<td><?php echo $row->lugar_carga; ?></td>
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