<?php
    $servername = "localhost";
    $username = "root";
    $password = "kikinalba";
    $dbname = "cmpv1_0";
    $conn = mysqli_connect($servername, $username, $password, $dbname) or die("Connection failed: " . mysqli_connect_error());
    if (mysqli_connect_errno()) {
        printf("Connect failed: %s\n", mysqli_connect_error());
        exit();
    }
    $dt = new DateTime("now", new DateTimeZone('America/Mexico_City'));
    $fecha = $dt->format("Y-m-d H:i:s"); 
    $sql = "update notificaciones set estado=4, fechaFinAtencion = '".$fecha."', calificacionAtencion=".$_GET['calificacionAtencion'].", "
            ."comentarios='".$_GET['comentarios']."' where id=".$_GET['idNotificacion'];
    $respuesta;
    if ($conn->query($sql) === TRUE) {
        //obitene nombre completo de usuario operador(lider)
        //$sql1 = "SELECT TIMEDIFF(`fechaInicioAtencion`,`fechaFinAtencion`) as ta from notificaciones where id=".$_GET['idNotificacion'];
        //$sql1 = "SELECT TIMEDIFF(`fechaFinAtencion`,`fechaInicioAtencion`) as ta from notificaciones where id=".$_GET['idNotificacion'];
        $sql1 = "SELECT ADDTIME(TIMEDIFF(`fechaFinAtencion`,`fechaInicioAtencion`),`tiempoAtencionTemp`) as ta from notificaciones where id=".$_GET['idNotificacion'];

        $res1 = mysqli_query($conn, $sql1) or die("database error:". mysqli_error($conn));
        //$tiempoAtencion = "";
        $tiempoAtencion = "";
        while($row1 = mysqli_fetch_assoc($res1) ) { 
            $tiempoAtencion = $row1['ta'];
        }
        
        //suma tiempoTemporal
        
        
        $sqlFinal = "update notificaciones set tiempoAtencion='".$tiempoAtencion."' where id=".$_GET['idNotificacion'];
        if ($conn->query($sqlFinal) === TRUE) {
            //actualiza el status del usuario en el turno a 0(disponible)
            $sql = "update turnos set status=0 where idUsuario=(select idUsuarioTecnico from notificaciones where id=".$_GET['idNotificacion'].")";
            if ($conn->query($sql) === TRUE) {
                $sql = "update layout_maq_status set status=1 where idNotificacion=".$_GET['idNotificacion'];
                mysqli_query($conn, $sql);
                $resouesta = 1;
            } else {
                $resouesta = 0;
            }
        } else {
            $respuesta = 0;
        }
    } else {
        $respuesta = 0;
    }
    //SELECT TIMEDIFF(`fechaEnvioNotificacionAlOperador`,`fechaEnvioNotificacionAlTecnico`) from notificaciones
    //para calcular tiempo de atencion
    //SELECT  SEC_TO_TIME( SUM( TIME_TO_SEC( `tiempoAtencion` ) ) ) AS timeSum  FROM notificaciones    
    $conn->close();
    echo json_encode($respuesta);
?>

