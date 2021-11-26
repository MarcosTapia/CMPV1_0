<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title><?php if ($nombre_Empresa != "") { echo $nombre_Empresa; }?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <link href='http://fonts.googleapis.com/css?family=Economica' rel='stylesheet' type='text/css'>
    <link href="<?php echo base_url();?>css/bootstrap.css" rel="stylesheet">
    
    <style>
    #chartdiv5 {
      margin-top:-60px;
      margin-left: 60px; 
      width: 100%;
      height: 700px;
    }
    #chartdiv6 {
      margin-top:-60px;
      margin-left: 30px; 
      width: 100%;
      height: 700px;
    }

    </style>    
    
    <script type="text/javascript" charset="utf-8">
        function verificaFechas(){
            //verifica que vengan las dos fechas
            if ((document.getElementById('fecha1').value == "") && (document.getElementById('fecha2').value == "")) {
                alert("Debes ingresar al menos la fecha 1.");
                return false;
            }
            
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
            return true;
        }
    </script>
</head>
<body>
<div class="container">
<div class="row">
<div class="col-md-12">
    <div>
        <p style="font-size: 20px; margin-top: -10px; text-align: center; color: #0000CC; ">Consulta por Fechas: </p>
            <form onsubmit="javascript: return verificaFechas()" class="form-inline" action="<?php echo base_url();?>index.php/turnos_controller/consultaParosGraficas" method="post">
                <center>
                <div>
                    <!--
                  <div class="form-group">
                    <p>Proyecto:
                    <select style="height: 35px;" class="form-control" name="proyecto" id="proyecto">
                        <option value="">General...</option>
                        <?php 
                            foreach($proyectos as $filaN) {
                                echo "<option value=".$filaN->{'idProyecto'}.">".$filaN->{'descripcion_proyecto'}."</option>";
                            } 
                        ?>
                    </select>  
                    &nbsp;&nbsp;
                    </p>
                  </div>
                  -->  
                    
                  <div class="form-group">
                      <p>De:
                          <input style="height: 35px;" type="date" name="fecha1" id="fecha1" value="<?php echo $fecha; ?>">
                      </p>
                  </div>

                  <div class="form-group">
                      <p style="margin-left:10px;">A:
                      <input style="height: 35px;" type="date" name="fecha2" id="fecha2" value="<?php echo $fecha; ?>">
                      
                      <input type="submit" class="btn btn-primary" name="submit" value="Buscar" style="margin-left:20px;margin-top: -3px; height: 38px;"/>
                    </p>
                  </div>
                </div>
                </center>
            </form>                        
    </div>

    <div class="row">   
        <div class="col-md-12"> 
            <p style="font-size: 20px; text-align:center;margin-top: 30px;color: #0000CC; ">APLICACIONES (CANTIDAD DE CAMBIOS)</p>
            <div class="col-md-6"> 
                <h4 style="text-align:center;">APLICADORES</h4>
                <br>
                <div id="chartdiv_aa"></div>        
            </div>
            <div class="col-md-6"> 
                <h4 style="text-align:center;">MOLDES</h4>
                <br>
                <div id="chartdiv_bb"></div>        
            </div>
        </div>
    </div>
    
    <div class="row">   
        <div class="col-md-12"> 
            <p style="font-size: 20px; text-align:center;margin-top: 30px;color: #0000CC; ">POR PROYECTOS</p>
            <div class="col-md-6"> 
                <h4 style="text-align:center;">NUMERO DE PAROS</h4>
                <br>
                <div id="chartdiv_a"></div>        
            </div>
            <div class="col-md-6"> 
                <h4 style="text-align:center;">DURACION DE PAROS (HRS)</h4>
                <br>
                <div id="chartdiv_b"></div>        
            </div>
        </div>
    </div>
    
    <div class="row">   
        <div class="col-md-12"> 
            <p style="font-size: 20px; text-align:center;margin-top: 30px;color: #0000CC; ">POR AREAS</p>
            <div class="col-md-6"> 
                <h4 style="text-align:center;">NUMERO DE PAROS</h4>
                <br>
                <div id="chartdiv1"></div>        
            </div>
            <div class="col-md-6"> 
                <h4 style="text-align:center;">DURACION DE PAROS (HRS)</h4>
                <br>
                <div id="chartdiv2"></div>        
            </div>
        </div>
    </div>
    <div class="row">   
        <div class="col-md-12"> 
            <br>
            <p style="font-size: 20px; text-align:center;color: #0000CC; ">POR CATEGORIAS</p>
            <div class="col-md-6"> 
                <h4 style="text-align:center;">NUMERO DE PAROS</h4>
                <br>
                <div id="chartdiv3"></div>        
            </div>
            <div class="col-md-6"> 
                <h4 style="text-align:center;">DURACION DE PAROS (HRS)</h4>
                <br>
                <div id="chartdiv4"></div>        
            </div>
        </div>
    </div>
    <div class="row">   
        <div class="col-md-12"> 
            <br>
            <p style="font-size: 20px;; text-align:center;color: #0000CC; ">POR MAQUINAS</p>
            <div class="col-md-6"> 
                <h4 style="text-align:center;">NUMERO DE PAROS</h4>
                <div id="chartdiv5"></div>        
            </div>
            <div class="col-md-6"> 
                <h4 style="text-align:center;">DURACION DE PAROS (HRS)</h4>
                <div id="chartdiv6"></div>        
            </div>            
        </div>        
    </div>
</div> <!-- /division renglon en 12-->
</div> <!-- / renglon-->
</div> <!-- /container -->

    <?php
        function getHoursMinutes($seconds, $format = '%02d.%02d') {
            if (empty($seconds) || ! is_numeric($seconds)) {
                return false;
            }
            $minutes = round($seconds / 60);
            $hours = floor($minutes / 60);
            $remainMinutes = ($minutes % 60);
            return sprintf($format, $hours, $remainMinutes);
        }
        
        /*************************/
        $dataAplicacionesArray;
        $i=0;
        foreach($proyectos as $filaProyecto) {
            $dataAplicacionesArray[$i][0] = $filaProyecto->{'idProyecto'};
            $dataAplicacionesArray[$i][1] = $filaProyecto->{'descripcion_proyecto'};
            $dataAplicacionesArray[$i][2] = 0;//aplicadores
            $dataAplicacionesArray[$i][3] = 0;//moldes
            $i++;
        }
        
        if ($paros) {
            foreach($paros as $fila) {
                if (isset($fila->{'idProyecto'})) {
                    for ($i=0; $i<sizeof($dataAplicacionesArray);$i++) {
                        if ($fila->{'idProyecto'} == $dataAplicacionesArray[$i][0]) {
                            if (($fila->{'desc_falla'} == "Cambio de molde") || ($fila->{'desc_falla'} == "Cambio de aplicador")) {
                                if ($fila->{'desc_falla'} == "Cambio de aplicador") {
                                    $dataAplicacionesArray[$i][2] = $dataAplicacionesArray[$i][2] + 1;
                                }
                                if ($fila->{'desc_falla'} == "Cambio de molde") {
                                    $dataAplicacionesArray[$i][3] = $dataAplicacionesArray[$i][3] + 1;
                                }
                            }
                        }
                    }
                }
            }
        }
        
