<div id="main-layout" class="clearfix">
	
	<div id="main-sidebar">
		
		<div class="ms-inner">
			
			<!-- Toolbar -->	
			<div class="navbar navbar-googlenav">
				<div class="navbar-inner">
					<form class="navbar-search">
						<input class="search-query span2" type="text" placeholder="Filtro r&aacute;pida..."
						data-modulo="<?php echo base_url($modulo['nombre'] . '/show_content'); ?>" />
					</form>
				</div>
			</div>
			
			<!-- Navbar -->
			<ul class="nav nav-list" id="side-menu">
				<li class="nav-header">UEB</li>
				<li class="<?php if (! $this->session->userdata('filtro')): ?> active <?php endif; ?> ueb-list"><a data-id="all" href="<?php echo base_url($modulo['nombre'] . '/show_content'); ?>">Todo</a></li>
				<?php if ($empresas_data->num_rows() > 0): ?>
					<?php foreach ($empresas_data->result() as $empresa): ?>
						<li class="ueb-list <?php if ($this->session->userdata('filtro') AND $this->session->userdata('filtro') == $empresa->empresa_id): ?> active <?php endif; ?>">
							<a data-id="<?php echo $empresa->empresa_id; ?>" href="<?php echo base_url($modulo['nombre'] . '/show_content'); ?>"><?php echo $empresa->empresa; ?> 
							<?php if ($empresa->total>0): ?>
								<span class="counter">
									<?php if ($empresa->estado == 'Activa'): 
										echo "<b>$empresa->total</b>"; 
										else:
									 	echo $empresa->total;
									endif?> 
									
								</span>
							<?php endif; ?> 
							</a>
						</li>
					<?php endforeach; ?>
				<?php endif; ?>
				
				<li class="divider"></li>
				
				<li><?php echo anchor('estadisticas', 'Estad&iacute;sticas'); ?></li>
			</ul>
		
		</div>

	</div> <!-- /Main-sidebar -->