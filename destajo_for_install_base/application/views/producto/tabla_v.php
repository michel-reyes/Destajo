<?php if ($results->num_rows() > 0): ?>
	
	<table class="table-fixed-header fancyTable">
		
		<thead>
			<tr>				
				<th headers="checked"><input type="checkbox" />
					<?php echo anchor($modulo['nombre'] . "/show_content/?ofield=m_producto.producto&otype=" . $otype, 'Producto'); ?>				
				</th>
				<th>
                    <?php echo anchor($modulo['nombre'] . "/show_content/?ofield=m_producto.tipo&otype=" . $otype, 'Tipo de producto'); ?>                
                </th>
			</tr>
		</thead>
		
		<tbody>
			<?php foreach($results->result() as $row): ?>
			<tr>
				<td headers="checked">
					<input type="checkbox" value="<?php echo $row->m_producto_id; ?>" />
					<?php echo $row->producto; ?>
				</td>
				<td><?php echo $row->tipo; ?></td>
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