//        for ($i=0; $i<sizeof($dataAplicacionesArray);$i++) {
//            echo "*********** ".$dataAplicacionesArray[$i][0]." - ".$dataAplicacionesArray[$i][1]." - ".$dataAplicacionesArray[$i][2]." - ".$dataAplicacionesArray[$i][3]."<br>";
//        }          
        /*************************/
        
        
        /*************************/
        $dataProyectosArray;
        $i=0;
        foreach($proyectos as $filaProyecto) {
            $dataProyectosArray[$i][0] = $filaProyecto->{'idProyecto'};
            $dataProyectosArray[$i][1] = $filaProyecto->{'descripcion_proyecto'};
            $dataProyectosArray[$i][2] = 0;
            $dataProyectosArray[$i][3] = "00:00:00";
            $i++;
        }
        
        if ($paros) {
            foreach($paros as $fila) {
                if (isset($fila->{'idProyecto'})) {
                    for ($i=0; $i<sizeof($dataProyectosArray);$i++) {
                        if ($fila->{'idProyecto'} == $dataProyectosArray[$i][0]) {
                            $dataProyectosArray[$i][2] = $dataProyectosArray[$i][2] + 1;

                            $time = $dataProyectosArray[$i][3];
                            $time2 = $fila->{'tiempoAtencion'};
                            $secs = strtotime($time2)-strtotime("00:00:00");
                            $result = date("H:i:s",strtotime($time)+$secs);                            
                            $dataProyectosArray[$i][3] = $result;
                        }
                    }
                }
            }
        }
        
        for ($i=0; $i<sizeof($dataProyectosArray);$i++) {
            if ($dataProyectosArray[$i][3] != "00:00:00") {
                $str_time = $dataProyectosArray[$i][3];
                sscanf($str_time, "%d:%d:%d", $hours, $minutes, $seconds);
                $time_seconds = isset($seconds) ? $hours * 3600 + $minutes * 60 + $seconds : $hours * 60 + $minutes;
                $dataProyectosArray[$i][3] = getHoursMinutes($time_seconds);
            } else {
                $dataProyectosArray[$i][3] = 0;
            }
        }
//        for ($i=0; $i<sizeof($dataProyectosArray);$i++) {
//            echo "*********** ".$dataProyectosArray[$i][0]." - ".$dataProyectosArray[$i][1]." - ".$dataProyectosArray[$i][2]." - ".$dataProyectosArray[$i][3]."<br>";
//        }          
        /*************************/
        
        
        /*************************/
        $dataAreasArray;
        $i=0;
        foreach($areas as $filaArea) {
            $dataAreasArray[$i][0] = $filaArea->{'idArea'};
            $dataAreasArray[$i][1] = $filaArea->{'descripcion'};
            $dataAreasArray[$i][2] = 0;
            $dataAreasArray[$i][3] = "00:00:00";
            $i++;
        }
        
        if ($paros) {
            foreach($paros as $fila) {
                if (isset($fila->{'idArea'})) {
                    for ($i=0; $i<sizeof($dataAreasArray);$i++) {
                        //area diferente de gral y de almacen 17,6
                        if (($fila->{'idArea'} == $dataAreasArray[$i][0]) && ($dataAreasArray[$i][0] != 17) && ($dataAreasArray[$i][0] != 6)) {
                            $dataAreasArray[$i][2] = $dataAreasArray[$i][2] + 1;
                            
                            $time = $dataAreasArray[$i][3];
                            $time2 = $fila->{'tiempoAtencion'};
                            $secs = strtotime($time2)-strtotime("00:00:00");
                            $result = date("H:i:s",strtotime($time)+$secs);                            
                            $dataAreasArray[$i][3] = $result;
                        }
                    }
                }
            }
        }
        
        for ($i=0; $i<sizeof($dataAreasArray);$i++) {
            if ($dataAreasArray[$i][3] != "00:00:00") {
                $str_time = $dataAreasArray[$i][3];
                sscanf($str_time, "%d:%d:%d", $hours, $minutes, $seconds);
                $time_seconds = isset($seconds) ? $hours * 3600 + $minutes * 60 + $seconds : $hours * 60 + $minutes;
                $dataAreasArray[$i][3] = getHoursMinutes($time_seconds);
            } else {
                $dataAreasArray[$i][3] = 0;
            }
        }

