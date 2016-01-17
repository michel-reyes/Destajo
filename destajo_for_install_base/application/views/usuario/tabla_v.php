<?php if ($results->num_rows() > 0): ?>
	
	<table class="table-fixed-header fancyTable">
		
		<thead>
			<tr>				
				<th headers="checked"><input type="checkbox" />
					<?php echo anchor($modulo['nombre'] . "/show_content/?ofield=usuario.nombre&otype=" . $otype, 'Nombre y apellidos'); ?>				
				</th>
				<th>
				    <?php echo anchor($modulo['nombre'] . "/show_content/?ofield=usuario.email&otype=" . $otype, 'Email'); ?>
				</th>
				<th>
                    <?php echo anchor($modulo['nombre'] . "/show_content/?ofield=usuario.nombre_login&otype=" . $otype, 'Nombre login'); ?>
                </th>
                <th>
                    <?php echo anchor($modulo['nombre'] . "/show_content/?ofield=usuario.fecha_alta&otype=" . $otype, 'Alta'); ?>
                </th>
                <th>
                    <?php echo anchor($modulo['nombre'] . "/show_content/?ofield=empresa.empresa&otype=" . $otype, 'Empresa'); ?>
                </th>
                <th>
                    <?php echo anchor($modulo['nombre'] . "/show_content/?ofield=perfil.perfil&otype=" . $otype, 'Perfil'); ?>
                </th>
			</tr>
		</thead>
		
		<tbody>
			<?php foreach($results->result() as $row): ?>
			<tr>
				<td headers="checked">
					<input type="checkbox" value="<?php echo $row->usuario_id; ?>" />
					<?php echo $row->nombre . " " . $row->apellidos; ?>
				</td>
				<td><?php echo $row->email; ?></td>
				<td><?php echo $row->nombre_login; ?></td>
				<td><?php echo to_date($row->fecha_alta); ?></td>
				<td><?php echo $row->empresa; ?></td>
				<td><?php echo $row->perfil; ?></td>
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