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
    <h3 style="background-color:#C13030; color:white;text-align: center">Consulta de Movimientos del numero de parte: <?php echo $inventario->{'descripcion'}." ".$inventario->{'numero_parte'}; ?></h3>
    <br>
    <a href="<?php echo base_url();?>index.php/inventariotoolcrib_controller/mostrarInventario" >Regresar a inventario general</a>
    <br>
    <br>
    <div class="table-responsive">     
        <table class="table" cellpadding="0" cellspacing="0" border="0" class="display" id="example">
            <thead>
                <tr>
                    <th>Usuario</th>
                    <th>Fecha</th>
                    <th>Cantidad</th>
                    <th>Maquina</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if($movimientos) {
                    $i=1;
                    foreach($movimientos as $fila) {  ?>
                        <tr id="fila-<?php echo $fila->{'idMov'} ?>">
                            <?php 
                                $posicionContenidoUsuario = array_search($fila->{'idUsuario'},$usuariosArray);
                                $elemArrayUsu = explode("|", $posicionContenidoUsuario);
                                
                                $posicionContenidoMaquina = array_search($fila->{'idMaquina'},$maquinasArray);
                                $elemArrayMaq = explode("|", $posicionContenidoMaquina);
                            ?>
                            
                            
                            <td><?php echo $elemArrayUsu[0]; ?></td>
                            <td><?php echo $fila->{'fecha'} ?></td>
                            <td><?php echo $fila->{'cantidad'} ?></td>
                            <td><?php echo $elemArrayMaq[0]." ".$elemArrayMaq[1]; ?></td>
                        </tr>
                      <?php 
                      $i++;  
                    }   
                }
                ?>
            </tbody>
            <tfoot>
                <tr>
                    <th>Usuario</th>
                    <th>Fecha</th>
                    <th>Cantidad</th>
                    <th>Maquina</th>
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