//        for ($i=0; $i<sizeof($dataAreasArray);$i++) {
//            echo "*********** ".$dataAreasArray[$i][0]." - ".$dataAreasArray[$i][1]." - ".$dataAreasArray[$i][2]." - ".$dataAreasArray[$i][3]."<br>";
//        }         
        /*************************/

        /*************************/
        $dataCategoriasArray;
        $i=0;
        foreach($categorias as $filaCat) {
            $dataCategoriasArray[$i][0] = $filaCat->{'idCategoria'};
            $dataCategoriasArray[$i][1] = $filaCat->{'descripcion_categoria'};
            $dataCategoriasArray[$i][2] = 0;
            $dataCategoriasArray[$i][3] = "00:00:00";
            $i++;
        }
        
        if ($paros) {
            foreach($paros as $fila) {
                if (isset($fila->{'idCategoria'})) {
                    for ($i=0; $i<sizeof($dataCategoriasArray);$i++) {
                        if ($fila->{'idCategoria'} == $dataCategoriasArray[$i][0]) {
                            $dataCategoriasArray[$i][2] = $dataCategoriasArray[$i][2] + 1;
                            
                            $time = $dataCategoriasArray[$i][3];
                            $time2 = $fila->{'tiempoAtencion'};
                            $secs = strtotime($time2)-strtotime("00:00:00");
                            $result = date("H:i:s",strtotime($time)+$secs);                            
                            $dataCategoriasArray[$i][3] = $result;
                        }
                    }
                }
            }
        }
        
        for ($i=0; $i<sizeof($dataCategoriasArray);$i++) {          
            if ($dataCategoriasArray[$i][3] != "00:00:00") {
                $str_time = $dataCategoriasArray[$i][3];
                sscanf($str_time, "%d:%d:%d", $hours, $minutes, $seconds);
                $time_seconds = isset($seconds) ? $hours * 3600 + $minutes * 60 + $seconds : $hours * 60 + $minutes;
                $dataCategoriasArray[$i][3] = getHoursMinutes($time_seconds);
            } else {
                $dataCategoriasArray[$i][3] = 0;
            }
        }
        /*************************/
        
        /*************************/
        $dataMaquinasArray;
        $i=0;
        foreach($maquinasSimple as $filaMaq) {
            $palabra1 = "Tableros Dimensionales";
            $palabra2 = "Tableros Auxiliares";
            if ((strpos($filaMaq->{'nombre_maquina'}, $palabra1) !== false) || (strpos($filaMaq->{'nombre_maquina'}, $palabra2) !== false)) {
            } else{
                if ($filaMaq->{'status'} != 0) {
                    $dataMaquinasArray[$i][0] = $filaMaq->{'idMaquina'};
                    $dataMaquinasArray[$i][1] = $filaMaq->{'nombre_maquina'}." - ".$filaMaq->{'numero_maquina'};
                    $dataMaquinasArray[$i][2] = 0;
                    $dataMaquinasArray[$i][3] = "00:00:00";
                    $dataMaquinasArray[$i][4] = $filaMaq->{'idArea'};
                    $i++;
                }
            } 
        }
        
        if ($paros) {
            foreach($paros as $fila) {
                if (isset($fila->{'idMaqui'})) {
                    for ($i=0; $i<sizeof($dataMaquinasArray);$i++) {
                        if (($fila->{'idMaqui'} == $dataMaquinasArray[$i][0]) && ($fila->{'idMaqui'} != 589)) {
                            $dataMaquinasArray[$i][2] = $dataMaquinasArray[$i][2] + 1;
                            
                            $time = $dataMaquinasArray[$i][3];
                            $time2 = $fila->{'tiempoAtencion'};
                            $secs = strtotime($time2)-strtotime("00:00:00");
                            $result = date("H:i:s",strtotime($time)+$secs);
                            $dataMaquinasArray[$i][3] = $result;
                        }
                    }
                }
            }
        }
        
        for ($i=0; $i<sizeof($dataMaquinasArray);$i++) {
            if ($dataMaquinasArray[$i][3] != "00:00:00") {
                $str_time = $dataMaquinasArray[$i][3];
                sscanf($str_time, "%d:%d:%d", $hours, $minutes, $seconds);
                $time_seconds = isset($seconds) ? $hours * 3600 + $minutes * 60 + $seconds : $hours * 60 + $minutes;
                $dataMaquinasArray[$i][3] = getHoursMinutes($time_seconds);
            } else {
                $dataMaquinasArray[$i][3] = 0;
            }
        }
        
        for ($i=0; $i<sizeof($dataMaquinasArray);$i++) {
            if (strlen($dataMaquinasArray[$i][3]) > 1) {
                if ((substr($dataMaquinasArray[$i][3], 0,2) == "00") || (substr($dataMaquinasArray[$i][3], 0,2) == "01") || (substr($dataMaquinasArray[$i][3], 0,2) == "02") || (substr($dataMaquinasArray[$i][3], 0,2) == "03") || 
                        (substr($dataMaquinasArray[$i][3], 0,2) == "04") || (substr($dataMaquinasArray[$i][3], 0,2) == "05") || (substr($dataMaquinasArray[$i][3], 0,2) == "06") || (substr($dataMaquinasArray[$i][3], 0,2) == "07") || 
                        (substr($dataMaquinasArray[$i][3], 0,2) == "08") || (substr($dataMaquinasArray[$i][3], 0,2) == "09")) {
                    //echo "si<br>";
                    $dataMaquinasArray[$i][3] = substr($dataMaquinasArray[$i][3], 1, 5);
                }
            }
        }        
        /*************************/
    ?>    
    
    <!-- Resources -->
    <script src="https://cdn.amcharts.com/lib/4/core.js"></script>
    <script src="https://cdn.amcharts.com/lib/4/charts.js"></script>
    <script src="https://cdn.amcharts.com/lib/4/themes/animated.js"></script>

    <!-- Chart code -->
    <script>
    am4core.ready(function() {
        am4core.useTheme(am4themes_animated);

        /**********/
        //Prepara datos para grafico _aa
        var dataGraficoBarras = Array();
        <?php
        for ($i=0; $i<sizeof($dataAplicacionesArray);$i++) { ?>
            var cantidad = '<?php echo $dataAplicacionesArray[$i][2]; ?>';
            info = {
              "hour": "<?php echo substr($dataAplicacionesArray[$i][1],0,3); ?>",  
              "produced": parseInt(cantidad)
            }; 
            dataGraficoBarras.push(info);
       <?php } ?>  
        var chart_aa = am4core.create("chartdiv_aa", am4charts.XYChart);
        var categoryAxis = chart_aa.xAxes.push(new am4charts.CategoryAxis());
        categoryAxis.dataFields.category = "hour";
        categoryAxis.renderer.minGridDistance = 30;
        var valueAxis = chart_aa.yAxes.push(new am4charts.ValueAxis());
        var columnSeries = chart_aa.series.push(new am4charts.ColumnSeries());
        columnSeries.name = "produced";
        columnSeries.dataFields.valueY = "produced";
        columnSeries.dataFields.categoryX = "hour";
        columnSeries.columns.template.tooltipText = "[#fff font-size: 15px]{name} in {categoryX}:\n[/][#fff font-size: 20px]{valueY}[/] [#fff]{additional}[/]"
        columnSeries.columns.template.propertyFields.fillOpacity = "fillOpacity";
        columnSeries.columns.template.propertyFields.stroke = "stroke";
        columnSeries.columns.template.propertyFields.strokeWidth = "strokeWidth";
        columnSeries.columns.template.propertyFields.strokeDasharray = "columnDash";
        columnSeries.tooltip.label.textAlign = "middle";
        chart_aa.data = dataGraficoBarras;        
        /**********/    

        /**********/
        //Prepara datos para grafico _bb
        dataGraficoBarras = Array();
        <?php
        for ($i=0; $i<sizeof($dataAplicacionesArray);$i++) { ?>
            var cantidad = '<?php echo $dataAplicacionesArray[$i][3]; ?>';
            info = {
              "hour": "<?php echo substr($dataAplicacionesArray[$i][1],0,3); ?>",  
              "produced": parseInt(cantidad)
            }; 
            dataGraficoBarras.push(info);
       <?php } ?>  
        var chart_bb = am4core.create("chartdiv_bb", am4charts.XYChart);
        var categoryAxis = chart_bb.xAxes.push(new am4charts.CategoryAxis());
        categoryAxis.dataFields.category = "hour";
        categoryAxis.renderer.minGridDistance = 30;
        var valueAxis = chart_bb.yAxes.push(new am4charts.ValueAxis());
        var columnSeries = chart_bb.series.push(new am4charts.ColumnSeries());
        columnSeries.name = "produced";
        columnSeries.dataFields.valueY = "produced";
        columnSeries.dataFields.categoryX = "hour";
        columnSeries.columns.template.tooltipText = "[#fff font-size: 15px]{name} in {categoryX}:\n[/][#fff font-size: 20px]{valueY}[/] [#fff]{additional}[/]"
        columnSeries.columns.template.propertyFields.fillOpacity = "fillOpacity";
        columnSeries.columns.template.propertyFields.stroke = "stroke";
        columnSeries.columns.template.propertyFields.strokeWidth = "strokeWidth";
        columnSeries.columns.template.propertyFields.strokeDasharray = "columnDash";
        columnSeries.tooltip.label.textAlign = "middle";
        chart_bb.data = dataGraficoBarras;        
        /**********/    

        /**********/
        //Prepara datos para grafico _a
        dataGraficoBarras = Array();
        <?php
        for ($i=0; $i<sizeof($dataProyectosArray);$i++) { ?>
            var cantidad = '<?php echo $dataProyectosArray[$i][2]; ?>';
            info = {
              "hour": "<?php echo substr($dataProyectosArray[$i][1],0,3); ?>",  
              "produced": parseFloat(cantidad)
            }; 
            dataGraficoBarras.push(info);
       <?php } ?>  
        var chart_a = am4core.create("chartdiv_a", am4charts.XYChart);
        var categoryAxis = chart_a.xAxes.push(new am4charts.CategoryAxis());
        categoryAxis.dataFields.category = "hour";
        categoryAxis.renderer.minGridDistance = 30;
        var valueAxis = chart_a.yAxes.push(new am4charts.ValueAxis());
        var columnSeries = chart_a.series.push(new am4charts.ColumnSeries());
        columnSeries.name = "produced";
        columnSeries.dataFields.valueY = "produced";
        columnSeries.dataFields.categoryX = "hour";
        columnSeries.columns.template.tooltipText = "[#fff font-size: 15px]{name} in {categoryX}:\n[/][#fff font-size: 20px]{valueY}[/] [#fff]{additional}[/]"
        columnSeries.columns.template.propertyFields.fillOpacity = "fillOpacity";
        columnSeries.columns.template.propertyFields.stroke = "stroke";
        columnSeries.columns.template.propertyFields.strokeWidth = "strokeWidth";
        columnSeries.columns.template.propertyFields.strokeDasharray = "columnDash";
        columnSeries.tooltip.label.textAlign = "middle";
        chart_a.data = dataGraficoBarras;        
        /**********/    

        /**********/
        //Prepara datos para grafico _b
        var dataGraficoBarrasP = Array();
        <?php
        for ($i=0; $i<sizeof($dataProyectosArray);$i++) { ?>
            var cantidad = '<?php echo $dataProyectosArray[$i][3]; ?>';
            info = {
              "hour": "<?php echo substr($dataProyectosArray[$i][1],0,3); ?>",  
              "time": parseFloat(cantidad)
            }; 
            dataGraficoBarrasP.push(info);
       <?php } ?>  
       
        var chart_b = am4core.create("chartdiv_b", am4charts.XYChart);
        //chart_b.exporting.menu = new am4core.ExportMenu();
        var categoryAxis = chart_b.xAxes.push(new am4charts.CategoryAxis());
        categoryAxis.dataFields.category = "hour";
        categoryAxis.renderer.minGridDistance = 30;
        var valueAxis = chart_b.yAxes.push(new am4charts.ValueAxis());
        var columnSeries = chart_b.series.push(new am4charts.ColumnSeries());
        columnSeries.name = "time";
        columnSeries.dataFields.valueY = "time";
        columnSeries.dataFields.categoryX = "hour";
        
        columnSeries.columns.template.tooltipText = "[#fff font-size: 15px]{name} in {categoryX}:\n[/][#fff font-size: 20px]{valueY}[/] [#fff]{additional}[/]"
        columnSeries.columns.template.propertyFields.fillOpacity = "fillOpacity";
        columnSeries.columns.template.propertyFields.stroke = "stroke";
        columnSeries.columns.template.propertyFields.strokeWidth = "strokeWidth";
        columnSeries.columns.template.propertyFields.strokeDasharray = "columnDash";
        columnSeries.tooltip.label.textAlign = "middle";
        chart_b.data = dataGraficoBarrasP;        
        /**********/    


        /**********/
        //Prepara datos para grafico 1
        dataGraficoBarras = Array();
        <?php
        for ($i=0; $i<sizeof($dataAreasArray);$i++) { ?>
            <?php if (($dataAreasArray[$i][0] != 17) && ($dataAreasArray[$i][0] != 6)) { ?>
            var cantidad = '<?php echo $dataAreasArray[$i][2]; ?>';
            info = {
              "hour": "<?php echo substr($dataAreasArray[$i][1],0,3); ?>",  
              "produced": parseFloat(cantidad)
            }; 
            dataGraficoBarras.push(info);
            <?php } ?>
       <?php } ?>  
       
        var chart1 = am4core.create("chartdiv1", am4charts.XYChart);
        //chart1.exporting.menu = new am4core.ExportMenu();
        var categoryAxis = chart1.xAxes.push(new am4charts.CategoryAxis());
        categoryAxis.dataFields.category = "hour";
        categoryAxis.renderer.minGridDistance = 30;
        var valueAxis = chart1.yAxes.push(new am4charts.ValueAxis());
        var columnSeries = chart1.series.push(new am4charts.ColumnSeries());
        columnSeries.name = "produced";
        columnSeries.dataFields.valueY = "produced";
        columnSeries.dataFields.categoryX = "hour";
        
        columnSeries.columns.template.tooltipText = "[#fff font-size: 15px]{name} in {categoryX}:\n[/][#fff font-size: 20px]{valueY}[/] [#fff]{additional}[/]"
        columnSeries.columns.template.propertyFields.fillOpacity = "fillOpacity";
        columnSeries.columns.template.propertyFields.stroke = "stroke";
        columnSeries.columns.template.propertyFields.strokeWidth = "strokeWidth";
        columnSeries.columns.template.propertyFields.strokeDasharray = "columnDash";
        columnSeries.tooltip.label.textAlign = "middle";
        chart1.data = dataGraficoBarras;        
        /**********/    

        /**********/
        //Prepara datos para grafico 2
        var dataGraficoBarras = Array();
        <?php
        for ($i=0; $i<sizeof($dataAreasArray);$i++) { ?>
            <?php if (($dataAreasArray[$i][0] != 17) && ($dataAreasArray[$i][0] != 6)) { ?>
            var cantidad = '<?php echo $dataAreasArray[$i][3]; ?>';
            info = {
              "hour": "<?php echo substr($dataAreasArray[$i][1],0,3); ?>",  
              "time": parseFloat(cantidad)
            }; 
            dataGraficoBarras.push(info);
            <?php } ?>
       <?php } ?>  
       
        var chart2 = am4core.create("chartdiv2", am4charts.XYChart);
        //chart2.exporting.menu = new am4core.ExportMenu();
        var categoryAxis = chart2.xAxes.push(new am4charts.CategoryAxis());
        categoryAxis.dataFields.category = "hour";
        categoryAxis.renderer.minGridDistance = 30;
        var valueAxis = chart2.yAxes.push(new am4charts.ValueAxis());
        var columnSeries = chart2.series.push(new am4charts.ColumnSeries());
        columnSeries.name = "time";
        columnSeries.dataFields.valueY = "time";
        columnSeries.dataFields.categoryX = "hour";
        
        columnSeries.columns.template.tooltipText = "[#fff font-size: 15px]{name} in {categoryX}:\n[/][#fff font-size: 20px]{valueY}[/] [#fff]{additional}[/]"
        columnSeries.columns.template.propertyFields.fillOpacity = "fillOpacity";
        columnSeries.columns.template.propertyFields.stroke = "stroke";
        columnSeries.columns.template.propertyFields.strokeWidth = "strokeWidth";
        columnSeries.columns.template.propertyFields.strokeDasharray = "columnDash";
        columnSeries.tooltip.label.textAlign = "middle";
        chart2.data = dataGraficoBarras;        
        /**********/    
        
        //Prepara datos para grafico 3
        var chart = am4core.create("chartdiv3", am4charts.XYChart);
        chart.hiddenState.properties.opacity = 0;
        var $dataCategoriasArray = Array();
        <?php
        for ($i=0; $i<sizeof($dataCategoriasArray);$i++) { ?>
            <?php if ($dataCategoriasArray[$i][0] != 589) { ?>
            var cantidad = '<?php echo $dataCategoriasArray[$i][2]; ?>';
            info = {
              "category": "<?php echo substr($dataCategoriasArray[$i][1],0,3); ?>",
              "value": parseFloat(cantidad),
              "open": 0,
              "color": chart.colors.getIndex( <?php echo $i; ?> ),
              "displayValue": parseFloat(cantidad)
      
            }; 
            $dataCategoriasArray.push(info);
            <?php } ?>
        <?php } ?>              
        chart.data = $dataCategoriasArray;
        var categoryAxis = chart.xAxes.push(new am4charts.CategoryAxis());
        categoryAxis.dataFields.category = "category";
        categoryAxis.renderer.minGridDistance = 40;
        var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());
        var columnSeries = chart.series.push(new am4charts.ColumnSeries());
        columnSeries.dataFields.categoryX = "category";
        columnSeries.dataFields.valueY = "value";
        columnSeries.dataFields.openValueY = "open";
        columnSeries.fillOpacity = 0.8;
        columnSeries.sequencedInterpolation = true;
        columnSeries.interpolationDuration = 1500;
        var columnTemplate = columnSeries.columns.template;
        columnTemplate.strokeOpacity = 0;
        columnTemplate.propertyFields.fill = "color";
        var label = columnTemplate.createChild(am4core.Label);
        label.text = "{displayValue.formatNumber('#,## a')}";
        label.align = "center";
        label.valign = "middle";
        var stepSeries = chart.series.push(new am4charts.StepLineSeries());
        stepSeries.dataFields.categoryX = "category";
        stepSeries.dataFields.valueY = "stepValue";
        stepSeries.noRisers = true;
        stepSeries.stroke = new am4core.InterfaceColorSet().getFor("alternativeBackground");
        stepSeries.strokeDasharray = "3,3";
        stepSeries.interpolationDuration = 2000;
        stepSeries.sequencedInterpolation = true;
        // because column width is 80%, we modify start/end locations so that step would start with column and end with next column
        stepSeries.startLocation = 0.1;
        stepSeries.endLocation = 1.1;
        chart.cursor = new am4charts.XYCursor();
        chart.cursor.behavior = "none";
        /**********/    

        //Prepara datos para grafico 4
        var $dataCategoriasArray = Array();
        <?php
        for ($i=0; $i<sizeof($dataCategoriasArray);$i++) { ?>
            <?php if (($dataCategoriasArray[$i][0] != 17) && ($dataCategoriasArray[$i][0] != 6)) { ?>
            var cantidad = '<?php echo $dataCategoriasArray[$i][3]; ?>';
            info = {
              "hour": "<?php echo substr($dataCategoriasArray[$i][1],0,3); ?>",  
              "time": parseFloat(cantidad)
            }; 
            $dataCategoriasArray.push(info);
            <?php } ?>
       <?php } ?>  
       
        var chart4 = am4core.create("chartdiv4", am4charts.XYChart);
        //chart4.exporting.menu = new am4core.ExportMenu();
        var categoryAxis = chart4.xAxes.push(new am4charts.CategoryAxis());
        categoryAxis.dataFields.category = "hour";
        categoryAxis.renderer.minGridDistance = 30;
        var valueAxis = chart4.yAxes.push(new am4charts.ValueAxis());
        var columnSeries = chart4.series.push(new am4charts.ColumnSeries());
        columnSeries.name = "time";
        columnSeries.dataFields.valueY = "time";
        columnSeries.dataFields.categoryX = "hour";
        
        columnSeries.columns.template.tooltipText = "[#fff font-size: 15px]{name} in {categoryX}:\n[/][#fff font-size: 20px]{valueY}[/] [#fff]{additional}[/]"
        columnSeries.columns.template.propertyFields.fillOpacity = "fillOpacity";
        columnSeries.columns.template.propertyFields.stroke = "stroke";
        columnSeries.columns.template.propertyFields.strokeWidth = "strokeWidth";
        columnSeries.columns.template.propertyFields.strokeDasharray = "columnDash";
        columnSeries.tooltip.label.textAlign = "middle";
        chart4.data = $dataCategoriasArray;        
        /**********/    

//Prepara datos para grafico 5
var temperatures = {};
var temperatures2 = {};
<?php
for ($i=0; $i<sizeof($dataAreasArray);$i++) { 
    if ($dataAreasArray[$i][0] != 17) { ?>
        var idArea = "<?php echo $dataAreasArray[$i][0]; ?>";
        var claveArea = "<?php echo $dataAreasArray[$i][1]; ?>";
        var maquinasArea = [];
        var maquinasArea2 = [];
        <?php for ($j=0; $j<sizeof($dataMaquinasArray); $j++) { ?>
            var idMaquina = "<?php echo $dataMaquinasArray[$j][0]; ?>";
            if (idMaquina != "589") {
                var idAreaMaquina = "<?php echo $dataMaquinasArray[$j][4]; ?>";
                if (idAreaMaquina == idArea) { 
                    var cantidad = '<?php echo $dataMaquinasArray[$j][2]; ?>';
                    var tiempo = '<?php echo $dataMaquinasArray[$j][3]; ?>';
                    var maquina = "<?php echo $dataMaquinasArray[$j][1]; ?>";
                    var arrayTemp = [];
                    var arrayTemp2 = [];
                    //if ((maquina != null) && (cantidad != null)) {
                        arrayTemp = [maquina, parseInt(cantidad),parseInt(cantidad)];
                        arrayTemp2 = [maquina, tiempo,tiempo];
                        maquinasArea[<?php echo $j; ?>] = arrayTemp;
                        maquinasArea2[<?php echo $j; ?>] = arrayTemp2;
                        //console.log(arrayTemp2);
                    //}
                } 
            }
        <?php } ?>
        temperatures[claveArea] = maquinasArea;
        temperatures2[claveArea] = maquinasArea2;
        maquinasArea = [];
        maquinasArea2 = [];
    <?php  } ?>
<?php  } ?>
     
//limpia el json formado
<?php for ($i=0; $i<sizeof($dataAreasArray)-1;$i++) { ?>
    if (temperatures["<?php echo $dataAreasArray[$i][1]; ?>"].length ==  0) {
        //remueve areas sin maquinas
        delete temperatures['<?php echo $dataAreasArray[$i][1]; ?>'];
        delete temperatures2['<?php echo $dataAreasArray[$i][1]; ?>'];
    } else {
        //remueve elementos nulos
        temperatures["<?php echo $dataAreasArray[$i][1]; ?>"] = temperatures["<?php echo $dataAreasArray[$i][1]; ?>"].filter(function(x) { return x !== null });
        temperatures2["<?php echo $dataAreasArray[$i][1]; ?>"] = temperatures2["<?php echo $dataAreasArray[$i][1]; ?>"].filter(function(x) { return x !== null });
    }//console.log(temperatures);
<?php } ?>

var startYear = 1;
var endYear = 1;
var currentYear = 1;
var colorSet = new am4core.ColorSet();
var chart = am4core.create("chartdiv5", am4charts.RadarChart);
//chart.numberFormatter.numberFormat = "#.0";
chart.hiddenState.properties.opacity = 0;
chart.startAngle = 270 - 180;
chart.endAngle = 270 + 180;
chart.padding(5,15,5,10)
chart.radius = am4core.percent(65);
chart.innerRadius = am4core.percent(40);
// year label goes in the middle
var yearLabel = chart.radarContainer.createChild(am4core.Label);

yearLabel.horizontalCenter = "middle";
yearLabel.verticalCenter = "middle";
yearLabel.fill = am4core.color("#673AB7");
yearLabel.fontSize = 25;
yearLabel.text = "Acámbaro";

// zoomout button
var zoomOutButton = chart.zoomOutButton;
zoomOutButton.dx = 0;
zoomOutButton.dy = 0;
zoomOutButton.marginBottom = 15;
zoomOutButton.parent = chart.rightAxesContainer;
// scrollbar
chart.scrollbarX = new am4core.Scrollbar();
chart.scrollbarX.parent = chart.rightAxesContainer;
chart.scrollbarX.orientation = "vertical";
chart.scrollbarX.align = "center";
chart.scrollbarX.exportable = false;
// vertical orientation for zoom out button and scrollbar to be positioned properly
chart.rightAxesContainer.layout = "vertical";
chart.rightAxesContainer.padding(120, 20, 120, 20);
// category axis
var categoryAxis = chart.xAxes.push(new am4charts.CategoryAxis());
categoryAxis.renderer.grid.template.location = 0;
categoryAxis.dataFields.category = "country";
var categoryAxisRenderer = categoryAxis.renderer;
var categoryAxisLabel = categoryAxisRenderer.labels.template;
categoryAxisLabel.location = 0.5;
categoryAxisLabel.radius = 10;
categoryAxisLabel.relativeRotation = 90;
categoryAxisRenderer.fontSize = 12;
categoryAxisRenderer.minGridDistance = 10;
categoryAxisRenderer.grid.template.radius = -25;
categoryAxisRenderer.grid.template.strokeOpacity = 0.05;
categoryAxisRenderer.grid.template.interactionsEnabled = false;
categoryAxisRenderer.ticks.template.disabled = true;
categoryAxisRenderer.axisFills.template.disabled = true;
categoryAxisRenderer.line.disabled = true;
categoryAxisRenderer.tooltipLocation = 0.5;
categoryAxis.tooltip.defaultState.properties.opacity = 0;

// value axis
var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());
valueAxis.min = -3;
valueAxis.max = 12;
valueAxis.strictMinMax = true;
valueAxis.tooltip.defaultState.properties.opacity = 0;
valueAxis.tooltip.animationDuration = 0;
valueAxis.cursorTooltipEnabled = true;
valueAxis.zIndex = 10;
var valueAxisRenderer = valueAxis.renderer;
valueAxisRenderer.axisFills.template.disabled = true;
valueAxisRenderer.ticks.template.disabled = true;
valueAxisRenderer.minGridDistance = 0;
valueAxisRenderer.grid.template.strokeOpacity = 0.05;

