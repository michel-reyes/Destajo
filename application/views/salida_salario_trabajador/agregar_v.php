<?php 
/*
DESTAJO-MODULE

date: 2014.12.17
type: php module
path: application/views/salida_salario_trabajador/agregar_v.php

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
		        <th colspan="2" class="text-center">Importe del viaje</th>
		        <th colspan="2" class="text-center">Cumplimiento de la norma</th>
		        <th colspan="6" class="text-center">Horas</th>
		        <th colspan="2" class="text-center">Periodo de pago</th>
		    </tr>
			<tr>
                <th headers="checked">
                    <input type="checkbox" />
                    <?php echo anchor($modulo['nombre'] . "/show_content/?ofield=salida_salario_trabajador.chapa&otype=" . $otype, 'Chapa'); ?>
                </th>
                <th><?php echo anchor($modulo['nombre'] . "/show_content/?ofield=salida_salario_trabajador.nombre&otype=" . $otype, 'Nombre y apellidos'); ?></th>
                
                <th><?php echo anchor($modulo['nombre'] . "/show_content/?ofield=salida_salario_trabajador.importe_viaje&otype=" . $otype, 'Mayorista'); ?></th>
                <th><?php echo anchor($modulo['nombre'] . "/show_content/?ofield=salida_salario_trabajador.importe_viaje_m&otype=" . $otype, 'Minorista'); ?></th>
                
                <th><?php echo anchor($modulo['nombre'] . "/show_content/?ofield=salida_salario_trabajador.cumplimiento_norma&otype=" . $otype, 'Mayorista'); ?></th>
                <th><?php echo anchor($modulo['nombre'] . "/show_content/?ofield=salida_salario_trabajador.cumplimiento_norma_m&otype=" . $otype, 'Minorista'); ?></th>
                
                <th><?php echo anchor($modulo['nombre'] . "/show_content/?ofield=salida_salario_trabajador.horas_viaje&otype=" . $otype, 'Viaje'); ?></th>
                <th><?php echo anchor($modulo['nombre'] . "/show_content/?ofield=salida_salario_trabajador.horas_viaje_m&otype=" . $otype, 'Viaje Minorista'); ?></th>
                <th><?php echo anchor($modulo['nombre'] . "/show_content/?ofield=salida_salario_trabajador.horas_interrupto&otype=" . $otype, 'Interrupto'); ?></th>
                <th><?php echo anchor($modulo['nombre'] . "/show_content/?ofield=salida_salario_trabajador.horas_no_vinculado&otype=" . $otype, 'No vinculado'); ?></th>
                <th><?php echo anchor($modulo['nombre'] . "/show_content/?ofield=salida_salario_trabajador.horas_nocturnidad_corta&otype=" . $otype, 'Nocturnidad corta'); ?></th>
                <th><?php echo anchor($modulo['nombre'] . "/show_content/?ofield=salida_salario_trabajador.horas_nocturnidad_larga&otype=" . $otype, 'Nocturnidad larga'); ?></th>
                
                <th><?php echo anchor($modulo['nombre'] . "/show_content/?ofield=salida_salario_trabajador.fecha_inicio_periodo_pago&otype=" . $otype, 'Inicio periodo de pago'); ?></th>
                <th><?php echo anchor($modulo['nombre'] . "/show_content/?ofield=salida_salario_trabajador.fecha_final_periodo_pago&otype=" . $otype, 'Final periodo de pago'); ?></th>
           </tr>
		</thead>
		
		<tbody>
			<?php foreach($results->result() as $row): ?>
			<tr>
				<td headers="checked">
				    <!-- Si la entrada esta fuera del rango del periodo de pago no editar ni eliminar -->
                    <?php if($fipp == $row->fecha_inicio_periodo_pago): ?>
					   <input type="checkbox" value="<?php echo $row->salida_salario_trabajador_id; ?>" />
					<?php endif; ?>
					
					<?php echo $row->chapa; ?>
				</td>
				<td><?php echo $row->nombre . " " . $row->apellidos; ?></td>
				<td><?php echo mysql_to_number($row->importe_viaje); ?></td>
				<td><?php echo mysql_to_number($row->importe_viaje_m); ?></td>								
				<td><?php echo mysql_to_number($row->cumplimiento_norma); ?></td>
				<td><?php echo mysql_to_number($row->cumplimiento_norma_m); ?></td>	
						
				<td><?php echo mysql_to_number($row->horas_viaje); ?></td>
				<td><?php echo mysql_to_number($row->horas_viaje_m); ?></td>
				<td><?php echo mysql_to_number($row->horas_interrupto + $row->horas_interrupto_m); ?></td>
				<td><?php echo mysql_to_number($row->horas_no_vinculado + $row->horas_no_vinculado_m); ?></td>				
				<td><?php echo mysql_to_number($row->horas_nocturnidad_corta + $row->horas_nocturnidad_corta_m); if (! empty($row->horas_nocturnidad_corta) || ! empty($row->horas_nocturnidad_corta_m)) echo "<span class='muted'> (" . mysql_to_number($row->cuantia_horaria_nocturnidad_corta + $row->horas_nocturnidad_corta_m) . ")</span>"; ?></td>
				<td><?php echo mysql_to_number($row->horas_nocturnidad_larga + $row->horas_nocturnidad_larga_m); if (! empty($row->horas_nocturnidad_larga) || ! empty($row->horas_nocturnidad_larga_m)) echo "<span class='muted'> (" . mysql_to_number($row->cuantia_horaria_nocturnidad_larga + $row->cuantia_horaria_nocturnidad_larga_m) . ")</span>"; ?></td>
				
				<td><?php echo date("d/m/Y",$row->fecha_inicio_periodo_pago); ?></td>
				<td><?php echo date("d/m/Y",$row->fecha_final_periodo_pago); ?></td>
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