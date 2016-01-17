<?php 
/*
DESTAJO-MODULE

date: 2014.03.10
type: php module
path: application/views/lugar_descarga/tabla_v.php

DESTAJO-MODULE-END
*/

if ($results->num_rows() > 0): ?>
	
	<table class="table-fixed-header fancyTable">
		
		<thead>
		    <tr class="fcr">
		        <?php for ($i=1; $i<=16; $i++): ?>
		            <th></th>
		        <?php endfor; ?>
		    </tr>
		    <tr>
		        <th colspan="2"></th>
		        <th colspan="2" class="text-center">Velocidad media</th>
		        <th colspan="12" class="text-center">Productos descargables</th>
		    </tr>
			<tr>				
				<th headers="checked">
                    <input type="checkbox" />
                    <?php echo anchor($modulo['nombre'] . "/show_content/?ofield=m_lugar_descarga.lugar_descarga&otype=" . $otype, 'Lugar de descarga'); ?>
                </th>
                <th><?php echo anchor($modulo['nombre'] . "/show_content/?ofield=m_lugar_descarga.capacidad_bombeo_turbina_cliente&otype=" . $otype, 'Capacidad de bombeo <i class="icon-question-sign tip" title="Turbina del cliente"></i>'); ?></th>
                <th><?php echo anchor($modulo['nombre'] . "/show_content/?ofield=m_lugar_descarga.velocidad_media_a_k&otype=" . $otype, 'Alcohol y Kerosina'); ?></th>
                <th><?php echo anchor($modulo['nombre'] . "/show_content/?ofield=m_lugar_descarga.velocidad_media_d&otype=" . $otype, 'Diesel'); ?></th>
                <th><?php echo anchor($modulo['nombre'] . "/show_content/?ofield=Alcohol&otype=" . $otype, 'Alcohol'); ?></th>
                <th><?php echo anchor($modulo['nombre'] . "/show_content/?ofield=BioMix&otype=" . $otype, 'BioMix'); ?></th>
                <th><?php echo anchor($modulo['nombre'] . "/show_content/?ofield=Crudo&otype=" . $otype, 'Crudo'); ?></th>
                <th><?php echo anchor($modulo['nombre'] . "/show_content/?ofield=Diesel&otype=" . $otype, 'Diesel'); ?></th>
                <th><?php echo anchor($modulo['nombre'] . "/show_content/?ofield=Fuell&otype=" . $otype, 'Fuell'); ?></th>
                <th><?php echo anchor($modulo['nombre'] . "/show_content/?ofield=Gasolina_Especial&otype=" . $otype, 'Gasolina Especial'); ?></th>
                <th><?php echo anchor($modulo['nombre'] . "/show_content/?ofield=Gasolina_Regular&otype=" . $otype, 'Gasolina Regular'); ?></th>
                <th><?php echo anchor($modulo['nombre'] . "/show_content/?ofield=GLP&otype=" . $otype, 'GLP'); ?></th>
                <th><?php echo anchor($modulo['nombre'] . "/show_content/?ofield=Kerosina&otype=" . $otype, 'Kerosina'); ?></th>
                <th><?php echo anchor($modulo['nombre'] . "/show_content/?ofield=Lubricantes&otype=" . $otype, 'Lubricantes'); ?></th>
                <th><?php echo anchor($modulo['nombre'] . "/show_content/?ofield=Nafta&otype=" . $otype, 'Nafta'); ?></th>
                <th><?php echo anchor($modulo['nombre'] . "/show_content/?ofield=Turbo&otype=" . $otype, 'Turbo'); ?></th>
			</tr>
		</thead>
		
		<tbody>
			<?php foreach($results->result() as $row): ?>
			<tr>
				<td headers="checked">
					<input type="checkbox" value="<?php echo $row->m_lugar_descarga_id; ?>" />
					<?php echo $row->lugar_descarga; ?>
				</td>
				<td><?php echo mysql_to_number($row->capacidad_bombeo_turbina_cliente); ?></td>
				<td><?php echo mysql_to_number($row->velocidad_media_a_k); ?></td>
				<td><?php echo mysql_to_number($row->velocidad_media_d); ?></td>
				<td ><span class="<?php echo ($row->Alcohol >= 1) ? "check-mark" : "radio-mark"; ?>"></span></td>
				<td ><span class="<?php echo ($row->BioMix >= 1) ? "check-mark" : "radio-mark"; ?>"></span></td>
				<td ><span class="<?php echo ($row->Crudo >= 1) ? "check-mark" : "radio-mark"; ?>"></span></td>
				<td ><span class="<?php echo ($row->Diesel >= 1) ? "check-mark" : "radio-mark"; ?>"></span></td>
				<td ><span class="<?php echo ($row->Fuell >= 1) ? "check-mark" : "radio-mark"; ?>"></span></td>
				<td ><span class="<?php echo ($row->Gasolina_Especial >= 1) ? "check-mark" : "radio-mark"; ?>"></span></td>
				<td ><span class="<?php echo ($row->Gasolina_Regular >= 1) ? "check-mark" : "radio-mark"; ?>"></span></td>
				<td ><span class="<?php echo ($row->GLP >= 1) ? "check-mark" : "radio-mark"; ?>"></span></td>
				<td ><span class="<?php echo ($row->Kerosina >= 1) ? "check-mark" : "radio-mark"; ?>"></span></td>
				<td ><span class="<?php echo ($row->Lubricantes >= 1) ? "check-mark" : "radio-mark"; ?>"></span></td>
				<td ><span class="<?php echo ($row->Nafta >= 1) ? "check-mark" : "radio-mark"; ?>"></span></td>
				<td ><span class="<?php echo ($row->Turbo >= 1) ? "check-mark" : "radio-mark"; ?>"></span></td>
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