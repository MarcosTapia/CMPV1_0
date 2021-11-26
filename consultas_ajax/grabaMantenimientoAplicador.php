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
$fecha = $dt->format("Y_m_d_H_i_s"); 
$movimientoArray = explode("|",$_POST['movimiento']);

    //nombre Archivo idUsuario_id_Aplicador_fecha
$nombre_archivo = $movimientoArray[1]."_".$movimientoArray[0]."_".$fecha.".".$movimientoArray[7];
$ruta = $_SERVER['DOCUMENT_ROOT']."/CMPV1_0/microsecciones/".$nombre_archivo;
if ( 0 < $_FILES['file']['error'] ) {
    echo json_encode(0);
} else {
    move_uploaded_file($_FILES['file']['tmp_name'], $ruta);
    echo json_encode(1);
    
}

////ultimo golpes registrados del aplicador en cuestion
//$ultimoCiclos = 0;
//$sqlMantto = "SELECT * from movimientosaplicadores where idAplicador=".$movimientoArray[0]." ORDER BY ciclos DESC limit 1";
//$resMantto = mysqli_query($conn, $sqlMantto) or die("database error:". mysqli_error($conn));	
//while( $rowMantto = mysqli_fetch_assoc($resMantto) ) { 
//    $ultimoCiclos = $rowMantto['ciclos'];
//}

$mantenimientoArray = explode("|",$_POST['mantenimientoData']);

$fecha2 = $dt->format("Y:m:d H:i:s");

$sql = "insert into movimientosaplicadores(idAplicador ,idUsuario,fecha,observacionesMantto,verificacionesMantto,"
        . "ciclos,status,tipoMovimiento,verificacionesTipoMov,observacionesTipoMov,nombreMicroseccion) values(".$movimientoArray[0].",".$movimientoArray[1].",'".$fecha2."','".$mantenimientoArray[1]."','".$mantenimientoArray[0]."',"
        .$movimientoArray[2].",'HECHO','".$movimientoArray[3]."','".$movimientoArray[4]."','".$movimientoArray[5]."','".$nombre_archivo."')";
if ($conn->query($sql) === TRUE) {
    echo json_encode(1);
} else {
    echo json_encode(0);
}
$conn->close();
?>

