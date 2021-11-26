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
    
    <link rel="stylesheet" href="//apps.bdimg.com/libs/jqueryui/1.10.4/css/jquery-ui.min.css">
    <script src="//apps.bdimg.com/libs/jqueryui/1.10.4/jquery-ui.min.js"></script>
    <link rel="stylesheet" href="jqueryui/style.css">
    
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

        function verificaFechas(){
            if (document.getElementById('maquinaConsulta').value == "") {
                //si el responsable no existe entonces debe haber fechas
                if (document.getElementById('responsable').value == "") {
                    //verifica que no vengan las fechas vacias
                    if ((document.getElementById('semana1').value == "") &&
                        (document.getElementById('semana2').value == "")) {
                        alert("Debes seleccionar al menos una fecha.");
                        return false;
                    }            
                    
                    //verifica que no venga sola la fecha 2
                    if (document.getElementById('semana2').value != "") {
                        if (document.getElementById('semana1').value == "") {
                            alert("Si es solo una fecha selecciona la fecha 1");
                            return false;
                        }                
                    }
                    //verifica que la fecha 2 sea mayor que la 1
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
                }    
            } else {
                //verifica que no venga sola la fecha 2
                if (document.getElementById('semana2').value != "") {
                    if (document.getElementById('semana1').value == "") {
                        alert("Si es solo una fecha selecciona la fecha 1");
                        return false;
                    }                
                }
                //checa si la fecha 2 es mayor que la 1
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
            }
            return true;
        }
        
        function validaConsultaParaExcel(){
            if (document.getElementById('mantenimientosHiddenCheckDatasetExist').value == "0") {
                alert("Debe existir información previamente cargada.");
                return false;
            } 
            return true;
        }

        function validaConsultaParaPdf(){
            if (document.getElementById('mantenimientosHiddenCheckDatasetExist').value == "0") {
                alert("Debe existir información previamente cargada.");
                return false;
            } 
            return true;
        }

        function desactivaSelectResponsable(){
            document.getElementById("responsable").selectedIndex = 0;
            document.getElementById("responsable").disabled = true;
        }
        
        function desactivaSelectMaquina(){
            document.getElementById("maquinaConsulta").selectedIndex = 0;
            document.getElementById("maquinaConsulta").disabled = true;
        }
        
        function activaSelectResponsable(){
            document.getElementById("responsable").selectedIndex = 0;
            document.getElementById("responsable").disabled = false;
        }

        function activaSelectMaquina(){
            document.getElementById("maquinaConsulta").selectedIndex = 0;
            document.getElementById("maquinaConsulta").disabled = false;
        }
        
        function verificar(idFechaMantenimiento,accion) {
            jQuery.ajax({
                url: "<?php echo base_url(); ?>/treeview_mantenimientos/tree_dataVerificar.php?idFechaMantenimiento=" + idFechaMantenimiento + "&accion=" + accion,
                cache: false,
                success: function(response){            
                    let ruta = document.getElementById('img' + idFechaMantenimiento).getAttribute('src');
                    var splitRuta = ruta.split("/");            
                    if (splitRuta[splitRuta.length - 1] == "ojobuscar.ico") {
                        document.getElementById('img' + idFechaMantenimiento).src = "<?php echo base_url(); ?>/images/sistemaicons/persona.ico";
                    } else {
                        document.getElementById('img' + idFechaMantenimiento).src = "<?php echo base_url(); ?>/images/sistemaicons/ojobuscar.ico";
                    }
                },
                error: function(response){
                    alert("Error");
                    document.getElementById('img' + idFechaMantenimiento).src = "<?php echo base_url(); ?>/images/sistemaicons/persona.ico";
                }
            });	               
        }


    </script>
                
