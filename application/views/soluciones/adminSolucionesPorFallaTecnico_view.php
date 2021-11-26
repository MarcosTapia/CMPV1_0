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
    <?php if ($falla) { ?>    
    <p style="font-size:17px;"><b>Listado de soluciones para la falla:</b> <ins><?php echo $falla->{'descripcionFalla'} ?></ins>. <b>Máquina: </b><ins><?php echo $falla->{'nombre_maquina'}." ".$falla->{'numero_maquina'} ?></ins></p>
    <?php } ?>    
    <br>
    <p>
        <a class="btn btn-primary" href="<?php echo base_url();?>index.php/soluciones_controller/nuevoSolucion/<?php echo $idFalla; ?>">Nueva Solución</a>
        <a class="btn btn-success" href="<?php echo base_url();?>index.php/fallas_controller/mostrarFallas">Regresar a fallas</a>
    </p>
    <div class="table-responsive">     
        <table class="table" cellpadding="0" cellspacing="0" border="0" class="display" id="example">
            <thead>
                <tr>
                    <th>Descripci&oacute;n</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php
                if($soluciones) {
                    $i=1;
                    foreach($soluciones as $fila) { ?>
                        <tr id="fila-<?php echo $fila->{'idFalla'} ?>">
                            <td><?php echo $fila->{'descripcionSolucion'} ?></td>
                            <td>
                            <a href="<?php echo base_url();?>index.php/soluciones_controller/verSolucion/<?php echo $fila->{'idSolucion'} ?>/<?php echo $fila->{'idFalla'} ?>"><img src="<?php echo base_url(); ?>/images/sistemaicons/mostrar.ico" alt="Ver detalle de solución" title="Ver detalle de solución" /></a>                            
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
                    <th></th>
                </tr>
            </tfoot>
        </table>
        <script>mensaje()</script>
    </div>
</div> <!-- /division renglon en 12-->
</div> <!-- / renglon-->
</div> <!-- /container -->
</body>	
</html>
