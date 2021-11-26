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
    <?php $respuestasTecnico = 0; ?>
    <audio id="mySound" src="<?php echo base_url(); ?>respuesta_tecnico.wav"></audio>
    <div class="table-responsive">   
        <br>
        <table class="table" cellpadding="0" cellspacing="0" border="0" class="display" id="example">
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Id lider</th>
                    <th>Msg del Lider/Sup</th>
                    <th>Msg del Técnico</th>
                    <th>Inicio Notif</th>
                    <th>Resp Técnico</th>
                    <th>Inicio Atenc</th>
                    <th>Fin Atenc</th>
                    <th>Duración</th>
                    <th>Técnico</th>
                    <th>Proyecto</th>
                    <th>Estatus</th>
                    <th>Reanudar</th>
                    
                    <?php if ($permisos == "Supervisor") { ?>
                    <th>Acciones</th>
                    <?php } ?> 
                </tr>
            </thead>
            <tbody>
                <?php
                if($notificaciones) {
                    $i=1;
                    foreach($notificaciones as $fila) { ?>
                        <tr id="fila-<?php echo $fila->{'id'} ?>">
                            <td><?php echo $fila->{'id'} ?></td>
                            <td><?php echo $fila->{'idUsuarioOperador'} ?></td>
                            <td><?php echo $fila->{'mensajeOperador'} ?></td>
                            <td><?php echo $fila->{'mensajeTecnico'} ?></td>
                            <td><?php echo $fila->{'fechaEnvioNotificacionAlTecnico'} ?></td>
                            <td><?php echo $fila->{'fechaEnvioNotificacionAlOperador'} ?></td>
                            <td><?php echo $fila->{'fechaInicioAtencion'} ?></td>
                            <td><?php echo $fila->{'fechaFinAtencion'} ?></td> <!--idUsuarioTecnico -->
                            <td><?php echo $fila->{'tiempoAtencion'} ?></td>
                            
                            <?php 
                                $posicionContenidoUsuario = array_search($fila->{'idUsuarioTecnico'},$usuariosArray);
                                $elemArrayUsu = explode("|", $posicionContenidoUsuario);
                            ?>
                            <td><?php echo $elemArrayUsu[0]; ?></td>                            
                            
                            <?php 
                                foreach ($proyectos as $proyecto){
                                    if ($fila->{'idProyecto'} == $proyecto->{'idProyecto'}) {
                                        echo "<td>".$proyecto->{'descripcion_proyecto'}."</td>";
                                    }
                                }              
                             ?>                            
                            
                            <td>
                            <?php 
                                if ($fila->{'estado'} < 3) { 
                                    if ($fila->{'estado'} == 0) { 
                                
                                        if ($fila->{'idUsuarioTecnico'} == 1) { ?> 
                                            <img src="<?php echo base_url(); ?>/images/sistemaicons/en_cola.png" alt="En cola" title="En cola" />
                                        <?php } else { ?>
                                            <img src="<?php echo base_url(); ?>/images/sistemaicons/vocear.png" alt="Vocear" title="Vocear" />
                                        <?php } ?>
                                
                                    <?php } else { $respuestasTecnico++; ?>
                                    <img src="<?php echo base_url(); ?>/images/sistemaicons/begin2.png" alt="Inicio" title="Inicio" />
                                    <?php } ?>
                            <?php } else {
                                    if ($fila->{'estado'} == 3) { ?>
                                    <img src="<?php echo base_url(); ?>/images/sistemaicons/inprocess2.png" alt="En proceso" title="En proceso" />
                            <?php   }
                                    if ($fila->{'estado'} == 4) { ?>
                                    <img src="<?php echo base_url(); ?>/images/sistemaicons/terminate2.png" alt="Terminada" title="Terminada" />
                            <?php   }
                                }
                            ?>
                            </td>
                            
                            
                            <!-- La parte de reactivar -->
                            <td>
                            <?php
                                if ($fila->{'estado'} == 2) { ?>
                                    <img src="<?php echo base_url(); ?>/images/sistemaicons/reanudar2.png" alt="Reanudar" title="Reanudar" onclick="reanudar('<?php echo $fila->{'id'} ?>',1)"/>
                             <?php  } ?>
                            
                            <?php
                                if ($fila->{'estado'} == 3) { ?>
                                    <img src="<?php echo base_url(); ?>/images/sistemaicons/reanudar2.png" alt="Reanudar" title="Reanudar" onclick="reanudar('<?php echo $fila->{'id'} ?>',2)"/>
                             <?php  } ?>

                            </td>
                            
                            
                            <!-- Acciones del supervisor -->
                            <?php if ($permisos == "Supervisor") { ?>
                            <td>
                            <?php if ($fila->{'estado'} < 4) { 
                                if (($fila->{'estado'} < 3) && ($fila->{'tiempoAtencionTemp'} == '00:00:00')) { ?>
                                    <img src="<?php echo base_url(); ?>/images/sistemaicons/borrar_noti.png" alt="Borrar Notificación" title="Borrar Notificación" onclick="borrarNotificacion('<?php echo $fila->{'id'} ?>','<?php echo $fila->{'idUsuarioTecnico'} ?>','<?php echo $fila->{'idMaqui'} ?>')"/>
                            <?php   
                                } else { 
                                    if ($fila->{'pausado'} == 0) { ?> <!-- Si solo acepta pausar -->
                                    <img src="<?php echo base_url(); ?>/images/sistemaicons/pausar_noti.png" alt="Pausar Notificación" title="Pausar Notificación" onclick="pausarNotificacion('<?php echo $fila->{'id'} ?>','<?php echo $fila->{'idUsuarioTecnico'} ?>','<?php echo $fila->{'idMaqui'} ?>')"/>
                                <?php } }
                                 } ?> <!-- Por si ya esta cerrada -->                                    
                            </td>
                            <?php } ?> 
                            
                        </tr>
                      <?php 
                  
                      $i++;  
                    }   
                }
                ?>
            </tbody>
            <tfoot>
                <tr>
                    <th>Id</th>
                    <th>Id lider</th>
                    <th>Msg del Lider/Sup</th>
                    <th>Msg del Técnico</th>
                    <th>Inicio Notif</th>
                    <th>Resp Técnico</th>
                    <th>Inicio Atenc</th>
                    <th>Fin Atenc</th>
                    <th>Duración</th>
                    <th>Técnico</th>
                    <th>Proyecto</th>
                    <th>Estatus</th>
                    <th>Reanudar</th>
                    
                    <?php if ($permisos == "Supervisor") { ?>
                    <th>Acciones</th>
                    <?php } ?> 
                </tr>
            </tfoot>
        </table>
        <?php if ($respuestasTecnico > 0) { 
            $ruta = base_url()."/respuesta_tecnico.wav";
            echo "<audio src='".$ruta."' autoplay></audio>";
        } ?>
    </div>
</div> <!-- /division renglon en 12-->
</div> <!-- / renglon-->
</div> <!-- /container -->

<script>
    function listenResp() {
        document.getElementById('mySound').play();
        alert("fsdf");
    }
</script>
<script>setTimeout(function(){ window.location.reload(); }, 13000); </script>

</body>	
</html>
