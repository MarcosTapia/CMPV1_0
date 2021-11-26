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

//Llena array Actividades
$actividadesArray = array();
$i = 0;
$sqlAct = "SELECT * from actividades";
$resAct = mysqli_query($conn, $sqlAct) or die("database error:". mysqli_error($conn));	
while( $rowAct = mysqli_fetch_assoc($resAct) ) { 
    $actividadesArray[$rowAct['descripcion_actividad']."|".$i."|"] = "".$rowAct['idActividad'];
    $i++;
} 

$sql;
if ($_GET['opcion'] == 1) {
    $sql = "SELECT * from mantenimientos where idMaquina=".$_GET['idMaquina']." and idResponsable=".$_GET['idResponsable'];
} else {
    $sql = "SELECT * from mantenimientos where idMaquina=".$_GET['idMaquina']." and idResponsable=".$_GET['idResponsable']." and idActividad=".$_GET['idActTemp'];
}
$res = mysqli_query($conn, $sql) or die("database error:". mysqli_error($conn));	
$actividadesRepetidas = array("-2");

$contenidoConsultaTemp = "";
$contenidoConsulta = "";
while( $row = mysqli_fetch_assoc($res) ) { 
    $posicionContenidoActividad = array_search($row['idActividad'],$actividadesArray);
    $elemArrayAct = explode("|", $posicionContenidoActividad);
//    $v = array_search($elemArrayAct[0],$actividadesRepetidas);
//    if ($v == false){
//        array_push($actividadesRepetidas,$elemArrayAct[0]);    
        $contenidoConsultaTemp = $row['fechaMantenimiento']."|".$elemArrayAct[0]."|"; 
        $contenidoConsulta = $contenidoConsulta.$contenidoConsultaTemp."@@";
//    }
}
echo json_encode($contenidoConsulta);
?>

