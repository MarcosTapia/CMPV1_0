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
    
    $sql3 = "select * from turnos where idUsuario=".$_GET['idTecnico']." and CURTIME() >= TIME(hora_entrada) and CURTIME() <= TIME(hora_salida)";
    $respuesta = 0;
    if ($conn->query($sql3)->num_rows <= 0) {
        $respuesta = 1;
    } else {
        //verifica si esta en turno
        $sql2 = "select * from notificaciones where idUsuarioTecnico=".$_GET['idTecnico']." and DATE(fechaEnvioNotificacionAlTecnico)=DATE(NOW())";
        if ($conn->query($sql2)->num_rows > 0) {
            $respuesta = 1;
        } else {
            $sql3 = "update turnos set status=0 where idUsuario=".$_GET['idTecnico'];
            mysqli_query($conn, $sql3);
        }
    }
    /*
    $sql = "select * from notificaciones where idUsuarioTecnico=".$_GET['idTecnico']." and DATE(fechaEnvioNotificacionAlTecnico)=DATE(NOW())";
    $respuesta = 0;
    if ($conn->query($sql)->num_rows > 0) {
        $respuesta = 1;
    } else {
        //verifica si esta en turno
        $sql2 = "select * from turnos where idUsuario=".$_GET['idTecnico']." and CURTIME() >= TIME(hora_entrada) and CURTIME() <= TIME(hora_salida)";
        if ($conn->query($sql2)->num_rows > 0) {
            $sql3 = "update turnos set status=0 where idUsuario=".$_GET['idTecnico'];
            mysqli_query($conn, $sql3);
        } else {
            $respuesta = 1;
        }
    }
     */
    $conn->close();
    echo json_encode($respuesta);
?>

