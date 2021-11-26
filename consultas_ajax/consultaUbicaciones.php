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
$sql = "SELECT * from layout_maq_status";
$res = mysqli_query($conn, $sql) or die("database error:". mysqli_error($conn));	
$resultado = "";
while( $row = mysqli_fetch_assoc($res) ) { 
    $resultado.=$row['control']."|".$row['status']."|@@";
}
echo json_encode($resultado);
?>