// series
var series = chart.series.push(new am4charts.RadarColumnSeries());
series.columns.template.width = am4core.percent(90);
series.columns.template.strokeOpacity = 0;
series.dataFields.valueY = "value" + currentYear;
series.dataFields.categoryX = "country";
series.tooltipText = "{categoryX}: Paros: {valueY.value}";
// this makes columns to be of a different color, depending on value
series.heatRules.push({ target: series.columns.template, property: "fill", minValue: -3, maxValue: 6, min: am4core.color("#673AB7"), max: am4core.color("#F44336"), dataField: "valueY" });

// cursor
var cursor = new am4charts.RadarCursor();
chart.cursor = cursor;
cursor.behavior = "zoomX";
cursor.xAxis = categoryAxis;
cursor.innerRadius = am4core.percent(40);
cursor.lineY.disabled = true;
cursor.lineX.fillOpacity = 0.2;
cursor.lineX.fill = am4core.color("#000000");
cursor.lineX.strokeOpacity = 0;
cursor.fullWidthLineX = true;


// year slider
var yearSliderContainer = chart.createChild(am4core.Container);
yearSliderContainer.layout = "vertical";
yearSliderContainer.padding(0, 38, 0, 38);
yearSliderContainer.width = am4core.percent(100);
var yearSlider = yearSliderContainer.createChild(am4core.Slider);
yearSlider.events.on("rangechanged", function () {
    updateRadarData(startYear + Math.round(yearSlider.start * (endYear - startYear)));
})

