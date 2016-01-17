<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Subir fichero de actualizaciones</title>

    
    <link href="<?php echo base_url(); ?>css/vendor/bootstrap.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>css/vendor/bootstrap-responsive.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>css/vendor/todc-bootstrap.css" rel="stylesheet">

    <script src="<?php echo base_url(); ?>js/vendor/modernizr-2.6.2.min.js"></script>

  </head>
  <body>
    
    <div class="container-fluid">
      <div class="row-fluid">

        <h4>Seleccionar fichero de actualizaciones</h4>

        <div>

            <?php if(isset($error)) echo $error; ?>

            <?php echo form_open_multipart("update/file_upload"); ?>

            <input type="file" name="userfile" />

            <br><br>

            <input type="submit" value="Cargar fichero" class="btn btn-primary" />


            <?php echo form_close(); ?>

        </div>

      </div>
    </div>
    
    <script src="<?php echo base_url(); ?>js/vendor/jquery-1.9.1.min.js"></script>
    <script src="<?php echo base_url(); ?>js/vendor/jquery.cookie.js"></script>
    <script src="<?php echo base_url(); ?>js/vendor/jquery-migrate-1.1.1.min.js"></script>                       
    <script src="<?php echo base_url(); ?>js/vendor/jquery-ui.js"></script>
    <script src="<?php echo base_url(); ?>js/vendor/alertify.js"></script>
  </body>
</html>