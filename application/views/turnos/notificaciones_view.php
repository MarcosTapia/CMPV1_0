<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="utf-8">
    <title>Técnicos en Servicio</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    
    <link href='http://fonts.googleapis.com/css?family=Economica' rel='stylesheet' type='text/css'>
    <!-- Bootstrap -->
    <link href="<?php echo base_url();?>css/bootstrap.css" rel="stylesheet">
    
    <!--
    <link href="<?php echo base_url();?>css/bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo base_url();?>css/starter-template.css" rel="stylesheet">
    -->
    <link href="<?php echo base_url();?>css/estilos.css" rel="stylesheet">    

    <style type="text/css" title="currentStyle">
        @import "<?php echo base_url();?>media/css/demo_page.css";
        @import "<?php echo base_url();?>media/css/demo_table.css";
    </style>
    <script type="text/javascript" language="javascript" src="<?php echo base_url();?>media/js/jquery.js"></script>
    <script type="text/javascript" language="javascript" src="<?php echo base_url();?>media/js/jquery.dataTables.js"></script>
    
    <script type="text/javascript" charset="utf-8">
        function mensaje() {
            setTimeout(function(){ location.reload(); }, 90000);
        }
        
        function solicitar(idUsuario){
            var link = '<?php echo base_url()?>/index.php/turnos_controller/solicitarAtencion/' + idUsuario;
            document.getElementById('enlace' + idUsuario).setAttribute('href', link);            
        }
    </script>
                
</head>
<body onload="//mensaje()">
<div class="container">
    <div class="row">
    <div class="col-md-12">
        <div class="col-md-10">
        </div>
        <div class="col-md-2">
            <nav class="navbar navbar-expand-md navbar-dark border border-dark" style="width:120px;align:right;">
              <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExampleDefault" 
              aria-controls="navbarsExampleDefault" aria-expanded="false" aria-label="Toggle navigation" style="display:none;">
                    <span class="navbar-toggler-icon"></span>
              </button>
              <div class="collapse navbar-collapse" id="navbarsExampleDefault" style="position:absolute;z-index: 1;">
                      <div class="demo-content">
                            <div id="notification-header">
                              <div >
                                    <button id="notification-icon" name="button" onclick="myFunction()" class="dropbtn">
                                        <span id="notification-count">
                                        <?php if($count>0) { echo $count; } ?></span>&nbsp;<img src="<?php echo base_url(); ?>/images/sistemaicons/icono.png" /></button>
                                    <div id="notification-latest"></div>
                              </div>          
                            </div>
                      </div>
                      <script>//mensaje();</script>
                      <?php if(isset($message)) { ?> <div class="error"><?php echo $message; ?></div> <?php } ?>
                      <?php if(isset($success)) { ?> <div class="success"><?php echo $success;?></div> <?php } ?>
              </div>
            </nav>    
        </div>
    </div> <!-- /division renglon en 12-->
    </div> <!-- / renglon-->
    
    <div class="row">
    <div class="col-md-12">
        
        <div class="table-responsive">     
            <table class="table" cellpadding="0" cellspacing="0" border="0" class="display" id="example">
                <thead>
                    <tr>
                        <th>Usuario</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if($notificaciones) {
                        $i=1;
                        foreach($notificaciones as $fila) { ?>
                            <tr id="fila-<?php echo $fila->{'id'} ?>">
                                <?php 
                                    //$posicionContenidoUsuario = array_search($fila->{'idUsuario'},$usuariosArray);
                                    //$elemArrayUsu = explode("|", $posicionContenidoUsuario);
                                ?>

                                <td><?php echo $fila->{'idUsuario'}; ?></td>
                                <td>
                                <a href="actualizarTurno/<?php echo $fila->{'id'} ?>"><img src="<?php echo base_url(); ?>/images/sistemaicons/modificar.ico" alt="Editar" title="Editar" /></a>                            
                                &nbsp;&nbsp;
                                <a href="eliminarTurno/<?php echo $fila->{'id'} ?>"  onclick="javascript: return preguntar()"><img src="<?php echo base_url(); ?>/images/sistemaicons/borrar2.ico" alt="Borrar" title="Borrar" /></a>
                                </td>
                            </tr>
                          <?php 
                        }   
                    }
                    ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th>Usuario</th>
                        <th></th>
                    </tr>
                </tfoot>
            </table>
            <?php echo "<script>mensaje();</script>"; ?>
        </div>
        
        
    </div> <!-- /division renglon en 12-->
    </div> <!-- / renglon-->
    
</div> <!-- /container -->
    
    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <!--
    <script src="https://code.jquery.com/jquery-2.1.1.min.js" crossorigin="anonymous"></script>
    <script>window.jQuery || document.write('<script src="js/jquery.min.js"><\/script>')</script>
    <script src="<?php echo base_url();?>js/popper.min.js"></script>
    <script src="<?php echo base_url();?>js/bootstrap.min.js"></script>
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug 
    <script src="<?php echo base_url();?>js/ie10-viewport-bug-workaround.js"></script>
    -->
    <script type="text/javascript">
      function myFunction() {
        $.ajax({
          url: "<?php echo base_url(); ?>/consultas_ajax/notificaciones.php",
          type: "POST",
          processData:false,
          success: function(data){
            $("#notification-count").remove();                  
            $("#notification-latest").show();$("#notification-latest").html(data);
          },
          error: function(){}           
        });
      }
                                 
      $(document).ready(function() {
            $('#example').dataTable( {
                    "sPaginationType": "full_numbers"
            } );
        $('body').click(function(e){
          if ( e.target.id != 'notification-icon'){
            $("#notification-latest").hide();
          }
        });
      });                                     
    </script>
</body>	
</html>
