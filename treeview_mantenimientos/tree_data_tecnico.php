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
    
    //Llena array Actividades
    $actividadesArray = array();
    $i = 0;
    $sqlAct = "SELECT * from actividades";
    $resAct = mysqli_query($conn, $sqlAct) or die("database error:". mysqli_error($conn));	
    while( $rowAct = mysqli_fetch_assoc($resAct) ) { 
        $actividadesArray[$rowAct['descripcion_actividad']."|".$i."|"] = "".$rowAct['idActividad'];
        $i++;
    } 
    
    //Llena array Maquinas
    $maquinasArray = array();
    $j = 0;
    $sqlMaq = "SELECT * from maquinas";
    $resMaq = mysqli_query($conn, $sqlMaq) or die("database error:". mysqli_error($conn));	
    while( $rowMaq = mysqli_fetch_assoc($resMaq) ) { 
        $maquinasArray[$rowMaq['nombre_maquina']."|".$rowMaq['numero_maquina']."|".$j."|"] = "".$rowMaq['idMaquina'];
        $j++;
    } 

    $nombreMaquinas = array();
    $numerosMaquinas = array();
    $semanasMantenimientos = array();
    $informacion = array();
    $elementos = array();
    $maquinasRepetidas = array("-2");
    $numerosRepetidos = array("-2");
    //selecciona fechas del mes por fecha y responsable
    //nota se cambio la segunda condicion del where por solo < antes <= para la semana del fin de mes
    //esta muestra mayor o iguales a la semana
//    $sql = "SELECT distinct(trim(substr(`fechaMantenimiento`,1,9))) as fechaMantenimiento FROM mantenimientos "
//            . "WHERE trim(substr(`fechaMantenimiento`,8,2)) >= "
//            . "(SELECT WEEK('".$_GET['diaIniMes']."')) and trim(substr(`fechaMantenimiento`,8,2)) < "
//            . "(SELECT WEEK('".$_GET['diaFinMes']."')) and idResponsable = '".$_GET['idUsuario']."' order by `fechaMantenimiento`";

    //esta muestra iguales a la semana
    $sql = "SELECT distinct(trim(substr(`fechaMantenimiento`,1,9))) as fechaMantenimiento FROM mantenimientos "
            . "WHERE trim(substr(`fechaMantenimiento`,8,2)) = "
            . "(SELECT WEEK('".$_GET['diaIniMes']."')) and idResponsable = '".$_GET['idUsuario']."' order by `fechaMantenimiento`";
    
    
    $res = mysqli_query($conn, $sql) or die("database error:". mysqli_error($conn));	
    while( $row = mysqli_fetch_assoc($res) ) { 
            $row['text']=trim(substr($row['fechaMantenimiento'],0,9));
            
            //selecciona maquinas a traves de los mantenimientos señalados por la semana y el responsable comparando con el array de maquinas
            $sql2 = "SELECT * from mantenimientos where idResponsable = ".$_GET['idUsuario']." and trim(substr(fechaMantenimiento,1,9)) = '".$row['fechaMantenimiento']."'";
            $res2 = mysqli_query($conn, $sql2) or die("database error:". mysqli_error($conn));	
            $maquinasRepetidas = [];
            $maquinasRepetidas = array("-2");
            while( $row2 = mysqli_fetch_assoc($res2) ) { 
                
                $posicionContenidoMaquina = array_search($row2['idMaquina'],$maquinasArray);
                $elemArrayMaq = explode("|", $posicionContenidoMaquina);
                $v = array_search($elemArrayMaq[0],$maquinasRepetidas);
                if ($v == false){
                    array_push($maquinasRepetidas,$elemArrayMaq[0]);    
                    $row2['text'] = $elemArrayMaq[0]; 
                    
                    $sql3 = "SELECT numero_maquina,idMaquina from maquinas where nombre_maquina = '".$elemArrayMaq[0]."' and idMaquina in "
                            ."(SELECT idMaquina from mantenimientos where idResponsable = "
                            .$_GET['idUsuario']." and trim(substr(fechaMantenimiento,1,9)) = '".$row['fechaMantenimiento']."')";
                    $res3 = mysqli_query($conn, $sql3) or die("database error:". mysqli_error($conn));
                    while( $row3 = mysqli_fetch_assoc($res3) ) { 
                        $w = array_search($row3['numero_maquina'],$numerosRepetidos);
                        if ($w == false){
                            array_push($numerosRepetidos,$row3['numero_maquina']);    
                            $row3['text'] = "<h5 style='margin-left: 42px;margin-top: -15px;margin-bottom: 5px;' onclick='muestraInfo(1,".$row3['idMaquina'].",\"".$elemArrayMaq[0]."\",\"".$row['fechaMantenimiento']."\",this)'>".$row3['numero_maquina']."</h5>";
                            //$row3['text'] = $row3['numero_maquina'];
                            array_push($numerosMaquinas,$row3);
                        }
                        
                    }
                    $numerosRepetidos = [];
                    $numerosRepetidos = array("-2");
                    $row2['nodes'] = $numerosMaquinas;
                    array_push($nombreMaquinas,$row2);
                    $numerosMaquinas = [];
                    
                }
                
                
                
                
                
            
            
                
                
                //"<img src='http://192.168.98.200/cmpv1_0/images/sistemaicons/adelante.ico' />&nbsp;&nbsp;&nbsp;".

//                      $row3['text'] = "<h5 style='margin-left: 42px;margin-top: -15px;margin-bottom: 5px;' onclick='muestraInfo(1,\"".$row['fechaMantenimiento']."\",\"".$row2['nombre_maquina']."\",this)'>".$row3['numero_maquina']."</h5>"; 
//                      $row3['name'] = $row3['numero_maquina'];
//                      array_push($numerosMaquinas,$row3);
//                  }                  
                  
//                  if (count($numerosMaquinas) > 0) {
//                        $row2['text'] = $row2['nombre_maquina']; 
//                        $row2['name'] = $row2['nombre_maquina'];
//                      $row2['nodes'] = $numerosMaquinas; 
//                  }
//                  
//                  array_push($nombreMaquinas,$row2);
//            }
            
            
//            if (count($nombreMaquinas) > 0) {
//                $row['nodes'] = $nombreMaquinas; 
//            }
        }    
        $row2['nodes'] = $numerosMaquinas;
        $row['nodes'] = $nombreMaquinas;
        $dataOrigen[] = $row;
        $nombreMaquinas = [];
    }
    echo json_encode($dataOrigen);
?>

