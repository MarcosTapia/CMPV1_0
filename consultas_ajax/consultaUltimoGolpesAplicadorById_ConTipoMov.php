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
$respuesta = 0;
$sqlMantto = "SELECT * from movimientosaplicadores where idAplicador=".$_GET['idAplicador']." ORDER BY idMov DESC";
$resMantto = mysqli_query($conn, $sqlMantto) or die("database error:". mysqli_error($conn));	
while( $rowMantto = mysqli_fetch_assoc($resMantto) ) { 
    $respuesta = $rowMantto['ciclos']."|".$rowMantto['tipoMovimiento']."|";
    break;
} 
echo json_encode($respuesta);
?>

