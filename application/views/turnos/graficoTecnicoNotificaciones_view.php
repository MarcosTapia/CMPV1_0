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
        function verificaFechas(){
            if (document.getElementById('maquinaConsulta').value == "") {
                //si el responsable no existe entonces debe haber fechas
                if (document.getElementById('responsable').value == "") {
                    //verifica que no venga sola la fecha 2
                    if (document.getElementById('fecha2').value != "") {
                        if (document.getElementById('fecha1').value == "") {
                            alert("Si es solo una fecha selecciona la fecha 1");
                            return false;
                        }                
                    }
                    //verifica que la fecha 2 sea mayor que la 1
                    if ((document.getElementById('fecha1').value != "") && (document.getElementById('fecha2').value != "")) {
                        var fecha1 = document.getElementById('fecha1').value; 
                        var fecha2 = document.getElementById('fecha2').value;
                        if (fecha1 > fecha2) {
                            alert("La fecha 1 debe ser menor o igual a la fecha 2");
                            return false;
                        }
                    }
                }    
            } else {
                //verifica que no venga sola la fecha 2
                if (document.getElementById('fecha2').value != "") {
                    if (document.getElementById('fecha1').value == "") {
                        alert("Si es solo una fecha selecciona la fecha 1");
                        return false;
                    }                
                }
                //checa si la fecha 2 es mayor que la 1
                if (document.getElementById('fecha1').value != "") {
                    if (document.getElementById('fecha2').value != "") {
                        var fecha1 = parseInt(document.getElementById('fecha1').value); 
                        var fecha2 = parseInt(document.getElementById('fecha2').value);
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


    <?php 
        $contadorExcelente = 0;
        $contadorBien = 0;
        $contadorRegular = 0;
        $contadorMala = 0;
        if ($paros) {
            foreach($paros as $fila) {
                if ($fila->{'calificacionAtencion'} == 10) {
                    $contadorExcelente = $contadorExcelente + 1;
                }
                if ($fila->{'calificacionAtencion'} == 8) {
                    $contadorBien = $contadorBien + 1;
                }
                if ($fila->{'calificacionAtencion'} == 7) {
                    $contadorRegular = $contadorRegular + 1;
                }
                if ($fila->{'calificacionAtencion'} == 5) {
                    $contadorMala = $contadorMala + 1;
                }
            } 
        }
    ?>    
    
    <!-- Resources -->
    <script src="https://cdn.amcharts.com/lib/4/core.js"></script>
    <script src="https://cdn.amcharts.com/lib/4/charts.js"></script>
    <script src="https://cdn.amcharts.com/lib/4/themes/animated.js"></script>

    <!-- Chart code -->
    <script>
    am4core.ready(function() {
        am4core.useTheme(am4themes_animated);
        var chart = am4core.create("chartdiv", am4charts.PieChart);
        var chart3 = am4core.create("chartdiv3", am4charts.PieChart);
        
        chart.data = [ 
            {
              "Mantenimientos": "Excelente",
              "servicios": <?php echo $contadorExcelente; ?>
            }, 
            {
              "Mantenimientos": "Bien",
              "servicios": <?php echo $contadorBien; ?>
            },        
            {
              "Mantenimientos": "Regular",
              "servicios": <?php echo $contadorRegular; ?>
            },       
            {
              "Mantenimientos": "Mala",
              "servicios": <?php echo $contadorMala; ?>
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
        
        /**********/
        var dataGrafLineas = Array();
        infoHora = {
          "hour": "Excelente",
          "produced": parseFloat('<?php echo $contadorExcelente; ?>'),
          "meta": parseFloat(100)
        }; 
        dataGrafLineas.push(infoHora);
        infoHora = {
          "hour": "Bien",
          "produced": parseFloat('<?php echo $contadorBien; ?>'),
          "meta": parseFloat(100)
        }; 
        dataGrafLineas.push(infoHora);
        infoHora = {
          "hour": "Regular",
          "produced": parseFloat('<?php echo $contadorRegular; ?>'),
          "meta": parseFloat(100)
        }; 
        dataGrafLineas.push(infoHora);
        infoHora = {
          "hour": "Mala",
          "produced": parseFloat('<?php echo $contadorMala; ?>'),
          "meta": parseFloat(100)
        }; 
        dataGrafLineas.push(infoHora);
        var chart2 = am4core.create("chartdiv2", am4charts.XYChart);
        chart2.exporting.menu = new am4core.ExportMenu();
        var categoryAxis = chart2.xAxes.push(new am4charts.CategoryAxis());
        categoryAxis.dataFields.category = "hour";
        categoryAxis.renderer.minGridDistance = 30;
        var valueAxis = chart2.yAxes.push(new am4charts.ValueAxis());
        var columnSeries = chart2.series.push(new am4charts.ColumnSeries());
        columnSeries.name = "produced";
        columnSeries.dataFields.valueY = "produced";
        columnSeries.dataFields.categoryX = "hour";
        
        columnSeries.columns.template.tooltipText = "[#fff font-size: 15px]{name} in {categoryX}:\n[/][#fff font-size: 20px]{valueY}[/] [#fff]{additional}[/]"
        columnSeries.columns.template.propertyFields.fillOpacity = "fillOpacity";
        columnSeries.columns.template.propertyFields.stroke = "stroke";
        columnSeries.columns.template.propertyFields.strokeWidth = "strokeWidth";
        columnSeries.columns.template.propertyFields.strokeDasharray = "columnDash";
        columnSeries.tooltip.label.textAlign = "middle";
        chart2.data = dataGrafLineas;        
        /**********/
        
        chart3.data = [ 
            {
              "Mantenimientos": "Excelente",
              "servicios": <?php echo $contadorExcelente; ?>
            }, 
            {
              "Mantenimientos": "Bien",
              "servicios": <?php echo $contadorBien; ?>
            },        
            {
              "Mantenimientos": "Regular",
              "servicios": <?php echo $contadorRegular; ?>
            },       
            {
              "Mantenimientos": "Mala",
              "servicios": <?php echo $contadorMala; ?>
            }
        ];
        
        // Add and configure Series
        pieSeries = chart3.series.push(new am4charts.PieSeries());
        pieSeries.dataFields.value = "servicios";
        pieSeries.dataFields.category = "Mantenimientos";
        pieSeries.slices.template.stroke = am4core.color("#fff");
        pieSeries.slices.template.strokeOpacity = 1;
        // This creates initial animation
        pieSeries.hiddenState.properties.opacity = 1;
        pieSeries.hiddenState.properties.endAngle = -90;
        pieSeries.hiddenState.properties.startAngle = -90;
        chart3.hiddenState.properties.radius = am4core.percent(0);
        
        }); // end am4core.ready()

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
    <?php } ?>
    <script>mensaje();</script>;
    <div>
        <?php 
            $tecnico = "";
            foreach($usuarios as $fila) {
                if ($fila->{'idUsuario'} == $idUsuarioTecnico) {
                    $tecnico = $fila->{'apellido_paterno'}." ".$fila->{'apellido_materno'}." ".$fila->{'nombre'};
                }
            }
        ?>
        <h4 style="margin-top: -10px; text-align: center; color:#cc0033;">Criterios de consulta: </h4>
        <div class="table-responsive">  
            <form onsubmit="javascript: return verificaFechas()" class="form-inline" action="<?php echo base_url();?>index.php/turnos_controller/mostrarGraficoTecnico/<?php echo $idUsuarioTecnico; ?>" method="post">
            <table class="table">
                <tr>
                    <td>
                        <div class="form-group mx-sm-4 mb-3">
                        <select style="width:250px;" onchange="desactivaSelectResponsable();" class="form-control" name="maquinaConsulta" id="maquinaConsulta">
                          <option value="">Máquina...</option>
                          <?php
                              foreach($maquinasSimple as $fila) {
                                    $palabra1 = "Tableros Dimensionales";
                                    $palabra2 = "Tableros Auxiliares";
                                    if ((strpos($fila->{'nombre_maquina'}, $palabra1) !== false) || (strpos($fila->{'nombre_maquina'}, $palabra2) !== false)) {
                                    } else{
                                        //echo "<option codigo1='".$fila->{'idMaquina'}."' value=".$fila->{'idMaquina'}.">".$fila->{'nombre_maquina'}." ".$fila->{'numero_maquina'}."</option>";
                                          echo "<option codigo1='".$fila->{'idMaquina'}."' value=".$fila->{'idMaquina'}.">".$fila->{'nombre_maquina'}." - ".$fila->{'numero_maquina'}."</option>";
                                    }                                    
                              }    
                          ?>
                        </select>                              
                        <img src='<?php echo base_url(); ?>/images/sistemaicons/desbloquear.ico' alt='Desbloquear' title='Desbloquear' onclick='activaSelectMaquina()' />
                        &nbsp;&nbsp;
                        </duv>
                    </td>
                    
                    <td>
                          <div class="form-group mx-sm-4 mb-2">
                            <p>De:
                                <input style="height: 35px;" type="date" name="fecha1" id="fecha1" value="<?php echo $fecha; ?>">
                            </p>
                          </div>
                    </td>
                    
                    <td>
                        <div class="form-group mx-sm-4 mb-2">
                          <p>A:
                          <input style="height: 35px;" type="date" name="fecha2" id="fecha2" value="<?php echo $fecha; ?>">
                          </p>
                        </div>
                    </td>
                    
                    <td>
                        <input type="submit" class="btn btn-success" name="submit" value="Buscar" />
                    </td>
                </tr>
            </table>
            </form>                        
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
        <h4 style="color:#330066;text-align: center;">Notificaciones de <?php echo $tecnico; ?></h4>
        <?php }
            if ($idResponsable != 0) { 
                //Si solo esta el resposable
                $cadena = "Responsable: ".$elemArrayUsu[0].".";
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
        <br>
        <div class="col-md-12"> 
            <div class="col-md-4"> 
                <h4 style="text-align:center;">Evaluación de Servicios</h4>
                <br>
                <div id="chartdiv"></div>        
            </div>
            <div class="col-md-4"> 
                <h4 style="text-align:center;">Evaluación de Servicios</h4>
                <br>
                <div id="chartdiv2"></div>        
            </div>
            <div class="col-md-4"> 
                <h4 style="text-align:center;">Evaluación de Servicios</h4>
                <br>
                <div id="chartdiv3"></div>        
            </div>
        </div>
    </div>
</div> <!-- /division renglon en 12-->
</div> <!-- / renglon-->
</div> <!-- /container -->
</body>	
</html>
