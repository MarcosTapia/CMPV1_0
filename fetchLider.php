<?php
    include("connect.php");
    $idUsuarioOperador = $_POST["idUsuarioOperador"];
//    $query = "select * from notificaciones as noti inner join usuariosoperadores as usu "
//            . "on noti.idUsuarioOperador = usu.idUsuarioOperador where date(substr(noti.fechaEnvioNotificacionAlOperador,1,10)) = "
//            . "date(now()) and noti.estado=2 and noti.idUsuarioOperador=".$idUsuarioOperador;
    $query = "select * from notificaciones as noti inner join usuarios as usu "
            . "on noti.idUsuarioTecnico = usu.idUsuario where date(substr(noti.fechaEnvioNotificacionAlOperador,1,10)) = "
            . "date(now()) and noti.estado=2 and noti.idUsuarioOperador=".$idUsuarioOperador;
    $result = mysqli_query($connect, $query);
    $output = '';//$row["nombre"].' '.$row["apellido_paterno"].' '.$row["apellido_materno"]
    if(mysqli_num_rows($result) > 0) {
            while($row = mysqli_fetch_array($result)) {
                if ($row["mensajeTecnico"] == "null") {
                    $row["mensajeTecnico"]="";
                }
                    $output .= '
                            <strong>Técnico: '.$row["nombre"].' '.$row["apellido_paterno"].' '.$row["apellido_materno"].'</strong><br />
                            <strong>Mensaje: '.$row["mensajeTecnico"].'</strong><br />
                            <small><em>Fecha: '.$row["fechaEnvioNotificacionAlOperador"].'</em></small><br>
                    ';
            }
    } else {
            $output .= '-1';
    }
    $data = array('notification' => $output,'unseen_notification' => 0);
//    $update_query = "UPDATE notificaciones SET estado=3 where date(substr(fechaEnvioNotificacionAlOperador,1,10)) = "
//            . "date(now()) and estado=2 and idUsuarioOperador=".$idUsuarioOperador;
//    mysqli_query($connect, $update_query);
//    $conn->close();
    echo json_encode($data);
?>

