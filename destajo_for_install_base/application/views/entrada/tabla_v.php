<?php
/*
DESTAJO-MODULE

date: 2014.03.21
type: php module
path: application/views/entrada/tabla_v.php

DESTAJO-MODULE-END
*/

if ($results->num_rows() > 0): ?>

  <table class="table-fixed-header fancyTable table-entrada">

    <thead>
        <tr class="fcr">
            <?php for($i=1; $i<=28; $i++): ?>
                <th></th>
            <?php endfor; ?>
        </tr>
        <tr>
          <th colspan="2"></th>
          <th colspan="2" class="text-center bg-1">Importes</th>
          <th colspan="2" class="text-center bg-2">Cumplimiento de la norma</th>
            <th colspan="13"></th>
            <th colspan="5" class="text-center">Horas</th>
            <th colspan="4"></th>
        </tr>
      <tr>
        <th headers="checked">
                    <input type="checkbox" />
                    <?php echo anchor($modulo['nombre'] . "/show_content/?ofield=op.chapa&otype=" . $otype, 'Chapa'); ?>
                </th>
                <th><?php echo anchor($modulo['nombre'] . "/show_content/?ofield=op.nombre&otype=" . $otype, 'Nombre y apellidos'); ?></th>
                <th class="bg-1"><?php echo anchor($modulo['nombre'] . "/show_content/?ofield=entrada.importe_viaje&otype=" . $otype, 'Mayorista'); ?></th>
                <th class="bg-1"><?php echo anchor($modulo['nombre'] . "/show_content/?ofield=entrada.importe_viaje_m&otype=" . $otype, 'Minorista'); ?></th>
                <th class="bg-2"><?php echo anchor($modulo['nombre'] . "/show_content/?ofield=entrada.cumplimiento_norma&otype=" . $otype, 'Mayorista'); ?></th>
                <th class="bg-2"><?php echo anchor($modulo['nombre'] . "/show_content/?ofield=entrada.cumplimiento_norma_minorista&otype=" . $otype, 'Minorista'); ?></th>
                <th><?php echo anchor($modulo['nombre'] . "/show_content/?ofield=entrada.hoja_de_ruta&otype=" . $otype, 'Hoja de ruta'); ?></th>
                <th><?php echo anchor($modulo['nombre'] . "/show_content/?ofield=entrada.fecha_incidencia&otype=" . $otype, 'Fecha de la incidencia'); ?></th>
                <th><?php echo anchor($modulo['nombre'] . "/show_content/?ofield=m_equipo.numero_operacional&otype=" . $otype, 'Equipo'); ?></th>
                <th><?php echo anchor($modulo['nombre'] . "/show_content/?ofield=m_cuna.numero_operacional&otype=" . $otype, 'Cu&ntilde;a'); ?></th>
                <th><?php echo anchor($modulo['nombre'] . "/show_content/?ofield=cc.capacidad_carga&otype=" . $otype, 'Capacidad de carga'); ?></th>
                <th><?php echo anchor($modulo['nombre'] . "/show_content/?ofield=p.producto&otype=" . $otype, 'Producto'); ?></th>
                <th><?php echo anchor($modulo['nombre'] . "/show_content/?ofield=lc.lugar_carga&otype=" . $otype, 'Lugar de carga'); ?></th>
                <th><?php echo anchor($modulo['nombre'] . "/show_content/?ofield=ld.lugar_descarga&otype=" . $otype, 'Lugar de descarga'); ?></th>
                <th><?php echo anchor($modulo['nombre'] . "/show_content/?ofield=entrada.litros_entregados&otype=" . $otype, 'Litros entregados'); ?></th>
                <th><?php echo anchor($modulo['nombre'] . "/show_content/?ofield=cd.km_recorridos&otype=" . $otype, 'Kil&oacute;metros recorridos'); ?></th>
                <th><?php echo anchor($modulo['nombre'] . "/show_content/?ofield=md.modo&otype=" . $otype, 'Modo de descarga'); ?></th>
                <th><?php echo anchor($modulo['nombre'] . "/show_content/?ofield=entrada.numero_de_viajes&otype=" . $otype, 'N&deg; de viajes'); ?></th>
                <th><?php echo anchor($modulo['nombre'] . "/show_content/?ofield=entrada.numero_de_entregas&otype=" . $otype, 'N&deg; de entregas'); ?></th>

                <!-- Horas -->
                <th><?php echo anchor($modulo['nombre'] . "/show_content/?ofield=entrada.horas_de_viaje&otype=" . $otype, 'Viaje'); ?></th>
                <th><?php echo anchor($modulo['nombre'] . "/show_content/?ofield=entrada.horas_interrupto&otype=" . $otype, 'Interrupto'); ?></th>
                <th><?php echo anchor($modulo['nombre'] . "/show_content/?ofield=entrada.horas_no_vinculado&otype=" . $otype, 'No vinculado'); ?></th>
                <th><?php echo anchor($modulo['nombre'] . "/show_content/?ofield=entrada.horas_nocturnidad_corta&otype=" . $otype, 'Nocturnidad corta'); ?></th>
                <th><?php echo anchor($modulo['nombre'] . "/show_content/?ofield=entrada.horas_nocturnidad_larga&otype=" . $otype, 'Nocturnidad larga'); ?></th>
                <!--<th><?php //echo anchor($modulo['nombre'] . "/show_content/?ofield=entrada.horas_capacitacion&otype=" . $otype, 'Capacitaci&oacute;n'); ?></th>
                <th><?php //echo anchor($modulo['nombre'] . "/show_content/?ofield=entrada.horas_movilizacion&otype=" . $otype, 'Movilizaci&oacute;n'); ?></th>
                <th><?php //echo anchor($modulo['nombre'] . "/show_content/?ofield=entrada.horas_feriado&otype=" . $otype, 'Feriado'); ?></th>
                <th><?php //echo anchor($modulo['nombre'] . "/show_content/?ofield=entrada.horas_ausencia&otype=" . $otype, 'Ausencia'); ?></th> -->

                <!--<th><?php //echo anchor($modulo['nombre'] . "/show_content/?ofield=ca.causa&otype=" . $otype, 'Causa de la ausencia'); ?></th>-->
                <th><?php echo anchor($modulo['nombre'] . "/show_content/?ofield=entrada.pago_feriado&otype=" . $otype, 'Pago feriado'); ?></th>
                <th><?php echo anchor($modulo['nombre'] . "/show_content/?ofield=entrada.fecha_captacion&otype=" . $otype, 'Fecha de captacion'); ?></th>
                <th><?php echo anchor($modulo['nombre'] . "/show_content/?ofield=entrada.fecha_inicio_periodo_pago&otype=" . $otype, 'Inicio del periodo de pago'); ?></th>
                <th><?php echo anchor($modulo['nombre'] . "/show_content/?ofield=entrada.fecha_final_periodo_pago&otype=" . $otype, 'Final del periodo de pago'); ?></th>
      </tr>
    </thead>

    <tbody>
      <?php foreach($results->result() as $row): ?>
      <tr>
        <td headers="checked">
            <!-- Si la entrada esta fuera del rango del periodo de pago no editar ni eliminar -->
                    <?php if($fipp == $row->fecha_inicio_periodo_pago): ?>
                <input type="checkbox" value="<?php echo $row->entrada_id; ?>" />
            <?php endif; ?>
            <?php echo $row->chapa; ?>
        </td>
        <td><?php echo $row->nombre.' '.$row->apellidos; ?></td>
        <td class="text-right bg-1"><strong>
          <?php if($row->importe_viaje == NULL && $row->importe_viaje_progresivo_i == NULL && $row->importe_viaje == 0.00 && $row->importe_viaje_progresivo_i == 0.00)
                echo "-";
                else
                echo ($row->importe_viaje_progresivo_i == NULL || $row->importe_viaje_progresivo_i == '0.00') ? mysql_to_number($row->importe_viaje): mysql_to_number($row->importe_viaje_progresivo_i); ?></strong></td>
        <td class="text-right bg-1"><strong><?php if ($row->importe_viaje_m == NULL && $row->importe_viaje_m == 0.00) echo "-"; else echo mysql_to_number($row->importe_viaje_m); ?></strong></td>
        <td class="text-right bg-2"><strong><?php if($row->cumplimiento_norma == NULL || $row->cumplimiento_norma == 0.00) echo "-"; else  echo mysql_to_number($row->cumplimiento_norma); ?></strong></td>
        <td class="text-right bg-2"><strong><?php if($row->cumplimiento_norma_minorista == NULL || $row->cumplimiento_norma_minorista == 0.00) echo  "-"; else echo mysql_to_number($row->cumplimiento_norma_minorista); ?></strong></td>
        <td><?php echo $row->hoja_de_ruta; ?></td>
        <td class="text-right"><?php echo to_date($row->fecha_incidencia); ?></td>
        <td><?php echo $row->no_equipo; ?></td>
        <td><?php echo $row->no_cuna; ?></td>
        <td class="text-right"><?php echo $row->capacidad_carga; ?></td>
        <td><?php echo $row->producto; if ($row->tipo != "") echo "<span class='muted'> (" . $row->tipo . ")</span>"; ?></td>
        <td><?php echo $row->lugar_carga; ?></td>
        <td><?php echo $row->lugar_descarga; ?></td>
        <td class="text-right"><?php echo $row->litros_entregados; ?></td>
        <td class="text-right"><?php echo mysql_to_number($row->km_recorridos); ?></td>
        <td><?php echo $row->modo; ?></td>
        <td><?php echo $row->numero_de_viajes; ?></td>
        <td><?php echo $row->numero_de_entregas; ?></td>

        <!-- Horas -->
        <td class="text-right"><?php echo mysql_to_number($row->horas_de_viaje); ?></td>
        <td class="text-right"><?php echo mysql_to_number($row->horas_interrupto); ?></td>
        <td class="text-right"><?php echo mysql_to_number($row->horas_no_vinculado); ?></td>
        <td class="text-right"><?php echo mysql_to_number($row->horas_nocturnidad_corta); if (! empty($row->horas_nocturnidad_corta)) echo "<span class='muted'> (" . mysql_to_number($row->cuantia_horaria_nocturnidad_corta,3) . ")</span>"; ?></td>
        <td class="text-right"><?php echo mysql_to_number($row->horas_nocturnidad_larga); if (! empty($row->horas_nocturnidad_larga)) echo "<span class='muted'> (" . mysql_to_number($row->cuantia_horaria_nocturnidad_larga,3) . ")</span>"; ?></td>
        <!--<td><?php //echo mysql_to_number($row->horas_capacitacion); ?></td>
        <td><?php //echo mysql_to_number($row->horas_movilizacion); ?></td>
        <td><?php //echo mysql_to_number($row->horas_feriado); ?></td>
        <td><?php //echo mysql_to_number($row->horas_ausencia); ?></td>-->

        <!--<td><?php //echo $row->causa; ?></td>-->
        <td><span class="<?php echo ($row->pago_feriado == 1) ? "check-mark" : "radio-mark"; ?>"></span></td>
        <td class="text-right"><?php echo to_date($row->fecha_captacion); ?></td>
        <td class="text-right"><?php echo date("d/m/Y", $row->fecha_inicio_periodo_pago); ?></td>
                <td class="text-right"><?php echo date("d/m/Y", $row->fecha_final_periodo_pago); ?></td>
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