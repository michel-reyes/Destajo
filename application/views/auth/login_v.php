<?php
/*
DESTAJO-MODULE

date: 2014.03.10
type: php module
path: application/views/auth/login_v.php

DESTAJO-MODULE-END
*/
?>
<div class="container-fluid container-signin">

	<h2 class="form-signin-heading text-center">Inicie sesi&oacute;n en <a href="<?php echo base_url('entrada'); ?>">Destajo</a> con su cuenta</h2>

	<div class="card card-signin">
		<img class="img-circle profile-img" src="<?php echo base_url('css/img/avatar.png'); ?>" alt="">
		<?php echo form_open('auth/login', array('class'=>'main-form form-signin form-validate')); ?>
			<input type="text" class="form-control" name="nombre_login" id="nombre_login" placeholder="Nombre de usuario" autofocus autocomplete="off">
			<input type="password" class="form-control" name="password_login" id="password_login" placeholder="Contrase&ntilde;a">
			<button class="btn btn-large btn-primary btn-block comando-login">
				Acceder
			</button>

		<?php echo form_close(); ?>
	</div>
</div>