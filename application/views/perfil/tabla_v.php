<?php if ($results->num_rows() > 0): ?>
	
	<table class="table-fixed-header fancyTable">
		
		<thead>
			<tr>				
				<th headers="checked"><input type="checkbox" />
					<?php echo anchor($modulo['nombre'] . "/show_content/?ofield=perfil.perfil&otype=" . $otype, 'Perfil'); ?>				
				</th>
				<th>
				    <?php echo anchor($modulo['nombre'] . "/show_content/?ofield=perfil.descripcion&otype=" . $otype, 'Descripci&oacute;n'); ?>
				</th>
				<th>
                    <?php echo anchor($modulo['nombre'] . "/show_content/?ofield=perfil.no_eliminar&otype=" . $otype, 'No eliminar'); ?>
                </th>
			</tr>
		</thead>
		
		<tbody>
			<?php foreach($results->result() as $row): ?>
			<tr>
				<td headers="checked">
					<input type="checkbox" value="<?php echo $row->perfil_id; ?>" />
					<?php echo $row->perfil; ?>
				</td>
				<td><?php echo $row->descripcion; ?></td>
				<td><span class="<?php echo ($row->no_eliminar == 1) ? "check-mark" : "radio-mark"; ?>"></span></td>
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