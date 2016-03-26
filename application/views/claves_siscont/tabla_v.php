<?php
/*
DESTAJO-MODULE

date: 2014.12.17
type: php module
path: application/views/claves_siscont/tabla_v.php

DESTAJO-MODULE-END
*/
?>

<?php if ($results->num_rows() > 0): ?>
	
	<table class="table-fixed-header fancyTable">
		
		<thead>
			<tr>				
				<th headers="checked"><input type="checkbox" />
					<?php echo anchor($modulo['nombre'] . "/show_content/?ofield=m_claves_siscont.clave&otype=" . $otype, 'Clave'); ?>				
				</th>
				<th>
				    <?php echo anchor($modulo['nombre'] . "/show_content/?ofield=m_claves_siscont.sigla&otype=" . $otype, 'Sigla'); ?>
				</th>
				<th>
                    <?php echo anchor($modulo['nombre'] . "/show_content/?ofield=m_claves_siscont.valor&otype=" . $otype, 'Valor'); ?>
                </th>
                <th>
                    <?php echo anchor($modulo['nombre'] . "/show_content/?ofield=m_claves_siscont.unidad_medida&otype=" . $otype, 'U.M.'); ?>
                </th>
			</tr>
		</thead>
		
		<tbody>
			<?php foreach($results->result() as $row): ?>
			<tr>
				<td headers="checked">
					<input type="checkbox" value="<?php echo $row->m_claves_siscont_id; ?>" />
					<?php echo $row->clave; ?>
				</td>
				<td><strong><?php echo $row->sigla; ?></strong></td>
				<td><?php echo $row->valor; ?></td>
				<td><?php echo $row->unidad_medida; ?></td>
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