<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="../../favicon.ico">
    <title> <?php if ($nombre_Empresa != "") { echo $nombre_Empresa; }?> </title>
    <link href="<?php echo base_url(); ?>/css/bootstrap.css" rel="stylesheet">
    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="<?php echo base_url(); ?>/js/bootstrap.min.js"></script>
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="<?php echo base_url(); ?>/js/ie10-viewport-bug-workaround.js"></script> 
    
    <!-- Para Slider -->
    <link rel="stylesheet" href="<?php echo base_url(); ?>/styles/media-queries.css" />
    <link rel="stylesheet" href="<?php echo base_url(); ?>/flex-slider/flexslider.css" type="text/css" media="screen" />
    <script type="text/javascript" src="<?php echo base_url(); ?>/scripts/jquery-1.7.1.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>/flex-slider/jquery.flexslider-min.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>/scripts/setup.js"></script> 
    
    <link href="<?php echo base_url(); ?>/images/favicon.ico" rel="icon" type="image/x-icon" /> 

     <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
     <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
     <script src="<?php echo base_url(); ?>/js/navbar.js"></script>   

     <style>
        a {
          font-size:16px;
        }     
        li {
          display: block;
          transition-duration: 0.5s;
        }
        li:hover {
          cursor: pointer;
          /*background-color:red;*/
        }
        ul li ul {
          visibility: hidden;
          opacity: 0;
          position: absolute;
          transition: all 0.5s ease;
          margin-top: 1rem;
          left: 0;
          display: none;
        }
        ul li:hover > ul,
        ul li ul:hover {
          visibility: visible;
          opacity: 1;
          display: block;
          /*background-color:red;*/
        }
        ul li ul li {
          clear: both;
          width: 100%;
        }
     </style>    
     
  </head>
  <body onload="menuElegido()">
<div class="container">
    <div class="row-fluid">
        <div class="col-md-12">
            <br>
            <nav class="navbar navbar-default">
              <div class="container-fluid">
                <div class="navbar-header">
                  <p class="navbar-brand"><?php if ($nombre_Empresa != "") { echo $nombre_Empresa; } ?> </p><br>
                </div>
                <div><br></div>  
                  <ul class="nav navbar-nav">
                    <?php if ($opcionClickeada == 'bot') { ?>
                          <li><a id="bot" style="background-color:#C13030; color:white;" href="<?php echo base_url(); ?>index.php/inventariotoolcrib_controller/mostrarBot">Bot Toolcrib</a></li>
                    <?php } else { ?>
                          <li><a id="bot" href="<?php echo base_url(); ?>index.php/inventariotoolcrib_controller/mostrarBot+
						  ">Bot Toolcrib</a></li>
                    <?php } ?>    
                          
                    <li><a id="salir" href="<?php echo base_url(); ?>index.php/usuarios_controller/cerrarSesion">Salir</a></li>
                    
                  </ul>
            </nav>  
        </div>
    </div>
    <div class="row-fluid">
        <div class="col-md-6">
            <p class="h4 text-primary font-weight-bold">Bienvenido: <?php echo $usuarioDatos; ?></p>
        </div>
        <div class="col-md-6">
            <div class="col-md-6">
            </div>
            <div class="col-md-6">
                <p class="h4 text-primary font-weight-bold">Fecha: <?php echo $fecha; ?> </p>
            </div>
        </div>
    </div>
            
