<?php
/* Database connection start */
$servername = "localhost";
$username = "root";
$password = "kikinalba";
$dbname = "cmpv1_0";
$conn = mysqli_connect($servername, $username, $password, $dbname) or die("Connection failed: " . mysqli_connect_error());
if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}

$sql = "SELECT distinct(`nombre_maquina`) FROM `maquinas` order by nombre_maquina";
$res = mysqli_query($conn, $sql) or die("database error:". mysqli_error($conn));	
$numerosMaquinas = array();
$semanasMantenimientos = array();
$informacion = array();
while( $row = mysqli_fetch_assoc($res) ) { 
  $row['text']=$row['nombre_maquina']; 
  $row['name']=$row['nombre_maquina']; 
    $sql2 = "SELECT distinct(`numero_maquina`),idMaquina,nombre_maquina FROM `maquinas` where nombre_maquina = '".$row['nombre_maquina']."' order by numero_maquina + 0";//para castear el numero_maquina a entero
    $res2 = mysqli_query($conn, $sql2) or die("database error:". mysqli_error($conn));	
    while( $row2 = mysqli_fetch_assoc($res2) ) { 
        $row2['text']="<h5 style='margin-left: 42px;margin-top: -15px;margin-bottom: 5px;' onclick='muestraInfo(1,".$row2['idMaquina'].",\"".$row2['nombre_maquina']."\",this)'>".$row2['numero_maquina']."</h5>"; 
        $row2['name']=$row2['idMaquina'];
        array_push($numerosMaquinas,$row2);
    }
  $row['nodes']=$numerosMaquinas; 
  $dataOrigen[] = $row;
  $numerosMaquinas = [];
}
echo json_encode($dataOrigen);
?>

