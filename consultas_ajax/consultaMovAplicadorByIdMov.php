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
$registro = "";
//$sqlMantto = "SELECT * from movimientosaplicadores where idMov=".$_GET['idMov'];
$sqlMantto = "SELECT * FROM `movimientosaplicadores` as ma inner join aplicadores as a on ma.idAplicador = a.idAplicador inner join usuariosherramentales as uh on ma.idUsuario=uh.idUsuario WHERE ma.idMov=".$_GET['idMov'];
$resMantto = mysqli_query($conn, $sqlMantto) or die("database error:". mysqli_error($conn));	
while( $rowMantto = mysqli_fetch_assoc($resMantto) ) { 
    $registro = $registro.$rowMantto['aplicador']."|".$rowMantto['apellido_paterno']." ".$rowMantto['apellido_materno']." ".$rowMantto['nombre']."|"
            .$rowMantto['fecha']."|"
            .$rowMantto['observacionesMantto']."|"
            .$rowMantto['verificacionesMantto']."|"
            .$rowMantto['ciclos']."|"
            .$rowMantto['status']."|"
            .$rowMantto['tipoMovimiento']."|"
            .$rowMantto['verificacionesTipoMov']."|"
            .$rowMantto['observacionesTipoMov']."|";
    break;
} 
echo json_encode($registro);

/*
	idAplicador,idUsuario,fecha,observacionesMantto,verificacionesMantto
        ,ciclos,status,tipoMovimiento,verificacionesTipoMov,observacionesTipoMov,nombreMicroseccion

*/


?>

