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
$fechaIngreso = $dt->format("Y-m-d H:i:s");
$sql = "UPDATE mantenimientos SET condicion_maquina='ATENDIDA', fechaMantenimiento=concat(fechaMantenimiento,' ','".$fechaIngreso."') where idFechaMantenimiento=".$_GET['idFechaMantenimiento'];
if (mysqli_query($conn, $sql)) {
  $data[] = "'ok':'Actualización Exitosa'";
} else {
  $data[] = "'error':'Error al actualizar'";
}
mysqli_close($conn);

echo json_encode($data);
?>

