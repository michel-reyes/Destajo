<?php if ($results->num_rows() > 0): ?>
	
	<table class="table-fixed-header fancyTable">
		
		<thead>
		    <tr class="fcr">
		        <?php for($i=1; $i<=13; $i++): ?>
		            <th></th>
		        <?php endfor; ?>
		    </tr>
		    <tr>
		        <th colspan="1"></th>
		        <th colspan="12" class="text-center">Capacidad de bombeo por productos</th>
		    </tr>
			<tr>				
				<th headers="checked"><input type="checkbox" />
					<?php echo anchor($modulo['nombre'] . "/show_content/?ofield=m_lugar_carga.lugar_carga&otype=" . $otype, 'Lugar de carga'); ?>				
				</th>
				<th><?php echo anchor($modulo['nombre'] . "/show_content/?ofield=alcohol&otype=" . $otype, 'Alcohol'); ?></th>
				<th><?php echo anchor($modulo['nombre'] . "/show_content/?ofield=biomix&otype=" . $otype, 'BioMix'); ?></th>
				<th><?php echo anchor($modulo['nombre'] . "/show_content/?ofield=crudo&otype=" . $otype, 'Crudo'); ?></th>
				<th><?php echo anchor($modulo['nombre'] . "/show_content/?ofield=diesel&otype=" . $otype, 'Diesel'); ?></th>
				<th><?php echo anchor($modulo['nombre'] . "/show_content/?ofield=fuell&otype=" . $otype, 'Fuell'); ?></th>
				<th><?php echo anchor($modulo['nombre'] . "/show_content/?ofield=gasolina_especial&otype=" . $otype, 'Gasolina Especial'); ?></th>
				<th><?php echo anchor($modulo['nombre'] . "/show_content/?ofield=gasolina_regular&otype=" . $otype, 'Gasolina Regular'); ?></th>
				<th><?php echo anchor($modulo['nombre'] . "/show_content/?ofield=glp&otype=" . $otype, 'GLP'); ?></th>
				<th><?php echo anchor($modulo['nombre'] . "/show_content/?ofield=kerosina&otype=" . $otype, 'Kerosina'); ?></th>
				<th><?php echo anchor($modulo['nombre'] . "/show_content/?ofield=lubricantes&otype=" . $otype, 'Lubricantes'); ?></th>
				<th><?php echo anchor($modulo['nombre'] . "/show_content/?ofield=nafta&otype=" . $otype, 'Nafta'); ?></th>
				<th><?php echo anchor($modulo['nombre'] . "/show_content/?ofield=turbo&otype=" . $otype, 'Turbo'); ?></th>
			</tr>
		</thead>
		
		<tbody>
			<?php foreach($results->result() as $row): ?>
			<tr>
				<td headers="checked">
					<input type="checkbox" value="<?php echo $row->m_lugar_carga_id; ?>" />
					<?php echo $row->lugar_carga; ?>
				</td>
				<td><?php echo mysql_to_number($row->Alcohol); ?></td>
                <td><?php echo mysql_to_number($row->BioMix); ?></td>
                <td><?php echo mysql_to_number($row->Crudo); ?></td>
                <td><?php echo mysql_to_number($row->Diesel); ?></td>
                <td><?php echo mysql_to_number($row->Fuell); ?></td>
                <td><?php echo mysql_to_number($row->Gasolina_Especial); ?></td>
                <td><?php echo mysql_to_number($row->Gasolina_Regular); ?></td>
                <td><?php echo mysql_to_number($row->GLP); ?></td>
                <td><?php echo mysql_to_number($row->Kerosina); ?></td>
                <td><?php echo mysql_to_number($row->Lubricantes); ?></td>
                <td><?php echo mysql_to_number($row->Nafta); ?></td>
                <td><?php echo mysql_to_number($row->Turbo); ?></td>
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