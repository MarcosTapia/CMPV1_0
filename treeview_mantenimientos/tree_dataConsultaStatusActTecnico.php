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

$sql = "SELECT * from mantenimientos as mantos where idActividad='".$_GET['idActividad']."' and idResponsable=".$_GET['idResponsable']." and "
        . "trim(substr(mantos.fechaMantenimiento,1,9)) = '".$_GET['fechaMantenimiento']."' and idMaquina = ".$_GET['idMaquina'];

$res = mysqli_query($conn, $sql) or die("database error:". mysqli_error($conn));	
while( $row = mysqli_fetch_assoc($res) ) { 
  if ($row['condicion_maquina'] == "NO ATENDIDA") {
//    $row['text']=$row['condicion_maquina']."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <a href='actualizarMantenimientoTecnico3FromBtn/".$row['idFechaMantenimiento']."/"
//            .trim(substr($row['fechaMantenimiento'],7,3))."' class='btn btn-primary' >Atención Rápida </a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; "
//            ."<a href='actualizarMantenimientoTecnico3/".$row['idFechaMantenimiento']."/".$row['idMaquina']
//            ."' class='btn btn-primary' >Atención Normal </a>";
      
    $row['text']=$row['condicion_maquina']."&nbsp;&nbsp;&nbsp;<input type='button' class='btn btn-primary' ".
            "value='Atención Rápida' onclick='manttoRapido(".$row['idFechaMantenimiento'].")'/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; "
            ."<a href='actualizarMantenimientoTecnico3/".$row['idFechaMantenimiento']."/".$row['idMaquina']
            ."' class='btn btn-primary'>Atención Normal</a>";
      
  } else {
    $row['text']=$row['condicion_maquina']."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <a href='actualizarMantenimientoTecnico3/".$row['idFechaMantenimiento']."/".$row['idMaquina']
            ."' class='btn btn-primary' >Revisar </a>";
  }
  $row['name']=$row['fechaMantenimiento'];
  $data[] = $row;
}
echo json_encode($data);
?>

