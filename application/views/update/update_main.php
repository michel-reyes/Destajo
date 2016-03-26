<!DOCTYPE html>
<html lang="en" class="update-view">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Subir fichero de actualizaciones</title>

    <link href="<?php echo base_url(); ?>css/vendor/bootstrap.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>css/vendor/bootstrap-responsive.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>css/vendor/todc-bootstrap.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>css/main.css" rel="stylesheet">

    <script src="<?php echo base_url(); ?>js/vendor/modernizr-2.6.2.min.js"></script>

  </head>
  <body class="update-view">

    <div class="update-container">
        <div class="update-inner">
            <h4>Actualizaciones</h4>
            <div class="card">
                <p>Seleccione un fichero (.zip)</p>
                <!-- Error track -->
                <?php if(isset($error)) echo '<div class="alert alert-error">' .  $error . '</div>' ?>

                <!-- Form -->
                <?php echo form_open_multipart("update/file_upload"); ?>
                    <div class="fileupload fileupload-new">
                        <div class="input-append">
                            <div class="uneditable-input">
                                <span class="fileupload-preview">Seleccione un fichero</span>
                            </div>
                            <span class="btn btn-file btn-primary">
                                <span class="fileupload-new">Seleccionar fichero</span>
                                <input type="file" name="userfile" />
                            </span>
                        </div>
                    </div>
                    <input type="submit" value="Cargar fichero" class="btn btn-success" />
                <?php echo form_close(); ?>
            </div>
        </div>
    </div>

    <!--<div class="container-fluid">
      <div class="row-fluid">

        <h4>Seleccionar fichero de actualizaciones</h4>

        <div>

            <?php //if(isset($error)) echo $error; ?>

            <?php// echo form_open_multipart("update/file_upload"); ?>

            <input type="file" name="userfile" />

            <br><br>

            <input type="submit" value="Cargar fichero" class="btn btn-primary" />


            <?php //echo form_close(); ?>

        </div>

      </div>
    </div>-->

    <script src="<?php echo base_url(); ?>js/vendor/jquery-1.9.1.min.js"></script>
    <script src="<?php echo base_url(); ?>js/vendor/jquery.cookie.js"></script>
    <script src="<?php echo base_url(); ?>js/vendor/jquery-migrate-1.1.1.min.js"></script>
    <script src="<?php echo base_url(); ?>js/vendor/jquery-ui.js"></script>
    <script src="<?php echo base_url(); ?>js/vendor/alertify.js"></script>

    <script type="text/javascript">
        $(function() {
            // Reset the form. The browser's previous button cause some issues with file cache.
            $('form')[0].reset();
            // The new input:file UI lose control, so... here you know when
            // the file has data or not.
            $('input[type="file"]').on('change', function(event) {
                var data = $(this).val();
                $('.fileupload-preview').html( (data == "") ? "Seleccione un fichero" : data );
            })
        });
    </script>
  </body>
</html>