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
    <h3 style="margin-top:-5px; background-color:#C13030; color:white;text-align: center">Consulta de Partes por Surtir</h3>
    <a class="btn btn-primary" href="<?php echo base_url();?>index.php/inventariotoolcrib_controller/exportarExcelSurtir">Exportar a Excel</a>
    <div class="table-responsive">     
        <br>
        <table class="table" cellpadding="0" cellspacing="0" border="0" class="display" id="example">
            <thead>
                <tr>
                    <th>Numero SAP</th>
                    <th>Numero Parte</th>
                    <th>Descripcion</th>
                    <th>Stock</th>
                    <th>Cantidad Mínima</th>
                    <th>Cantidad Máxima</th>
                    <th>Proveedor</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if($alertas) {
                    $i=1;
                    foreach($alertas as $fila) {  ?>
                        <tr id="fila-<?php echo $fila->{'idInventario'} ?>">
                            <td><?php echo $fila->{'sap'}; ?></td>
                            <td><?php echo $fila->{'numero_parte'}; ?></td>
                            <td><?php echo $fila->{'descripcion'} ?></td>
                            <td><?php echo $fila->{'stock'} ?></td>
                            <td><?php echo $fila->{'cantidad_minima'} ?></td>
                            <td><?php echo $fila->{'cantidad_maxima'} ?></td>
                            <td><?php echo $fila->{'nombre_empresa'} ?></td>
                        </tr>
                      <?php 
                      $i++;  
                    }   
                }
                ?>
            </tbody>
            <tfoot>
                <tr>
                    <th>Numero SAP</th>
                    <th>Numero Parte</th>
                    <th>Descripcion</th>
                    <th>Stock</th>
                    <th>Cantidad Mínima</th>
                    <th>Cantidad Máxima</th>
                    <th>Proveedor</th>
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