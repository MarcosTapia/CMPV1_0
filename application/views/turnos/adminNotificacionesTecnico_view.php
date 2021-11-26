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
    <!--
    <script type="text/javascript" language="javascript" src="<?php echo base_url();?>media/js/jquery.js"></script>
    -->
    <script type="text/javascript" language="javascript" src="<?php echo base_url();?>media/js/jquery.dataTables.js"></script>
    <script type="text/javascript" charset="utf-8">
        $(document).ready(function() {
                $('#example').dataTable( {
                    "aLengthMenu": [ [10, 25, 50, 100, 500, 1000, -1], [10, 25, 50,  100, 500, 1000, "All"] ],
                    "sPaginationType": "full_numbers",
                    'aaSorting': [[ 9, 'asc' ]]
                } );
        } );

        function mensaje() {
            if (document.getElementById('registroCorrecto').innerHTML != "") {
                setTimeout(function(){ location.reload(); }, 1000);
            }
        }
        
        function salidaComedor(idUsuarioTecnico) {
            jQuery.ajax({
                url: "<?php echo base_url(); ?>/consultas_ajax/salidaComedor.php?idUsuarioTecnico=" + idUsuarioTecnico,
                cache: false,
                contentType: "text/html; charset=UTF-8",
                success: function(response){
                    if (response != 0) {
                        alert("Salida a comedor registrada.");
                    } else {
                        alert("Tienes notificaciones sin terminar.");
                    }
                    location.reload();
                },
                error: function(response){
                    alert("Error");
                }
            });
        }
        
        function regresoComedor(idUsuarioTecnico) {
            jQuery.ajax({
                url: "<?php echo base_url(); ?>/consultas_ajax/regresoComedor.php?idUsuarioTecnico=" + idUsuarioTecnico,
                cache: false,
                contentType: "text/html; charset=UTF-8",
                success: function(response){
                    if (response != 0) {
                        alert("Regreso de comedor registrado.");
                    } else {
                        alert("Error al registrar el regreso de comedor.");
                    }
                    location.reload();
                },
                error: function(response){
                    alert("Error");
                }
            });
        }
        
    </script>
</head>
<body onload="mensaje()">
<div class="container">
<div class="row">
<div class="col-md-12">
    <?php 
        $correcto = $this->session->flashdata('correcto');
        if ($correcto) { ?>
    <span id="registroCorrecto" style="color:blue;"><?= $correcto ?></span>
    <?php } ?>
    
    <?php $respuestasTecnico = 0; ?>
    <audio id="mySound" src="<?php echo base_url(); ?>respuesta_tecnico.wav"></audio>
    <p>
        <a class="btn btn-primary" href="exportarExcelTecnico">Exportar a Excel</a>
        &nbsp;&nbsp;
        <?php if ($turno->{'salida_comedor'} == 0) { ?>
        <a class="btn btn-primary" href="#" onclick="salidaComedor('<?php echo $idUsuario; ?>');">Salida a Comedor</a>
        <?php } else { ?>
        <a class="btn btn-primary" href="#" onclick="regresoComedor('<?php echo $idUsuario; ?>');">Regreso de Comedor</a>
        <?php } ?>
    </p>
    <div class="table-responsive">   
        <br>
        <table class="table" cellpadding="0" cellspacing="0" border="0" class="display" id="example">
            <thead>
                <tr>
                    <th>Msg del Lider/Sup</th>
                    <th>Msg del Técnico</th>
                    <th>Inicio Notif</th>
                    <th>Resp Técnico</th>
                    <th>Inicio Atenc</th>
                    <th>Fin Atenc</th>
                    <th>Duración</th>
                    <th>Lider</th>
                    <th>Observaciones</th>
                    <th>Proyecto</th>                    
                    <th>Estatus</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php
                if($notificaciones) {
                    $i=1;
                    foreach($notificaciones as $fila) { ?>
                        <tr id="fila-<?php echo $fila->{'id'} ?>">
                            <td><?php echo $fila->{'mensajeOperador'} ?></td>
                            <td><?php echo $fila->{'mensajeTecnico'} ?></td>
                            <td><?php echo $fila->{'fechaEnvioNotificacionAlTecnico'} ?></td>
                            <td><?php echo $fila->{'fechaEnvioNotificacionAlOperador'} ?></td>
                            <td><?php echo $fila->{'fechaInicioAtencion'} ?></td>
                            <td><?php echo $fila->{'fechaFinAtencion'} ?></td> <!--idUsuarioTecnico -->
                            <td><?php echo $fila->{'tiempoAtencion'} ?></td>
                            
                            <?php 
                                $posicionContenidoUsuario = array_search($fila->{'idUsuarioOperador'},$usuariosArray);
                                $elemArrayUsu = explode("|", $posicionContenidoUsuario);
                            ?>
                            <td><?php echo $elemArrayUsu[0]; ?></td>                            
                            
                            <td><?php echo $fila->{'observaciones_tecnico'} ?></td>
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
                            <td>
                            <a href="agregarComentario/<?php echo $fila->{'id'} ?>"><img src="<?php echo base_url(); ?>/images/sistemaicons/comentarios2.png" alt="Agregar Observación" title="Agregar Observación" /></a>
                            </td>
                        </tr>
                      <?php 
                  
                      $i++;  
                    }   
                }
                ?>
            </tbody>
            <tfoot>
                <tr>
                    <th>Msg del Lider/Sup</th>
                    <th>Msg del Técnico</th>
                    <th>Inicio Notif</th>
                    <th>Resp Técnico</th>
                    <th>Inicio Atenc</th>
                    <th>Fin Atenc</th>
                    <th>Duración</th>
                    <th>Lider</th>
                    <th>Observaciones</th>
                    <th>Proyecto</th>                    
                    <th>Estatus</th>
                    <th></th>
                </tr>
            </tfoot>
        </table>
        <script>mensaje();</script>
    </div>
</div> <!-- /division renglon en 12-->
</div> <!-- / renglon-->
</div> <!-- /container -->

</body>	
</html>