</head>
<body>
<div class="container">
<div class="row">
<div class="col-md-12">
    <?php 
        $correcto = $this->session->flashdata('correcto');
        if ($correcto) { ?>
    <span id="registroCorrecto" style="color:blue;"><?= $correcto ?></span>
    <script>mensaje();</script>;
    <?php } ?>
    <br>
    
    <div>
        <div class="table-responsive">  
            <table class="table">
                <tr>
                    <td>
                        <h5 style="color:#cc0033;font-weight: bold;">Consulta de Mantenimientos: </h5>
                        <form onsubmit="javascript: return verificaFechas()" class="form-inline" action="<?php echo base_url();?>index.php/mantenimiento_controller/consultaMantenimientosOtros" method="post">
                          <div class="form-group mx-sm-4 mb-3">
                              <select onchange="desactivaSelectResponsable();" class="form-control" name="maquinaConsulta" id="maquinaConsulta">
                                <option value="">Máquina...</option>
                                <?php
                                    foreach($maquinasSimple as $fila) {
                                        echo "<option value=".$fila->{'idMaquina'}.">".$fila->{'nombre_maquina'}." - ".$fila->{'numero_maquina'}."</option>";
                                    }    
                                ?>
                            </select>                              
                            <img src='<?php echo base_url(); ?>/images/sistemaicons/desbloquear.ico' alt='Desbloquear' title='Desbloquear' onclick='activaSelectMaquina()' />
                            &nbsp;&nbsp;
                          </div>
                            
                          <div class="form-group mx-sm-4 mb-3">
                            <select onchange="desactivaSelectMaquina();" class="form-control" name="responsable" id="responsable">
                                <option value="">Responsable...</option>
                                <?php
                                    foreach($usuarios as $fila) {
                                        if ($fila->{'permisos'} != "Administrador") {
                                            echo "<option value=".$fila->{'idUsuario'}.">".$fila->{'apellido_paterno'}." ".$fila->{'apellido_materno'}." ".$fila->{'nombre'}."</option>";
                                        }
                                    }    
                                ?>
                            </select>                              
                            <img src='<?php echo base_url(); ?>/images/sistemaicons/desbloquear.ico' alt='Desbloquear' title='Desbloquear' onclick='activaSelectResponsable()' />
                            &nbsp;&nbsp;
                          </div>
                            
                          <div class="form-group mx-sm-4 mb-3">
                            <select class="form-control" name="semana1" id="semana1">
                                <option value="">De...</option>
                                <?php
                                    for($i=1;$i<54;$i++) {
                                        echo "<option value=".$i.">Semana ".$i."</option>";
                                    }    
                                ?>
                            </select>
                            <span style="font-weight:bold;"> - </span>
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
                            &nbsp;
                            <input type="submit" class="btn btn-success" name="submit" value="Buscar" />
                          </div>
                        </form>                        
                    </td>
                </tr>
            </table>
        </div>
    </div>
    
    <div class="table-responsive">   
        <?php 
            $posicionContenidoUsuario = array_search($idResponsable,$usuariosArray);
            $elemArrayUsu = explode("|", $posicionContenidoUsuario);

            $posicionContenidoMaquina = array_search($idMaquina,$maquinasArray);
            $elemArrayMaq = explode("|", $posicionContenidoMaquina);
            $cadena = "";

        ?>

        <?php
        if (trim($week) != "||") { ?>
        <h4 style="color:#330066;text-align: center;">Mantenimientos de la Semana: <?php $weeks = explode("|",$week); if ($weeks[1]!="") { echo $weeks[0]." a ".$weeks[1]; } else { echo $weeks[0]; }?></h4>
        <?php }
            if ($idResponsable != 0) { 
                //Si solo esta el resposable
                $cadena = "Responsable: ".$elemArrayUsu[0];
            } else {
                if ($idMaquina != 0) { 
                    //Si solo esta la maquina
                    $posicionContenidoUsuario = array_search($idResponsableMaq,$usuariosArray);
                    $elemArrayUsu = explode("|", $posicionContenidoUsuario);
                    $cadena = "Máquina: ".$elemArrayMaq[0]." ".$elemArrayMaq[1].". Responsable: ".$elemArrayUsu[0].".";
                }
            }
        ?>
        <h4 style="color:#330066;text-align: center;"><?php echo $cadena; ?></h4>

        <table class="table" cellpadding="0" cellspacing="0" border="0" class="display" id="example">
            <thead>
                <tr>
                    <th>Fecha</th>
                    <th>M&aacute;quina</th>
                    <th>Num M&aacute;quina</th>
                    <th>Actividad</th>
                    <th>Responsable</th>
                    <th>Status</th>
                    <th>Observaciones</th>
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
                                $posicionContenidoUsuario = array_search($fila->{'idResponsable'},$usuariosArray);
                                $elemArrayUsu = explode("|", $posicionContenidoUsuario);
                            
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
                            <td><?php echo $elemArrayUsu[0]; ?></td>
                            <td><?php
                                if ($fila->{'condicion_maquina'} == "NO ATENDIDA"){
                                    echo "<p style='opacity: 0; color: transparent;font-size:1px;'>##</p> <img src='".base_url()."/images/sistemaicons/nook.ico' alt='Pendiente' title='Pendiente' />";
                                } else {
                                    echo "<p style='opacity: 0; color: transparent;font-size:1px;'>@@</p> <img src='".base_url()."/images/sistemaicons/ok.ico' alt='Ok' title='Ok' />";
                                } 
                                ?>
                            </td>
                            <td>
                                <?php echo $fila->{'observaciones_maquina'}; ?>
                            </td>
                        </tr>
                      <?php                   
                      $i++;  
                    }   
                } 
                //echo "<script>mensaje();</script>";
                ?>
            </tbody>
            <tfoot>
                <tr>
                    <th>Fecha</th>
                    <th>M&aacute;quina</th>
                    <th>Num M&aacute;quina</th>
                    <th>Actividad</th>
                    <th>Responsable</th>
                    <th>Status</th>
                    <th>Observaciones</th>
                </tr>
            </tfoot>
        </table>
    </div>
</div> <!-- /division renglon en 12-->
</div> <!-- / renglon-->
</div> <!-- /container -->
</body>	
</html>
