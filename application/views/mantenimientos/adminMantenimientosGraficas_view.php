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
        
        function validaConsultaMaquinaSemanas() {
            var continua = false;
              //  semana2
            if (document.getElementById('maquinaConsulta').value == '') {
                alert('Debes elegir una máquina');
            } else {
                var mes = parseInt(prompt("Escribe el número de mes:","1)Ene.2)Feb,3)Mar4)Abr5)May,6)Jun,7)Jul,8)Ago,9)Sep,10)Oct,11)Nov,12)Dic."));
                if ((mes < 13) && (mes > 0)) {
                    document.getElementById('maquinaHidden').value = document.getElementById('maquinaConsulta').value;
                    document.getElementById('mesHidden').value = mes;
                    continua = true;
                } else {
                    alert('Mes no válido');
                    continua = false;
                }
            }
            return continua;
        }
    </script>
     
    <style>
        fieldset.scheduler-border {
            border: 1px groove #ddd !important;
            padding: 0 1.4em 1.4em 1.4em !important;
            margin: 0 0 1.5em 0 !important;
            -webkit-box-shadow:  0px 0px 0px 0px #000;
                    box-shadow:  0px 0px 0px 0px #000;
        }

        legend.scheduler-border {
            font-size: 1.2em !important;
            font-weight: bold !important;
            text-align: left !important;
        }        
    </style>
    
    <style>
    #chartdiv {
      width: 100%;
      height: 500px;
    }

    </style>

    <!-- Resources -->
    <script src="https://cdn.amcharts.com/lib/4/core.js"></script>
    <script src="https://cdn.amcharts.com/lib/4/charts.js"></script>
    <script src="https://cdn.amcharts.com/lib/4/themes/animated.js"></script>

    <!-- Chart code -->
    <script>
    am4core.ready(function() {

    // Themes begin
    am4core.useTheme(am4themes_animated);
    // Themes end

    // Create chart instance
    var chart = am4core.create("chartdiv", am4charts.PieChart);

    // Add data
    <?php 
        $contadorRealizados = 0;
        $contadorPendientes = 0;
        foreach($mantenimientos as $fila) {
            if ($fila->{'condicion_maquina'} == "NO ATENDIDA") {
                $contadorPendientes = $contadorPendientes + 1;
            } else {
                $contadorRealizados = $contadorRealizados  + 1;
            }
        } 
    ?>    
    
    chart.data = [ 
        {
          "Mantenimientos": "Realizados",
          "servicios": <?php echo $contadorRealizados; ?>
        }, {
          "Mantenimientos": "Pendientes",
          "servicios": <?php echo $contadorPendientes; ?>
        }        

    ];

    // Add and configure Series
    var pieSeries = chart.series.push(new am4charts.PieSeries());
    pieSeries.dataFields.value = "servicios";
    pieSeries.dataFields.category = "Mantenimientos";
    pieSeries.slices.template.stroke = am4core.color("#fff");
    pieSeries.slices.template.strokeOpacity = 1;

    // This creates initial animation
    pieSeries.hiddenState.properties.opacity = 1;
    pieSeries.hiddenState.properties.endAngle = -90;
    pieSeries.hiddenState.properties.startAngle = -90;

    chart.hiddenState.properties.radius = am4core.percent(0);


    }); // end am4core.ready()
    </script>    
</head>
<body>
<div class="container">
<div class="row">
<div class="col-md-12">
    <br>
    
    <div>
        <div class="table-responsive">  
            <table class="table">
                <tr>
                    <td>
                        <h5 style="color:#cc0033;font-weight: bold;">Consulta de Mantenimientos: </h5>
                        <form onsubmit="javascript: return verificaFechas()" class="form-inline" action="<?php echo base_url();?>index.php/mantenimiento_controller/mostrarGraficas" method="post">
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
                          </div>
                          &nbsp;  
                          <input type="submit" class="btn btn-success" name="submit" value="Buscar" />
                        </form>                        
                    </td>
                </tr>
            </table>
        </div>
    </div>
    
    <div>
        <div class="table-responsive">  
        <center>
            <div>
                <form action="<?php echo base_url();?>index.php/mantenimiento_controller/exportarExcel" method="post" style="display:inline;">  
                      <?php if (isset($mantenimientos)) { ?>
                      <input type="hidden" name="mantenimientosHidden" id="mantenimientosHidden" value="<?php echo htmlspecialchars(serialize($mantenimientos)) ?>" />
                      <input type="hidden" name="mantenimientosHiddenCheckDatasetExist" id="mantenimientosHiddenCheckDatasetExist" value="1" />
                      <?php } else { ?>
                      <input type="hidden" name="mantenimientosHiddenCheckDatasetExist" id="mantenimientosHiddenCheckDatasetExist" value="0" />
                      <?php } ?>
                      <input onclick='javascript: return validaConsultaParaExcel()' style="padding-top: 2px; width: 33px; height: 33px;" type="image" src="<?php echo base_url(); ?>/images/sistemaicons/excelicon2.png" alt="Exportar a Excel la última consulta" title="Exportar a Excel la última consulta" />
                 </form>
                <form action="<?php echo base_url();?>index.php/mantenimiento_controller/verManttoPdf" method="post" style="display:inline;">    
                      <?php if (isset($mantenimientos)) { ?>
                      <input type="hidden" name="mantenimientosHidden" value="<?php echo htmlspecialchars(serialize($mantenimientos)) ?>" />
                      <input type="hidden" name="mantenimientosHiddenCheckDatasetExist" id="mantenimientosHiddenCheckDatasetExist" value="1" />
                      <?php } else { ?>
                      <input type="hidden" name="mantenimientosHiddenCheckDatasetExist" id="mantenimientosHiddenCheckDatasetExist" value="0" />
                      <?php } ?>
                      <input onclick='javascript: return validaConsultaParaPdf()' style="padding-top: 2px; width: 33px; height: 33px;" type="image" src="<?php echo base_url(); ?>/images/sistemaicons/pdficon2.png" alt="Ver última consulta en PDF" title="Ver última consulta en PDF" />
               </form>
               <form onsubmit='javascript: return validaConsultaMaquinaSemanas()' action="<?php echo base_url();?>index.php/mantenimiento_controller/verManttoPdf2" method="post" style="display:inline;">    
                      <input type="hidden" name="mesHidden" id="mesHidden" />
                      <input type="hidden" name="maquinaHidden" id="maquinaHidden" />
                      <input style="padding-top: 2px; width: 33px; height: 33px;" type="image" src="<?php echo base_url(); ?>/images/sistemaicons/reporte.ico" alt="Reporte por Máquina" title="Reporte por Máquina" />
               </form>
            </div>
        </center>
        <br>
        </div>
    </div>
    
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
                $cadena = "Máquina: ".$elemArrayMaq[0]." ".$elemArrayMaq[1]." Responsable: ".$elemArrayUsu[0];
            }
        }
    ?>
    <h4 style="color:#330066;text-align: center;"><?php echo $cadena; ?></h4>
                                
    <div class="table-responsive col-md-12"> 
        <div id="chartdiv"></div>        
    </div>
    
</div> <!-- /division renglon en 12-->
</div> <!-- / renglon-->
</div> <!-- /container -->
</body>	
</html>
