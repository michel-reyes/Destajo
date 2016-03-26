<?php
/*
DESTAJO-MODULE

date: 2015.01.19
type: php module
path: application/views/salida_salario_trabajador/tabla_v.php

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
          <th rowspan="2" headers="checked" style="vertical-align: middle">
              <input type="checkbox" />
              <?php echo anchor($modulo['nombre'] . "/show_content/?ofield=salida_salario_trabajador.chapa&otype=" . $otype, 'Chapa'); ?>
          </th>
          <th rowspan="2" style="vertical-align: middle"><?php echo anchor($modulo['nombre'] . "/show_content/?ofield=salida_salario_trabajador.ci&otype=" . $otype, 'CI'); ?></th>
          <th rowspan="2" style="vertical-align: middle"><?php echo anchor($modulo['nombre'] . "/show_content/?ofield=salida_salario_trabajador.nombre&otype=" . $otype, 'Nombre y apellidos'); ?></th>
          <th colspan="2" style="text-align: center">Importes</th>
          <th colspan="2" style="text-align: center">Norma</th>
          <th colspan="6" style="text-align: center">Horas</th>
          <th rowspan="2" style="vertical-align: middle"><?php echo anchor($modulo['nombre'] . "/show_content/?ofield=salida_salario_trabajador.fecha_inicio_periodo_pago&otype=" . $otype, 'Inicio periodo de pago'); ?></th>
          <th rowspan="2" style="vertical-align: middle"><?php echo anchor($modulo['nombre'] . "/show_content/?ofield=salida_salario_trabajador.fecha_final_periodo_pago&otype=" . $otype, 'Final periodo de pago'); ?></th>
        </tr>
        <tr>
          <th>Mayorista</th>
          <th>Minorista</th>
          <th>Mayorista</th>
          <th>Minorista</th>
          <!-- Horas -->
            <th>Viaje</th>
            <th>V. Minorista</th>
            <th>Interrupto</th>
            <th>No vinculado</th>
            <th>N. corta</th>
            <th>N. larga</th>
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
        <td><?php echo $row->ci; ?></td>
        <td><?php echo $row->nombre . " " . $row->apellidos; ?></td>
        <td><?php echo mysql_to_number($row->importe_viaje); ?></td>
        <td><?php echo mysql_to_number($row->importe_viaje_m); ?></td>
        <td><?php echo mysql_to_number($row->cumplimiento_norma); ?></td>
        <td><?php echo mysql_to_number($row->cumplimiento_norma_m); ?></td>
        <td><?php echo mysql_to_number($row->horas_viaje); ?></td>
        <td><?php echo mysql_to_number($row->horas_viaje_m); ?></td>
        <td><?php echo mysql_to_number($row->horas_interrupto + $row->horas_interrupto_m); ?></td>
        <td><?php echo mysql_to_number($row->horas_no_vinculado + $row->horas_no_vinculado_m); ?></td>
        <td><?php echo mysql_to_number($row->horas_nocturnidad_corta /*+ $row->horas_nocturnidad_corta_m*/); ?></td>
        <td><?php echo mysql_to_number($row->horas_nocturnidad_larga /*+ $row->horas_nocturnidad_larga_m*/); ?></td>
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