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
$movimientoArray = explode("|",$_GET['movimiento']);

//ultimo golpes registrados del aplicador en cuestion
$ultimoCiclos = 0;
$sqlMantto = "SELECT * from movimientosaplicadores where idAplicador=".$movimientoArray[0]." ORDER BY ciclos DESC limit 1";
$resMantto = mysqli_query($conn, $sqlMantto) or die("database error:". mysqli_error($conn));	
while( $rowMantto = mysqli_fetch_assoc($resMantto) ) { 
    $ultimoCiclos = $rowMantto['ciclos'];
} 

$sql = "";
if ($movimientoArray[6] == "2") {
    $sql = "insert into movimientosaplicadores(idAplicador ,idUsuario,fecha,observacionesMantto,verificacionesMantto,"
            . "ciclos,status,tipoMovimiento,verificacionesTipoMov,observacionesTipoMov) values(".$movimientoArray[0].",".$movimientoArray[1].",'".$fecha."','','',"
            .$ultimoCiclos.",'','".$movimientoArray[3]."','".$movimientoArray[4]."','".$movimientoArray[5]."')";
}
if ($movimientoArray[6] == "1") {
    $sql = "insert into movimientosaplicadores(idAplicador ,idUsuario,fecha,observacionesMantto,verificacionesMantto,"
            . "ciclos,status,tipoMovimiento,verificacionesTipoMov,observacionesTipoMov) values(".$movimientoArray[0].",".$movimientoArray[1].",'".$fecha."','','',"
            .$movimientoArray[2].",'','".$movimientoArray[3]."','".$movimientoArray[4]."','".$movimientoArray[5]."')";
}
if ($conn->query($sql) === TRUE) {
    echo json_encode(1);
} else {
    echo json_encode(0);
}
$conn->close();
?>

