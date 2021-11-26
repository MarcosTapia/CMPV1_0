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

$sql = "SELECT distinct(`nombre_maquina`) FROM `maquinas`";
$res = mysqli_query($conn, $sql) or die("database error:". mysqli_error($conn));	
$numerosMaquinas = array();
$semanasMantenimientos = array();
$informacion = array();
while( $row = mysqli_fetch_assoc($res) ) { 
  //llena para primer nivel del treeview  
  $row['text']=$row['nombre_maquina']; 
  $row['name']=$row['nombre_maquina']; 
    //para segundo nivel de tree view
    $sql2 = "SELECT distinct(`numero_maquina`),idMaquina,nombre_maquina FROM `maquinas` where nombre_maquina = '".$row['nombre_maquina']."'";
    $res2 = mysqli_query($conn, $sql2) or die("database error:". mysqli_error($conn));	
    while( $row2 = mysqli_fetch_assoc($res2) ) { 
        $row2['text']="<h5 style='margin-left: 42px;margin-top: -15px;margin-bottom: 5px;' onclick='muestraInfo(1,".$row2['idMaquina'].",\"".$row2['nombre_maquina']."\",this)'>".$row2['numero_maquina']."</h5>"; 
        //$row2['text']=$row2['numero_maquina']; 
        $row2['name']=$row2['idMaquina'];

//        //para tercer nivel de tree view
//        $sql3 = "SELECT * FROM mantenimientos WHERE idMaquina=".$row2['idMaquina'];
//        $res3 = mysqli_query($conn, $sql3) or die("database error:". mysqli_error($conn));
//        while( $row3 = mysqli_fetch_assoc($res3) ) { 
//            $row3['text']=$row3['fechaMantenimiento']."".$row3['idFechaMantenimiento'];
//            $row3['name']=$row3['fechaMantenimiento'];
//            
////            $sql4 = "SELECT * FROM mantenimientos as mtos inner join maquinas as maq on mtos.idMaquina = maq.idMaquina "
////                ."inner join usuarios as u on mtos.idResponsable = u.idUsuario inner join actividades as act "
////                ."on mtos.idActividad = act.idActividad WHERE mtos.idFechaMantenimiento=".$row3['fechaMantenimiento'];
////            $res4 = mysqli_query($conn, $sql3) or die("database error:". mysqli_error($conn));
////            while( $row4 = mysqli_fetch_assoc($res4) ) { 
////                $row4['text']=$row4['descripcion_actividad']; 
////                $row4['name']=$row4['fechaMantenimiento'];
////                
////                array_push($informacion,$row4);        
////            }
////            $row3['nodes']=$informacion; 
////            $informacion = [];
//            
//            array_push($semanasMantenimientos,$row3);        
//        }
//        $row2['nodes']=$semanasMantenimientos; 
//        $semanasMantenimientos = [];

        array_push($numerosMaquinas,$row2);
    }
  $row['nodes']=$numerosMaquinas; 
  $dataOrigen[] = $row;
  $numerosMaquinas = [];
        
}



////guarda solo las maquinas diferentes
//$maquinasSinRepeticion = array('-2');
//$data2 = array('-2');
//$itemsByReference = array();
//$final = array();
//for($i=0; $i<sizeof($dataOrigen);$i++) { 
//    $w = array_search($dataOrigen[$i]['nombre_maquina'],$maquinasSinRepeticion);
//    if ($w == false){
//        array_push($maquinasSinRepeticion,$dataOrigen[$i]['nombre_maquina']);
//        array_push($data2,$dataOrigen[$i]);
//        //$itemsByReference[$item['id']] = &$item;
//    }
//    array_push($final,$dataOrigen[$i]['nombre_maquina']);
//} 



//$itemsByReference = array();
//// Build array of item references:
//foreach($data as $key => &$item) {
//   $itemsByReference[$item['id']] = &$item;
//}
//// Set items as children of the relevant parent item.
//foreach($data as $key => &$item)  {    
//   if($item['parent_id'] && isset($itemsByReference[$item['parent_id']])) {
//	  $itemsByReference [$item['parent_id']]['nodes'][] = &$item;
//	}
//}
//// Remove items that were added to parents elsewhere:
//foreach($data as $key => &$item) {
//   if($item['parent_id'] && isset($itemsByReference[$item['parent_id']]))
//	  unset($data[$key]);
//}
// Encode:
//echo json_encode(sizeof($data2)." - ".sizeof($final));

//SELECT m.idMaquina,maq.nombre_maquina,maq.numero_maquina FROM `mantenimientos` as m inner join maquinas as maq on m.idMaquina = maq.idMaquina where maq.nombre_maquina = "Schleuniger"
echo json_encode($dataOrigen);



/*  Anterior 
/* Database connection start 
$servername = "localhost";
$username = "root";
$password = "kikinalba";
$dbname = "treeview";
$conn = mysqli_connect($servername, $username, $password, $dbname) or die("Connection failed: " . mysqli_connect_error());
if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}


$reg;
$sql = "SELECT id, name, text, link as href, parent_id FROM treeview";
$res = mysqli_query($conn, $sql) or die("database error:". mysqli_error($conn));	
//iterate on results row and create new index array of data
while( $row = mysqli_fetch_assoc($res) ) { 
  //Agrego parent_id a maquinas
  //$row['parent_id']="1";  
  $data[] = $row;
  $reg = $row;
}
$itemsByReference = array();
// Build array of item references:
foreach($data as $key => &$item) {
   $itemsByReference[$item['id']] = &$item;
}
// Set items as children of the relevant parent item.
foreach($data as $key => &$item)  {    
   if($item['parent_id'] && isset($itemsByReference[$item['parent_id']])) {
	  $itemsByReference [$item['parent_id']]['nodes'][] = &$item;
	}
}
// Remove items that were added to parents elsewhere:
foreach($data as $key => &$item) {
   if($item['parent_id'] && isset($itemsByReference[$item['parent_id']]))
	  unset($data[$key]);
}
// Encode: 
//echo json_encode($data);
echo json_encode($data);*/


?>

