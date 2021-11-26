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

//OBTIENE PARAMETRO DE CICLOS DEL APLICADOR
$ciclosConf = 0;
$sqlAplic = "SELECT * from aplicadores where idAplicador=".$_GET['idAplicador'];
$resAplic = mysqli_query($conn, $sqlAplic) or die("database error:". mysqli_error($conn));	
while( $rowAplic = mysqli_fetch_assoc($resAplic) ) { 
    $ciclosConf = $rowAplic['no_ciclos'];
} 


$ultimoCiclos = 0;
$sqlMantto = "SELECT * from movimientosaplicadores where idAplicador=".$_GET['idAplicador']." ORDER BY ciclos DESC";
$resMantto = mysqli_query($conn, $sqlMantto) or die("database error:". mysqli_error($conn));	
while( $rowMantto = mysqli_fetch_assoc($resMantto) ) { 
    $ultimoCiclos = $rowMantto['ciclos'];
    break;
} 

$tablaCienArray = array();
for($i=0;$i<99;$i++) {
    $tablaCienArray[$i] = ($i+1) * 100000;
}
$tablaCienArray[99] = 9999999;

$tablaTreintaArray = array();
for($i=0;$i<333;$i++) {
    $tablaTreintaArray[$i] = ($i+1) * 30000;
}
$tablaTreintaArray[333] = 9999999;

$aplicaMantenimiento = 0;
$diferenciaCiclos = 0;
$ciclosActual = $_GET['contadorActual'];
//valida rango para deterinar si toca mantenimiento
if ($ciclosConf == 100000) {
    $diferenciaCiclos = $ciclosActual - $ultimoCiclos;
    if ((($diferenciaCiclos >= 95000) && ($diferenciaCiclos <= 105000)) || ($diferenciaCiclos >= $ciclosConf) ){
        $aplicaMantenimiento = 1;
    }
}

if ($ciclosConf == 30000) {
    $diferenciaCiclos = $ciclosActual - $ultimoCiclos;
    if ((($diferenciaCiclos >= 25000) && ($diferenciaCiclos <= 35000)) || ($diferenciaCiclos >= $ciclosConf)){
        $aplicaMantenimiento = 1;
    }
}

echo json_encode($aplicaMantenimiento);
?>

