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
        
        function desbloquearTecnico(idTecnico) {
            var conf = confirm("¿Seguro que quieres desbloquear?");
            if (conf == false) {
                return false;
            } else {
                jQuery.ajax({
                    url: "<?php echo base_url(); ?>/consultas_ajax/desbloquearTecnico.php?idTecnico=" + idTecnico,
                    cache: false,
                    contentType: "text/html; charset=UTF-8",
                    success: function(response){
                        if (response == 1) {
                            alert("Error.- No se puede desbloquear el técnico debido a que tiene notificaciones abiertas en el día o no se encuentra en turno. \nSolicita se elimine la notificación al supervisor o el cambio de horario respectivo con algún administrador del sistema.");
                        }
                        if (response == 0) {
                            alert("Técnico desbloqueado.");
                        }
                        location.reload();
                    },
                    error: function(response){
                        alert("Error");
                    }
                });	            
                return true;
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
    <div class="table-responsive">     
        <table class="table" cellpadding="0" cellspacing="0" border="0" class="display" id="example">
            <thead>
                <tr>
                    <th>Usuario</th>
                    <th>Hora Entrada</th>
                    <th>Hora Salida</th>
                    <th>Area(s)</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php
                if($turnos) {
                    $i=1;
                    foreach($turnos as $fila) { ?>
                        <tr id="fila-<?php echo $fila->{'idTurno'} ?>">
                            <?php 
                                $posicionContenidoUsuario = array_search($fila->{'idUsuario'},$usuariosArray);
                                $elemArrayUsu = explode("|", $posicionContenidoUsuario);
                            ?>
                            
                            <td><?php echo $elemArrayUsu[0]; ?></td>
                            <td><?php echo $fila->{'hora_entrada'}; ?></td>
                            <td><?php echo $fila->{'hora_salida'}; ?></td>
                            <?php 
                                $posicionContenidoArea1 = array_search($fila->{'idArea1'},$areasArray);
                                $elemArrayArea1 = explode("|", $posicionContenidoArea1);
                                $posicionContenidoArea2 = array_search($fila->{'idArea2'},$areasArray);
                                $elemArrayArea2 = explode("|", $posicionContenidoArea2);
                                $posicionContenidoArea3 = array_search($fila->{'idArea3'},$areasArray);
                                $elemArrayArea3 = explode("|", $posicionContenidoArea3);
                                $posicionContenidoArea4 = array_search($fila->{'idArea4'},$areasArray);
                                $elemArrayArea4 = explode("|", $posicionContenidoArea4);
                                $posicionContenidoArea5 = array_search($fila->{'idArea5'},$areasArray);
                                $elemArrayArea5 = explode("|", $posicionContenidoArea5);
                                
                                //si es el area pivote la pongo en blanco
                                $areasStr = "";
                                if ($elemArrayArea1[0] != "General") {
                                    $areasStr .= $elemArrayArea1[0];
                                } 
                                if ($elemArrayArea2[0] != "General") {
                                    $areasStr .= ",".$elemArrayArea2[0];
                                }
                                if ($elemArrayArea3[0] != "General") {
                                    $areasStr .= ",".$elemArrayArea3[0];
                                }
                                if ($elemArrayArea4[0] != "General") {
                                    $areasStr .= ",".$elemArrayArea4[0];
                                }
                                if ($elemArrayArea5[0] != "General") {
                                    $areasStr .= ",".$elemArrayArea5[0];
                                }
                            ?>
                            
                            <td><?php echo $areasStr; ?></td>
                            
                            <td>
                            <?php if ($fila->{'status'} != 0) { ?>
                            <a href="#" onclick="desbloquearTecnico('<?php echo $fila->{'idUsuario'} ?>')"><img src="<?php echo base_url(); ?>/images/sistemaicons/desbloquear.ico" alt="Desbloquear Técnico" title="Desbloquear Técnico" /></a>
                            <?php } ?>
                            </td>
                        </tr>
                      <?php 
                    }   
                }
                ?>
            </tbody>
            <tfoot>
                <tr>
                    <th>Usuario</th>
                    <th>Hora Entrada</th>
                    <th>Hora Salida</th>
                    <th>Area(s)</th>
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
