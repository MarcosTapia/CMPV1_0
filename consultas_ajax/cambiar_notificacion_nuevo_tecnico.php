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
    $sql = "update notificaciones set idUsuarioTecnico=".$_GET['idUsuarioTecnico']." where id=".$_GET['idNotificacion'];
    $respuesta;
    if ($conn->query($sql) === TRUE) {
        $respuesta = 1;
    } else {
        $resouesta = 0;
    }
    $conn->close();
    echo json_encode($resouesta);
?>

