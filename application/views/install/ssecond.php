<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Destajo</title>
        <meta name="description" content="destajo, sistema de pago, pago, mayorista, destajo individual mayorista, transcupet">
        <meta name="viewport" content="width=device-width">

        <!-- Place favicon.ico and apple-touch-icon.png in the root directory -->
        <link rel="shortcut icon" href="favicon.ico" />
        <script src="<?php echo base_url(); ?>js/vendor/modernizr-2.6.2.min.js"></script>

        <style type="text/css">
        html,
        body {
          height: 100%;
          overflow: hidden;
          color: #404040; 
          font-family: 'Helvetica Neue', arial, sans-serif;
          font-size: 15px;
          background-color: #f1f1f1;
        }
        .container {
            width: 740px;
            margin: 20px auto;
        }
        .logo {
            text-align: center;
        }
        .content {
            padding: 10px 15px;
            background-color: #fff;
            -webkit-box-shadow: rgba(0, 0, 0, 0.14902) 0px 1px 2px 0px;
                    box-shadow: rgba(0, 0, 0, 0.14902) 0px 1px 2px 0px;
            border-radius: 3px;
            line-height: 19.600000381469727px;
            color: rgb(38, 38, 38);
            min-height: 150px;
            margin-top: 5px;
            border-top: 1px solid #eee;
        }
        a.btn,
        .btn {
            text-decoration: none;
            position: relative;
          padding: 6px 12px;
          margin: 0;
          color: #333;
          text-shadow: 0 1px 0 #fff;
          white-space: nowrap;
          font-family: Arial, Helvetica, sans-serif;
          font-weight: bold;
          font-size: 13px;
          text-align: center;
          vertical-align: middle;
          -webkit-background-clip: padding;
          -moz-background-clip: padding;
          background-clip: padding;
          cursor: pointer;
          background-color: #f3f3f3;
          background-image: -moz-linear-gradient(top, #f5f5f5, #f1f1f1);
          background-image: -webkit-gradient(linear, 0 0, 0 100%, from(#f5f5f5), to(#f1f1f1));
          background-image: -webkit-linear-gradient(top, #f5f5f5, #f1f1f1);
          background-image: -o-linear-gradient(top, #f5f5f5, #f1f1f1);
          background-image: linear-gradient(to bottom, #f5f5f5, #f1f1f1);
          background-repeat: repeat-x;
          filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#fff5f5f5', endColorstr='#fff1f1f1', GradientType=0);
          border: 1px solid #dcdcdc;
          -webkit-border-radius: 2px;
          -moz-border-radius: 2px;
          border-radius: 2px;
          -webkit-box-shadow: none;
          -moz-box-shadow: none;
          box-shadow: none;
        }
        input.form-control {
          display: inline-block;
          padding: 4px 6px;
          margin-bottom: 10px;
          margin: 0 7px 10px;
          font-size: 14px;
          line-height: 20px;
          color: #555555;
          vertical-align: middle;
          -webkit-border-radius: 4px;
          -moz-border-radius: 4px;
          border-radius: 4px;
          font-size: 14px;
          font-weight: normal;
          line-height: 20px;
          padding: 4px 8px;
          font-size: 13px;
          -webkit-border-radius: 1px;
          -moz-border-radius: 1px;
          border-radius: 1px;
          border: 1px solid #d9d9d9;
          border-top-color: #c0c0c0;
          -webkit-box-shadow: none;
          -moz-box-shadow: none;
          box-shadow: none;
          -webkit-transition: none;
          -moz-transition: none;
          -o-transition: none;
          transition: none;
          width: 200px;
        }
        label {
          min-width: 140px;
          display: inline-block;
        }
        .resalt {
          color: #FF1D13;
        }
        </style>
        
    </head>
    <body>
        <!--[if lt IE 8]>
            <p class="alert">Estas usando un navegador <strong>desactualizado</strong>. 
                Porfavor <strong>actualiza</strong> tu navegador; le recomendamos utilizar 
                una versi&oacute;n reciente de Google Chrome o Firefox. :)</p>
        <![endif]-->

        <div class="container">
            <div class="logo">
                <img src="<?php echo base_url('css/img/dj.png'); ?>">
            </div>
            <div class="content">

            <?php echo form_open('install/ssecond', '', array('createdb'=>'ok')); ?>
                <p>Debe escribir los datos necesarios para conectarnos a su base de datos. Si no est&aacute; seguro de esto, contacte con su proveedor de software.</p>

                <p>
                  <label><strong>Base de datos</strong></label>
                  <input type="text" class="form-control" name="dbname" value="destajo">
                  <span>Nombre de la bd donde estar&aacute; alojado Destajo</span>
                </p>

                <p>
                  <label><strong>Nombre de usuario</strong></label>
                  <input type="text" class="form-control" name="username" value="root">
                  <span>Usuario de MySQL</span>
                </p>

                <p>
                  <label><strong>Password</strong></label>
                  <input type="password" class="form-control" name="userpassword" value="">
                  <span>... y tu password de MySQL</span>
                </p>

                <p>
                  <label><strong>Servidor</strong></label>
                  <input type="text" class="form-control" name="dbhost" value="localhost">
                  <span>Nombre del servidor donde esta alojado MySQL</span>
                </p>

                <p>
                  <button style="margin-right: 20px;" type="submit" class="btn btn-default">Configurar</button>
                  <?php 
                    if(isset($mysql_connect_error) && $mysql_connect_error === 1) 
                      echo "<span class='resalt'>No se puede conectar con los datos proporcionados.<span>";
                    if(isset($config_error) && $config_error === 1) 
                      echo "<span class='resalt'>No se puedo configurar la conexi&oacute;n. Contacte con el administrador del software.<span>";
                    if(isset($createdb) && $createdb === 1)
                      echo "<span class='resalt'>No se pudo crear la base de datos especificada. Intente con un nombre diferente.<span>";
                  ?>
                </p>
              <?php echo form_close(); ?>
            </div>
        </div>

        
        <script src="<?php echo base_url(); ?>js/vendor/jquery-1.9.1.min.js"></script>
    </body>
</html>
