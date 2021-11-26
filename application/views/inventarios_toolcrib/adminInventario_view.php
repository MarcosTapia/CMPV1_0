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
<body onload="//mensaje()">
<div class="container">
<div class="row">
<div class="col-md-12">
    <?php 
        $correcto = $this->session->flashdata('correcto');
        if ($correcto) { ?>
    <span id="registroCorrecto" style="color:blue;"><?= $correcto ?></span>
    <?php } ?>
    <br>
    <h3 style="background-color:#C13030; color:white;text-align: center">Administraci&oacute;n de Inventario Toolcrib</h3>
    <br>
    <p>
        <a class="btn btn-primary" href="nuevoNumero">Nuevo Número de Parte</a>
        <a class="btn btn-primary" href="<?php echo base_url();?>index.php/inventariotoolcrib_controller/exportarExcel">Exportar a Excel</a>
        <a class="btn btn-primary" href="<?php echo base_url();?>index.php/inventariotoolcrib_controller/enviaTelegram">Enviar Mensaje Telegram</a>
        <a class="btn btn-primary" href="<?php echo base_url();?>index.php/inventariotoolcrib_controller/enviaCorreoGmail">Enviar Email Gmail</a>
        <a class="btn btn-primary" href="<?php echo base_url();?>index.php/inventariotoolcrib_controller/enviaCorreoEmpresa">Enviar Email Empresa</a>
        <!--
        <a class="btn btn-primary" href="<?php echo base_url();?>index.php/inventariotoolcrib_controller/enviaWhatsapp">Enviar WhatsApp</a>
        -->
    </p>
    <div class="table-responsive">     
        <table class="table" cellpadding="0" cellspacing="0" border="0" class="display" id="example">
            <thead>
                <tr>
                    <th>No SAP</th>
                    <th>No Parte</th>
                    <th>Descripción</th>
                    <th>Ubicación</th>
                    <th>Stock</th>
                    <th>Mínimo</th>
                    <th>Máximo</th>
                    <th>Proveedor</th>
                    <th>Maquina</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php
                if($inventarios) {
                    $i=1;
                    foreach($inventarios as $fila) {  ?>
                        <tr id="fila-<?php echo $fila->{'idInventario'} ?>">
                            <td><?php echo $fila->{'sap'} ?></td>
                            <td><?php echo $fila->{'numero_parte'} ?></td>
                            <td><?php echo $fila->{'descripcion'} ?></td>
                            <td><?php echo $fila->{'ubicacion'} ?></td>
                            <td><?php echo $fila->{'stock'} ?></td>
                            <td><?php echo $fila->{'cantidad_minima'} ?></td>
                            <td><?php echo $fila->{'cantidad_maxima'} ?></td>
                            <td><?php echo $fila->{'nombre_empresa'} ?></td>
                            <td><?php echo $fila->{'maquina'} ?></td>
                            <td>
                            <?php if ($fila->{'stock'} < $fila->{'cantidad_minima'}) { ?>
                            <img src="<?php echo base_url(); ?>/images/sistemaicons/warning.ico" alt="Surtir" title="Surtir" /></a>
                            &nbsp;&nbsp;
                            <?php } ?>
                                
                            <a href="actualizarInventario/<?php echo $fila->{'idInventario'} ?>"><img src="<?php echo base_url(); ?>/images/sistemaicons/modificar.ico" alt="Editar" title="Editar" /></a>
                            &nbsp;&nbsp;
                            <a href="eliminarParteInventario/<?php echo $fila->{'idInventario'} ?>" onclick="javascript: return preguntar()"><img src="<?php echo base_url(); ?>/images/sistemaicons/borrar2.ico" alt="Borrar" title="Borrar" /></a>
                            &nbsp;&nbsp;
                            <a href="mostrarMovimientosPorCodigo/<?php echo $fila->{'idInventario'} ?>" ><img src="<?php echo base_url(); ?>/images/sistemaicons/movimientos.ico" alt="Movimientos" title="Movimientos" /></a>
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
                    <th>No SAP</th>
                    <th>idInventario</th>
                    <th>Descripción</th>
                    <th>Ubicación</th>
                    <th>Stock</th>
                    <th>Mínimo</th>
                    <th>Máximo</th>
                    <th>Proveedor</th>
                    <th>Maquina</th>
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
