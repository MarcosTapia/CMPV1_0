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
    <h3 style="background-color:#C13030; color:white;text-align: center">Administraci&oacute;n de Aplicadores</h3>
    <br>
    <p>
        <a class="btn btn-primary" href="nuevoAplicador">Nuevo Aplicador</a>
        <a class="btn btn-primary" href="creaMovimientoInicial">Crear Movimiento de Inicio de Aplicador</a>
        
        <!--
        <a class="btn btn-primary" href="<?php echo base_url();?>index.php/herramentales_controller/enviaTelegram">Enviar Mensaje Telegram</a>
        <a class="btn btn-primary" href="<?php echo base_url();?>index.php/herramentales_controller/enviaCorreo">Enviar Email Gmail</a>
        <a class="btn btn-primary" href="<?php echo base_url();?>index.php/herramentales_controller/enviaWhatsapp">Enviar WhatsApp</a>
        -->
    </p>
    <div class="table-responsive">     
        <table class="table" cellpadding="0" cellspacing="0" border="0" class="display" id="example">
            <thead>
                <tr>
                    <th>Máquina</th>
                    <th>Aplicador</th>
                    <th>Fabricante</th>
                    <th>NoParte Aplic</th>
                    <th>NoParte Term</th>
                    <th>NoParte Term Int</th>
                    <th>Cliente</th>
                    <th>No Ciclos</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php
                if($aplicadores) {
                    $i=1;
                    foreach($aplicadores as $fila) { ?>
                        <tr id="fila-<?php echo $fila->{'idAplicador'} ?>">
                            <td><?php echo $fila->{'noMaquina'} ?></td>
                            <td><?php echo $fila->{'aplicador'} ?></td>
                            <td><?php echo $fila->{'fabricante'} ?></td>
                            <td><?php echo $fila->{'no_parte_aplicador'} ?></td>
                            <td><?php echo $fila->{'no_parte_terminal'} ?></td>
                            <td><?php echo $fila->{'no_parte_terminal_interno'} ?></td>
                            <td><?php echo $fila->{'cliente'} ?></td>
                            <td><?php echo $fila->{'no_ciclos'} ?></td>
                            <td>
                            <a href="movimientosAplicador/<?php echo $fila->{'idAplicador'} ?>"><img src="<?php echo base_url(); ?>/images/sistemaicons/movimientos.ico" alt="Movimientos del Aplicador" title="Movimientos del Aplicador" /></a>
                            <a href="actualizarAplicador/<?php echo $fila->{'idAplicador'} ?>"><img src="<?php echo base_url(); ?>/images/sistemaicons/modificar.ico" alt="Editar" title="Editar" /></a>                            
                            <a href="eliminarAplicador/<?php echo $fila->{'idAplicador'} ?>"  onclick="javascript: return preguntar()"><img src="<?php echo base_url(); ?>/images/sistemaicons/borrar2.ico" alt="Borrar" title="Borrar" /></a>
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
                    <th>Máquina</th>
                    <th>Aplicador</th>
                    <th>Fabricante</th>
                    <th>NoParte Aplic</th>
                    <th>NoParte Term</th>
                    <th>NoParte Term Int</th>
                    <th>Cliente</th>
                    <th>No Ciclos</th>
                    <th></th>
                </tr>
            </tfoot>
        </table>
        <?php echo "<script>mensaje();</script>"; ?>
    </div>
</div> <!-- /division renglon en 12-->
</div> <!-- / renglon-->
</div> <!-- /container -->
</body>	
</html>
