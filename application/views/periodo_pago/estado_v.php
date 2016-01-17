<!-- El periodo depago esta cerrado -->
<?php if ($ppa == 1): ?>
   
   <p><strong>El periodo de pago est&aacute; inicado.</strong></p>
   <table class="table-form-auxiliar">
       <tbody>
           <tr>
               <td><span class="muted">Fecha de inico del periodo:</span></td>
               <td><?php echo date("d/m/Y", $fipp); ?></td>
           </tr>
           <tr>
               <td><span class="muted">Fecha de culminaci&oacute;n del periodo:</span></td>
               <td><?php echo date("d/m/Y", $ffpp); ?></td>
           </tr>
       </tbody>
   </table>

<?php else: ?>
<!-- El periodo de pago esta abierto --> 
   
    <p><strong>El periodo de pago est&aacute; cerrado.</strong></p>
    <table class="table-form-auxiliar">
       <tbody>
           <tr>
               <td><span class="muted">Periodo de pago concluido:</span></td>
               <td><?php echo date("d/m/Y", $fipp) . " &rarr; " . date("d/m/Y", $ffpp); ?></td>
           </tr>
           <tr>
               <td><span class="muted">El pr&oacute;ximo periodo de pago<br/>deber&iacute;a estar comprendido<br />entre las siguiente fechas:</span></td>
               <td><?php echo date("d/m/Y", strtotime("first day of next month", $ffpp)) . " &rarr; " . date("d/m/Y", strtotime("last day of next month", $ffpp)); ?></td>
           </tr>
       </tbody>
   </table>
    
<?php endif; ?>