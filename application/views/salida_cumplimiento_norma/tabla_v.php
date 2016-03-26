<?php 

/*
DESTAJO-MODULE

date: 2014.03.20
type: php module
path: application/views/salida_cumplimiento_norma/tabla_v.php

DESTAJO-MODULE-END
*/

if ($results->num_rows() > 0): ?>

	<table class="table-fixed-header fancyTable">

		<thead>
		    <tr class="fcr">
		        <?php for ($i=1; $i<=5; $i++): ?>
		            <th></th>
		        <?php endfor; ?>
		    </tr>
		    <tr>
		        <th class="warning text-right">
		            <div class="pull-right">
		            <span class="thspan">Cumplimiento general mayorista: </span>
		            <span class="<?php if ($cgn->cn<70 OR $cgn->cn>140) echo "text-error"; ?>"><?php echo mysql_to_number($cgn->cn); ?></span>
		            </div>
		        </th>
		        <th colspan="2" class="warning text-right">
                <div class="pull-right">
                <span class="thspan">Cumplimiento general minorista: </span>
                <span class="<?php if ($cgn->cnm<70 OR $cgn->cnm>140) echo "text-error"; ?>"><?php echo mysql_to_number($cgn->cnm); ?></span>
                </div>
            </th>
		        <th colspan="2" class="text-center">Periodo de pago</th>
		    </tr>
			<tr>
				<th headers="checked">
            <input type="checkbox" />
            <?php echo anchor($modulo['nombre'] . "/show_content/?ofield=salida_cumplimiento_norma.producto&otype=" . $otype, 'Producto'); ?></th>
        <th><?php echo anchor($modulo['nombre'] . "/show_content/?ofield=salida_cumplimiento_norma.cumplimiento_norma&otype=" . $otype, 'Cumplimiento de la norma'); ?></th>
        <th><?php echo anchor($modulo['nombre'] . "/show_content/?ofield=salida_cumplimiento_norma.cumplimiento_norma_minorista&otype=" . $otype, 'Cumplimiento de la norma minorista'); ?></th>
        <th><?php echo anchor($modulo['nombre'] . "/show_content/?ofield=salida_cumplimiento_norma.fecha_inicio_periodo_pago&otype=" . $otype, 'Inicio periodo de pago'); ?></th>
        <th><?php echo anchor($modulo['nombre'] . "/show_content/?ofield=salida_cumplimiento_norma.fecha_final_periodo_pago&otype=" . $otype, 'Final periodo de pago'); ?></th>
			</tr>
		</thead>

		<tbody>
			<?php foreach($results->result() as $row): ?>
			<tr>
				<td headers="checked">
				    <!-- Si la entrada esta fuera del rango del periodo de pago no editar ni eliminar -->
                    <?php if($fipp == $row->fecha_inicio_periodo_pago): ?>
				        <input type="checkbox" value="<?php echo $row->salida_cumplimiento_norma_id; ?>" />
				    <?php endif; ?>

				    <?php echo $row->producto; ?>
				</td>
				<td><?php echo $row->cumplimiento_norma; ?></td>
				<td><?php echo $row->cumplimiento_norma_minorista; ?></td>
				<td><?php echo date("d/m/Y", $row->fecha_inicio_periodo_pago); ?></td>
        <td><?php echo date("d/m/Y", $row->fecha_final_periodo_pago); ?></td>
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