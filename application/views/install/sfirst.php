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
                <p>Bienvenido a Destajo. Antes de comenzar, necesitamos informaci&oacute;n para conectarnos a su base de datos. Necesita conocer estos datos antes de comenzar.</p>

                <p>
                    <ol>
                        <li>Nombre de la base de datos</li>
                        <li>Usuario de la base de datos</li>
                        <li>Password de la base de datos</li>
                        <li>Servidor de la base de datos</li>
                    </ol>
                </p>

                <p>
                    <strong>Si por alguna raz&oacute;n no se crea automaticamente este fichero de configuraci&oacute;n, no se preocupe. Todo lo que har&aacute; es llenar los datos de conexi&oacute;n con la base de datos. Usted puede simplemente abrir el fichero: "application/config/database.php" con un editor de texto, rellenarlo con su informaci&oacute;n y salvarlo.</strong>
                </p>

                <p>
                    <?php echo anchor('install/ssecond', 'Comencemos', array('class'=>'btn btn-default')); ?>
                </p>
            </div>
        </div>

        
        <script src="<?php echo base_url(); ?>js/vendor/jquery-1.9.1.min.js"></script>
    </body>
</html>
