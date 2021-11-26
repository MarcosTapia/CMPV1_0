<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="utf-8">
    <title><?php if ($nombre_Empresa != "") { echo $nombre_Empresa; }?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <link href='http://fonts.googleapis.com/css?family=Economica' rel='stylesheet' type='text/css'>
    <!-- Bootstrap -->
    <link href="<?php echo base_url();?>css/bootstrap.css" rel="stylesheet">
  
    <style type="text/css" title="currentStyle">
            @import "<?php echo base_url();?>media/css/demo_page.css";
            @import "<?php echo base_url();?>media/css/demo_table.css";
    </style>
    <style>
        button {
            border:3px solid brown;
            border-radius:22px;
            width:300px;            
        }  
    </style>
    <script type="text/javascript" language="javascript" src="<?php echo base_url();?>media/js/jquery.js"></script>
    <script type="text/javascript" language="javascript" src="<?php echo base_url();?>media/js/jquery.dataTables.js"></script>
    <script type="text/javascript" charset="utf-8">
        $(document).ready(function() {
                $('#example').dataTable( {
                    "aLengthMenu": [ [10, 25, 50, 100, 500, 1000, -1], [10, 25, 50,  100, 500, 1000, "All"] ],
                    "sPaginationType": "full_numbers",
                    'aaSorting': [[ 9, 'asc' ]]
                } );
        } );

        function pausarNotificacion(idNotificacion,idUsuarioTecnico,idMaquina) {
            var conf = confirm("¿Seguro que quieres pausar?");
            if (conf == false) {
                return false;
            } else {
                jQuery.ajax({
                    url: "<?php echo base_url(); ?>/consultas_ajax/cambiar_notificacion.php?idNotificacion=" + idNotificacion + "&idUsuario=" + idUsuarioTecnico + "&idMaquina=" + idMaquina + "&tipoOperacion=2",
                    cache: false,
                    contentType: "text/html; charset=UTF-8",
                    success: function(response){
                        alert("Notificación pausada.");
                    },
                    error: function(response){
                        alert("Error al pausar la notificación.");
                    }
                });	                
                location.reload();                        
            }
        }
        
        function borrarNotificacion(idNotificacion,idUsuarioTecnico,idMaquina) {
            var conf = confirm("¿Seguro que quieres eliminar?");
            if (conf == false) {
                return false;
            } else {
                jQuery.ajax({
                    url: "<?php echo base_url(); ?>/consultas_ajax/cambiar_notificacion.php?idNotificacion=" + idNotificacion + "&idUsuario=" + idUsuarioTecnico + "&idMaquina=" + idMaquina + "&tipoOperacion=1",
                    cache: false,
                    contentType: "text/html; charset=UTF-8",
                    success: function(response){
                        alert("Notificación eliminada corretamente.");
                    },
                    error: function(response){
                        alert("Error al eliminar.");
                    }
                });	                
                location.reload();                        
            }
        }
        
        
        function reanudar(idNotificacion,etapa) {
            jQuery.ajax({
                url: "<?php echo base_url(); ?>/consultas_ajax/reanudar_solicitud.php?idNotificacion=" + idNotificacion,
                cache: false,
                contentType: "text/html; charset=UTF-8",
                success: function(response){
                    var ruta = "http://192.168.98.200/cmpv1_0//index.php/turnos_controller/reanudarSolicitud/" + idNotificacion + "/" + etapa;
                    window.location.replace(ruta);
                },
                error: function(response){
                    alert("Error");
                }
            });	                
        }    
        
    </script>
</head>
<body>
<div class="container">
<div class="row">
<div class="col-md-12">
    <div class="table-responsive">   
        <br>
        <h3 style="background-color:#C13030; color:white;text-align: center">Salidas a Comedor</h3>
        <br>
        <table class="table" cellpadding="0" cellspacing="0" border="0" class="display" id="example">
            <thead>
                <tr>
                    <th>Movimiento</th>
                    <th>Fecha</th>
                    <th>Técnico</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if($salidas) {
                    $i=1;
                    foreach($salidas as $fila) { ?>
                        <tr id="fila-<?php echo $fila->{'idMovimiento'} ?>">
                            <td><?php echo $fila->{'evento'} ?></td>
                            <td><?php echo $fila->{'fecha'} ?></td>
                            <?php 
                                $posicionContenidoUsuario = array_search($fila->{'idUsuarioTecnico'},$usuariosArray);
                                $elemArrayUsu = explode("|", $posicionContenidoUsuario);
                            ?>
                            <td><?php echo $elemArrayUsu[0]; ?></td>                            
                        </tr>
                      <?php                   
                      $i++;  
                    }   
                }
                ?>
            </tbody>
            <tfoot>
                <tr>
                    <th>Movimiento</th>
                    <th>Fecha</th>
                    <th>Técnico</th>
                </tr>
            </tfoot>
        </table>
    </div>
</div> <!-- /division renglon en 12-->
</div> <!-- / renglon-->
</div> <!-- /container -->

</body>	
</html>
