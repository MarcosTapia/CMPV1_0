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
    $notificacionArray = explode("|",$_GET['notificacion']);    
    $sql = "update notificaciones set estado = 2, fechaEnvioNotificacionAlOperador = '".$fecha."', mensajeTecnico = '".$notificacionArray[0]."' where id=".$notificacionArray[1];
    $respuesta;
    if ($conn->query($sql) === TRUE) {
        //actualiza el status del usuario en el turno a 1(ocupado)
        $sql = "update turnos set status=1 where idUsuario=(select idUsuarioTecnico from notificaciones where id=".$notificacionArray[1].")";
        if ($conn->query($sql) === TRUE) {
            $resouesta = 1;
        } else {
            $resouesta = 0;
        }
        //$resouesta = 1;
    } else {
        $resouesta = 0;
    }
    $conn->close();
    echo json_encode($resouesta);
?>

