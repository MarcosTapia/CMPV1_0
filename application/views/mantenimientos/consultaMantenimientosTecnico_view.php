<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title><?php if ($nombre_Empresa != "") { echo $nombre_Empresa; }?></title>
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="distributor" content="Global" />
    <meta itemprop="contentRating" content="General" />
    <meta name="robots" content="All" />
    <link href='http://fonts.googleapis.com/css?family=Economica' rel='stylesheet' type='text/css'>
    <!-- Bootstrap -->
    <link href="<?php echo base_url();?>css/bootstrap.css" rel="stylesheet">
  
    <style type="text/css" title="currentStyle">
            @import "<?php echo base_url();?>media/css/demo_page.css";
            @import "<?php echo base_url();?>media/css/demo_table.css";
    </style>
    <!--
    <script type="text/javascript" language="javascript" src="<?php echo base_url();?>media/js/jquery.js"></script>
    -->
    <script type="text/javascript" language="javascript" src="<?php echo base_url();?>media/js/jquery.dataTables.js"></script>
    
    <link rel="stylesheet" href="//apps.bdimg.com/libs/jqueryui/1.10.4/css/jquery-ui.min.css">
    <script src="//apps.bdimg.com/libs/jqueryui/1.10.4/jquery-ui.min.js"></script>
    <link rel="stylesheet" href="jqueryui/style.css">
    
    <script type="text/javascript" charset="utf-8">
        $(function() {
          $( "#datepicker" ).datepicker({
              showWeek: true,
          });
        });        
    
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
        
        function verificaFechas(){
            if ((document.getElementById('semana1').value == "") &&
                (document.getElementById('semana2').value == "")) {
                alert("Debes seleccionar al menos una fecha.");
                return false;
            }            
            
            if (document.getElementById('semana2').value != "") {
                if (document.getElementById('semana1').value == "") {
                    alert("Si es solo una fecha selecciona la fecha 1");
                    return false;
                }                
            }
            if (document.getElementById('semana1').value != "") {
                if (document.getElementById('semana2').value != "") {
                    var fecha1 = parseInt(document.getElementById('semana1').value); 
                    var fecha2 = parseInt(document.getElementById('semana2').value);
                    if (fecha1 > fecha2) {
                        alert("La fecha 1 debe ser menor o igual a la fecha 2");
                        return false;
                    }
                }                
            }
            return true;
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
    
    <div>
        <div class="table-responsive">  
            <table class="table">
                <tr>
                    <td>
                        <h3 class="h3 text-info font-weight-bold">Mantenimientos de la Semana: <?php $weeks = explode("|",$week); if ($weeks[1]!="") { echo $weeks[0]." a ".$weeks[1]; } else { echo $weeks[0]; }?></h3>
                    </td>
                    <td>
                        <br>
                        <form onsubmit="javascript: return verificaFechas()" class="form-inline" action="<?php echo base_url();?>index.php/mantenimiento_controller/mostrarMantenimientosSemanaUsuario4" method="post">
                          <div class="form-group mx-sm-4 mb-3">
                            <select class="form-control" name="semana1" id="semana1">
                                <option value="">De...</option>
                                <?php
                                    for($i=1;$i<54;$i++) {
                                        echo "<option value=".$i.">Semana ".$i."</option>";
                                    }    
                                ?>
                            </select>                              
                          </div>
                          <div class="form-group mx-sm-4 mb-3">
                            <select class="form-control" name="semana2" id="semana2">
                                <option value="">A...</option>
                                <?php
                                    for($i=1;$i<54;$i++) {
                                        echo "<option value=".$i.">Semana ".$i."</option>";
                                    }    
                                ?>
                            </select>                              
                          </div>
                          <input type="submit" class="btn btn-primary" name="submit" value="Buscar" />
                        </form>                        
                    </td>
                </tr>
            </table>
        </div>
    </div>
    
    <div class="table-responsive">   
        <br>
        <table class="table" cellpadding="0" cellspacing="0" border="0" class="display" id="example">
            <thead>
                <tr>
                    <th>Fecha</th>
                    <th>M&aacute;quina</th>
                    <th>Num M&aacute;quina</th>
                    <th>Actividad</th>
                    <th>Status</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php
                if(isset($mantenimientos)) {
                    $i=1;
                    foreach($mantenimientos as $fila) {?>
                        <tr id="fila-<?php echo $fila->{'idFechaMantenimiento'} ?>">
                            <td><?php echo $fila->{'fechaMantenimiento'} ?></td>
                            <?php 
                                $posicionContenidoMaquina = array_search($fila->{'idMaquina'},$maquinasArray);
                                $elemArrayMaq = explode("|", $posicionContenidoMaquina);
                            ?>
                            
                            <td><?php echo $elemArrayMaq[0]; ?></td>
                            <td><?php echo $elemArrayMaq[1]; ?></td>
                            
                            <?php 
                                $posicionContenidoActividad = array_search($fila->{'idActividad'},$actividadesArray);
                                $elemArrayAct = explode("|", $posicionContenidoActividad);
                            ?>
                            
                            <td><?php echo $elemArrayAct[0]; ?></td>
                            <td><?php
                                if ($fila->{'condicion_maquina'} == "NO ATENDIDA"){
                                    echo "<img src='".base_url()."/images/sistemaicons/nook.ico' alt='Pendiente' title='Pendiente' />";
                                } else {
                                    echo "<img src='".base_url()."/images/sistemaicons/ok.ico' alt='Ok' title='Ok' />";
                                } 
                                ?>
                            <td>
                                <?php if ($fila->{'condicion_maquina'} == "NO ATENDIDA") { ?>
                                <!-- Bloqueada para no permitir realizar mantenimientos anteriores
                                <a href="actualizarMantenimientoTecnico4FromBtn/<?php echo $fila->{'idFechaMantenimiento'}."/".trim(substr($fila->{'fechaMantenimiento'},7,9)); ?>" class="btn btn-primary" />Atender</a>
                                &nbsp;&nbsp;&nbsp;&nbsp;
                                -->
                                <?php } ?>
                                <!-- Bloqueada para no permitir realizar mantenimientos anteriores
                                <a href="actualizarMantenimientoTecnico4/<?php echo $fila->{'idFechaMantenimiento'}."/".$fila->{'idMaquina'}; ?>"><img src="<?php echo base_url(); ?>/images/sistemaicons/modificar.ico" alt="Editar" title="Editar" /></a>                            
                                -->
                            </td>
                        </tr>
                      <?php                   
                      $i++;  
                    }   
                } 
                echo "<script>mensaje();</script>";
                ?>
            </tbody>
            <tfoot>
                <tr>
                    <th>Fecha</th>
                    <th>M&aacute;quina</th>
                    <th>Num M&aacute;quina</th>
                    <th>Actividad</th>
                    <th>Status</th>
                    <th></th>
                </tr>
            </tfoot>
        </table>
    </div>
</div> <!-- /division renglon en 12-->
</div> <!-- / renglon-->
</div> <!-- /container -->
</body>	
</html>
