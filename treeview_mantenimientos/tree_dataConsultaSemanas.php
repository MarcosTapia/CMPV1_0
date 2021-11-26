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

$sql = "SELECT * from mantenimientos as mantos inner join usuarios as u on mantos.idResponsable = u.idUsuario where idActividad=".$_GET['idActividad']." and mantos.idMaquina=".$_GET['idMaquina'];
$res = mysqli_query($conn, $sql) or die("database error:". mysqli_error($conn));	
while( $row = mysqli_fetch_assoc($res) ) { 
  //$row['text']="<p style='margin-top:-15px;margin-bottom:-3px;' onclick='muestraInfoSemanas(".$row['idActividad'].")'><img src='http://localhost/cmpv1_0/images/sistemaicons/adelante.ico' />&nbsp;&nbsp;&nbsp;".$row['descripcion_actividad']."</p>"; 
  
  $verificacion = "";
  if ($row['verificada'] == "")  {
//    $verificacion = "<a href='actualizarMantenimientoVerificacionLista/".$row['idFechaMantenimiento']."/1'>"
//            ."<img src='http://192.168.98.200/cmpv1_0/images/sistemaicons/persona.ico' alt='Por verificar' title='Por verificar' /></a>";
    
    $dt = new DateTime("now", new DateTimeZone('America/Mexico_City'));
    $fechaIngreso = $dt->format("Y-m-d H:i:s"); 
    $accion = "Verificada ".$fechaIngreso;      
    $verificacion = "<input type='button' value='Verificar' onclick='verificar(".$row['idFechaMantenimiento'].",\"".$accion."\")' class='btn btn-primary' />";
  } else {
//    $verificacion = "<a href='actualizarMantenimientoVerificacionLista/".$row['idFechaMantenimiento']."/0'>"
//            ."<img src='http://192.168.98.200/cmpv1_0/images/sistemaicons/ojobuscar.ico' alt='Verificada' title='Verificada' /></a>";
    $accion = "";
    $verificacion = "<input type='button' value='Verificada' onclick='verificar(".$row['idFechaMantenimiento'].",\"".$accion."\")' class='btn btn-primary' />";
  }
    
  $row['text']=$row['fechaMantenimiento']." - ".$row['condicion_maquina']." - ".$row['apellido_paterno']." ".$row['nombre']." ".$row['observaciones_maquina']
          .$verificacion; 
  $row['name']=$row['fechaMantenimiento'];
  $data[] = $row;
}
echo json_encode($data);
?>

