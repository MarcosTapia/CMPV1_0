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
     
    <script>
        function menuElegido() {
            var opcionClickeadaJS = '<?php echo $opcionClickeada; ?>';
            if (opcionClickeadaJS == 'Actividades') {  
                link = document.getElementById('inicio');
                link.style.pointerEvents = 'none';
                link.style.color = '#bbb';
                
                link = document.getElementById('catalogos');
                link.style.pointerEvents = 'none';
                link.style.color = '#bbb';

                    link = document.getElementById('catalogos1');
                    link.style.pointerEvents = 'none';
                    link.style.color = '#bbb';

                    link = document.getElementById('catalogos2');
                    link.style.pointerEvents = 'none';
                    link.style.color = '#bbb';

                    link = document.getElementById('catalogos3');
                    link.style.pointerEvents = 'none';
                    link.style.color = '#bbb';

                    link = document.getElementById('catalogos4');
                    link.style.pointerEvents = 'none';
                    link.style.color = '#bbb';

                    link = document.getElementById('catalogos5');
                    link.style.pointerEvents = 'none';
                    link.style.color = '#bbb';

                    link = document.getElementById('catalogos6');
                    link.style.pointerEvents = 'none';
                    link.style.color = '#bbb';

                link = document.getElementById('turnosP');
                link.style.pointerEvents = 'none';
                link.style.color = '#bbb';

                    link = document.getElementById('turnosAdmon');
                    link.style.pointerEvents = 'none';
                    link.style.color = '#bbb';
                    
                    link = document.getElementById('salidas_comedor');
                    link.style.pointerEvents = 'none';
                    link.style.color = '#bbb';                    

                    link = document.getElementById('turnosRep');
                    link.style.pointerEvents = 'none';
                    link.style.color = '#bbb';

                    link = document.getElementById('turnosGraf');
                    link.style.pointerEvents = 'none';
                    link.style.color = '#bbb';

                    link = document.getElementById('turnosLayout');
                    link.style.pointerEvents = 'none';
                    link.style.color = '#bbb';

                    link = document.getElementById('lugares');
                    link.style.pointerEvents = 'none';
                    link.style.color = '#bbb';

                link = document.getElementById('calendarizarOpt');
                link.style.pointerEvents = 'none';
                link.style.color = '#bbb';

                link = document.getElementById('graficas');
                link.style.pointerEvents = 'none';
                link.style.color = '#bbb';

                link = document.getElementById('salir');
                link.style.pointerEvents = 'none';
                link.style.color = '#bbb';            
                    

                link = document.getElementById('mantenimientosRoot');
                link.style.pointerEvents = 'none';
                link.style.color = '#bbb';

                    link = document.getElementById('mantenimientosLista');
                    link.style.pointerEvents = 'none';
                    link.style.color = '#bbb';

                    //link = document.getElementById('mantenimientos');
                    //link.style.pointerEvents = 'none';
                    //link.style.color = '#bbb';

                    link = document.getElementById('mantenimientosOperaciones');
                    link.style.pointerEvents = 'none';
                    link.style.color = '#bbb';

                    link = document.getElementById('mantenimientosConsultas');
                    link.style.pointerEvents = 'none';
                    link.style.color = '#bbb';
                    
                    link = document.getElementById('historicoMantenimientos');
                    link.style.pointerEvents = 'none';
                    link.style.color = '#bbb';

                link = document.getElementById('toolcrib');
                link.style.pointerEvents = 'none';
                link.style.color = '#bbb';

                    link = document.getElementById('toolcrib1');
                    link.style.pointerEvents = 'none';
                    link.style.color = '#bbb';

                    link = document.getElementById('toolcrib2');
                    link.style.pointerEvents = 'none';
                    link.style.color = '#bbb';

                    link = document.getElementById('toolcrib3');
                    link.style.pointerEvents = 'none';
                    link.style.color = '#bbb';
                    
                    link = document.getElementById('toolcrib4');
                    link.style.pointerEvents = 'none';
                    link.style.color = '#bbb';
                    
                link = document.getElementById('herramentales');
                link.style.pointerEvents = 'none';
                link.style.color = '#bbb';

                    link = document.getElementById('herramentales1');
                    link.style.pointerEvents = 'none';
                    link.style.color = '#bbb';

                    link = document.getElementById('herramentales2');
                    link.style.pointerEvents = 'none';
                    link.style.color = '#bbb';
                    
                link = document.getElementById('fallas');
                link.style.pointerEvents = 'none';
                link.style.color = '#bbb';
            }
        }
            
        function desbloquear() {
            var conf = confirm("¿Realmente quieres desbloquear para salir del módulo  Actividades?");
            if (conf == true) {
                link = document.getElementById('inicio');
                link.style.pointerEvents = null;
                link.style.color = 'black';

                link = document.getElementById('catalogos');
                link.style.pointerEvents = null;
                link.style.color = 'black';
                
                    link = document.getElementById('catalogos1');
                    link.style.pointerEvents = null;
                    link.style.color = 'black';

                    link = document.getElementById('catalogos2');
                    link.style.pointerEvents = null;
                    link.style.color = 'black';
                    
                    link = document.getElementById('catalogos3');
                    link.style.pointerEvents = null;
                    link.style.color = 'black';

                    link = document.getElementById('catalogos4');
                    link.style.pointerEvents = null;
                    link.style.color = 'black';

                    link = document.getElementById('catalogos5');
                    link.style.pointerEvents = null;
                    link.style.color = 'black';

                    link = document.getElementById('catalogos6');
                    link.style.pointerEvents = null;
                    link.style.color = 'black';

                link = document.getElementById('turnosP');
                link.style.pointerEvents = null;
                link.style.color = 'black';
                
                    link = document.getElementById('turnosAdmon');
                    link.style.pointerEvents = null;
                    link.style.color = 'black';
                    
                    link = document.getElementById('salidas_comedor');
                    link.style.pointerEvents = null;
                    link.style.color = 'black';

                    link = document.getElementById('turnosRep');
                    link.style.pointerEvents = null;
                    link.style.color = 'black';

                    link = document.getElementById('turnosGraf');
                    link.style.pointerEvents = null;
                    link.style.color = 'black';

                    link = document.getElementById('turnosLayout');
                    link.style.pointerEvents = null;
                    link.style.color = 'black';

                    link = document.getElementById('lugares');
                    link.style.pointerEvents = null;
                    link.style.color = 'black';

                link = document.getElementById('mantenimientosRoot');
                link.style.pointerEvents = null;
                link.style.color = 'black';

                    link = document.getElementById('mantenimientosLista');
                    link.style.pointerEvents = null;
                    link.style.color = 'black';

                    link = document.getElementById('mantenimientosOperaciones');
                    link.style.pointerEvents = null;
                    link.style.color = 'black';

                    link = document.getElementById('mantenimientosConsultas');
                    link.style.pointerEvents = null;
                    link.style.color = 'black';

                    link = document.getElementById('historicoMantenimientos');
                    link.style.pointerEvents = null;
                    link.style.color = 'black';

                link = document.getElementById('calendarizarOpt');
                link.style.pointerEvents = null;
                link.style.color = 'black';

                link = document.getElementById('graficas');
                link.style.pointerEvents = null;
                link.style.color = 'black';

                link = document.getElementById('salir');
                link.style.pointerEvents = null;
                link.style.color = 'black';
                
                link = document.getElementById('toolcrib');
                link.style.pointerEvents = null;
                link.style.color = 'black';

                    link = document.getElementById('toolcrib1');
                    link.style.pointerEvents = null;
                    link.style.color = 'black';

                    link = document.getElementById('toolcrib2');
                    link.style.pointerEvents = null;
                    link.style.color = 'black';

                    link = document.getElementById('toolcrib3');
                    link.style.pointerEvents = null;
                    link.style.color = 'black';
                    
                    link = document.getElementById('toolcrib4');
                    link.style.pointerEvents = null;
                    link.style.color = 'black';
                    
                link = document.getElementById('herramentales');
                link.style.pointerEvents = null;
                link.style.color = 'black';

                    link = document.getElementById('herramentales1');
                    link.style.pointerEvents = null;
                    link.style.color = 'black';

                    link = document.getElementById('herramentales2');
                    link.style.pointerEvents = null;
                    link.style.color = 'black';

                link = document.getElementById('fallas');
                link.style.pointerEvents = null;
                link.style.color = 'black';
            }                
        }
    </script>
     
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
                    <?php if ($opcionClickeada == 'Inicio') { ?>
                        <li><a id="inicio" style="background-color:#C13030; color:white;" href="<?php echo base_url(); ?>index.php/usuarios_controller/inicio">Inicio</a></li>
                    <?php } else { ?>
                        <li><a id="inicio" href="<?php echo base_url(); ?>index.php/usuarios_controller/inicio">Inicio</a></li>
                    <?php } ?>
  
                    <li class="dropdown">
                      <a id="catalogos" href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="true"> <span class="nav-label">Cat&aacute;logos</span> <span class="caret"></span></a>
                      <ul class="dropdown-menu">
                          <li><a id="catalogos1" href="<?php echo base_url(); ?>index.php/usuarios_controller/mostrarUsuarios">Usuarios</a></li>
                          <li><a id="catalogos2" href="<?php echo base_url(); ?>index.php/maquinaria_controller/mostrarMaquinas">Maquinaria</a></li>
                          <li><a id="catalogos3" href="<?php echo base_url(); ?>index.php/areas_controller/mostrarAreas">&Aacute;reas</a></li>
                          <li><a id="catalogos4" href="<?php echo base_url(); ?>index.php/configuracion_controller/actualizarDatosEmpresa">Configuración</a></li>
                          <li><a id="catalogos5" href="<?php echo base_url(); ?>index.php/categorias_controller/mostrarCategorias">Categorías</a></li>
                          <li><a id="catalogos6" href="<?php echo base_url(); ?>index.php/proyectos_controller/mostrarProyectos">Proyectos</a></li>
                      </ul>
                    </li> 
                    
                    <li class="dropdown">
                      <a id="turnosP" href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="true"> <span class="nav-label">Paros</span> <span class="caret"></span></a>
                      <ul class="dropdown-menu">
                          <li><a id="turnosAdmon"  href="<?php echo base_url(); ?>index.php/turnos_controller/mostrarTurnos">Administración de Técnicos</a></li>              
                          <li><a id="salidas_comedor"  href="<?php echo base_url(); ?>index.php/turnos_controller/mostrarSalidasComedor">Salidas a Comedor</a></li>              
                          <li><a id="lugares"  href="<?php echo base_url(); ?>index.php/layoutmaqstatus_controller/mostrarlugares">Ubicacion de Máquinas</a></li>
                          <li><a id="turnosRep"  href="<?php echo base_url(); ?>index.php/turnos_controller/consultaParos">Consulta Paros</a></li>              
                          <li><a id="turnosGraf"  href="<?php echo base_url(); ?>index.php/turnos_controller/consultaParosGraficas">Gráficas Paros</a></li>              
                          <li><a id="turnosLayout"  href="<?php echo base_url(); ?>index.php/turnos_controller/layoutParos">Layout Planta</a></li>              
                      </ul>
                    </li> 
                    
                    
                    <li class="dropdown">
                      <a id="mantenimientosRoot" href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="true"> <span class="nav-label">Mantenimientos</span> <span class="caret"></span></a>
                      <ul class="dropdown-menu">
                          <li><a id="mantenimientosLista" href="<?php echo base_url(); ?>index.php/mantenimiento_controller/mostrarMantenimientosLista">Mantenimientos Vista Lista</a></li>
                          <!--
                          <li><a id="mantenimientos"  href="<?php echo base_url(); ?>index.php/mantenimiento_controller/mostrarMantenimientos">Mantenimientos Vista Tabla</a></li>              
                          -->
                          <li><a id="mantenimientosConsultas"  href="<?php echo base_url(); ?>index.php/mantenimiento_controller/consultaMantenimientos">Consulta de Mantenimientos</a></li>              
                          <li><a id="mantenimientosOperaciones"  href="<?php echo base_url(); ?>index.php/mantenimiento_controller/operacionesMasivas">Operaciones Masivas</a></li>              
                          <li><a id="historicoMantenimientos"  href="<?php echo base_url(); ?>index.php/mantenimiento_controller/consultaMantenimientosHistorico">Historial de Mantenimientos</a></li>              
                      </ul>
                    </li> 
                    

                    <?php if ($opcionClickeada == 'Actividades') { ?>
                        <li><a id="actividades" style="background-color:#C13030; color:white;" href="<?php echo base_url(); ?>index.php/actividades_controller/mostrarActividades">Actividades</a></li>
                    <?php } else { ?>
                        <li><a id="actividades" href="<?php echo base_url(); ?>index.php/actividades_controller/mostrarActividades">Actividades</a></li>
                    <?php } ?>

                    <?php if ($opcionClickeada == 'Calendarizar') { ?>
                        <li><a id="calendarizarOpt" style="background-color:#C13030; color:white;" href="<?php echo base_url(); ?>index.php/calendario_controller/mostrarCalendario">Calendarizar</a></li>
                    <?php } else { ?>
                        <li><a id="calendarizarOpt" href="<?php echo base_url(); ?>index.php/calendario_controller/mostrarCalendario">Calendarizar</a></li>
                    <?php } ?>

                    <?php if ($opcionClickeada == 'Graficas') { ?>
                        <li><a id="graficas" style="background-color:#C13030; color:white;" href="<?php echo base_url(); ?>index.php/mantenimiento_controller/mostrarGraficas">Gr&aacute;ficas</a></li>
                    <?php } else { ?>
                        <li><a id="graficas" href="<?php echo base_url(); ?>index.php/mantenimiento_controller/mostrarGraficas">Gr&aacute;ficas</a></li>
                    <?php } ?>

                    <li class="dropdown">
                      <a id="toolcrib" href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="true"> <span class="nav-label">ToolCrib</span> <span class="caret"></span></a>
                      <ul class="dropdown-menu" id="submenus2">
                          <li><a id="toolcrib1" href="<?php echo base_url(); ?>index.php/inventariotoolcrib_controller/mostrarInventario">Inventario</a></li>
                          <li><a id="toolcrib2" href="<?php echo base_url(); ?>index.php/proveedores_controller/mostrarProveedores">Proveedores</a></li>
                          <li><a id="toolcrib3" href="<?php echo base_url(); ?>index.php/inventariotoolcrib_controller/mostrarMovimientos">Movimientos</a></li>
                          <li><a id="toolcrib4" href="<?php echo base_url(); ?>index.php/inventariotoolcrib_controller/mostrarAlertas">Surtir</a></li>
                      </ul>
                    </li> 
                    
                    <li class="dropdown">
                      <a id="herramentales" href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="true"> <span class="nav-label">Herramentales</span> <span class="caret"></span></a>
                      <ul class="dropdown-menu" id="submenus2">
                          <li><a id="herramentales1" href="<?php echo base_url(); ?>index.php/herramentales_controller/funcioninicialAdministradorMoldes">Moldes</a></li>
                          <li><a id="herramentales2" href="<?php echo base_url(); ?>index.php/herramentales_controller/funcioninicialAdministradorAplicadores">Aplicadores</a></li>
                      </ul>
                    </li> 
                    
                    <?php if ($opcionClickeada == 'Fallas') { ?>
                        <li><a id="fallas" style="background-color:#C13030; color:white;" href="<?php echo base_url(); ?>index.php/fallas_controller/mostrarFallas">Fallas</a></li>
                    <?php } else { ?>
                        <li><a id="fallas" href="<?php echo base_url(); ?>index.php/fallas_controller/mostrarFallas">Fallas</a></li>
                    <?php } ?>
                        
                    <li><a id="salir" href="<?php echo base_url(); ?>index.php/usuarios_controller/cerrarSesion">Salir</a></li>
                    
                    &nbsp;&nbsp;&nbsp;
                    <li><img src='<?php echo base_url(); ?>/images/sistemaicons/desbloquear.ico' alt='Desbloquear' title='Desbloquear' onclick='desbloquear()' /></li>
                    
                  </ul>
            </nav>  
        </div>
    </div>
    <div class="row-fluid">
        <div class="col-md-6">
            <p style="font-size: 14px;color: #006666; font-weight: bold;">Bienvenido: <?php echo $usuarioDatos; ?></p>
        </div>
        <div class="col-md-6">
            <div class="col-md-6">
            </div>
            <div class="col-md-6">
                <p style="font-size: 14px;color: #006666; font-weight: bold;">Fecha: <?php echo $fecha; ?> </p>
            </div>
        </div>
    </div>
            
