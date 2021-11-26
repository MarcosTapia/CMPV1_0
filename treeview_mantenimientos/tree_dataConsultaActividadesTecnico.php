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
$sql = "SELECT * from mantenimientos where idMaquina=".$_GET['idMaquina']." and trim(substr(fechaMantenimiento,1,9)) = '".$_GET['fechaMantenimiento']."' "
        ."and idResponsable=".$_GET['idResponsable'];
//$sql = "SELECT * from mantenimientos where idMaquina=".$_GET['idMaquina']." and trim(substr('fechaMantenimiento',1,9)) = '"
//        .$_GET['fechaMantenimiento']."' and idResponsable=".$_GET['idResponsable'];
$res = mysqli_query($conn, $sql) or die("database error:". mysqli_error($conn));	
$actividadesRepetidas = array("-2");
while( $row = mysqli_fetch_assoc($res) ) { 
    $posicionContenidoActividad = array_search($row['idActividad'],$actividadesArray);
    $elemArrayAct = explode("|", $posicionContenidoActividad);
    $v = array_search($elemArrayAct[0],$actividadesRepetidas);
    if ($v == false){
        array_push($actividadesRepetidas,$elemArrayAct[0]);    
        $row['text'] = "<p style='margin-top:-15px;margin-bottom:-3px;' onclick='muestraInfoSemanas(".$row['idActividad'].",".$_GET['idMaquina'].",\"".$_GET['fechaMantenimiento']."\")'><img src='http://192.168.98.200/cmpv1_0/images/sistemaicons/adelante.ico' />&nbsp;&nbsp;&nbsp;".$elemArrayAct[0]."</p>"; 
        $row['name'] = $elemArrayAct[0];
        $data[] = $row;
    }
}

echo json_encode($data);
?>