yearSlider.orientation = "horizontal";
yearSlider.start = 0.5;
yearSlider.exportable = false;
chart.data = generateRadarData();

function generateRadarData() {
    var data = [];
    var i = 0;
    for (var continent in temperatures) {
        var continentData = temperatures[continent];
        continentData.forEach(function (country) {
            var rawDataItem = { "country": country[0] }
            for (var y = 2; y < country.length; y++) {
                rawDataItem["value" + (startYear + y - 2)] = country[y];
            }
            data.push(rawDataItem);
        });
        createRange(continent, continentData, i);
        i++;
    }
    return data;
}

function updateRadarData(year) {
    if (currentYear != year) {
        currentYear = year;
        yearLabel.text = String(currentYear);
        series.dataFields.valueY = "value" + currentYear;
        chart.invalidateRawData();
    }
}

function createRange(name, continentData, index) {
    var axisRange = categoryAxis.axisRanges.create();
    axisRange.axisFill.interactionsEnabled = true;
    axisRange.text = name;
    // first country
    axisRange.category = continentData[0][0];
    // last country
    axisRange.endCategory = continentData[continentData.length - 1][0];
    // every 3rd color for a bigger contrast
    axisRange.axisFill.fill = colorSet.getIndex(index * 3);
    axisRange.grid.disabled = true;
    axisRange.label.interactionsEnabled = false;
    axisRange.label.bent = true;
    var axisFill = axisRange.axisFill;
    axisFill.innerRadius = -0.001; // almost the same as 100%, we set it in pixels as later we animate this property to some pixel value
    axisFill.radius = -20; // negative radius means it is calculated from max radius
    axisFill.disabled = false; // as regular fills are disabled, we need to enable this one
    axisFill.fillOpacity = 1;
    axisFill.togglable = true;
    axisFill.showSystemTooltip = true;
    axisFill.readerTitle = "click to zoom";
    axisFill.cursorOverStyle = am4core.MouseCursorStyle.pointer;
    axisFill.events.on("hit", function (event) {
        var dataItem = event.target.dataItem;
        if (!event.target.isActive) {
            categoryAxis.zoom({ start: 0, end: 1 });
        }
        else {
            categoryAxis.zoomToCategories(dataItem.category, dataItem.endCategory);
        }
    })
    // hover state
    var hoverState = axisFill.states.create("hover");
    hoverState.properties.innerRadius = -10;
    hoverState.properties.radius = -25;
    var axisLabel = axisRange.label;
    axisLabel.location = 0.5;
    axisLabel.fill = am4core.color("#ffffff");
    axisLabel.radius = 3;
    axisLabel.relativeRotation = 0;
}
var slider = yearSliderContainer.createChild(am4core.Slider);
slider.start = 1;
slider.exportable = false;
slider.events.on("rangechanged", function () {
    var start = slider.start;
    chart.startAngle = 270 - start * 179 - 1;
    chart.endAngle = 270 + start * 179 + 1;
    valueAxis.renderer.axisAngle = chart.startAngle;
})




