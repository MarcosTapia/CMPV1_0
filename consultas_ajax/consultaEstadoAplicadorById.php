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
//obtiene el ultimo registro de movimientos
//$respuesta1 = 0;
$respuesta1 = $_GET['ciclosRegistrados'];
$respuesta2 = 0;
$respuesta3 = "";
/*
$sqlMantto = "SELECT * from movimientosaplicadores where idAplicador=".$_GET['idAplicador']." ORDER BY idMov DESC";
$resMantto = mysqli_query($conn, $sqlMantto) or die("database error:". mysqli_error($conn));	
while( $rowMantto = mysqli_fetch_assoc($resMantto) ) { 
    $respuesta1 = $rowMantto['ciclos'];
    break;
}
 */

//obtiene el rango de ciclos del aplicador
$sqlMantto = "SELECT * from aplicadores where idAplicador=".$_GET['idAplicador'];
$resMantto = mysqli_query($conn, $sqlMantto) or die("database error:". mysqli_error($conn));	
while( $rowMantto = mysqli_fetch_assoc($resMantto) ) { 
    $respuesta2 = $rowMantto['no_ciclos'];
    break;
}

$diferencia = $_GET['ciclosVerificar'] - $respuesta1;
if ($diferencia >= ($respuesta2 - 5000)) {
    $diferencia2 = $diferencia - ($respuesta2 - 5000);
    $respuesta3 = "Si|".$diferencia2."|";
} else {
    $diferencia2 = ($respuesta2 - 5000) - $diferencia;
    $respuesta3 = "No|".$diferencia2."|";
}
echo json_encode($respuesta3);
?>

