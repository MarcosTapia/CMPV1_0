<?php
    function send_whatsapp($message){
        $phone="+4427216832";  // Enter your phone number here
        $apikey="430372";       // Enter your personal apikey received in step 3 above
        $url='https://api.callmebot.com/whatsapp.php?phone='.$phone.'&text='.$message.'&apikey='.$apikey;
        if($ch = curl_init($url)) {
            curl_setopt ($ch, CURLOPT_RETURNTRANSFER, true);
            $html = curl_exec($ch);
            $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            // echo "Output:".$html;  // you can print the output for troubleshooting
            curl_close($ch);
            return (int) $status;
        } else {
            return false;
        }
    }


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
    $fecha = $dt->format("Y-m-d H:i:s"); 
    $notificacionArray = explode("|",$_GET['notificacion']);
    
    //obitene nombre completo de usuario operador(lider)
    $sql1 = "SELECT * from usuariosoperadores where idUsuarioOperador=".$notificacionArray[1];
    $res1 = mysqli_query($conn, $sql1) or die("database error:". mysqli_error($conn));
    $operador = "";
    while($row1 = mysqli_fetch_assoc($res1) ) { 
        $operador = $row1['nombre']." ".$row1['apellido_paterno']." ".$row1['apellido_materno'];
    } 

    //obtiene el nombre de la maquina y su numero
    $sql2 = "SELECT * from maquinas where idMaquina=".$notificacionArray[2];
    $res2 = mysqli_query($conn, $sql2) or die("database error:". mysqli_error($conn));
    $maquina = "";
    while($row2 = mysqli_fetch_assoc($res2) ) { 
        $maquina = $row2['nombre_maquina']." ".$row2['numero_maquina'];
    } 
    
    //obtiene la descripcio de la falla
    $descripcionFalla = "";
    if ($notificacionArray[3] != "310") {
        $sql3 = "SELECT * from fallas_notificaciones where idFallaNotificacion=".$notificacionArray[3];
        $res3 = mysqli_query($conn, $sql3) or die("database error:". mysqli_error($conn));
        while($row3 = mysqli_fetch_assoc($res3) ) { 
            $descripcionFalla = $row3['descripcion'];
        } 
    } else {
        $descripcionFalla = $notificacionArray[5];
    }

    $mensaje = "Maquina: ".$maquina." Falla: ".$descripcionFalla." Prioridad: ".$notificacionArray[4];
    $autor = $operador;
    $lastId = 0;
    $sql = "INSERT INTO notificaciones (mensajeOperador,fechaEnvioNotificacionAlTecnico,idUsuarioTecnico,idUsuarioOperador,idFallaNotificacion,desc_falla,idMaqui,idProyecto) VALUES('" . $mensaje . "','"
            .$fecha."',".$notificacionArray[0].",".$notificacionArray[1].",".$notificacionArray[3].",'".$descripcionFalla."',".$notificacionArray[2].",".$notificacionArray[6].")";
    if (mysqli_query($conn, $sql) == TRUE) {
        //$last_id = mysqli_insert_id($conn);
        $lastId = mysqli_insert_id($conn);
        //grabastatusmaquina  layout_maq_status
        $sql = "update layout_maq_status set status=0,idNotificacion=".$lastId." where idMaq=".$notificacionArray[2];
        mysqli_query($conn, $sql);            
        
        //cambia status de tecnico para que no lo soliciten ni se hagan mas notificaciones
        $sql = "update turnos set status=1 where idUsuario=".$notificacionArray[0];
        mysqli_query($conn, $sql);            
    }
    
        //$conn->close();
    
    //envia mensage de whatsapp
    //yo
    //$phone='+5214427216832';
    //$apikey='430372';
    
    //luismi
    //$phone='+5217861166330';
    //$apikey='771719';
    
    //victorio
    //$phone='+5217861241196';
    //$apikey='566985';
    
    //ale garfias
    //$phone='+5217861191161';
    //$apikey='200616';
    
    //alexis
    //$phone='+5214471035992';
    //$apikey='613159';
    
    
//    $mensaje .= ". Por el momento contesta desde la aplicación de la empresa. Gracias.";
//    $url='https://api.callmebot.com/whatsapp.php?source=php&phone='.$phone.'&text='.urlencode($mensaje).'&apikey='.$apikey;
//    $html=file_get_contents($url);    
    
//    $myAudioFile = "servicio.wav";
//    echo '<audio autoplay="true" style="display:none;">
//         <source src="'.$myAudioFile.'" type="audio/wav">
//      </audio>';    
    
    echo json_encode($lastId);
?>