//Prepara datos para grafico 6
var startYear = 1;
var endYear = 1;
var currentYear = 1;
var colorSet = new am4core.ColorSet();
var chart2 = am4core.create("chartdiv6", am4charts.RadarChart);
//chart.numberFormatter.numberFormat = "#.0";
chart2.hiddenState.properties.opacity = 0;
chart2.startAngle = 270 - 180;
chart2.endAngle = 270 + 180;
chart2.padding(5,15,5,10)
chart2.radius = am4core.percent(65);
chart2.innerRadius = am4core.percent(40);
// year label goes in the middle
var yearLabel = chart2.radarContainer.createChild(am4core.Label);

yearLabel.horizontalCenter = "middle";
yearLabel.verticalCenter = "middle";
yearLabel.fill = am4core.color("#673AB7");
yearLabel.fontSize = 25;
yearLabel.text = "Acámbaro";

// zoomout button
var zoomOutButton = chart2.zoomOutButton;
zoomOutButton.dx = 0;
zoomOutButton.dy = 0;
zoomOutButton.marginBottom = 15;
zoomOutButton.parent = chart2.rightAxesContainer;
// scrollbar
chart2.scrollbarX = new am4core.Scrollbar();
chart2.scrollbarX.parent = chart2.rightAxesContainer;
chart2.scrollbarX.orientation = "vertical";
chart2.scrollbarX.align = "center";
chart2.scrollbarX.exportable = false;
// vertical orientation for zoom out button and scrollbar to be positioned properly
chart2.rightAxesContainer.layout = "vertical";
chart2.rightAxesContainer.padding(120, 20, 120, 20);
// category axis
    
