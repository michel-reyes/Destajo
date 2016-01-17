<?php
/*
DESTAJO-MODULE

date: 2014.03.10
type: php module
path: application/views/salida_salario_trabajador/imprimir_v.php

DESTAJO-MODULE-END
*/
?>

<!-- Vista previa -->

<div id="main-content-all">    
    <div class="mc-inner">
        
        <!-- Toolbar -->
        <div class="navbar navbar-googlenav vista-previa-toolbar navbar-fixed-top" id="main-toolbar">
            
            <div class="navbar-inner">
                
                <div class="btn-toolbar">

                    <ul class="nav navbar-googlenav">
                        
                        <li><a title="Regresar" href="<?php echo $modulo['nombre']; ?>"> <i class="icon-chevron-left"></i> </a></li>
                        
                        <li class="divider-vertical"></li>
                        
                        <li><a href="javascript:window.print();"> <i class="icon-print"></i> </a></li> 
                        
                        <li><a title="Exportar a Excel" href="<?php echo base_url($modulo['nombre'] . '/exportar'); ?>"> <i class="icon-file-alt"></i> </a></li>                       
                                           
                    </ul>
                    
                </div>
                
            </div>
            
        </div>
        
        <!-- Table content -->
        <div id="word-template">            
            <div class="word-template-inner">
                
                <div class="sheet hrz">                    
                    
                    <div class="sheet-header">
                        <div class="row-fluid">
                            <div class="span2">
                                <img alt="Destajo" src="<?php echo base_url('css/img/logo32.png'); ?>" />
                            </div>
                            <div class="span8 sheet-title">
                                <p class="text-center">Prenomina de choferes y ayudantes de distribuci&oacute;n</p>
                            </div>
                            <div class="span2 text-right">
                                <?php echo date("d/m/Y",$fipp) . " - " . date("d/m/Y",$ffpp);  ?>
                            </div>
                        </div>
                    </div>
                    
                    <div class="sheet-body">           
                        <table class="table-printable table table-condensed table-bordered">
                            
                            <thead>
                                <tr>
                                    <th rowspan="2">Chapa</th>
                                    <th rowspan="2">Nombre y apellidos</th>
                                    <th colspan="2">Importes</th>
                                    <th colspan="2">Norma</th>
                                    <th colspan="6">Horas</th>
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
                                <?php if ($query->num_rows() > 0): ?>
                                    <?php foreach($query->result() as $row): ?>
                                        <tr>
                                            <td><?php echo $row->chapa; ?></td>
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
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <p class="nosearch-title">No encontramos ning&uacute;n resultado</p>
                                <?php endif; ?>
                            </tbody>
                                                        
                        </table>
                    </div>
                                        
                </div>
                
            </div>            
        </div>  
        
    </div>    
</div>