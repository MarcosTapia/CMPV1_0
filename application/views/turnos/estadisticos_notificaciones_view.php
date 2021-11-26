<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="utf-8">
    <title>Técnicos en Servicio</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <link href='http://fonts.googleapis.com/css?family=Economica' rel='stylesheet' type='text/css'>
    <!-- Bootstrap -->
    <link href="<?php echo base_url();?>css/bootstrap.css" rel="stylesheet">
  
    <script type="text/javascript" language="javascript" src="<?php echo base_url();?>media/js/jquery.js"></script>
	
    <script type="text/javascript" charset="utf-8">
        function mensaje() {
            setTimeout(function(){ location.reload(); }, 60000);
        }
        
        function solicitar(idUsuario,idUsuarioOperador){
            var link = '<?php echo base_url()?>/index.php/turnos_controller/solicitarAtencion/' + idUsuario + "/" + idUsuarioOperador;
            document.getElementById('enlace' + idUsuario).setAttribute('href', link);            
        }
    </script>
                
</head>
<body onload="mensaje()">
<div class="container">
<?php
    $contRenglon = 3;
    $contGrafico = 1;
    foreach($turnos  as $fila){
        if (($contRenglon % 3) == 0) {
            echo "<div class='row'>";
        }
        echo "<div class='col-md-4'>";
        $posicionContenidoUsuario = array_search($fila->{'idUsuario'},$usuariosArray);
        $elemArrayUsu = explode("|", $posicionContenidoUsuario);
        echo "<h4 style='font-weight: bold;text-align: center'>Servicios de ".$elemArrayUsu[0]."</h4>";
        echo "<div id='chartdiv".$contGrafico."' style='margin-top:10px; margin-left:-10px; height:250px;'></div>";
        echo "<br></div>";
        echo "<script>creaGrafico('".$contGrafico."', 10, 20, 30);</script>";
        if (($contRenglon % 5) == 0) {
            echo "</div>";
            $contRenglon = 2;
        }
        $contRenglon++;
        $contGrafico++;
        //echo $fila->{'idUsuario'}."<br>";
    }

?>

</div> <!-- /container -->

    <?php 
        $a = 10;
        $b = 20;
        $c = 30; 
    ?>

    <!-- Resources -->
    <script src="https://cdn.amcharts.com/lib/4/core.js"></script>
    <script src="https://cdn.amcharts.com/lib/4/charts.js"></script>
    <script src="https://cdn.amcharts.com/lib/4/themes/animated.js"></script>

    <!-- Chart code -->
    <script>
        function creaGrafico(charId,t1,t2,t3){
            am4core.useTheme(am4themes_animated);
            var chart = am4core.create("chartdiv" + charId, am4charts.PieChart);
            chart.data = [ 
                    {
                      "titlesLabels": "FS",
                      "valores": t1
                    }, {
                      "titlesLabels": "SS",
                      "valores": t2
                    }, {
                      "titlesLabels": "TS",
                      "valores": t3
                    }        
            ];
            var pieSeries = chart.series.push(new am4charts.PieSeries());
            pieSeries.dataFields.value = "valores";
            pieSeries.dataFields.category = "titlesLabels";
            pieSeries.slices.template.stroke = am4core.color("#fff");
            pieSeries.slices.template.strokeOpacity = 1;
            pieSeries.hiddenState.properties.opacity = 1;
            pieSeries.hiddenState.properties.endAngle = -90;
            pieSeries.hiddenState.properties.startAngle = -90;
            chart.hiddenState.properties.radius = am4core.percent(0);
        }

        am4core.ready(function() {
            var i = 1;
            var numServ = 10;
            <?php
            $conn = mysqli_connect("localhost", "root", "kikinalba", "cmpv1_0") or die("Database Error"); 
            foreach($turnos as $fila) { 
                $check_data = "SELECT * FROM notificaciones where idUsuarioTecnico=".$fila->{'idUsuario'};
                $run_query = mysqli_query($conn, $check_data) or die("Error");
                $numServicios = mysqli_num_rows($run_query); ?>
                numServ = <?php echo $numServicios; ?>;
                creaGrafico(i, numServ, 0, 0);
                i++;
            <?php } ?>
        });
    </script>    	

</body>	
</html>
