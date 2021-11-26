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
    <h3 style="background-color:#C13030; color:white;text-align: center">Administraci&oacute;n de Fallas</h3>
    <br>
    <p>
        <a class="btn btn-primary" href="nuevoFalla">Nueva Falla</a>
    </p>
    <div class="table-responsive">     
        <table class="table" cellpadding="0" cellspacing="0" border="0" class="display" id="example">
            <thead>
                <tr>
                    <th>Descripci&oacute;n</th>
                    <th>Máquina</th>
                    <th>Usuario</th>
                    <th>Fecha</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php
                if($fallas) {
                    $i=1;
                    foreach($fallas as $fila) { ?>
                        <tr id="fila-<?php echo $fila->{'idFalla'} ?>">
                            <td><?php echo $fila->{'descripcionFalla'} ?></td>
                            <td><?php echo $fila->{'nombre_maquina'} ?></td>
                            <td><?php echo $fila->{'apellido_paterno'}." ".$fila->{'apellido_materno'}." ".$fila->{'nombre'} ?></td>
                            <td><?php echo $fila->{'fecha'} ?></td>
                            <td>
                            <a href="actualizarFalla/<?php echo $fila->{'idFalla'} ?>"><img src="<?php echo base_url(); ?>/images/sistemaicons/mostrar.ico" alt="Ver detalle de falla" title="Ver detalle de falla" /></a>                            
                            &nbsp;&nbsp;
                            <a href="<?php echo base_url();?>index.php/soluciones_controller/mostrarSolucionesPorIdFalla/<?php echo $fila->{'idFalla'} ?>"><img src="<?php echo base_url(); ?>/images/sistemaicons/lista.ico" alt="Ver soluciónes" title="Ver soluciónes" /></a>                            
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
                    <th>Descripci&oacute;n</th>
                    <th>Máquina</th>
                    <th>Usuario</th>
                    <th>Fecha</th>
                    <th></th>
                </tr>
            </tfoot>
        </table>
        <script>mensaje()</script>
    </div>
</div> <!-- /division renglon en 12-->
</div> <!-- / renglon-->
</div> <!-- /container -->
<!--
    <script type="text/javascript" language="javascript" src="<?php echo base_url();?>media/js/jquery.js"></script>
-->
    <script type="text/javascript" language="javascript" src="<?php echo base_url();?>media/js/jquery.dataTables.js"></script>
    <script type="text/javascript" charset="utf-8">
        $(document).ready(function() {
                $('#example').dataTable( {
                        "sPaginationType": "full_numbers"
                } );
        } );

        function preguntar() {
            var conf = confirm("¿Seguro que quieres eliminar?. Se eliminaran también las soluciones relacionadas y sus arhivos adjuntos.");
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

</body>	
</html>
