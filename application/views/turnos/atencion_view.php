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
            setTimeout(function(){ location.reload(); }, 25000);
        }
        
        function solicitarAtencion(idUsuarioSolicitante,tipoUsuario) {
            var idArea;
            if (tipoUsuario == 1) {
                idArea = document.getElementById('idArea').value;
                if (idArea == "") {
                    alert("Debes seleccionar una area para atender.");
                    return;
                }
            } else {
                idArea = "-1";
            }
            var link = '<?php echo base_url()?>/index.php/turnos_controller/solicitarAtencion/' + idUsuarioSolicitante + '/' + tipoUsuario + '/' + idArea;
            document.getElementById('enlaceSolicitud').setAttribute('href', link);
        }
        
        function solicitudesActuales(idUsuarioSolicitante,tipoUsuario) {
            var link = "";
            if (tipoUsuario == 1) {
                link = '<?php echo base_url()?>/index.php/turnos_controller/solicitudesEnCurso/' + idUsuarioSolicitante + '/' + tipoUsuario;
            } else {
                link = '<?php echo base_url()?>/index.php/turnos_controller/solicitudesEnCurso/' + idUsuarioSolicitante + '/' + tipoUsuario;
            }
            document.getElementById('enlaceSolicitudesEnCurso').setAttribute('href', link);
        }
        
        function enviarSolicitud(idUsuario,idUsuarioOperador,idNotificacion,mensajeToTecnico,idMaquina){
            var notificacion = "";
            notificacion = idUsuario + "|" + idUsuarioOperador + "|" +  idNotificacion + "|" + mensajeToTecnico + "|" + idMaquina + "|";
            jQuery.ajax({
                url: "<?php echo base_url(); ?>/consultas_ajax/grabaNotificacionParaTecnico2.php?notificacion=" + notificacion,
                cache: false,
                contentType: "text/html; charset=UTF-8",
                success: function(response){
                    location.reload();
                },
                error: function(response){
                    alert("Error");
                }
            });	
        }
    </script>
                
</head>
<body onload="mensaje()">
<div class="container">

    <div class="row">
        <div class="col-md-12">
            <h3 style="text-align: center" style='background: #00cc00'>Operaciones </h3>
            <table><tr>
                <td>
                <?php 
                    if ($permisos == "Supervisor") { ?>
                    <a style="margin-left: 10px;" id='enlaceSolicitud' href='' onclick='solicitarAtencion("<?php echo $idUsuarioOperador; ?>","1");' class='btn btn-primary'>Atención</a>
                    <?php } if ($permisos == "Lider") { ?>
                    <a style="margin-left: 10px;" id='enlaceSolicitud' href='' onclick='solicitarAtencion("<?php echo $idUsuarioOperador; ?>","2");' class='btn btn-primary'>Atención</a>
                <?php } ?>
                </td>
                <td>
                <?php
                if ($permisos == "Supervisor") { ?>
                    <?php 
                    if ($permisos == "Supervisor") { 
                        $conn = mysqli_connect("localhost", "root", "kikinalba", "cmpv1_0") or die("Database Error");
                        $check_data = "select * from areas";
                        $run_query = mysqli_query($conn, $check_data) or die("Error");
                        if(mysqli_num_rows($run_query) > 0){ ?>
                        <select style="margin-left: 10px; width: 200px;" class="form-control" name="idArea" id="idArea" required>
                                    <option value="">Area de Solicitud...</option>
                                    <?php
                                    while($row = mysqli_fetch_assoc($run_query)) { 
                                        if (($row['idArea'] != 17) && ($row['idArea'] != 6)) { //si el area no es la general y almacen
                                            echo "<option value=".$row['idArea'].">".$row['descripcion']."</option>";
                                        }
                                     } 
                                     ?>
                            </select>
                     <?php  
                        }
                     } 
                } ?>
                </td>
                <td>
                <?php
                if ($permisos == "Supervisor") { ?>
                    <a style="margin-left: 25px;" id='enlaceSolicitudesEnCurso' href='' onclick='solicitudesActuales("<?php echo $idUsuarioOperador; ?>","1");' class='btn btn-primary'>En curso</a>
                <?php } if ($permisos == "Lider") { ?>
                    <a style="margin-left: 25px;" id='enlaceSolicitudesEnCurso' href='' onclick='solicitudesActuales("<?php echo $idUsuarioOperador; ?>","2");' class='btn btn-primary'>En curso</a>
                <?php } ?>
                </td>
            </tr></table>
        </div>
    </div>    
    