var categoryAxis2 = chart2.xAxes.push(new am4charts.CategoryAxis());
categoryAxis2.renderer.grid.template.location = 0;
categoryAxis2.dataFields.category = "country";
var categoryAxisRenderer = categoryAxis2.renderer;
var categoryAxisLabel = categoryAxisRenderer.labels.template;
categoryAxisLabel.location = 0.5;
categoryAxisLabel.radius = 10;
categoryAxisLabel.relativeRotation = 90;
categoryAxisRenderer.fontSize = 12;
categoryAxisRenderer.minGridDistance = 10;
categoryAxisRenderer.grid.template.radius = -25;
categoryAxisRenderer.grid.template.strokeOpacity = 0.05;
categoryAxisRenderer.grid.template.interactionsEnabled = false;
categoryAxisRenderer.ticks.template.disabled = true;
categoryAxisRenderer.axisFills.template.disabled = true;
categoryAxisRenderer.line.disabled = true;
categoryAxisRenderer.tooltipLocation = 0.5;
categoryAxis2.tooltip.defaultState.properties.opacity = 0;

// value axis
var valueAxis = chart2.yAxes.push(new am4charts.ValueAxis());
valueAxis.min = -3;
valueAxis.max = 12;
valueAxis.strictMinMax = true;
valueAxis.tooltip.defaultState.properties.opacity = 0;
valueAxis.tooltip.animationDuration = 0;
valueAxis.cursorTooltipEnabled = true;
valueAxis.zIndex = 10;
var valueAxisRenderer = valueAxis.renderer;
valueAxisRenderer.axisFills.template.disabled = true;
valueAxisRenderer.ticks.template.disabled = true;
valueAxisRenderer.minGridDistance = 0;
valueAxisRenderer.grid.template.strokeOpacity = 0.05;

