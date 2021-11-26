<!DOCTYPE html>
<html lang="en">
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
                        "sPaginationType": "full_numbers"
                } );
        } );

        function preguntar() {
            var conf = confirm("¿Seguro que quieres eliminar?");
            if (conf == false) {
                return false;
            } else {
                return true;
            }
        }
        
        function mensaje() {
            if (document.getElementById('registroCorrecto').innerHTML != "") {
                setTimeout(function(){ location.reload(); }, 1000);
            }
        }
        
        /*
        INSERT INTO `notificaciones` (`id`, `mensajeOperador`, `mensajeTecnico`, `estado`, 
        `fechaEnvioNotificacionAlTecnico`, `fechaEnvioNotificacionAlOperador`, `fechaInicioAtencion`, 
        `fechaFinAtencion`, `tiempoAtencion`, `idUsuarioTecnico`, `idUsuarioOperador`, `calificacionAtencion`, 
        `comentarios`, `idFallaNotificacion`, `desc_falla`, `pendiente_lider`, `pausa_cambio_prioridad`, 
        `reanudacion_cambio_prioridad`) VALUES (NULL, 'inicio', 'inicio', '4', current_timestamp(), '2021-10-07 08:34:07', 
        '2021-10-07 08:34:07', '00:00:00', '00:00:00', '1', '1', '10', '-', '', '1', '1', '1', '1');         
        */
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
    <br>
    <h3 style="background-color:#C13030; color:white;text-align: center">Administraci&oacute;n de lugares</h3>
    <br>
    <p>
        <a class="btn btn-primary" href="nuevoLugar">Nueva Ubicación</a>
    </p>
    <div class="table-responsive">     
        <table class="table" cellpadding="0" cellspacing="0" border="0" class="display" id="example">
            <thead>
                <tr>
                    <th>Maquina</th>
                    <th>Control</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php
                if($layoutmaqstatus) {
                    $i=1;
                    foreach($layoutmaqstatus as $fila) { ?>
                        <tr id="fila-<?php echo $fila->{'id'} ?>">
                            <td><?php echo $fila->{'nombre_maquina'}." ".$fila->{'numero_maquina'}; ?></td>
                            <td><?php echo $fila->{'control'} ?></td>
                            <td>
                            <a href="actualizarMaqLugar/<?php echo $fila->{'id'} ?>"><img src="<?php echo base_url(); ?>/images/sistemaicons/modificar.ico" alt="Editar" title="Editar" /></a>                            
                            &nbsp;&nbsp;
                            <a href="eliminarMaqLugar/<?php echo $fila->{'id'} ?>/<?php echo $fila->{'id'} ?>"  onclick="javascript: return preguntar()"><img src="<?php echo base_url(); ?>/images/sistemaicons/borrar2.ico" alt="Borrar" title="Borrar" /></a>
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
                    <th>Maquina</th>
                    <th>Control</th>
                    <th></th>
                </tr>
            </tfoot>
        </table>
    </div>
    <script>mensaje();</script>
</div> <!-- /division renglon en 12-->
</div> <!-- / renglon-->
</div> <!-- /container -->
</body>	
</html>
