<?php
/*
DESTAJO-MODULE

date: 2014.03.10
type: php module
path: application/views/template/t_header.php

DESTAJO-MODULE-END
*/
?>
<!doctype html>
<html class="no-js" lang="">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <title>Destajo</title>
        <meta name="description" content="destajo, sistema de pago, pago, mayorista, destajo individual mayorista, transcupet">
        <meta name="viewport" content="width=device-width">

        <!-- Place favicon.ico and apple-touch-icon.png in the root directory -->
        <link rel="shortcut icon" href="favicon.ico" />
        
        <link rel="stylesheet" href="<?php echo base_url(); ?>css/vendor/ui/jquery-ui.css">        
	    <link rel="stylesheet" href="<?php echo base_url(); ?>css/vendor/bootstrap.css">
	    <link rel="stylesheet" href="<?php echo base_url(); ?>css/vendor/bootstrap-responsive.css">
	    	    	    
	    <link rel="stylesheet" href="<?php echo base_url(); ?>css/vendor/todc-bootstrap.css">
	    <link rel="stylesheet" href="<?php echo base_url(); ?>css/vendor/font-awesome.css">  
	     
               
        <link rel="stylesheet" href="<?php echo base_url(); ?>css/vendor/alertify.core.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>css/polaris/polaris.css">        
        
        <link rel="stylesheet" href="<?php echo base_url(); ?>css/vendor/defaultTheme.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>css/vendor/select2.css">        
        <link rel="stylesheet" href="<?php echo base_url(); ?>css/main.css">
        
        <link media="print" rel="stylesheet" href="<?php echo base_url(); ?>css/vendor/print.css">
             
        <script src="<?php echo base_url(); ?>js/modernizr-2.6.2.min.js"></script>
        
    </head>
    <body>
        <!--[if lt IE 8]>
            <p class="alert">Estas usando un navegador <strong>desactualizado</strong>. 
            	Porfavor <strong>actualiza</strong> tu navegador; le recomendamos utilizar 
            	una versi&oacute;n reciente de Google Chrome o Firefox. :)</p>
        <![endif]-->
        
        
        <?php
        // El usuario intentó acceder a una session donde no tiene privilegios
        // por lo que se creo esta input oculto con el objetvo quee JS muestre su mensaje
        // la session flashdata se elimina automaticamnete y el input por JS
        if ($this->session->flashdata('noaccess_redirect')): ?>
			<input type="hidden" name="noaccess_redirect" value="<?php echo $this->session->flashdata('noaccess_redirect'); ?>" />
		<?php endif; ?>
           