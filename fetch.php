<?php
    if(isset($_POST["view"])){
        include("connect.php");
        $idTecnico = $_POST["idTecnico"];
        $query = "select * from notificaciones inner join usuariosoperadores on notificaciones.idUsuarioOperador=usuariosoperadores.idUsuarioOperador "
                . "where date(substr(notificaciones.fechaEnvioNotificacionAlTecnico,1,10)) = date(now()) and notificaciones.estado=0 and notificaciones.idUsuarioTecnico=".$idTecnico;
        $result = mysqli_query($connect, $query);
        $output = '';
        if(mysqli_num_rows($result) > 0) {
            while($row = mysqli_fetch_array($result)) {
                $output .= '
                        <li>
                        <a href="#">
                        <strong>Autor: '.$row["nombre"].' '.$row["apellido_paterno"].' '.$row["apellido_materno"].'</strong><br />
                        <strong>Mensaje: '.$row["mensajeOperador"].'</strong><br />
                        <small><em>Fecha: '.$row["fechaEnvioNotificacionAlTecnico"].'</em></small>
                        </a>
                        <input type="button" value="Responder" class="btn btn-primary center-block" onclick="enviaAlertaLider('.$row["id"].');"/>
                </li>
                
                <li class="divider"></li>
                ';
            }
        } else {
            $output .= '<li><a href="#" class="text-bold text-italic">No hay notificaciones</a></li>';
        }
        $query_1 = "select * from notificaciones where estado=0 and idUsuarioTecnico=".$idTecnico." and date(substr(notificaciones.fechaEnvioNotificacionAlTecnico,1,10)) = date(now())";
        $result_1 = mysqli_query($connect, $query_1);
        $count = mysqli_num_rows($result_1);
        $data = array('notification' => $output,'unseen_notification' => $count);
        if($_POST["view"] != '') {
                $update_query = "UPDATE notificaciones SET estado=1 WHERE estado=0";
                mysqli_query($connect, $update_query);
        }
        echo json_encode($data);
    }
?>

