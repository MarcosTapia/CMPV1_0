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
    $sql = "update notificaciones set pendiente_lider=0 where id=".$_GET['idNotificacion'];
    $respuesta;
    if ($conn->query($sql) === TRUE) {
        $respuesta = 1;
    } else {
        $resouesta = 0;
    }
    $conn->close();
    echo json_encode($resouesta);
?>