// series
var series = chart2.series.push(new am4charts.RadarColumnSeries());
series.columns.template.width = am4core.percent(90);
series.columns.template.strokeOpacity = 0;
series.dataFields.valueY = "value" + currentYear;
series.dataFields.categoryX = "country";
series.tooltipText = "{categoryX}: Duración(Hrs): {valueY.value}";
// this makes columns to be of a different color, depending on value
series.heatRules.push({ target: series.columns.template, property: "fill", minValue: -3, maxValue: 6, min: am4core.color("#673AB7"), max: am4core.color("#F44336"), dataField: "valueY" });

// cursor
var cursor = new am4charts.RadarCursor();
chart2.cursor = cursor;
cursor.behavior = "zoomX";
cursor.xAxis = categoryAxis2;
cursor.innerRadius = am4core.percent(40);
cursor.lineY.disabled = true;
cursor.lineX.fillOpacity = 0.2;
cursor.lineX.fill = am4core.color("#000000");
cursor.lineX.strokeOpacity = 0;
cursor.fullWidthLineX = true;


// year slider
var yearSliderContainer = chart2.createChild(am4core.Container);
yearSliderContainer.layout = "vertical";
yearSliderContainer.padding(0, 38, 0, 38);
yearSliderContainer.width = am4core.percent(100);
var yearSlider = yearSliderContainer.createChild(am4core.Slider);
yearSlider.events.on("rangechanged", function () {
    updateRadarData2(startYear + Math.round(yearSlider.start * (endYear - startYear)));
})

yearSlider.orientation = "horizontal";
yearSlider.start = 0.5;
yearSlider.exportable = false;
chart2.data = generateRadarData2();

function generateRadarData2() {
    var data = [];
    var i = 0;
    for (var continent in temperatures2) {
        var continentData = temperatures2[continent];
        continentData.forEach(function (country) {
            var rawDataItem = { "country": country[0] }
            for (var y = 2; y < country.length; y++) {
                rawDataItem["value" + (startYear + y - 2)] = country[y];
            }
            data.push(rawDataItem);
        });
        createRange2(continent, continentData, i);
        i++;
    }
    return data;
}

function updateRadarData2(year) {
    if (currentYear != year) {
        currentYear = year;
        yearLabel.text = String(currentYear);
        series.dataFields.valueY = "value" + currentYear;
        chart2.invalidateRawData();
    }
}

function createRange2(name, continentData, index) {
    var axisRange = categoryAxis2.axisRanges.create();
    axisRange.axisFill.interactionsEnabled = true;
    axisRange.text = name;
    // first country
    axisRange.category = continentData[0][0];
    // last country
    axisRange.endCategory = continentData[continentData.length - 1][0];
    // every 3rd color for a bigger contrast
    axisRange.axisFill.fill = colorSet.getIndex(index * 3);
    axisRange.grid.disabled = true;
    axisRange.label.interactionsEnabled = false;
    axisRange.label.bent = true;
    var axisFill = axisRange.axisFill;
    axisFill.innerRadius = -0.001; // almost the same as 100%, we set it in pixels as later we animate this property to some pixel value
    axisFill.radius = -20; // negative radius means it is calculated from max radius
    axisFill.disabled = false; // as regular fills are disabled, we need to enable this one
    axisFill.fillOpacity = 1;
    axisFill.togglable = true;
    axisFill.showSystemTooltip = true;
    axisFill.readerTitle = "click to zoom";
    axisFill.cursorOverStyle = am4core.MouseCursorStyle.pointer;
    axisFill.events.on("hit", function (event) {
        var dataItem = event.target.dataItem;
        if (!event.target.isActive) {
            categoryAxis2.zoom({ start: 0, end: 1 });
        }
        else {
            categoryAxis2.zoomToCategories(dataItem.category, dataItem.endCategory);
        }
    })
    // hover state
    var hoverState = axisFill.states.create("hover");
    hoverState.properties.innerRadius = -10;
    hoverState.properties.radius = -25;
    var axisLabel = axisRange.label;
    axisLabel.location = 0.5;
    axisLabel.fill = am4core.color("#ffffff");
    axisLabel.radius = 3;
    axisLabel.relativeRotation = 0;
}
var slider2 = yearSliderContainer.createChild(am4core.Slider);
slider2.start = 1;
slider2.exportable = false;
slider2.events.on("rangechanged", function () {
    var start = slider2.start;
    chart2.startAngle = 270 - start * 179 - 1;
    chart2.endAngle = 270 + start * 179 + 1;
    valueAxis.renderer.axisAngle = chart2.startAngle;
})


        }); // end am4core.ready()

    </script>    

</body>	
</html>