<div class="row">
<div class="col-md-12">
    
    <h3 style="text-align: center" style='background: #00cc00'>Relación de Técnicos </h3>
    <?php
    $conn = mysqli_connect("localhost", "root", "kikinalba", "cmpv1_0") or die("Database Error");
    //$check_data = "SELECT * FROM turnos inner join usuarios on turnos.idUsuario = usuarios.idUsuario order by status";
    $check_data;
    if ($permisos == "Supervisor") { 
        $check_data = "call actualizaStatusTecnico(1,".$idUsuarioOperador.")";
    } else {
        $check_data = "call actualizaStatusTecnico(2,".$idUsuarioOperador.")";
    }
    $run_query = mysqli_query($conn, $check_data) or die("Error");
    if(mysqli_num_rows($run_query) > 0){
        while($row = mysqli_fetch_assoc($run_query)) { 
            //echo "area1: ".$row["idArea1"]."area2: ".$row["idArea2"]."area3: ".$row["idArea3"]."area4: ".$row["idArea4"]."area5: ".$row["idArea5"];
            $inicial1 = $row["apellido_paterno"][0];
            $inicial2 = $row["apellido_materno"][0];
            $nombre = $row["apellido_paterno"]." ".$row["apellido_materno"]." ".$row["nombre"];?>			
            <div class="col-md-3">
                <div class="panel panel-default" style="border: 2px solid blue">
                    <?php 
                    if ($row["status"] == 0) { ?>
                        <div class="panel-heading">Turno: <?php echo $row["hora_entrada"]." - ",$row["hora_salida"] ?></div>
                        <div class="panel-body">Técnico: 
                            <?php echo " ".$nombre; ?>
                        </div>
                        <?php
                        //antes de ponerlo disponible verifico si no hay notificaciones encoladas
                        $conn = mysqli_connect("localhost", "root", "kikinalba", "cmpv1_0") or die("Database Error");
                        $sql = "select * from notificaciones as n inner join maquinas as m on n.idMaqui=m.idMaquina where n.idUsuarioTecnico=1 and "
                                . "DATE(n.fechaEnvioNotificacionAlTecnico) = DATE(NOW()) and (m.idArea=".$row["idArea1"]." or m.idArea=".$row["idArea2"]." or "
                                . "m.idArea=".$row["idArea3"]." or m.idArea=".$row["idArea4"]." or m.idArea=".$row["idArea5"].")";
                        //$sql = "select * from notificaciones where idUsuarioTecnico=1 and DATE(fechaEnvioNotificacionAlTecnico) = DATE(NOW())";
                                    
                        $result = mysqli_query($conn, $sql) or die("Error");
                        $idNotificacion = 0;
                        $idUsuarioOperador = 0;
                        $idTecnicoElegido = $row["idUsuario"];
                        $mensajeToTecnico = ""; 
                        $idMaquina = 0;
                        if(mysqli_num_rows($result) > 0){
                            while($row2 = mysqli_fetch_assoc($result)) { 
                                $idNotificacion = $row2["id"];
                                $idUsuarioOperador = $row2["idUsuarioOperador"];
                                $mensajeToTecnico = $row2["mensajeOperador"];
                                $idMaquina = $row2["idMaqui"];
                                break;
                            }                       
                            echo "<div class='panel-footer' style='background:orange; color:black;'>Status: Reasignando...";
                            echo "</div>";
                            echo "<script>enviarSolicitud('".$idTecnicoElegido."','".$idUsuarioOperador."','".$idNotificacion."','".$mensajeToTecnico."','".$idMaquina."');</script>";
                        } else {
                            echo "<div class='panel-footer' style='background:#00cc00; color:black;'>Status: Disponible";
                            echo "</div>";
                        }
                    } else { ?>
                        <div class="panel-heading">Turno: <?php echo $row["hora_entrada"]." - ",$row["hora_salida"] ?></div>
                        <div class="panel-body">Técnico: 
                            <?php echo " ".$nombre; ?>
                        </div>
                        <?php if ($row["status"] == 1) {
                            echo "<div class='panel-footer' style='background:orange; color:black;'>Status: Ocupado";
                            echo "</div>";
                        } else {
                            echo "<div class='panel-footer' style='background:#2271b3; color:black;'>Status: Salida Comedor";
                            echo "</div>";
                        }
                    } ?>
                </div>
            </div> <?php
         } //while
    } //if
    //mysqli_close($conn);
    ?>
    
    <!-- MUESTRA LOS QUE NO ESTAN EN TURNO -->
    <br>
    <br>
    <?php /*
    $conn = mysqli_connect("localhost", "root", "kikinalba", "cmpv1_0") or die("Database Error");
    $check_data = "call muestraTecnicosNotInPlant()";
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
                        echo "<div class='panel-footer' style='background:#C13030; color:white;'>Status: No disponible</div>";
                    ?>
                </div>
            </div> <?php
         } //while
    } //if
     */
    ?>
     
    
    
    
    <?php echo "<script>mensaje();</script>"; ?>
</div> <!-- /division renglon en 12-->
</div> <!-- / renglon-->


</div> <!-- /container -->
</body>	
</html>
