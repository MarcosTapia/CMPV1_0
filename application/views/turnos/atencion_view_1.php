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
            setTimeout(function(){ location.reload(); }, 30000);
        }
        
        function solicitarAtencion(idUsuarioSolicitante,tipoUsuario) {
            var link = '<?php echo base_url()?>/index.php/turnos_controller/solicitarAtencion/' + idUsuarioSolicitante + '/' + tipoUsuario;
            document.getElementById('enlaceSolicitud').setAttribute('href', link);
        }
        /*
        function solicitar(idUsuarioTecnico,idUsuarioSolicitante){
            var link = '<?php echo base_url()?>/index.php/turnos_controller/solicitarAtencion/' + idUsuarioTecnico + "/" + idUsuarioSolicitante;
            document.getElementById('enlace' + idUsuario).setAttribute('href', link);            
        }
        */
    </script>
                
</head>
<body onload="mensaje()">
<div class="container">
<div class="row">
<div class="col-md-12">
    <h3 style="text-align: center" style='background: #00cc00'>Relación de Técnicos </h3>
    <div>
        <?php 
        if ($permisos == "Supervisor") { ?>
        <a id='enlaceSolicitud' href='' onclick='solicitarAtencion("<?php echo $idUsuarioOperador; ?>","1");' class='btn btn-primary'>Solicitar Atención</a>
        <?php } if ($permisos == "Lider") { ?>
        <a id='enlaceSolicitud' href='' onclick='solicitarAtencion("<?php echo $idUsuarioOperador; ?>","2");' class='btn btn-primary'>Solicitar Atención</a>
        <?php } ?>
        <input type="button" class="btn btn-primary" value="Solicitudes en curso" />
        <br><br>
    </div>
    <br>
    <?php
    $conn = mysqli_connect("localhost", "root", "kikinalba", "cmpv1_0") or die("Database Error");
    $check_data = "SELECT * FROM turnos inner join usuarios on turnos.idUsuario = usuarios.idUsuario order by status";
    $run_query = mysqli_query($conn, $check_data) or die("Error");
    if(mysqli_num_rows($run_query) > 0){
        while($row = mysqli_fetch_assoc($run_query)) { 
            $inicial1 = $row["apellido_paterno"][0];
            $inicial2 = $row["apellido_materno"][0];
            $nombre = $row["apellido_paterno"]." ".$row["apellido_materno"]." ".$row["nombre"];?>			
            <div class="col-md-3">
                <div class="panel panel-default" style="border: 2px solid blue">
                    <div class="panel-heading">Turno: <?php echo $row["hora_entrada"]." - ",$row["hora_salida"] ?></div>
                    <div class="panel-body">Técnico: 
                        <?php echo " ".$nombre; ?>
                    </div>
                    <?php 
                        $dt = new DateTime("now", new DateTimeZone('America/Mexico_City'));
                        $hora = $dt->format("H:i"); 
                        //trae los tecnicos del turno actual
                        if (((date('H:i', strtotime($hora)) >= date('H:i', strtotime($row["hora_entrada"]))) && (date('H:i', strtotime($hora)) <= date('H:i', strtotime($row["hora_salida"]))))) {
                            if ($row["status"] == 0) {
                                echo "<div class='panel-footer' style='background:#00cc00; color:black;'>Status: Disponible";
                                echo "<a id='enlace".$row["idUsuario"]."' href='' onclick='solicitar(".$row["idUsuario"].",".$idUsuarioOperador.");'>";
                                echo "&nbsp;&nbsp;&nbsp;<input type='button' class='btn btn-primary' value='Solicitar' />";
                                echo "</a>";
                                echo "</div>";
                            } else {
                                if ($row["status"] == 1) {
                                    echo "<div class='panel-footer' style='background:orange; color:black;'>Status: Ocupado";
                                    echo "</div>";
                                }
                            }
                        } else {
                            if (((date('H:i', strtotime($hora)) <= date('H:i', strtotime($row["hora_entrada"]))) && (date('H:i', strtotime($hora)) <= date('H:i', strtotime($row["hora_salida"])))
                                    && (date('H:i', strtotime($hora)) <= date('H:i', strtotime("07:00"))))) {
                                if ($row["status"] == 0) {
                                    echo "<div class='panel-footer' style='background:#00cc00; color:black;'>Status: Disponible";
                                    echo "<a id='enlace".$row["idUsuario"]."' href='' onclick='solicitar(".$row["idUsuario"].",".$idUsuarioOperador.");'>";
                                    echo "&nbsp;&nbsp;&nbsp;<input type='button' class='btn btn-primary' value='Solicitar' />";
                                    echo "</a>";
                                    echo "</div>";
                                } else {
                                    if ($row["status"] == 1) {
                                        echo "<div class='panel-footer' style='background:orange; color:black;'>Status: Ocupado";
                                        echo "</div>";
                                    }
                                }
                            } else {
                                if (((date('H:i', strtotime($hora)) >= date('H:i', strtotime($row["hora_entrada"]))) && (date('H:i', strtotime($hora)) >= date('H:i', strtotime($row["hora_salida"])))) && (date('H:i', strtotime($hora)) <= date('H:i', strtotime("23:59")))) {
                                    if ($row["status"] == 0) {
                                        echo "<div class='panel-footer' style='background:#00cc00; color:black;'>Status: Disponible";
                                        echo "<a id='enlace".$row["idUsuario"]."' href='' onclick='solicitar(".$row["idUsuario"].",".$idUsuarioOperador.");'>";
                                        echo "&nbsp;&nbsp;&nbsp;<input type='button' class='btn btn-primary' value='Solicitar' />";
                                        echo "</a>";
                                        echo "</div>";
                                    } else {
                                        if ($row["status"] == 1) {
                                            echo "<div class='panel-footer' style='background:orange; color:black;'>Status: Ocupado";
                                            echo "</div>";
                                        }
                                    }
                                } else {
                                    echo "<div class='panel-footer' style='background:#C13030; color:white;'>Status: No disponible";
                                    echo "</div>";
                                }
                            }
                        }
                    ?>
                </div>                                
            </div>
            <?php		
         } //while
    } //if
    ?>
    <?php echo "<script>mensaje();</script>"; ?>
</div> <!-- /division renglon en 12-->
</div> <!-- / renglon-->
</div> <!-- /container -->
</body>	
</html>
