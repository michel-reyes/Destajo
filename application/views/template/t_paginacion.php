<?php 
$paginacion =  $this->pagination->create_links();
$pa = $this->pagination->output['cur_page'];
$rpp = $this->pagination->per_page;
$from = 0;
$to = 0;

// from
if ($rpp < $all_rows AND $pa > 1)
{
    $from = $rpp * $pa - $rpp + 1;
}
else 
{
    $from = 1;	
}

// to
$b_campo = $this->session->userdata('buscar_campo_' . $modulo['nombre']);
if ($b_campo !== FALSE)
{
    if (( $from + $rpp ) <= $total_rows_show_of )
    {
        $to = $from + $rpp - 1;
    }
    else
    {
        $to = $total_rows_show_of;
    } 
}
else 
{
    if (( $from + $rpp ) <= $all_rows )
    {
        $to = $from + $rpp - 1;
    }
    else
    {
        $to = $all_rows;
    } 	
}
   
 
?>

<!-- Informacion de los registros -->
<a class="btn btn-text">
	<?php echo "$from - $to";
    if ($b_campo !== FALSE)
    {
        echo " de $total_rows_show_of (total: $all_rows)";
    }
    else
    {
        echo " de $all_rows";    
    }?>
    
</a>

<?php if (strlen($paginacion)): ?>	
    
        <button class="btn dropdown-toggle paginacion-menu" data-toggle="dropdown" >
            <?php echo 'P&aacute;gina ' . $this->pagination->output['cur_page']; ?>
            <span class="caret"></span>
        </button>
        <ul class="dropdown-menu paginacion-content pull-right">
        	<li class="nav-header">Ir a la p&aacute;gina:</li>
            <?php for ($i = 1; $i <= $this->pagination->output['num_pages']; $i++):
                if ($i != $this->pagination->output['cur_page']): ?> 
                <li><a href="<?php echo base_url() . $modulo['nombre'] . '/show_content/'; ?>" data-paginacion="<?php echo $i; ?>">P&aacute;gina <?php echo $i; ?></a></li>                            
            <?php
                endif;
            endfor; ?>                        
        </ul>
        <!-- previous page -->
        <?php if ($this->pagination->output['before_page'] == ""): ?>
            <button class="btn disabled paginacion-link-left"><i class="icon-angle-left"></i></button>
        <?php else: ?>
        <a class="btn paginacion-link paginacion-link-left" href="<?php echo $this->pagination->output['before_page']; ?>">
            <i class="icon-angle-left"></i>
        </a>
        <?php endif; ?>
        <!-- next page -->
        <?php if ($this->pagination->output['next_page'] == ""): ?>
            <button class="btn disabled paginacion-link-right"><i class="icon-angle-right"></i></button>
        <?php else: ?>
        <a class="btn paginacion-link paginacion-link-right" href="<?php echo $this->pagination->output['next_page']; ?>">
            <i class="icon-angle-right"></i>
        </a>
        <?php endif; ?>                   
  	
<?php endif; ?>	