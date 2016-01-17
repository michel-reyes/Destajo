<?php if ($results->num_rows() > 0): ?>
	
	<table class="table-fixed-header fancyTable">
		
		<thead>
		    <tr class="fcr">
		        <?php for($i=1; $i<=12; $i++): ?>
		            <th></th>
		        <?php endfor; ?>
		    </tr>
		    <tr>
		        <th colspan="4"></th>
		        <th colspan="8" class="text-center">Kil&oacute;metros recorridos por cada v&iacute;a <span class="muted">(Kms)</span></th>
		    </tr>
			<tr>				
				<th headers="checked">
                    <input type="checkbox" />
                    <?php echo anchor($modulo['nombre'] . "/show_content/?ofield=carga_descarga.codigo&otype=" . $otype, 'C&oacute;digo'); ?>
                </th>
                <th><?php echo anchor($modulo['nombre'] . "/show_content/?ofield=m_lugar_carga.lugar_carga&otype=" . $otype, 'Lugar de carga'); ?></th>
                <th><?php echo anchor($modulo['nombre'] . "/show_content/?ofield=carga_descarga.km_recorridos&otype=" . $otype, 'Kil&oacute;metros'); ?></th>
                <th><?php echo anchor($modulo['nombre'] . "/show_content/?ofield=m_lugar_descarga.lugar_descarga&otype=" . $otype, 'Lugar de descarga'); ?></th>
                <th><?php echo anchor($modulo['nombre'] . "/show_content/?ofield=m_lugar_carga.PU&otype=" . $otype, 'PU', array('title'=>'Per&iacute;metro urbano','data-title'=>'top')); ?></th>
                <th><?php echo anchor($modulo['nombre'] . "/show_content/?ofield=m_lugar_carga.C&otype=" . $otype, 'C', array('title'=>'Carretera','data-title'=>'top')); ?></th>
                <th><?php echo anchor($modulo['nombre'] . "/show_content/?ofield=m_lugar_carga.A&otype=" . $otype, 'A', array('title'=>'Autopista','data-title'=>'top')); ?></th>
                <th><?php echo anchor($modulo['nombre'] . "/show_content/?ofield=m_lugar_carga.T&otype=" . $otype, 'T' , array('title'=>'Terrapl&eacute;n','data-title'=>'top')); ?></th>
                <th><?php echo anchor($modulo['nombre'] . "/show_content/?ofield=m_lugar_carga.CM&otype=" . $otype, 'CM', array('title'=>'Carretera de monta&ntilde;a','data-title'=>'top')); ?></th>
                <th><?php echo anchor($modulo['nombre'] . "/show_content/?ofield=m_lugar_carga.CT&otype=" . $otype, 'CT', array('title'=>'Camino de tierra','data-title'=>'top')); ?></th>
                <th><?php echo anchor($modulo['nombre'] . "/show_content/?ofield=m_lugar_carga.TM&otype=" . $otype, 'TM', array('title'=>'Terrapl&eacute;n de monta&ntilde;','data-title'=>'top')); ?></th>
                <th><?php echo anchor($modulo['nombre'] . "/show_content/?ofield=m_lugar_carga.CV&otype=" . $otype, 'CV', array('title'=>'Camino vecinal','data-title'=>'top')); ?></th>
			</tr>
		</thead>
		
		<tbody>
			<?php foreach($results->result() as $row): ?>
			<tr>
				<td headers="checked">
					<input type="checkbox" value="<?php echo $row->carga_descarga_id; ?>" />
					<?php echo $row->codigo; ?>
				</td>
				<td><?php echo $row->lugar_carga; ?></td>
				<td><span class="muted">&mdash; <?php echo mysql_to_number($row->km_recorridos); ?><em class="km-arrow"> &rarr;</em></span></td>
				<td><?php echo $row->lugar_descarga; ?></td>
				<td ><?php echo mysql_to_number($row->PU); ?></td>
				<td ><?php echo mysql_to_number($row->C); ?></td>
				<td ><?php echo mysql_to_number($row->A); ?></td>
				<td ><?php echo mysql_to_number($row->T); ?></td>
				<td ><?php echo mysql_to_number($row->CM); ?></td>
				<td ><?php echo mysql_to_number($row->CT); ?></td>
				<td ><?php echo mysql_to_number($row->TM); ?></td>
				<td ><?php echo mysql_to_number($row->CV); ?></td>
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