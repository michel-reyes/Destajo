<?php 
/*
DESTAJO-MODULE

date: 2014.03.10
type: php module
path: application/views/capacidad_carga/tabla_v.php

DESTAJO-MODULE-END
*/

if ($results->num_rows() > 0): ?>
	
	<table class="table-fixed-header fancyTable">
		
		<thead>
		    <tr class="fcr">
		        <?php for ($i=1; $i<=10; $i++): ?>
		            <th></th>
		        <?php endfor; ?>
		    </tr>
		    <tr>
		        <th colspan="6"></th>
		        <th colspan="4" class="text-center">Capacidades de bombeo <span class="muted">(descarga)</span></th>
		    </tr>
			<tr>				
				<th headers="checked">
                    <input type="checkbox" />
                    <?php echo anchor($modulo['nombre'] . "/show_content/?ofield=m_equipo.numero_operacional&otype=" . $otype, 'Equipo'); ?>
                </th>
                <th><?php echo anchor($modulo['nombre'] . "/show_content/?ofield=m_cuna.numero_operacional&otype=" . $otype, 'Cu&ntilde;a'); ?></th>
                <th><?php echo anchor($modulo['nombre'] . "/show_content/?ofield=m_capacidad_carga.capacidad_carga&otype=" . $otype, 'Capacidad de carga'); ?></th>
                <th><?php echo anchor($modulo['nombre'] . "/show_content/?ofield=m_capacidad_carga.viajes_promedio&otype=" . $otype, 'Viajes promedio'); ?></th>
                <th><?php echo anchor($modulo['nombre'] . "/show_content/?ofield=m_capacidad_carga.entregas_promedio&otype=" . $otype, 'Entregas promedio'); ?></th>
                <th><?php echo anchor($modulo['nombre'] . "/show_content/?ofield=m_capacidad_carga.tipo_de_producto&otype=" . $otype, 'Tipo de producto'); ?></th>
    
                <!-- Capacidades e bomebo -->
                <th><?php echo anchor($modulo['nombre'] . "/show_content/?ofield=turbina_equipo&otype=" . $otype, 'Turbina del equipo'); ?></th>
                <th><?php echo anchor($modulo['nombre'] . "/show_content/?ofield=g2&otype=" . $otype, 'Gravedad 2"'); ?></th>
                <th><?php echo anchor($modulo['nombre'] . "/show_content/?ofield=g3&otype=" . $otype, 'Gravedad 3"'); ?></th>
                <th><?php echo anchor($modulo['nombre'] . "/show_content/?ofield=g4&otype=" . $otype, 'Gravedad 4"'); ?></th>
			</tr>
		</thead>
		
		<tbody>
			<?php foreach($results->result() as $row): ?>
			<tr>
				<td headers="checked">
					<input type="checkbox" value="<?php echo $row->m_capacidad_carga_id; ?>" />
					<?php echo $row->no_equipo; ?>
				</td>
				<td><?php echo $row->no_cuna; ?></td>
				<td><strong><?php echo mysql_to_number($row->capacidad_carga); ?></strong></td>
				<td><?php echo $row->viajes_promedio; ?></td>				
				<td><?php echo $row->entregas_promedio; ?></td>
				<td><?php echo $row->tipo_de_producto; ?></td>
				<td ><?php echo mysql_to_number($row->turbina_equipo); ?></td>
				<td ><?php echo mysql_to_number($row->g2); ?></td>
				<td ><?php echo mysql_to_number($row->g3); ?></td>
				<td ><?php echo mysql_to_number($row->g4); ?></td>
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